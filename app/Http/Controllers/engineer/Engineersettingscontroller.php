<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use App\Models\EngineerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class EngineerSettingsController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        $engineer = EngineerProfile::firstOrCreate(['user_id' => $user->id]);

        return view('engineer.settings', compact('user', 'engineer'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        $engineer = EngineerProfile::firstOrCreate(['user_id' => $user->id]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'specialization' => ['nullable', 'string', 'max:100'],
            'years_experience' => ['nullable', 'integer', 'min:0', 'max:60'],
            'phone' => ['nullable', 'string', 'max:30'],
            'location' => ['nullable', 'string', 'max:255'],
            'service_radius_km' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'is_available' => ['required', 'boolean'],
            'photo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        // Account (users table)
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        // Professional profile (engineer_profiles table).
        // license_number and other credentials are deliberately NOT editable here.
        $profile = collect($data)->only([
            'specialization', 'years_experience', 'phone', 'location',
            'service_radius_km', 'bio', 'is_available',
        ])->all();

        if ($request->hasFile('photo')) {
            if ($engineer->photo_path) {
                Storage::disk('public')->delete($engineer->photo_path);
            }
            $profile['photo_path'] = $request->file('photo')->store('engineer-photos', 'public');
        }

        $engineer->update($profile);

        return redirect()
            ->route('engineer.settings')
            ->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('engineer.settings')
            ->with('status', 'password-updated');
    }
}
