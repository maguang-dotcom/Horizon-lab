@extends('layouts.app')

@section('content')
<style>
    .contact-page { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
    .contact-page h1, .contact-page h2 { font-family: Georgia, 'Times New Roman', serif; }
    .contact-field { border: 1px solid #cbd5e1; background: #fff; transition: border-color 150ms ease, box-shadow 150ms ease; }
    .contact-field:focus { border-color: #0f766e; box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12); outline: none; }
</style>

<section class="contact-page bg-[#f7f5f0] border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-6 py-16 sm:py-24">
        <div class="max-w-3xl mb-14">
            <p class="text-[#9a6b32] text-xs font-semibold uppercase tracking-[0.2em] mb-4">Horizon-LAB correspondence</p>
            <h1 class="text-4xl sm:text-6xl leading-tight text-[#172b3a] mb-6">Let’s begin a conversation.</h1>
            <p class="text-lg leading-relaxed text-slate-600 max-w-2xl">
                Whether you need help with an order, equipment servicing, or a general enquiry, our team is ready to help.
            </p>
        </div>

        <div class="grid lg:grid-cols-[0.8fr_1.2fr] gap-10 lg:gap-16 items-start">
            <aside class="bg-[#172b3a] text-white p-8 sm:p-10">
                <p class="text-[#d9b477] text-xs font-semibold uppercase tracking-[0.18em] mb-8">Find us</p>
                <h2 class="text-3xl leading-tight mb-8">We’re here to help.</h2>
                <address class="not-italic text-slate-300 leading-relaxed mb-10">
                    <p class="text-white font-semibold mb-1">Horizon-LAB</p>
                    <p>Kitgum District, Uganda</p>
                    <p>P.O. Box 1234, Kitgum</p>
                </address>
                <div class="border-t border-slate-600 pt-6 space-y-4 text-sm">
                    <p><span class="block text-[#d9b477] text-xs uppercase tracking-widest mb-1">Email</span><a href="mailto:info@horizon-lab.co.uk" class="text-white hover:text-[#d9b477]">info@horizon-lab.co.uk</a></p>
                    <p><span class="block text-[#d9b477] text-xs uppercase tracking-widest mb-1">Telephone</span><a href="tel:+256700000000" class="text-white hover:text-[#d9b477]">+256 700 000 000</a></p>
                </div>
            </aside>

            <div class="bg-white border border-slate-200 p-8 sm:p-10 shadow-[0_12px_35px_rgba(23,43,58,0.06)]">
                <div class="flex items-end justify-between gap-6 border-b border-slate-200 pb-6 mb-8">
                    <div>
                        <p class="text-[#9a6b32] text-xs font-semibold uppercase tracking-[0.18em] mb-2">Written enquiry</p>
                        <h2 class="text-3xl text-[#172b3a]">Make an enquiry</h2>
                    </div>
                    <p class="hidden sm:block text-xs text-slate-500">* Required field</p>
                </div>

                @if (session('success'))
                    <div class="mb-6 border-l-4 border-teal-700 bg-teal-50 px-4 py-3 text-sm text-teal-900">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid sm:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-[#172b3a] mb-2">Your name <span class="text-[#9a6b32]">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="contact-field w-full px-4 py-3 text-sm text-slate-800">
                            @error('name') <span class="block mt-1 text-xs text-red-700">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="contact_number" class="block text-sm font-semibold text-[#172b3a] mb-2">Contact number <span class="text-[#9a6b32]">*</span></label>
                            <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required class="contact-field w-full px-4 py-3 text-sm text-slate-800">
                            @error('contact_number') <span class="block mt-1 text-xs text-red-700">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-[#172b3a] mb-2">Email address <span class="text-[#9a6b32]">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required class="contact-field w-full px-4 py-3 text-sm text-slate-800">
                        @error('email') <span class="block mt-1 text-xs text-red-700">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-semibold text-[#172b3a] mb-2">How can we help? <span class="text-[#9a6b32]">*</span></label>
                        <select id="subject" name="subject" required class="contact-field w-full px-4 py-3 text-sm text-slate-800">
                            <option value="">Select an enquiry type</option>
                            @foreach (['Servicing', 'AD Hoc', 'Partnerships', 'General Enquiry', 'Careers', 'B2B', 'Product Enquiry'] as $option)
                                <option value="{{ $option }}" @selected(old('subject') === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('subject') <span class="block mt-1 text-xs text-red-700">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="enquiry" class="block text-sm font-semibold text-[#172b3a] mb-2">Your enquiry <span class="text-[#9a6b32]">*</span></label>
                        <textarea id="enquiry" name="enquiry" rows="6" required class="contact-field w-full px-4 py-3 text-sm text-slate-800 resize-y">{{ old('enquiry') }}</textarea>
                        @error('enquiry') <span class="block mt-1 text-xs text-red-700">{{ $message }}</span> @enderror
                    </div>

                    <div class="border-t border-slate-200 pt-6">
                        <label class="flex items-start gap-3 text-sm text-slate-600">
                            <input type="checkbox" id="consent" name="consent" value="1" @checked(old('consent')) required class="mt-1 h-4 w-4 accent-teal-700">
                            <span>I agree to the Backup Medical privacy policy.</span>
                        </label>
                        @error('consent') <span class="block mt-1 text-xs text-red-700">{{ $message }}</span> @enderror
                        <p class="mt-3 text-xs leading-relaxed text-slate-500">By submitting your details, you agree to the conditions set out in the privacy policy.</p>
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center bg-[#172b3a] px-7 py-3 text-sm font-semibold text-white transition hover:bg-[#0f766e]">Send your enquiry</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection