@extends('layouts.hospital')

@section('title', 'Service Requests')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Service Requests</h1>
            <p class="text-sm text-slate-500">Track your requests or start a new one.</p>
        </div>
        <a href="{{ route('facility.service-requests.create') }}"
            class="px-4 py-2 rounded-lg text-white text-sm font-medium" style="background:#380ebf;">
            + New request
        </a>
    </div>

    <div class="bg-white rounded-xl p-5 mb-6" style="border:1px solid var(--line);">
        <p class="font-semibold mb-3">Request a service</p>
        <div class="flex flex-wrap gap-2">
            @foreach ($serviceTypes as $value => $label)
                <a href="{{ route('facility.service-requests.create', ['service_type' => $value]) }}"
                    class="px-3 py-2 rounded-lg text-sm border hover:bg-slate-50" style="border-color:#4609b7;color:#4813cd;">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl overflow-hidden" style="border:1px solid var(--line);">
        @if ($requests && $requests->count())
            <table class="w-full text-sm">
                <thead class="text-left text-slate-500 bg-slate-50">
                    <tr>
                        <th class="p-3">Equipment</th>
                        <th class="p-3">Service</th>
                        <th class="p-3">Urgency</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $serviceRequest)
                        <tr class="border-t" style="border-color:var(--line);">
                            <td class="p-3 font-medium">{{ $serviceRequest->equipment_name }}</td>
                            <td class="p-3">{{ $serviceTypes[$serviceRequest->service_type] ?? $serviceRequest->service_type }}</td>
                            <td class="p-3 capitalize">{{ $serviceRequest->urgency }}</td>
                            <td class="p-3 capitalize">{{ str_replace('_', ' ', $serviceRequest->status) }}</td>
                            <td class="p-3">{{ $serviceRequest->created_at->format('M j, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-3">{{ $requests->links() }}</div>
        @else
            <p class="p-8 text-center text-slate-500">No service requests yet. Choose a service above to get started.</p>
        @endif
    </div>
@endsection
