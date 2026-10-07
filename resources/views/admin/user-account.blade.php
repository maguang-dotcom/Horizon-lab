@extends('admin.layout')

@section('title', $user->name.' Account')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-blue-700 hover:text-blue-900">&larr; Users</a>
        <div class="mt-4 flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">User account</p>
                <h1 class="mt-1 text-3xl font-bold text-slate-900">{{ $user->name }}</h1>
                <p class="mt-1 text-sm text-slate-600">{{ $user->email }}</p>
            </div>
            <span class="rounded-full bg-blue-100 px-3 py-1.5 text-sm font-semibold capitalize text-blue-800">{{ $user->role }}</span>
        </div>
    </div>

    <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_minmax(0,1.5fr)]">
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">Account details</h2>
            <dl class="mt-4 divide-y divide-slate-100">
                <div class="py-3"><dt class="text-xs font-semibold uppercase text-slate-500">Email</dt><dd class="mt-1 break-all text-sm text-slate-800">{{ $user->email }}</dd></div>
                <div class="py-3"><dt class="text-xs font-semibold uppercase text-slate-500">Role</dt><dd class="mt-1 capitalize text-sm text-slate-800">{{ $user->role }}</dd></div>
                <div class="py-3"><dt class="text-xs font-semibold uppercase text-slate-500">Account created</dt><dd class="mt-1 text-sm text-slate-800">{{ $user->created_at?->format('M d, Y g:i A') ?? 'Unknown' }}</dd></div>
                <div class="py-3"><dt class="text-xs font-semibold uppercase text-slate-500">Email verification</dt><dd class="mt-1 text-sm text-slate-800">{{ $user->email_verified_at ? 'Verified' : 'Not verified' }}</dd></div>
            </dl>
        </section>

        @if ($user->engineerProfile)
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-lg font-bold text-slate-900">Engineer profile</h2>
                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $engineerApproved ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ $engineerApproved ? 'Approved' : 'Pending approval' }}</span>
                </div>
                <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div><dt class="text-xs font-semibold uppercase text-slate-500">Professional title</dt><dd class="mt-1 text-sm text-slate-800">{{ $user->engineerProfile->professional_title ?? 'Not provided' }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-slate-500">Specialization</dt><dd class="mt-1 text-sm text-slate-800">{{ $user->engineerProfile->specialization ?? 'Not provided' }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-slate-500">License number</dt><dd class="mt-1 text-sm text-slate-800">{{ $user->engineerProfile->license_number ?? 'Not provided' }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-slate-500">Experience</dt><dd class="mt-1 text-sm text-slate-800">{{ $user->engineerProfile->years_experience ?? 'Not provided' }}{{ $user->engineerProfile->years_experience !== null ? ' years' : '' }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-slate-500">Availability</dt><dd class="mt-1 text-sm text-slate-800">{{ $user->engineerProfile->is_available ? 'Available' : 'Unavailable' }}</dd></div>
                </dl>
            </section>
        @elseif ($facility)
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">Facility profile</h2>
                <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div><dt class="text-xs font-semibold uppercase text-slate-500">Facility</dt><dd class="mt-1 text-sm text-slate-800">{{ $facility->name }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-slate-500">Facility type</dt><dd class="mt-1 text-sm text-slate-800">{{ $facility->facility_type ?? 'Not specified' }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-slate-500">Address</dt><dd class="mt-1 text-sm text-slate-800">{{ $facility->address ?? 'Not provided' }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-slate-500">District</dt><dd class="mt-1 text-sm text-slate-800">{{ $facility->district ?? 'Not provided' }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-slate-500">Contact phone</dt><dd class="mt-1 text-sm text-slate-800">{{ $facility->contact_phone ?? 'Not provided' }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase text-slate-500">Status</dt><dd class="mt-1 capitalize text-sm text-slate-800">{{ $facility->status ?? 'Active' }}</dd></div>
                </dl>
            </section>
        @else
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">Linked profile</h2>
                <p class="mt-3 text-sm text-slate-600">No engineer or facility profile is linked to this account.</p>
            </section>
        @endif
    </div>

    @if ($facility)
        <section class="mt-5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-lg font-bold text-slate-900">Facility equipment</h2>
                <span class="text-sm text-slate-500">{{ $facilityEquipmentCount }} total</span>
            </div>
            <div class="mt-3 divide-y divide-slate-100">
                @forelse ($facilityEquipment as $equipment)
                    <div class="flex items-center justify-between gap-4 py-3"><span class="text-sm font-medium text-slate-800">{{ $equipment->name }}</span><span class="text-xs text-slate-500">{{ $equipment->manufacturer ?? '' }} {{ $equipment->model ?? '' }}</span></div>
                @empty
                    <p class="py-4 text-sm text-slate-500">No equipment is registered for this facility.</p>
                @endforelse
            </div>
        </section>

        <section class="mt-5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">Recent service requests</h2>
            <div class="mt-3 divide-y divide-slate-100">
                @forelse ($facilityRequests as $request)
                    <div class="flex flex-wrap items-center justify-between gap-3 py-3"><div><p class="text-sm font-semibold text-slate-800">{{ $request->form_ref ?? 'Request #'.$request->id }} · {{ $request->equipment?->name ?? 'Equipment' }}</p><p class="mt-1 text-xs text-slate-500">{{ $request->problem_classification ?? 'No issue description' }}</p></div><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs capitalize text-slate-700">{{ str_replace('_', ' ', $request->status) }}</span></div>
                @empty
                    <p class="py-4 text-sm text-slate-500">No service requests are linked to this facility.</p>
                @endforelse
            </div>
        </section>
    @endif

    @if ($user->role === 'engineer')
        <section class="mt-5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">Recent service reports</h2>
            <div class="mt-3 divide-y divide-slate-100">
                @forelse ($serviceReports as $report)
                    <div class="flex flex-wrap items-center justify-between gap-3 py-3"><div><p class="text-sm font-semibold text-slate-800">{{ $report->request?->facility?->name ?? 'Facility' }} · Request #{{ $report->biomedical_service_request_id }}</p><p class="mt-1 text-xs text-slate-500">{{ $report->problem_found }}</p></div><span class="whitespace-nowrap text-sm font-semibold text-slate-800">{{ number_format((float) $report->total_cost, 2) }} {{ config('app.currency', 'UGX') }}</span></div>
                @empty
                    <p class="py-4 text-sm text-slate-500">No service reports are linked to this engineer account.</p>
                @endforelse
            </div>
        </section>
    @endif
@endsection