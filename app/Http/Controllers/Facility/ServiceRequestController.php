<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequestRequest;
use App\Models\Equipment;
use App\Models\ServiceRequest;
use App\Services\MatchingService;
use Illuminate\Support\Str;

class ServiceRequestController extends Controller
{
    public function store(StoreServiceRequestRequest $request, MatchingService $matching)
    {
        $facility = $request->user()->currentFacility;
        $equipment = Equipment::where('facility_id', $facility->id)->findOrFail($request->integer('equipment_id'));

        $serviceRequest = ServiceRequest::create([
            'form_ref' => 'HL-DISP-'.Str::padLeft((string) random_int(1000, 9999), 4, '0'),
            'facility_id' => $facility->id,
            'equipment_id' => $equipment->id,
            'requested_by' => $request->user()->id,
            'problem_classification' => $request->string('problem_classification')->toString(),
            'diagnostic_notes' => $request->string('diagnostic_notes')->toString(),
            'urgency_tier' => $request->string('urgency_tier')->toString(),
            'sla_minutes' => match ($request->string('urgency_tier')->toString()) {
                'critical' => 30,
                'high' => 45,
                default => 120,
            },
        ]);

        foreach ($request->file('attachments', []) as $file) {
            $path = $file->store('service-requests/'.$serviceRequest->id, 'private');
            $serviceRequest->attachments()->create([
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'size_bytes' => $file->getSize(),
            ]);
        }

        $matches = $matching->match($serviceRequest);
        $serviceRequest->update(['matched_at' => now()]);

        return response()->json([
            'service_request' => $serviceRequest->fresh(),
            'matches' => $matches,
        ]);
    }

    public function assign(ServiceRequest $serviceRequest, int $engineerProfileId)
    {
        abort_unless($serviceRequest->facility_id === request()->user()->currentFacility?->id, 403);

        $serviceRequest->update([
            'assigned_engineer_profile_id' => $engineerProfileId,
            'status' => 'assigned',
        ]);

        return back()->with('success', 'Engineer dispatched.');
    }
}
