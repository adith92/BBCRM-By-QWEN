# 🧠 GOLDEN BIRD CRM v7.5 — MASTERPROMPT

> **Gunakan file ini sebagai konteks awal** setiap kali memulai sesi baru dengan Claude.  
> Copy bagian yang relevan sesuai task hari itu.

---

## 🟦 PROMPT MASTER (CONTEXT UTAMA)

Paste ini di **awal setiap sesi baru:**

```
Kamu adalah senior Laravel developer yang sedang mengerjakan proyek Golden Bird CRM v7.5.

KONTEKS PROYEK:
- Laravel 12.61 + PHP 8.5 + Blade Templates
- Tailwind CSS 4 via Vite (bukan CDN)
- Alpine.js 3 + Chart.js 4 via NPM
- Database: SQLite (dev), PostgreSQL (prod)
- Auth: 6 roles (gm, director, manager, sales, operational, finance)
- Testing: Pest PHP (93 tests, 85% coverage)
- Project path: /Users/adith92/Documents/ClaudeCodeBB/golden-bird-crm

ATURAN WAJIB:
1. JANGAN gunakan CDN apapun — semua via Vite/NPM
2. Semua view harus pakai blade components dari resources/views/components/ui/
3. Setiap file view maksimal 150 baris — sisanya ekstrak ke component
4. Tailwind classes HANYA yang ada di tailwind.config.js design tokens
5. SELALU test di mobile (375px) sebelum commit
6. Dark mode wajib didukung dengan class dark: prefix
7. Jangan bilang DONE kalau belum dicek di browser

STRUKTUR KOMPONEN SUDAH ADA:
resources/views/components/ui/
├── button.blade.php
├── card.blade.php
├── stat-card.blade.php
├── alert.blade.php
├── badge.blade.php
├── modal.blade.php
├── form-input.blade.php
├── form-select.blade.php
├── page-header.blade.php
└── empty-state.blade.php

DESAIN TOKENS:
- Primary color: #2563eb (blue-600)
- Font: Inter
- Border radius card: rounded-lg (8px)
- Spacing base: 4px (gap-1)
```

---

## 🔨 PROMPT PER TASK

### PROMPT 1 — Setup Vite + Tailwind CSS (FASE 0)

```
Task: Migrasi dari Tailwind CDN ke Tailwind CSS via Vite

Kondisi saat ini:
- app.blade.php menggunakan script CDN Tailwind dengan config inline
- Alpine.js dari CDN
- Chart.js dari CDN

Yang perlu dilakukan:
1. Install: npm install -D tailwindcss@latest @tailwindcss/forms @tailwindcss/typography
2. Install: npm install alpinejs chart.js
3. Buat tailwind.config.js lengkap dengan design tokens v7.5
4. Buat resources/css/app.css dengan @tailwind directives
5. Update resources/js/app.js untuk import Alpine + Chart.js
6. Update vite.config.js
7. Update app.blade.php — hapus semua CDN, ganti dengan @vite directive
8. Verify: npm run dev berjalan tanpa error

Buat semua file yang dibutuhkan. Sertakan konfigurasi Tailwind lengkap dengan warna custom dari desain saat ini (Material Design 3 palette yang sudah ada).
```

---

### PROMPT 2 — Component Library (FASE 1)

```
Task: Buat component library lengkap untuk Golden Bird CRM v7.5

Buat file-file berikut di resources/views/components/ui/:

1. button.blade.php
   Props: $variant (primary|secondary|danger|ghost|link), $size (sm|md|lg), $type, $href, $disabled
   Support: wire:click, @click (Alpine), type="submit"
   Example: <x-ui.button variant="primary" size="md">Save</x-ui.button>

2. card.blade.php
   Props: $title (optional), $subtitle (optional), $padding (sm|md|lg)
   Slots: default slot untuk content, $actions slot untuk header actions
   Example: <x-ui.card title="Fleet Overview"><p>Content</p></x-ui.card>

3. stat-card.blade.php
   Props: $label, $value, $trend (up|down|flat), $trendValue, $icon, $color (blue|green|amber|red)
   Example: <x-ui.stat-card label="Total Booking" value="2,847" trend="up" trendValue="12%"/>

4. badge.blade.php
   Props: $variant (success|warning|danger|info|neutral|purple), $size (sm|md)
   Example: <x-ui.badge variant="success">Active</x-ui.badge>

5. alert.blade.php
   Props: $type (success|warning|error|info), $dismissible (bool), $title (optional)
   Example: <x-ui.alert type="success">Data berhasil disimpan</x-ui.alert>

6. modal.blade.php
   Props: $id, $title, $size (sm|md|lg|xl)
   Alpine.js based (x-data, x-show)
   Example: <x-ui.modal id="confirm-delete" title="Konfirmasi Hapus">...</x-ui.modal>

7. page-header.blade.php
   Props: $title, $subtitle (optional), $breadcrumb (optional array)
   Slots: $actions slot untuk tombol di kanan
   Example: <x-ui.page-header title="Daftar Booking"><x-slot:actions>...</x-slot></x-ui.page-header>

8. empty-state.blade.php
   Props: $title, $description, $icon
   Slots: $action slot untuk CTA button
   Example: <x-ui.empty-state title="Belum ada booking" icon="calendar_today"/>

9. form-input.blade.php
   Props: $name, $label, $type (text|number|date|email), $required, $placeholder, $error
   Wire:model compatible
   Example: <x-ui.form-input name="client_name" label="Nama Client" required/>

10. form-select.blade.php
    Props: $name, $label, $options (array), $selected, $required, $error, $placeholder
    Example: <x-ui.form-select name="status" label="Status" :options="$statuses"/>

Setiap komponen harus:
- Support dark mode dengan class dark:
- Pakai design tokens yang konsisten
- Fully accessible (label, aria, focus ring)
- Documented dengan contoh penggunaan di komentar atas file
```

---

### PROMPT 3 — GM Dashboard (FASE 2)

```
Task: Redesign GM Dashboard menggunakan component library v7.5

File target: resources/views/dashboard/gm.blade.php
Controller: DashboardController@gm (sudah ada, jangan ubah logic PHP)

Design requirement:
- Layout "Football Manager" (sudah ada di v7.2) — pertahankan konsep, upgrade visual
- Pakai @extends('layouts.app') dan @section('content')
- Maksimal 150 baris (sisanya pakai components)
- Semua KPI card pakai <x-ui.stat-card>
- Charts pakai Chart.js (bukan CDN, sudah via NPM)
- Responsive: mobile (1 kolom), tablet (2 kolom), desktop (4 kolom)
- Dark mode support

Sections yang harus ada:
1. Page Header — "Good Morning, {{ $user->name }}" + tanggal
2. KPI Row (4 cards): Total Bookings, Revenue Bulan Ini, Fleet Active, Team Target %
3. Revenue Chart — Line chart 12 bulan (data dari api/revenue)
4. Booking Status — Donut chart (Pending/Active/Completed/Cancelled)
5. Top Sales Table — Nama, booking count, revenue, target %
6. Recent Activities — 5 aktivitas terbaru

Data yang tersedia dari controller (variabel $data sudah di-pass):
- $totalBookings, $monthlyRevenue, $activeVehicles, $teamTargetPercent
- $revenueChartData (JSON untuk Chart.js)
- $bookingStatusData (JSON untuk Chart.js)
- $topSales (collection)
- $recentActivities (collection)

Output: File gm.blade.php yang bersih, readable, < 150 baris.
```

---

### PROMPT 4 — Bookings Module Redesign (FASE 3)

```
Task: Redesign modul Bookings (4 views: index, create, edit, show)

Files:
- resources/views/bookings/index.blade.php
- resources/views/bookings/create.blade.php
- resources/views/bookings/edit.blade.php
- resources/views/bookings/show.blade.php

JANGAN ubah controller atau routes. Hanya view.

index.blade.php requirements:
- Page header dengan tombol "Buat Booking Baru"
- Filter bar: status, tanggal, sales (hanya GM & Manager yang lihat semua)
- Tabel responsive dengan kolom: No, Client, Tipe, Tanggal, Status, Sales, Total, Actions
- Pagination component
- Empty state jika data kosong
- Badge status berwarna (pending=amber, active=blue, completed=green, cancelled=red)

create.blade.php requirements:
- Form 2-column layout (kiri: data booking, kanan: summary)
- Auto-assign sales (jika role GM/Manager, bisa pilih. Jika role Sales, auto-fill)
- Client search/select
- Product/service selection
- Date range picker
- Total calculation (Alpine.js reactive)
- Submit dengan konfirmasi modal

edit.blade.php requirements:
- Sama seperti create tapi pre-filled
- Tambah section "Riwayat Perubahan" di bawah

show.blade.php requirements:
- Header dengan status badge + action buttons (Edit, Cancel, Generate Invoice)
- Grid 2-column: detail booking (kiri), timeline status (kanan)
- Section invoice jika sudah ada
- Section payment jika sudah ada

Gunakan semua komponen dari x-ui.*
Maksimal 120 baris per file.
```

---

### PROMPT 5 — Fleet Module (FASE 3)

```
Task: Redesign Fleet Management module

Files:
- resources/views/fleet/index.blade.php
- resources/views/fleet/create.blade.php
- resources/views/fleet/show.blade.php

index.blade.php requirements:
- Filter: status (active/maintenance/idle), pool, type
- View toggle: Grid (card per vehicle) vs List (tabel)
  - Grid: Foto placeholder, plat nomor, tipe, status badge, driver, aksi
  - List: Tabel standar dengan semua kolom
- Status indicator: hijau=active, amber=maintenance, gray=idle
- Summary stats di atas: Total, Active, Maintenance, Idle

create.blade.php requirements:
- Form sections: Data Kendaraan | Penugasan | Dokumen
- Plat nomor format validation (visual)
- Dropdown pool yang sudah ada
- Upload foto (placeholder for now)

show.blade.php requirements:
- Header: Foto + nama/plat + status + actions
- Tabs: Detail | Driver History | Maintenance Log | Booking History
- Setiap tab render tabel atau timeline

Gunakan x-ui.* components. Max 120 baris per file.
```

---

### PROMPT 6 — Analytics Dashboard (FASE 3)

```
Task: Redesign Analytics Module (chart-heavy)

Files:
- resources/views/analytics/index.blade.php (Revenue Overview)
- resources/views/analytics/sales.blade.php (Sales Performance)
- resources/views/analytics/pipeline.blade.php (Pipeline Analysis)

index.blade.php requirements:
- Period selector: Daily | Weekly | Monthly | Yearly (Alpine.js tabs)
- Revenue trend chart (Line) — ambil dari GET /api/revenue
- Revenue per Sales (Bar) — ambil dari GET /api/revenue/per-sales
- Booking volume chart (Bar)
- Filter by date range

Semua chart harus:
- Gunakan Chart.js via NPM (sudah di-setup Fase 0)
- Responsive (canvas wrapper dengan aspect ratio)
- Loading state saat fetch data
- Error state jika API gagal
- Warna sesuai design tokens

sales.blade.php requirements:
- Tabel ranking sales (rank, nama, booking, revenue, target, %)
- Progress bar untuk target achievement
- Badge "On Track" / "Behind" / "Exceeded"

pipeline.blade.php requirements:
- Funnel visualization (pakai bar chart horizontal)
- Stage count: Lead → Qualified → Proposal → Negotiation → Won/Lost
- Konversi rate per stage

Max 130 baris per file.
```

---

### PROMPT 7 — Dark Mode Implementation

```
Task: Implement dark mode di seluruh project Golden Bird CRM v7.5

Setup yang diperlukan:
1. tailwind.config.js: darkMode: 'class' (sudah ada, verify)
2. Toggle button di navbar (Alpine.js, simpan ke localStorage)
3. Script di app.blade.php untuk detect saved preference + system preference

Komponen yang perlu dark mode variants:
- Layout (sidebar, navbar, main content area)
- Card components
- Table rows + header
- Form inputs
- Modal backdrop + content
- Alert components
- Badge components
- Charts (Chart.js dark theme)

Aturan dark mode:
- Background:    dark:bg-gray-900 (sidebar), dark:bg-gray-800 (card)
- Text:          dark:text-gray-100 (heading), dark:text-gray-300 (body)
- Border:        dark:border-gray-700
- Input:         dark:bg-gray-700 dark:border-gray-600 dark:text-white
- Chart.js:      Ganti backgroundColor dan gridLines ke dark variant

Toggle button HTML (Alpine.js):
<button @click="dark = !dark; $store.theme.toggle()" class="...">
  <span x-show="!dark">🌙</span>
  <span x-show="dark">☀️</span>
</button>

Alpine store (di app.js):
Alpine.store('theme', {
  dark: localStorage.getItem('theme') === 'dark',
  toggle() {
    this.dark = !this.dark
    localStorage.setItem('theme', this.dark ? 'dark' : 'light')
    document.documentElement.classList.toggle('dark', this.dark)
  }
})

Pastikan semua 20+ page tercover sebelum commit.
```

---

### PROMPT 8 — Mobile Optimization

```
Task: Audit dan optimasi mobile responsiveness seluruh aplikasi

Target: Semua page harus usable di 375px (iPhone SE viewport)

Checklist per page:
1. Sidebar: Collapse ke drawer di mobile (Alpine.js x-show + overlay)
2. Navbar: Hamburger menu di mobile
3. Tabel: Horizontal scroll di mobile (overflow-x-auto wrapper)
4. Form: Single column di mobile (sm:grid-cols-2 lg:grid-cols-3)
5. KPI cards: 2 kolom di mobile (grid-cols-2), 4 di desktop
6. Chart: Aspect ratio terjaga di mobile (tidak terpotong)
7. Tombol aksi tabel: Dropdown menu di mobile
8. Modal: Full-screen di mobile (max-w-screen-sm → full width di mobile)

Sidebar mobile pattern:
<div x-data="{ open: false }">
  <!-- Overlay -->
  <div x-show="open" @click="open = false" 
       class="fixed inset-0 bg-black/50 z-20 lg:hidden"></div>
  
  <!-- Sidebar -->
  <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
         class="fixed inset-y-0 left-0 z-30 w-64 transition-transform lg:translate-x-0">
    ...
  </aside>
  
  <!-- Hamburger -->
  <button @click="open = true" class="lg:hidden">☰</button>
</div>

Lakukan audit dan fix untuk semua views yang belum responsive.
```

---

### PROMPT 9 — Performance Optimization

```
Task: Optimize performance Golden Bird CRM v7.5

Target: Lighthouse Performance ≥ 90

Optimasi yang diperlukan:

1. Laravel Blade:
   - Tambah @once untuk scripts yang duplikat
   - Ganti @include yang berat dengan @component
   - Cache view dengan php artisan view:cache

2. Query Optimization:
   - Tambah eager loading di semua controller (with(['client', 'sales']))
   - Tambah pagination di semua list (->paginate(20))
   - Tambah index di kolom yang sering diquery

3. Asset:
   - npm run build (production minification)
   - Pastikan Vite menghasilkan hashed filenames

4. Chart.js:
   - Lazy load charts (hanya render jika canvas visible)
   - Gunakan intersection observer untuk defer render

5. Images:
   - Semua image pakai loading="lazy"
   - Tambah width dan height attribute

6. Laravel Performance:
   - php artisan config:cache
   - php artisan route:cache
   - php artisan view:cache

Analisa file yang paling berat dan optimasi satu per satu.
Report: Sebelum vs Sesudah Lighthouse score.
```

---

### PROMPT 10 — Final QA Checklist

```
Task: Final Quality Assurance sebelum deploy v7.5

Jalankan checklist berikut dan report hasilnya:

FUNCTIONALITY:
☐ Login semua 6 role berhasil
☐ Dashboard masing-masing role tampil benar
☐ CRUD Booking: Create, Read, Update, Delete
☐ CRUD Client: Create, Read, Update, Delete
☐ CRUD Fleet: Create, Read, Update, Delete
☐ Analytics charts render dengan data
☐ Dark mode toggle berfungsi dan persisten
☐ Logout berfungsi

VISUAL:
☐ Tidak ada layout broken di desktop (1920px)
☐ Tidak ada layout broken di tablet (768px)
☐ Tidak ada layout broken di mobile (375px)
☐ Dark mode tidak ada warna yang "bocor" (text tidak terbaca)
☐ Semua icon tampil benar
☐ Semua badge warna sesuai status
☐ Pagination tampil dan berfungsi

PERFORMANCE:
☐ Lighthouse Performance ≥ 90 (dashboard GM)
☐ Page load < 1 detik (local server)
☐ npm run build sukses tanpa warning
☐ Tidak ada console error di browser

TESTS:
☐ php artisan test → semua 93 tests green
☐ Tidak ada 404 route error
☐ Tidak ada 500 server error
☐ Tidak ada auth bypass

DEPLOY:
☐ .env.production siap
☐ APP_KEY dirotate
☐ DEMO CREDENTIALS bukan di seeder (pindah ke .env)
☐ Railway.app deploy sukses

Buat report dengan format: ✅ PASS | ❌ FAIL | ⚠️ WARNING
```

---

## 🗂️ QUICK REFERENCE

### Nama Role & Email Demo:
```
gm@goldenbird.co.id        → role: gm
director@goldenbird.co.id  → role: director
manager@goldenbird.co.id   → role: manager
sales1@goldenbird.co.id    → role: sales
ops@goldenbird.co.id       → role: operational
finance@goldenbird.co.id   → role: finance
Password semua: password123
```

### Artisan Commands Penting:
```bash
php artisan serve                    # Start server
php artisan migrate:fresh --seed     # Reset DB
php artisan test                     # Run all tests
php artisan route:list               # List routes
npm run dev                          # Dev build (watch)
npm run build                        # Production build
php artisan view:clear               # Clear view cache
php artisan cache:clear              # Clear all cache
```

### Struktur Folder Yang Relevan:
```
app/Http/Controllers/         → Jangan ubah logic
app/Models/                   → 21 models, jangan ubah
resources/views/              → Area kerja utama
resources/views/components/   → Component library
resources/css/app.css         → Tailwind input
resources/js/app.js           → Alpine + Chart setup
vite.config.js                → Build config
tailwind.config.js            → Design tokens
```

---

*Masterprompt ini adalah toolkit sesi per sesi. Gunakan sesuai fase yang sedang dikerjakan.*
