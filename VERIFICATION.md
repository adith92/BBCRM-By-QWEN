# Golden Bird CRM — Verification & Error Prevention

Dokumentasi semua error yang pernah terjadi dan cara verifikasinya.

## Quick Start

### 1️⃣ Local Verification (sebelum commit)
```bash
chmod +x scripts/verify-local.sh
./scripts/verify-local.sh
```

### 2️⃣ Production Verification (setelah deploy ke Render)
```bash
chmod +x scripts/verify-production.sh
./scripts/verify-production.sh
```

---

## Error History & Fixes

### ❌ Error #1: HTTP 500 — "Class Not Found: Controller"
**Ketika**: Pertama kali buka site → semua route return 500  
**Penyebab**: `app/Http/Controllers/Controller.php` tidak ada  
**Solusi**: Buat file dengan class definition:
```php
<?php
namespace App\Http\Controllers;
abstract class Controller { }
```
**Verifikasi**: 
```bash
grep -q "class Controller" app/Http/Controllers/Controller.php && echo "✅ OK"
```

---

### ❌ Error #2: HTTP 500 — "routes/auth.php not found"
**Ketika**: Buka `https://site.com/login` → 500  
**Penyebab**: `routes/web.php` line 103 `require '/auth.php'` tapi file tidak ada  
**Solusi**: Buat `routes/auth.php` dengan:
```php
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
```
**Verifikasi**:
```bash
grep -q "Route::post.*login" routes/auth.php && echo "✅ OK"
```

---

### ❌ Error #3: View Cache Failure
**Ketika**: Deploy ke Render → "View path not found"  
**Penyebab**: `config/view.php` missing, `php artisan view:cache` fails  
**Solusi**: 
1. Buat `config/view.php`
2. Remove `view:cache` dari `docker/start.sh`, replace dengan fallback:
   ```bash
   php artisan view:cache || echo "view:cache skipped"
   ```
**Verifikasi**:
```bash
[ -f "config/view.php" ] && echo "✅ OK"
```

---

### ❌ Error #4: Laravel ^11 Security Advisories
**Ketika**: `composer install` → blocked oleh security advisories  
**Penyebab**: Laravel 11 has unpatched security issues  
**Solusi**: Upgrade ke `laravel/framework: ^12.0` dan commit `composer.lock`  
**Verifikasi**:
```bash
grep '"laravel/framework"' composer.lock | grep "12\."
```

---

### ❌ Error #5: "Email atau password salah" saat login
**Ketika**: Klik tombol role → "Email atau password salah"  
**Penyebab**: Seeder tidak membuat `director@` dan `manager@` accounts  
**Solusi**: Add ke `database/seeders/DatabaseSeeder.php`:
```php
User::create([
    'name' => 'Bapak Direktur',
    'email' => 'director@goldenbird.co.id',
    'password' => bcrypt('password123'),
    'role' => 'director'
]),
User::create([
    'name' => 'Ratna Dewi',
    'email' => 'manager@goldenbird.co.id',
    'password' => bcrypt('password123'),
    'role' => 'manager'
]),
```
**Verifikasi**:
```bash
grep "director@goldenbird.co.id" database/seeders/DatabaseSeeder.php
grep "manager@goldenbird.co.id" database/seeders/DatabaseSeeder.php
```

---

### ❌ Error #6: Form Warning "Not Secure"
**Ketika**: Login page → browser: "This form is being submitted using a connection that's not secure"  
**Penyebab**: Form action `http://` ketika koneksi user adalah `https://` (Render reverse proxy)  
**Solusi**: Force HTTPS di `app/Providers/AppServiceProvider.php`:
```php
if (config('app.env') !== 'local') {
    URL::forceScheme('https');
}
```
**Verifikasi**:
```bash
curl -s https://goldenbirdcrm.onrender.com/login | grep -o 'action="[^"]*"'
# Harus pakai https:// atau relative URL, tidak http://
```

---

### ❌ Error #7: 419 Page Expired (CSRF Mismatch)
**Ketika**: Submit login form → 419 Page Expired  
**Penyebab**: CSRF token tidak cocok karena:
- Form set cookie sebagai `http://` 
- Request datang sebagai `https://` (Render proxy)
- Mismatch → CSRF token invalid  

**Solusi**: Trust reverse proxy di `bootstrap/app.php`:
```php
$middleware->trustProxies(
    at: '*',
    headers: Request::HEADER_X_FORWARDED_FOR
        | Request::HEADER_X_FORWARDED_HOST
        | Request::HEADER_X_FORWARDED_PORT
        | Request::HEADER_X_FORWARDED_PROTO
        | Request::HEADER_X_FORWARDED_AWS_ELB
);
```

**Verifikasi**:
```bash
grep -q "trustProxies" bootstrap/app.php && echo "✅ OK"
grep -q "X_FORWARDED_PROTO" bootstrap/app.php && echo "✅ OK"
```

---

## Missing Config Files (Fixed in Sprint)

Semua file berikut **wajib ada**, jika tidak ada = 500 error:

| File | Purpose |
|------|---------|
| `config/app.php` | Timezone, locale, app name |
| `config/auth.php` | Guard & provider config |
| `config/cache.php` | Cache driver (file/redis) |
| `config/session.php` | Session driver & cookie settings |
| `config/view.php` | View path & compiled view location |
| `config/cors.php` | CORS settings untuk API |
| `config/filesystem.php` | Storage disk config |
| `config/logging.php` | Log channels |
| `config/mail.php` | Mail driver & from address |
| `config/queue.php` | Queue connection |
| `config/database.php` | Database drivers (sqlite/mysql) |

**Verifikasi**:
```bash
for file in config/*.php; do
    [ -f "$file" ] && echo "✅ $file" || echo "❌ $file"
done
```

---

## Deployment Checklist

Before `git push origin main`:

- [ ] Run `./scripts/verify-local.sh` — semua ✅
- [ ] Commit semua file: `git add .`
- [ ] Push: `git push origin main`
- [ ] Wait ~2 min untuk Render deploy

After deploy to Render:

- [ ] Run `./scripts/verify-production.sh`
- [ ] Open `https://goldenbirdcrm.onrender.com/login`
- [ ] Hard refresh: `Cmd+Shift+R` (buang old cookies)
- [ ] Click one role button → test login flow
- [ ] Check no 500 errors, no 419 CSRF, form secure ✅

---

## Common Issues & Quick Fixes

### Issue: "Render free tier sleeping"
**Symptoms**: First request takes 30+ seconds  
**Why**: Render free tier spins down after 15 min inactivity  
**Fix**: Just wait, or upgrade to paid instance  

### Issue: Cache issues after deploy
**Solution**:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache  # optional
```

### Issue: Database not migrated
**Check**: `php artisan migrate --force`  
**Seed**: `php artisan db:seed --force`  

### Issue: "Cannot write to storage/"
**Fix in Dockerfile**: Ensure `RUN chown -R www-data:www-data /app/storage`

---

## Monitoring Production

### View Logs
```bash
# Render dashboard
https://dashboard.render.com → Service → Logs

# Or via Render CLI
render logs --service golden-bird-crm
```

### Check Deployment Status
```bash
render deployments list --service golden-bird-crm
```

### Monitor Errors
Check for:
- HTTP 500 errors in logs
- CSRF token mismatches (419)
- Unsecure form submissions (http://)
- Database migration failures

---

## Scripts Reference

### `scripts/verify-local.sh`
- ✅ Check all config files exist
- ✅ Validate PHP syntax
- ✅ Check seeder has all 6 demo accounts
- ✅ Verify key features (trust proxies, force HTTPS)
- ✅ Git status

**Run before commit**:
```bash
./scripts/verify-local.sh
```

### `scripts/verify-production.sh`
- ✅ Check HTTP 200 on `/login`
- ✅ Verify form action uses HTTPS
- ✅ Check CSRF token in form
- ✅ Validate critical files exist
- ✅ Check PHP syntax

**Run after Render deploy**:
```bash
./scripts/verify-production.sh
```

---

## Key Takeaways

1. **Always trust proxies** when behind reverse proxy (Render, Heroku, AWS ALB)
2. **Always force HTTPS** in production
3. **Check seeder** has all demo accounts before deploy
4. **Run verify scripts** before and after deploy
5. **Hard refresh** browser after deploy (cookies change)

---

## Need Help?

1. Check logs: `Render dashboard → Logs`
2. Run local verify: `./scripts/verify-local.sh`
3. Check git: `git status`
4. Restart service: `Render dashboard → Manual Deploy`

---

**Last Updated**: 2026-06-05  
**Version**: 7.2  
**Status**: ✅ Production Ready
