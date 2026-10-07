@extends('layouts.hospital')

@section('title', 'Settings')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">Facility Management</p>
            <h1 class="mt-1 text-3xl font-bold text-slate-900">Settings</h1>
            <p class="mt-2 text-sm text-slate-600">Manage your facility details and account password.</p>
        </div>

        @if (session('status') === 'profile-updated')
            <div role="status" class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                Facility and account names updated.
            </div>
        @elseif (session('status') === 'password-updated')
            <div role="status" class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                Password updated.
            </div>
        @endif

        <section class="card">
            <div class="mb-5">
                <h2 class="text-lg font-semibold text-slate-900">Facility and account names</h2>
                <p class="mt-1 text-sm text-slate-600">Update the facility name and the name shown for your login account.</p>
            </div>

            <form method="POST" action="{{ route('facility.settings.profile.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="facility_name" class="mb-1.5 block text-sm font-semibold text-slate-700">Facility name</label>
                    <input id="facility_name" name="facility_name" type="text" value="{{ old('facility_name', $facility->name) }}" required maxlength="255" autocomplete="organization" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    @error('facility_name')
                        <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Account name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required maxlength="255" autocomplete="name" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    @error('name')
                        <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                    Save names
                </button>
            </form>
        </section>

        <section class="card">
            <div class="mb-5">
                <h2 class="text-lg font-semibold text-slate-900">Change password</h2>
                <p class="mt-1 text-sm text-slate-600">Enter your current password to choose a new one.</p>
            </div>

            <form method="POST" action="{{ route('facility.settings.password.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="mb-1.5 block text-sm font-semibold text-slate-700">Current password</label>
                    <input id="current_password" name="current_password" type="password" required autocomplete="current-password" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">New password</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    @error('password')
                        <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-slate-700">Confirm new password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <button type="submit" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                    Update password
                </button>
            </form>
        </section>
    </div>
@endsection
