<?php

namespace App\Http\Controllers\Facility;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            'facility_type' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'duration_of_operation' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        $user = $request->user();
        abort_unless($user instanceof User, 403);
        $facility = $this->facilityFor($user);

        $oldLogoPath = $facility->logo_path;
        $newLogoPath = $request->hasFile('logo')
            ? $request->file('logo')->store('facility-logos', 'public')
            : null;

        DB::transaction(function () use ($user, $facility, $validated, $newLogoPath): void {
            $user->name = $validated['name'];
            $user->save();

            $facility->name = $validated['facility_name'];
            $facility->facility_type = $validated['facility_type'] ?? null;
            $facility->contact_phone = $validated['phone'] ?? null;
            $facility->email = $validated['email'] ?? null;
            $facility->address = $validated['location'] ?? null;
            $facility->duration_of_operation = $validated['duration_of_operation'] ?? null;
            $facility->contact_person = $validated['contact_person'] ?? null;
            $facility->website = $validated['website'] ?? null;
            $facility->bio = $validated['bio'] ?? null;

            if ($newLogoPath !== null) {
                $facility->logo_path = $newLogoPath;
            }

            $facility->save();
        });

        if ($newLogoPath !== null && $oldLogoPath) {
            Storage::disk('public')->delete($oldLogoPath);
        }

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
