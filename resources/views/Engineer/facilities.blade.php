@extends('layouts.engineer')

@section('title', 'Facilities')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Assigned facilities</h1>
        <p class="text-slate-500 mt-1">{{ $facilities->count() }} {{ \Illuminate\Support\Str::plural('facility', $facilities->count()) }} assigned to you.</p>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($facilities as $f)
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <p class="text-lg font-semibold">{{ $f['name'] }}</p>
                @if ($f['location'])<p class="text-sm text-slate-500">{{ $f['location'] }}</p>@endif
                @if ($f['phone'])<p class="text-sm text-slate-500">{{ $f['phone'] }}</p>@endif
                <div class="mt-4 grid grid-cols-3 text-center text-sm">
                    <div><p class="text-xl font-bold">{{ $f['total'] }}</p><p class="text-slate-500">Total</p></div>
                    <div><p class="text-xl font-bold text-amber-600">{{ $f['open'] }}</p><p class="text-slate-500">Open</p></div>
                    <div><p class="text-xl font-bold text-green-600">{{ $f['completed'] }}</p><p class="text-slate-500">Done</p></div>
                </div>
            </div>
        @empty
            <p class="text-slate-500">No facilities have been assigned to you yet.</p>
        @endforelse
    </div>
</div>
@endsection
