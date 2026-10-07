<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Engineer') — HealthFacilityPro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --navy:#14213D; --blue:#2F6FE4; --bg:#F3F5F9; --line:#E6E9EF; }
    </style>
    @stack('styles')
</head>
<body style="background: var(--bg);" class="text-slate-800">
<div class="flex min-h-screen">

    <aside class="w-60 shrink-0 flex flex-col justify-between" style="background: var(--navy);">
        <div>
            <div class="px-4 py-5 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,.08);">
                <img src="{{ asset('images/logo.png') }}" alt="Horizon Lab logo" class="w-10 h-10 object-contain shrink-0">
                <div class="leading-tight">
                    <p class="text-white font-semibold text-base tracking-tight">Horizon Lab</p>
                    <p class="text-[11px]" style="color:#8B98B8;">Engineer Portal</p>
                </div>
            </div>
            <nav class="px-3 py-4 space-y-1">
                <a href="{{ route('engineer.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm"
                   style="color:#fff; background: {{ request()->routeIs('engineer.dashboard') ? 'var(--blue)' : 'transparent' }};">
                    <span class="w-4 h-4">@include('facility.partials.icon', ['name' => 'home'])</span> Dashboard
                </a>
                <a href="{{ route('engineer.dashboard') }}#assignments" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm" style="color:#AEB8CE;">
                    <span class="w-4 h-4">@include('facility.partials.icon', ['name' => 'list'])</span> My Assignments
                </a>
            </nav>
        </div>
        <div class="px-5 py-5 text-xs" style="color:#8B98B8; border-top:1px solid rgba(255,255,255,.08);">
            Signed in as<br><span class="text-white text-sm">{{ auth()->user()->name }}</span>
        </div>
    </aside>

    <div class="flex-1 min-w-0">
        <main class="p-6 max-w-6xl">
            @if (session('status'))
                <div class="mb-5 px-4 py-3 text-sm rounded-lg" style="background:#E7F6EC; color:#1F7A45; border:1px solid #BFE3CB;">
                    {{ session('status') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>