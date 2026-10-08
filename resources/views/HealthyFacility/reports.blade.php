@extends('layouts.hospital')

@section('title', 'Reports')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Reports</h1>
        <p class="text-sm text-slate-500">Graphs of your facility's service requests ({{ $reportData['total'] }} total).</p>
    </div>

    @if ($reportData['total'] === 0)
        <div class="bg-white rounded-xl p-10 text-center" style="border:1px solid var(--line);">
            <p class="font-semibold">No requests to report on yet.</p>
            <a href="{{ route('facility.service-requests.create') }}" class="text-sm underline">Create your first request</a>
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2">
            @foreach ([
                'reportTypeChart' => 'Requests by service type',
                'reportMonthlyChart' => 'Requests over the last 6 months',
                'reportUrgencyChart' => 'Requests by urgency',
                'reportStatusChart' => 'Requests by status',
            ] as $canvasId => $title)
                <div class="bg-white rounded-xl p-5" style="border:1px solid var(--line);">
                    <p class="font-semibold mb-4">{{ $title }}</p>
                    <div class="h-64"><canvas id="{{ $canvasId }}"></canvas></div>
                </div>
            @endforeach
        </div>
    @endif
@endsection

@push('scripts')
    <script type="application/json" id="facility-report-data">@json($reportData)</script>
    @vite('resources/js/facility-reports.js')
@endpush
