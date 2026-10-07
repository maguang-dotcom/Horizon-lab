<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiomedicalServiceRequest;
use App\Models\EngineerProfile;
use App\Models\Facility;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $pendingEngineers = EngineerProfile::with('user')
            ->when(Schema::hasColumn('engineer_profiles', 'is_approved'), function ($query) {
                $query->where('is_approved', false);
            }, function ($query) {
                $query->whereDoesntHave('credentials', function ($credentialsQuery) {
                    $credentialsQuery->where('admin_status', 'approved');
                });
            })
            ->latest()
            ->get();

        $approvedEngineers = EngineerProfile::with('user')
            ->when(Schema::hasColumn('engineer_profiles', 'is_approved'), function ($query) {
                $query->where('is_approved', true);
            }, function ($query) {
                $query->whereHas('credentials', function ($credentialsQuery) {
                    $credentialsQuery->where('admin_status', 'approved');
                });
            })
            ->where('is_available', true)
            ->latest()
            ->get();

        $biomedicalRequests = BiomedicalServiceRequest::with(['facility', 'engineer'])
            ->whereIn('status', ['pending', 'matching', 'assigned', 'in_progress'])
            ->latest()
            ->get();

        $legacyRequests = ServiceRequest::with(['facility', 'equipment', 'assignedEngineer.user'])
            ->whereIn('status', ['pending', 'matching', 'assigned', 'in_progress'])
            ->latest()
            ->get();

        $openRequests = $biomedicalRequests->map(fn (BiomedicalServiceRequest $request) => [
            'id' => $request->id,
            'reference' => 'SR-'.str_pad((string) $request->id, 4, '0', STR_PAD_LEFT),
            'facility' => $request->facility?->name ?? 'Unknown facility',
            'equipment' => $request->equipment_name,
            'issue' => $request->issue_description ?? 'General issue',
            'priority' => $request->urgency ?? 'normal',
            'status' => $request->status,
            'engineer' => $request->engineer?->name,
            'assignment_url' => route('admin.biomedical-service-requests.assign', $request),
            'created_at' => $request->created_at,
        ])->concat($legacyRequests->map(fn (ServiceRequest $request) => [
            'id' => $request->id,
            'reference' => $request->form_ref ?? 'REQ-'.$request->id,
            'facility' => $request->facility?->name ?? 'Unknown facility',
            'equipment' => $request->equipment?->name ?? 'Equipment',
            'issue' => $request->problem_classification ?? 'General issue',
            'priority' => $request->urgency_tier ?? 'normal',
            'status' => $request->status,
            'engineer' => $request->assignedEngineer?->user?->name,
            'assignment_url' => route('admin.service-requests.assign', $request),
            'created_at' => $request->created_at,
        ]))->sortByDesc('created_at')->values();

        $notifications = $pendingEngineers->map(fn (EngineerProfile $profile) => [
            'id' => 'engineer-'.$profile->id,
            'category' => 'engineer',
            'title' => 'Engineer approval needed',
            'message' => $profile->user?->name ?? 'New engineer',
            'url' => route('admin.engineers.index'),
            'time' => $profile->created_at?->diffForHumans() ?? 'just now',
            'created_at' => $profile->created_at,
        ])->concat($openRequests
            ->where('status', 'pending')
            ->map(fn (array $request) => [
                'id' => 'request-'.md5($request['assignment_url']),
                'category' => 'service-request',
                'title' => 'New service request',
                'message' => $request['facility'].' · '.$request['equipment'],
                'url' => route('admin.service-requests.index'),
                'time' => $request['created_at']?->diffForHumans() ?? 'just now',
                'created_at' => $request['created_at'],
            ]))
            ->sortByDesc('created_at')
            ->take(8)
            ->values();

        $facilityCount = Facility::count();

        $recentActivities = BiomedicalServiceRequest::with(['facility', 'engineer'])
            ->latest('updated_at')
            ->take(5)
            ->get()
            ->map(function ($request) {
                $engineerName = $request->engineer?->name;

                if ($engineerName && $request->status === 'assigned') {
                    $message = "Engineer {$engineerName} assigned to {$request->facility?->name}";
                } elseif ($engineerName && $request->status === 'in_progress') {
                    $message = "Service request updated for {$request->facility?->name}";
                } else {
                    $message = 'New service request created for '.$request->facility?->name;
                }

                return [
                    'status' => $request->status,
                    'message' => $message,
                    'time' => $request->updated_at?->diffForHumans() ?? 'just now',
                ];
            });

        return view('admin.dashboard', [
            'pendingEngineers' => $pendingEngineers,
            'approvedEngineers' => $approvedEngineers,
            'openRequests' => $openRequests,
            'notifications' => $notifications,
            'facilityCount' => $facilityCount,
            'recentActivities' => $recentActivities,
            'stats' => [
                'pendingEngineers' => $pendingEngineers->count(),
                'approvedEngineers' => $approvedEngineers->count(),
                'openRequests' => $openRequests->count(),
                'facilityCount' => $facilityCount,
            ],
        ]);
    }

    public function approveEngineer(User $user): RedirectResponse
    {
        $profile = $user->engineerProfile()->firstOrFail();

        if (Schema::hasColumn('engineer_profiles', 'is_approved')) {
            $profile->update([
                'is_approved' => true,
                'approved_at' => now(),
                'is_available' => true,
            ]);
        } else {
            $profile->credentials()->updateOrCreate(
                ['name' => 'admin_approval'],
                ['admin_status' => 'approved', 'expires_at' => now()->addYears(1)]
            );
        }

        return redirect()->route('admin.dashboard')->with('success', $user->name.' has been approved.');
    }

    public function assignEngineer(ServiceRequest $serviceRequest, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'engineer_profile_id' => ['required', 'integer', 'exists:engineer_profiles,id'],
        ]);

        $engineerProfile = EngineerProfile::where('id', $validated['engineer_profile_id'])
            ->when(Schema::hasColumn('engineer_profiles', 'is_approved'), function ($query) {
                $query->where('is_approved', true);
            }, function ($query) {
                $query->whereHas('credentials', function ($credentialsQuery) {
                    $credentialsQuery->where('admin_status', 'approved');
                });
            })
            ->firstOrFail();

        $serviceRequest->update([
            'assigned_engineer_profile_id' => $engineerProfile->id,
            'status' => 'assigned',
            'matched_at' => now(),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Engineer assigned to the request.');
    }

    public function assignBiomedicalEngineer(
        BiomedicalServiceRequest $biomedicalServiceRequest,
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'engineer_profile_id' => ['required', 'integer', 'exists:engineer_profiles,id'],
        ]);

        abort_unless(in_array($biomedicalServiceRequest->status, ['pending', 'assigned', 'in_progress'], true), 409);

        $engineerProfile = EngineerProfile::with('user')
            ->whereKey($validated['engineer_profile_id'])
            ->when(Schema::hasColumn('engineer_profiles', 'is_approved'), function ($query) {
                $query->where('is_approved', true);
            }, function ($query) {
                $query->whereHas('credentials', function ($credentialsQuery) {
                    $credentialsQuery->where('admin_status', 'approved');
                });
            })
            ->where('is_available', true)
            ->firstOrFail();

        $biomedicalServiceRequest->update([
            'engineer_id' => $engineerProfile->user_id,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Engineer assigned to the service request.');
    }
}
