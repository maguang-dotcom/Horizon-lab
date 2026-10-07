@php
    $navLink = fn (string $route) => request()->routeIs($route)
        ? 'bg-[#123d67] text-white shadow-sm'
        : 'bg-[#eaf2f8] text-[#123d67] hover:bg-[#123d67] hover:text-white';
@endphp

<header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="w-full px-4 py-4 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <img
                src="{{ asset('images/logo.png') }}"
                alt="Horizon-LABS Logo"
                class="h-16 w-16 object-contain"
            >

            <span class="text-xl font-semibold tracking-tight text-[#123d67]">
                Horizon-LABS
            </span>
        </a>

        {{-- Navigation --}}
        <nav class="hidden lg:flex items-center gap-3 text-[15px] font-medium">

            <a
                href="{{ route('home') }}"
                class="px-4 py-2 rounded-md transition duration-200 {{ $navLink('home') }}"
            >
                Home
            </a>

            <a
                href="{{ route('about-Us') }}"
                class="px-4 py-2 rounded-md transition duration-200 {{ $navLink('about-Us') }}"
            >
                About Us
            </a>

            <a
                href="{{ route('services-Repair') }}"
                class="px-4 py-2 rounded-md transition duration-200 {{ $navLink('services-Repair') }}"
            >
                Services & Repair
            </a>

            <a
                href="{{ route('work-with-Us') }}"
                class="px-4 py-2 rounded-md transition duration-200 {{ $navLink('work-with-Us') }}"
            >
                Work With Us
            </a>

            <a
                href="{{ route('contact.show') }}"
                class="px-4 py-2 rounded-md transition duration-200 {{ $navLink('contact.show') }}"
            >
                Contact Us
            </a>

        </nav>

    </div>
</header>