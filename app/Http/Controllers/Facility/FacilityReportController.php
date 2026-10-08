<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\BiomedicalServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class FacilityReportController extends Controller
{
    /**
     * Show graphs summarising the facility's service requests.
     */
    public function index(Request $request): View
    {
        $facility = $request->user()->currentFacility;

        $requests = $facility
            ? BiomedicalServiceRequest::query()
                ->where('facility_id', $facility->id)
                ->get(['service_type', 'urgency', 'status', 'created_at'])
            : collect();

        $months = collect(range(5, 0))->map(fn (int $ago) => now()->startOfMonth()->subMonths($ago));

        $reportData = [
            'total' => $requests->count(),
            'byType' => $this->breakdown($requests, 'service_type', BiomedicalServiceRequest::SERVICE_TYPES),
            'byUrgency' => $this->breakdown($requests, 'urgency', BiomedicalServiceRequest::URGENCY_LEVELS),
            'byStatus' => $this->breakdown($requests, 'status'),
            'monthly' => [
                'labels' => $months->map(fn ($month) => $month->format('M Y'))->all(),
                'counts' => $months->map(
                    fn ($month) => $requests->filter(fn ($item) => $item->created_at->isSameMonth($month))->count()
                )->all(),
            ],
        ];

        return view('HealthyFacility.reports', ['reportData' => $reportData]);
    }

    /**
     * @param  Collection<int, BiomedicalServiceRequest>  $requests
     * @param  array<string, string>|null  $labels
     * @return array{labels: list<string>, counts: list<int>}
     */
    private function breakdown(Collection $requests, string $column, ?array $labels = null): array
    {
        $counts = $requests->countBy($column);
        $keys = $labels ? array_keys($labels) : $counts->keys()->all();

        return [
            'labels' => array_map(
                fn (string $key) => $labels ? strtok($labels[$key], ' —') : ucfirst(str_replace('_', ' ', $key)),
                $keys
            ),
            'counts' => array_map(fn (string $key) => (int) $counts->get($key, 0), $keys),
        ];
    }
}
