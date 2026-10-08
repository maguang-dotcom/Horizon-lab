<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\BiomedicalServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacilityServiceRequestController extends Controller
{
    /**
     * List the facility's service requests and the service types it can request.
     */
    public function index(Request $request): View
    {
        $facility = $request->user()->currentFacility;

        return view('HealthyFacility.service-requests', [
            'requests' => $facility
                ? BiomedicalServiceRequest::query()
                    ->where('facility_id', $facility->id)
                    ->latest()
                    ->paginate(10)
                : null,
            'serviceTypes' => BiomedicalServiceRequest::SERVICE_TYPES,
        ]);
    }

    /**
     * Show the request form for the logged-in facility.
     */
    public function create(): View
    {
        return view('HealthyFacility.create', [
            'serviceTypes' => BiomedicalServiceRequest::SERVICE_TYPES,
            'urgencyLevels' => BiomedicalServiceRequest::URGENCY_LEVELS,
        ]);
    }

    /**
     * Store a new biomedical service request submitted by the facility.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'equipment_name' => ['required', 'string', 'max:255'],
            'equipment_model' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'service_type' => ['required', 'in:repair,preventive_maintenance,calibration,installation,other'],
            'urgency' => ['required', 'in:low,normal,high,critical'],
            'issue_description' => ['required', 'string', 'max:2000'],
            'preferred_date' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        // Facility is inferred from the authenticated facility user,
        // matching the single-facility-per-account model used elsewhere
        // in the approval workflow.
        $facility = $request->user()->currentFacility;
        abort_unless($facility, 403, 'This account is not linked to a facility.');

        $validated['facility_id'] = $facility->id;
        $validated['status'] = 'pending';

        BiomedicalServiceRequest::create($validated);

        return redirect()
            ->route('facility.service-requests.create')
            ->with('status', 'Request submitted. An admin will assign an engineer shortly.');
    }
}
