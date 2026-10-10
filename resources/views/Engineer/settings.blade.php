@extends('layouts.engineer')

@section('title', 'Settings')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">

        {{-- Page Header --}}
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">
                Engineer Account
            </p>

            <h1 class="mt-1 text-3xl font-bold text-slate-900">
                Engineer Settings
            </h1>

            <p class="mt-2 text-sm text-slate-600">
                Manage your professional profile, contact details, availability and account security.
            </p>
        </div>


        {{-- Success Messages --}}
        @if (session('status') === 'profile-updated')
            <div role="status"
                 class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100">✓</span>
                <span>Profile updated successfully.</span>
            </div>
        @elseif (session('status') === 'password-updated')
            <div role="status"
                 class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100">✓</span>
                <span>Password updated successfully.</span>
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


        {{-- PROFESSIONAL PROFILE --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                        🛠️
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Professional Profile</h2>
                        <p class="text-sm text-slate-600">
                            This information is used to match you with service requests.
                        </p>
                    </div>
                </div>
            </div>


            <form method="POST"
                  id="engineer-profile-form"
                  action="{{ route('engineer.settings.profile.update') }}"
                  enctype="multipart/form-data"
                  class="p-6">

                @csrf
                @method('PUT')


                {{-- PHOTO --}}
                <div class="mb-8">
                    <label class="mb-3 block text-sm font-semibold text-slate-700">Profile Photo</label>

                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center">

                        <div class="relative flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-full border-2 border-dashed border-slate-300 bg-slate-50">

                            @if (!empty($engineer->photo_path))
                                <img id="photoPreview"
                                     src="{{ asset('storage/' . $engineer->photo_path) }}"
                                     alt="{{ $user->name }}"
                                     class="h-full w-full object-cover">
                            @else
                                <div id="photoPlaceholder" class="text-center">
                                    <div class="text-3xl">👤</div>
                                    <p class="mt-1 text-xs text-slate-400">No photo</p>
                                </div>

                                <img id="photoPreview" src="" alt="Photo preview"
                                     class="hidden h-full w-full object-cover">
                            @endif
                        </div>

                        <div>
                            <label for="photo"
                                   class="inline-flex cursor-pointer items-center gap-2 rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800">
                                <span>📷</span> Upload Photo
                            </label>

                            <input id="photo" name="photo" type="file"
                                   accept="image/png,image/jpeg,image/webp"
                                   class="hidden" onchange="previewPhoto(event)">

                            <p class="mt-2 text-xs text-slate-500">PNG, JPG or WEBP. Maximum size: 2MB.</p>

                            @error('photo')
                                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>


                {{-- AVAILABILITY --}}
                <div class="mb-8 flex items-start justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Available for new requests</p>
                        <p class="mt-1 text-xs text-slate-500">
                            Turn this off when you are on leave or fully booked. You will not be matched to new requests while it is off.
                        </p>
                    </div>

                    <label class="relative inline-flex shrink-0 cursor-pointer items-center">
                        <input type="hidden" name="is_available" value="0">
                        <input type="checkbox" name="is_available" value="1" class="peer sr-only"
                               @checked(old('is_available', $engineer->is_available ?? true))>
                        <span class="h-6 w-11 rounded-full bg-slate-300 transition peer-checked:bg-blue-700 peer-focus-visible:ring-2 peer-focus-visible:ring-blue-300"></span>
                        <span class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition peer-checked:translate-x-5"></span>
                    </label>
                </div>


                {{-- PROFILE FIELDS --}}
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    @php
                        $inputClass = 'w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100';
                        $labelClass = 'mb-1.5 block text-sm font-semibold text-slate-700';
                    @endphp

                    {{-- Specialization --}}
                    <div>
                        <label for="specialization" class="{{ $labelClass }}">Specialization</label>
                        <select id="specialization" name="specialization" class="{{ $inputClass }} bg-white">
                            <option value="">Select specialization</option>
                            @foreach (['Imaging Equipment', 'Laboratory Equipment', 'Patient Monitoring', 'Surgical & Theatre Equipment', 'Dental Equipment', 'Sterilization Equipment', 'General Biomedical'] as $option)
                                <option value="{{ $option }}"
                                    @selected(old('specialization', $engineer->specialization ?? '') === $option)>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                        @error('specialization')
                            <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Years of experience --}}
                    <div>
                        <label for="years_experience" class="{{ $labelClass }}">Years of Experience</label>
                        <input id="years_experience" name="years_experience" type="number" min="0" max="60"
                               value="{{ old('years_experience', $engineer->years_experience ?? '') }}"
                               placeholder="e.g. 5" class="{{ $inputClass }}">
                        @error('years_experience')
                            <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="{{ $labelClass }}">Phone Number</label>
                        <input id="phone" name="phone" type="text"
                               value="{{ old('phone', $engineer->phone ?? '') }}"
                               placeholder="+256 7XX XXX XXX" class="{{ $inputClass }}">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Base location --}}
                    <div>
                        <label for="location" class="{{ $labelClass }}">Base Location</label>
                        <input id="location" name="location" type="text"
                               value="{{ old('location', $engineer->location ?? '') }}"
                               placeholder="e.g. Gulu, Northern Uganda" class="{{ $inputClass }}">
                        @error('location')
                            <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Service radius --}}
                    <div>
                        <label for="service_radius_km" class="{{ $labelClass }}">Service Radius (km)</label>
                        <input id="service_radius_km" name="service_radius_km" type="number" min="1" max="1000"
                               value="{{ old('service_radius_km', $engineer->service_radius_km ?? '') }}"
                               placeholder="How far you can travel" class="{{ $inputClass }}">
                        @error('service_radius_km')
                            <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Registration number (read-only: part of approved credentials) --}}
                    <div>
                        <label for="license_number" class="{{ $labelClass }}">Registration / License No.</label>
                        <input id="license_number" type="text" readonly
                               value="{{ $engineer->license_number ?? '—' }}"
                               class="{{ $inputClass }} cursor-not-allowed bg-slate-50 text-slate-500">
                        <p class="mt-1.5 text-xs text-slate-500">
                            Credentials are verified by an administrator. Contact support to change them.
                        </p>
                    </div>

                    {{-- Bio --}}
                    <div class="md:col-span-2">
                        <label for="bio" class="{{ $labelClass }}">About You</label>
                        <textarea id="bio" name="bio" rows="4" maxlength="1000"
                                  placeholder="Briefly describe your experience, the equipment you service and your area of operation..."
                                  class="{{ $inputClass }} resize-none">{{ old('bio', $engineer->bio ?? '') }}</textarea>
                        <p class="mt-1.5 text-xs text-slate-500">Maximum 1000 characters.</p>
                        @error('bio')
                            <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                {{-- ACCOUNT INFORMATION --}}
                <div class="mt-8 border-t border-slate-200 pt-6">
                    <h3 class="text-base font-semibold text-slate-900">Account Information</h3>
                    <p class="mt-1 text-sm text-slate-600">These details are used to sign in and receive notifications.</p>

                    <div class="mt-4 grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="name" class="{{ $labelClass }}">Full Name</label>
                            <input id="name" name="name" type="text" required maxlength="255"
                                   autocomplete="name"
                                   value="{{ old('name', $user->name) }}" class="{{ $inputClass }}">
                            @error('name')
                                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="{{ $labelClass }}">Email</label>
                            <input id="email" name="email" type="email" required maxlength="255"
                                   autocomplete="email"
                                   value="{{ old('email', $user->email) }}" class="{{ $inputClass }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>


                {{-- Save --}}
                <div class="mt-6 flex justify-end border-t border-slate-200 pt-6">
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-200">
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
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-200 text-slate-700">🔐</div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Change Password</h2>
                        <p class="text-sm text-slate-600">Keep your engineer account secure.</p>
                    </div>
                </div>
            </div>

            <form method="POST"
                  action="{{ route('engineer.settings.password.update') }}"
                  class="space-y-5 p-6">

                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="{{ $labelClass }}">Current Password</label>
                    <input id="current_password" name="current_password" type="password" required
                           autocomplete="current-password" class="{{ $inputClass }}">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="{{ $labelClass }}">New Password</label>
                    <input id="password" name="password" type="password" required
                           autocomplete="new-password" class="{{ $inputClass }}">
                    @error('password')
                        <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="{{ $labelClass }}">Confirm New Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           autocomplete="new-password" class="{{ $inputClass }}">
                </div>

                <div class="flex justify-end border-t border-slate-200 pt-5">
                    <button type="submit"
                            class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                        Update Password
                    </button>
                </div>
            </form>
        </section>

    </div>


    {{-- Photo Preview --}}
    <script>
        function previewPhoto(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('photoPreview');
            const placeholder = document.getElementById('photoPlaceholder');

            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    </script>
@endsection