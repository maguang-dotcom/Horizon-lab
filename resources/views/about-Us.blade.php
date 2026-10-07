<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us — Horizon-LAB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-700">

    @include('partials.header')

    {{-- ================= HERO ================= --}}
    <section class="bg-gradient-to-br from-[#123d67] via-[#0f766e] to-[#123d67] py-24">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-white text-4xl sm:text-5xl font-medium max-w-2xl">
                Meet your trusted &amp; reliable medical repair team.
            </h1>
        </div>
    </section>

    {{-- ================= INTRO ================= --}}
    <section class="py-16">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <p class="mb-4 leading-relaxed">
                Established in 2026, Horizon-LAB are leaders in the sales, servicing and repairs of medical equipment.
            </p>
            <p class="mb-4 leading-relaxed">
                With   experience team in sales, servicing and repairs of hospital beds, trolleys and patient moving equipment, we offer a personal service to the healthcare industry.
            </p>
            <p class="mb-4 leading-relaxed">
                Built upon excellent client relationships, we are well known and trusted by all our contacts within NHS procurement and estate teams. Our private customers include a wide range of care homes and nursing universities.
            </p>
            <p class="leading-relaxed">
                Our engineers service the whole of Kitgum and, if required, we can cover the whole of Uganda.
            </p>
        </div>
    </section>

    {{-- ================= OUR TEAM ================= --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl font-semibold text-slate-800 text-center mb-12">Our Team</h2>

            @php
                $team = [
                    ['name' => 'Awoui Sarah Athem', 'role' => 'Operations Director', 'image' => 'images/Awoui-Sarah.jpeg'],
                    ['name' => 'Machar Bol Leet', 'role' => 'Repairs & Maintenance Director', 'image' => 'images/Machar-Bol.jpeg'],
                    ['name' => 'Maguang Dhieu Maguang', 'role' => 'System Administrator', 'image' => 'images/Maguang-Dhieu.jpeg'],
                ];
            @endphp

            <div class="grid sm:grid-cols-3 gap-8 mb-10">
                @foreach ($team as $member)
                    <div class="bg-white rounded-lg p-8 shadow-sm text-center">
                        <div class="w-20 h-20 rounded-full bg-purple-700 text-white flex items-center justify-center text-xl font-semibold mx-auto mb-5 overflow-hidden">
                            <img src="{{ asset($member['image']) }}" alt="{{ $member['name'] }}" class="w-full h-full object-cover rounded-full">
                        </div>
                        <h3 class="font-semibold text-slate-800">{{ $member['name'] }}</h3>
                        <p class="text-sm text-[#1791a7] font-medium mt-1">{{ $member['role'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="max-w-3xl mx-auto text-center text-slate-500 leading-relaxed space-y-4">
                <p>
                    Both Awoui Sarah Athem &amp; Machar Bol Leet started the business in 2026 after noticing a space in the market for a truly personal, professional service to the wider healthcare sector.
                </p>
                <p>
                    Maguang Dhieu Maguang develops the system, expanding its customer base and enhancing our reach through established digital platforms and his impeccable customer service.
                </p>
            </div>
        </div>
    </section>

    {{-- ================= OUR OBJECTIVES ================= --}}
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-2xl mx-auto text-center mb-12">
                <h2 class="text-2xl font-semibold text-slate-800 mb-4">Our Objectives</h2>
                <p class="text-slate-500 leading-relaxed">
                    We strive to offer a complete solution for every healthcare sector, finding innovative ways to seamlessly work together, to make sure your medical equipment meets industry standards and requirements.
                </p>
            </div>

            @php
                $objectives = [
                    ['title' => 'Added Value', 'text' => 'We listen to our customers and add value to your offering by sharing our expertise.'],
                    ['title' => 'Highest Standards', 'text' => 'Our service team maintain equipment to the highest standards and offer advice and support to ensure equipment is safe, working and compliant.'],
                    ['title' => 'Healthcare', 'text' => 'We care passionately about healthcare and the people in the profession.'],
                    ['title' => 'Excellent Service', 'text' => 'Our team are all committed to delivering the very best level of customer service.'],
                    ['title' => 'Relationships', 'text' => 'We believe in forming long-term relationships with our clients, customers and users.'],
                    ['title' => 'Flexible Solutions', 'text' => 'We offer long term service contracts for your equipment, as well as ad hoc repairs and servicing.'],
                ];
            @endphp

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($objectives as $item)
                    <div class="border-l-2 border-[#1791a7] pl-5">
                        <h3 class="font-semibold text-slate-800 mb-2">{{ $item['title'] }}</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= MEDICAL EQUIPMENT ================= --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <span class="text-[#1791a7] font-semibold text-sm tracking-wide">We only work with the best in the industry.</span>
            <h2 class="text-2xl font-semibold text-slate-800 mt-2 mb-4">Medical Equipment</h2>
            <p class="text-slate-500 leading-relaxed">
                We can provide bespoke furnishing solutions to suit every project. We work first-hand with suppliers, procurement teams and public bodies, which ensures you receive the best value-to-quality ratio possible.
            </p>
        </div>
    </section>

    {{-- ================= CTA STRIP ================= --}}
    <section class="bg-purple-700 py-14">
        <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div>
                <h2 class="text-white text-2xl font-semibold">Medical Equipment, Servicing &amp; Repairs</h2>
                <p class="text-purple-200 mt-1">Start your service plan today.</p>
            </div>
            <a href="{{ route('service-requests.create') }}" class="px-7 py-3 rounded bg-[#1791a7] hover:bg-[#147c8f] text-white font-semibold text-sm whitespace-nowrap transition">
                Book an Engineer
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
                    Kitgum district, Uganda
                </p>
                <p class="mt-3 text-sm">
                    e. <a href="mailto:info@backupmedical.co.uk" class="hover:text-white">info@backupmedical.co.uk</a>
                </p>
                <p class="text-sm">
                    t. <a href="tel:+441563550836" class="hover:text-white">01563 550 836</a>
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
                For all your mobility needs, don't hesistate to contact us!
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Horizon-LAB Ltd &nbsp;|&nbsp; Site by <a href="https://adandco.com/" class="hover:text-white" target="_blank" rel="noopener">AD&amp;CO</a></p>
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