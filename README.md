# 🚕 Golden Bird CRM Demo MVP - BlueERP Fleet Management

A highly premium B2B Fleet Management and CRM system built for **Golden Bird (Bluebird Group)**. Designed using a bespoke visual language matching the **BlueERP design system** with sleek radial gradients, Material Symbols, and an extremely responsive multi-level drill-down experience.

🌐 **Live Demo:** [https://golden-bird-crm-production.up.railway.app](https://golden-bird-crm-production.up.railway.app)

---

## 🚀 Tech Stack & Architecture
* **Core**: Laravel 12 + Livewire 3 (Single-file/Anonymous component structure)
* **Styling**: Tailwind CSS 3 (harmonious deep blue palettes `#003887`, responsive layouts, and glassmorphism components)
* **RBAC Engine**: Spatie Laravel-Permission (Role-based access controls for `gm`, `sales`, `finance`, and `ops`)
* **Database**: MySQL with InnoDB transactional execution & pessimistic row-level locking

---

## 🌟 Key Features Implemented

### 👥 Multi-Role Based Portals & Dashboards
* **General Manager (`gm`)**: Access to all directories, aggregate stats, and high-level charts like **Revenue This Month** from real invoice payment history.
* **Sales Officer (`sales`)**: Fleet overview and customer relationship pipeline.
* **Finance Admin (`finance`)**: Invoice lists, outstanding payments ledger, and an interactive payment modal.
* **Operations Head (`ops`)**: Booking creation suite with overlap protection and fleet status manual override controls.

### 🔍 Real Multi-level Drill-down (Revised)
Replaced restrictive inline index popups with deep-linked dedicated pages for ultimate clarity:
* **Invoice Ledger** (`/invoices/{invoice}`) ➔ Drill-down to **Client Profile** or **Vehicle Profile** in one click.
* **Fleet Profile** (`/fleet/{vehicle}`) ➔ View complete vehicle details, real-time status tracker, and **Booking History** with clickable client references.
* **Client Profile** (`/clients/{client}`) ➔ [NEW] Premium Platinum dashboard showing active contracted fleets, recent activity timelines, and direct links back to outstanding invoices.

### 🔒 Secure Transactional Booking Engine
* Operations team can book vehicles ONLY when status is `available`.
* Powered by `DB::transaction()` combined with pessimistic locking (`sharedLock` & `lockForUpdate`) to guarantee zero booking overlaps under high concurrent traffic.
* Automatically updates vehicle status to `po` (Pre-Ordered) and instantiates a linked billing invoice.

### 💰 Ledger & Payment Collection Modal
* Interactive Livewire modal in the Invoice screen that validates payment inputs (`amount <= remaining_balance`).
* Auto-calculates payment status: sets to `paid` if remaining balance reaches 0, or `partially_paid` for split-payments.

---

## ⚡ Performance Optimizations Applied

To solve typical WSL mount and localhost latency issues:
1. **Multi-Worker Server Activation**:
   Added `PHP_CLI_SERVER_WORKERS=8` in `.env` to enable multi-threaded concurrent request handling (allowing CSS, JS, and image assets to load in parallel without queuing).
2. **Bootstrap Optimization**:
   Configured optimized autoloader cache and cleared view/route compile cache via `php artisan optimize:clear`.

---

## 🔑 Demo Access Credentials
Use password `password` for all users:
* **General Manager**: `gm@bluebird.co.id`
* **Sales Officer**: `sales@bluebird.co.id`
* **Finance Admin**: `finance@bluebird.co.id`
* **Operations Head**: `ops@bluebird.co.id`

---

## 🛠️ How to Install and Run Locally

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/adith92/BBCRM-By-QWEN.git
   cd BBCRM-By-QWEN
   ```
2. **Setup Dependencies**:
   ```bash
   composer install
   npm install && npm run build
   ```
3. **Database Migration & Seeding**:
   ```bash
   # Setup your DB_DATABASE inside .env first, then run:
   php artisan migrate:fresh --seed
   ```
4. **Launch Dev Server**:
   ```bash
   php artisan serve
   ```
   Open `http://localhost:8000` in your web browser.
