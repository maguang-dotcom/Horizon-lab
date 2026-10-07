<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    //
    public function create(Request $request)
    {
        $facility = $request->user()?->currentFacility;
        $equipment = $facility
            ? Equipment::query()
                ->where('facility_id', $facility->id)
                ->orderBy('name')
                ->get()
            : collect();

        return view('service-request.create', compact('equipment'));
    }
}