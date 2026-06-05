# 🖼️ GOLDEN BIRD CRM v7.5 — WIREFRAME DOCUMENTATION

> **Format:** ASCII wireframe + spesifikasi visual per screen  
> **Coverage:** 12 screen utama (semua role + module kritis)  
> **Responsive:** Mobile (375px), Tablet (768px), Desktop (1440px)

---

## 📐 GRID SYSTEM & BREAKPOINTS

```
Mobile  (sm):  375px  — 1 column
Tablet  (md):  768px  — 2 columns
Desktop (lg):  1024px — 12 columns (sidebar fixed)
Wide    (xl):  1440px — 12 columns + sidebar 260px
```

---

## 🗂️ SCREEN 1: LAYOUT SHELL (Semua Page)

### Desktop (1440px):
```
┌────────────────────────────────────────────────────────────────────┐
│ TOPBAR                                                         [80px]│
│ [Hamburger] [Logo: Golden Bird CRM]          [🔔] [🌙] [Avatar ▼] │
├──────────┬─────────────────────────────────────────────────────────┤
│ SIDEBAR  │                                                          │
│ [260px]  │  MAIN CONTENT AREA                                       │
│          │  padding: 24px                                           │
│ ● Dashboard              │                                          │
│ ● Bookings               │  <-- @yield('content') renders here      │
│ ● Clients                │                                          │
│ ● Fleet                  │                                          │
│ ● Analytics              │                                          │
│ ● Finance                │                                          │
│ ─────────                │                                          │
│ ● Pipeline               │                                          │
│ ● Approvals              │                                          │
│ ● KPI                    │                                          │
│ ● Products               │                                          │
│ ─────────                │                                          │
│ [Avatar]                 │                                          │
│ Adith Suryadi            │                                          │
│ GM                       │                                          │
│ [Logout]                 │                                          │
└──────────┴───────────────────────────────────────────────────────-─┘
```

### Mobile (375px):
```
┌─────────────────────────────────┐
│ TOPBAR                     [64px]│
│ [☰] [GB Logo]    [🔔] [Avatar]  │
├─────────────────────────────────┤
│                                  │
│  MAIN CONTENT (full width)       │
│  padding: 16px                   │
│                                  │
│  <-- @yield('content')           │
│                                  │
└─────────────────────────────────┘

SIDEBAR (hidden, drawer dari kiri):
┌──────────────────────────────────┐
│ ◀ OVERLAY (bg-black/50)          │
│ ┌──────────────────┐             │
│ │ [X] Golden Bird  │             │
│ │                  │             │
│ │ ● Dashboard      │             │
│ │ ● Bookings       │             │
│ │ ● Clients        │             │
│ │ ● Fleet          │             │
│ │ ● Analytics      │             │
│ │                  │             │
│ │ [Avatar] Adith   │             │
│ │ [Logout]         │             │
│ └──────────────────┘             │
└──────────────────────────────────┘
```

**Spesifikasi:**
```
Sidebar:
  width:       260px (desktop fixed) | 280px (mobile drawer)
  background:  white / dark: gray-800
  border:      1px right, gray-200 / dark: gray-700

Topbar:
  height:      64px (mobile) | 72px (desktop)
  background:  white / dark: gray-800
  shadow:      shadow-sm

Nav Item:
  height:      44px
  padding:     0 12px
  hover:       bg-gray-100 rounded-lg
  active:      bg-brand-50 text-brand-600 rounded-lg
  icon size:   20px Material Symbols
```

---

## 🗂️ SCREEN 2: GM DASHBOARD

### Desktop:
```
┌──────────────────────────────────────────────────────────────────────┐
│ PAGE HEADER                                                           │
│ Dashboard                           [+ Booking Baru] [📊 Export]     │
│ Selamat datang, Adith • Jumat, 5 Juni 2026                           │
├──────────────────────────────────────────────────────────────────────┤
│ KPI CARDS (4 kolom)                                                   │
│ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐                │
│ │ 📅       │ │ 💰       │ │ 🚌       │ │ 🎯       │                │
│ │ BOOKING  │ │ REVENUE  │ │ ARMADA   │ │ TARGET   │                │
│ │   2,847  │ │ Rp 4.2M  │ │ 142/156  │ │   87%    │                │
│ │ ↑12% lbl │ │ ↑8% lbl  │ │ 2 maint  │ │ ↑On Track│                │
│ └──────────┘ └──────────┘ └──────────┘ └──────────┘                │
├──────────────────────────────────────────────────────────────────────┤
│ CHARTS ROW                                                            │
│ ┌──────────────────────────────────┐ ┌───────────────────┐          │
│ │ Revenue Trend (Line Chart)        │ │ Status Booking     │          │
│ │                                   │ │ (Donut Chart)      │          │
│ │  [line going up ↗]               │ │                    │          │
│ │                                   │ │    [donut]         │          │
│ │  Jan Feb Mar Apr Mei Jun          │ │                    │          │
│ │  ─────────────────────            │ │ ■ Active    ■ Done │          │
│ │  Rp 2.1M   3.4M   4.2M           │ │ ■ Pending  ■ Cncl  │          │
│ └──────────────────────────────────┘ └───────────────────┘          │
├──────────────────────────────────────────────────────────────────────┤
│ BOTTOM ROW                                                            │
│ ┌─────────────────────────────┐ ┌────────────────────────────────┐  │
│ │ 🏆 TOP SALES BULAN INI      │ │ ⏰ AKTIVITAS TERBARU           │  │
│ │                             │ │                                │  │
│ │ # Nama         Rev    Target│ │ ● Booking #B001 dibuat        │  │
│ │ 1 Sarah     5.2M  104%  ✅  │ │   15 menit lalu               │  │
│ │ 2 Budi      4.8M   96%  🔔  │ │                               │  │
│ │ 3 Rina      3.1M   62%  ⚠️  │ │ ● Client Mitra Jaya diupdate  │  │
│ │ 4 Dani      2.9M   58%  ⚠️  │ │   1 jam lalu                  │  │
│ │ 5 Tri       2.5M   50%  🔴  │ │                               │  │
│ └─────────────────────────────┘ │ ● Invoice #INV-0042 lunas     │  │
│                                  │   2 jam lalu                  │  │
│                                  └────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────────────┘
```

### Mobile (375px):
```
┌─────────────────────────────────┐
│ Dashboard                        │
│ Selamat datang, Adith            │
├─────────────────────────────────┤
│ KPI CARDS (2 kolom)             │
│ ┌───────────┐ ┌───────────┐     │
│ │ 📅 2,847  │ │ 💰 4.2M   │     │
│ │ Booking   │ │ Revenue   │     │
│ │ ↑12%      │ │ ↑8%       │     │
│ └───────────┘ └───────────┘     │
│ ┌───────────┐ ┌───────────┐     │
│ │ 🚌 142    │ │ 🎯 87%    │     │
│ │ Armada    │ │ Target    │     │
│ └───────────┘ └───────────┘     │
├─────────────────────────────────┤
│ Revenue Trend                    │
│ [Chart.js line, full width]      │
│ [height: 200px]                  │
├─────────────────────────────────┤
│ Status Booking (Donut)           │
│ [Centered, 180px height]         │
├─────────────────────────────────┤
│ Top Sales                        │
│ [Scrollable table]               │
├─────────────────────────────────┤
│ Aktivitas Terbaru                │
│ [List view]                      │
└─────────────────────────────────┘
```

---

## 🗂️ SCREEN 3: BOOKINGS INDEX

### Desktop:
```
┌──────────────────────────────────────────────────────────────────────┐
│ Daftar Booking                              [+ Buat Booking Baru]     │
├──────────────────────────────────────────────────────────────────────┤
│ FILTER BAR                                                            │
│ [🔍 Cari booking...] [Status ▼] [Tgl Mulai] [Tgl Akhir] [Filter] [Reset]│
├──────────────────────────────────────────────────────────────────────┤
│ TABEL                                                                 │
│ ┌──┬────────────┬──────────┬────────────┬──────────┬───────┬───────┐│
│ │#  │ Client     │ Tipe     │ Tgl        │ Status   │ Sales │ Total ││
│ ├──┼────────────┼──────────┼────────────┼──────────┼───────┼───────┤│
│ │1  │ PT. Mitra  │ Airport  │ 10 Jun '26 │ [Active] │ Sarah │12.5M ││
│ │   │ Jaya       │ Transfer │            │          │       │ [⋯]  ││
│ ├──┼────────────┼──────────┼────────────┼──────────┼───────┼───────┤│
│ │2  │ CV. Trans  │ City     │ 8 Jun '26  │[Pending] │ Budi  │ 7.2M ││
│ │   │ Global     │ Tour     │            │          │       │ [⋯]  ││
│ ├──┼────────────┼──────────┼────────────┼──────────┼───────┼───────┤│
│ │3  │ Logistik   │ Charter  │ 5 Jun '26  │[Done ✓]  │ Rina  │18.0M ││
│ │   │ Perdana    │ Bus      │            │          │       │ [⋯]  ││
│ └──┴────────────┴──────────┴────────────┴──────────┴───────┴───────┘│
│                                            Showing 1-20 of 847       │
│                              [< Prev] [1] [2] [3] ... [43] [Next >]  │
└──────────────────────────────────────────────────────────────────────┘
```

**Action Menu [⋯]:**
```
┌─────────────────┐
│ 👁 Lihat Detail │
│ ✏️ Edit         │
│ 📄 Invoice      │
│ ─────────────── │
│ 🗑 Hapus        │
└─────────────────┘
```

**Badge Colors:**
```
Active   → bg-blue-100  text-blue-800
Pending  → bg-amber-100 text-amber-800
Done     → bg-green-100 text-green-800
Cancelled→ bg-red-100   text-red-800
```

---

## 🗂️ SCREEN 4: BOOKING CREATE FORM

### Desktop (2-column layout):
```
┌──────────────────────────────────────────────────────────────────────┐
│ ← Kembali   Buat Booking Baru                                         │
├────────────────────────────────────────┬─────────────────────────────┤
│ FORM SECTION (LEFT 2/3)                │ SUMMARY (RIGHT 1/3)         │
│                                        │                              │
│ ┌─ CLIENT ────────────────────────┐   │ ┌─ RINGKASAN BOOKING ─────┐ │
│ │ Client *                         │   │ │                          │ │
│ │ [🔍 Cari client...          ▼]  │   │ │ Client: (belum dipilih)  │ │
│ │                                  │   │ │ Tipe:   (belum dipilih)  │ │
│ └──────────────────────────────────┘   │ │ Tgl:    (belum dipilih)  │ │
│                                        │ │ ─────────────────────    │ │
│ ┌─ DETAIL BOOKING ────────────────┐   │ │ Subtotal:    Rp 0        │ │
│ │ Tipe Layanan *                   │   │ │ Diskon:      Rp 0        │ │
│ │ [Pilih tipe...              ▼]  │   │ │ PPN (11%):   Rp 0        │ │
│ │                                  │   │ │ ─────────────────────    │ │
│ │ Tanggal Mulai *  Tanggal Selesai*│   │ │ TOTAL:       Rp 0        │ │
│ │ [📅 10/06/2026]  [📅 15/06/2026]│   │ │                          │ │
│ │                                  │   │ ┌──────────────────────┐  │ │
│ │ Armada / Produk *                │   │ │ [✓ Buat Booking]      │  │ │
│ │ [Pilih produk...            ▼]  │   │ └──────────────────────┘  │ │
│ │                                  │   │                          │ │
│ │ Jumlah Unit     Harga Satuan     │   │ ⓘ Booking akan otomatis  │ │
│ │ [1           ]  [Rp 5.000.000 ] │   │   di-assign ke sales Anda │ │
│ └──────────────────────────────────┘   │ └──────────────────────────┘ │
│                                        │                              │
│ ┌─ PENUGASAN ─────────────────────┐   │                              │
│ │ Sales (hanya GM/Manager)         │   │                              │
│ │ [Pilih Sales...             ▼]  │   │                              │
│ │                                  │   │                              │
│ │ Catatan                          │   │                              │
│ │ [                              ] │   │                              │
│ │ [                              ] │   │                              │
│ └──────────────────────────────────┘   │                              │
└────────────────────────────────────────┴─────────────────────────────┘
```

### Mobile (single column):
```
┌─────────────────────────────────┐
│ ← Kembali    Buat Booking        │
├─────────────────────────────────┤
│ CLIENT                           │
│ [Cari client...            ▼]   │
├─────────────────────────────────┤
│ DETAIL BOOKING                   │
│ Tipe Layanan                     │
│ [Pilih tipe...             ▼]   │
│ Tanggal Mulai                    │
│ [📅 10/06/2026]                  │
│ Tanggal Selesai                  │
│ [📅 15/06/2026]                  │
│ Produk                           │
│ [Pilih produk...           ▼]   │
│ Jumlah        Harga              │
│ [1      ]  [Rp 5.000.000 ]      │
├─────────────────────────────────┤
│ RINGKASAN                        │
│ Subtotal:    Rp 5.000.000        │
│ PPN (11%):   Rp 550.000          │
│ ─────────────────────────────── │
│ TOTAL:       Rp 5.550.000        │
├─────────────────────────────────┤
│ [        Buat Booking        ]   │
└─────────────────────────────────┘
```

---

## 🗂️ SCREEN 5: BOOKING DETAIL / SHOW

### Desktop:
```
┌──────────────────────────────────────────────────────────────────────┐
│ ← Kembali   Booking #BK-2024-001                                      │
│                              [✏️ Edit] [📄 Invoice] [✕ Cancel]        │
├──────────────────────────────────────────────────────────────────────┤
│ HEADER STATUS BAR                                                     │
│ [● Active] Booking sedang berlangsung                                 │
├──────────────────────────────────────────────────────────────────────┤
│ LEFT COLUMN (2/3)                     RIGHT COLUMN (1/3)             │
│                                                                       │
│ ┌─ DETAIL BOOKING ──────────────┐   ┌─ TIMELINE STATUS ──────────┐  │
│ │ Client:    PT. Mitra Jaya     │   │                              │  │
│ │ Tipe:      Airport Transfer   │   │ ✅ Dibuat (10 Jun, 09:00)   │  │
│ │ Mulai:     10 Juni 2026       │   │ |                            │  │
│ │ Selesai:   15 Juni 2026       │   │ ✅ Dikonfirmasi (10 Jun)     │  │
│ │ Produk:    Executive Van      │   │ |                            │  │
│ │ Unit:      3                  │   │ 🔵 Berlangsung (aktif)       │  │
│ │ Sales:     Sarah Dewi         │   │ |                            │  │
│ │ Catatan:   -                  │   │ ○ Selesai (belum)            │  │
│ └───────────────────────────────┘   │ |                            │  │
│                                     │ ○ Invoice (belum)            │  │
│ ┌─ FINANSIAL ───────────────────┐   └──────────────────────────────┘  │
│ │ Subtotal:      Rp 15.000.000  │                                     │
│ │ Diskon (5%):  -Rp 750.000    │   ┌─ ARMADA DITUGASKAN ──────────┐  │
│ │ PPN (11%):    +Rp 1.567.500  │   │ [🚌] B 1234 AB               │  │
│ │ ─────────────────────────    │   │ Toyota HiAce | Aktif          │  │
│ │ TOTAL:         Rp 15.817.500 │   │ Driver: Pak Hendra            │  │
│ └───────────────────────────────┘   └──────────────────────────────┘  │
│                                                                       │
│ ┌─ INVOICE ─────────────────────────────────────────────────────────┐ │
│ │ #INV-2024-001 | Rp 15.817.500 | 🟡 Belum Bayar | [Lihat Invoice] │ │
│ └───────────────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────────────┘
```

---

## 🗂️ SCREEN 6: FLEET INDEX

### Desktop (Grid View):
```
┌──────────────────────────────────────────────────────────────────────┐
│ Manajemen Armada                    [+ Tambah Kendaraan] [☰ / ⊞]     │
│ Filter: [Pool ▼] [Status ▼] [Tipe ▼]                                 │
├──────────────────────────────────────────────────────────────────────┤
│ SUMMARY STATS                                                         │
│ [Total: 156] [Active: 142] [Maintenance: 8] [Idle: 6]               │
├──────────────────────────────────────────────────────────────────────┤
│ VEHICLE GRID (4 kolom)                                               │
│ ┌───────────────┐ ┌───────────────┐ ┌───────────────┐ ┌─────────────┐│
│ │ [🚌 icon]     │ │ [🚌 icon]     │ │ [🔧 Maint]    │ │ [🚌 icon]   ││
│ │               │ │               │ │               │ │             ││
│ │ B 1234 AB     │ │ B 5678 CD     │ │ B 9012 EF     │ │ B 3456 GH   ││
│ │ Toyota HiAce  │ │ Toyota Innova │ │ Isuzu Elf     │ │ Mercedez    ││
│ │ [● Active]    │ │ [● Active]    │ │ [⚙ Maint]     │ │ [● Active]  ││
│ │ Driver: Hendra│ │ Driver: Budi  │ │ Scheduled:    │ │ Driver: Yus ││
│ │               │ │               │ │ 10 Jun        │ │             ││
│ │ [Detail] [⋯]  │ │ [Detail] [⋯]  │ │ [Detail] [⋯]  │ │ [Detail][⋯] ││
│ └───────────────┘ └───────────────┘ └───────────────┘ └─────────────┘│
│                                                                       │
│ [+ 6 more vehicles...]                                               │
└──────────────────────────────────────────────────────────────────────┘
```

### Desktop (List View):
```
┌──────────────────────────────────────────────────────────────────────┐
│ ┌──────┬───────────┬──────────┬──────────┬─────────┬──────┬────────┐│
│ │ Plat │ Tipe      │ Pool     │ Status   │ Driver  │ KM   │ Aksi   ││
│ ├──────┼───────────┼──────────┼──────────┼─────────┼──────┼────────┤│
│ │B1234 │ HiAce     │ Jakarta  │ [Active] │ Hendra  │ 82K  │ [⋯]   ││
│ │B5678 │ Innova    │ Jakarta  │ [Active] │ Budi    │ 61K  │ [⋯]   ││
│ │B9012 │ Isuzu Elf │ Bandung  │ [Maint]  │ -       │ 145K │ [⋯]   ││
│ └──────┴───────────┴──────────┴──────────┴─────────┴──────┴────────┘│
└──────────────────────────────────────────────────────────────────────┘
```

---

## 🗂️ SCREEN 7: ANALYTICS DASHBOARD

### Desktop:
```
┌──────────────────────────────────────────────────────────────────────┐
│ Analytics & Laporan                    [📥 Export Excel] [📄 PDF]     │
├──────────────────────────────────────────────────────────────────────┤
│ PERIOD SELECTOR (Tabs)                                               │
│ [Harian] [Mingguan] [Bulanan ✓] [Tahunan]   [📅 Jan 2026 - Jun 2026]│
├──────────────────────────────────────────────────────────────────────┤
│ KPI SUMMARY                                                           │
│ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐                │
│ │ Rp 42.5M │ │   847    │ │   156    │ │   87%    │                │
│ │ Revenue  │ │ Booking  │ │ Armada   │ │ Target   │                │
│ └──────────┘ └──────────┘ └──────────┘ └──────────┘                │
├──────────────────────────────────────────────────────────────────────┤
│ REVENUE TREND                                                         │
│ ┌──────────────────────────────────────────────────────────────────┐ │
│ │                                                    Revenue Trend  │ │
│ │  10M │                              ╱‾‾╲                          │ │
│ │   8M │                        ╱‾‾‾╱    ╲____╱‾                   │ │
│ │   6M │              ╱‾‾‾╲____╱                                    │ │
│ │   4M │   ╱‾‾╲____╱                                                │ │
│ │   2M │___╱                                                         │ │
│ │      Jan  Feb  Mar  Apr  Mei  Jun                                  │ │
│ └──────────────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────────────┤
│ BOTTOM CHARTS                                                         │
│ ┌───────────────────────────────┐ ┌───────────────────────────────┐  │
│ │ Revenue per Sales (Bar)        │ │ Booking Volume (Bar)           │  │
│ │                               │ │                               │  │
│ │ Sarah ████████████ Rp 8.2M    │ │ Airport ████████ 245          │  │
│ │ Budi  ██████████   Rp 7.1M    │ │ Charter ██████   189          │  │
│ │ Rina  ████████     Rp 5.8M    │ │ City Tr ████     132          │  │
│ │ Dani  ██████       Rp 4.2M    │ │ Lainnya ██       81           │  │
│ │ Tri   █████        Rp 3.9M    │ │                               │  │
│ └───────────────────────────────┘ └───────────────────────────────┘  │
└──────────────────────────────────────────────────────────────────────┘
```

---

## 🗂️ SCREEN 8: PIPELINE (KANBAN VIEW)

### Desktop:
```
┌──────────────────────────────────────────────────────────────────────┐
│ Pipeline Penjualan                         [+ Tambah Opportunity]     │
│ Filter: [Sales ▼] [Client ▼] [Periode ▼]                             │
├──────────────────────────────────────────────────────────────────────┤
│ PIPELINE STATS                                                        │
│ Total: Rp 89.2M | 34 Opportunities | Avg Conversion: 42%            │
├──────────────────────────────────────────────────────────────────────┤
│ KANBAN BOARD                                                          │
│ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐  │
│ │ LEAD     │ │QUALIFIED │ │ PROPOSAL │ │NEGOTIAT. │ │ WON/LOST │  │
│ │ 8 opps   │ │ 12 opps  │ │ 7 opps   │ │ 5 opps   │ │ 2/3 opps │  │
│ │ Rp 12M   │ │ Rp 28M   │ │ Rp 21M   │ │ Rp 18M   │ │          │  │
│ ├──────────┤ ├──────────┤ ├──────────┤ ├──────────┤ ├──────────┤  │
│ │┌────────┐│ │┌────────┐│ │┌────────┐│ │┌────────┐│ │┌────────┐│  │
│ ││PT Mitra││ ││CV Trans││ ││Logistik││ ││Mandiri ││ ││🏆 Menang││  │
│ ││Airport ││ ││Charter ││ ││Elf x5  ││ ││Paket A ││ ││PT ABC  ││  │
│ ││Rp 2.5M ││ ││Rp 5.2M ││ ││Rp 8M   ││ ││Rp 12M  ││ ││Rp 4.2M ││  │
│ ││[→Move] ││ ││[→Move] ││ ││[→Move] ││ ││[→Move] ││ │└────────┘│  │
│ │└────────┘│ │└────────┘│ │└────────┘│ │└────────┘│ │          │  │
│ │          │ │          │ │          │ │          │ │┌────────┐│  │
│ │[+ Lead]  │ │          │ │          │ │          │ ││❌ Kalah ││  │
│ │          │ │          │ │          │ │          │ ││Rp 3.1M ││  │
│ │          │ │          │ │          │ │          │ │└────────┘│  │
│ └──────────┘ └──────────┘ └──────────┘ └──────────┘ └──────────┘  │
└──────────────────────────────────────────────────────────────────────┘
```

---

## 🗂️ SCREEN 9: LOGIN PAGE

### Desktop:
```
┌──────────────────────────────────────────────────────────────────────┐
│                                                                       │
│  LEFT: BRAND HERO (1/2)         │  RIGHT: LOGIN FORM (1/2)           │
│                                  │                                    │
│  [🐦 Golden Bird CRM]            │  ┌────────────────────────────┐   │
│                                  │  │                            │   │
│  Enterprise Fleet                │  │   Welcome back 👋          │   │
│  Management System               │  │   Login ke akun Anda       │   │
│                                  │  │                            │   │
│  ✓ Multi-role dashboard          │  │   Email                    │   │
│  ✓ Real-time fleet tracking      │  │   [gm@goldenbird.co.id   ] │   │
│  ✓ Revenue analytics             │  │                            │   │
│  ✓ Sales pipeline                │  │   Password                 │   │
│                                  │  │   [••••••••••••          ] │   │
│  [bg gradient: blue-800 →        │  │                            │   │
│   blue-600]                      │  │   [          Login          ]│   │
│                                  │  │                            │   │
│  ─────────────────               │  │   Demo Accounts:           │   │
│  Quick Login Demo:               │  │   [GM] [Sales] [Ops] [Fin] │   │
│  Klik role untuk auto-fill       │  │                            │   │
│  [GM] [Director] [Sales]         │  │   © 2026 Golden Bird Group │   │
│  [Ops] [Manager] [Finance]       │  └────────────────────────────┘   │
│                                  │                                    │
└──────────────────────────────────────────────────────────────────────┘
```

**Demo Role Cards (1-click login):**
```
┌─────────┐ ┌─────────┐ ┌─────────┐
│ 👤 GM   │ │ 📊 Sales│ │ 🚌 Ops  │
│ Director│ │ Manager │ │ Finance │
│ [Login] │ │ [Login] │ │ [Login] │
└─────────┘ └─────────┘ └─────────┘
```

---

## 🗂️ SCREEN 10: APPROVAL WORKFLOW

### Desktop:
```
┌──────────────────────────────────────────────────────────────────────┐
│ Daftar Approval                                                       │
│ Filter: [Pending ✓] [Disetujui] [Ditolak] [Semua]                   │
├──────────────────────────────────────────────────────────────────────┤
│ ┌─────────────────────────────────────────────────────────────────┐  │
│ │ 🟡 MENUNGGU APPROVAL                                            │  │
│ │                                                                  │  │
│ │ Booking #BK-2024-089 | PT. Sinar Jaya                           │  │
│ │ Diskon 15% (Max allowed: 10%)                                    │  │
│ │ Diajukan oleh: Budi Santoso (Sales) | 2 jam lalu                │  │
│ │ Nilai booking: Rp 25.000.000                                     │  │
│ │                                                                  │  │
│ │ [📋 Lihat Detail]  [✅ Setujui]  [❌ Tolak]                     │  │
│ └─────────────────────────────────────────────────────────────────┘  │
│ ┌─────────────────────────────────────────────────────────────────┐  │
│ │ 🟡 MENUNGGU APPROVAL                                            │  │
│ │                                                                  │  │
│ │ Booking #BK-2024-091 | CV. Berkah Logistik                      │  │
│ │ Diskon 20% (Max allowed: 10%)                                    │  │
│ │ Diajukan oleh: Rina Pertiwi (Sales) | 5 jam lalu                │  │
│ │                                                                  │  │
│ │ [📋 Lihat Detail]  [✅ Setujui]  [❌ Tolak]                     │  │
│ └─────────────────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────────────┘
```

---

## 🗂️ SCREEN 11: KPI / SALES TARGET

### Desktop:
```
┌──────────────────────────────────────────────────────────────────────┐
│ KPI & Target Penjualan              [Juni 2026 ▼]  [+ Set Target]    │
├──────────────────────────────────────────────────────────────────────┤
│ TEAM SUMMARY                                                          │
│ ┌───────────────┐ ┌───────────────┐ ┌───────────────┐               │
│ │ Team Target   │ │ Actual        │ │ Achievement   │               │
│ │ Rp 100M      │ │ Rp 87.5M      │ │    87.5%      │               │
│ │ (5 orang)    │ │               │ │ [On Track]    │               │
│ └───────────────┘ └───────────────┘ └───────────────┘               │
├──────────────────────────────────────────────────────────────────────┤
│ PER SALES BREAKDOWN                                                   │
│ ┌─────────────────────────────────────────────────────────────────┐  │
│ │ Sarah Dewi                                          104% ✅     │  │
│ │ Target: Rp 25M  Actual: Rp 26M                                 │  │
│ │ ████████████████████████████░░░ 104/100                         │  │
│ ├─────────────────────────────────────────────────────────────────┤  │
│ │ Budi Santoso                                         96% 🔔     │  │
│ │ Target: Rp 20M  Actual: Rp 19.2M                               │  │
│ │ ████████████████████████████░░░░ 96/100                         │  │
│ ├─────────────────────────────────────────────────────────────────┤  │
│ │ Rina Pertiwi                                         62% ⚠️     │  │
│ │ Target: Rp 20M  Actual: Rp 12.4M  Gap: Rp 7.6M                │  │
│ │ ████████████████████░░░░░░░░░░░░ 62/100                         │  │
│ └─────────────────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────────────┘
```

**Progress Bar Colors:**
```
≥ 100%  → bg-green-500  (text: "Exceeded ✅")
≥ 80%   → bg-blue-500   (text: "On Track 🔔")
≥ 60%   → bg-amber-500  (text: "Behind ⚠️")
< 60%   → bg-red-500    (text: "Critical 🔴")
```

---

## 🗂️ SCREEN 12: DARK MODE COMPARISONS

### Light Mode:
```
Background:   #f8f9ff (surface)
Card:         #ffffff
Sidebar:      #ffffff
Text:         #0b1c30 (on-surface)
Border:       #e5e7eb (gray-200)
```

### Dark Mode:
```
Background:   #0f172a (gray-950)
Card:         #1e293b (gray-800)
Sidebar:      #1e293b (gray-800)
Text:         #f1f5f9 (gray-100)
Border:       #334155 (gray-700)
```

### Dark Mode Toggle UI:
```
┌──────────────────────────────────────────────────────┐
│ TOPBAR (dark mode)                                    │
│ [☰] [GB]                   [🔔] [☀️ toggle] [Avatar] │
│                                    ↑                  │
│                         Switch to light mode          │
└──────────────────────────────────────────────────────┘
```

---

## 🎨 COMPONENT STATES REFERENCE

### Button States:
```
Normal:   bg-brand-600 text-white
Hover:    bg-brand-700 (darker)
Focus:    ring-2 ring-brand-500 ring-offset-2
Active:   scale-95 (brief press)
Disabled: opacity-50 cursor-not-allowed
Loading:  [spinner icon] + opacity-75
```

### Form Input States:
```
Default:  border-gray-300
Focus:    border-brand-500 ring-1 ring-brand-500
Error:    border-red-500 (+ error message below)
Success:  border-green-500
Disabled: bg-gray-50 opacity-75 cursor-not-allowed
```

### Table Row States:
```
Default:  bg-white
Hover:    bg-gray-50
Selected: bg-brand-50 border-l-2 border-brand-600
```

---

## 📏 SPACING BLUEPRINT

```
Page padding:      24px desktop / 16px mobile
Section gap:       24px
Card padding:      20px (md)
Table cell:        px-4 py-3
Form group gap:    16px
Input height:      40px
Button height:     36px (sm) / 40px (md) / 44px (lg)
Sidebar width:     260px
Topbar height:     64px
```

---

*Wireframe ini adalah visual spec. Implementasi mengacu ke file komponen di resources/views/components/ui/*  
*Dibuat: June 2026 | v7.5 Design System*
