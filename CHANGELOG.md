# CHANGELOG — Golden Bird CRM

All notable changes to this project are documented in this file.

---

## [v7.8] — 2026-06-07

### Features
- **Kanban Pipeline**: SortableJS drag-drop sales pipeline with health scores, deal cards, and live filters
- **Login Redesign v2**: Armada fleet image + 1-click demo login for all 6 roles (director, gm, manager, sales, operational, finance)
- **Command Center UX**: Alpine.js stores, ⌘K shortcuts, Chart.js 4 performance, FAB, toast notifications, konfetti
- **GM Dashboard**: Quick Shortcuts grid (16 modules), KPI cards, revenue charts, dark theme
- **Director Dashboard**: Executive summary, fleet health overview, revenue analytics
- **Analytics Route**: New analytics view with soft colors, 3D buttons, clickable elements

### Fixes
- **Async Database Seeding**: Changed `db:seed` to background process (`&`) to prevent Render health check timeout
- **SQLite Runtime Writable**: Fixed auth errors by ensuring `/var/www/html/database/` is writable at runtime
- **Login Error 500**: Fixed APP_URL missing `https://` prefix in render.yaml causing session/CSRF failures
- **Docker Build**: Changed `sqlite` to `sqlite-dev` apk; removed built-in `tokenizer` and `xml` extensions
- **Revenue Route**: Added missing `revenue.index` route + RoleAccessTest feature test
- **UI Test Status**: Fixed incorrect status value passed in role access test

### Chores
- **Cleanup**: Removed outdated deployment files (DEPLOYMENT.md, DEPLOY_CHECKLIST.md, Procfile, docker/, scripts/)
- **Rebrand**: Bluebird → Golden Bird CRM with updated logo and color palette
- **Alpine.store**: Migrated component state to Alpine.store for cross-component reactivity
- **Chart.js Performance**: Lazy loading, animation disable on mobile, responsive options

---

## [v7.7] — 2026-06-06

### Features
- Alpine.store global state management
- Chart.js 4 performance optimizations
- PHP 8.3 → 8.4 upgrade
- Improved deploy config (render.yaml + Dockerfile)

### Fixes
- Docker: sqlite-dev package, removed built-in PHP extensions from install list
- Health check: /up endpoint (Laravel default)

---

## [v7.5] — 2026-06-05

### Features
- **Command Center**: Dark/light mode toggle, keyboard shortcuts (⌘K), notification bell
- **Kanban Pipeline**: SortableJS drag-drop with deal cards, pipeline stages, health scores
- **Authentication**: 6-role RBAC (director, gm, manager, sales, operational, finance)
- **Vite + Tailwind CSS 4**: Modern build pipeline, CSS custom properties, responsive design
- **SQLite on Render**: Free tier deployment without external database

### Stack
- Laravel 12 + PHP 8.4 + Blade templates
- Vite + Tailwind CSS 4 + Alpine.js 3 + Chart.js 4 + SortableJS
- Docker multi-stage: node:22-alpine + php:8.4-fpm-alpine + supervisord
- Render free tier (auto-deploy from GitHub main)

---

## Previous Versions

See git log for full history:
```bash
git log --oneline
```
