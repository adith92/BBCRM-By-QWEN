# Golden Bird CRM — Scripts

Utility scripts untuk development, testing, dan production verification.

## Available Scripts

### `verify-local.sh` — Local Development Verification
Run **sebelum commit** untuk memastikan semua file, config, dan syntax valid.

```bash
chmod +x scripts/verify-local.sh
./scripts/verify-local.sh
```

**Checks**:
- ✅ PHP & Composer installed
- ✅ All config files exist (app, auth, cache, session, view, cors, etc.)
- ✅ Core app files (Controller, routes, providers)
- ✅ PHP syntax validation
- ✅ Database setup (SQLite/MySQL)
- ✅ All 6 seeder demo accounts
- ✅ Controllers, Models, Views, Middleware
- ✅ Services (Approval, Pipeline, KPI)
- ✅ Key features: trust proxies, force HTTPS, timezone, locale
- ✅ 1-click demo login buttons exist
- ✅ Docker files (if applicable)
- ✅ Git repository status

**Output**: Green ✅ = OK, Red ❌ = FAIL, Yellow ⚠️ = WARNING

---

### `verify-production.sh` — Production Verification
Run **setelah deploy ke Render** untuk memastikan site live tanpa error.

```bash
chmod +x scripts/verify-production.sh
./scripts/verify-production.sh
```

**Checks**:
- ✅ All critical files exist
- ✅ Controller base class defined
- ✅ Auth routes configured
- ✅ All 6 demo accounts in seeder
- ✅ Trust proxies configured
- ✅ HTTPS forced
- ✅ HTTP status checks on production URL
- ✅ Login form structure
- ✅ Form action uses HTTPS (not http://)
- ✅ CSRF token present in form
- ✅ PHP syntax validation
- ✅ Composer.lock exists
- ✅ Git commits pushed

**Requirements**:
- Internet connection (checks `https://goldenbirdcrm.onrender.com`)
- `curl` installed

**Output**: Report saved to `/tmp/crm-verify-*.log`

---

## Typical Workflow

### 1. Before Push to GitHub
```bash
./scripts/verify-local.sh
# Fix any ❌ FAIL or ⚠️ WARN before pushing
git add .
git commit -m "your message"
git push origin main
```

### 2. After Render Deploy (wait ~2 min)
```bash
./scripts/verify-production.sh
# Check all items are ✅ PASS
# Then test manually: https://goldenbirdcrm.onrender.com/login
```

### 3. Test Login Flow
After production verify:
```
1. Open: https://goldenbirdcrm.onrender.com/login
2. Hard refresh: Cmd+Shift+R (clear old cookies)
3. Click "Director" button
4. Should login & see dashboard (no 500, no 419)
5. Click logout
6. Repeat for other roles
```

---

## Troubleshooting

### `verify-local.sh` fails on Controller
```bash
# Fix:
cat > app/Http/Controllers/Controller.php << 'EOF'
<?php
namespace App\Http\Controllers;
abstract class Controller { }
EOF
```

### `verify-local.sh` fails on config files
```bash
# These should all exist:
ls config/app.php config/auth.php config/session.php config/view.php
# If missing, check git or create from Laravel defaults
```

### `verify-production.sh` fails on HTTP 200
- Render free tier might be **sleeping** (first request takes 30s)
- Wait a minute, then try again
- Check: `https://dashboard.render.com` → Logs → any errors?

### `verify-production.sh` fails on CSRF token
- Form action might be `http://` instead of `https://`
- Check: `curl -s https://goldenbirdcrm.onrender.com/login | grep action`
- Must be `https://` or relative URL, not `http://`

---

## What These Scripts Verify

### Critical Errors (FAIL = broken site)
- ❌ Missing `Controller.php` → HTTP 500 all routes
- ❌ Missing config files → HTTP 500 on boot
- ❌ Missing auth routes → HTTP 500 on `/login`
- ❌ Form action `http://` → Browser security warning + 419 CSRF
- ❌ Missing seeder accounts → "Email atau password salah"

### Features (WARN = missing feature)
- ⚠️ Missing 1-click login buttons → manual typing required
- ⚠️ Missing trust proxies → CSRF 419 behind reverse proxy
- ⚠️ Missing force HTTPS → form "not secure" warning

---

## Manual Checks (if scripts fail)

### Check PHP Syntax
```bash
php -l app/Http/Controllers/Controller.php
php -l bootstrap/app.php
php -l routes/auth.php
```

### Check Files Exist
```bash
# Critical files
ls app/Http/Controllers/Controller.php
ls routes/auth.php
ls bootstrap/app.php

# Config files
ls config/app.php config/auth.php config/session.php config/view.php
```

### Check HTTP Status
```bash
# Local
php artisan serve  # runs on http://localhost:8000
curl -I http://localhost:8000/login

# Production
curl -I https://goldenbirdcrm.onrender.com/login
```

### Check Database
```bash
# Verify migration
php artisan migrate:status

# Verify seeder
php artisan db:seed --class=DatabaseSeeder

# Check demo accounts
php artisan tinker
User::where('email', 'director@goldenbird.co.id')->first();
User::where('email', 'manager@goldenbird.co.id')->first();
```

---

## Exit Codes

- `0` = All checks passed ✅
- `1` = Some checks failed ❌

Use in CI/CD:
```bash
./scripts/verify-local.sh || exit 1
git push origin main
```

---

## Performance

- `verify-local.sh`: ~2-5 seconds (offline)
- `verify-production.sh`: ~10-30 seconds (depends on Render response)

If production checks are slow:
- Render free tier might be sleeping
- Wait 30+ seconds for first request (normal)
- Subsequent checks will be faster

---

## Adding New Checks

To add checks to these scripts:

1. Edit `scripts/verify-local.sh` or `scripts/verify-production.sh`
2. Add function calls within appropriate section (e.g., `=== 12. New Feature ===`)
3. Use `pass()`, `fail()`, `warn()`, `info()` functions
4. Test: `./scripts/verify-local.sh`

Example:
```bash
echo -e "\n${BLUE}=== X. My New Check ===${NC}" | tee -a "$RESULTS_FILE"

if [ some_condition ]; then
    pass "Description of what passed"
else
    fail "Description of what failed"
fi
```

---

## References

See `VERIFICATION.md` for detailed error history and fixes.

---

**Last Updated**: 2026-06-05  
**Version**: 7.2
