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
            --navy-deep: #08163B;
            --blue: #1F62F0;
            --line: #E6ECF5;
            --page: #F4F7FC;
            --ink: #0B1D4D;
        }
        html, body { font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif; color: var(--ink); background: var(--page); }
        .nav-link { display:flex; align-items:center; gap:14px; padding:13px 16px; border-radius:10px; font-size:15px; font-weight:500; color:#DCE5FA; transition:background .15s; }
        .nav-link:hover { background:rgba(255,255,255,.08); }
        .nav-link.active { background:var(--blue); color:#fff; font-weight:600; }
        .nav-link svg { width:22px; height:22px; flex-shrink:0; }
        :focus-visible { outline:2px solid var(--blue); outline-offset:2px; }
    </style>
    @stack('styles')
</head>
@php
    $dashboardNotifications = $notifications ?? collect();
@endphp
<body data-dashboard-role="engineer" data-dashboard-user-id="{{ auth()->id() }}" class="min-h-screen">

@php
    $navItems = [
        ['Dashboard', route('engineer.dashboard'), 'engineer.dashboard', 'dashboard', '<path d="M3 11.5 12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-8.5Z"/>'],
        ['Assignments', route('engineer.assignments'), 'engineer.assignments', 'assignments', '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 10h.01M12 10h3M9 14h.01M12 14h3M9 18h.01M12 18h3"/>'],
        ['Facilities', route('engineer.facilities'), 'engineer.facilities', 'assigned-facilities', '<rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="M9 7h.01M12 7h.01M15 7h.01M9 11h.01M12 11h.01M15 11h.01M10 21v-4h4v4"/>'],
        ['Reports', route('engineer.reports.index'), 'engineer.reports.*', 'reports', '<path d="M7 3h7l4 4v14H7V3Z"/><path d="M14 3v4h4M10 12h5M10 16h5"/>'],
        ['Settings', route('engineer.settings'), 'engineer.settings.*', 'settings', '<path d="M12 1a11 11 0 1 0 11 11A11 11 0 0 0 12 1Zm0 20a9 9 0 1 1 9-9 9 9 0 0 1-9 9Zm1-14h-2v6h6v-2h-4Z"/>'],
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

    {{-- Nav --}}
    <nav class="px-4 mt-2 space-y-2">
        @foreach ($navItems as [$label, $url, $pattern, $section, $icon])
            <a href="{{ $url }}" data-engineer-section="{{ $section }}"
               class="nav-link {{ request()->routeIs($pattern) ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $icon !!}</svg>
                <span class="flex-1">{{ $label }}</span>
                @if ($label === 'Assignments' && ($activeCount ?? 0) > 0)
                    <span class="w-2.5 h-2.5 rounded-full bg-sky-400"></span>
                @endif
            </a>
        @endforeach

        <div class="my-3 mx-2 border-t border-white/10"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-full text-left">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                <span class="flex-1">Logout</span>
            </button>
        </form>
    </nav>

    {{-- Footer tagline --}}
    <div class="mt-auto px-6 pb-8">
        <svg viewBox="0 0 56 56" class="w-14 h-14 text-sky-400 mb-3" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="22" cy="14" r="7"/><path d="M8 46v-7a10 10 0 0 1 10-10h8"/>
            <circle cx="42" cy="38" r="5"/><path d="M42 29v3M42 44v3M33 38h3M48 38h3M35.6 31.6l2 2M46.4 42.4l2 2M48.4 31.6l-2 2M37.6 42.4l-2 2"/>
        </svg>
        <p class="text-slate-200 text-sm leading-relaxed">Reliable Equipment.<br>Better Healthcare.</p>
        <p class="text-slate-400 text-xs mt-4 leading-snug">Horizon Lab<br>Biomedical Engineering</p>
    </div>

    <div class="pointer-events-none absolute -bottom-24 -left-24 w-96 h-96 rounded-full" style="background:rgba(255,255,255,.03);"></div>
</aside>
<div id="backdrop" class="fixed inset-0 z-30 bg-slate-900/40 hidden lg:hidden"></div>

<div class="lg:pl-72">
    {{-- Top bar --}}
    <header class="sticky top-0 z-20 h-[72px] bg-white flex items-center justify-between px-5 lg:px-8" style="box-shadow:0 1px 8px rgba(11,29,77,.06);">
        <button id="menuBtn" class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-slate-100" aria-label="Open menu">
            <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="mx-3 flex min-w-0 max-w-xl flex-1 items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
            <svg viewBox="0 0 24 24" class="h-5 w-5 shrink-0 text-slate-500" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="6"/><path d="m16 16 5 5"/></svg>
            <input type="search" data-dashboard-search placeholder="Search assignments and reports" aria-label="Search dashboard records" class="min-w-0 w-full bg-transparent text-sm outline-none">
        </div>

        <div class="flex items-center gap-4">
            <div class="relative" data-dashboard-notifications>
                <button type="button" data-notification-toggle class="relative p-2 rounded-lg hover:bg-slate-100" aria-label="Notifications" aria-expanded="false">
                    <svg viewBox="0 0 24 24" class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 1 1 12 0c0 7 3 9 3 9H3s3-2 3-9M10.3 21a1.9 1.9 0 0 0 3.4 0"/></svg>
                    <span data-notification-count class="{{ $dashboardNotifications->isEmpty() ? 'hidden' : '' }} absolute top-1.5 right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-semibold inline-flex items-center justify-center">{{ $dashboardNotifications->count() }}</span>
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
            <span class="h-8 w-px bg-slate-200"></span>
            <details class="relative group">
                <summary class="flex items-center gap-3 cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center" style="background:#E7EEFB; color:var(--navy);">
                        <svg viewBox="0 0 24 24" class="w-6 h-6" fill="currentColor"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0Z"/></svg>
                    </div>
                    <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->name ?? 'Engineer' }}</span>
                    <svg viewBox="0 0 24 24" class="w-4 h-4 text-slate-500 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m6 9 6 6 6-6"/></svg>
                </summary>
                <div class="absolute right-0 mt-3 w-52 rounded-xl bg-white py-2" style="box-shadow:0 10px 30px rgba(11,29,77,.15); border:1px solid var(--line);">
                    <div class="px-4 py-2 border-b" style="border-color:var(--line);">
                        <p class="text-sm font-semibold truncate">{{ auth()->user()->name ?? 'Engineer' }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 text-left">
                            <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </details>
        </div>
    </header>

    <main class="px-5 py-8 lg:px-10">
        @yield('content')
    </main>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('backdrop');
    const dashboardPath = new URL('{{ route('engineer.dashboard') }}', window.location.origin).pathname.replace(/\/+$/, '') || '/';
    const currentPath = window.location.pathname.replace(/\/+$/, '') || '/';
    const sectionLinks = [...document.querySelectorAll('[data-engineer-section]')];

    function syncActiveSection() {
        const activeSection = window.location.hash.slice(1) || (currentPath === dashboardPath ? 'dashboard' : null);
        if (!activeSection) return;

        sectionLinks.forEach(link => link.classList.toggle('active', link.dataset.engineerSection === activeSection));
    }

    function toggleMenu() { sidebar.classList.toggle('-translate-x-full'); backdrop.classList.toggle('hidden'); }
    document.getElementById('menuBtn').addEventListener('click', toggleMenu);
    backdrop.addEventListener('click', toggleMenu);
    window.addEventListener('hashchange', syncActiveSection);
    syncActiveSection();
</script>
<script src="{{ asset('js/dashboard-tools.js') }}" defer></script>
@stack('scripts')
</body>
</html>