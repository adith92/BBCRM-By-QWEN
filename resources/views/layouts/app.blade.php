<!DOCTYPE html>
<html class="dark" lang="id" x-data x-init="$store.theme.init()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Golden Bird CRM | Command Center' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen overflow-x-hidden bg-slate-50 text-slate-900 antialiased dark:bg-command-950 dark:text-slate-100">
@php
    $role = Auth::user()->role ?? '';
    $roleLabels = [
        'director' => 'Director HQ',
        'gm' => 'GM HQ',
        'manager' => 'Manager HQ',
        'sales' => 'Sales Officer',
        'operational' => 'Operations',
        'finance' => 'Finance',
    ];
    $roleIcons = [
        'director' => 'workspace_premium',
        'gm' => 'admin_panel_settings',
        'manager' => 'supervisor_account',
        'sales' => 'badge',
        'operational' => 'local_shipping',
        'finance' => 'payments',
    ];
    $navItems = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'active' => 'dashboard', 'icon' => 'dashboard', 'roles' => null],
        ['label' => 'Sales Pipeline', 'route' => 'pipeline.index', 'active' => 'pipeline*|opportunities*', 'icon' => 'conversion_path', 'roles' => ['director','gm','manager','sales']],
        ['label' => 'Approval Queue', 'route' => 'approvals.index', 'active' => 'approvals*', 'icon' => 'approval', 'roles' => ['director','gm','manager']],
        ['label' => 'Clients', 'route' => 'clients.index', 'active' => 'clients*', 'icon' => 'business_center', 'roles' => ['director','gm','manager','sales','finance']],
        ['label' => 'Fleet Armada', 'route' => 'fleet.index', 'active' => 'fleet*|vehicles*', 'icon' => 'directions_car', 'roles' => ['director','gm','manager','operational']],
        ['label' => 'Dispatch / Booking', 'route' => 'bookings.index', 'active' => 'bookings*', 'icon' => 'route', 'roles' => null],
        ['label' => 'Subscriptions', 'route' => 'subscriptions.index', 'active' => 'subscriptions*', 'icon' => 'autorenew', 'roles' => ['director','gm','manager','finance']],
        ['label' => 'E-Voucher', 'route' => 'vouchers.index', 'active' => 'vouchers*', 'icon' => 'confirmation_number', 'roles' => ['director','gm','manager','finance']],
        ['label' => 'Price Book', 'route' => 'products.index', 'active' => 'products*', 'icon' => 'menu_book', 'roles' => ['director','gm','manager','sales']],
        ['label' => 'Finance & Billing', 'route' => 'finance.index', 'active' => 'finance*|invoices*', 'icon' => 'account_balance_wallet', 'roles' => ['director','gm','finance']],
        ['label' => 'KPI & Target', 'route' => 'kpi.index', 'active' => 'kpi*', 'icon' => 'leaderboard', 'roles' => ['director','gm','manager','sales']],
        ['label' => 'Activity Log', 'route' => 'activities.index', 'active' => 'activities*', 'icon' => 'event_note', 'roles' => ['director','gm','manager','sales']],
        ['label' => 'Reports & Analytics', 'route' => 'analytics.index', 'active' => 'analytics*', 'icon' => 'monitoring', 'roles' => ['director','gm','manager']],
    ];
    $routeIsAny = function (string $patterns): bool {
        return collect(explode('|', $patterns))->contains(fn ($pattern) => request()->routeIs($pattern));
    };
@endphp

<div x-data="{ sidebarOpen: false }" class="min-h-screen lg:flex">
    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity
        class="fixed inset-0 z-40 bg-command-950/70 backdrop-blur-sm lg:hidden"
        @click="sidebarOpen = false"
        aria-hidden="true"
    ></div>

    <aside
        id="app-sidebar"
        x-cloak
        :style="sidebarOpen ? 'translate: 0 0;' : 'translate: -100% 0;'"
        class="fixed inset-y-0 left-0 z-50 flex w-[280px] flex-col border-r border-white/10 bg-command-950 text-white shadow-command transition duration-200 lg:sticky lg:top-0 lg:h-screen"
    >
        <div class="flex h-[72px] items-center gap-3 border-b border-white/10 px-5 py-5">
            <div class="grid h-11 w-11 place-items-center rounded-lg bg-gradient-to-br from-brand-500 to-cyan-400 text-white shadow-glow">
                <span class="material-symbols-outlined text-[24px]">directions_bus</span>
            </div>
            <div class="min-w-0">
                <p class="truncate text-sm font-black">Golden Bird CRM</p>
                <p class="truncate text-[10px] font-bold uppercase tracking-[0.18em] text-cyan-200/80">Command Center</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
            @foreach($navItems as $item)
                @continue($item['roles'] && !in_array($role, $item['roles'], true))
                @php $isActive = $routeIsAny($item['active']); @endphp
                <a
                    href="{{ route($item['route']) }}"
                    class="group flex min-h-11 items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold transition {{ $isActive ? 'bg-white text-command-950 shadow-glow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >
                    <span class="material-symbols-outlined text-[20px] {{ $isActive ? 'text-brand-700' : 'text-cyan-200/80 group-hover:text-cyan-100' }}">{{ $item['icon'] }}</span>
                    <span class="truncate">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="border-t border-white/10 p-4">
            <div class="mb-4 flex items-center gap-3 rounded-lg bg-white/[0.08] p-3 ring-1 ring-white/10">
                <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-white/10 text-cyan-200">
                    <span class="material-symbols-outlined text-[22px]">{{ $roleIcons[$role] ?? 'person' }}</span>
                </div>
                <div class="min-w-0">
                    <p class="truncate text-xs font-bold">{{ Auth::user()->name }}</p>
                    <p class="truncate text-[10px] font-bold uppercase tracking-wide text-slate-400">{{ $roleLabels[$role] ?? strtoupper($role) }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex h-10 w-full items-center justify-center gap-2 rounded-lg text-sm font-bold text-red-200 transition hover:bg-red-500/15 hover:text-white">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="min-w-0 flex-1">
        <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/[0.85] backdrop-blur-xl dark:border-white/10 dark:bg-command-950/[0.82]">
            <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6">
                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" class="grid h-10 w-10 place-items-center rounded-lg text-slate-700 hover:bg-slate-100 lg:hidden dark:text-slate-200 dark:hover:bg-white/10" @click="sidebarOpen = true" aria-label="Open sidebar">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <div class="min-w-0">
                        <p class="truncate text-xs font-black uppercase tracking-[0.16em] text-brand-600 dark:text-cyan-300">Bluebird CRM Command Center</p>
                        <p class="truncate text-sm font-bold text-slate-800 dark:text-slate-100">@yield('header_title', 'Dashboard')</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <x-ui.badge variant="success" class="hidden sm:inline-flex">
                        <span class="mr-1 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Live
                    </x-ui.badge>
                    <x-ui.badge variant="primary" class="hidden md:inline-flex">{{ strtoupper($role) }} Gateway</x-ui.badge>
                    <button type="button" class="grid h-10 w-10 place-items-center rounded-lg border border-slate-200 bg-white text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:bg-white/10 dark:text-slate-100 dark:hover:bg-white/15" @click="$store.theme.toggle()" aria-label="Toggle dark mode">
                        <span class="material-symbols-outlined text-[20px]" x-show="!$store.theme.dark">dark_mode</span>
                        <span class="material-symbols-outlined text-[20px]" x-show="$store.theme.dark">light_mode</span>
                    </button>
                </div>
            </div>
        </header>

        <main class="min-h-[calc(100vh-4rem)] bg-slate-50 px-4 py-5 dark:bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.18),_transparent_32rem),#070918] sm:px-6">
            <div class="mx-auto max-w-[1500px] space-y-4">
                @if (session('success'))
                    <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
                @endif
                @if (session('error'))
                    <x-ui.alert variant="danger">{{ session('error') }}</x-ui.alert>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
