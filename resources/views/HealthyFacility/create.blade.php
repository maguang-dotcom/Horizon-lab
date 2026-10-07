@extends('layouts.hospital')

@section('title', 'Request biomedical service')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,500;8..60,600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --ink: #1B2B34;
        --paper: #F7F9F8;
        --surface: #FFFFFF;
        --line: #DDE4E1;
        --teal: #0E6E5F;
        --teal-dark: #0A4F44;
        --amber: #C97B3D;
        --critical: #B3432B;
    }
    .req-serif { font-family: 'Source Serif 4', Georgia, serif; }
    .req-sans { font-family: 'Inter', system-ui, sans-serif; }
</style>
@endpush

@section('content')
<div class="req-sans min-h-screen" style="background: var(--paper); color: var(--ink);">
    <div class="max-w-4xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-[1fr_260px] gap-10">

        {{-- Left: the requisition form --}}
        <div>
            <div class="mb-8 pb-6" style="border-bottom: 1px solid var(--line);">
                <p class="text-sm mb-1" style="color: var(--teal);">Service requisition</p>
                <h1 class="req-serif text-3xl" style="font-weight: 600;">Request biomedical equipment service</h1>
                <p class="mt-2 text-sm max-w-md" style="color: #5B6B66;">
                    Tell us what's broken or what's due for maintenance. An admin reviews every request
                    and assigns an approved engineer to your facility.
                </p>
            </div>

            @if (session('status'))
                <div class="mb-6 px-4 py-3 text-sm rounded" style="background: #E7F3F0; color: var(--teal-dark); border: 1px solid #BFDBD4;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('facility.service-requests.store') }}" class="space-y-7">
                @csrf

                {{-- Equipment identity --}}
                <fieldset>
                    <legend class="text-xs uppercase tracking-wide mb-3" style="color: #7A8A85;">Equipment</legend>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="equipment_name" class="block text-sm mb-1">Equipment name</label>
                            <input type="text" name="equipment_name" id="equipment_name"
                                value="{{ old('equipment_name') }}"
                                placeholder="e.g. Infant incubator, X-ray unit"
                                class="w-full px-3 py-2 rounded"
                                style="border: 1px solid var(--line); background: var(--surface);">
                            @error('equipment_name')
                                <p class="mt-1 text-sm" style="color: var(--critical);">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="equipment_model" class="block text-sm mb-1">Model </label>
                            <input type="text" name="equipment_model" id="equipment_model"
                                value="{{ old('equipment_model') }}"
                                class="w-full px-3 py-2 rounded"
                                style="border: 1px solid var(--line); background: var(--surface);">
                        </div>
                        <div>
                            <label for="serial_number" class="block text-sm mb-1">Serial number</label>
                            <input type="text" name="serial_number" id="serial_number"
                                value="{{ old('serial_number') }}"
                                class="w-full px-3 py-2 rounded"
                                style="border: 1px solid var(--line); background: var(--surface);">
                        </div>
                    </div>
                </fieldset>

                {{-- Service type + urgency --}}
                <fieldset>
                    <legend class="text-xs uppercase tracking-wide mb-3" style="color: #7A8A85;">Service needed</legend>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="service_type" class="block text-sm mb-1">Type of service</label>
                            <select name="service_type" id="service_type"
                                class="w-full px-3 py-2 rounded"
                                style="border: 1px solid var(--line); background: var(--surface);">
                                <option value="">Select one</option>
                                @foreach ($serviceTypes as $value => $label)
                                    <option value="{{ $value }}" @selected(old('service_type') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('service_type')
                                <p class="mt-1 text-sm" style="color: var(--critical);">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="urgency" class="block text-sm mb-1">Urgency</label>
                            <select name="urgency" id="urgency"
                                class="w-full px-3 py-2 rounded"
                                style="border: 1px solid var(--line); background: var(--surface);">
                                @foreach ($urgencyLevels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('urgency', 'normal') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </fieldset>

                {{-- Description --}}
                <div>
                    <label for="issue_description" class="block text-sm mb-1">Describe the issue</label>
                    <textarea name="issue_description" id="issue_description" rows="4"
                        placeholder="What's wrong, when it started, and anything an engineer should know before arriving."
                        class="w-full px-3 py-2 rounded"
                        style="border: 1px solid var(--line); background: var(--surface);">{{ old('issue_description') }}</textarea>
                    @error('issue_description')
                        <p class="mt-1 text-sm" style="color: var(--critical);">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Preferred date --}}
                <div class="max-w-xs">
                    <label for="preferred_date" class="block text-sm mb-1">Preferred date <span style="color:#9AA6A2;">(optional)</span></label>
                    <input type="date" name="preferred_date" id="preferred_date"
                        value="{{ old('preferred_date') }}"
                        class="w-full px-3 py-2 rounded"
                        style="border: 1px solid var(--line); background: var(--surface);">
                </div>

                <div class="pt-4" style="border-top: 1px solid var(--line);">
                    <button type="submit"
                        class="px-5 py-2.5 rounded text-white text-sm"
                        style="background: var(--teal);">
                        Submit request
                    </button>
                </div>
            </form>
        </div>

        {{-- Right: quiet context rail, not a decorative card --}}
        <aside class="text-sm space-y-6 md:pt-24" style="color: #5B6B66;">
            <div>
                <p class="req-serif text-base mb-1" style="color: var(--ink);">What happens next</p>
                <p>An admin reviews the request and assigns it to an engineer already approved for
                    your facility. You'll be notified once someone is assigned.</p>
            </div>
            <div>
                <p class="req-serif text-base mb-1" style="color: var(--ink);">Marking urgency</p>
                <p>Use <span style="color: var(--critical);">Critical</span> only when the equipment
                    is down and directly affecting patient care — it's routed ahead of standing requests.</p>
            </div>
        </aside>
    </div>
</div>
@endsection