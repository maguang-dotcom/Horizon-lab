<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · Horizon Lab</title>

    {{-- Remove this line if your project already loads Tailwind through Vite --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --navy: #0B1D4D;
            --navy-active: #1F4FD8;
            --blue: #1F5EE8;
            --line: #E6ECF5;
            --page: #F3F6FC;
            --ink: #0F1B3D;
        }
        html, body { font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif; color: var(--ink); background: var(--page); }
        .nav-link { display:flex; align-items:center; gap:12px; padding:10px 14px; border-radius:8px; font-size:14px; font-weight:500; color:#DCE5FA; transition:background .15s; }
        .nav-link:hover { background:rgba(255,255,255,.08); }
        .nav-link.active { background:var(--navy-active); color:#fff; }
        .nav-link svg { width:20px; height:20px; flex-shrink:0; }
        .card { background:#fff; border:1px solid var(--line); border-radius:12px; padding:20px; }
        :focus-visible { outline:2px solid var(--blue); outline-offset:2px; }
    </style>
    @stack('styles')
</head>
@php
    $dashboardNotifications = $notifications ?? collect();
@endphp
<body data-dashboard-role="facility" data-dashboard-user-id="{{ auth()->id() }}" class="min-h-screen">

@php
    $navItems = [
        ['Dashboard',        'facility.dashboard',              'facility.dashboard',              '<path d="M3 11.5 12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-8.5Z"/>'],
        ['Service Requests', 'facility.service-requests.index', 'facility.service-requests.*',     '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 11h6M9 15h6"/>'],
        ['Equipment',        'facility.equipment.index',        'facility.equipment.*',            '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/>'],
        ['Reports',          'facility.reports.index',          'facility.reports.*',              '<path d="M6 20V11M12 20V4M18 20v-6"/>'],
        ['Settings',         'facility.settings.edit',          'facility.settings.*',             '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1Z"/>'],
    ];
@endphp

{{-- Sidebar --}}
<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full lg:translate-x-0 transition-transform flex flex-col"
       style="background:var(--navy);">
    <div class="flex items-center gap-3 px-5 h-20 border-b border-white/10">
        <img src="{{ asset('images/logo.png') }}" alt="Horizon Lab logo" class="w-10 h-10 shrink-0 object-contain">
        <span class="text-white font-semibold leading-tight text-lg">Horizon Lab</span>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1">
        @foreach ($navItems as [$label, $routeName, $pattern, $icon])
            <a href="{{ Route::has($routeName) ? route($routeName) : '#' }}"
               class="nav-link {{ request()->routeIs($pattern) ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $icon !!}</svg>
                {{ $label }}
            </a>
        @endforeach

        <div class="my-3 mx-2 border-t border-white/10"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-full text-left">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                Logout
            </button>
        </form>
    </nav>
</aside>
<div id="backdrop" class="fixed inset-0 z-30 bg-slate-900/40 hidden lg:hidden"></div>

<div class="lg:pl-64">
    {{-- Top bar --}}
    <header class="sticky top-0 z-20 h-16 bg-white border-b flex items-center justify-between px-5" style="border-color:var(--line);">
        <button id="menuBtn" class="p-2 -ml-2 rounded-lg hover:bg-slate-100" aria-label="Toggle menu">
            <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <div class="mx-3 flex min-w-0 flex-1 items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 lg:max-w-md">
            <svg viewBox="0 0 24 24" class="h-5 w-5 shrink-0 text-slate-500" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="6"/><path d="m16 16 5 5"/></svg>
            <input type="search" data-dashboard-search placeholder="Search requests and equipment" aria-label="Search dashboard records" class="min-w-0 w-full bg-transparent text-sm outline-none">
        </div>

        <div class="flex items-center gap-5">
            <div class="relative" data-dashboard-notifications>
                <button type="button" data-notification-toggle class="relative p-2 rounded-lg hover:bg-slate-100" aria-label="Notifications" aria-expanded="false">
                    <svg viewBox="0 0 24 24" class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 1 1 12 0c0 7 3 9 3 9H3s3-2 3-9M10.3 21a1.9 1.9 0 0 0 3.4 0"/></svg>
                    <span data-notification-count class="{{ $dashboardNotifications->isEmpty() ? 'hidden' : '' }} absolute top-0.5 right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-semibold inline-flex items-center justify-center">{{ $dashboardNotifications->count() }}</span>
                </button>
                <div data-notification-panel hidden class="absolute right-0 top-full z-50 mt-2 w-80 max-w-[calc(100vw-2rem)] overflow-hidden rounded-xl border border-slate-200 bg-white text-slate-800 shadow-xl">
                    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                        <div><h2 class="text-sm font-bold">Notifications</h2><p data-notification-summary class="text-xs text-slate-500">{{ $dashboardNotifications->count() }} unread</p></div>
                        <button type="button" data-notification-mark-all class="text-xs font-semibold text-blue-700 hover:text-blue-900">Mark all read</button>
                    </div>
                    <div data-notification-list class="max-h-80 overflow-y-auto">
                        @foreach ($dashboardNotifications as $notification)
                            <div data-notification-id="{{ $notification['id'] }}" data-notification-category="{{ $notification['category'] }}" class="flex items-start gap-3 border-b border-slate-100 px-4 py-3 hover:bg-slate-50">
                                <a data-notification-link href="{{ $notification['url'] }}" class="min-w-0 flex-1"><span class="block text-sm font-semibold">{{ $notification['title'] }}</span><span class="mt-0.5 block truncate text-xs text-slate-600">{{ $notification['message'] }}</span><span class="mt-1 block text-[11px] text-slate-400">{{ $notification['time'] }}</span></a>
                                <button type="button" data-notification-dismiss aria-label="Dismiss notification" class="rounded p-1 text-slate-400 hover:bg-slate-200">&times;</button>
                            </div>
                        @endforeach
                        <p data-notification-empty class="{{ $dashboardNotifications->isEmpty() ? '' : 'hidden' }} px-4 py-6 text-center text-sm text-slate-500">You're all caught up.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white" style="background:var(--blue);">
                    <svg viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0Z"/></svg>
                </div>
                <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->name ?? 'Facility Manager' }}</span>
                <svg viewBox="0 0 24 24" class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m6 9 6 6 6-6"/></svg>
            </div>
        </div>
    </header>

    <main class="p-5 lg:p-6">
        @yield('content')
    </main>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('backdrop');
    function toggleMenu() {
        sidebar.classList.toggle('-translate-x-full');
        backdrop.classList.toggle('hidden');
    }
    document.getElementById('menuBtn').addEventListener('click', toggleMenu);
    backdrop.addEventListener('click', toggleMenu);
</script>
<script src="{{ asset('js/dashboard-tools.js') }}" defer></script>
@stack('scripts')
</body>
</html>