<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | Horizon Lab</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#eef3fb] text-slate-800">
    @php
        $adminNavigation = [
            ['Dashboard', 'admin.dashboard'],
            ['Engineers', 'admin.engineers.index'],
            ['Service Requests', 'admin.service-requests.index'],
            ['Facilities', 'admin.facilities.index'],
            ['Assignments', 'admin.assignments.index'],
            ['Reports', 'admin.reports.index'],
            ['Users', 'admin.users.index'],
            ['Settings', 'admin.settings.index'],
        ];
    @endphp
    <div class="min-h-screen lg:grid lg:grid-cols-[260px_minmax(0,1fr)]">
        <aside class="bg-[#0b1d4d] px-4 py-5 text-white lg:min-h-screen">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 pb-5">
                <img src="{{ asset('images/logo.png') }}" alt="Horizon Lab logo" class="h-11 w-11 object-contain">
                <span class="text-xl font-extrabold tracking-tight">HORIZON <span class="text-sky-300">LAB</span></span>
            </a>
            <nav aria-label="Admin navigation" class="grid grid-cols-2 gap-1 sm:grid-cols-4 lg:grid-cols-1">
                @foreach ($adminNavigation as [$label, $routeName])
                    <a href="{{ route($routeName) }}" @class([
                        'rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-white/10',
                        'bg-blue-700 text-white' => request()->routeIs($routeName),
                        'text-slate-200' => ! request()->routeIs($routeName),
                    ])>{{ $label }}</a>
                @endforeach
            </nav>
        </aside>

        <div class="min-w-0">
            <header class="flex items-center justify-between border-b border-slate-200 bg-white px-5 py-3 sm:px-8">
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-slate-700 lg:hidden">Horizon Lab Admin</a>
                <span class="hidden text-sm text-slate-500 lg:block">Administration</span>
                <div class="flex items-center gap-3">
                    <span class="hidden text-sm font-medium text-slate-700 sm:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Log out</button>
                    </form>
                </div>
            </header>

            <main class="mx-auto w-full max-w-[1500px] p-4 sm:p-6 lg:p-8">
                @if (session('success'))
                    <div role="status" class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div role="alert" class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">Please correct the highlighted fields.</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>