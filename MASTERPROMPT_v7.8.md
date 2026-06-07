# MASTERPROMPT v7.8 — Golden Bird CRM
> Copy this entire file to start a new Claude Code session with full project context.

---

## 🎯 Project Overview

**Golden Bird Group — B2B Fleet Management SaaS Demo**
- **Live URL**: https://goldenbirdcrm.onrender.com
- **Repo**: https://github.com/adith92/BBCRM-By-Claude
- **Branch**: `main` (auto-deploys to Render on push)
- **Status**: LIVE ✅ | v7.8 deployed

### Quick Login (Demo)
- Director: `director@goldenbird.co.id` / `password123`
- GM: `gm@goldenbird.co.id` / `password123`
- Manager: `manager@goldenbird.co.id` / `password123`
- Sales: `sales@goldenbird.co.id` / `password123`
- Operational: `operational@goldenbird.co.id` / `password123`
- Finance: `finance@goldenbird.co.id` / `password123`

---

## 🛠 Tech Stack

```
Backend:     Laravel 12 + PHP 8.4 + Blade templates
Frontend:    Vite + Tailwind CSS 4 + Alpine.js 3 + Chart.js 4 + SortableJS
Database:    SQLite (Render free tier, no external DB)
Auth:        6 roles RBAC via Laravel Gates + Middleware
Build:       Docker multi-stage (node:22-alpine → php:8.4-fpm-alpine)
Runtime:     supervisord managing nginx + php-fpm
Deploy:      Render (auto-deploy from GitHub main, health check: /up)
```

---

## 📁 Key Files

```
golden-bird-crm/
├── Dockerfile                    ← Multi-stage build (DO NOT MODIFY without testing)
├── render.yaml                   ← Render deployment config + env vars
├── CHANGELOG.md                  ← Version history
│
├── app/
│   ├── Http/Controllers/         ← DashboardController, RevenueController, etc.
│   └── Models/                   ← User, Client, Fleet, Booking, etc.
│
├── resources/
│   ├── views/
│   │   ├── auth/login.blade.php  ← Login page (Armada image + 1-click demo)
│   │   ├── dashboard/            ← director.blade.php, gm.blade.php, etc.
│   │   ├── components/           ← sidebar.blade.php, topbar.blade.php
│   │   └── layouts/app.blade.php ← Main layout (dark/light mode, Alpine.store)
│   ├── js/app.js                 ← Alpine.js stores, Chart.js, ⌘K shortcuts
│   └── css/app.css               ← Tailwind + CSS custom properties
│
├── routes/web.php                ← All routes (role-gated)
└── database/
    ├── migrations/               ← Schema definitions
    └── seeders/DatabaseSeeder.php ← Demo data (6 users, clients, fleet, bookings)
```

---

## ✅ Phase Status

```
Phase 1 (Days 1-2):    ✅ Foundation — Vite, Tailwind, Alpine.js
Phase 2 (Days 3-7):    ✅ Component Library (20+ components)
Phase 3 (Days 8-12):   ✅ Director + GM dashboards dark theme
Phase 4 (Days 12-15):  ✅ Sales Pipeline Kanban (SortableJS drag-drop)
Phase 4.5:             ✅ Command Center UX — dark/light mode, ⌘K, charts, FAB
Phase 5:               🔄 NEXT — Dark theme for list pages (clients, bookings, fleet, finance)
Phase 6:               🔄 Custom error pages (404, 500, 403)
Phase 7:               🔄 Mobile polish + Lighthouse ≥90
Phase 8:               🔄 Full test suite + release v7.5.0
```

---

## 🔧 Deployment Notes (Critical)

### Dockerfile (DO NOT BREAK)
- Stage 1: `node:22-alpine` — Vite build (`npm run build`)
- Stage 2: `php:8.4-fpm-alpine` — Runtime
- APK packages: `sqlite-dev` (NOT `sqlite` — needs pkg-config headers for pdo_sqlite)
- PHP extensions: `pdo pdo_sqlite mbstring zip bcmath opcache`
- Do NOT add `tokenizer` or `xml` — already built-in to php:8.4-fpm-alpine
- DB seeding runs async: `(php artisan db:seed --force || true) &` (prevents health check timeout)
- Health check: Render polls `/up` during startup

### render.yaml (Key Settings)
- `healthCheckPath: /up` — Laravel's built-in health endpoint
- `APP_URL: https://goldenbirdcrm.onrender.com` — must include `https://` or CSRF/sessions break
- `DB_DATABASE: /var/www/html/database/database.sqlite`
- `SESSION_DRIVER: file`, `CACHE_STORE: file` (no Redis on free tier)

### Common Issues & Fixes
| Issue | Fix |
|-------|-----|
| Deploy times out / "Update Failed" | db:seed blocking → run async with `&` |
| HTTP 500 on login | APP_URL missing `https://` prefix |
| Docker build fails `sqlite3 not found` | Use `sqlite-dev` not `sqlite` in apk |
| Docker build fails `zend_language_parser` | Remove `tokenizer xml` from docker-php-ext-install |
| Git index.lock | `rm -f .git/index.lock` |

---

## 🎨 Design System

### Colors
```
Primary:     #0052cc (Bluebird Blue) / #0066ff
Dark BG:     #0f172a (slate-900)
Card Dark:   #1e293b (slate-800)
Text Light:  #f8fafc (slate-50)
Accent:      #3b82f6 (blue-500)
Success:     #22c55e (green-500)
Warning:     #f59e0b (amber-500)
```

### Component Inventory
- `x-sidebar` — collapsible, role-aware, dark/light
- `x-topbar` — search, notifications, dark mode toggle, user menu
- `CRM_Theme` Alpine store — dark/light mode, persisted
- `CRM_Keys` Alpine store — ⌘K command palette
- `CRM_Notif` Alpine store — toast notifications
- `CRM_Confetti` Alpine store — celebration effects
- KPI cards, revenue charts (Chart.js), health score badges
- Kanban board (SortableJS) with drag-drop deal cards

---

## 🗺 Pending Work (Phase 5+)

### Phase 5 — List Pages Dark Theme
Target files:
- `resources/views/clients/index.blade.php`
- `resources/views/bookings/index.blade.php`
- `resources/views/fleet/index.blade.php`
- `resources/views/finance/index.blade.php`

Each should have:
- Dark table with `bg-slate-800` rows, `hover:bg-slate-700`
- Search + filter bar
- Status badges (color-coded)
- Pagination with dark theme
- Mobile responsive (horizontal scroll on small screens)

### Phase 6 — Custom Error Pages
- `resources/views/errors/404.blade.php`
- `resources/views/errors/500.blade.php`
- `resources/views/errors/403.blade.php`
- All with Golden Bird branding + back button

### Phase 7 — Mobile Polish
- Sidebar: swipe-to-open on mobile
- Tables: card view on mobile (<640px)
- Charts: simplified on mobile
- FAB: position fixed bottom-right

---

## 🚀 Dev Commands

```bash
# Local development
cd golden-bird-crm
npm install && npm run dev          # terminal 1
php artisan serve                   # terminal 2
open http://localhost:8000

# Local Docker test (mirrors Render exactly)
docker build -t golden-bird-crm-test .
docker run -p 8080:8080 golden-bird-crm-test
open http://localhost:8080

# Deploy (Render auto-deploys on push to main)
git add -A && git commit -m "feat: ..." && git push origin main

# Check deployment
curl -I https://goldenbirdcrm.onrender.com/login
# → HTTP/2 200 means live ✅
```

---

## 📋 Session Checklist

Before starting work:
1. ✅ Read this MASTERPROMPT_v7.8.md
2. ✅ Check CHANGELOG.md for recent changes
3. ✅ Confirm live URL is up: `curl -I https://goldenbirdcrm.onrender.com/up`
4. 🔄 Pick next phase from Phase Status above

When finishing work:
1. Test locally if possible
2. `git push origin main`
3. Wait ~3 min for Render deploy
4. Verify: `curl -I https://goldenbirdcrm.onrender.com/login` → 200
5. Update CHANGELOG.md with changes
