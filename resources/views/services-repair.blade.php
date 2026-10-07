<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service & Repair - Horizon-LAB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
        .hero-bg {
            background:
                linear-gradient(120deg, rgba(15, 43, 58, 0.98), rgba(18, 93, 105, 0.94)),
                repeating-linear-gradient(135deg, rgba(255,255,255,0.05) 0 1px, transparent 1px 32px);
        }
    </style>
</head>
<body class="bg-white text-slate-700">

    @include('partials.header')

    {{-- ================= HERO ================= --}}
    <section class="hero-bg py-20 sm:py-24">
        <div class="max-w-7xl mx-auto px-6">
            <p class="text-teal-300 text-sm font-semibold uppercase tracking-[0.18em] mb-4">Reliable equipment care</p>
            <h1 class="text-white text-4xl sm:text-5xl font-semibold tracking-tight mb-5">Service &amp; Repairs</h1>
            <p class="text-slate-200 max-w-2xl text-lg leading-relaxed">
                Keep essential medical equipment safe, compliant, and ready for the people who depend on it.
            </p>
        </div>
    </section>

    {{-- ================= INTRO ================= --}}
    <section class="py-16">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-800 mb-6">There when you need us, day or night.</h2>
            <p class="mb-4 leading-relaxed">
                At Horizon-LAB, we understand just how important it is to ensure that everything is in working order in hospitals or other healthcare settings. Just like any other appliance, medical equipment is not immune to faults.
            </p>
            <p class="mb-4 leading-relaxed">
                Our highly trained medical equipment engineers have the tools, knowledge, and experience to get your equipment back to full functionality.
            </p>
            <p class="mb-4 leading-relaxed">
                When fixing your equipment, our engineers keep a full record of all repairs being carried out. This can help identify the pattern of any problems, and also help to prevent further issues from occurring.
            </p>
            <p class="mb-4 leading-relaxed">
                We make sure to test all equipment thoroughly after completing repairs. This will ensure that it is safe to use and is functioning properly.
            </p>
            <p class="leading-relaxed">
                By following these steps, we can fix medical equipment quickly and efficiently, ensuring that it remains safe and functional for all patients.
            </p>
        </div>
    </section>

    {{-- ================= SERVICING ================= --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl sm:text-3xl font-semibold text-[#0f2b3a] tracking-tight text-center mb-12">Servicing that keeps you moving</h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white border border-slate-200 rounded-lg p-8 shadow-sm">
                    <h3 class="font-semibold text-slate-800 mb-3">Annual Maintenance</h3>
                    <p class="text-sm leading-relaxed text-slate-500 mb-4">
                        We offer a planned maintenance service for medical equipment, including one visit to your healthcare centre each year and a team on hand 24/7 for urgent repairs. There is no call-out charge.
                    </p>
                    <p class="text-sm leading-relaxed text-slate-500">
                        Additionally, our servicing reports &amp; reminders help you keep track of all previous repairs carried out on your equipment.
                    </p>
                </div>

                <div class="bg-white border border-slate-200 rounded-lg p-8 shadow-sm">
                    <h3 class="font-semibold text-slate-800 mb-3">Ad hoc maintenance</h3>
                    <p class="text-sm leading-relaxed text-slate-500">
                        Our highly trained medical equipment engineers are here when you need us and we carry spare parts to try and ensure the fault is resolved during the initial call out.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= WHAT'S INCLUDED ================= --}}
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-800 text-center mb-12">What's included</h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="border border-slate-200 rounded-lg p-8 shadow-sm">
                    <h3 class="font-semibold text-slate-800 mb-2">Annual Maintenance</h3>
                    <p class="text-sm text-slate-500 mb-5">Have your maintenance team on call 24/7, for a fixed yearly cost.</p>
                    <ul class="space-y-2.5 text-sm">
                        @foreach ([
                            '1 visit per year',
                            'Mechanical Framework Inspections',
                            'Infection Control Inspection',
                            'Electrical / Functional Inspection',
                            'Service Reports',
                            'Service Reminders',
                            'Next Service Labels',
                            'No Call Out Charge',
                            'Competitive rates for all consumables',
                        ] as $item)
                            <li class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#1791a7] mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M20 6 9 17l-5-5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="border border-slate-200 rounded-lg p-8 shadow-sm">
                    <h3 class="font-semibold text-slate-800 mb-2">Ad hoc</h3>
                    <p class="text-sm text-slate-500 mb-5">We're here when you need us, services can be booked as required.</p>
                    <ul class="space-y-2.5 text-sm">
                        @foreach ([
                            'Call Out as required',
                            'Equipment can also be serviced on request',
                            'Full repair and service reports',
                            'Next service labels',
                            'Competitive rates for all consumables',
                        ] as $item)
                            <li class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#1791a7] mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M20 6 9 17l-5-5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= BENEFITS ================= --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-800 text-center mb-12">Benefits of ongoing maintenance</h2>

            @php
                $benefits = [
                    [
                        'title' => 'Preventative',
                        'subtitle' => 'Cuts down on added cost, time and lost productivity.',
                        'items' => ['Increases Efficiency', 'Lowers the risk of breakdowns', 'Lengthens product life span', 'Promotes Safety', 'Save money long-term'],
                    ],
                    [
                        'title' => 'Hassle Free',
                        'subtitle' => 'All maintenance plans come with an expert service.',
                        'items' => ['Unlimited Call Outs', '24 Hour Support', 'Minimal Downtime', 'Emergency Repairs', 'Replacements'],
                    ],
                    [
                        'title' => 'Multi-Location',
                        'subtitle' => 'Do you have more than one location? We can serve them all!',
                        'items' => ['Friendly Service', 'Location Management', 'Asset Management', 'Planned Maintenance', 'Cost Effective'],
                    ],
                ];
            @endphp

            <div class="grid md:grid-cols-3 gap-8">
                @foreach ($benefits as $benefit)
                    <div class="bg-white border border-slate-200 rounded-lg p-8 shadow-sm">
                        <h3 class="font-semibold text-slate-800 mb-2">{{ $benefit['title'] }}</h3>
                        <p class="text-sm text-slate-500 mb-5">{{ $benefit['subtitle'] }}</p>
                        <ul class="space-y-2 text-sm text-slate-600">
                            @foreach ($benefit['items'] as $item)
                                <li class="flex items-start gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#1791a7] mt-2 shrink-0"></span>
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
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

    {{-- ================= SERVICE SECTORS ================= --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl font-semibold text-slate-800 text-center mb-12">Service Sectors</h2>

            @php
                $sectors = [
                    [
                        'name' => 'Medical Equipment',
                        'text' => "Horizon-LAB's team of highly trained medical engineers are there when you need them, helping to keep your healthcare establishment in full working order.",
                        'icon' => 'M3 13h18M3 13a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2M3 13v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4M7 11V8a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v3',
                    ],
                    [
                        'name' => 'Consumable Replacements',
                        'text' => "Whatever you need Horizon-LAB's replacement consumable service has you covered. We can supply directly to you and our team can even fit them on site.",
                        'icon' => 'M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0ZM12 8v4l3 3',
                    ],
                    [
                        'name' => 'Partner Solutions',
                        'text' => "We supply a wide range of medical repair solutions to B2B businesses that don't have direct access to service repair technicians. Find out more about our partner solutions.",
                        'icon' => 'M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m5-3.13a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM16 8a4 4 0 0 1 0 7.75',
                    ],
                    [
                        'name' => 'Testing',
                        'text' => 'Looking to have your medical equipment tested by a professional? Our team have experience in testing all types of medical equipment, big or small.',
                        'icon' => 'M9 3v6l-4.5 8.5A2 2 0 0 0 6.3 21h11.4a2 2 0 0 0 1.8-3.5L15 9V3M9 3h6M9 12h6',
                    ],
                    [
                        'name' => 'Upholstery',
                        'text' => 'We repair a range of medical upholstery, including medical sofas, chairs, trolleys, and waiting room seating. We only use the highest quality medical-grade fabrics.',
                        'icon' => 'M4 18v-6a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v6M4 18h16M4 18v2M20 18v2M8 12h.01M16 12h.01',
                    ],
                    [
                        'name' => 'Medical Furniture',
                        'text' => 'Our specialised furniture ensures both you and your client\'s experience will vastly improve over time and will integrate seamlessly into your daily workflow.',
                        'icon' => 'M3 7h18M3 12h18M3 17h18M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7',
                    ],
                ];
            @endphp

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($sectors as $sector)
                    <div class="bg-white border border-slate-200 rounded-lg p-8 shadow-sm hover:shadow-md transition">
                        <div class="w-12 h-12 rounded-lg bg-teal-50 flex items-center justify-center mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-teal-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path d="{{ $sector['icon'] }}" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-800 mb-3">{{ $sector['name'] }}</h3>
                        <p class="text-sm leading-relaxed text-slate-500">{{ $sector['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= MEDICAL FURNITURE ================= --}}
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
            
            <div class="bg-gray-50 rounded-lg p-8">
                <h3 class="font-semibold text-slate-800 mb-3">Medical Equipment</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    We can provide bespoke furnishing solutions to suit every project. We work first-hand with suppliers, procurement teams and public bodies which ensures you receive the best value to quality ratio possible.
                </p>
            </div>
        </div>
    </section>

    {{-- ================= CTA STRIP ================= --}}
    <section class="bg-slate-900 py-14">
        <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div>
                <h2 class="text-white text-2xl font-semibold">Medical Equipment, Servicing &amp; Repairs</h2>
                <p class="text-slate-300 mt-1">Get dependable support for the equipment your team relies on.</p>
            </div>
            <a href="{{ route('service-requests.create') }}" class="px-7 py-3 rounded bg-[#1791a7] hover:bg-[#147c8f] text-white font-semibold text-sm whitespace-nowrap transition">
                Request a service
            </a>
        </div>
    </section>

    @include('partials.footer')

</body>
</html>