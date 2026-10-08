@extends('layouts.hospital')

@section('title', 'Dashboard')

@push('styles')
<style>
    .section-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
    .section-title { display:flex; align-items:center; gap:10px; font-size:16px; font-weight:600; }
    .section-sub { font-size:12px; color:#64748B; margin-top:2px; }
    .icon-btn { width:32px; height:32px; border-radius:8px; background:#EEF3FD; color:var(--blue); display:flex; align-items:center; justify-content:center; }
    .view-all { display:inline-flex; align-items:center; gap:4px; font-size:12px; font-weight:500; color:var(--blue); }
    .view-all:hover { text-decoration:underline; }

    .stat { border-radius:12px; padding:18px; border:1px solid var(--bd); background:var(--bg); }
    .stat-icon { width:44px; height:44px; border-radius:999px; background:var(--fg); color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .t-blue   { --bg:#EDF3FF; --bd:#D5E3FF; --fg:#1F5EE8; }
    .t-violet { --bg:#F3EEFF; --bd:#E3D8FB; --fg:#490dc3; }
    .t-orange { --bg:#FFF5EA; --bd:#FBE2C4; --fg:#3404e1; }
    .t-green  { --bg:#EAF8F0; --bd:#CDEBD9; --fg:#2e0564; }

    .data-table { width:100%; font-size:13px; border-collapse:collapse; }
    .data-table thead th { background:#F4F7FC; color:#334155; font-weight:600; font-size:12px; text-align:left; padding:10px 12px; }
    .data-table tbody td { padding:11px 12px; border-top:1px solid var(--line); }
    .badge { display:inline-flex; align-items:center; gap:6px; padding:3px 10px; border-radius:999px; font-size:12px; font-weight:500; white-space:nowrap; }
    .badge::before { content:''; width:8px; height:8px; border-radius:999px; background:currentColor; }
    .badge-days { display:inline-block; padding:3px 10px; border-radius:999px; font-size:12px; font-weight:600; background:#FDE8E8; color:#D62F2F; }

    .quick { display:flex; align-items:center; gap:12px; padding:12px 0; }
    .quick + .quick { border-top:1px solid var(--line); }
    .quick-icon { width:40px; height:40px; border-radius:999px; background:#EAF1FF; color:var(--blue); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .quick:hover .quick-title { color:var(--blue); }

    .timeline { position:relative; }
    .timeline::before { content:''; position:absolute; left:5px; top:8px; bottom:8px; width:1px; background:var(--line); }
    .timeline li { position:relative; padding-left:28px; }
    .timeline .dot { position:absolute; left:0; top:4px; width:11px; height:11px; border-radius:999px; box-shadow:0 0 0 3px #fff; }

    .empty { text-align:center; color:#94A3B8; font-size:13px; padding:26px 0; }
</style>
@endpush

@section('content')

@php
    $stats                 = $stats ?? [];
    $serviceTypeBreakdown  = $serviceTypeBreakdown ?? [];
    $recentRequests        = $recentRequests ?? [];
    $equipmentNeedingService = $equipmentNeedingService ?? [];
    $recentActivity        = $recentActivity ?? [];
    $chartData             = $chartData ?? ['months'=>[], 'maintenance'=>[], 'calibration'=>[], 'repair'=>[], 'other'=>[]];

    $statCards = [
        ['Total Requests',   $stats['total'] ?? 0,       '<span class="text-green-600">↑ +'.($stats['new_this_week'] ?? 0).' this week</span>', 't-blue',   '<path d="M8 3h8l3 3v15H5V3h3Z M9 12h6M9 16h6"/>'],
        ['In Progress',      $stats['in_progress'] ?? 0, ($stats['in_progress_pct'] ?? 0).'% of total', 't-violet', '<circle cx="12" cy="12" r="8"/><path d="M12 8v4l3 2"/>'],
        ['Pending Approval', $stats['pending'] ?? 0,     ($stats['pending_pct'] ?? 0).'% of total',     't-orange', '<circle cx="12" cy="12" r="8"/><path d="M12 8v5M12 16h.01"/>'],
        ['Completed',        $stats['completed'] ?? 0,   ($stats['completed_pct'] ?? 0).'% of total',   't-green',  '<path d="M5 13l4 4 10-10"/>'],
    ];

    $quickLinks = [
        ['Equipment',        'View and manage equipment', 'facility.equipment.index',        '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/>'],
        ['Service Requests', 'Track and manage requests', 'facility.service-requests.index', '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 11h6M9 15h6"/>'],
        ['Reports',          'View facility reports',     'facility.reports.index',          '<path d="M6 20V11M12 20V4M18 20v-6"/>'],
    ];
@endphp

{{-- Main grid: content + right rail --}}
<div class="grid grid-cols-1 xl:grid-cols-[1fr_320px] gap-6">

    {{-- ================= LEFT ================= --}}
    <div class="min-w-0 space-y-5">

        {{-- Welcome --}}
        <div class="rounded-xl p-5 flex flex-col md:flex-row md:items-center gap-5" style="background:#EAF2FF; border:1px solid #D9E6FB;">
            <svg viewBox="0 0 120 100" class="w-28 h-24 shrink-0 hidden sm:block" fill="none">
                <ellipse cx="60" cy="90" rx="52" ry="8" fill="#CFE0FA"/>
                <rect x="30" y="34" width="60" height="54" rx="3" fill="#2F6FE4"/>
                <rect x="22" y="52" width="24" height="36" rx="2" fill="#5B93F0"/>
                <rect x="74" y="52" width="24" height="36" rx="2" fill="#5B93F0"/>
                <rect x="54" y="20" width="12" height="28" rx="2" fill="#fff"/>
                <rect x="46" y="28" width="28" height="12" rx="2" fill="#fff"/>
                <g fill="#fff"><rect x="36" y="42" width="8" height="8" rx="1"/><rect x="76" y="42" width="8" height="8" rx="1"/><rect x="36" y="58" width="8" height="8" rx="1"/><rect x="76" y="58" width="8" height="8" rx="1"/><rect x="52" y="68" width="16" height="20" rx="1"/></g>
            </svg>
            <div class="flex-1">
                <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">Facility Management</p>
                <h1 class="text-2xl md:text-3xl font-bold mt-1">Welcome, {{ $facilityUserName ?? (auth()->user()->name ?? 'Facility Manager') }}</h1>
                <p class="text-slate-600 text-sm mt-1.5">Monitor biomedical equipment, service requests and activities.</p>
            </div>
            <div class="flex items-center gap-2 text-sm font-medium self-start">
                <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/></svg>
                {{ now()->format('D, j M Y') }} <span class="text-slate-400">•</span> {{ now()->format('H:i') }}
            </div>
        </div>

        {{-- Stat cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($statCards as [$label, $value, $sub, $tone, $icon])
                <div class="stat {{ $tone }} flex items-center gap-4">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $icon !!}</svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold">{{ $label }}</p>
                        <p class="text-3xl font-bold leading-tight">{{ $value }}</p>
                        <p class="text-xs text-slate-500">{!! $sub !!}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div class="card">
                <div class="section-head">
                    <div class="flex items-center gap-3">
                        <svg viewBox="0 0 24 24" class="w-6 h-6 text-blue-600" fill="currentColor"><rect x="4" y="12" width="4" height="8" rx="1"/><rect x="10" y="6" width="4" height="14" rx="1"/><rect x="16" y="9" width="4" height="11" rx="1"/></svg>
                        <div>
                            <p class="section-title">Service Requests Overview</p>
                            <p class="section-sub">Monthly service request activity</p>
                        </div>
                    </div>
                </div>
                <div class="h-64"><canvas id="requestsOverviewChart"></canvas></div>
            </div>

            <div class="card">
                <div class="section-head">
                    <div class="flex items-center gap-3">
                        <svg viewBox="0 0 24 24" class="w-6 h-6 text-blue-600" fill="currentColor"><path d="M12 3a9 9 0 1 0 9 9h-9V3Z"/><path d="M14 2.1V10h7.9A9 9 0 0 0 14 2.1Z" opacity=".6"/></svg>
                        <div>
                            <p class="section-title">Requests by Service Type</p>
                            <p class="section-sub">Distribution of requests</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="w-48 h-48 shrink-0"><canvas id="serviceTypeChart"></canvas></div>
                    <ul class="flex-1 w-full text-sm space-y-3">
                        @forelse ($serviceTypeBreakdown as $row)
                            <li class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full shrink-0" style="background:{{ $row['color'] }};"></span>
                                <span class="flex-1">{{ $row['label'] }}</span>
                                <span class="text-slate-500 font-medium">{{ $row['pct'] }}%</span>
                            </li>
                        @empty
                            <li class="text-slate-400">No data yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- Tables --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div class="card">
                <div class="section-head">
                    <p class="section-title">
                        <svg viewBox="0 0 24 24" class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/></svg>
                        Recent Service Requests
                    </p>
                    <a href="{{ Route::has('facility.service-requests.index') ? route('facility.service-requests.index') : '#' }}" class="view-all">View all →</a>
                </div>
                <div class="overflow-x-auto -mx-1">
                    <table class="data-table">
                        <thead><tr><th>ID</th><th>Equipment</th><th>Service</th><th>Status</th><th>Date</th></tr></thead>
                        <tbody data-dashboard-search-region>
                            @forelse ($recentRequests as $req)
                                <tr data-dashboard-searchable>
                                    <td class="font-medium">{{ $req['ref'] }}</td>
                                    <td>{{ $req['equipment'] }}</td>
                                    <td>{{ $req['service_type'] }}</td>
                                    <td><span class="badge" style="background:{{ $req['status_bg'] }}; color:{{ $req['status_fg'] }};">{{ $req['status_label'] }}</span></td>
                                    <td class="text-slate-500 whitespace-nowrap">{{ $req['requested_on'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5"><div class="empty">No service requests yet. Create your first request.</div></td></tr>
                            @endforelse
                            <tr data-dashboard-search-empty hidden><td colspan="5"><div class="empty">No matching requests.</div></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="section-head">
                    <p class="section-title">
                        <svg viewBox="0 0 24 24" class="w-6 h-6 text-blue-600" fill="currentColor"><path d="M12 2 1 21h22L12 2Zm1 14h-2v-2h2v2Zm0-4h-2V8h2v4Z"/></svg>
                        Equipment Needing Service
                    </p>
                    <a href="{{ Route::has('facility.equipment.index') ? route('facility.equipment.index') : '#' }}" class="view-all">View all →</a>
                </div>
                <div class="overflow-x-auto -mx-1">
                    <table class="data-table">
                        <thead><tr><th>Equipment</th><th>Type</th><th>Last Service</th></tr></thead>
                        <tbody data-dashboard-search-region>
                            @forelse ($equipmentNeedingService as $eq)
                                <tr data-dashboard-searchable>
                                    <td class="font-medium">{{ $eq['name'] }}</td>
                                    <td>{{ $eq['type'] }}</td>
                                    <td><span class="badge-days">{{ $eq['days_since_service'] }} days</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3"><div class="empty">No equipment needs service right now.</div></td></tr>
                            @endforelse
                            <tr data-dashboard-search-empty hidden><td colspan="3"><div class="empty">No matching equipment.</div></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
    </div>

    {{-- ================= RIGHT RAIL ================= --}}
    <aside class="space-y-5">

        <a href="{{ route('facility.service-requests.create') }}"
           class="flex items-center gap-4 rounded-xl p-5 text-white transition hover:brightness-110" style="background:var(--blue);">
            <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0" style="background:rgba(255,255,255,.2);">
                <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            </div>
            <div class="flex-1">
                <p class="font-semibold">Request Biomedical Service</p>
                <p class="text-xs text-blue-100 mt-0.5">Create a new service request for equipment.</p>
            </div>
            <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="m9 6 6 6-6 6"/></svg>
        </a>

        <div class="card">
            <p class="section-title mb-1">
                <svg viewBox="0 0 24 24" class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/></svg>
                Quick Links
            </p>
            <div class="mt-2">
                @foreach ($quickLinks as [$label, $sub, $routeName, $icon])
                    <a href="{{ Route::has($routeName) ? route($routeName) : '#' }}" class="quick">
                        <div class="quick-icon">
                            <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $icon !!}</svg>
                        </div>
                        <div class="flex-1">
                            <p class="quick-title text-sm font-semibold">{{ $label }}</p>
                            <p class="text-xs text-slate-500">{{ $sub }}</p>
                        </div>
                        <svg viewBox="0 0 24 24" class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="m9 6 6 6-6 6"/></svg>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="card">
            <p class="section-title mb-4">
                <svg viewBox="0 0 24 24" class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                Recent Activity
            </p>
            <ul class="timeline space-y-5 text-sm">
                @forelse ($recentActivity as $item)
                    <li>
                        <span class="dot" style="background:{{ $item['color'] }};"></span>
                        <p class="font-semibold leading-tight">{{ $item['text'] }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $item['time'] }}</p>
                    </li>
                @empty
                    <li class="text-slate-400 pl-0" style="padding-left:0;">No recent activity.</li>
                @endforelse
            </ul>
            @if (count($recentActivity))
                <div class="text-right mt-4">
                    <a href="{{ Route::has('facility.service-requests.index') ? route('facility.service-requests.index') : '#' }}" class="view-all">View all →</a>
                </div>
            @endif
        </div>
    </aside>
</div>
@endsection

@push('scripts')
@php
    $facilityDashboardChartData = [
        'chartData' => $chartData,
        'totalRequests' => (int) ($stats['total'] ?? 0),
        'serviceTypeBreakdown' => $serviceTypeBreakdown,
    ];
@endphp
<script type="application/json" id="facility-dashboard-chart-data">
    @json($facilityDashboardChartData)
</script>
@vite('resources/js/facility-dashboard.js')
@endpush