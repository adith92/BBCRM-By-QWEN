# Deployment Guide - Golden Bird CRM v7.5

This document details the configuration, build steps, and troubleshooting instructions for deploying the **Bluebird CRM Command Center** preview to **Railway.app** or **Render.com**.

---

## 🛠️ Environment Variables (.env)

Make sure to set the following environment variables in your deployment dashboard:

```env
APP_NAME="Bluebird CRM"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-domain.railway.app

# Database configuration
# For SQLite (Easiest for quick previews/demos):
DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite

# For PostgreSQL (Production grade):
# DB_CONNECTION=pgsql
# DB_HOST=your-postgres-host
# DB_PORT=5432
# DB_DATABASE=your-database-name
# DB_USERNAME=your-username
# DB_PASSWORD=your-password

# Session & Cache configuration
CACHE_STORE=file
SESSION_DRIVER=cookie
QUEUE_CONNECTION=sync
```

---

## 🚂 Railway Deployment Instructions

Railway automatically detects Laravel applications and uses Nixpacks or Heroku Buildpacks. Here are the configurations:

### 1. Nixpacks Setup (Recommended)
This repository contains a `nixpacks.toml` file that specifies the build settings. If you deploy using Nixpacks:

- **Build Command:** `composer install --no-dev --optimize-autoloader`
- **Start Command:** `php artisan serve --host=0.0.0.0 --port=$PORT`

### 2. Procfile Setup (Fallback)
If you deploy using Heroku Buildpacks, the `Procfile` is configured as:
```
web: vendor/bin/heroku-php-apache2 public/
```

### 3. Post-Deployment Run Commands
After the project is deployed, open the Railway CLI or use the web terminal console to run the setup commands:

```bash
# Create SQLite DB if using SQLite
touch database/database.sqlite

# Run migrations and seed the demo data
php artisan migrate:fresh --seed --force

# Optimize Laravel cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## ☁️ Render Deployment Instructions

To deploy on Render, create a new **Web Service** linked to your GitHub repository:

### 1. Build and Start Settings
- **Environment:** `PHP`
- **Build Command:** `composer install --no-dev --optimize-autoloader`
- **Start Command:** `vendor/bin/heroku-php-apache2 public/` (or `php artisan serve --host=0.0.0.0 --port=$PORT`)

### 2. Disk Storage (Critical for SQLite)
If using SQLite on Render, you must attach a persistent **Disk mount** to preserve the SQLite database across restarts:
- **Mount Path:** `/app/database`
- **Size:** `1 GB` (or minimal)

---

## 👤 Login Credentials (Demo Accounts)

All accounts use the password: `password123`

- **GM HQ Cockpit:** `gm@goldenbird.co.id`
- **Director HQ Cockpit:** `director@goldenbird.co.id`
- **Manager HQ:** `manager@goldenbird.co.id`
- **Sales Officer:** `sales1@goldenbird.co.id`
- **Operational Head:** `ops@goldenbird.co.id`
- **Finance Admin:** `finance@goldenbird.co.id`

---

## 🚨 Troubleshooting & Cache Clearing

If you face visual glitches or 500 errors, run:

```bash
# Clear all Laravel cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Fix storage symlink issues
php artisan storage:link
```
