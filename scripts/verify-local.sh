#!/bin/bash

# ============================================================================
# Golden Bird CRM — Local Development Verification
# ============================================================================
# Run locally sebelum push ke GitHub / deploy ke Render
# ============================================================================

set -e

echo "🔨 Golden Bird CRM Local Verification"
echo "================================================================"

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

pass() {
    echo -e "${GREEN}✅ PASS${NC} — $1"
}

fail() {
    echo -e "${RED}❌ FAIL${NC} — $1"
    EXIT_CODE=1
}

warn() {
    echo -e "${YELLOW}⚠️  WARN${NC} — $1"
}

info() {
    echo -e "${BLUE}ℹ️  INFO${NC} — $1"
}

EXIT_CODE=0

# ============================================================================
# 1. PHP & Composer
# ============================================================================
echo -e "\n${BLUE}=== 1. PHP & Composer Setup ===${NC}"

if command -v php &> /dev/null; then
    php_version=$(php -v | head -1)
    pass "PHP installed: $php_version"
else
    fail "PHP not found"
fi

if command -v composer &> /dev/null; then
    composer_version=$(composer -V)
    pass "Composer installed: $composer_version"
else
    fail "Composer not found"
fi

if [ -f "composer.lock" ]; then
    pass "composer.lock exists"
else
    warn "composer.lock not found — run: composer install"
fi

# ============================================================================
# 2. Laravel Config Files
# ============================================================================
echo -e "\n${BLUE}=== 2. Laravel Config Files ===${NC}"

config_files=(
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
    "config/database.php"
)

for file in "${config_files[@]}"; do
    if [ -f "$file" ]; then
        pass "Config: $file"
    else
        fail "Missing: $file"
    fi
done

# ============================================================================
# 3. Core Application Files
# ============================================================================
echo -e "\n${BLUE}=== 3. Core Application Files ===${NC}"

app_files=(
    "bootstrap/app.php"
    "app/Http/Controllers/Controller.php"
    "routes/web.php"
    "routes/auth.php"
    "app/Providers/AppServiceProvider.php"
)

for file in "${app_files[@]}"; do
    if [ -f "$file" ]; then
        pass "File: $file"
    else
        fail "Missing: $file"
    fi
done

# ============================================================================
# 4. PHP Syntax Check
# ============================================================================
echo -e "\n${BLUE}=== 4. PHP Syntax Validation ===${NC}"

php_check_files=(
    "bootstrap/app.php"
    "app/Http/Controllers/Controller.php"
    "app/Providers/AppServiceProvider.php"
    "database/seeders/DatabaseSeeder.php"
    "routes/web.php"
    "routes/auth.php"
)

for file in "${php_check_files[@]}"; do
    if php -l "$file" > /dev/null 2>&1; then
        pass "Syntax: $file"
    else
        fail "Syntax error: $file"
    fi
done

# ============================================================================
# 5. Database Setup
# ============================================================================
echo -e "\n${BLUE}=== 5. Database Setup ===${NC}"

if [ -f ".env" ]; then
    pass ".env file exists"

    if grep -q "DB_CONNECTION" .env; then
        db_conn=$(grep "^DB_CONNECTION=" .env | cut -d= -f2)
        pass "Database connection: $db_conn"
    else
        fail ".env missing DB_CONNECTION"
    fi
else
    warn ".env file not found — create from .env.example"
fi

if [ -f "database/database.sqlite" ]; then
    pass "SQLite database file exists (dev)"
else
    info "SQLite database will be created on first migrate"
fi

migration_count=$(find database/migrations -name "*.php" | wc -l)
if [ "$migration_count" -ge 18 ]; then
    pass "Migrations: $migration_count files"
else
    warn "Migrations: only $migration_count files (expected >= 18)"
fi

# ============================================================================
# 6. Seeder Data
# ============================================================================
echo -e "\n${BLUE}=== 6. Database Seeder ===${NC}"

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

    if grep -q "'email' => '$email'" database/seeders/DatabaseSeeder.php && \
       grep -q "'role' => '$role'" database/seeders/DatabaseSeeder.php; then
        pass "Seeder: $email ($role)"
    else
        fail "Seeder missing: $email ($role)"
    fi
done

# ============================================================================
# 7. Controllers
# ============================================================================
echo -e "\n${BLUE}=== 7. Controllers ===${NC}"

controllers=(
    "app/Http/Controllers/OpportunityController.php"
    "app/Http/Controllers/PipelineController.php"
    "app/Http/Controllers/ProductController.php"
    "app/Http/Controllers/ApprovalController.php"
    "app/Http/Controllers/ActivityLogController.php"
    "app/Http/Controllers/SubscriptionController.php"
)

for file in "${controllers[@]}"; do
    if [ -f "$file" ]; then
        pass "Controller: $file"
    else
        warn "Missing: $file"
    fi
done

# ============================================================================
# 8. Models
# ============================================================================
echo -e "\n${BLUE}=== 8. Models ===${NC}"

models=(
    "app/Models/Opportunity.php"
    "app/Models/Product.php"
    "app/Models/Subscription.php"
    "app/Models/Voucher.php"
)

for file in "${models[@]}"; do
    if [ -f "$file" ]; then
        pass "Model: $(basename $file)"
    else
        warn "Missing: $file"
    fi
done

# ============================================================================
# 9. Views (Critical)
# ============================================================================
echo -e "\n${BLUE}=== 9. Views ===${NC}"

views=(
    "resources/views/auth/login.blade.php"
    "resources/views/pipeline/index.blade.php"
    "resources/views/dashboard/director.blade.php"
)

for file in "${views[@]}"; do
    if [ -f "$file" ]; then
        pass "View: $(basename $file)"
    else
        warn "Missing: $file"
    fi
done

# ============================================================================
# 10. Middleware
# ============================================================================
echo -e "\n${BLUE}=== 10. Middleware ===${NC}"

if [ -f "app/Http/Middleware/RoleMiddleware.php" ]; then
    pass "RoleMiddleware.php exists"
else
    fail "RoleMiddleware.php missing"
fi

# ============================================================================
# 11. Services
# ============================================================================
echo -e "\n${BLUE}=== 11. Services ===${NC}"

services=(
    "app/Services/ApprovalService.php"
    "app/Services/PipelineService.php"
    "app/Services/KpiService.php"
)

for file in "${services[@]}"; do
    if [ -f "$file" ]; then
        pass "Service: $(basename $file)"
    else
        warn "Missing: $file"
    fi
done

# ============================================================================
# 12. Key Features Check
# ============================================================================
echo -e "\n${BLUE}=== 12. Key Features Verification ===${NC}"

# Check trustProxies
if grep -q "trustProxies" bootstrap/app.php; then
    pass "✅ Trust Proxies configured (HTTPS/CSRF fix)"
else
    fail "❌ trustProxies missing"
fi

# Check forceScheme
if grep -q "forceScheme.*https" app/Providers/AppServiceProvider.php; then
    pass "✅ Force HTTPS configured"
else
    fail "❌ Force HTTPS not configured"
fi

# Check timezone
if grep -q "Asia/Jakarta" config/app.php; then
    pass "✅ Timezone set to Asia/Jakarta"
else
    fail "❌ Timezone not set to Asia/Jakarta"
fi

# Check locale
if grep -q "'locale' => 'id'" config/app.php; then
    pass "✅ Locale set to id (Indonesian)"
else
    warn "⚠️ Locale not set to Indonesian"
fi

# Check 1-click login buttons
if grep -q "director@goldenbird" resources/views/auth/login.blade.php; then
    pass "✅ 1-click demo login buttons exist"
else
    fail "❌ 1-click login buttons missing"
fi

# ============================================================================
# 13. Docker (if present)
# ============================================================================
echo -e "\n${BLUE}=== 13. Docker Setup ===${NC}"

if [ -f "Dockerfile" ]; then
    pass "Dockerfile exists"

    if grep -q "PHP 8.3" Dockerfile; then
        pass "Dockerfile: PHP 8.3 configured"
    else
        warn "Dockerfile may not be PHP 8.3"
    fi
else
    warn "Dockerfile not found"
fi

if [ -f "docker-compose.yml" ]; then
    pass "docker-compose.yml exists"
else
    info "docker-compose.yml not found (optional)"
fi

if [ -f "docker/start.sh" ]; then
    pass "docker/start.sh exists"
else
    warn "docker/start.sh missing"
fi

# ============================================================================
# 14. Git Status
# ============================================================================
echo -e "\n${BLUE}=== 14. Git Repository ===${NC}"

if git rev-parse --git-dir > /dev/null 2>&1; then
    pass "Git repository initialized"

    uncommitted=$(git status --short 2>/dev/null | wc -l)
    if [ "$uncommitted" -eq 0 ]; then
        pass "✅ No uncommitted changes"
    else
        warn "⚠️ Uncommitted changes: $uncommitted file(s)"
    fi

    branch=$(git rev-parse --abbrev-ref HEAD 2>/dev/null)
    pass "Current branch: $branch"
else
    fail "Not a git repository"
fi

# ============================================================================
# Summary
# ============================================================================
echo -e "\n${BLUE}=== Summary ===${NC}"
echo "================================================================"

if [ $EXIT_CODE -eq 0 ]; then
    echo -e "${GREEN}✅ All local checks passed!${NC}"
    echo ""
    echo "Next steps:"
    echo "  1. Run: composer install"
    echo "  2. Run: php artisan migrate:refresh --seed"
    echo "  3. Run: php artisan serve"
    echo "  4. Visit: http://localhost:8000/login"
    echo "  5. Click any role to test 1-click login"
else
    echo -e "${RED}❌ Some checks failed. Fix above issues.${NC}"
fi

echo ""
echo "For production deployment:"
echo "  git add ."
echo "  git commit -m 'your message'"
echo "  git push origin main"
echo "  → Render auto-deploys to: https://goldenbirdcrm.onrender.com"

exit $EXIT_CODE
