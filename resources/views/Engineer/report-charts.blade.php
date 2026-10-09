@extends('layouts.engineer')

@section('title', 'Reports')

@section('content')
@php
    $colors = ['assigned' => '#E47F00', 'in_progress' => '#1F62F0', 'completed' => '#12925A'];
@endphp
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Assignment reports</h1>
        <p class="text-slate-500 mt-1">Number of assignments by status for each of the last 12 months.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        @foreach ($statuses as $key => $label)
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <p class="text-sm font-semibold uppercase text-slate-500"><span class="inline-block w-3 h-3 rounded-full mr-1" style="background:{{ $colors[$key] }}"></span>{{ $label }}</p>
                <p class="text-4xl font-extrabold mt-1">{{ $totals[$key] }}</p>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <div class="flex items-end gap-2 h-64">
            @foreach ($series as $m)
                @php $total = $m['assigned'] + $m['in_progress'] + $m['completed']; @endphp
                <div class="flex-1 flex flex-col items-center justify-end h-full" title="{{ $m['label'] }}: {{ $total }}">
                    <span class="text-xs text-slate-500">{{ $total ?: '' }}</span>
                    <div class="w-full flex flex-col-reverse rounded-t overflow-hidden" style="height:{{ round($total / $maxMonth * 100) }}%">
                        @foreach (['assigned', 'in_progress', 'completed'] as $s)
                            @if ($m[$s])<div style="flex:{{ $m[$s] }};background:{{ $colors[$s] }}" title="{{ $statuses[$s] }}: {{ $m[$s] }}"></div>@endif
                        @endforeach
                    </div>
                    <span class="text-[11px] text-slate-500 mt-1 whitespace-nowrap">{{ $m['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <table class="w-full text-left">
            <thead class="text-sm text-slate-500"><tr><th class="p-3">Month</th>@foreach ($statuses as $label)<th class="p-3">{{ $label }}</th>@endforeach</tr></thead>
            <tbody>
                @foreach ($series as $m)
                    <tr class="border-t border-slate-100"><td class="p-3">{{ $m['label'] }}</td><td class="p-3">{{ $m['assigned'] }}</td><td class="p-3">{{ $m['in_progress'] }}</td><td class="p-3">{{ $m['completed'] }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
