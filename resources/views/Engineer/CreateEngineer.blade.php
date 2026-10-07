@extends('layouts.engineer')
@section('title', 'Service report')

@php
    $cur = config('app.currency', 'UGX');
    $existingParts = $report
        ? $report->parts->map(fn ($p) => ['name' => $p->name, 'quantity' => $p->quantity, 'unit_cost' => (float) $p->unit_cost])->all()
        : [];
    $parts = old('parts', $existingParts ?: [['name' => '', 'quantity' => 1, 'unit_cost' => '']]);
    $inputStyle = 'border:1px solid var(--line); background:#fff;';
@endphp

@section('content')
<a href="{{ route('engineer.dashboard') }}" class="text-sm" style="color: var(--blue);">← Back to dashboard</a>

<div class="mt-3 mb-6">
    <h1 class="text-2xl font-semibold">Service report</h1>
    <p class="text-sm text-slate-500 mt-1">
        {{ $serviceRequest->equipment_name }} · {{ $serviceRequest->facility->name ?? '—' }}
        @if($serviceRequest->facility?->location) ({{ $serviceRequest->facility->location }}) @endif
    </p>
</div>

<div class="bg-white rounded-xl p-4 mb-6 text-sm" style="border:1px solid var(--line);">
    <p class="text-xs text-slate-400 mb-1">Facility's reported issue</p>
    <p>{{ $serviceRequest->issue_description }}</p>
</div>

<form method="POST" action="{{ route('engineer.reports.store', $serviceRequest) }}" id="report-form"
      class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-6">
    @csrf

    <div class="space-y-6 min-w-0">
        <div class="bg-white rounded-xl p-5 space-y-4" style="border:1px solid var(--line);">
            <div>
                <label for="problem_found" class="block text-sm font-medium mb-1">Problem found</label>
                <textarea id="problem_found" name="problem_found" rows="4" class="w-full px-3 py-2 rounded-lg text-sm" style="{{ $inputStyle }}"
                    placeholder="What is actually wrong with the equipment? Include fault codes, failed components, and the cause if known.">{{ old('problem_found', $report->problem_found ?? '') }}</textarea>
                @error('problem_found') <p class="text-sm mt-1" style="color:#C0392B;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="work_done" class="block text-sm font-medium mb-1">Work done / required <span class="text-slate-400 font-normal">(optional)</span></label>
                <textarea id="work_done" name="work_done" rows="3" class="w-full px-3 py-2 rounded-lg text-sm" style="{{ $inputStyle }}"
                    placeholder="Repairs carried out, or work still needed.">{{ old('work_done', $report->work_done ?? '') }}</textarea>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5" style="border:1px solid var(--line);">
            <p class="text-sm font-medium mb-3">Labor</p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="labor_hours" class="block text-xs text-slate-500 mb-1">Hours worked</label>
                    <input type="number" step="0.25" min="0" id="labor_hours" name="labor_hours"
                        value="{{ old('labor_hours', $report->labor_hours ?? 0) }}" class="w-full px-3 py-2 rounded-lg text-sm" style="{{ $inputStyle }}">
                    @error('labor_hours') <p class="text-sm mt-1" style="color:#C0392B;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="hourly_rate" class="block text-xs text-slate-500 mb-1">Hourly rate ({{ $cur }})</label>
                    <input type="number" step="any" min="0" id="hourly_rate" name="hourly_rate"
                        value="{{ old('hourly_rate', $report->hourly_rate ?? '') }}" class="w-full px-3 py-2 rounded-lg text-sm" style="{{ $inputStyle }}">
                    @error('hourly_rate') <p class="text-sm mt-1" style="color:#C0392B;">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5" style="border:1px solid var(--line);">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium">Spare parts</p>
                <button type="button" id="add-part" class="text-xs px-3 py-1.5 rounded-lg" style="background:#E9F1FE;color:#2F6FE4;">+ Add part</button>
            </div>
            <div id="parts" class="space-y-2">
                @foreach ($parts as $i => $part)
                    <div class="part-row grid grid-cols-[1fr_70px_120px_28px] gap-2 items-center">
                        <input type="text" name="parts[{{ $i }}][name]" value="{{ $part['name'] ?? '' }}" placeholder="Part name" class="px-3 py-2 rounded-lg text-sm" style="{{ $inputStyle }}">
                        <input type="number" min="1" name="parts[{{ $i }}][quantity]" value="{{ $part['quantity'] ?? 1 }}" class="qty px-3 py-2 rounded-lg text-sm" style="{{ $inputStyle }}">
                        <input type="number" step="any" min="0" name="parts[{{ $i }}][unit_cost]" value="{{ $part['unit_cost'] ?? '' }}" placeholder="Unit cost" class="cost px-3 py-2 rounded-lg text-sm" style="{{ $inputStyle }}">
                        <button type="button" class="remove-part text-slate-400" aria-label="Remove part">✕</button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Live cost summary --}}
    <aside class="self-start lg:sticky lg:top-6 bg-white rounded-xl p-5" style="border:1px solid var(--line);">
        <p class="font-semibold text-sm mb-3">Cost estimate</p>
        <dl class="text-sm space-y-2">
            <div class="flex justify-between"><dt class="text-slate-500">Labor</dt><dd id="sum-labor">0</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Parts</dt><dd id="sum-parts">0</dd></div>
            <div class="flex justify-between pt-2 font-semibold" style="border-top:1px solid var(--line);"><dt>Total ({{ $cur }})</dt><dd id="sum-total">0</dd></div>
        </dl>

        <label class="flex items-center gap-2 text-sm mt-5">
            <input type="checkbox" name="mark_completed" value="1" @checked(old('mark_completed', ($serviceRequest->status === 'completed')))>
            Mark work as completed
        </label>

        <button type="submit" class="w-full mt-4 py-2.5 rounded-lg text-white text-sm" style="background: var(--blue);">Save report</button>
        <p class="text-xs text-slate-400 mt-3">The final total is recalculated on the server when you save.</p>
    </aside>
</form>

<template id="part-template">
    <div class="part-row grid grid-cols-[1fr_70px_120px_28px] gap-2 items-center">
        <input type="text" name="parts[__i__][name]" placeholder="Part name" class="px-3 py-2 rounded-lg text-sm" style="{{ $inputStyle }}">
        <input type="number" min="1" name="parts[__i__][quantity]" value="1" class="qty px-3 py-2 rounded-lg text-sm" style="{{ $inputStyle }}">
        <input type="number" step="any" min="0" name="parts[__i__][unit_cost]" placeholder="Unit cost" class="cost px-3 py-2 rounded-lg text-sm" style="{{ $inputStyle }}">
        <button type="button" class="remove-part text-slate-400" aria-label="Remove part">✕</button>
    </div>
</template>
@endsection

@push('scripts')
<script>
    const form = document.getElementById('report-form');
    const partsBox = document.getElementById('parts');
    let nextIndex = partsBox.querySelectorAll('.part-row').length;
    const fmt = n => Math.round(n).toLocaleString();

    function recalc() {
        const labor = (parseFloat(form.labor_hours.value) || 0) * (parseFloat(form.hourly_rate.value) || 0);
        let parts = 0;
        partsBox.querySelectorAll('.part-row').forEach(row => {
            parts += (parseInt(row.querySelector('.qty').value) || 0) * (parseFloat(row.querySelector('.cost').value) || 0);
        });
        document.getElementById('sum-labor').textContent = fmt(labor);
        document.getElementById('sum-parts').textContent = fmt(parts);
        document.getElementById('sum-total').textContent = fmt(labor + parts);
    }

    document.getElementById('add-part').addEventListener('click', () => {
        const html = document.getElementById('part-template').innerHTML.replaceAll('__i__', nextIndex++);
        partsBox.insertAdjacentHTML('beforeend', html);
    });
    partsBox.addEventListener('click', e => {
        if (e.target.classList.contains('remove-part')) { e.target.closest('.part-row').remove(); recalc(); }
    });
    form.addEventListener('input', recalc);
    recalc();
</script>
@endpush