<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $title ?? 'BlueERP | Enterprise Fleet Management' }}</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-bright": "#f8f9ff",
                        "on-surface-variant": "#434652",
                        "surface-container-low": "#eff4ff",
                        "on-primary": "#ffffff",
                        "primary-container": "#1e4fa8",
                        "on-error": "#ffffff",
                        "secondary-fixed-dim": "#a4c9ff",
                        "on-secondary-fixed": "#001c39",
                        "on-primary-container": "#b2c7ff",
                        "inverse-primary": "#b0c6ff",
                        "tertiary-container": "#24548d",
                        "secondary-fixed": "#d4e3ff",
                        "primary": "#003887",
                        "surface-container-highest": "#d3e4fe",
                        "on-tertiary-fixed-variant": "#124780",
                        "on-primary-fixed-variant": "#04429b",
                        "tertiary": "#003c73",
                        "surface-variant": "#d3e4fe",
                        "tertiary-fixed": "#d4e3ff",
                        "background": "#f8f9ff",
                        "surface-container": "#e5eeff",
                        "surface": "#f8f9ff",
                        "tertiary-fixed-dim": "#a5c8ff",
                        "on-error-container": "#93000a",
                        "error-container": "#ffdad6",
                        "surface-dim": "#cbdbf5",
                        "surface-container-lowest": "#ffffff",
                        "outline-variant": "#c3c6d4",
                        "inverse-on-surface": "#eaf1ff",
                        "error": "#ba1a1a",
                        "surface-container-high": "#dce9ff",
                        "secondary": "#1960a6",
                        "on-surface": "#0b1c30",
                        "on-tertiary": "#ffffff",
                        "on-secondary": "#ffffff",
                        "on-background": "#0b1c30",
                        "on-secondary-fixed-variant": "#004883",
                        "inverse-surface": "#213145",
                        "secondary-container": "#7ab3ff",
                        "on-tertiary-container": "#a7c9ff",
                        "surface-tint": "#2d5bb4",
                        "on-primary-fixed": "#001945",
                        "outline": "#737783",
                        "primary-fixed-dim": "#b0c6ff",
                        "on-secondary-container": "#00447e",
                        "primary-fixed": "#d9e2ff",
                        "on-tertiary-fixed": "#001c3a"
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .sidebar-active-glow {
            box-shadow: 0 0 15px rgba(164, 201, 255, 0.1);
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #c3c6d4; border-radius: 10px; }
    </style>
    @livewireStyles
</head>
<body class="bg-background text-on-surface min-h-screen flex flex-col md:flex-row">

    <!-- Mobile Header -->
    <div class="md:hidden w-full bg-primary text-on-primary flex justify-between items-center px-6 py-4 shadow-md z-50">
        <div class="flex items-center gap-2">
            <div class="p-1 bg-secondary rounded-lg">
                <span class="material-symbols-outlined text-on-primary text-[20px]">local_shipping</span>
            </div>
            <span class="text-lg font-bold tracking-wider">BlueERP</span>
        </div>
        <button id="hamburger-btn" class="p-1 text-on-primary focus:outline-none rounded">
            <span class="material-symbols-outlined text-[24px]">menu</span>
        </button>
    </div>

    <!-- SIDEBAR (Fixed Left on desktop, slide-out on mobile) -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out z-50 bg-primary w-64 flex flex-col py-6 px-4 shadow-xl min-h-screen text-on-primary">
        <!-- Brand Header -->
        <div class="mb-8 flex items-center gap-3 px-2 hidden md:flex">
            <div class="p-1.5 bg-secondary rounded-lg">
                <span class="material-symbols-outlined text-on-primary text-[24px]">local_shipping</span>
            </div>
            <div>
                <h1 class="text-lg font-bold text-on-primary leading-tight">BlueERP</h1>
                <p class="text-[9px] uppercase tracking-widest text-on-primary-container opacity-85 font-semibold">Fleet Management</p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-grow space-y-1 overflow-y-auto px-1">
            <!-- GM Dashboard Menu -->
            @can('crm.view')
                <a href="/dashboard/gm" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-150 {{ Request::is('dashboard/gm') ? 'bg-secondary text-on-secondary sidebar-active-glow' : 'text-on-primary-container hover:bg-primary-container hover:text-on-primary-container' }}">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="text-sm font-semibold">CRM Dashboard</span>
                </a>
            @endcan

            <!-- Fleet Index Menu -->
            @can('fleet.view')
                <a href="/fleet" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-150 {{ Request::is('fleet*') ? 'bg-secondary text-on-secondary sidebar-active-glow' : 'text-on-primary-container hover:bg-primary-container hover:text-on-primary-container' }}">
                    <span class="material-symbols-outlined">local_shipping</span>
                    <span class="text-sm font-semibold">Fleet Armada</span>
                </a>
            @endcan

            <!-- Booking Menu -->
            @can('booking.view')
                <a href="/bookings" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-150 {{ Request::is('bookings*') ? 'bg-secondary text-on-secondary sidebar-active-glow' : 'text-on-primary-container hover:bg-primary-container hover:text-on-primary-container' }}">
                    <span class="material-symbols-outlined">distance</span>
                    <span class="text-sm font-semibold">Dispatch (Booking)</span>
                </a>
            @endcan

            <!-- Finance Menu -->
            @can('finance.view')
                <a href="/invoices" class="flex items-center gap-3 py-2.5 px-4 rounded-xl transition duration-150 {{ Request::is('invoices*') ? 'bg-secondary text-on-secondary sidebar-active-glow' : 'text-on-primary-container hover:bg-primary-container hover:text-on-primary-container' }}">
                    <span class="material-symbols-outlined">payments</span>
                    <span class="text-sm font-semibold">Finance & Billing</span>
                </a>
            @endcan
        </nav>

        <!-- Sidebar Footer / Logout -->
        <div class="mt-auto pt-4 border-t border-primary-container">
            <div class="flex items-center gap-3 mb-4 px-2">
                @php
                    $roleImgMap = [
                        'gm' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAgfAXBZyRiqkiDnziL-EN4vadP0lVqf-xCcO1r281JlFL3Ylo-DPwhciZggofTEKMQsTNTlcyJBiM9Mxkcl3nBOS5vmyGxZi63HwxmBrETgbXocbqxuxP2AwPBgdePQuCPYPOUcUtLy8jP07NGD79VJa5IWR4ro7C_W3bCM88wsgXWMAcGIa5sqPJy2cckNLve9i4O7i_52aOevc2p4ZLbrLdhPX0TOVTazTQ7MrfaVT2iYjM4uhQ5XUw7AiA0WfAXLcDkDV-WihGe',
                        'sales' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAgfAXBZyRiqkiDnziL-EN4vadP0lVqf-xCcO1r281JlFL3Ylo-DPwhciZggofTEKMQsTNTlcyJBiM9Mxkcl3nBOS5vmyGxZi63HwxmBrETgbXocbqxuxP2AwPBgdePQuCPYPOUcUtLy8jP07NGD79VJa5IWR4ro7C_W3bCM88wsgXWMAcGIa5sqPJy2cckNLve9i4O7i_52aOevc2p4ZLbrLdhPX0TOVTazTQ7MrfaVT2iYjM4uhQ5XUw7AiA0WfAXLcDkDV-WihGe',
                        'finance' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAgfAXBZyRiqkiDnziL-EN4vadP0lVqf-xCcO1r281JlFL3Ylo-DPwhciZggofTEKMQsTNTlcyJBiM9Mxkcl3nBOS5vmyGxZi63HwxmBrETgbXocbqxuxP2AwPBgdePQuCPYPOUcUtLy8jP07NGD79VJa5IWR4ro7C_W3bCM88wsgXWMAcGIa5sqPJy2cckNLve9i4O7i_52aOevc2p4ZLbrLdhPX0TOVTazTQ7MrfaVT2iYjM4uhQ5XUw7AiA0WfAXLcDkDV-WihGe',
                        'ops' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAgfAXBZyRiqkiDnziL-EN4vadP0lVqf-xCcO1r281JlFL3Ylo-DPwhciZggofTEKMQsTNTlcyJBiM9Mxkcl3nBOS5vmyGxZi63HwxmBrETgbXocbqxuxP2AwPBgdePQuCPYPOUcUtLy8jP07NGD79VJa5IWR4ro7C_W3bCM88wsgXWMAcGIa5sqPJy2cckNLve9i4O7i_52aOevc2p4ZLbrLdhPX0TOVTazTQ7MrfaVT2iYjM4uhQ5XUw7AiA0WfAXLcDkDV-WihGe',
                    ];
                    $role = Auth::user() ? Auth::user()->roles->first()?->name : 'ops';
                    $userImg = $roleImgMap[$role] ?? $roleImgMap['ops'];
                @endphp
                <img alt="User profile" class="h-10 w-10 rounded-full object-cover border-2 border-secondary" src="{{ $userImg }}"/>
                <div class="overflow-hidden">
                    <p class="font-bold text-xs text-on-primary truncate">{{ Auth::user()->name ?? 'Demo User' }}</p>
                    <p class="text-[10px] text-on-primary-container opacity-85 truncate uppercase tracking-wider font-semibold">{{ strtoupper($role) }} HQ</p>
                </div>
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 text-red-200 hover:text-white hover:bg-error rounded-xl transition duration-200 font-semibold text-sm">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-grow min-h-screen flex flex-col bg-surface-bright">
        <!-- TOP APP BAR -->
        <header class="sticky top-0 h-16 flex items-center justify-between px-6 bg-surface-container-lowest border-b border-outline-variant z-40 shadow-sm">
            <div class="flex items-center gap-4">
                <nav class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 font-sans">
                    <span class="text-slate-400">BlueERP</span>
                    <span class="material-symbols-outlined text-sm opacity-50">chevron_right</span>
                    <span class="text-[#003887] font-extrabold uppercase tracking-wide">@yield('header_title', 'Dashboard')</span>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <!-- User Control / Role Info -->
                @php
                    $roleColors = [
                        'gm' => 'bg-purple-100 text-purple-800 border-purple-200',
                        'sales' => 'bg-green-100 text-green-800 border-green-200',
                        'finance' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'ops' => 'bg-blue-100 text-blue-800 border-blue-200',
                    ];
                    $roleNames = [
                        'gm' => 'GM',
                        'sales' => 'Sales Officer',
                        'finance' => 'Finance Admin',
                        'ops' => 'Operations Head',
                    ];
                    $colorClass = $roleColors[$role] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                    $roleName = $roleNames[$role] ?? strtoupper($role);
                @endphp
                <span class="bg-surface-container-high px-2.5 py-1 rounded-full text-[10px] text-primary border border-blue-200 font-bold uppercase tracking-wider">
                    {{ $roleName }} GATEWAY
                </span>
            </div>
        </header>

        <!-- CANVAS -->
        <div class="p-6 flex-grow">
            @yield('content')
        </div>
    </main>

    <!-- Mobile Sidebar JavaScript -->
    <script>
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const sidebar = document.getElementById('sidebar');
        let sidebarOpen = false;

        hamburgerBtn.addEventListener('click', () => {
            sidebarOpen = !sidebarOpen;
            if (sidebarOpen) {
                sidebar.classList.remove('-translate-x-full');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });

        document.addEventListener('click', (e) => {
            if (sidebarOpen && !sidebar.contains(e.target) && !hamburgerBtn.contains(e.target)) {
                sidebarOpen = false;
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
    @livewireScripts
</body>
</html>
