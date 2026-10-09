@extends('layouts.engineer')

@section('title', 'Dashboard')

@push('styles')
<style>
    .kpi { position:relative; display:block; border-radius:14px; padding:22px 24px; border:1px solid var(--bd); background:var(--bg); transition:box-shadow .15s; }
    a.kpi:hover { box-shadow:0 6px 18px rgba(11,29,77,.08); }
    .kpi-icon { width:60px; height:60px; border-radius:999px; background:var(--ic-bg); color:var(--ic); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .kpi-label { font-size:13px; font-weight:600; letter-spacing:.03em; text-transform:uppercase; line-height:1.35; }
    .kpi-value { font-size:40px; font-weight:800; line-height:1.1; margin-top:4px; }
    .k-blue  { --bg:#F1F6FF; --bd:#CFE0FB; --ic-bg:#DCE9FD; --ic:#1F62F0; }
    .k-red   { --bg:#FFF4F5; --bd:#F8CBD0; --ic-bg:#FBC9CF; --ic:#DC2626; }
    .k-green { --bg:#F0FAF5; --bd:#C7E8D6; --ic-bg:#CBEBD8; --ic:#12925A; }
    .k-gold  { --bg:#FFF9EC; --bd:#F6E2B0; --ic-bg:#FBE3A4; --ic:#D18A0B; }
    .k-sky   { --bg:#F1F6FF; --bd:#CFE0FB; --ic-bg:#3B8BFF; --ic:#fff; }

    .pill-count { min-width:28px; height:28px; padding:0 8px; border-radius:999px; background:#DCE9FD; color:#1F62F0; font-size:13px; font-weight:600; display:inline-flex; align-items:center; justify-content:center; }

    .panel { background:#fff; border-radius:14px; box-shadow:0 2px 14px rgba(11,29,77,.06); border:1px solid #EEF2F9; }
    .tbl { width:100%; border-collapse:collapse; }
    .tbl thead th { text-align:left; font-size:14px; font-weight:500; color:#64748B; padding:14px 16px; border-bottom:1px solid var(--line); }
    .tbl tbody td { padding:18px 16px; vertical-align:middle; }
    .tbl tbody tr + tr td { border-top:1px solid var(--line); }

    .fac-icon { width:46px; height:46px; border-radius:999px; background:#E4EEFD; color:#1F62F0; display:flex; align-items:center; justify-content:center; flex-shrink:0; }

    .chip { display:inline-flex; align-items:center; gap:8px; padding:6px 14px; border-radius:999px; font-size:13.5px; font-weight:500; white-space:nowrap; }
    .chip::before { content:''; width:9px; height:9px; border-radius:999px; background:currentColor; }
    .c-critical { background:#FDE6E8; color:#D91F2F; }
    .c-high     { background:#FFF0DC; color:#E47F00; }
    .c-normal   { background:#E4EEFD; color:#1F62F0; }
    .c-low      { background:#EEF1F5; color:#64748B; }
    .c-progress { background:#E4EEFD; color:#1F62F0; }
    .c-assigned { background:#FFF0DC; color:#E47F00; }
    .c-done     { background:#DDF3E6; color:#12925A; }

    .btn { display:inline-flex; align-items:center; justify-content:space-between; gap:14px; min-width:150px; padding:11px 18px; border-radius:8px; font-size:14px; font-weight:500; border:1.5px solid var(--blue); transition:filter .15s, background .15s; }
    .btn-solid { background:var(--blue); color:#fff; }
    .btn-solid:hover { filter:brightness(1.08); }
    .btn-outline { background:#fff; color:var(--blue); }
    .btn-outline:hover { background:#F1F6FF; }
</style>
@endpush

@section('content')

@php
    // ---- Data contract (pass these from EngineerDashboardController@index) ----
    $engineerName       = $engineerName ?? (auth()->user()->name ?? 'Engineer');
    $activeCount        = $activeCount ?? 0;
    $urgentCount        = $urgentCount ?? 0;
    $completedThisMonth = $completedThisMonth ?? 0;
    $totalEstimated     = $totalEstimated ?? 0;
    $assignedFacilities = $assignedFacilities ?? [];   // [['name' => 'Mulago Hospital', 'count' => 3], ...]
    $assignments        = $assignments ?? [];          // see keys used below
    $reports            = $reports ?? collect();

    $priorityClass = ['critical' => 'c-critical', 'high' => 'c-high', 'normal' => 'c-normal', 'low' => 'c-low'];
    $statusMap = [
        'in_progress' => ['In progress', 'c-progress', 'Edit report',  'btn-solid'],
        'assigned'    => ['Assigned',    'c-assigned', 'Write report', 'btn-outline'],
        'completed'   => ['Completed',   'c-done',     'View report',  'btn-outline'],
    ];
    $assignmentsUrl = route('engineer.assignments');
    $facilitiesUrl  = route('engineer.facilities');
@endphp

{{-- Heading --}}
<div class="mb-7">
    <h1 class="text-3xl md:text-4xl font-extrabold">Welcome, {{ $engineerName }}</h1>
    <p class="text-slate-500 text-lg mt-1">Engineer workspace</p>
</div>

{{-- KPI row --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
    <a href="{{ $assignmentsUrl }}" class="kpi k-blue">
        <div class="flex items-start gap-5">
            <div class="kpi-icon">
                <svg viewBox="0 0 24 24" class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 10l1 1 2-2M14 10h1M9 15l1 1 2-2M14 15h1"/></svg>
            </div>
            <div class="flex-1">
                <p class="kpi-label">Active<br>Assignments</p>
                <p class="kpi-value">{{ $activeCount }}</p>
                <p class="mt-3 text-[15px] font-medium text-blue-600">View jobs →</p>
            </div>
            <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400 mt-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m9 6 6 6-6 6"/></svg>
        </div>
    </a>

    <a href="{{ $assignmentsUrl }}" class="kpi k-red">
        <div class="flex items-start gap-5">
            <div class="kpi-icon">
                <svg viewBox="0 0 24 24" class="w-7 h-7" fill="currentColor"><path d="M12 2.5 1.5 21h21L12 2.5Zm1 13.5h-2v-2h2v2Zm0-4h-2V8h2v4Z"/></svg>
            </div>
            <div class="flex-1">
                <p class="kpi-label">Urgent<br>Requests</p>
                <p class="kpi-value">{{ $urgentCount }}</p>
                <p class="mt-3 text-[15px] font-medium text-red-600">Needs action</p>
            </div>
            <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400 mt-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m9 6 6 6-6 6"/></svg>
        </div>
    </a>

    <a href="{{ route('engineer.reports.index') }}" class="kpi k-green">
        <div class="flex items-start gap-5">
            <div class="kpi-icon">
                <svg viewBox="0 0 24 24" class="w-8 h-8" fill="currentColor"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm-1.6 14.4-4-4 1.4-1.4 2.6 2.6 5.8-5.8 1.4 1.4-7.2 7.2Z"/></svg>
            </div>
            <div class="flex-1">
                <p class="kpi-label">Completed</p>
                <p class="kpi-value">{{ $completedThisMonth }}</p>
                <p class="mt-3 text-[15px] font-medium text-green-700">This month</p>
            </div>
            <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-400 mt-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m9 6 6 6-6 6"/></svg>
        </div>
    </a>
</div>

{{-- Estimate + facilities --}}
<div class="grid grid-cols-1 lg:grid-cols-[1fr_1fr] gap-5 mb-7">
    <div class="kpi k-gold">
        <div class="flex items-start gap-5">
            <div class="kpi-icon">
                <svg viewBox="0 0 24 24" class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><ellipse cx="9" cy="6.5" rx="6" ry="2.5" fill="currentColor" fill-opacity=".25"/><path d="M3 6.5v4c0 1.4 2.7 2.5 6 2.5s6-1.1 6-2.5v-4"/><path d="M3 10.5v4c0 1.4 2.7 2.5 6 2.5"/><ellipse cx="16" cy="15.5" rx="5" ry="2.2" fill="currentColor" fill-opacity=".25"/><path d="M11 15.5v3c0 1.2 2.2 2.2 5 2.2s5-1 5-2.2v-3"/></svg>
            </div>
            <div>
                <p class="kpi-label">Total estimated</p>
                <p class="text-4xl md:text-5xl font-extrabold mt-2">UGX {{ number_format($totalEstimated) }}</p>
            </div>
        </div>
        <div class="h-16 md:h-24"></div>
    </div>

    <div id="assigned-facilities" class="kpi k-sky">
        <div class="flex items-start gap-5">
            <div class="kpi-icon">
                <svg viewBox="0 0 24 24" class="w-8 h-8" fill="currentColor"><path d="M12 2a7.5 7.5 0 0 0-7.5 7.5C4.5 15 12 22 12 22s7.5-7 7.5-12.5A7.5 7.5 0 0 0 12 2Zm0 10.3a2.8 2.8 0 1 1 0-5.6 2.8 2.8 0 0 1 0 5.6Z"/></svg>
            </div>
            <div class="flex-1">
                <p class="kpi-label">Assigned facilities</p>
                <ul data-dashboard-search-region class="mt-2">
                    @forelse ($assignedFacilities as $f)
                        <li data-dashboard-searchable class="flex items-center justify-between py-1.5 {{ ! $loop->last ? 'border-b' : '' }}" style="border-color:var(--line);">
                            <span class="text-[15px]">{{ $f['name'] }}</span>
                            <span class="pill-count">{{ $f['count'] }}</span>
                        </li>
                    @empty
                        <li class="py-2 text-sm text-slate-500">No facilities assigned yet.</li>
                    @endforelse
                    <li data-dashboard-search-empty hidden class="py-2 text-sm text-slate-500">No matching facilities.</li>
                </ul>
                <div class="text-right mt-3">
                    <a href="{{ $facilitiesUrl }}" class="text-sm font-medium text-blue-600 hover:underline">+ View all facilities</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- My assignments --}}
<section id="assignments" class="panel p-6 md:p-8">
    <h2 class="text-2xl font-extrabold uppercase tracking-wide mb-4">My Assignments</h2>

    <div class="overflow-x-auto">
        <table class="tbl min-w-[860px]">
            <thead>
                <tr>
                    <th>Facility</th>
                    <th>Equipment</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody data-dashboard-search-region>
                @forelse ($assignments as $a)
                    @php
                        [$statusLabel, $statusClass, $btnLabel, $btnClass] = $statusMap[$a['status']] ?? $statusMap['assigned'];
                        $pClass = $priorityClass[strtolower($a['priority'] ?? 'normal')] ?? 'c-normal';
                    @endphp
                    <tr data-dashboard-searchable>
                        <td>
                            <div class="flex items-center gap-4">
                                <div class="fac-icon">
                                    <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="M9 7h.01M12 7h.01M15 7h.01M9 11h.01M12 11h.01M15 11h.01M10 21v-4h4v4"/></svg>
                                </div>
                                <div>
                                    <p class="font-semibold leading-tight">{{ $a['facility'] }}</p>
                                    <p class="text-sm text-slate-500 mt-0.5">{{ $a['city'] ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="font-medium">{{ $a['equipment'] }}</p>
                            <p class="text-sm text-slate-500 mt-0.5">{{ $a['category'] ?? '' }}</p>
                        </td>
                        <td><span class="chip {{ $pClass }}">{{ ucfirst($a['priority'] ?? 'Normal') }}</span></td>
                        <td><span class="chip {{ $statusClass }}">{{ $statusLabel }}</span></td>
                        <td>
                            <a href="{{ Route::has('engineer.reports.create') ? route('engineer.reports.create', $a['request_id']) : '#' }}"
                               class="btn {{ $btnClass }}">
                                {{ $btnLabel }}
                                <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="m9 6 6 6-6 6"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-slate-500 py-12">
                            No assignments yet. New jobs from facilities will appear here.
                        </td>
                    </tr>
                @endforelse
                <tr data-dashboard-search-empty hidden><td colspan="5" class="py-8 text-center text-slate-500">No matching assignments.</td></tr>
            </tbody>
        </table>
    </div>
</section>

<section id="reports" class="panel p-6 md:p-8 mt-6">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <h2 class="text-2xl font-extrabold uppercase tracking-wide">My Service Reports</h2>
        <span class="text-sm text-slate-500">{{ $reports->count() }} recent</span>
    </div>

    <div class="overflow-x-auto">
        <table class="tbl min-w-[760px]">
            <thead>
                <tr>
                    <th>Facility / Equipment</th>
                    <th>Finding</th>
                    <th>Status</th>
                    <th>Total estimate</th>
                    <th>Reported</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody data-dashboard-search-region>
                @forelse ($reports as $report)
                    <tr data-dashboard-searchable>
                        <td>
                            <p class="font-semibold">{{ $report->request?->facility?->name ?? 'Unknown facility' }}</p>
                            <p class="mt-0.5 text-sm text-slate-500">{{ $report->request?->equipment_name ?? 'Equipment' }}</p>
                        </td>
                        <td class="max-w-xs text-slate-600"><span class="line-clamp-2">{{ $report->problem_found }}</span></td>
                        <td><span class="chip {{ ($report->request?->status ?? '') === 'completed' ? 'c-done' : 'c-progress' }}">{{ ucfirst(str_replace('_', ' ', $report->request?->status ?? 'submitted')) }}</span></td>
                        <td class="whitespace-nowrap font-semibold">{{ config('app.currency', 'UGX') }} {{ number_format((float) $report->total_cost) }}</td>
                        <td class="whitespace-nowrap text-sm text-slate-500">{{ $report->created_at?->format('M d, Y') }}</td>
                        <td>
                            @if ($report->request)
                                <a href="{{ route('engineer.reports.create', $report->request) }}" class="btn btn-outline">Open report</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-slate-500">You have not submitted any service reports yet.</td>
                    </tr>
                @endforelse
                <tr data-dashboard-search-empty hidden><td colspan="6" class="py-8 text-center text-slate-500">No matching reports.</td></tr>
            </tbody>
        </table>
    </div>
</section>

@endsection