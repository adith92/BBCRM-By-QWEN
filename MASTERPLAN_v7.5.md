# 🐦 GOLDEN BIRD CRM v7.5 — MASTERPLAN

> **Versi:** 7.5 (Major UX Overhaul)  
> **Tanggal:** June 2026  
> **Status:** 🟡 PLANNING — Siap Eksekusi  
> **Estimasi Total:** 4–6 Minggu (Solo Developer)

---

## 📌 EXECUTIVE SUMMARY

v7.5 adalah **total redesign** berbasis data nyata dari proyek yang sudah berjalan. Bukan menulis ulang dari nol — melainkan **upgrade strategis** dengan:

- UI lebih bersih & cepat (performance-first)
- UX yang lebih intuitif per role (GM, Sales, Ops, Finance)
- Design system konsisten dari halaman pertama hingga terakhir
- Komponen bisa reuse 80%+ di seluruh modul
- Mobile-ready sejak hari pertama

---

## 🏗️ ARSITEKTUR PROYEK EKSISTING (Baseline)

### Stack Saat Ini:
```
Backend:   Laravel 12.61 + PHP 8.5
Frontend:  Blade + Tailwind CDN + Alpine.js 3 + Chart.js
Database:  SQLite (dev) → PostgreSQL (prod)
Auth:      Laravel Breeze (6 roles)
Testing:   Pest PHP (93 tests, 85% coverage)
```

### Modul Aktif (21 Models, 19 Controllers):
```
✅ Dashboard      — GM, Sales, Ops, Finance, Manager, Director
✅ Bookings       — CRUD + auto-assign sales
✅ Clients        — CRM profile + relasi
✅ Fleet          — Vehicle + Driver + Pool
✅ Finance        — Invoice + Payment
✅ Analytics      — Revenue, Pipeline, Crosssell, Sales
✅ Approvals      — Multi-level discount approval
✅ KPI / Target   — Sales target tracking
✅ Pipeline       — Opportunity management
✅ Products       — Catalog + categories
✅ Subscriptions  — Recurring revenue
✅ Vouchers       — E-voucher system
✅ Maintenance    — Fleet maintenance log
✅ Activities     — CRM activity tracking
```

### Rute Aktif:
```
~50+ routes  |  6 role dashboards  |  API endpoints untuk revenue & activities
```

---

## 🎯 GOALS v7.5

### Problem Statement (dari v7.2 → v7.3):
| Issue | Impact | Priority |
|-------|--------|----------|
| Layout file > 400 baris (gm.blade, pipeline.blade) | Sulit maintain | P0 |
| Tailwind via CDN (lambat, tak terkompilasi) | Performance buruk | P0 |
| Desain tidak konsisten antar modul | UX buruk | P1 |
| Tidak ada dark mode nyata | User experience | P1 |
| Mobile tidak dioptimasi | Ops tim di lapangan | P1 |
| Knowledge silo (64% kode 1 orang) | Maintainability | P2 |
| View files tanpa komponen terisolasi | DRY violation | P2 |

### Target v7.5:
```
✅ Tailwind CSS dikompilasi via Vite (bukan CDN)
✅ Semua view < 150 baris (komponen diekstrak)
✅ Component library terpusat di resources/views/components/
✅ Dark mode toggle (class-based Tailwind)
✅ Mobile-first responsive (375px → 1920px)
✅ Lighthouse score ≥ 90 (semua page)
✅ 0 duplikasi kode antar view
✅ Desain konsisten: 1 layout, 1 sidebar, 1 navbar
```

---

## 📐 DESIGN PHILOSOPHY v7.5

### Prinsip:
```
1. CLEAN FIRST      → Whitespace is not waste
2. ROLE-AWARE       → Tiap role dapat konteks berbeda
3. DATA-FORWARD     → Angka & chart = hero element
4. ACTION-ORIENTED  → Tombol aksi selalu visible
5. MOBILE-READY     → Ops tim pakai HP di lapangan
```

### Visual Identity:
```
Palet:       Material Design 3 (sudah ada di v7.2) + ekstensi
Aksen:       Blue-600 (#2563eb) → Primary
Font:        Inter (sudah dipakai)
Border:      Rounded-lg (8px) konsisten
Shadow:      shadow-sm untuk card, shadow-md untuk modal
Density:     Compact tapi breathing (bukan padat)
```

---

## 📅 TIMELINE v7.5 (SOLO DEVELOPER)

```
FASE 0: Foundation Setup          → Hari 1–2    (2 hari)
FASE 1: Design System & Components → Hari 3–7   (5 hari)
FASE 2: Dashboard Overhaul        → Hari 8–12   (5 hari)
FASE 3: Core Modules Redesign     → Hari 13–22  (10 hari)
FASE 4: Advanced Modules          → Hari 23–28  (6 hari)
FASE 5: Testing & Polish          → Hari 29–33  (5 hari)
FASE 6: Deployment                → Hari 34–35  (2 hari)
─────────────────────────────────────────────────────────
TOTAL:                                           35 hari
```

---

## 🔢 FASE DETAIL

### FASE 0: Foundation Setup (Hari 1–2)
```
☐ Install Vite + Tailwind CSS via NPM (bukan CDN)
☐ Setup tailwind.config.js dengan design tokens v7.5
☐ Setup Alpine.js via NPM
☐ Setup Chart.js via NPM
☐ Update app.css dan app.js
☐ Hapus semua CDN link dari layouts
☐ Verify npm run dev jalan
☐ Commit: "feat(v7.5): migrate from CDN to Vite build pipeline"
```

### FASE 1: Design System & Components (Hari 3–7)
```
☐ Buat resources/views/components/ui/ folder
☐ Komponen: button.blade.php (5 variants)
☐ Komponen: card.blade.php (3 variants)
☐ Komponen: stat-card.blade.php
☐ Komponen: alert.blade.php (4 types)
☐ Komponen: badge.blade.php
☐ Komponen: table-wrapper.blade.php
☐ Komponen: modal.blade.php
☐ Komponen: form-input.blade.php
☐ Komponen: form-select.blade.php
☐ Komponen: page-header.blade.php
☐ Komponen: empty-state.blade.php
☐ Redesign layouts/app.blade.php (navbar + sidebar)
☐ Dark mode toggle implementation
☐ Commit: "feat(v7.5): component library + design system"
```

### FASE 2: Dashboard Overhaul (Hari 8–12)
```
☐ GM Dashboard — Football Manager style (refactor komponen)
☐ Sales Dashboard — Funnel + target tracking
☐ Ops Dashboard — Fleet real-time status
☐ Finance Dashboard — Revenue summary
☐ Manager Dashboard — Team KPI view
☐ Director Dashboard — Executive summary
☐ Commit: "feat(v7.5): all 6 dashboards redesigned"
```

### FASE 3: Core Modules (Hari 13–22)
```
☐ Bookings — List, Create, Edit, Show (10 view files)
☐ Clients — CRM cards + detail view
☐ Fleet — Vehicle list + status indicators
☐ Pipeline — Kanban-style opportunity board
☐ Analytics — Chart-heavy dashboard
☐ Commit per modul selesai
```

### FASE 4: Advanced Modules (Hari 23–28)
```
☐ Finance — Invoice + payment list
☐ Approvals — Approval workflow UI
☐ KPI — Target vs actual visual
☐ Products — Catalog grid view
☐ Subscriptions — Recurring list
☐ Vouchers — Voucher management
☐ Maintenance — Log table
☐ Activities — Timeline view
☐ Commit per modul selesai
```

### FASE 5: Testing & Polish (Hari 29–33)
```
☐ Lighthouse audit semua 20+ page (target ≥ 90)
☐ Responsive test (375px, 768px, 1024px, 1920px)
☐ Dark mode test semua page
☐ Cross-browser (Chrome, Safari, Firefox)
☐ Pest tests masih green (93 tests)
☐ Fix semua visual bug
☐ Performance optimization (lazy load, pagination)
☐ Commit: "fix(v7.5): polish + performance"
```

### FASE 6: Deployment (Hari 34–35)
```
☐ Deploy ke Railway.app
☐ Setup PostgreSQL production
☐ Environment variables
☐ Run migrations + seeders
☐ Smoke test semua role
☐ Share live URL
☐ Tag release: v7.5.0
```

---

## 🧩 KOMPONEN YANG DIBANGUN

### UI Primitives (Atoms):
```
button.blade.php       → 5 variants (primary, secondary, danger, ghost, link)
badge.blade.php        → 6 variants (status, role, priority)
avatar.blade.php       → Initials-based, 3 sizes
icon.blade.php         → Material Symbols wrapper
divider.blade.php      → Horizontal separator
spinner.blade.php      → Loading state
tooltip.blade.php      → Hover info
```

### Form Components (Molecules):
```
form-input.blade.php   → Text, number, date, email
form-select.blade.php  → Single + multi select
form-textarea.blade.php
form-checkbox.blade.php
form-radio-group.blade.php
form-error.blade.php   → Error display
form-label.blade.php
```

### Data Display (Molecules):
```
stat-card.blade.php    → KPI card dengan trend
chart-card.blade.php   → Chart.js wrapper
table-wrapper.blade.php → Responsive table
table-row-action.blade.php
empty-state.blade.php
pagination.blade.php
```

### Layout (Organisms):
```
page-header.blade.php  → Title + breadcrumb + actions
card.blade.php         → Content card
modal.blade.php        → Dialog/modal
alert.blade.php        → Notification
sidebar-item.blade.php → Nav item
dropdown-menu.blade.php
```

---

## 🎨 DESIGN TOKENS v7.5

### Colors:
```javascript
// tailwind.config.js
colors: {
  brand: {
    50:  '#eff6ff',
    100: '#dbeafe',
    500: '#3b82f6',
    600: '#2563eb',   // PRIMARY
    700: '#1d4ed8',
    900: '#1e3a8a',
  },
  success: '#10b981',
  warning: '#f59e0b',
  danger:  '#ef4444',
  info:    '#06b6d4',
}
```

### Spacing:
```
xs:  4px   (gap-1)
sm:  8px   (gap-2)
md:  16px  (gap-4)
lg:  24px  (gap-6)
xl:  32px  (gap-8)
2xl: 48px  (gap-12)
```

### Typography:
```
font-sans: 'Inter', system-ui, sans-serif
text-xs:   12px / 16px
text-sm:   14px / 20px   ← default body
text-base: 16px / 24px
text-lg:   18px / 28px
text-xl:   20px / 28px
text-2xl:  24px / 32px   ← section heading
text-3xl:  30px / 36px   ← page title
```

### Border Radius:
```
rounded-sm:  4px   (badge)
rounded:     6px   (input)
rounded-lg:  8px   (card)
rounded-xl:  12px  (modal)
rounded-full:50%   (avatar)
```

---

## 📊 SUCCESS METRICS

| Metric | v7.2 (Sekarang) | v7.5 (Target) |
|--------|-----------------|---------------|
| Lighthouse Performance | ~70 | ≥ 90 |
| Lighthouse Accessibility | ~80 | ≥ 95 |
| Largest view file | 436 baris | ≤ 150 baris |
| Duplikasi kode | Tinggi | Minimal |
| Mobile usable | Partial | Fully |
| Dark mode | Tidak ada | Complete |
| CDN dependencies | 4 CDN links | 0 (Vite) |
| Component coverage | ~10% | ≥ 80% |
| Build time (npm) | N/A | < 5 detik |
| Page load (local) | ~1.5s | < 0.5s |

---

## ⚠️ RISIKO & MITIGASI

| Risiko | Probabilitas | Mitigasi |
|--------|-------------|---------|
| Tailwind Vite config konflik | Medium | Test di branch terpisah |
| View breakdown merusak route | Low | Unit test tetap green |
| Dark mode tidak konsisten | Medium | Buat checklist per komponen |
| Timeline molor (solo dev) | High | Prioritaskan FASE 0–2 dulu, ship early |
| Chart.js NPM vs CDN API beda | Low | Verifikasi sebelum migrate |

---

## 🚀 DEFINISI DONE

v7.5 dianggap **SELESAI** jika:

```
✅ npm run build berhasil tanpa error
✅ 0 CDN link di semua view
✅ Lighthouse ≥ 90 di halaman dashboard
✅ Dark mode berjalan di semua page
✅ Mobile responsive (375px) semua page
✅ 93+ Pest tests masih passing
✅ Live demo di Railway.app
✅ Commit history rapi dengan tag v7.5.0
```

---

*Masterplan ini adalah dokumen hidup. Update setiap akhir fase.*  
*Dibuat: June 2026 | Golden Bird CRM Project*
