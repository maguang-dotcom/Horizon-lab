<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use App\Models\BiomedicalServiceRequest;
use App\Models\ServiceReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EngineerDashboardController extends Controller
{
    public function index(): View
    {
        $engineerId = Auth::id();

        $requests = BiomedicalServiceRequest::with(['facility', 'report'])
            ->where('engineer_id', $engineerId)
            ->orderByRaw("CASE urgency WHEN 'critical' THEN 1 WHEN 'high' THEN 2 WHEN 'normal' THEN 3 WHEN 'low' THEN 4 ELSE 5 END")
            ->latest('assigned_at')
            ->get();

        $open = $requests->whereIn('status', ['assigned', 'in_progress']);

        $completedThisMonth = $requests
            ->where('status', 'completed')
            ->filter(fn ($r) => $r->updated_at && $r->updated_at->isCurrentMonth())
            ->count();

        $assignedFacilities = $requests
            ->whereIn('status', ['assigned', 'in_progress', 'completed'])
            ->groupBy('facility_id')
            ->map(fn ($jobs) => [
                'name' => $jobs->first()->facility->name ?? 'Unknown facility',
                'count' => $jobs->count(),
            ])->values()->all();

        $assignments = $requests->take(10)->map(fn ($r) => [
            'request_id' => $r->id,
            'facility' => $r->facility->name ?? 'Unknown facility',
            'city' => $r->facility->district ?? $r->facility->address ?? '',
            'equipment' => $r->equipment_name ?? $r->equipment ?? $r->title ?? 'Equipment',
            'category' => $r->equipment_type ?? $r->category ?? $r->service_type ?? '',
            'priority' => $r->urgency ?? 'normal',
            'status' => $r->status,
        ])->all();

        $reports = ServiceReport::with(['request.facility'])
            ->where('engineer_id', $engineerId)
            ->latest()
            ->take(10)
            ->get();

        $notifications = $requests
            ->where('status', 'assigned')
            ->take(8)
            ->map(fn (BiomedicalServiceRequest $request) => [
                'id' => 'engineer-assignment-'.$request->id,
                'category' => 'assignment',
                'title' => 'New service assignment',
                'message' => ($request->facility?->name ?? 'Facility').' · '.$request->equipment_name,
                'url' => route('engineer.dashboard').'#assignments',
                'time' => $request->assigned_at?->diffForHumans() ?? 'just now',
                'created_at' => $request->assigned_at,
            ])
            ->values();

        return view('engineer.dashboard', [
            'engineerName' => Auth::user()->name,
            'activeCount' => $open->count(),
            'urgentCount' => $open->whereIn('urgency', ['critical', 'high'])->count(),
            'completedThisMonth' => $completedThisMonth,
            'totalEstimated' => (float) ServiceReport::where('engineer_id', $engineerId)->sum('total_cost'),
            'assignedFacilities' => $assignedFacilities,
            'assignments' => $assignments,
            'reports' => $reports,
            'notifications' => $notifications,
        ]);
    }
}
