# ⚙️ GOLDEN BIRD CRM v7.5 — IMPLEMENTATION PLAN

> **Dokumen ini adalah panduan teknis eksekusi harian.**  
> Tiap task punya file target, output yang diharapkan, dan command verifikasi.

---

## 🗓️ HARI 1 — Setup Vite + Tailwind

### Tujuan:
Buang semua CDN. Migrasi ke Vite build pipeline.

### Commands:
```bash
cd /Users/adith92/Documents/ClaudeCodeBB/golden-bird-crm

# Install dependencies
npm install -D tailwindcss @tailwindcss/forms autoprefixer
npm install alpinejs chart.js

# Init tailwind (jika belum ada)
npx tailwindcss init -p
```

### File: `tailwind.config.js`
```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
      colors: {
        // Material Design 3 palette (pertahankan dari v7.2)
        primary: '#003887',
        secondary: '#1960a6',
        tertiary: '#003c73',
        surface: '#f8f9ff',
        background: '#f8f9ff',
        // Custom additions v7.5
        brand: {
          50:  '#eff6ff',
          100: '#dbeafe',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
          900: '#1e3a8a',
        },
        success:  '#10b981',
        warning:  '#f59e0b',
        danger:   '#ef4444',
        info:     '#06b6d4',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
```

### File: `resources/css/app.css`
```css
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
  html {
    font-family: 'Inter', system-ui, sans-serif;
    -webkit-font-smoothing: antialiased;
  }
  
  body {
    @apply bg-surface text-gray-900;
  }
  
  .dark body {
    @apply bg-gray-900 text-gray-100;
  }
}

@layer components {
  .focus-ring {
    @apply focus:outline-none focus:ring-2 focus:ring-brand-600 focus:ring-offset-2;
  }
  
  .transition-base {
    @apply transition-all duration-200 ease-in-out;
  }
}
```

### File: `resources/js/app.js`
```javascript
import './bootstrap';
import Alpine from 'alpinejs';
import { Chart, registerables } from 'chart.js';

// Register Chart.js components
Chart.register(...registerables);

// Alpine dark mode store
Alpine.store('theme', {
  dark: localStorage.getItem('theme') === 'dark' || 
        (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
  
  init() {
    document.documentElement.classList.toggle('dark', this.dark);
  },
  
  toggle() {
    this.dark = !this.dark;
    localStorage.setItem('theme', this.dark ? 'dark' : 'light');
    document.documentElement.classList.toggle('dark', this.dark);
  }
});

window.Alpine = Alpine;
Alpine.start();

// Chart.js defaults
Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
Chart.defaults.font.size = 12;
Chart.defaults.color = '#6b7280';
```

### File: `vite.config.js` (update)
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
});
```

### Update `resources/views/layouts/app.blade.php`:
Hapus semua script CDN, ganti dengan:
```html
<!-- Hapus ini: -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Ganti dengan: -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

### Verifikasi:
```bash
npm run dev   # Harus sukses, 0 error
# Buka http://localhost:8000
# Semua styling harus tampil (bukan raw HTML)
```

---

## 🗓️ HARI 2 — Layout & Sidebar Overhaul

### File: `resources/views/layouts/app.blade.php`

```html
<!DOCTYPE html>
<html class="{{ $darkMode ?? '' }}" lang="id" x-data x-init="$store.theme.init()">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <title>{{ $title ?? 'Golden Bird CRM' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@400,0&display=swap" rel="stylesheet"/>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('head')
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen">

<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
  
  <!-- Mobile Overlay -->
  <div x-show="sidebarOpen" x-transition.opacity 
       @click="sidebarOpen = false"
       class="fixed inset-0 z-20 bg-black/50 lg:hidden"></div>

  <!-- Sidebar -->
  @include('layouts.partials.sidebar')

  <!-- Main Content -->
  <div class="flex-1 flex flex-col overflow-hidden">
    
    <!-- Topbar -->
    @include('layouts.partials.topbar')

    <!-- Page Content -->
    <main class="flex-1 overflow-y-auto p-4 lg:p-6">
      @if (session('success'))
        <x-ui.alert type="success" :dismissible="true" class="mb-4">
          {{ session('success') }}
        </x-ui.alert>
      @endif
      
      @if (session('error'))
        <x-ui.alert type="error" :dismissible="true" class="mb-4">
          {{ session('error') }}
        </x-ui.alert>
      @endif

      @yield('content')
    </main>
  </div>
</div>

@stack('scripts')
</body>
</html>
```

### File: `resources/views/layouts/partials/sidebar.blade.php`

```html
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-gray-800 
              border-r border-gray-200 dark:border-gray-700
              transition-transform duration-300 flex flex-col">

  <!-- Brand -->
  <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-200 dark:border-gray-700">
    <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center">
      <span class="text-white text-sm font-bold">GB</span>
    </div>
    <span class="font-bold text-gray-900 dark:text-white">Golden Bird CRM</span>
  </div>

  <!-- Nav Items -->
  <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
    @include('layouts.partials.nav-items')
  </nav>

  <!-- User Profile -->
  <div class="px-3 py-4 border-t border-gray-200 dark:border-gray-700">
    <div class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
      <div class="w-8 h-8 bg-brand-100 rounded-full flex items-center justify-center">
        <span class="text-brand-700 text-sm font-semibold">
          {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </span>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
          {{ auth()->user()->name }}
        </p>
        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
          {{ ucfirst(auth()->user()->role) }}
        </p>
      </div>
    </div>
  </div>
</aside>
```

---

## 🗓️ HARI 3–7 — Component Library

### Urutan Build:
```
Hari 3: button, badge, alert
Hari 4: card, stat-card, empty-state
Hari 5: form-input, form-select, form-textarea
Hari 6: modal, page-header, table-wrapper
Hari 7: Integrasi semua komponen ke layouts, test semua
```

### Contoh `resources/views/components/ui/stat-card.blade.php`:

```html
@props([
  'label'       => 'Label',
  'value'       => '0',
  'trend'       => null,        // up|down|flat
  'trendValue'  => null,        // '12%'
  'icon'        => 'analytics',
  'color'       => 'blue',      // blue|green|amber|red|purple
])

@php
  $colorClasses = [
    'blue'   => 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
    'green'  => 'bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400',
    'amber'  => 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
    'red'    => 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400',
    'purple' => 'bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
  ];
  
  $trendColor = match($trend) {
    'up'   => 'text-green-600',
    'down' => 'text-red-600',
    default => 'text-gray-500',
  };
  
  $trendIcon = match($trend) {
    'up'   => '↑',
    'down' => '↓',
    default => '→',
  };
@endphp

<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5 
            hover:border-brand-300 hover:shadow-sm transition-all duration-200">
  <div class="flex items-start justify-between">
    <div class="flex-1">
      <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $label }}</p>
      <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $value }}</p>
      @if($trend && $trendValue)
        <p class="text-xs mt-2 {{ $trendColor }}">
          {{ $trendIcon }} {{ $trendValue }} dari bulan lalu
        </p>
      @endif
    </div>
    <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $colorClasses[$color] }}">
      <span class="material-symbols-outlined text-lg">{{ $icon }}</span>
    </div>
  </div>
</div>
```

### Contoh `resources/views/components/ui/button.blade.php`:

```html
@props([
  'variant' => 'primary',   // primary|secondary|danger|ghost|link
  'size'    => 'md',        // sm|md|lg
  'type'    => 'button',
  'href'    => null,
  'disabled' => false,
])

@php
  $variants = [
    'primary'   => 'bg-brand-600 hover:bg-brand-700 text-white border border-transparent',
    'secondary' => 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600',
    'danger'    => 'bg-red-600 hover:bg-red-700 text-white border border-transparent',
    'ghost'     => 'bg-transparent hover:bg-gray-100 text-gray-700 border border-transparent dark:hover:bg-gray-700 dark:text-gray-200',
    'link'      => 'bg-transparent text-brand-600 hover:text-brand-700 border border-transparent underline-offset-2 hover:underline',
  ];
  
  $sizes = [
    'sm' => 'px-3 py-1.5 text-xs rounded-md gap-1.5',
    'md' => 'px-4 py-2 text-sm rounded-lg gap-2',
    'lg' => 'px-6 py-2.5 text-base rounded-lg gap-2',
  ];
  
  $base = 'inline-flex items-center justify-center font-medium transition-all duration-200 
           focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2
           disabled:opacity-50 disabled:cursor-not-allowed';
@endphp

@if($href && !$disabled)
  <a href="{{ $href }}" {{ $attributes->merge(['class' => "$base {$variants[$variant]} {$sizes[$size]}"]) }}>
    {{ $slot }}
  </a>
@else
  <button 
    type="{{ $type }}" 
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => "$base {$variants[$variant]} {$sizes[$size]}"]) }}
  >
    {{ $slot }}
  </button>
@endif
```

---

## 🗓️ HARI 8–12 — Dashboard Redesign

### Priority Order:
```
Hari 8:  GM Dashboard (paling kompleks)
Hari 9:  Sales Dashboard
Hari 10: Ops Dashboard + Finance Dashboard
Hari 11: Manager + Director Dashboard
Hari 12: Cross-test semua dashboard, fix bugs
```

### Template Pattern (gunakan di semua dashboard):

```html
@extends('layouts.app')
@section('title', 'Dashboard GM')

@section('content')

{{-- Page Header --}}
<x-ui.page-header title="Dashboard" :subtitle="'Selamat datang, ' . auth()->user()->name">
  <x-slot:actions>
    <x-ui.button variant="secondary" size="sm" href="{{ route('bookings.create') }}">
      <span class="material-symbols-outlined text-sm">add</span>
      Booking Baru
    </x-ui.button>
  </x-slot:actions>
</x-ui.page-header>

{{-- KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  <x-ui.stat-card label="Total Booking" :value="number_format($totalBookings)" 
    trend="up" trendValue="12%" icon="calendar_month" color="blue"/>
  <x-ui.stat-card label="Revenue Bulan Ini" :value="'Rp ' . number_format($monthlyRevenue/1000000, 1) . 'M'" 
    trend="up" trendValue="8%" icon="payments" color="green"/>
  <x-ui.stat-card label="Armada Aktif" :value="$activeVehicles . '/' . $totalVehicles" 
    icon="directions_bus" color="amber"/>
  <x-ui.stat-card label="Target Tim" :value="$teamTargetPercent . '%'" 
    :trend="$teamTargetPercent >= 80 ? 'up' : 'down'" 
    :trendValue="$teamTargetPercent . '%'" icon="flag" color="purple"/>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
  
  {{-- Revenue Trend (2/3 width) --}}
  <x-ui.card title="Revenue Trend" class="lg:col-span-2">
    <canvas id="revenueChart" class="w-full" style="height:220px"></canvas>
  </x-ui.card>
  
  {{-- Booking Status (1/3 width) --}}
  <x-ui.card title="Status Booking">
    <canvas id="statusChart" class="w-full" style="height:220px"></canvas>
  </x-ui.card>
</div>

{{-- Bottom Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
  
  {{-- Top Sales --}}
  <x-ui.card title="Top Sales Bulan Ini">
    @if($topSales->isEmpty())
      <x-ui.empty-state title="Belum ada data" icon="leaderboard"/>
    @else
      <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">#</th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Nama</th>
            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500">Revenue</th>
            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500">%</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          @foreach($topSales as $i => $sales)
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
            <td class="px-3 py-2 text-gray-500">{{ $i + 1 }}</td>
            <td class="px-3 py-2 font-medium text-gray-900 dark:text-white">{{ $sales->name }}</td>
            <td class="px-3 py-2 text-right text-gray-700 dark:text-gray-300">
              Rp {{ number_format($sales->total_revenue / 1000000, 1) }}M
            </td>
            <td class="px-3 py-2 text-right">
              <x-ui.badge :variant="$sales->target_pct >= 80 ? 'success' : 'warning'">
                {{ $sales->target_pct }}%
              </x-ui.badge>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </x-ui.card>

  {{-- Recent Activities --}}
  <x-ui.card title="Aktivitas Terbaru">
    @forelse($recentActivities as $act)
      <div class="flex gap-3 pb-3 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
        <div class="w-8 h-8 bg-brand-100 dark:bg-brand-900/30 rounded-full flex-shrink-0 
                    flex items-center justify-center">
          <span class="material-symbols-outlined text-xs text-brand-600">person</span>
        </div>
        <div>
          <p class="text-sm text-gray-900 dark:text-white">{{ $act->description }}</p>
          <p class="text-xs text-gray-500 mt-0.5">{{ $act->created_at->diffForHumans() }}</p>
        </div>
      </div>
    @empty
      <x-ui.empty-state title="Belum ada aktivitas" icon="history"/>
    @endforelse
  </x-ui.card>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Revenue Chart
  const isDark = document.documentElement.classList.contains('dark');
  const gridColor = isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.08)';
  const textColor = isDark ? '#9ca3af' : '#6b7280';
  
  new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: @json($revenueChartData),
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { color: gridColor }, ticks: { color: textColor } },
        y: { grid: { color: gridColor }, ticks: { color: textColor,
          callback: v => 'Rp ' + (v/1000000).toFixed(0) + 'M' } }
      }
    }
  });

  // Status Chart
  new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: @json($bookingStatusData),
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' } },
      cutout: '65%'
    }
  });
});
</script>
@endpush
```

---

## 🗓️ HARI 13–22 — Core Module Redesign

### Prioritas & Estimasi:
| Module | Files | Estimasi |
|--------|-------|---------|
| Bookings | 4 views | 2 hari |
| Clients | 4 views | 1.5 hari |
| Fleet | 3 views | 1.5 hari |
| Analytics | 3 views | 2 hari |
| Pipeline | 2 views | 1 hari |
| Approvals | 2 views | 1 hari |
| KPI | 1 view | 0.5 hari |
| **Total** | **19 views** | **10 hari** |

### Pattern yang digunakan tiap modul:

**List View Pattern:**
```html
@extends('layouts.app')
@section('content')

<x-ui.page-header title="{{ $pageTitle }}">
  <x-slot:actions>
    @can('create', $modelClass)
      <x-ui.button href="{{ route("$routePrefix.create") }}">
        <span class="material-symbols-outlined text-sm">add</span> Tambah
      </x-ui.button>
    @endcan
  </x-slot:actions>
</x-ui.page-header>

{{-- Filter Bar --}}
<x-ui.card class="mb-4">
  <form method="GET" class="flex flex-wrap gap-3">
    <x-ui.form-input name="search" placeholder="Cari..." :value="request('search')" class="w-full sm:w-auto"/>
    <x-ui.form-select name="status" :options="$statusOptions" :selected="request('status')" placeholder="Semua Status"/>
    <x-ui.button type="submit" variant="secondary">Filter</x-ui.button>
    @if(request()->hasAny(['search','status']))
      <x-ui.button href="{{ route("$routePrefix.index") }}" variant="ghost">Reset</x-ui.button>
    @endif
  </form>
</x-ui.card>

{{-- Data Table --}}
<x-ui.card>
  @if($items->isEmpty())
    <x-ui.empty-state title="Tidak ada data" icon="{{ $emptyIcon }}">
      <x-slot:action>
        <x-ui.button href="{{ route("$routePrefix.create") }}">Tambah Pertama</x-ui.button>
      </x-slot:action>
    </x-ui.empty-state>
  @else
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        {{-- thead, tbody... --}}
      </table>
    </div>
    <div class="mt-4">{{ $items->links() }}</div>
  @endif
</x-ui.card>

@endsection
```

---

## 🗓️ HARI 23–28 — Advanced Modules

### Module → File Target:
```
Finance:
  resources/views/finance/index.blade.php
  resources/views/finance/invoices/show.blade.php

Products:
  resources/views/products/index.blade.php
  resources/views/products/create.blade.php

Subscriptions:
  resources/views/subscriptions/index.blade.php

Vouchers:
  resources/views/vouchers/index.blade.php

Maintenance:
  resources/views/maintenance/index.blade.php

Activities:
  resources/views/activities/index.blade.php
```

---

## 🗓️ HARI 29–33 — Testing & Polish

### Lighthouse Audit Commands:
```bash
# Install Lighthouse
npm install -g lighthouse

# Audit halaman utama
lighthouse http://localhost:8000/dashboard --output html --output-path ./lighthouse-report.html --view

# Audit per page
for page in dashboard bookings clients fleet analytics; do
  lighthouse http://localhost:8000/$page \
    --output json \
    --output-path ./lighthouse-$page.json \
    --chrome-flags="--headless"
done
```

### Performance Checklist:
```bash
# 1. Production build
npm run build

# 2. Cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Run tests
php artisan test

# 4. Check for N+1 queries (di Telescope atau log)
# Tambahkan di AppServiceProvider::boot():
# \Illuminate\Support\Facades\DB::listen(fn($q) => logger($q->sql));
```

---

## 🗓️ HARI 34–35 — Deployment

### Railway.app Deployment:
```bash
# 1. Login Railway
railway login

# 2. Link ke project (atau buat baru)
railway link
# OR: railway init --name golden-bird-crm-v7.5

# 3. Add PostgreSQL
railway add --database postgresql

# 4. Set environment variables
railway variable set APP_ENV=production
railway variable set APP_DEBUG=false
railway variable set APP_KEY=$(php artisan key:generate --show)
railway variable set APP_URL=https://your-app.railway.app

# 5. Deploy
git push origin main
# Railway auto-deploy dari main branch

# 6. Run migrations
railway run php artisan migrate --force
railway run php artisan db:seed --force

# 7. Verify
railway open
```

### `.env.production` checklist:
```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...  # ROTATED, bukan default
APP_URL=https://golden-bird-crm-v7.railway.app

DB_CONNECTION=pgsql
# (Railway auto-set dari database addon)

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=database

LOG_CHANNEL=stack
LOG_LEVEL=error
```

---

## 🔁 GIT WORKFLOW v7.5

### Branch Strategy:
```
main              → Production (deploy)
feature/v7.5-base → Main development branch
feature/v7.5-*    → Per-feature branches
```

### Commit Convention:
```bash
# Format: type(scope): description
git commit -m "feat(layout): sidebar mobile drawer + dark mode toggle"
git commit -m "feat(components): stat-card, badge, alert primitives"
git commit -m "feat(dashboard): gm dashboard redesign v7.5"
git commit -m "feat(bookings): index + create view redesign"
git commit -m "fix(darkmode): chart.js colors on dark theme"
git commit -m "perf(fleet): lazy load vehicle images"
git commit -m "test: verify 93 tests still passing post-redesign"
```

### Checkpoint Commits (wajib):
```
Hari 2:  "feat(v7.5): Vite migration complete, CDN removed"
Hari 7:  "feat(v7.5): component library complete"
Hari 12: "feat(v7.5): all 6 dashboards redesigned"
Hari 22: "feat(v7.5): core modules complete"
Hari 28: "feat(v7.5): all modules complete"
Hari 33: "fix(v7.5): polish + performance"
Hari 35: "release: v7.5.0 production deploy"
```

---

## 📊 FILE TRACKER

Gunakan ini untuk track progress harian:

| Status | File | Hari Target | Done? |
|--------|------|-------------|-------|
| ⚙️ | tailwind.config.js | 1 | ☐ |
| ⚙️ | resources/css/app.css | 1 | ☐ |
| ⚙️ | resources/js/app.js | 1 | ☐ |
| ⚙️ | vite.config.js | 1 | ☐ |
| 🎨 | layouts/app.blade.php | 2 | ☐ |
| 🎨 | layouts/partials/sidebar.blade.php | 2 | ☐ |
| 🎨 | layouts/partials/topbar.blade.php | 2 | ☐ |
| 🧩 | components/ui/button.blade.php | 3 | ☐ |
| 🧩 | components/ui/badge.blade.php | 3 | ☐ |
| 🧩 | components/ui/alert.blade.php | 3 | ☐ |
| 🧩 | components/ui/card.blade.php | 4 | ☐ |
| 🧩 | components/ui/stat-card.blade.php | 4 | ☐ |
| 🧩 | components/ui/empty-state.blade.php | 4 | ☐ |
| 🧩 | components/ui/form-input.blade.php | 5 | ☐ |
| 🧩 | components/ui/form-select.blade.php | 5 | ☐ |
| 🧩 | components/ui/modal.blade.php | 6 | ☐ |
| 🧩 | components/ui/page-header.blade.php | 6 | ☐ |
| 📊 | dashboard/gm.blade.php | 8 | ☐ |
| 📊 | dashboard/sales.blade.php | 9 | ☐ |
| 📊 | dashboard/operational.blade.php | 10 | ☐ |
| 📊 | dashboard/finance.blade.php | 10 | ☐ |
| 📊 | dashboard/manager.blade.php | 11 | ☐ |
| 📊 | dashboard/director.blade.php | 11 | ☐ |
| 📋 | bookings/index.blade.php | 13 | ☐ |
| 📋 | bookings/create.blade.php | 13 | ☐ |
| 📋 | bookings/edit.blade.php | 14 | ☐ |
| 📋 | bookings/show.blade.php | 14 | ☐ |
| 📋 | clients/index.blade.php | 15 | ☐ |
| 📋 | clients/show.blade.php | 15 | ☐ |
| 📋 | fleet/index.blade.php | 16 | ☐ |
| 📋 | fleet/show.blade.php | 16 | ☐ |
| 📋 | analytics/index.blade.php | 17 | ☐ |
| 📋 | analytics/sales.blade.php | 18 | ☐ |
| 📋 | pipeline/index.blade.php | 19 | ☐ |
| 📋 | approvals/index.blade.php | 20 | ☐ |
| 📋 | kpi/index.blade.php | 21 | ☐ |
| 📋 | finance/index.blade.php | 23 | ☐ |
| 📋 | products/index.blade.php | 24 | ☐ |
| 📋 | subscriptions/index.blade.php | 25 | ☐ |
| 📋 | vouchers/index.blade.php | 26 | ☐ |
| 📋 | maintenance/index.blade.php | 27 | ☐ |
| 📋 | activities/index.blade.php | 28 | ☐ |

*Total: 40 files | 35 hari | Solo developer*
