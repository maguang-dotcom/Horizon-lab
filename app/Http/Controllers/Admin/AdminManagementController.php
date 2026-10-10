<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiomedicalServiceRequest;
use App\Models\EngineerProfile;
use App\Models\Facility;
use App\Models\ServiceReport;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminManagementController extends Controller
{
    public function engineers(): View
    {
        $hasApprovalColumn = Schema::hasColumn('engineer_profiles', 'is_approved');
        $engineers = EngineerProfile::with(['user', 'credentials'])
            ->latest()
            ->paginate(15);

        $engineers->getCollection()->each(function (EngineerProfile $engineer) use ($hasApprovalColumn): void {
            $engineer->setAttribute(
                'admin_approved',
                $hasApprovalColumn
                    ? (bool) $engineer->is_approved
                    : $engineer->credentials->contains(fn ($credential) => $credential->admin_status === 'approved')
            );
        });

        return $this->section('engineers', $engineers);
    }

    public function serviceRequests(): View
    {
        return $this->section(
            'service-requests',
            ServiceRequest::with(['facility', 'equipment', 'assignedEngineer.user'])
                ->latest()
                ->paginate(15),
            $this->approvedEngineers(),
            BiomedicalServiceRequest::with(['facility', 'engineer'])
                ->latest()
                ->paginate(15, ['*'], 'biomedical_page')
        );
    }

    public function facilities(): View
    {
        return $this->section(
            'facilities',
            Facility::with(['user', 'equipment:id,facility_id,name'])
                ->withCount(['equipment', 'serviceRequests', 'biomedicalServiceRequests'])
                ->latest()
                ->paginate(15)
        );
    }

    public function assignments(): View
    {
        return $this->section(
            'assignments',
            ServiceRequest::with(['facility', 'equipment', 'assignedEngineer.user'])
                ->whereNotNull('assigned_engineer_profile_id')
                ->latest('matched_at')
                ->paginate(15),
            $this->approvedEngineers(),
            BiomedicalServiceRequest::with(['facility', 'engineer'])
                ->whereNotNull('engineer_id')
                ->latest('assigned_at')
                ->paginate(15, ['*'], 'biomedical_page')
        );
    }

    public function reports(): View
    {
        return $this->section(
            'reports',
            ServiceReport::with(['request.facility', 'engineer'])
                ->latest()
                ->paginate(15)
        );
    }

    public function users(): View
    {
        return $this->section(
            'users',
            User::with(['engineerProfile', 'currentFacility'])
                ->latest()
                ->paginate(15)
        );
    }

    public function showUser(User $user): View
    {
        $user->load(['engineerProfile.credentials', 'currentFacility']);
        $facility = $user->currentFacility;
        $facilityEquipment = $facility?->equipment()->latest()->limit(5)->get() ?? collect();
        $facilityEquipmentCount = $facility?->equipment()->count() ?? 0;
        $facilityRequests = $facility?->serviceRequests()
            ->with(['equipment', 'assignedEngineer.user'])
            ->latest()
            ->limit(5)
            ->get() ?? collect();
        $serviceReports = $user->role === 'engineer'
            ? ServiceReport::with('request.facility')->where('engineer_id', $user->id)->latest()->limit(5)->get()
            : collect();

        $engineerApproved = null;
        if ($user->engineerProfile) {
            $engineerApproved = Schema::hasColumn('engineer_profiles', 'is_approved')
                ? (bool) $user->engineerProfile->is_approved
                : $user->engineerProfile->credentials->contains(fn ($credential) => $credential->admin_status === 'approved');
        }

        return view('admin.user-account', compact(
            'user',
            'facility',
            'facilityEquipment',
            'facilityEquipmentCount',
            'facilityRequests',
            'serviceReports',
            'engineerApproved'
        ));
    }

    public function settings(): View
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
        ]);

        $user->update($validated);

        return redirect()->route('admin.settings.index')->with('success', 'Administrator profile updated.');
    }

    public function storeAdmin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        User::forceCreate([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'admin',
        ]);

        return redirect()->route('admin.users.index')->with('success', $validated['name'].' was added as an administrator.');
    }

    public function rejectEngineer(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'engineer', 404);

        return $this->deleteAccount($request, $user, $user->name.' was rejected and the account removed.');
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        return $this->deleteAccount($request, $user, $user->name.' account was deleted.');
    }

    private function deleteAccount(Request $request, User $user, string $message): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'The last administrator cannot be deleted.');
        }

        try {
            $user->delete();
        } catch (QueryException) {
            return back()->with('error', 'This account has linked records and could not be deleted.');
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    private function approvedEngineers(): EloquentCollection
    {
        return EngineerProfile::with('user')
            ->when(Schema::hasColumn('engineer_profiles', 'is_approved'), function ($query) {
                $query->where('is_approved', true);
            }, function ($query) {
                $query->whereHas('credentials', function ($credentialsQuery) {
                    $credentialsQuery->where('admin_status', 'approved');
                });
            })
            ->where('is_available', true)
            ->orderBy('id')
            ->get();
    }

    private function section(
        string $section,
        LengthAwarePaginator $records,
        ?EloquentCollection $approvedEngineers = null,
        ?LengthAwarePaginator $biomedicalRecords = null
    ): View {
        return view('admin.section', compact('section', 'records', 'approvedEngineers', 'biomedicalRecords'));
    }
}
