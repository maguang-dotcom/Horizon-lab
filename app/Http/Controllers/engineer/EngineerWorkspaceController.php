<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use App\Models\BiomedicalServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EngineerWorkspaceController extends Controller
{
    private const STATUSES = [
        'assigned' => 'Not yet started',
        'in_progress' => 'In progress',
        'completed' => 'Completed',
    ];

    public function assignments(Request $request): View
    {
        $base = BiomedicalServiceRequest::where('engineer_id', Auth::id());

        $notStarted = (clone $base)->where('status', 'assigned')->count();
        $inProgress = (clone $base)->where('status', 'in_progress')->count();

        $filter = $request->query('status');
        $statuses = in_array($filter, ['assigned', 'in_progress'], true) ? [$filter] : ['assigned', 'in_progress'];

        $assignments = (clone $base)
            ->with('facility')
            ->whereIn('status', $statuses)
            ->orderByRaw("CASE urgency WHEN 'critical' THEN 1 WHEN 'high' THEN 2 WHEN 'normal' THEN 3 WHEN 'low' THEN 4 ELSE 5 END")
            ->latest('assigned_at')
            ->get();

        return view('engineer.assignments', [
            'assignments' => $assignments,
            'notStarted' => $notStarted,
            'inProgress' => $inProgress,
            'totalPending' => $notStarted + $inProgress,
            'filter' => in_array($filter, ['assigned', 'in_progress'], true) ? $filter : 'all',
            'activeCount' => $notStarted + $inProgress,
        ]);
    }

    public function facilities(): View
    {
        $requests = BiomedicalServiceRequest::with('facility')
            ->where('engineer_id', Auth::id())
            ->get();

        $facilities = $requests->groupBy('facility_id')->map(function ($jobs) {
            $facility = $jobs->first()->facility;

            return [
                'name' => $facility?->name ?? 'Unknown facility',
                'location' => $facility?->district ?: ($facility?->address ?? ''),
                'phone' => $facility?->contact_phone,
                'total' => $jobs->count(),
                'open' => $jobs->whereIn('status', ['assigned', 'in_progress'])->count(),
                'completed' => $jobs->where('status', 'completed')->count(),
            ];
        })->sortBy('name')->values();

        return view('engineer.facilities', [
            'facilities' => $facilities,
            'activeCount' => $requests->whereIn('status', ['assigned', 'in_progress'])->count(),
        ]);
    }

    public function reports(): View
    {
        $requests = BiomedicalServiceRequest::where('engineer_id', Auth::id())->get();

        $months = collect(range(11, 0))->map(fn (int $ago) => Carbon::now()->startOfMonth()->subMonths($ago));

        $series = $months->map(function (Carbon $month) use ($requests) {
            $inMonth = $requests->filter(
                fn ($r) => ($r->assigned_at ?? $r->created_at)?->isSameMonth($month)
            );

            return [
                'label' => $month->format('M Y'),
                'assigned' => $inMonth->where('status', 'assigned')->count(),
                'in_progress' => $inMonth->where('status', 'in_progress')->count(),
                'completed' => $inMonth->where('status', 'completed')->count(),
            ];
        })->values();

        return view('engineer.report-charts', [
            'series' => $series,
            'statuses' => self::STATUSES,
            'totals' => collect(self::STATUSES)->map(fn ($label, $key) => $requests->where('status', $key)->count()),
            'maxMonth' => max(1, $series->max(fn ($m) => $m['assigned'] + $m['in_progress'] + $m['completed'])),
            'activeCount' => $requests->whereIn('status', ['assigned', 'in_progress'])->count(),
        ]);
    }
}
