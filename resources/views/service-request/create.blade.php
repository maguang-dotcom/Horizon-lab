@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<main class="request-engineer-page min-h-screen bg-slate-50 text-slate-800">
<div
    x-data="requestEngineerForm({
        equipmentList: @js($equipment),
        storeUrl: '{{ route('facility.service-requests.store') }}',
        csrf: '{{ csrf_token() }}',
    })"
    class="request-engineer-wizard mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8"

    @php($facility = auth()->user()?->currentFacility)
    <header class="wizard-header mb-5 max-w-3xl">
        <span class="eyebrow text-xs font-bold uppercase tracking-[0.14em] text-teal-700">SERVICE REQUEST DASHBOARD · FORM ID: {{ 'HL-DISP-' . random_int(1000, 9999) }}</span>
        <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-5xl">Deploy Clinical Engineer</h1>
        <p class="mt-3 text-base leading-7 text-slate-500">Submit a verified equipment request, attach the clinical evidence, and dispatch the right biomedical technician.</p>
    </header>

    {{-- Step indicator --}}
    <nav class="step-indicator mb-5 grid grid-cols-2 gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-sm sm:grid-cols-5">
        <template x-for="(label, index) in steps" :key="index">
            <div class="step rounded-lg px-3 py-3" :class="{ active: step === index + 1, done: step > index + 1 }">
                <span class="step-number h-7 w-7" x-text="step > index + 1 ? '✓' : String(index + 1).padStart(2, '0')"></span>
                <span class="step-label text-xs font-bold" x-text="label"></span>
            </div>
        </template>
    </nav>

    <div class="dashboard-layout">
    <form @submit.prevent="submit" class="wizard-panel space-y-5">
        {{-- STEP 1: Equipment --}}
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            <h2 class="text-lg font-bold text-slate-900">1. Select Impacted Medical Device</h2>
            <p class="hint mt-1 text-sm text-slate-500">Validated against Clinical Asset Inventory Database</p>

            <template x-if="selectedEquipment">
                <div class="flex flex-col items-start justify-between gap-4 border border-blue-100 bg-blue-50 p-4 sm:flex-row sm:items-center">
                    <div>
                        <strong class="block text-sm font-semibold text-slate-800" x-text="selectedEquipment.name"></strong>
                        <span class="ml-2 inline-block bg-teal-100 px-2 py-1 text-[10px] font-bold uppercase tracking-wide text-teal-700" x-show="selectedEquipment.oem_verified">OEM Verified</span>
                        <div class="mt-1 text-xs text-slate-500">
                            Serial: #<span x-text="selectedEquipment.serial_number"></span>
                            · Location: <span x-text="selectedEquipment.location_label"></span>
                        </div>
                    </div>
                    <button type="button" class="bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-200" @click="selectedEquipment = null">Change Device</button>
                </div>
            </template>

            <template x-if="!selectedEquipment">
                <ul class="mb-8 grid list-none gap-3 p-0">
                    <template x-for="item in equipmentList" :key="item.id">
                        <li @click="selectedEquipment = item" class="cursor-pointer border border-blue-100 bg-blue-50 p-4 transition hover:border-teal-600 hover:bg-teal-50">
                            <strong class="block text-sm font-semibold text-slate-800" x-text="item.name"></strong>
                            <span class="mt-1 block text-xs text-slate-500" x-text="item.location_label"></span>
                        </li>
                    </template>
                </ul>
            </template>

            <button type="button" class="bg-teal-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-teal-800 disabled:cursor-not-allowed disabled:opacity-40" :disabled="!selectedEquipment" @click="step = 2">Continue</button>
        </section>

        {{-- STEP 2: Diagnostic --}}
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            <h2 class="text-lg font-bold text-slate-900">2. Describe the Diagnostic Malfunction</h2>
            <p class="hint mt-1 text-sm text-slate-500">Classify the observed symptoms for algorithmic engineer pre-screening</p>

            <label class="mb-2 mt-6 flex justify-between text-xs font-bold text-slate-700">Problem Classification Taxonomy</label>
            <div class="flex flex-wrap gap-2">
                <template x-for="option in classifications" :key="option">
                    <button
                        type="button"
                        class="border border-slate-300 bg-white px-3 py-2 text-xs text-slate-600 transition hover:border-blue-900 hover:bg-blue-900 hover:text-white"
                        :class="{ selected: classification === option }"
                        @click="classification = option"
                        x-text="option"
                    ></button>
                </template>
            </div>

            <label for="diagnostic_notes" class="mb-2 mt-6 flex justify-between text-xs font-bold text-slate-700">
                Detailed Clinical Observations
                <span class="text-[11px] font-medium text-slate-400" x-text="`${diagnosticNotes.length} / 1000 characters`"></span>
            </label>
            <textarea
                id="diagnostic_notes"
                x-model="diagnosticNotes"
                maxlength="1000"
                rows="4"
                placeholder="Describe symptoms, prior interventions, and anything already tried..."
                class="w-full border border-slate-300 bg-slate-50 p-3 text-sm text-slate-800 outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-100"
            ></textarea>

            <div class="wizard-nav">
                <button type="button" @click="step = 1">Back</button>
                <button type="button" :disabled="!classification || diagnosticNotes.length < 10" @click="step = 3">Continue</button>
            </div>
        </section>

        {{-- STEP 3: Logs & Evidence --}}
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            <h2 class="text-lg font-bold text-slate-900">3. Logs &amp; Evidence</h2>
            <p class="hint mt-1 text-sm text-slate-500">Attach device error logs, photos, or exported diagnostics (PDF, JPG, PNG — 10MB max each)</p>

            <input type="file" multiple @change="addFiles($event)" accept=".pdf,.jpg,.jpeg,.png" class="w-full border border-dashed border-slate-300 bg-slate-50 p-5 text-sm text-slate-600 file:mr-4 file:border-0 file:bg-teal-700 file:px-4 file:py-2 file:font-semibold file:text-white" />

            <ul class="mt-4 list-none p-0">
                <template x-for="(file, index) in attachments" :key="index">
                    <li class="flex items-center justify-between gap-4 border-b border-slate-200 py-3 text-sm">
                        <span class="truncate text-slate-700" x-text="file.name"></span>
                        <span class="text-xs text-slate-500" x-text="formatSize(file.size)"></span>
                        <button type="button" class="bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-200" @click="attachments.splice(index, 1)">Remove</button>
                    </li>
                </template>
            </ul>

            <div class="mt-8 flex justify-between border-t border-slate-200 pt-5">
                <button type="button" class="bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-200" @click="step = 2">Back</button>
                <button type="button" class="bg-teal-700 px-5 py-3 text-sm font-semibold text-white hover:bg-teal-800" @click="step = 4">Continue</button>
            </div>
        </section>

        {{-- STEP 4: Facility context --}}
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            <h2 class="text-lg font-bold text-slate-900">4. Facility Location &amp; Proximity</h2>
            <p class="hint mt-1 text-sm text-slate-500">Real-time dispatch context for the incoming mobile unit</p>
            <div class="flex flex-col items-start justify-between gap-4 border border-blue-100 bg-blue-50 p-4 sm:flex-row sm:items-center">
                <div>
                    <strong class="block text-sm font-semibold text-slate-800">{{ $facility?->name ?? 'Facility profile pending' }}</strong>
                    <span class="mt-1 block text-xs text-slate-500">{{ $facility?->address ?? 'Add an approved facility address' }}</span>
                    <span class="mt-1 block text-xs text-slate-500">Engineer distance is calculated from your saved facility coordinates.</span>
                </div>
                <span class="bg-teal-100 px-2 py-1 text-[10px] font-bold uppercase tracking-wide text-teal-700">Live dispatch</span>
            </div>
        </section>

        {{-- STEP 5: Urgency (feeds sla_minutes on the backend) --}}
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            <h2 class="text-lg font-bold text-slate-900">5. Clinical Urgency Protocol</h2>
            <p class="hint mt-1 text-sm text-slate-500">Dictates technician mobilisation SLA and emergency escalation.</p>
            <div class="flex flex-wrap gap-2">
                <template x-for="tier in urgencyTiers" :key="tier.value">
                    <button
                        type="button"
                        class="border border-slate-300 bg-white px-3 py-2 text-xs text-slate-600 transition hover:border-blue-900 hover:bg-blue-900 hover:text-white"
                        :class="{ selected: urgency === tier.value }"
                        @click="urgency = tier.value"
                        x-text="tier.label"
                    ></button>
                </template>
            </div>

            <div class="mt-8 flex justify-between border-t border-slate-200 pt-5">
                <button type="button" class="bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-200" @click="step = 4">Back</button>
                <button type="submit" class="bg-teal-700 px-5 py-3 text-sm font-semibold text-white hover:bg-teal-800 disabled:cursor-not-allowed disabled:opacity-40" :disabled="submitting">
                    <span x-show="!submitting">Run Autonomous Matching</span>
                    <span x-show="submitting">Matching…</span>
                </button>
            </div>
        </section>
    </form>

    @if (false)
    <aside class="dashboard-rail">
        <section class="dashboard-card">
            <span class="card-eyebrow">Facility location</span>
            <h3>Dispatch coordinates</h3>
            <div class="location-row">
                <span class="location-icon">⌖</span>
                <div>
                    <strong>{{ $facility?->name ?? 'Facility profile pending' }}</strong>
                    <span>{{ $facility?->address ?? 'Add an approved facility address' }}</span>
                </div>
            </div>
            <div class="location-row">
                <span class="location-icon">↗</span>
                <div>
                    <strong>Proximity matching</strong>
                    <span>Engineer distance is calculated from facility coordinates.</span>
                </div>
            </div>
        </section>

        <section class="dashboard-card dark">
            <span class="card-eyebrow">Clinical dispatch</span>
            <h3>Request readiness</h3>
            <p class="text-sm leading-relaxed">OEM credentials, toolkit stock, bio-safety clearance, and proximity are checked before matching.</p>
        </section>

        <section class="dashboard-card">
            <span class="card-eyebrow">Urgency protocol</span>
            <h3>Mobilisation SLA</h3>
            <template x-for="tier in urgencyTiers" :key="tier.value">
                <div class="urgency-option" :class="{ selected: urgency === tier.value }" @click="urgency = tier.value">
                    <strong x-text="tier.label"></strong>
                    <span x-text="tier.value === 'critical' ? 'Life-support failure or operating theatre halt' : tier.value === 'high' ? 'Potential clinical throughput delay' : 'Routine or non-critical equipment support'"></span>
                </div>
            </template>
        </section>
    </aside>
    @endif
    </div>

    {{-- Match results panel --}}
    <aside x-show="matchResult" x-cloak class="matching-panel">
        <h3>Autonomous Matching <span class="tag">AI ENGINE v4.8</span></h3>
        <template x-if="matchResult && matchResult.matches.length">
            <div>
                <p class="probability">
                    Top candidate: <strong x-text="matchResult.matches[0].engineer.user.name"></strong>
                    · Est. SLA <span x-text="matchResult.matches[0].estimated_sla_minutes"></span> min
                </p>
                <ul class="criteria-list">
                    <template x-for="(criterion, key) in matchResult.matches[0].criteria" :key="key">
                        <li :class="criterion.passed ? 'pass' : 'fail'">
                            <span x-text="criterion.label"></span>
                            <span x-text="criterion.passed ? '✓' : '✗'"></span>
                        </li>
                    </template>
                </ul>
            </div>
        </template>
        <template x-if="matchResult && !matchResult.matches.length">
            <p>No eligible engineers matched this request's criteria yet.</p>
        </template>
    </aside>
</div>
</main>
@endsection

@push('scripts')
<script>
function requestEngineerForm({ equipmentList, storeUrl, csrf }) {
    return {
        step: 1,
        steps: ['Equipment', 'Diagnostics', 'Logs & Evidence', 'Facility GPS', 'Urgency SLA'],
        equipmentList,
        selectedEquipment: null,
        classifications: [
            'Equipment not powering on',
            'Incorrect Readings / Calibration Problem',
            'Mechanical Jam / Transducer Fault',
            'Electrical Grounding / Intermittent Short',
            'Preventive Maintenance (PM) Routine',
            'Firmware / IoMT Protocol Lockout',
        ],
        classification: null,
        diagnosticNotes: '',
        attachments: [],
        urgencyTiers: [
            { value: 'standard', label: 'Standard' },
            { value: 'high', label: 'High (45m SLA)' },
            { value: 'critical', label: 'Critical (30m SLA)' },
        ],
        urgency: 'standard',
        submitting: false,
        matchResult: null,

        addFiles(event) {
            const incoming = Array.from(event.target.files).filter(f => f.size <= 10 * 1024 * 1024);
            this.attachments.push(...incoming);
        },

        formatSize(bytes) {
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        },

        async submit() {
            this.submitting = true;

            const formData = new FormData();
            formData.append('equipment_id', this.selectedEquipment.id);
            formData.append('problem_classification', this.classification);
            formData.append('diagnostic_notes', this.diagnosticNotes);
            formData.append('urgency_tier', this.urgency);
            this.attachments.forEach(file => formData.append('attachments[]', file));

            try {
                const res = await fetch(storeUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: formData,
                });

                if (!res.ok) {
                    const err = await res.json();
                    alert(err.message || 'Something went wrong submitting the request.');
                    return;
                }

                this.matchResult = await res.json();
            } finally {
                this.submitting = false;
            }
        },
    };
}
</script>
@endpush