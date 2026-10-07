<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showFacilityLoginForm(): View
    {
        return view('Auth.HealthfacilityLogin');
    }

    public function showEngineerLoginForm(): View
    {
        return view('Auth.engineer-login');
    }

    public function loginFacility(Request $request): RedirectResponse
    {
        return $this->attemptLogin($request, 'facility', 'facility.dashboard');
    }

    public function loginEngineer(Request $request): RedirectResponse
    {
        return $this->attemptLogin($request, 'engineer', 'engineer.dashboard');
    }

    public function showAdminLoginForm(): View
    {
        return view('Auth.admin-login');
    }

    public function loginAdmin(Request $request): RedirectResponse
    {
        return $this->attemptLogin($request, 'admin', 'admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function attemptLogin(Request $request, string $role, string $dashboardRoute): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials['role'] = $role;

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if ($role === 'engineer') {
                $profile = Auth::user()->engineerProfile;
                if ($profile && ! $profile->is_approved) {
                    Auth::logout();

                    return back()->withErrors([
                        'email' => 'This engineer account is awaiting admin approval.',
                    ])->onlyInput('email');
                }
            }

            $request->session()->regenerate();

            return redirect()->route($dashboardRoute);
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }
}
