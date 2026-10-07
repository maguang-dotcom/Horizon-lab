@extends('admin.layout')

@php
    $sectionDetails = [
        'engineers' => ['Engineers', 'Review engineer profiles and approve qualified applicants.'],
        'service-requests' => ['Service Requests', 'Review hospital requests and assign an approved engineer.'],
        'facilities' => ['Facilities', 'View registered hospitals and their equipment and service activity.'],
        'assignments' => ['Assignments', 'Track active service requests that have an assigned engineer.'],
        'reports' => ['Service Reports', 'Review completed engineer reports and service cost estimates.'],
        'users' => ['Users', 'Review account roles and linked engineer or facility profiles.'],
    ];
    [$heading, $description] = $sectionDetails[$section];
@endphp

@section('title', $heading)

@section('content')
    <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">Horizon Lab Admin</p>
            <h1 class="mt-1 text-3xl font-bold text-slate-900">{{ $heading }}</h1>
            <p class="mt-2 text-sm text-slate-600">{{ $description }}</p>
        </div>
        <p class="text-sm text-slate-500">{{ number_format($records->total() + ($biomedicalRecords?->total() ?? 0)) }} records</p>
    </div>

    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                @if ($section === 'engineers')
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-4 py-3">Engineer</th><th class="px-4 py-3">Specialization</th><th class="px-4 py-3">License</th><th class="px-4 py-3">Status</th><th class="px-4 py-3 text-right">Action</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($records as $engineer)
                            <tr>
                                <td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $engineer->user?->name ?? 'Unknown engineer' }}</p><p class="text-xs text-slate-500">{{ $engineer->user?->email ?? 'No account email' }}</p></td>
                                <td class="px-4 py-4 text-slate-700">{{ $engineer->specialization ?? $engineer->professional_title ?? 'Not provided' }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $engineer->license_number ?? 'Not provided' }}</td>
                                <td class="px-4 py-4"><span @class(['rounded-full px-2.5 py-1 text-xs font-semibold', 'bg-emerald-100 text-emerald-800' => $engineer->admin_approved, 'bg-amber-100 text-amber-800' => ! $engineer->admin_approved])>{{ $engineer->admin_approved ? 'Approved' : 'Pending approval' }}</span></td>
                                <td class="px-4 py-4 text-right">@unless ($engineer->admin_approved)<form method="POST" action="{{ route('admin.engineers.approve', $engineer->user_id) }}" class="inline">@csrf<button class="rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700" type="submit">Approve</button></form>@endunless</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">No engineer profiles are registered.</td></tr>
                        @endforelse
                    </tbody>
                @elseif ($section === 'service-requests' || $section === 'assignments')
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-4 py-3">Request</th><th class="px-4 py-3">Facility</th><th class="px-4 py-3">Equipment / Issue</th><th class="px-4 py-3">Priority</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Assigned engineer</th><th class="px-4 py-3">Assignment</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($records as $request)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-4 font-semibold text-slate-900">{{ $request->form_ref ?? 'REQ-'.$request->id }}<p class="text-xs font-normal text-slate-500">{{ $request->created_at?->format('M d, Y') }}</p></td>
                                <td class="px-4 py-4 text-slate-700">{{ $request->facility?->name ?? 'Unknown facility' }}</td>
                                <td class="max-w-sm px-4 py-4 text-slate-700">{{ $request->equipment?->name ?? 'Equipment' }}<p class="truncate text-xs text-slate-500">{{ $request->problem_classification ?? 'No issue description' }}</p></td>
                                <td class="px-4 py-4 capitalize text-slate-700">{{ str_replace('_', ' ', $request->urgency_tier ?? 'normal') }}</td>
                                <td class="px-4 py-4 capitalize text-slate-700">{{ str_replace('_', ' ', $request->status) }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $request->assignedEngineer?->user?->name ?? 'Unassigned' }}</td>
                                <td class="min-w-64 px-4 py-4"><form method="POST" action="{{ route('admin.service-requests.assign', $request->id) }}" class="flex gap-2">@csrf<select name="engineer_profile_id" required class="min-w-0 flex-1 rounded-lg border border-slate-300 px-2 py-2 text-xs" aria-label="Engineer for request {{ $request->form_ref ?? $request->id }}"><option value="">Select engineer</option>@foreach ($approvedEngineers as $engineer)<option value="{{ $engineer->id }}" @selected($request->assigned_engineer_profile_id === $engineer->id)>{{ $engineer->user?->name ?? 'Engineer' }}</option>@endforeach</select><button type="submit" @disabled($approvedEngineers->isEmpty()) class="rounded-lg bg-blue-700 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-50">Assign</button></form></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-10 text-center text-slate-500">No legacy {{ $section === 'assignments' ? 'assignments' : 'service requests' }} found.</td></tr>
                        @endforelse
                    </tbody>
                @elseif ($section === 'facilities')
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-4 py-3">Facility</th><th class="px-4 py-3">Facility type</th><th class="px-4 py-3">District / Address</th><th class="px-4 py-3">Contact</th><th class="px-4 py-3">Equipment</th><th class="px-4 py-3">Requests</th><th class="px-4 py-3">Status</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($records as $facility)
                            <tr><td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $facility->name }}</p><p class="text-xs text-slate-500">{{ $facility->user?->email ?? 'No linked account' }}</p></td><td class="px-4 py-4 text-slate-700">{{ $facility->facility_type ?? 'Not specified' }}</td><td class="px-4 py-4 text-slate-700">{{ $facility->district ?? $facility->address ?? 'Not provided' }}</td><td class="px-4 py-4 text-slate-700">{{ $facility->contact_phone ?? 'Not provided' }}</td><td class="px-4 py-4 text-slate-700"><p>{{ $facility->equipment->take(2)->pluck('name')->join(', ') ?: 'No equipment' }}</p><p class="text-xs text-slate-500">{{ $facility->equipment_count }} total</p></td><td class="px-4 py-4 text-slate-700">{{ $facility->service_requests_count + $facility->biomedical_service_requests_count }}</td><td class="px-4 py-4 capitalize text-slate-700">{{ $facility->status ?? 'Active' }}</td></tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-10 text-center text-slate-500">No facilities are registered.</td></tr>
                        @endforelse
                    </tbody>
                @elseif ($section === 'reports')
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-4 py-3">Report</th><th class="px-4 py-3">Facility</th><th class="px-4 py-3">Engineer</th><th class="px-4 py-3">Problem found</th><th class="px-4 py-3">Total estimate</th><th class="px-4 py-3">Submitted</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($records as $report)
                            <tr><td class="px-4 py-4 font-semibold text-slate-900">#{{ $report->id }}<p class="text-xs font-normal text-slate-500">Request #{{ $report->biomedical_service_request_id }}</p></td><td class="px-4 py-4 text-slate-700">{{ $report->request?->facility?->name ?? 'Unknown facility' }}</td><td class="px-4 py-4 text-slate-700">{{ $report->engineer?->name ?? 'Unknown engineer' }}</td><td class="max-w-md px-4 py-4 text-slate-700"><span class="line-clamp-2">{{ $report->problem_found }}</span></td><td class="whitespace-nowrap px-4 py-4 font-medium text-slate-900">{{ number_format((float) $report->total_cost, 2) }} {{ config('app.currency', 'UGX') }}</td><td class="whitespace-nowrap px-4 py-4 text-slate-600">{{ $report->created_at?->format('M d, Y') }}</td></tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">No service reports have been submitted.</td></tr>
                        @endforelse
                    </tbody>
                @else
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-4 py-3">User</th><th class="px-4 py-3">Role</th><th class="px-4 py-3">Linked profile</th><th class="px-4 py-3">Registered</th><th class="px-4 py-3 text-right">Account</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($records as $user)
                            <tr><td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $user->name }}</p><p class="text-xs text-slate-500">{{ $user->email }}</p></td><td class="px-4 py-4 capitalize text-slate-700">{{ $user->role }}</td><td class="px-4 py-4 text-slate-700">{{ $user->engineerProfile?->professional_title ?? $user->currentFacility?->name ?? 'No linked profile' }}</td><td class="whitespace-nowrap px-4 py-4 text-slate-600">{{ $user->created_at?->format('M d, Y') }}</td><td class="px-4 py-4 text-right"><a href="{{ route('admin.users.show', $user) }}" class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-50">View account</a></td></tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">No users found.</td></tr>
                        @endforelse
                    </tbody>
                @endif
            </table>
        </div>
        @if (in_array($section, ['service-requests', 'assignments'], true) && $biomedicalRecords)
            <div class="overflow-x-auto border-t border-slate-200">
                <div class="px-4 py-3 text-sm font-semibold text-slate-700">Facility-submitted service requests</div>
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-4 py-3">Request</th><th class="px-4 py-3">Facility</th><th class="px-4 py-3">Equipment / issue</th><th class="px-4 py-3">Priority</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Assigned engineer</th><th class="px-4 py-3">Assignment</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($biomedicalRecords as $request)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-4 font-semibold text-slate-900">{{ 'SR-'.str_pad((string) $request->id, 4, '0', STR_PAD_LEFT) }}<p class="text-xs font-normal text-slate-500">{{ $request->created_at?->format('M d, Y') }}</p></td>
                                <td class="px-4 py-4 text-slate-700">{{ $request->facility?->name ?? 'Unknown facility' }}</td>
                                <td class="max-w-sm px-4 py-4 text-slate-700">{{ $request->equipment_name }}<p class="truncate text-xs text-slate-500">{{ $request->issue_description ?? 'No issue description' }}</p></td>
                                <td class="px-4 py-4 capitalize text-slate-700">{{ str_replace('_', ' ', $request->urgency ?? 'normal') }}</td>
                                <td class="px-4 py-4 capitalize text-slate-700">{{ str_replace('_', ' ', $request->status) }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $request->engineer?->name ?? 'Unassigned' }}</td>
                                <td class="min-w-64 px-4 py-4"><form method="POST" action="{{ route('admin.biomedical-service-requests.assign', $request->id) }}" class="flex gap-2">@csrf<select name="engineer_profile_id" required class="min-w-0 flex-1 rounded-lg border border-slate-300 px-2 py-2 text-xs" aria-label="Engineer for service request SR-{{ $request->id }}"><option value="">Select engineer</option>@foreach ($approvedEngineers as $engineer)<option value="{{ $engineer->id }}" @selected($request->engineer_id === $engineer->user_id)>{{ $engineer->user?->name ?? 'Engineer' }}</option>@endforeach</select><button type="submit" @disabled($approvedEngineers->isEmpty()) class="rounded-lg bg-blue-700 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-50">Assign</button></form></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-8 text-center text-slate-500">No facility-submitted {{ $section === 'assignments' ? 'assignments' : 'requests' }} found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="border-t border-slate-200 px-4 py-3">{{ $biomedicalRecords->links() }}</div>
            </div>
        @endif
        <div class="border-t border-slate-200 px-4 py-3">{{ $records->links() }}</div>
    </section>
@endsection