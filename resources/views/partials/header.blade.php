@php
    $navLink = fn (string $route) => request()->routeIs($route) ? 'text-teal-700' : 'text-slate-600 hover:text-teal-700';
@endphp

<header class="border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Horizon Labs" class="h-16 w-16 object-contain">
            <span class="text-xl font-semibold tracking-tight text-[#123d67]">Horizon-LABS</span>
        </a>

        <nav class="hidden lg:flex items-center gap-9 text-[15px] font-medium">
             <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-md border border-dashed border-slate-300 text-slate-600 hover:border-slate-400">Home</a>
                <a href="{{ route('about-Us') }}" class="px-4 py-1.5 rounded-md border border-dashed border-slate-300 text-slate-600 hover:border-slate-400">About Us</a>
                <a href="{{route('services-Repair') }}" class="px-4 py-1.5 rounded-md border border-dashed border-slate-300 text-slate-600 hover:border-slate-400">Services & Repair</a>
                <a href="{{ route('work-with-Us') }}" class="px-4 py-1.5 rounded-md border border-dashed border-slate-300 text-slate-600 hover:border-slate-400">Work With Us</a>
                <a href="{{ route('contact.show') }}" class="px-4 py-1.5 rounded-md border border-dashed border-slate-300 text-slate-600 hover:border-slate-400">Contact Us</a>
        </nav>
    </div>
</header>