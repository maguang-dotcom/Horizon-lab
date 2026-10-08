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

    .req-serif {
        font-family: 'Source Serif 4', Georgia, serif;
    }

    .req-sans {
        font-family: 'Inter', system-ui, sans-serif;
    }

    .upload-zone {
        transition: all 0.2s ease;
    }

    .upload-zone:hover {
        border-color: var(--teal);
        background: #F2F8F6;
    }
</style>
@endpush


@section('content')

<div class="req-sans min-h-screen"
     style="background: var(--paper); color: var(--ink);">

    <div class="max-w-4xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-[1fr_260px] gap-10">

        {{-- LEFT --}}
        <div>

            {{-- Header --}}
            <div class="mb-8 pb-6"
                 style="border-bottom: 1px solid var(--line);">

                <p class="text-sm mb-1"
                   style="color: var(--teal);">
                    Service requisition
                </p>

                <h1 class="req-serif text-3xl"
                    style="font-weight: 600;">
                    Request biomedical equipment service
                </h1>

                <p class="mt-2 text-sm max-w-md"
                   style="color: #5B6B66;">
                    Tell us what's wrong with your equipment. Upload photos when possible so
                    our biomedical engineer can understand the problem before arriving.
                </p>

            </div>


            {{-- Success --}}
            @if (session('status'))

                <div class="mb-6 px-4 py-3 text-sm rounded"
                     style="background: #E7F3F0;
                            color: var(--teal-dark);
                            border: 1px solid #BFDBD4;">

                    {{ session('status') }}

                </div>

            @endif


            {{-- FORM --}}
            <form method="POST"
                  action="{{ route('facility.service-requests.store') }}"
                  enctype="multipart/form-data"
                  class="space-y-7">

                @csrf


                {{-- EQUIPMENT --}}
                <fieldset>

                    <legend class="text-xs uppercase tracking-wide mb-3"
                            style="color: #7A8A85;">
                        Equipment
                    </legend>


                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">


                        {{-- Equipment Dropdown --}}
                        <div class="sm:col-span-2">

                            <label for="equipment_name"
                                   class="block text-sm mb-1">
                                Equipment name
                            </label>

                            <select
                                name="equipment_name"
                                id="equipment_name"
                                required
                                class="w-full px-3 py-2.5 rounded"
                                style="border: 1px solid var(--line);
                                       background: var(--surface);">

                                <option value="">
                                    Select equipment
                                </option>

                                <optgroup label="Diagnostic Equipment">

                                    <option value="X-Ray Machine"
                                        @selected(old('equipment_name') === 'X-Ray Machine')>
                                        X-Ray Machine
                                    </option>

                                    <option value="Ultrasound Machine"
                                        @selected(old('equipment_name') === 'Ultrasound Machine')>
                                        Ultrasound Machine
                                    </option>

                                    <option value="ECG Machine"
                                        @selected(old('equipment_name') === 'ECG Machine')>
                                        ECG Machine
                                    </option>

                                    <option value="Patient Monitor"
                                        @selected(old('equipment_name') === 'Patient Monitor')>
                                        Patient Monitor
                                    </option>

                                    <option value="Pulse Oximeter"
                                        @selected(old('equipment_name') === 'Pulse Oximeter')>
                                        Pulse Oximeter
                                    </option>

                                </optgroup>


                                <optgroup label="Life Support Equipment">

                                    <option value="Ventilator"
                                        @selected(old('equipment_name') === 'Ventilator')>
                                        Ventilator
                                    </option>

                                    <option value="Infant Incubator"
                                        @selected(old('equipment_name') === 'Infant Incubator')>
                                        Infant Incubator
                                    </option>

                                    <option value="Infant Warmer"
                                        @selected(old('equipment_name') === 'Infant Warmer')>
                                        Infant Warmer
                                    </option>

                                    <option value="Defibrillator"
                                        @selected(old('equipment_name') === 'Defibrillator')>
                                        Defibrillator
                                    </option>

                                </optgroup>


                                <optgroup label="Laboratory Equipment">

                                    <option value="Centrifuge"
                                        @selected(old('equipment_name') === 'Centrifuge')>
                                        Centrifuge
                                    </option>

                                    <option value="Microscope"
                                        @selected(old('equipment_name') === 'Microscope')>
                                        Microscope
                                    </option>

                                    <option value="Autoclave"
                                        @selected(old('equipment_name') === 'Autoclave')>
                                        Autoclave
                                    </option>

                                    <option value="Laboratory Analyzer"
                                        @selected(old('equipment_name') === 'Laboratory Analyzer')>
                                        Laboratory Analyzer
                                    </option>

                                    <option value="Incubator"
                                        @selected(old('equipment_name') === 'Incubator')>
                                        Laboratory Incubator
                                    </option>

                                </optgroup>


                                <optgroup label="Other Medical Equipment">

                                    <option value="Infusion Pump"
                                        @selected(old('equipment_name') === 'Infusion Pump')>
                                        Infusion Pump
                                    </option>

                                    <option value="Suction Machine"
                                        @selected(old('equipment_name') === 'Suction Machine')>
                                        Suction Machine
                                    </option>

                                    <option value="Operating Table"
                                        @selected(old('equipment_name') === 'Operating Table')>
                                        Operating Table
                                    </option>

                                    <option value="Operating Light"
                                        @selected(old('equipment_name') === 'Operating Light')>
                                        Operating Light
                                    </option>

                                    <option value="Other"
                                        @selected(old('equipment_name') === 'Other')>
                                        Other Equipment
                                    </option>

                                </optgroup>

                            </select>

                            @error('equipment_name')
                                <p class="mt-1 text-sm"
                                   style="color: var(--critical);">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Model --}}
                        <div>

                            <label for="equipment_model"
                                   class="block text-sm mb-1">
                                Model
                            </label>

                            <input
                                type="text"
                                name="equipment_model"
                                id="equipment_model"
                                value="{{ old('equipment_model') }}"
                                placeholder="e.g. Mindray PM-60"
                                class="w-full px-3 py-2 rounded"
                                style="border: 1px solid var(--line);
                                       background: var(--surface);">

                        </div>


                        {{-- Serial --}}
                        <div>

                            <label for="serial_number"
                                   class="block text-sm mb-1">
                                Serial number
                            </label>

                            <input
                                type="text"
                                name="serial_number"
                                id="serial_number"
                                value="{{ old('serial_number') }}"
                                placeholder="Enter serial number"
                                class="w-full px-3 py-2 rounded"
                                style="border: 1px solid var(--line);
                                       background: var(--surface);">

                        </div>

                    </div>

                </fieldset>


                {{-- EQUIPMENT PHOTOS --}}
                <fieldset>

                    <legend class="text-xs uppercase tracking-wide mb-3"
                            style="color: #7A8A85;">
                        Equipment photos
                    </legend>


                    <div>

                        <label for="equipment_photos"
                               class="upload-zone flex flex-col items-center justify-center w-full min-h-[180px] rounded-xl cursor-pointer"
                               style="border: 2px dashed var(--line);
                                      background: var(--surface);">

                            <div class="text-center px-6">

                                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full"
                                     style="background: #E7F3F0; color: var(--teal);">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="24"
                                         height="24"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="1.8">

                                        <path d="M15 8h.01"/>
                                        <path d="M12 20H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v6"/>
                                        <circle cx="12" cy="13" r="3"/>
                                        <path d="M16 19h6"/>
                                        <path d="M19 16v6"/>

                                    </svg>

                                </div>


                                <p class="text-sm font-semibold"
                                   style="color: var(--ink);">
                                    Upload photos of the equipment
                                </p>

                                <p class="mt-1 text-xs"
                                   style="color: #7A8A85;">
                                    Click to browse or drag and drop images here
                                </p>

                                <p class="mt-2 text-xs"
                                   style="color: #9AA6A2;">
                                    JPG, JPEG, PNG or WEBP · Maximum 5 images
                                </p>

                            </div>


                            <input
                                id="equipment_photos"
                                name="equipment_photos[]"
                                type="file"
                                accept="image/jpeg,image/png,image/webp"
                                multiple
                                class="hidden"
                                onchange="previewEquipmentImages(event)"
                            >

                        </label>


                        {{-- Image previews --}}
                        <div id="imagePreview"
                             class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-4">
                        </div>


                        @error('equipment_photos')
                            <p class="mt-1 text-sm"
                               style="color: var(--critical);">
                                {{ $message }}
                            </p>
                        @enderror

                        @error('equipment_photos.*')
                            <p class="mt-1 text-sm"
                               style="color: var(--critical);">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </fieldset>


                {{-- SERVICE TYPE --}}
                <fieldset>

                    <legend class="text-xs uppercase tracking-wide mb-3"
                            style="color: #7A8A85;">
                        Service needed
                    </legend>


                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">


                        {{-- Service --}}
                        <div>

                            <label for="service_type"
                                   class="block text-sm mb-1">
                                Type of service
                            </label>

                            <select
                                name="service_type"
                                id="service_type"
                                required
                                class="w-full px-3 py-2 rounded"
                                style="border: 1px solid var(--line);
                                       background: var(--surface);">

                                <option value="">
                                    Select one
                                </option>

                                @foreach ($serviceTypes as $value => $label)

                                    <option value="{{ $value }}"
                                        @selected(old('service_type', request('service_type')) === $value)>
                                        {{ $label }}
                                    </option>

                                @endforeach

                            </select>

                            @error('service_type')
                                <p class="mt-1 text-sm"
                                   style="color: var(--critical);">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Urgency --}}
                        <div>

                            <label for="urgency"
                                   class="block text-sm mb-1">
                                Urgency
                            </label>

                            <select
                                name="urgency"
                                id="urgency"
                                class="w-full px-3 py-2 rounded"
                                style="border: 1px solid var(--line);
                                       background: var(--surface);">

                                @foreach ($urgencyLevels as $value => $label)

                                    <option value="{{ $value }}"
                                        @selected(old('urgency', 'normal') === $value)>
                                        {{ $label }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                </fieldset>


                {{-- DESCRIPTION --}}
                <div>

                    <label for="issue_description"
                           class="block text-sm mb-1">
                        Describe the issue
                    </label>

                    <textarea
                        name="issue_description"
                        id="issue_description"
                        rows="4"
                        required
                        placeholder="What's wrong, when it started, and anything an engineer should know before arriving."
                        class="w-full px-3 py-2 rounded"
                        style="border: 1px solid var(--line);
                               background: var(--surface);">{{ old('issue_description') }}</textarea>

                    @error('issue_description')
                        <p class="mt-1 text-sm"
                           style="color: var(--critical);">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- PREFERRED DATE --}}
                <div class="max-w-xs">

                    <label for="preferred_date"
                           class="block text-sm mb-1">

                        Preferred date

                        <span style="color:#9AA6A2;">
                            (optional)
                        </span>

                    </label>

                    <input
                        type="date"
                        name="preferred_date"
                        id="preferred_date"
                        value="{{ old('preferred_date') }}"
                        class="w-full px-3 py-2 rounded"
                        style="border: 1px solid var(--line);
                               background: var(--surface);">

                </div>


                {{-- SUBMIT --}}
                <div class="pt-4"
                     style="border-top: 1px solid var(--line);">

                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded text-white text-sm font-medium"
                        style="background: var(--teal);">

                        Submit service request

                    </button>

                </div>

            </form>

        </div>


        {{-- RIGHT SIDEBAR --}}
        <aside class="text-sm space-y-6 md:pt-24"
               style="color: #5B6B66;">

            <div>

                <p class="req-serif text-base mb-1"
                   style="color: var(--ink);">
                    What happens next
                </p>

                <p>
                    An admin reviews the request and assigns it to an approved
                    biomedical engineer. You'll be notified once someone is assigned.
                </p>

            </div>


            <div>

                <p class="req-serif text-base mb-1"
                   style="color: var(--ink);">
                    Why upload a photo?
                </p>

                <p>
                    A clear photo helps the engineer identify the equipment,
                    visible damage, error messages and parts that may need attention
                    before arriving.
                </p>

            </div>


            <div>

                <p class="req-serif text-base mb-1"
                   style="color: var(--ink);">
                    Marking urgency
                </p>

                <p>
                    Use
                    <span style="color: var(--critical);">
                        Critical
                    </span>
                    only when the equipment is down and directly affecting patient care.
                </p>

            </div>

        </aside>

    </div>

</div>


{{-- IMAGE PREVIEW SCRIPT --}}
<script>

    function previewEquipmentImages(event) {

        const files = event.target.files;
        const previewContainer = document.getElementById('imagePreview');

        previewContainer.innerHTML = '';

        if (files.length > 5) {

            alert('You can upload a maximum of 5 images.');

            event.target.value = '';

            return;
        }


        Array.from(files).forEach((file) => {

            if (!file.type.startsWith('image/')) {
                return;
            }


            const reader = new FileReader();


            reader.onload = function(e) {

                const wrapper = document.createElement('div');

                wrapper.className =
                    'relative overflow-hidden rounded-lg border border-slate-200 bg-slate-100';


                const image = document.createElement('img');

                image.src = e.target.result;

                image.className =
                    'h-32 w-full object-cover';


                wrapper.appendChild(image);

                previewContainer.appendChild(wrapper);

            };


            reader.readAsDataURL(file);

        });

    }

</script>

@endsection

