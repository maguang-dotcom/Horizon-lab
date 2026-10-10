@extends('layouts.hospital')

@section('title', 'Settings')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">

        {{-- Page Header --}}
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">
                Facility Management
            </p>

            <h1 class="mt-1 text-3xl font-bold text-slate-900">
                Facility Settings
            </h1>

            <p class="mt-2 text-sm text-slate-600">
                Manage your facility profile, contact information, location and account security.
            </p>

            <a href="#facility-profile-form"
               class="mt-4 inline-flex items-center rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Update Profile
            </a>
        </div>


        {{-- Success Messages --}}
        @if (session('status') === 'profile-updated')
            <div role="status"
                 class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
                    ✓
                </span>

                <span>
                    Facility profile updated successfully.
                </span>
            </div>

        @elseif (session('status') === 'password-updated')
            <div role="status"
                 class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
                    ✓
                </span>

                <span>
                    Password updated successfully.
                </span>
            </div>
        @endif


        @if (request()->boolean('upload_too_large'))
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                The file you selected is too large. Please choose an image smaller than 2 MB (PNG, JPG or WEBP).
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-semibold">Please correct the following:</p>

                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- FACILITY PROFILE --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- Section Header --}}
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                        🏥
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Facility Profile
                        </h2>

                        <p class="text-sm text-slate-600">
                            Update the information displayed about your healthcare facility.
                        </p>
                    </div>
                </div>
            </div>


            {{-- Profile Form --}}
            <form method="POST"
                  id="facility-profile-form"
                  action="{{ route('facility.settings.profile.update') }}"
                  enctype="multipart/form-data"
                  class="p-6">

                @csrf
                @method('PUT')


                {{-- LOGO --}}
                <div class="mb-8">

                    <label class="mb-3 block text-sm font-semibold text-slate-700">
                        Facility Logo
                    </label>

                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center">

                        {{-- Current Logo --}}
                        <div class="relative flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50">

                            @if (!empty($facility->logo_path))
                                <img
                                    id="logoPreview"
                                    src="{{ asset('storage/' . $facility->logo_path) }}"
                                    alt="{{ $facility->name }} logo"
                                    class="h-full w-full object-cover"
                                >
                            @else
                                <div id="logoPlaceholder" class="text-center">
                                    <div class="text-3xl">🏥</div>
                                    <p class="mt-1 text-xs text-slate-400">
                                        No logo
                                    </p>
                                </div>

                                <img
                                    id="logoPreview"
                                    src=""
                                    alt="Logo preview"
                                    class="hidden h-full w-full object-cover"
                                >
                            @endif

                        </div>


                        {{-- Upload --}}
                        <div>
                            <label
                                for="logo"
                                class="inline-flex cursor-pointer items-center gap-2 rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800"
                            >
                                <span>📷</span>
                                Upload Logo
                            </label>

                            <input
                                id="logo"
                                name="logo"
                                type="file"
                                accept="image/png,image/jpeg,image/jpg,image/webp"
                                class="hidden"
                                onchange="previewLogo(event)"
                            >

                            <p class="mt-2 text-xs text-slate-500">
                                PNG, JPG or WEBP. Maximum size: 2MB.
                            </p>

                            @error('logo')
                                <p class="mt-1 text-sm text-red-700">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>
                </div>


                {{-- FACILITY INFORMATION --}}
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Facility Name --}}
                    <div class="md:col-span-2">
                        <label for="facility_name"
                               class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Facility Name
                        </label>

                        <input
                            id="facility_name"
                            name="facility_name"
                            type="text"
                            value="{{ old('facility_name', $facility->name) }}"
                            required
                            maxlength="255"
                            placeholder="e.g. Juba Teaching Hospital"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >

                        @error('facility_name')
                            <p class="mt-1 text-sm text-red-700">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Facility Type --}}
                    <div>
                        <label for="facility_type"
                               class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Facility Type
                        </label>

                        <select
                            id="facility_type"
                            name="facility_type"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="">Select facility type</option>

                            <option value="Hospital"
                                @selected(old('facility_type', $facility->facility_type ?? '') === 'Hospital')>
                                Hospital
                            </option>

                            <option value="Health Centre"
                                @selected(old('facility_type', $facility->facility_type ?? '') === 'Health Centre')>
                                Health Centre
                            </option>

                            <option value="Clinic"
                                @selected(old('facility_type', $facility->facility_type ?? '') === 'Clinic')>
                                Clinic
                            </option>

                            <option value="Laboratory"
                                @selected(old('facility_type', $facility->facility_type ?? '') === 'Laboratory')>
                                Laboratory
                            </option>

                            <option value="Medical Centre"
                                @selected(old('facility_type', $facility->facility_type ?? '') === 'Medical Centre')>
                                Medical Centre
                            </option>
                        </select>

                        @error('facility_type')
                            <p class="mt-1 text-sm text-red-700">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Phone --}}
                    <div>
                        <label for="phone"
                               class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Phone Number
                        </label>

                        <input
                            id="phone"
                            name="phone"
                            type="text"
                            value="{{ old('phone', $facility->contact_phone ?? '') }}"
                            placeholder="+256 7XX XXX XXX"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >

                        @error('phone')
                            <p class="mt-1 text-sm text-red-700">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Email --}}
                    <div>
                        <label for="email"
                               class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Facility Email
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $facility->email ?? '') }}"
                            placeholder="facility@example.com"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >

                        @error('email')
                            <p class="mt-1 text-sm text-red-700">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Location --}}
                    <div>
                        <label for="location"
                               class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Location
                        </label>

                        <input
                            id="location"
                            name="location"
                            type="text"
                            value="{{ old('location', $facility->address ?? '') }}"
                            placeholder="e.g. Kitgum, Northern Uganda"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >

                        @error('location')
                            <p class="mt-1 text-sm text-red-700">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Duration of Operation --}}
                    <div>
                        <label for="duration_of_operation"
                               class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Duration of Operation
                        </label>

                        <input
                            id="duration_of_operation"
                            name="duration_of_operation"
                            type="text"
                            value="{{ old('duration_of_operation', $facility->duration_of_operation ?? '') }}"
                            placeholder="e.g. Mon-Fri, 8am-5pm"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >

                        @error('duration_of_operation')
                            <p class="mt-1 text-sm text-red-700">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Contact Person --}}
                    <div>
                        <label for="contact_person"
                               class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Contact Person
                        </label>

                        <input
                            id="contact_person"
                            name="contact_person"
                            type="text"
                            value="{{ old('contact_person', $facility->contact_person ?? '') }}"
                            placeholder="Facility manager / administrator"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >

                        @error('contact_person')
                            <p class="mt-1 text-sm text-red-700">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Website --}}
                    <div>
                        <label for="website"
                               class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Website 
                        </label>

                        <input
                            id="website"
                            name="website"
                            type="url"
                            value="{{ old('website', $facility->website ?? '') }}"
                            placeholder="https://example.com"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >

                        @error('website')
                            <p class="mt-1 text-sm text-red-700">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Bio / Description --}}
                    <div class="md:col-span-2">
                        <label for="bio"
                               class="mb-1.5 block text-sm font-semibold text-slate-700">
                            About the Facility
                        </label>

                        <textarea
                            id="bio"
                            name="bio"
                            rows="4"
                            maxlength="1000"
                            placeholder="Briefly describe your facility, the services you provide and your area of operation..."
                            class="w-full resize-none rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >{{ old('bio', $facility->bio ?? '') }}</textarea>

                        <p class="mt-1.5 text-xs text-slate-500">
                             Maximum 1000 characters.
                        </p>

                        @error('bio')
                            <p class="mt-1 text-sm text-red-700">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>


                {{-- Account Name --}}
                <div class="mt-8 border-t border-slate-200 pt-6">

                    <h3 class="text-base font-semibold text-slate-900">
                        Account Information
                    </h3>

                    <p class="mt-1 text-sm text-slate-600">
                        This is the name associated with your facility login.
                    </p>

                    <div class="mt-4">
                        <label for="name"
                               class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Account Name
                        </label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $user->name) }}"
                            required
                            maxlength="255"
                            autocomplete="name"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >

                        @error('name')
                            <p class="mt-1 text-sm text-red-700">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>


                {{-- Save --}}
                <div class="mt-6 flex justify-end border-t border-slate-200 pt-6">

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >
                        <span>Save Changes</span>
                        <span>✓</span>
                    </button>

                </div>

            </form>
        </section>


        {{-- CHANGE PASSWORD --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-200 text-slate-700">
                        🔐
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Change Password
                        </h2>

                        <p class="text-sm text-slate-600">
                            Keep your facility account secure.
                        </p>
                    </div>

                </div>

            </div>


            <form method="POST"
                  action="{{ route('facility.settings.password.update') }}"
                  class="space-y-5 p-6">

                @csrf
                @method('PUT')

                <div>
                    <label for="current_password"
                           class="mb-1.5 block text-sm font-semibold text-slate-700">
                        Current Password
                    </label>

                    <input
                        id="current_password"
                        name="current_password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >

                    @error('current_password')
                        <p class="mt-1 text-sm text-red-700">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                <div>
                    <label for="password"
                           class="mb-1.5 block text-sm font-semibold text-slate-700">
                        New Password
                    </label>

                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >

                    @error('password')
                        <p class="mt-1 text-sm text-red-700">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                <div>
                    <label for="password_confirmation"
                           class="mb-1.5 block text-sm font-semibold text-slate-700">
                        Confirm New Password
                    </label>

                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >
                </div>


                <div class="flex justify-end border-t border-slate-200 pt-5">

                    <button
                        type="submit"
                        class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800"
                    >
                        Update Password
                    </button>

                </div>

            </form>

        </section>

    </div>


    {{-- Logo Preview --}}
    <script>
        function previewLogo(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('logoPreview');
            const placeholder = document.getElementById('logoPlaceholder');

            if (!file) {
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');

                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            };

            reader.readAsDataURL(file);
        }
    </script>
@endsection
