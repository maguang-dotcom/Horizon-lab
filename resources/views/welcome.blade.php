<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horizon-LAB — Home</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Inter, sans-serif; }
    </style>
</head>
<body class="bg-white">

    @include('partials.header')

    {{-- ================= HERO ================= --}}
    <section class="bg-slate-900">
        <div class="max-w-7xl mx-auto px-6 py-20 grid lg:grid-cols-2 gap-12 items-center">

            {{-- Left column --}}
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-800 border border-slate-700 text-teal-400 text-xs font-medium mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M20 6 9 17l-5-5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Trusted Biomedical Network in Uganda
                </span>

                <h1 class="text-4xl sm:text-5xl font-bold text-white leading-tight mb-6">
                    Bridging the Gap in Healthcare Logistics.
                </h1>

                <p class="text-slate-400 text-base leading-relaxed mb-8 max-w-lg">
                    Report equipment problems, find qualified biomedical professionals and get the technical support your facility needs — all in one platform.
                </p>

                <div class="flex flex-wrap gap-3 mb-12">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-md bg-teal-500 hover:bg-teal-400 text-slate-900 font-semibold text-sm transition">
                        Request Equipment Service
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    <a href="{{ route('engineer-register') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-md bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white font-semibold text-sm transition">
                        Join as a Biomedical Professional
                    </a>
                </div>

                <div class="flex flex-wrap gap-5 text-sm text-slate-300">
                    <a href="{{ route('facility.login') }}" class="hover:text-teal-300">Healthcare facility sign in</a>
                    <a href="{{ route('engineer.login') }}" class="hover:text-teal-300">Engineer sign in</a>
                </div>

                <div class="border-t border-slate-800 pt-8 flex flex-wrap gap-12">
                    <div>
                        <div class="text-2xl font-bold text-white">{{ number_format($metrics['facilities'] ?? 0) }}</div>
                        <div class="text-teal-400 text-xs mt-1">Registered Facilities</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white">{{ number_format($metrics['engineers'] ?? 0) }}</div>
                        <div class="text-teal-400 text-xs mt-1">Available Engineers</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white">{{ number_format($metrics['resolved_requests'] ?? 0) }}</div>
                        <div class="text-teal-400 text-xs mt-1">Requests Resolved</div>
                    </div>
                </div>
            </div>

            {{-- Right column: biomedical engineering image --}}
            <figure class="relative overflow-hidden rounded-2xl shadow-2xl ring-1 ring-white/10">
                <img src="{{ asset('images/home-image.png') }}" alt="Biomedical engineer inspecting hospital equipment" class="h-full min-h-[420px] w-full object-cover object-center">
                <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/85 to-transparent px-6 pb-6 pt-20 text-sm font-medium text-white">
                    Reliable equipment. Better care.
                </figcaption>
            </figure>

        </div>
    </section>

    {{-- ================= TRUST STRIP ================= --}}
    <section class="bg-gray-50 py-8">
        <p class="text-center text-xs font-semibold text-slate-400 tracking-wide">
            Trusted by leading health institutions across East Africa
        </p>
    </section>

    @include('partials.footer')

</body>
</html>