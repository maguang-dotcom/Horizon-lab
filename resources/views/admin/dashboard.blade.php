@php
    $unreadEngineerNotifications = $notifications->where('category', 'engineer')->count();
    $unreadRequestNotifications = $notifications->where('category', 'service-request')->count();
    $navItems = [
        ['label' => 'Dashboard', 'route' => route('admin.dashboard'), 'active' => true],
        ['label' => 'Engineers', 'route' => route('admin.engineers.index'), 'count' => $unreadEngineerNotifications, 'notification_category' => 'engineer'],
        ['label' => 'Service Requests', 'route' => route('admin.service-requests.index'), 'count' => $unreadRequestNotifications, 'notification_category' => 'service-request'],
        ['label' => 'Facilities', 'route' => route('admin.facilities.index')],
        ['label' => 'Assignments', 'route' => route('admin.assignments.index')],
        ['label' => 'Reports', 'route' => route('admin.reports.index')],
        ['label' => 'Users', 'route' => route('admin.users.index')],
        ['label' => 'Settings', 'route' => route('admin.settings.index')],
    ];

    $statusColors = [
        'pending' => 'bg-amber-100 text-amber-700 border border-amber-200',
        'matching' => 'bg-sky-100 text-sky-700 border border-sky-200',
        'assigned' => 'bg-violet-100 text-violet-700 border border-violet-200',
        'in_progress' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
        'resolved' => 'bg-green-100 text-green-700 border border-green-200',
        'critical' => 'bg-red-100 text-red-700 border border-red-200',
        'high' => 'bg-orange-100 text-orange-700 border border-orange-200',
        'normal' => 'bg-slate-200 text-slate-700 border border-slate-300',
    ];

    $priorityColors = [
        'critical' => 'bg-red-100 text-red-700',
        'high' => 'bg-orange-100 text-orange-700',
        'normal' => 'bg-slate-200 text-slate-700',
        'low' => 'bg-emerald-100 text-emerald-700',
    ];

    $initials = function ($name) {
        $parts = preg_split('/\s+/', trim((string) $name));
        $initials = array_map(fn ($part) => strtoupper(substr($part, 0, 1)), array_slice($parts, 0, 2));

        return implode('', $initials ?: ['A']);
    };
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover { background: rgba(255,255,255,0.08); }
    </style>
</head>
<body data-admin-id="{{ auth()->id() }}" data-dashboard-role="admin" data-dashboard-user-id="{{ auth()->id() }}" class="min-h-screen bg-[#eef3fb] text-slate-800">
    <div class="flex min-h-screen flex-col lg:flex-row">
        <aside class="w-full shrink-0 bg-[#0b1d4d] px-4 py-4 text-white lg:w-[280px] lg:px-5 lg:py-5">
            <div class="flex items-center gap-3 pb-4 pl-2 pt-1 lg:pb-6 lg:pt-2">
                <img src="{{ asset('images/logo.png') }}" alt="Horizon Lab logo" class="h-12 w-12 object-contain">
                <div class="leading-none">
                    <div class="text-[32px] font-black tracking-tight">HORIZON</div>
                    <div class="mt-1 text-[20px] font-semibold tracking-[0.32em] text-sky-300">LAB</div>
                </div>
            </div>

            <nav class="grid grid-cols-2 gap-2 sm:grid-cols-4 lg:mt-6 lg:grid-cols-1">
                @foreach ($navItems as $item)
                    <a href="{{ $item['route'] ?? '#' }}" class="sidebar-link flex min-w-0 items-center justify-between rounded-xl px-3 py-2.5 {{ ($item['active'] ?? false) ? 'bg-[#1d4ed8] text-white shadow-sm' : 'text-slate-200 hover:text-white' }} lg:px-4 lg:py-3">
                        <span class="flex items-center gap-3">
                            <span class="inline-flex h-5 w-5 items-center justify-center">
                                @if (($item['label'] ?? '') === 'Dashboard')
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor"><path d="M12 3.5 3.5 12H6v8h4v-5h4v5h4v-8h2.5L12 3.5Z"/></svg>
                                @elseif (($item['label'] ?? '') === 'Engineers')
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 19v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1"/><circle cx="10" cy="7" r="3.5"/><path d="M20 19v-1a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                @elseif (($item['label'] ?? '') === 'Service Requests')
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 3h8l4 4v13a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><path d="M14 3v4h4"/><path d="M8 11h8M8 15h8"/></svg>
                                @elseif (($item['label'] ?? '') === 'Facilities')
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 21V7.5L12 3l9 4.5V21"/><path d="M9 21v-7h6v7M6 9h.01M18 9h.01"/></svg>
                                @elseif (($item['label'] ?? '') === 'Assignments')
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 3h6"/><path d="M7 7h10"/><path d="M6 11h12a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2Z"/></svg>
                                @elseif (($item['label'] ?? '') === 'Reports')
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 4.5h9l4 4V19a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6.5a2 2 0 0 1 2-2Z"/><path d="M15 4.5v4h4M8 12h7M8 16h7"/></svg>
                                @elseif (($item['label'] ?? '') === 'Users')
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 19v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1"/><circle cx="10" cy="7" r="3.5"/><path d="M20 19v-1a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                @else
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/><circle cx="12" cy="12" r="3.5"/></svg>
                                @endif
                            </span>
                            <span class="truncate text-sm font-medium lg:text-[15px]">{{ $item['label'] }}</span>
                        </span>
                        @if (isset($item['notification_category']) || !empty($item['count']))
                            <span data-sidebar-notification="{{ $item['notification_category'] ?? '' }}" class="{{ empty($item['count']) ? 'hidden' : '' }} inline-flex min-w-[22px] items-center justify-center rounded-full bg-[#f87171] px-1.5 py-0.5 text-xs font-bold text-white">{{ $item['count'] ?? '' }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="mt-8 hidden rounded-xl border border-white/10 bg-[#112a67] p-4 lg:block">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-sky-200">
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2v6M12 16v6M4.93 4.93l4.24 4.24M14.83 14.83l4.24 4.24M2 12h6M16 12h6M4.93 19.07l4.24-4.24M14.83 9.17l4.24-4.24"/></svg>
                    </div>
                    <div>
                        <p class="text-[15px] font-semibold">Better Equipment.</p>
                        <p class="text-[15px] font-semibold">Better Healthcare.</p>
                    </div>
                </div>
                <p class="mt-4 text-xs text-slate-300">Horizon Lab</p>
                <p class="text-xs text-slate-400">Biomedical Engineering</p>
            </div>
        </aside>

        <main class="min-w-0 flex-1">
            <header class="flex flex-col gap-3 border-b border-slate-200 bg-white/80 px-4 py-3 backdrop-blur-sm sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:py-4">
                <div class="flex w-full max-w-xl items-center gap-3 rounded-xl border border-slate-200 bg-slate-100 px-3 py-2">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="6"/><path d="m16 16 5 5"/></svg>
                    <input type="search" data-dashboard-search placeholder="Search engineers, requests, hospitals..." class="w-full bg-transparent text-sm text-slate-600 placeholder:text-slate-400 focus:outline-none" aria-label="Search dashboard records">
                </div>

                <div class="flex items-center justify-between gap-3 sm:ml-6 sm:justify-end sm:gap-4">
                    <div id="admin-notifications" data-dashboard-notifications class="relative">
                        <button id="notification-toggle" data-notification-toggle type="button" class="relative rounded-full border border-slate-200 p-2 text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Notifications" aria-expanded="false" aria-controls="notification-panel">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 18h6"/><path d="M10.3 21h3.4"/><path d="M18 8a6 6 0 1 0-12 0c0 6.5-2.5 7.5-2.5 7.5h17S18 14.5 18 8Z"/></svg>
                            <span id="notification-count" data-notification-count class="{{ $notifications->isEmpty() ? 'hidden' : '' }} absolute -right-1 -top-1 min-w-5 rounded-full bg-red-600 px-1 text-center text-[11px] font-bold leading-5 text-white">{{ $notifications->count() }}</span>
                        </button>
                        <div id="notification-panel" data-notification-panel hidden class="absolute right-0 top-full z-50 mt-2 w-80 max-w-[calc(100vw-2rem)] overflow-hidden rounded-xl border border-slate-200 bg-white text-slate-800 shadow-xl">
                            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                                <div>
                                    <h2 class="text-sm font-bold">Notifications</h2>
                                    <p id="notification-summary" data-notification-summary class="text-xs text-slate-500">{{ $notifications->count() }} unread</p>
                                </div>
                                <button id="dismiss-all-notifications" data-notification-mark-all type="button" class="text-xs font-semibold text-blue-700 hover:text-blue-900">Mark all read</button>
                            </div>
                            <div id="notification-list" data-notification-list class="max-h-80 overflow-y-auto">
                                @foreach ($notifications as $notification)
                                    <div data-notification-id="{{ $notification['id'] }}" data-notification-category="{{ $notification['category'] }}" class="flex items-start gap-3 border-b border-slate-100 px-4 py-3 last:border-b-0 hover:bg-slate-50">
                                        <a data-notification-link href="{{ $notification['url'] }}" class="min-w-0 flex-1">
                                            <span class="block text-sm font-semibold text-slate-800">{{ $notification['title'] }}</span>
                                            <span class="mt-0.5 block truncate text-xs text-slate-600">{{ $notification['message'] }}</span>
                                            <span class="mt-1 block text-[11px] text-slate-400">{{ $notification['time'] }}</span>
                                        </a>
                                        <button type="button" data-notification-dismiss aria-label="Dismiss notification" class="rounded p-1 text-slate-400 hover:bg-slate-200 hover:text-slate-700">&times;</button>
                                    </div>
                                @endforeach
                                <p id="notifications-empty" data-notification-empty class="{{ $notifications->isEmpty() ? '' : 'hidden' }} px-4 py-6 text-center text-sm text-slate-500">You're all caught up.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 rounded-full border border-slate-200 bg-slate-50 px-2 py-1.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#dbeafe] text-sm font-bold text-blue-700">
                            {{ $initials(auth()->user()->name) }}
                        </div>
                        <div class="hidden sm:block">
                            <div class="text-[12px] font-semibold text-slate-700">{{ auth()->user()->name }}</div>
                            <div class="text-[11px] text-slate-500">System Administrator</div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="min-w-0 p-4 sm:p-6">
                @if (session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-[42px]">Admin Dashboard</h1>
                        <p class="mt-2 text-base text-slate-500 sm:text-lg">Manage engineers, approvals and service requests</p>
                    </div>
                    <div class="hidden items-center gap-2 text-sm text-slate-500 md:flex">
                        <span class="inline-flex h-2.5 w-2.5 rounded-full bg-sky-500"></span>
                        <span>Dashboard</span>
                    </div>
                </div>

                <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-[#d7e7f9] bg-[#e8f7f3] p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#d7f3ee] text-[#1f8b7f]">
                                <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 19v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1"/><circle cx="10" cy="7" r="3.5"/><path d="M20 19v-1a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <a href="{{ route('admin.engineers.index') }}" class="text-sm font-medium text-slate-500 hover:text-blue-700">View all →</a>
                        </div>
                        <p class="mt-4 text-sm font-medium text-slate-600">Pending Engineer Approvals</p>
                        <div class="mt-3 text-4xl font-bold text-slate-900">{{ $stats['pendingEngineers'] }}</div>
                    </div>

                    <div class="rounded-2xl border border-[#d7e7f9] bg-[#dcfce7] p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#d9f9e8] text-[#1d9a63]">
                                <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 19v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1"/><circle cx="10" cy="7" r="3.5"/><path d="M20 19v-1a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <a href="{{ route('admin.engineers.index') }}" class="text-sm font-medium text-slate-500 hover:text-blue-700">View all →</a>
                        </div>
                        <p class="mt-4 text-sm font-medium text-slate-600">Approved Engineers</p>
                        <div class="mt-3 text-4xl font-bold text-slate-900">{{ $stats['approvedEngineers'] }}</div>
                    </div>

                    <div class="rounded-2xl border border-[#d7e7f9] bg-[#fdf2f8] p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#fce7f3] text-[#c63d7d]">
                                <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 3h8l4 4v13a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><path d="M14 3v4h4"/><path d="M8 11h8M8 15h8"/></svg>
                            </div>
                            <a href="{{ route('admin.service-requests.index') }}" class="text-sm font-medium text-slate-500 hover:text-blue-700">View all →</a>
                        </div>
                        <p class="mt-4 text-sm font-medium text-slate-600">Open Service Requests</p>
                        <div class="mt-3 text-4xl font-bold text-slate-900">{{ $stats['openRequests'] }}</div>
                    </div>

                    <div class="rounded-2xl border border-[#d7e7f9] bg-[#f3e8ff] p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#eedcff] text-[#7b4de0]">
                                <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 21V7.5L12 3l9 4.5V21"/><path d="M9 21v-7h6v7M6 9h.01M18 9h.01"/></svg>
                            </div>
                            <a href="{{ route('admin.facilities.index') }}" class="text-sm font-medium text-slate-500 hover:text-blue-700">View all →</a>
                        </div>
                        <p class="mt-4 text-sm font-medium text-slate-600">Total Hospitals/Facilities</p>
                        <div class="mt-3 text-4xl font-bold text-slate-900">{{ $stats['facilityCount'] }}</div>
                    </div>
                </section>

                <section class="mt-8 grid min-w-0 gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(320px,1fr)]">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-slate-900">Pending Engineer Approvals</h2>
                            <a href="{{ route('admin.engineers.index') }}" class="text-sm font-medium text-sky-700">View all pending →</a>
                        </div>

                        @if ($pendingEngineers->isEmpty())
                            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">No pending engineer approvals.</div>
                        @else
                            <div class="overflow-x-auto rounded-xl border border-slate-200">
                                <table class="min-w-[760px] divide-y divide-slate-200 text-left text-sm">
                                    <thead class="bg-slate-50 text-slate-600">
                                        <tr>
                                            <th class="px-4 py-3 font-semibold">#</th>
                                            <th class="px-4 py-3 font-semibold">Name</th>
                                            <th class="px-4 py-3 font-semibold">Email</th>
                                            <th class="px-4 py-3 font-semibold">Skills</th>
                                            <th class="px-4 py-3 font-semibold">Applied On</th>
                                            <th class="px-4 py-3 font-semibold text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody data-dashboard-search-region class="divide-y divide-slate-100 bg-white">
                                        @foreach ($pendingEngineers as $index => $profile)
                                            <tr data-dashboard-searchable>
                                                <td class="px-4 py-4 text-slate-600">{{ $index + 1 }}</td>
                                                <td class="px-4 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-sky-100 text-xs font-bold text-sky-700">
                                                            {{ $initials($profile->user->name ?? 'Engineer') }}
                                                        </div>
                                                        <span class="font-medium text-slate-800">{{ $profile->user->name ?? 'New Engineer' }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 text-slate-600">{{ $profile->user->email ?? 'n/a' }}</td>
                                                <td class="px-4 py-4">
                                                    @php $skill = $profile->specialization ?? 'General'; @endphp
                                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">{{ $skill }}</span>
                                                </td>
                                                <td class="px-4 py-4 text-slate-600">{{ $profile->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                                                <td class="px-4 py-4">
                                                    <div class="flex justify-end gap-2">
                                                        <form method="POST" action="{{ route('admin.engineers.approve', $profile->user_id) }}">
                                                            @csrf
                                                            <button type="submit" class="rounded-lg bg-emerald-500 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-600">Approve</button>
                                                        </form>
                                                                                                                    <form method="POST" action="{{ route('admin.engineers.reject', $profile->user_id) }}" onsubmit="return confirm('Reject this engineer? Their account will be deleted.')">
                                                                                                                        @csrf
                                                                                                                        @method('DELETE')
                                                                                                                        <button type="submit" class="rounded-lg bg-red-500 px-3 py-2 text-xs font-semibold text-white hover:bg-red-600">Reject</button>
                                                                                                                    </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr data-dashboard-search-empty hidden><td colspan="6" class="px-4 py-8 text-center text-slate-500">No matching engineers.</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <h2 class="mb-4 text-2xl font-bold text-slate-900">Assign Engineer to Service Request</h2>

                            @if ($openRequests->isEmpty())
                                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">No open requests to assign.</div>
                            @else
                                <form method="POST" action="{{ $openRequests->first()['assignment_url'] }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Service Request</label>
                                        <select name="request_id" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-200" onchange="this.form.action=this.options[this.selectedIndex].dataset.url;">
                                            <option value="">Select a request</option>
                                            @foreach ($openRequests as $request)
                                                <option value="{{ $request['id'] }}" data-url="{{ $request['assignment_url'] }}">{{ $request['facility'] }} - {{ $request['equipment'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-slate-700">Engineer</label>
                                        <select name="engineer_profile_id" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-200">
                                            <option value="">Select an approved engineer</option>
                                            @foreach ($approvedEngineers as $engineer)
                                                <option value="{{ $engineer->id }}">{{ $engineer->user->name ?? 'Engineer' }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button type="submit" @disabled($approvedEngineers->isEmpty()) class="w-full rounded-xl bg-[#1d4ed8] px-4 py-3 text-base font-semibold text-white shadow-sm hover:bg-[#1e40af] disabled:cursor-not-allowed disabled:opacity-50">Assign Engineer</button>
                                </form>
                            @endif
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <h2 class="mb-4 text-2xl font-bold text-slate-900">Recent Activity</h2>
                            <div class="space-y-4">
                                @forelse ($recentActivities as $activity)
                                    <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                                        <div class="mt-1 flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                            @if (str_contains(strtolower($activity['message']), 'assign'))
                                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11V5h6v6M9 11H5v8h14v-8h-4"/></svg>
                                            @elseif (str_contains(strtolower($activity['message']), 'updated'))
                                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v5l3 2"/><circle cx="12" cy="12" r="8"/></svg>
                                            @else
                                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7h8M8 12h8M8 17h5"/><rect x="5" y="3" width="14" height="18" rx="2"/></svg>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-slate-800">{{ $activity['message'] }}</p>
                                            <p class="mt-1 text-xs text-slate-500">{{ $activity['time'] }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">No recent activity yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-slate-900">Recent Service Requests</h2>
                        <a href="{{ route('admin.service-requests.index') }}" class="text-sm font-medium text-sky-700">View all requests →</a>
                    </div>

                    @if ($openRequests->isEmpty())
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">No service requests available.</div>
                    @else
                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="min-w-[980px] divide-y divide-slate-200 text-left text-sm">
                                <thead class="bg-slate-50 text-slate-600">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold">#</th>
                                        <th class="px-4 py-3 font-semibold">Hospital / Facility</th>
                                        <th class="px-4 py-3 font-semibold">Equipment</th>
                                        <th class="px-4 py-3 font-semibold">Issue</th>
                                        <th class="px-4 py-3 font-semibold">Priority</th>
                                        <th class="px-4 py-3 font-semibold">Assigned Engineer</th>
                                        <th class="px-4 py-3 font-semibold">Status</th>
                                        <th class="px-4 py-3 font-semibold text-right">Action</th>
                                    </tr>
                                </thead>
                                    <tbody data-dashboard-search-region class="divide-y divide-slate-200 bg-white">
                                    @foreach ($openRequests as $index => $request)
                                        <tr data-dashboard-searchable>
                                            <td class="px-4 py-4 text-slate-600">{{ $request['reference'] }}</td>
                                            <td class="px-4 py-4 font-medium text-slate-800">{{ $request['facility'] }}</td>
                                            <td class="px-4 py-4 text-slate-600">{{ $request['equipment'] }}</td>
                                            <td class="px-4 py-4 text-slate-600">{{ $request['issue'] }}</td>
                                            <td class="px-4 py-4">
                                                @php $priority = strtolower((string) $request['priority']); @endphp
                                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $priorityColors[$priority] ?? $priorityColors['normal'] }}">{{ ucfirst($priority) }}</span>
                                            </td>
                                            <td class="px-4 py-4">
                                                @if ($request['engineer'])
                                                    <div class="flex items-center gap-2">
                                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-sky-100 text-[11px] font-bold text-sky-700">{{ $initials($request['engineer']) }}</div>
                                                        <span class="text-slate-700">{{ $request['engineer'] }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-slate-400">Unassigned</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4">
                                                @php $status = $request['engineer'] && in_array($request['status'], ['pending', 'matching'], true) ? 'assigned' : strtolower((string) $request['status']); @endphp
                                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusColors[$status] ?? $statusColors['pending'] }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('admin.service-requests.index') }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">View</a>
                                                    @if (! $request['engineer'])
                                                        <a href="{{ route('admin.service-requests.index') }}" class="rounded-lg bg-[#1d4ed8] px-3 py-2 text-xs font-semibold text-white hover:bg-[#1e40af]">Assign</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr data-dashboard-search-empty hidden><td colspan="8" class="px-4 py-8 text-center text-slate-500">No matching service requests.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>
            </div>
        </main>
    </div>
    <script src="{{ asset('js/dashboard-tools.js') }}" defer></script>
</body>
</html>
