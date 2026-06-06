<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $title ?? 'Bluebird CRM | Command Center Cockpit' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        },
                        success: '#10b981',
                        warning: '#f59e0b',
                        danger: '#ef4444',
                        info: '#06b6d4',
                    }
                }
            }
        }
    </script>
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #06070a;
            background-image: 
                radial-gradient(at 0% 0%, rgba(30, 27, 75, 0.4) 0, transparent 50%),
                radial-gradient(at 50% 0%, rgba(15, 23, 42, 0.4) 0, transparent 50%),
                radial-gradient(at 100% 0%, rgba(8, 47, 73, 0.4) 0, transparent 50%);
            background-attachment: fixed;
        }
        
        .glow-border {
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }
        .glow-border:hover {
            border-color: rgba(6, 182, 212, 0.3);
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.08);
        }
        
        .sidebar-item-active {
            background: linear-gradient(90deg, rgba(6, 182, 212, 0.15) 0%, rgba(59, 130, 246, 0.05) 100%);
            border-left: 3px solid #06b6d4;
            color: #22d3ee !important;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.1);
        }
        
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.3); }
        ::-webkit-scrollbar-thumb { background: rgba(30, 41, 59, 0.8); border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(51, 65, 85, 0.8); }
    </style>
    @stack('styles')
</head>
<body class="text-slate-200 min-h-screen flex flex-col md:flex-row antialiased">

    <!-- Mobile Header -->
    <div class="md:hidden w-full bg-[#08090d]/90 backdrop-blur-md text-slate-100 flex justify-between items-center px-6 py-4 border-b border-slate-800/80 z-50">
        <div class="flex items-center gap-2.5">
            <div class="p-1.5 bg-gradient-to-tr from-cyan-600 to-blue-600 rounded-lg shadow-[0_0_10px_rgba(6,182,212,0.3)]">
                <span class="material-symbols-outlined text-white text-[20px]">directions_bus</span>
            </div>
            <span class="text-base font-extrabold tracking-wide font-outfit text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400">Bluebird CRM</span>
        </div>
        <button id="hamburger-btn" class="p-1 text-slate-300 focus:outline-none rounded hover:bg-slate-800/60">
            <span class="material-symbols-outlined text-[24px]">menu</span>
        </button>
    </div>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-300 ease-in-out z-50 bg-[#08090d]/95 md:bg-[#08090d]/60 backdrop-blur-xl w-64 flex flex-col py-6 px-4 border-r border-slate-800/80 min-h-screen text-slate-300">

        <!-- Brand -->
        <div class="mb-8 flex items-center gap-3 px-2 hidden md:flex">
            <div class="p-2 bg-gradient-to-tr from-cyan-600 to-blue-600 rounded-xl shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                <span class="material-symbols-outlined text-white text-[24px]">directions_bus</span>
            </div>
            <div>
                <h1 class="text-base font-black text-slate-100 tracking-wide font-outfit leading-tight bg-clip-text bg-gradient-to-r from-slate-100 to-slate-300">Bluebird CRM</h1>
                <p class="text-[8px] uppercase tracking-widest text-cyan-400 font-extrabold font-outfit mt-0.5">Command Center v7.5</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-grow space-y-1 overflow-y-auto px-1 pr-2">
            @php $role = Auth::user()->role ?? 'sales'; @endphp

            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-200 hover:bg-slate-800/30 hover:text-cyan-400 font-medium text-sm {{ Request::routeIs('dashboard') ? 'sidebar-item-active' : '' }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span>Dashboard</span>
            </a>

            @if(in_array($role, ['director','gm','manager','sales']))
            <a href="{{ route('pipeline.index') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-200 hover:bg-slate-800/30 hover:text-cyan-400 font-medium text-sm {{ Request::routeIs('pipeline*','opportunities*') ? 'sidebar-item-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <span>Sales Pipeline</span>
            </a>
            @endif

            @if(in_array($role, ['director','gm','manager']))
            <a href="{{ route('approvals.index') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-200 hover:bg-slate-800/30 hover:text-cyan-400 font-medium text-sm {{ Request::routeIs('approvals*') ? 'sidebar-item-active' : '' }}">
                <span class="material-symbols-outlined">approval</span>
                <span>Approval Queue</span>
            </a>
            @endif

            @if(in_array($role, ['director','gm','manager','sales','finance']))
            <a href="{{ route('clients.index') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-200 hover:bg-slate-800/30 hover:text-cyan-400 font-medium text-sm {{ Request::routeIs('clients*') ? 'sidebar-item-active' : '' }}">
                <span class="material-symbols-outlined">business</span>
                <span>Clients</span>
            </a>
            @endif

            @if(in_array($role, ['director','gm','manager','operational']))
            <a href="{{ route('fleet.index') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-200 hover:bg-slate-800/30 hover:text-cyan-400 font-medium text-sm {{ Request::routeIs('fleet*','vehicles*') ? 'sidebar-item-active' : '' }}">
                <span class="material-symbols-outlined">local_shipping</span>
                <span>Fleet Armada</span>
            </a>
            @endif

            <a href="{{ route('bookings.index') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-200 hover:bg-slate-800/30 hover:text-cyan-400 font-medium text-sm {{ Request::routeIs('bookings*') ? 'sidebar-item-active' : '' }}">
                <span class="material-symbols-outlined">distance</span>
                <span>Dispatch / Booking</span>
            </a>

            @if(in_array($role, ['director','gm','manager','finance']))
            <a href="{{ route('subscriptions.index') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-200 hover:bg-slate-800/30 hover:text-cyan-400 font-medium text-sm {{ Request::routeIs('subscriptions*') ? 'sidebar-item-active' : '' }}">
                <span class="material-symbols-outlined">autorenew</span>
                <span>Subscriptions</span>
            </a>
            @endif

            @if(in_array($role, ['director','gm','manager','finance']))
            <a href="{{ route('vouchers.index') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-200 hover:bg-slate-800/30 hover:text-cyan-400 font-medium text-sm {{ Request::routeIs('vouchers*') ? 'sidebar-item-active' : '' }}">
                <span class="material-symbols-outlined">confirmation_number</span>
                <span>E-Voucher</span>
            </a>
            @endif

            @if(in_array($role, ['director','gm','manager','sales']))
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-200 hover:bg-slate-800/30 hover:text-cyan-400 font-medium text-sm {{ Request::routeIs('products*') ? 'sidebar-item-active' : '' }}">
                <span class="material-symbols-outlined">menu_book</span>
                <span>Price Book</span>
            </a>
            @endif

            @if(in_array($role, ['director','gm','finance']))
            <a href="{{ route('finance.index') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-200 hover:bg-slate-800/30 hover:text-cyan-400 font-medium text-sm {{ Request::routeIs('finance*','invoices*') ? 'sidebar-item-active' : '' }}">
                <span class="material-symbols-outlined">payments</span>
                <span>Finance & Billing</span>
            </a>
            @endif

            @if(in_array($role, ['director','gm','manager','sales']))
            <a href="{{ route('kpi.index') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-200 hover:bg-slate-800/30 hover:text-cyan-400 font-medium text-sm {{ Request::routeIs('kpi*') ? 'sidebar-item-active' : '' }}">
                <span class="material-symbols-outlined">leaderboard</span>
                <span>KPI & Target</span>
            </a>
            @endif

            @if(in_array($role, ['director','gm','manager','sales']))
            <a href="{{ route('activities.index') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-200 hover:bg-slate-800/30 hover:text-cyan-400 font-medium text-sm {{ Request::routeIs('activities*') ? 'sidebar-item-active' : '' }}">
                <span class="material-symbols-outlined">event_note</span>
                <span>Activity Log</span>
            </a>
            @endif

            @if(in_array($role, ['director','gm','manager']))
            <a href="{{ route('analytics.index') }}" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-200 hover:bg-slate-800/30 hover:text-cyan-400 font-medium text-sm {{ Request::routeIs('analytics*') ? 'sidebar-item-active' : '' }}">
                <span class="material-symbols-outlined">assessment</span>
                <span>Reports & Analytics</span>
            </a>
            @endif
        </nav>

        <!-- Sidebar Footer -->
        <div class="mt-auto pt-4 border-t border-slate-800/80">
            @php
                $roleIcons = ['director' => '👑', 'gm' => '🏢', 'manager' => '📊', 'sales' => '💼', 'operational' => '🚗', 'finance' => '💰'];
                $roleLabels = ['director' => 'Director HQ', 'gm' => 'GM HQ', 'manager' => 'Manager HQ', 'sales' => 'Sales Officer', 'operational' => 'Ops Head', 'finance' => 'Finance Admin'];
            @endphp
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-850 flex items-center justify-center text-lg flex-shrink-0 shadow-[0_2px_8px_rgba(0,0,0,0.5)]">
                    {{ $roleIcons[$role] ?? '👤' }}
                </div>
                <div class="overflow-hidden">
                    <p class="font-bold text-xs text-slate-100 truncate">{{ Auth::user()->name ?? 'Antigravity User' }}</p>
                    <p class="text-[9px] text-cyan-400 font-extrabold uppercase tracking-widest mt-0.5 truncate">{{ $roleLabels[$role] ?? strtoupper($role) }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 text-rose-400 hover:text-white hover:bg-rose-950/40 border border-transparent hover:border-rose-900/40 rounded-xl transition duration-200 font-semibold text-xs">
                    <span class="material-symbols-outlined text-[16px]">logout</span>
                    <span>LOGOUT COMMAND</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-grow min-h-screen flex flex-col">
        <!-- TOP APP BAR -->
        <header class="sticky top-0 h-16 flex items-center justify-between px-6 bg-[#06070a]/65 backdrop-blur-md border-b border-slate-800/80 z-40 shadow-lg">
            <nav class="flex items-center gap-1.5 text-xs font-semibold text-slate-400">
                <span class="text-slate-500">Bluebird CRM</span>
                <span class="material-symbols-outlined text-sm opacity-55">chevron_right</span>
                <span class="text-cyan-400 font-black uppercase tracking-wider font-outfit">@yield('header_title', 'Command Center')</span>
            </nav>
            <div class="flex items-center gap-3">
                @php
                    $roleColors = [
                        'director' => 'bg-purple-950/40 text-purple-400 border-purple-500/20 shadow-[0_0_10px_rgba(168,85,247,0.1)]',
                        'gm' => 'bg-blue-950/40 text-blue-400 border-blue-500/20 shadow-[0_0_10px_rgba(59,130,246,0.1)]',
                        'manager' => 'bg-emerald-950/40 text-emerald-400 border-emerald-500/20 shadow-[0_0_10px_rgba(16,185,129,0.1)]',
                        'sales' => 'bg-amber-950/40 text-amber-400 border-amber-500/20 shadow-[0_0_10px_rgba(245,158,11,0.1)]',
                        'operational' => 'bg-orange-950/40 text-orange-400 border-orange-500/20 shadow-[0_0_10px_rgba(249,115,22,0.1)]',
                        'finance' => 'bg-cyan-950/40 text-cyan-400 border-cyan-500/20 shadow-[0_0_10px_rgba(6,182,212,0.1)]'
                    ];
                    $roleColorClass = $roleColors[$role] ?? 'bg-slate-900/60 text-slate-400 border-slate-700/60';
                @endphp
                <span class="px-2.5 py-1 rounded-md text-[9px] border font-black uppercase tracking-widest {{ $roleColorClass }}">
                    {{ strtoupper($role) }} COCKPIT
                </span>
                <span class="flex items-center gap-1.5 bg-emerald-950/30 text-emerald-400 px-2.5 py-1 rounded-md text-[9px] font-black uppercase border border-emerald-500/20 tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                    Live Preview
                </span>
            </div>
        </header>

        <!-- FLASH MESSAGES -->
        <div class="px-6 pt-4">
            @if (session('success'))
                <div class="mb-2 flex items-center gap-2.5 bg-emerald-950/30 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl text-sm font-semibold shadow-[0_0_15px_rgba(16,185,129,0.05)]">
                    <span class="material-symbols-outlined text-emerald-400 text-[18px]">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-2 flex items-center gap-2.5 bg-rose-950/30 border border-rose-500/20 text-rose-400 px-4 py-3 rounded-xl text-sm font-semibold shadow-[0_0_15px_rgba(244,63,94,0.05)]">
                    <span class="material-symbols-outlined text-rose-400 text-[18px]">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <!-- PAGE CONTENT -->
        <div class="p-6 flex-grow">
            @yield('content')
        </div>
    </main>

    <!-- Mobile Sidebar JS -->
    <script>
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const sidebar = document.getElementById('sidebar');
        if (hamburgerBtn) {
            let open = false;
            hamburgerBtn.addEventListener('click', () => {
                open = !open;
                sidebar.classList.toggle('-translate-x-full', !open);
            });
            document.addEventListener('click', (e) => {
                if (open && !sidebar.contains(e.target) && !hamburgerBtn.contains(e.target)) {
                    open = false;
                    sidebar.classList.add('-translate-x-full');
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
