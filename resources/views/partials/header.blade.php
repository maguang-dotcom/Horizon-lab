@php
    $navLink = fn (string $route) => request()->routeIs($route)
        ? 'bg-[#123d67] text-white shadow-sm'
        : 'bg-[#eaf2f8] text-[#123d67] hover:bg-[#123d67] hover:text-white';

    $links = [
        ['route' => 'home',            'label' => 'Home'],
        ['route' => 'about-Us',        'label' => 'About Us'],
        ['route' => 'services-Repair', 'label' => 'Services & Repair'],
        ['route' => 'work-with-Us',    'label' => 'Work With Us'],
        ['route' => 'contact.show',    'label' => 'Contact Us'],
    ];
@endphp

<header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="w-full px-4 py-3 sm:py-4 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <img
                src="{{ asset('images/logo.png') }}"
                alt="Horizon-LABS Logo"
                class="h-12 w-12 sm:h-16 sm:w-16 object-contain"
            >

            <span class="text-lg sm:text-xl font-semibold tracking-tight text-[#123d67]">
                Horizon-LABS
            </span>
        </a>

        {{-- Desktop navigation (large screens) --}}
        <nav class="hidden lg:flex items-center gap-3 text-[15px] font-medium" aria-label="Main">
            @foreach ($links as $link)
                <a
                    href="{{ route($link['route']) }}"
                    class="px-4 py-2 rounded-md transition duration-200 {{ $navLink($link['route']) }}"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Mobile menu button (small screens) --}}
        <button
            id="mobileMenuButton"
            type="button"
            class="lg:hidden inline-flex items-center justify-center rounded-md p-2 text-[#123d67]
                   hover:bg-[#eaf2f8] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#123d67]"
            aria-controls="mobileMenu"
            aria-expanded="false"
            aria-label="Open menu"
        >
            {{-- Hamburger --}}
            <svg id="iconOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            {{-- Close --}}
            <svg id="iconClose" xmlns="http://www.w3.org/2000/svg" class="hidden h-6 w-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
            </svg>
        </button>
    </div>

    {{-- Mobile navigation panel --}}
    <nav id="mobileMenu" class="hidden lg:hidden border-t border-gray-200 bg-white px-4 pb-4 pt-3" aria-label="Mobile">
        <div class="flex flex-col gap-2 text-[15px] font-medium">
            @foreach ($links as $link)
                <a
                    href="{{ route($link['route']) }}"
                    class="block px-4 py-3 rounded-md transition duration-200 {{ $navLink($link['route']) }}"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>
    </nav>
</header>

<script>
    (function () {
        const button = document.getElementById('mobileMenuButton');
        const menu   = document.getElementById('mobileMenu');
        const open   = document.getElementById('iconOpen');
        const close  = document.getElementById('iconClose');
        if (!button || !menu) return;

        function setOpen(isOpen) {
            menu.classList.toggle('hidden', !isOpen);
            open.classList.toggle('hidden', isOpen);
            close.classList.toggle('hidden', !isOpen);
            button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            button.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');
        }

        button.addEventListener('click', function () {
            setOpen(menu.classList.contains('hidden'));
        });

        // Close when the screen grows to desktop size
        window.matchMedia('(min-width: 1024px)').addEventListener('change', function (e) {
            if (e.matches) setOpen(false);
        });

        // Close with Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') setOpen(false);
        });
    })();
</script>