<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\BiomedicalServiceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FacilityDashboardController extends Controller
{
    public function index(): View
    {
        $facilityId = Auth::user()->currentFacility?->id;

        $requests = BiomedicalServiceRequest::where('facility_id', $facilityId)->get();
        $total = $requests->count();

        $statusCounts = [
            'pending' => $requests->where('status', 'pending')->count(),
            'in_progress' => $requests->whereIn('status', ['assigned', 'in_progress'])->count(),
            'completed' => $requests->where('status', 'completed')->count(),
        ];

        $pct = fn (int $count) => $total > 0 ? (int) round($count / $total * 100) : 0;

        $stats = [
            'total' => $total,
            'new_this_week' => $requests->where('created_at', '>=', now()->subWeek())->count(),
            'in_progress' => $statusCounts['in_progress'],
            'in_progress_pct' => $pct($statusCounts['in_progress']),
            'pending' => $statusCounts['pending'],
            'pending_pct' => $pct($statusCounts['pending']),
            'completed' => $statusCounts['completed'],
            'completed_pct' => $pct($statusCounts['completed']),
        ];

        // Last 6 months, stacked by service type — feeds the bar chart.
        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i));
        $chartData = [
            'months' => $months->map(fn ($m) => $m->format('M'))->all(),
            'maintenance' => [],
            'calibration' => [],
            'repair' => [],
            'other' => [],
        ];
        foreach ($months as $month) {
            $monthly = $requests->filter(
                fn ($r) => $r->created_at->isSameMonth($month) && $r->created_at->isSameYear($month)
            );
            $chartData['maintenance'][] = $monthly->where('service_type', 'preventive_maintenance')->count();
            $chartData['calibration'][] = $monthly->where('service_type', 'calibration')->count();
            $chartData['repair'][] = $monthly->where('service_type', 'repair')->count();
            $chartData['other'][] = $monthly->whereIn('service_type', ['installation', 'other'])->count();
        }

        // Donut breakdown by service type.
        $typeMeta = [
            'preventive_maintenance' => ['label' => 'Maintenance', 'color' => '#2F6FE4'],
            'calibration' => ['label' => 'Calibration', 'color' => '#2F9E5B'],
            'repair' => ['label' => 'Repair', 'color' => '#D99A3D'],
            'installation' => ['label' => 'Other', 'color' => '#8B6FD9'],
            'other' => ['label' => 'Other', 'color' => '#8B6FD9'],
        ];
        $serviceTypeBreakdown = collect($typeMeta)
            ->pluck('label')
            ->unique()
            ->map(function ($label) use ($requests, $typeMeta, $total) {
                $types = collect($typeMeta)->filter(fn ($m) => $m['label'] === $label)->keys();
                $count = $requests->whereIn('service_type', $types)->count();

                return [
                    'label' => $label,
                    'color' => collect($typeMeta)->firstWhere('label', $label)['color'],
                    'count' => $count,
                    'pct' => $total > 0 ? (int) round($count / $total * 100) : 0,
                ];
            })
            ->filter(fn ($row) => $row['count'] > 0)
            ->values()
            ->all();

        $statusStyle = [
            'pending' => ['bg' => '#FDF3E3', 'fg' => '#B5791F', 'label' => 'Pending Approval'],
            'assigned' => ['bg' => '#E9F1FE', 'fg' => '#2F6FE4', 'label' => 'In Progress'],
            'in_progress' => ['bg' => '#E9F1FE', 'fg' => '#2F6FE4', 'label' => 'In Progress'],
            'completed' => ['bg' => '#E7F6EC', 'fg' => '#2F9E5B', 'label' => 'Completed'],
            'cancelled' => ['bg' => '#F1F1F1', 'fg' => '#64748B', 'label' => 'Cancelled'],
        ];

        $recentRequests = $requests
            ->sortByDesc('created_at')
            ->take(5)
            ->map(fn ($r) => [
                'ref' => 'SR-'.str_pad((string) $r->id, 4, '0', STR_PAD_LEFT),
                'equipment' => $r->equipment_name,
                'service_type' => BiomedicalServiceRequest::SERVICE_TYPES[$r->service_type] ?? $r->service_type,
                'status_bg' => $statusStyle[$r->status]['bg'] ?? '#F1F1F1',
                'status_fg' => $statusStyle[$r->status]['fg'] ?? '#64748B',
                'status_label' => $statusStyle[$r->status]['label'] ?? ucfirst($r->status),
                'requested_on' => $r->created_at->format('d M Y'),
            ])
            ->values();

        // Equipment overdue for service. Adjust to your actual Equipment
        // model/table once it exists — this reads from the most recent
        // completed request per piece of equipment as a placeholder signal.
        $equipmentNeedingService = $requests
            ->where('status', 'completed')
            ->groupBy('equipment_name')
            ->map(function ($group, $name) {
                $latest = $group->sortByDesc('updated_at')->first();

                return [
                    'name' => $name,
                    'type' => BiomedicalServiceRequest::SERVICE_TYPES[$latest->service_type] ?? '—',
                    'days_since_service' => $latest->updated_at->diffInDays(now()),
                ];
            })
            ->sortByDesc('days_since_service')
            ->take(5)
            ->values();

        $quickLinks = [
            [
                'label' => 'View My Requests', 'sub' => 'Track status of your requests',
                'url' => route('facility.service-requests.index'), 'bg' => '#E9F1FE', 'fg' => '#2F6FE4',
                'icon_path' => '<path d="M8 6h12M8 12h12M8 18h12M4 6h.01M4 12h.01M4 18h.01" stroke-linecap="round"/>',
            ],
            [
                'label' => 'Equipment Inventory', 'sub' => 'Check equipment details & warranty',
                'url' => route('facility.equipment.index'), 'bg' => '#E7F6EC', 'fg' => '#2F9E5B',
                'icon_path' => '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M4 10h16M10 4v16"/>',
            ],
            [
                'label' => 'Service Providers', 'sub' => 'Manage approved providers',
                'url' => route('facility.providers.index'), 'bg' => '#FDF3E3', 'fg' => '#D99A3D',
                'icon_path' => '<circle cx="9" cy="8" r="3"/><path d="M3 20c0-3.5 2.5-6 6-6s6 2.5 6 6" stroke-linecap="round"/>',
            ],
            [
                'label' => 'Generate Report', 'sub' => 'View service analytics and history',
                'url' => route('facility.reports.index'), 'bg' => '#F1EAFB', 'fg' => '#8B6FD9',
                'icon_path' => '<path d="M4 20V4M4 20h16" stroke-linecap="round"/><path d="M8 16v-4M13 16V8M18 16v-7" stroke-linecap="round"/>',
            ],
        ];

        // Placeholder feed — wire up to a real activity log once one exists.
        $recentActivity = $requests
            ->sortByDesc('updated_at')
            ->take(4)
            ->map(fn ($r) => [
                'color' => $statusStyle[$r->status]['fg'] ?? '#64748B',
                'text' => match ($r->status) {
                    'completed' => "Service completed for {$r->equipment_name}",
                    'assigned', 'in_progress' => "Request for {$r->equipment_name} assigned to an engineer",
                    default => "Service request created for {$r->equipment_name}",
                },
                'time' => $r->updated_at->diffForHumans(),
            ])
            ->values();

        $notifications = $requests
            ->whereIn('status', ['assigned', 'in_progress', 'completed'])
            ->sortByDesc('updated_at')
            ->take(8)
            ->map(fn ($request) => [
                'id' => 'facility-request-'.$request->id,
                'category' => 'service-request',
                'title' => match ($request->status) {
                    'completed' => 'Service request completed',
                    'in_progress' => 'Service request in progress',
                    default => 'Engineer assigned to request',
                },
                'message' => $request->equipment_name,
                'url' => route('facility.dashboard').'#facility-requests',
                'time' => $request->updated_at?->diffForHumans() ?? 'just now',
                'created_at' => $request->updated_at,
            ])
            ->values();

        return view('HealthyFacility.dashboard', [
            'stats' => $stats,
            'chartData' => $chartData,
            'serviceTypeBreakdown' => $serviceTypeBreakdown,
            'recentRequests' => $recentRequests,
            'equipmentNeedingService' => $equipmentNeedingService,
            'quickLinks' => $quickLinks,
            'recentActivity' => $recentActivity,
            'notifications' => $notifications,
            'facilityUserName' => Auth::user()->name,
        ]);
    }
}
