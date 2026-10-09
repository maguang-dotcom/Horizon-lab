<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work With Us - Horizon-LAB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; }
        .hero-bg {
            background:
                linear-gradient(160deg, rgba(124,58,183,0.88) 0%, rgba(60,120,190,0.85) 100%),
                repeating-linear-gradient(45deg, rgba(255,255,255,0.04) 0 2px, transparent 2px 40px);
        }
    </style>
</head>
<body class="bg-white text-slate-700">

    {{-- ================= NAVBAR ================= --}}
    @include('partials.header')
    @if (false)
        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-11 h-11 bg-purple-700 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path d="M4 20V10a1 1 0 0 1 .4-.8l7-5.4a1 1 0 0 1 1.2 0l7 5.4a1 1 0 0 1 .4.8v10" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M8 20v-6h8v6M9 6.5V4M15 6.5V4" stroke-linecap="round" />
                    </svg>
                </div>
                <span class="text-2xl">
                    <span class="font-bold text-purple-700">Horizon</span>
                    <span class="font-light text-slate-600">-LAB</span>
                </span>
            </a>

            <nav class="hidden lg:flex items-center gap-9 text-[15px] font-medium">
                <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-md border border-dashed border-slate-300 text-slate-600 hover:border-slate-400">Home</a>
                <a href="{{ route('about-Us') }}" class="px-4 py-1.5 rounded-md border border-dashed border-slate-300 text-slate-600 hover:border-slate-400">About Us</a>
                <a href="{{route('services-Repair') }}" class="px-4 py-1.5 rounded-md border border-dashed border-slate-300 text-slate-600 hover:border-slate-400">Services & Repair</a>
                <a href="{{ route('work-with-Us') }}" class="px-4 py-1.5 rounded-md border border-dashed border-slate-300 text-slate-600 hover:border-slate-400">Work With Us</a>
                <a href="{{ route('contact.show') }}" class="px-4 py-1.5 rounded-md border border-dashed border-slate-300 text-slate-600 hover:border-slate-400">Contact Us</a>
            </nav>

            <a href="{{ route('engineer-register') }}" class="px-6 py-3 rounded bg-[#1791a7] hover:bg-[#147c8f] text-white font-semibold text-sm transition">
                Join as a Biomedical Professional
            </a>
        </div>
    </header>
    @endif

    {{-- ================= HERO ================= --}}
    <section class="hero-bg py-24">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-white text-4xl sm:text-5xl font-medium">Work With Us</h1>
        </div>
    </section>

    {{-- ================= INTRO ================= --}}
    <section class="py-16">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <p class="text-xl text-slate-700 font-medium mb-6">
                Caring for you &amp; your patients, where &amp; whenever you need us.
            </p>
            <p class="mb-4 leading-relaxed">
                Established in 2026, Horizon-LAB are becoming leaders in the sales, servicing, maintenance and repair of medical equipment.
            </p>
            <p class="mb-4 leading-relaxed">
                With a team of  experience in the service and maintenance of hospital beds, trolleys and patient moving and handling equipment, we offer a personal service to the healthcare industry.
            </p>
            <p class="mb-4 leading-relaxed">
                Built upon excellent client relationships, we are well known and trusted by all our contacts within NHS procurement and estate teams. Our private customers include a wide range of care homes and nursing universities.
            </p>
            <p class="leading-relaxed">
                Our engineers service the whole of Kitgum District and we can cover neighboring districts as well.
            </p>
        </div>
    </section>

    {{-- ================= WORKING WITH YOU ================= --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl font-semibold text-slate-800 text-center mb-12">Working with you&hellip;</h2>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg p-8 shadow-sm">
                    <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <rect x="3" y="8" width="18" height="12" rx="1" />
                            <path d="M8 8V6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800 mb-3">Medical Suppliers</h3>
                    <p class="text-sm leading-relaxed text-slate-500">
                        If you are responsible for a care home, then you know how important it is to have the proper medical equipment on hand. After all, your residents rely on you to keep them safe and healthy.
                    </p>
                </div>

                <div class="bg-white rounded-lg p-8 shadow-sm">
                    <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path d="M4 21V9l8-5 8 5v12M9 21v-6h6v6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800 mb-3">Hospitals</h3>
                    <p class="text-sm leading-relaxed text-slate-500">
                        At Horizon-LAB, we carry a wide range of products, from basic medical supplies to advanced medical equipment. We have everything you need to keep your hospital running smoothly.
                    </p>
                </div>

                <div class="bg-white rounded-lg p-8 shadow-sm">
                    <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path d="M12 21s-7-4.6-9.5-9A5.5 5.5 0 0 1 12 6a5.5 5.5 0 0 1 9.5 6c-2.5 4.4-9.5 9-9.5 9Z" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800 mb-3">Carehomes</h3>
                    <p class="text-sm leading-relaxed text-slate-500">
                        Horizon-LAB have everything you need to ensure that your residents receive the best possible care, including electric profiling beds and patient moving and handling equipment.
                    </p>
                </div>
            </div>
        </div>
    </section>

    

    {{-- ================= B2B ================= --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div class="bg-white rounded-lg shadow-sm p-8">
                <span class="text-[#1791a7] font-semibold text-sm tracking-wide">B2B</span>
                <h3 class="text-xl font-semibold text-slate-800 mt-2 mb-4">Business to Business</h3>
                <p class="text-slate-500 leading-relaxed mb-6">
                    Helping your business is at the core of Horizon-LAB. We can offer bespoke solutions tailored to your business's needs.
                </p>
                <p class="text-slate-500 leading-relaxed">
                    We only work with the best in the industry.
                </p>
            </div>
             <div class="bg-white rounded-lg shadow-sm p-8">
                <span class="text-[#1791a7] font-semibold text-sm tracking-wide">Long-Lasting Relationships</span>
                <h3 class="text-xl font-semibold text-slate-800 mt-2 mb-4">Partner with Horizon-LAB</h3>
                <p class="text-slate-500 leading-relaxed mb-6">
                    Struggling to find a maintenance contractor for your clients? Partner with us, and we can serve your clients through our white label service.
                </p>
                <a href="{{ route('register') }}" class="inline-block px-6 py-3 rounded bg-[#1791a7] hover:bg-[#147c8f] text-white font-semibold text-sm transition">
                    Become a Partner
                </a>
            </div>
        </div>
    </section>

    {{-- ================= CTA STRIP ================= --}}
    <section class="bg-purple-700 py-14">
        <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div>
                <h2 class="text-white text-2xl font-semibold">Medical Equipment, Servicing &amp; Repairs</h2>
                <p class="text-purple-200 mt-1">Start your service plan today.</p>
            </div>
            <a href="{{ route('engineer-register') }}" class="px-7 py-3 rounded bg-[#1791a7] hover:bg-[#147c8f] text-white font-semibold text-sm whitespace-nowrap transition">
                Join as a Biomedical Professional
            </a>
        </div>
    </section>

    @if (false)
    {{-- ================= FOOTER ================= --}}
    <footer class="bg-slate-900 text-slate-400 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

            <div>
                <span class="text-xl">
                    <span class="font-bold text-white">Horizon</span>
                    <span class="font-light text-slate-300">-LAB</span>
                </span>
                <p class="mt-4 text-sm leading-relaxed">
                    Kitgum District, Uganda<br>
                </p>
                <p class="mt-3 text-sm">
                    e. <a href="mailto:info@horizon-lab.com" class="hover:text-white">info@horizon-lab.com</a>
                </p>
                <p class="text-sm">
                    t. <a href="tel:+256700000000" class="hover:text-white">+256 700 000 000</a>
                </p>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4 text-sm">Our Products</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white">Electric Rental Beds</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-white">Medical Beds</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-white">Patient Handling</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-white">Physiotherapy</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-white">Pressure Care</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4 text-sm">Servicing &amp; Repairs</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('service-requests.create') }}" class="hover:text-white">Medical Equipment</a></li>
                    <li><a href="{{ route('service-requests.create') }}" class="hover:text-white">Partner Solutions</a></li>
                    <li><a href="{{ route('service-requests.create') }}" class="hover:text-white">Testing</a></li>
                    <li class="pt-1 text-slate-500">Eco Friendly Repairs</li>
                    <li><a href="{{ route('service-requests.create') }}" class="hover:text-white">Consumable Replacements</a></li>
                    <li><a href="{{ route('service-requests.create') }}" class="hover:text-white">Upholstery</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4 text-sm">Partner With Us</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white">Become a Partner</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-white">B2B</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-white">Careers</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-white">Carehomes</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-white">Hospitals</a></li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 border-t border-slate-800 pt-6">
            <p class="text-sm text-center mb-3">
                For all your mobility needs, don't hesitate to contact us!
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Horizon-LAB Ltd</p>
                <div class="flex flex-wrap items-center gap-x-2">
                    <a href="{{ route('home') }}" class="hover:text-white">Privacy Policy</a>
                    <span>|</span>
                    <a href="{{ route('home') }}" class="hover:text-white">Terms &amp; Conditions</a>
                    <span>|</span>
                    <a href="{{ route('home') }}" class="hover:text-white">Policies</a>
                    <span>|</span>
                    <a href="{{ route('home') }}" class="hover:text-white">FAQs</a>
                </div>
            </div>
        </div>
    </footer>
    @endif

    @include('partials.footer')

</body>
</html>