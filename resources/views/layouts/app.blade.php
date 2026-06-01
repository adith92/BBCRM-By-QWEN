<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Golden Bird CRM' }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN (v3) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    @livewireStyles
</head>
<body class="bg-slate-50 font-sans min-h-screen flex flex-col md:flex-row">

    <!-- Mobile Header -->
    <div class="md:hidden w-full bg-blue-900 text-white flex justify-between items-center px-4 py-3 shadow-md z-50">
        <div class="text-lg font-bold tracking-wider">GOLDEN BIRD</div>
        <button id="hamburger-btn" class="p-1 focus:outline-none focus:ring-2 focus:ring-white rounded">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path id="hamburger-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- Sidebar Left (bg-blue-900 / bg-[#1E3A8A]) -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out z-40 bg-blue-950 text-white w-64 flex flex-col min-h-screen shadow-2xl">
        <!-- Brand Header -->
        <div class="px-6 py-5 border-b border-blue-900 hidden md:block">
            <div class="text-xl font-extrabold tracking-wider text-slate-100">
                GOLDEN <span class="text-yellow-400">BIRD</span>
            </div>
            <p class="text-[10px] text-blue-300 font-semibold uppercase mt-0.5 tracking-wider">CRM SYSTEM V1.0</p>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <p class="text-[11px] font-semibold text-blue-400 uppercase tracking-wider px-3 mb-2">Menus</p>

            @can('crm.view')
                <a href="/dashboard/gm" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition duration-150 {{ Request::is('dashboard/gm') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-200 hover:bg-blue-900 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                    CRM Dashboard
                </a>
            @endcan

            @can('fleet.view')
                <a href="/fleet" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition duration-150 {{ Request::is('fleet') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-200 hover:bg-blue-900 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Fleet Management
                </a>
            @endcan

            @can('booking.view')
                <a href="/bookings" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition duration-150 {{ Request::is('bookings') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-200 hover:bg-blue-900 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Booking Management
                </a>
            @endcan

            @can('finance.view')
                <a href="/invoices" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition duration-150 {{ Request::is('invoices') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-200 hover:bg-blue-900 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Invoices & Finance
                </a>
            @endcan
        </nav>

        <!-- Footer / User Control -->
        <div class="p-4 border-t border-blue-900">
            <form method="POST" action="/logout" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-red-300 hover:bg-red-950 hover:text-red-200 transition">
                    <svg class="mr-3 h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden min-h-screen">
        <!-- Main Top Header -->
        <header class="bg-white shadow-sm border-b border-slate-200 py-4 px-6 flex justify-between items-center z-10">
            <h1 class="text-lg font-bold text-slate-800 hidden md:block">
                @yield('header_title', 'Golden Bird CRM Dashboard')
            </h1>
            <div class="text-right ml-auto flex items-center space-x-3">
                <div class="hidden sm:block">
                    <div class="text-sm font-semibold text-slate-800">
                        {{ Auth::user()->name ?? 'Demo User' }}
                    </div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase">
                        {{ Auth::user()->email ?? '' }}
                    </div>
                </div>

                <!-- Role Badge -->
                @php
                    $role = Auth::user() ? Auth::user()->roles->first()?->name : 'guest';
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
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $colorClass }}">
                    {{ $roleName }}
                </span>
            </div>
        </header>

        <!-- Main Inner Area -->
        <main class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    <!-- Mobile Sidebar Backdrop & Toggle Logic -->
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

        // Close sidebar when clicking outside of it on mobile
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
