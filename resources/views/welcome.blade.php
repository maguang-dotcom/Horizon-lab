<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horizon-LAB — Home</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Inter, sans-serif;
        }
    </style>
</head>

<body class="bg-white">

    @include('partials.header')

    {{-- ================= HERO ================= --}}
    <section class="relative isolate overflow-hidden bg-slate-900">

        {{-- Left background image --}}
        <div class="pointer-events-none absolute inset-y-0 left-0 -z-10 w-full lg:w-1/2" aria-hidden="true">
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-30"
                 style="background-image: url('{{ asset('images/landing.png') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/40 via-slate-900/80 to-slate-900"></div>
        </div>

        {{-- Right background image (large screens only) --}}
        <div class="pointer-events-none absolute inset-y-0 right-0 -z-10 hidden w-1/2 lg:block" aria-hidden="true">
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ asset('images/home-image.png') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/20 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
        </div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-6 py-20 lg:grid-cols-2">

            {{-- Left column --}}
            <div>

                <h1 class="mb-6 text-4xl font-bold leading-tight text-white sm:text-5xl">
                    Behind every healthy machine is a life saved.
                </h1>

                <p class="mb-8 max-w-lg text-base leading-relaxed text-slate-300">
                    Report equipment problems, find qualified biomedical professionals
                    and get the technical support your facility needs — all in one platform.
                </p>

                {{-- Become our partner --}}
                <div class="relative mb-12 block lg:inline-block" id="partnerMenu">

                    <button
                        id="partnerButton"
                        type="button"
                        aria-haspopup="true"
                        aria-expanded="false"
                        aria-controls="partnerOptions"
                        class="inline-flex items-center gap-2 rounded-md bg-teal-500 px-5 py-3
                               text-sm font-semibold text-slate-900 transition duration-200 hover:bg-teal-400">

                        Become Our Partner

                        <svg id="partnerArrow"
                             xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4 transition-transform duration-200"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             aria-hidden="true">
                            <path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    {{-- In the page flow on mobile, floating on large screens --}}
                    <div
                        id="partnerOptions"
                        class="z-50 mt-3 hidden w-full max-w-sm overflow-hidden rounded-xl
                               border border-slate-700 bg-slate-900 shadow-2xl
                               lg:absolute lg:left-0 lg:top-full lg:w-80 lg:max-w-none">

                        {{-- Healthcare facility --}}
                        <a href="{{ route('register') }}"
                           class="flex items-start gap-4 px-5 py-4 transition duration-200 hover:bg-slate-800">

                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-teal-500/10 text-teal-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M3 21h18"/>
                                    <path d="M5 21V7l7-4 7 4v14"/>
                                    <path d="M9 21v-5h6v5"/>
                                    <path d="M9 10h.01"/>
                                    <path d="M12 10h.01"/>
                                    <path d="M15 10h.01"/>
                                </svg>
                            </div>

                            <div>
                                <div class="text-sm font-semibold text-white">Join as a Healthcare Facility</div>
                                <p class="mt-1 text-xs leading-relaxed text-slate-400">
                                    For hospitals and healthcare facilities that need equipment support.
                                </p>
                            </div>
                        </a>

                        <div class="border-t border-slate-700"></div>

                        {{-- Biomedical professional --}}
                        <a href="{{ route('engineer-register') }}"
                           class="flex items-start gap-4 px-5 py-4 transition duration-200 hover:bg-slate-800">

                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-teal-500/10 text-teal-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                    <path d="M21 21v-2a4 4 0 0 0-3-3.87"/>
                                </svg>
                            </div>

                            <div>
                                <div class="text-sm font-semibold text-white">Join as a Biomedical Professional</div>
                                <p class="mt-1 text-xs leading-relaxed text-slate-400">
                                    For biomedical engineers and technicians looking to join our network.
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right column (sits over the background image) --}}
            <div class="hidden min-h-[420px] items-end lg:flex">
                <p class="text-sm font-medium text-white">
                    Reliable equipment. Better care.
                </p>
            </div>

        </div>
    </section>

    @include('partials.footer')

    <script>
        (function () {
            const wrapper = document.getElementById('partnerMenu');
            const button  = document.getElementById('partnerButton');
            const menu    = document.getElementById('partnerOptions');
            const arrow   = document.getElementById('partnerArrow');

            if (!wrapper || !button || !menu) return;

            function setOpen(isOpen) {
                menu.classList.toggle('hidden', !isOpen);
                arrow.classList.toggle('rotate-180', isOpen);
                button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }

            button.addEventListener('click', function () {
                setOpen(menu.classList.contains('hidden'));
            });

            document.addEventListener('click', function (event) {
                if (!wrapper.contains(event.target)) setOpen(false);
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') setOpen(false);
            });
        })();
    </script>

</body>
</html>