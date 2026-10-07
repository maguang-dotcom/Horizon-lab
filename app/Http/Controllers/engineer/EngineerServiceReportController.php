<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use App\Models\BiomedicalServiceRequest;
use App\Models\ServiceReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EngineerServiceReportController extends Controller
{
    public function create(BiomedicalServiceRequest $serviceRequest): View
    {
        $this->authorizeAssignment($serviceRequest);

        return view('Engineer.CreateEngineer', [
            'serviceRequest' => $serviceRequest->load(['facility', 'report.parts']),
            'report' => $serviceRequest->report,
        ]);
    }

    public function store(Request $request, BiomedicalServiceRequest $serviceRequest): RedirectResponse
    {
        $this->authorizeAssignment($serviceRequest);

        $data = $request->validate([
            'problem_found' => ['required', 'string', 'max:3000'],
            'work_done' => ['nullable', 'string', 'max:3000'],
            'labor_hours' => ['required', 'numeric', 'min:0', 'max:999'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
            'parts' => ['nullable', 'array'],
            'parts.*.name' => ['required_with:parts.*.unit_cost', 'nullable', 'string', 'max:255'],
            'parts.*.quantity' => ['nullable', 'integer', 'min:1'],
            'parts.*.unit_cost' => ['nullable', 'numeric', 'min:0'],
            'mark_completed' => ['nullable', 'boolean'],
        ]);

        $parts = collect($data['parts'] ?? [])
            ->filter(fn ($p) => filled($p['name'] ?? null))
            ->map(function ($p) {
                $qty = (int) ($p['quantity'] ?? 1);
                $cost = (float) ($p['unit_cost'] ?? 0);

                return ['name' => $p['name'], 'quantity' => $qty, 'unit_cost' => $cost, 'line_total' => $qty * $cost];
            });

        $laborCost = round($data['labor_hours'] * $data['hourly_rate'], 2);
        $partsCost = round($parts->sum('line_total'), 2);

        DB::transaction(function () use ($serviceRequest, $data, $parts, $laborCost, $partsCost, $request) {
            $report = ServiceReport::updateOrCreate(
                ['biomedical_service_request_id' => $serviceRequest->id],
                [
                    'engineer_id' => Auth::id(),
                    'problem_found' => $data['problem_found'],
                    'work_done' => $data['work_done'] ?? null,
                    'labor_hours' => $data['labor_hours'],
                    'hourly_rate' => $data['hourly_rate'],
                    'labor_cost' => $laborCost,
                    'parts_cost' => $partsCost,
                    'total_cost' => $laborCost + $partsCost,
                ]
            );

            $report->parts()->delete();
            $report->parts()->createMany($parts->all());

            $serviceRequest->update([
                'status' => $request->boolean('mark_completed') ? 'completed' : 'in_progress',
            ]);
        });

        return redirect()->route('engineer.dashboard')
            ->with('status', 'Report saved. Total estimate: '.number_format($laborCost + $partsCost).' '.config('app.currency', 'UGX'));
    }

    private function authorizeAssignment(BiomedicalServiceRequest $serviceRequest): void
    {
        abort_unless($serviceRequest->engineer_id === Auth::id(), 403, 'This request is not assigned to you.');
    }
}
