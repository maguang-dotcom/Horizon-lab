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
        <div class="max-w-3xl mx-auto px-6 text-center text-dark">
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-30"
                 style="background-image: url('{{ asset('images/repair.png') }}');">
            </div>
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
            <h2 class="text-2xl sm:text-3xl font-semibold text-[#0f2b3a] tracking-tight text-center mb-12">Our Services</h2>

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
                    <h3 class="font-semibold text-slate-800 mb-3">Installation & Commissioning</h3>
                    <p class="text-sm leading-relaxed text-slate-500">
                        Installation of new medical equipment<br>
                        Equipment setup and configuration<br>
                        Testing before clinical use<br>
                        Commissioning and acceptance testing<br>
                        Connecting equipment to hospital systems
                    </p>
                </div>
                
                <div class="bg-white border border-slate-200 rounded-lg p-8 shadow-sm">
                    <h3 class="font-semibold text-slate-800 mb-3">Laboratory Equipment Services</h3>
                    <p class="text-sm leading-relaxed text-slate-500">
                        Laboratory analyzer maintenance<br>
                        Centrifuge servicing<br>
                        Microscope maintenance<br>
                        Incubator and refrigerator checks<br>
                        Autoclave testing<br>
                        Calibration of laboratory instruments
                    </p>
                </div>
                
                <div class="bg-white border border-slate-200 rounded-lg p-8 shadow-sm">
                    <h3 class="font-semibold text-slate-800 mb-3">Medical Technology & Software</h3>
                    <p class="text-sm leading-relaxed text-slate-500">
                        Biomedical engineers can also work on:

                        Medical-device software<br>
                        Hospital equipment monitoring systems<br>
                        Equipment-management databases<br>
                        IoT-based equipment monitoring<br>
                        Digital health systems<br>
                        AI-assisted medical technologies
                    </p>
                </div>
                <div class="bg-white border border-slate-200 rounded-lg p-8 shadow-sm">
                    <h3 class="font-semibold text-slate-800 mb-3">Training & Technical Support</h3>
                    <p class="text-sm leading-relaxed text-slate-500">
                       Training hospital staff to use equipment safely<br>
                       User training after installation<br>
                       Technical troubleshooting support<br>
                       Creating equipment user guides<br>
                       Emergency technical support
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= WHAT'S INCLUDED ================= --}}
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-800 text-center mb-12">Our Products</h2>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="border border-slate-200 rounded-lg p-8 shadow-sm">
                    <h3 class="font-semibold text-slate-800 mb-2">Medical reagents</h3>
                    <p class="text-sm text-slate-500 mb-5">Have your maintenance team on call 24/7, for a fixed yearly cost.</p>
                    <ul class="space-y-2.5 text-sm">
                       <img src="{{ asset('images/medicalReagent.jpeg') }}" alt="Medical reagents" class="w-full h-auto rounded-lg mb-4">
                           
                    </ul>
                </div>

                <div class="border border-slate-200 rounded-lg p-8 shadow-sm">
                    <h3 class="font-semibold text-slate-800 mb-2">Ad hoc</h3>
                    <p class="text-sm text-slate-500 mb-5">We're here when you need us, services can be booked as required.</p>
                    <ul class="space-y-2.5 text-sm">
                        <img src="{{ asset('images/RapidDiagnosticTests1.jpeg') }}" alt="Ad hoc" class="w-full h-auto rounded-lg mb-4">
                        <img src="{{ asset('images/RapidDiagnosticTests2.jpeg') }}" alt="Ad hoc" class="w-full h-auto rounded-lg mb-4">
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