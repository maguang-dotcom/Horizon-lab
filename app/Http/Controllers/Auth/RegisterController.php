<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AccountCreatedMail;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('Auth.HealthFacilityCreateLogin');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'facility',
        ]);

        Facility::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'address' => $validated['address'],
        ]);

        $this->sendWelcomeEmail($user);

        Auth::login($user);

        return redirect()->route('facility.dashboard');
    }

    private function sendWelcomeEmail(User $user): void
    {
        try {
            Mail::to($user->email)->send(new AccountCreatedMail($user));
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function registerEngineer(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'professional_title' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'license_number' => ['nullable', 'string', 'max:255', 'unique:engineer_profiles,license_number'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'engineer',
        ]);

        $user->engineerProfile()->create([
            'professional_title' => $validated['professional_title'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'license_number' => $validated['license_number'] ?? null,
            'is_approved' => false,
            'approved_at' => null,
        ]);

        $this->sendWelcomeEmail($user);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('engineer.dashboard');
    }
}
