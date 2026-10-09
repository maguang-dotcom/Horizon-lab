@extends('layouts.engineer')

@section('title', 'Assignments')

@section('content')
@php
    $tabs = ['all' => ['All pending', $totalPending], 'assigned' => ['Not yet started', $notStarted], 'in_progress' => ['In progress', $inProgress]];
    $statusChip = ['assigned' => ['Not yet started', 'background:#FFF0DC;color:#E47F00'], 'in_progress' => ['In progress', 'background:#E4EEFD;color:#1F62F0']];
@endphp
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Assignments</h1>
        <p class="text-slate-500 mt-1">Jobs assigned to you that are still in progress or not yet started.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        @foreach ($tabs as $key => [$label, $count])
            <a href="{{ $key === 'all' ? route('engineer.assignments') : route('engineer.assignments', ['status' => $key]) }}"
               class="rounded-xl border p-5 bg-white {{ $filter === $key ? 'border-blue-500 ring-2 ring-blue-100' : 'border-slate-200' }}">
                <p class="text-sm font-semibold uppercase text-slate-500">{{ $label }}</p>
                <p class="text-4xl font-extrabold mt-1">{{ $count }}</p>
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <table class="w-full text-left">
            <thead class="text-sm text-slate-500">
                <tr><th class="p-4">Equipment</th><th class="p-4">Facility</th><th class="p-4">Service</th><th class="p-4">Urgency</th><th class="p-4">Status</th><th class="p-4">Assigned</th><th class="p-4"></th></tr>
            </thead>
            <tbody>
                @forelse ($assignments as $a)
                    @php [$label, $style] = $statusChip[$a->status] ?? [ucfirst($a->status), '']; @endphp
                    <tr class="border-t border-slate-100">
                        <td class="p-4"><p class="font-semibold">{{ $a->equipment_name }}</p><p class="text-sm text-slate-500">{{ $a->equipment_model }}</p></td>
                        <td class="p-4">{{ $a->facility?->name ?? '—' }}</td>
                        <td class="p-4">{{ ucfirst(str_replace('_', ' ', (string) $a->service_type)) }}</td>
                        <td class="p-4">{{ ucfirst((string) $a->urgency) }}</td>
                        <td class="p-4"><span class="rounded-full px-3 py-1 text-sm" style="{{ $style }}">{{ $label }}</span></td>
                        <td class="p-4 text-sm text-slate-500">{{ $a->assigned_at?->format('d M Y') ?? '—' }}</td>
                        <td class="p-4">
                            @if (Route::has('engineer.reports.create'))
                                <a href="{{ route('engineer.reports.create', $a->id) }}" class="text-blue-600 font-medium">Open →</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-8 text-center text-slate-500">No pending assignments.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
