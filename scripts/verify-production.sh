#!/bin/bash

# ============================================================================
# Golden Bird CRM — Production Verification Script
# ============================================================================
# Cek semua error yang pernah terjadi, validate fix, ensure zero 500 errors
# ============================================================================

set -e

PROD_URL="https://goldenbirdcrm.onrender.com"
RESULTS_FILE="/tmp/crm-verify-$(date +%s).log"

echo "🔍 Golden Bird CRM Production Verification" > "$RESULTS_FILE"
echo "🔗 URL: $PROD_URL" >> "$RESULTS_FILE"
echo "⏰ Started: $(date)" >> "$RESULTS_FILE"
echo "================================================================" >> "$RESULTS_FILE"

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

pass() {
    echo -e "${GREEN}✅ PASS${NC} — $1" | tee -a "$RESULTS_FILE"
}

fail() {
    echo -e "${RED}❌ FAIL${NC} — $1" | tee -a "$RESULTS_FILE"
    EXIT_CODE=1
}

warn() {
    echo -e "${YELLOW}⚠️  WARN${NC} — $1" | tee -a "$RESULTS_FILE"
}

info() {
    echo -e "${BLUE}ℹ️  INFO${NC} — $1" | tee -a "$RESULTS_FILE"
}

EXIT_CODE=0

# ============================================================================
# 1. Check Local Files (Critical Controllers, Routes, Config)
# ============================================================================
echo -e "\n${BLUE}=== 1. Critical Files Check ===${NC}" | tee -a "$RESULTS_FILE"

files_to_check=(
    "app/Http/Controllers/Controller.php"
    "routes/auth.php"
    "config/app.php"
    "config/auth.php"
    "config/cache.php"
    "config/session.php"
    "config/view.php"
    "config/cors.php"
    "config/filesystem.php"
    "config/logging.php"
    "config/mail.php"
    "config/queue.php"
    "bootstrap/app.php"
    "app/Providers/AppServiceProvider.php"
)

for file in "${files_to_check[@]}"; do
    if [ -f "$file" ]; then
        pass "File exists: $file"
    else
        fail "Missing: $file"
    fi
done

# ============================================================================
# 2. Check Controller Base Class
# ============================================================================
echo -e "\n${BLUE}=== 2. Controller Base Class ===${NC}" | tee -a "$RESULTS_FILE"

if grep -q "class Controller" app/Http/Controllers/Controller.php 2>/dev/null; then
    pass "Controller.php has class definition"
else
    fail "Controller.php missing class definition"
fi

# ============================================================================
# 3. Check Auth Routes
# ============================================================================
echo -e "\n${BLUE}=== 3. Auth Routes ===${NC}" | tee -a "$RESULTS_FILE"

if grep -q "Route::post.*login" routes/auth.php 2>/dev/null; then
    pass "auth.php has login route"
else
    fail "auth.php missing login route"
fi

# ============================================================================
# 4. Check Database Seeder (6 Demo Accounts)
# ============================================================================
echo -e "\n${BLUE}=== 4. Database Seeder (6 Demo Accounts) ===${NC}" | tee -a "$RESULTS_FILE"

accounts=(
    "director@goldenbird.co.id:director"
    "gm@goldenbird.co.id:gm"
    "manager@goldenbird.co.id:manager"
    "sales1@goldenbird.co.id:sales"
    "ops@goldenbird.co.id:operational"
    "finance@goldenbird.co.id:finance"
)

for account in "${accounts[@]}"; do
    email=$(echo "$account" | cut -d: -f1)
    role=$(echo "$account" | cut -d: -f2)

    if grep -q "'email' => '$email'" database/seeders/DatabaseSeeder.php 2>/dev/null && \
       grep -q "'role' => '$role'" database/seeders/DatabaseSeeder.php 2>/dev/null; then
        pass "Seeder: $email ($role)"
    else
        fail "Seeder missing: $email ($role)"
    fi
done

# ============================================================================
# 5. Check Bootstrap Config (Trust Proxies)
# ============================================================================
echo -e "\n${BLUE}=== 5. Bootstrap Config (Proxy Trust) ===${NC}" | tee -a "$RESULTS_FILE"

if grep -q "trustProxies" bootstrap/app.php 2>/dev/null; then
    pass "bootstrap/app.php has trustProxies() for reverse proxy HTTPS"
else
    fail "bootstrap/app.php missing trustProxies() — CSRF/HTTPS issues"
fi

if grep -q "X_FORWARDED_PROTO" bootstrap/app.php 2>/dev/null; then
    pass "bootstrap/app.php trusts X-Forwarded-Proto header"
else
    warn "bootstrap/app.php may not trust X-Forwarded-Proto"
fi

# ============================================================================
# 6. Check HTTPS Force
# ============================================================================
echo -e "\n${BLUE}=== 6. HTTPS Force Config ===${NC}" | tee -a "$RESULTS_FILE"

if grep -q "forceScheme.*https" app/Providers/AppServiceProvider.php 2>/dev/null; then
    pass "AppServiceProvider forces HTTPS"
else
    fail "AppServiceProvider not forcing HTTPS"
fi

# ============================================================================
# 7. HTTP Status Checks (Production URL)
# ============================================================================
echo -e "\n${BLUE}=== 7. Production HTTP Status Checks ===${NC}" | tee -a "$RESULTS_FILE"

info "Testing $PROD_URL (this may take 30s if Render is sleeping)..."

# Test 1: Login page (HTTP 200, no 500)
echo -n "Testing GET /login ... "
status=$(curl -s -o /dev/null -w "%{http_code}" "$PROD_URL/login" --max-time 45)
if [ "$status" = "200" ]; then
    pass "Login page: HTTP 200"
else
    fail "Login page: HTTP $status (expected 200)"
fi

# Test 2: Check login form HTML (has correct structure)
echo -n "Testing login form structure ... "
if curl -s "$PROD_URL/login" --max-time 45 | grep -q "golden_bird\|Golden Bird\|1-Click" 2>/dev/null; then
    pass "Login form has correct structure"
else
    warn "Login form structure unclear"
fi

# Test 3: Check HTTPS redirect (no http:// forms)
echo -n "Checking form action protocol ... "
form_action=$(curl -s "$PROD_URL/login" --max-time 45 | grep -o 'action="[^"]*"' | head -1)
if echo "$form_action" | grep -q "https://" || ! echo "$form_action" | grep -q "http://"; then
    pass "Form action uses HTTPS or relative URL (no http://)"
else
    fail "Form action is insecure (http://)"
fi

# Test 4: Try login (if curl can handle cookies)
echo -n "Testing login form submission ... "
csrf_token=$(curl -s "$PROD_URL/login" --max-time 45 | grep -o '_token" value="[^"]*' | sed 's/_token" value="//' | head -1)
if [ -n "$csrf_token" ]; then
    pass "CSRF token found in form"
else
    warn "Could not extract CSRF token"
fi

# Test 5: Check Laravel logs for 500 errors (remote)
echo -n "Checking for recent 500 errors ... "
info "Note: Cannot check remote logs, verify in Render dashboard: https://dashboard.render.com"

# ============================================================================
# 8. Local PHP Syntax Check
# ============================================================================
echo -e "\n${BLUE}=== 8. Local PHP Syntax Check ===${NC}" | tee -a "$RESULTS_FILE"

php_files=(
    "bootstrap/app.php"
    "app/Providers/AppServiceProvider.php"
    "app/Http/Controllers/Controller.php"
    "database/seeders/DatabaseSeeder.php"
)

for php_file in "${php_files[@]}"; do
    if php -l "$php_file" > /dev/null 2>&1; then
        pass "PHP syntax: $php_file"
    else
        fail "PHP syntax error: $php_file"
    fi
done

# ============================================================================
# 9. Composer & Dependencies
# ============================================================================
echo -e "\n${BLUE}=== 9. Composer & Dependencies ===${NC}" | tee -a "$RESULTS_FILE"

if [ -f "composer.lock" ]; then
    pass "composer.lock exists (reproducible builds)"
else
    warn "composer.lock not found"
fi

if composer validate --quiet 2>/dev/null; then
    pass "composer.json is valid"
else
    fail "composer.json validation failed"
fi

# ============================================================================
# 10. Git Status
# ============================================================================
echo -e "\n${BLUE}=== 10. Git Status ===${NC}" | tee -a "$RESULTS_FILE"

uncommitted=$(git status --short 2>/dev/null | wc -l)
if [ "$uncommitted" -eq 0 ]; then
    pass "No uncommitted changes"
else
    warn "Uncommitted changes: $uncommitted file(s)"
fi

commits_ahead=$(git rev-list --count origin/main..HEAD 2>/dev/null || echo "0")
if [ "$commits_ahead" -eq 0 ]; then
    pass "All commits pushed to origin/main"
else
    warn "Commits ahead of origin/main: $commits_ahead"
fi

# ============================================================================
# 11. Critical Migrations
# ============================================================================
echo -e "\n${BLUE}=== 11. Database Migrations ===${NC}" | tee -a "$RESULTS_FILE"

migration_files=$(find database/migrations -name "*.php" | wc -l)
if [ "$migration_files" -ge 18 ]; then
    pass "Database migrations: $migration_files files (expected >= 18)"
else
    fail "Database migrations: only $migration_files files (expected >= 18)"
fi

# ============================================================================
# Summary & Report
# ============================================================================
echo -e "\n${BLUE}=== Summary ===${NC}" | tee -a "$RESULTS_FILE"
echo "================================================================" >> "$RESULTS_FILE"

if [ $EXIT_CODE -eq 0 ]; then
    echo -e "${GREEN}✅ All checks passed!${NC}" | tee -a "$RESULTS_FILE"
    echo "Golden Bird CRM is ready for production." | tee -a "$RESULTS_FILE"
else
    echo -e "${RED}❌ Some checks failed. Review above.${NC}" | tee -a "$RESULTS_FILE"
fi

echo "" | tee -a "$RESULTS_FILE"
echo "Full report: $RESULTS_FILE" | tee -a "$RESULTS_FILE"
echo "Render Dashboard: https://dashboard.render.com" | tee -a "$RESULTS_FILE"
echo "Production URL: $PROD_URL" | tee -a "$RESULTS_FILE"

cat "$RESULTS_FILE"
exit $EXIT_CODE
