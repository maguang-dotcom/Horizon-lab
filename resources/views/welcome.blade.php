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

        {{-- LEFT SIDE BACKGROUND IMAGE (behind the text) --}}
        <div class="pointer-events-none absolute inset-y-0 left-0 -z-10 w-full lg:w-1/2" aria-hidden="true">

            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-30"
                 style="background-image: url('{{ asset('images/landing.png') }}');">
            </div>

            {{-- Fades the image into the dark background so the text stays readable --}}
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/40 via-slate-900/80 to-slate-900"></div>
        </div>

        {{-- RIGHT SIDE BACKGROUND IMAGE (large screens only) --}}
        <div class="pointer-events-none absolute inset-y-0 right-0 -z-10 hidden w-1/2 lg:block" aria-hidden="true">

            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ asset('images/home-image.png') }}');">
            </div>

            {{-- Blends the image into the left half and darkens the bottom for the caption --}}
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/20 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 py-20 grid lg:grid-cols-2 gap-12 items-center">

            {{-- Left column --}}
            <div>

            


                {{-- Main Heading --}}
                <h1 class="text-4xl sm:text-5xl font-bold text-white leading-tight mb-6">
                   Behind every healthy machine is a life saved.
                </h1>


                {{-- Description --}}
                <p class="text-slate-300 text-base leading-relaxed mb-8 max-w-lg">
                    Report equipment problems, find qualified biomedical professionals
                    and get the technical support your facility needs — all in one platform.
                </p>


                {{-- ================= BECOME OUR PARTNER ================= --}}
                <div class="relative inline-block mb-12">

                    {{-- Main Partner Button --}}
                    <button
                        id="partnerButton"
                        type="button"
                        onclick="togglePartnerMenu()"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-md
                               bg-teal-500 hover:bg-teal-400
                               text-slate-900 font-semibold text-sm
                               transition duration-200">

                        Become Our Partner

                        <svg id="partnerArrow"
                             xmlns="http://www.w3.org/2000/svg"
                             class="w-4 h-4 transition-transform duration-200"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <path d="M6 9l6 6 6-6"
                                  stroke-linecap="round"
                                  stroke-linejoin="round" />
                        </svg>
                    </button>


                    {{-- ================= PARTNER OPTIONS ================= --}}
                    <div
                        id="partnerOptions"
                        class="hidden absolute left-0 top-full mt-3 w-80
                               rounded-xl bg-slate-900
                               border border-slate-700
                               shadow-2xl overflow-hidden z-50">

                        {{-- Facility Option --}}
                        <a href="{{ route('register') }}"
                           class="flex items-start gap-4 px-5 py-4
                                  hover:bg-slate-800 transition duration-200">

                            {{-- Icon --}}
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg
                                        bg-teal-500/10 flex items-center justify-center
                                        text-teal-400">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">

                                    <path d="M3 21h18"/>
                                    <path d="M5 21V7l7-4 7 4v14"/>
                                    <path d="M9 21v-5h6v5"/>
                                    <path d="M9 10h.01"/>
                                    <path d="M12 10h.01"/>
                                    <path d="M15 10h.01"/>
                                </svg>

                            </div>

                            {{-- Text --}}
                            <div>
                                <div class="text-white font-semibold text-sm">
                                    Join as a Healthcare Facility
                                </div>

                                <p class="text-slate-400 text-xs leading-relaxed mt-1">
                                    For hospitals and healthcare facilities
                                    that need equipment support.
                                </p>
                            </div>

                        </a>


                        {{-- Divider --}}
                        <div class="border-t border-slate-700"></div>


                        {{-- Biomedical Professional Option --}}
                        <a href="{{ route('engineer-register') }}"
                           class="flex items-start gap-4 px-5 py-4
                                  hover:bg-slate-800 transition duration-200">

                            {{-- Icon --}}
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg
                                        bg-teal-500/10 flex items-center justify-center
                                        text-teal-400">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">

                                    <circle cx="9" cy="7" r="4"/>

                                    <path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/>

                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>

                                    <path d="M21 21v-2a4 4 0 0 0-3-3.87"/>
                                </svg>

                            </div>

                            {{-- Text --}}
                            <div>
                                <div class="text-white font-semibold text-sm">
                                    Join as a Biomedical Professional
                                </div>

                                <p class="text-slate-400 text-xs leading-relaxed mt-1">
                                    For biomedical engineers and technicians
                                    looking to join our network.
                                </p>
                            </div>

                        </a>

                    </div>

                </div>
            </div>


            {{-- ================= RIGHT COLUMN (sits over the background image) ================= --}}
            <div class="hidden lg:flex min-h-[420px] items-end">
                <p class="text-sm font-medium text-white">
                    Reliable equipment. Better care.
                </p>
            </div>

        </div>
    </section>


    @include('partials.footer')


    {{-- ================= DROPDOWN JAVASCRIPT ================= --}}
    <script>

        function togglePartnerMenu() {

            const menu = document.getElementById('partnerOptions');
            const arrow = document.getElementById('partnerArrow');

            menu.classList.toggle('hidden');

            arrow.classList.toggle('rotate-180');
        }


        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {

            const button = document.getElementById('partnerButton');
            const menu = document.getElementById('partnerOptions');

            if (!button.contains(event.target) && !menu.contains(event.target)) {

                menu.classList.add('hidden');

                document.getElementById('partnerArrow')
                    .classList.remove('rotate-180');
            }

        });

    </script>

</body>
</html>