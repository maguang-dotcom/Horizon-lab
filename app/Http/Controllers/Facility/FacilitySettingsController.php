<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class FacilitySettingsController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        return view('HealthyFacility.settings', [
            'user' => $user,
            'facility' => $this->facilityFor($user),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'facility_name' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();
        abort_unless($user instanceof User, 403);
        $facility = $this->facilityFor($user);

        DB::transaction(function () use ($user, $facility, $validated): void {
            $user->name = $validated['name'];
            $user->save();

            $facility->name = $validated['facility_name'];
            $facility->save();
        });

        return back()->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $user->password = $validated['password'];
        $user->save();

        return back()->with('status', 'password-updated');
    }

    private function facilityFor(User $user): Facility
    {
        $facility = $user->currentFacility;
        abort_unless($facility instanceof Facility, 404, 'Facility profile not found.');

        return $facility;
    }
}
