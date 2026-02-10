#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_section() {
    echo ""
    echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo ""
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the Laravel project root directory"
    exit 1
fi

# Detect PHP executable
detect_php() {
    # Try common PHP locations
    local php_paths=(
        "php"
        "/usr/bin/php"
        "/usr/local/bin/php"
        "/opt/homebrew/bin/php"
        "/opt/homebrew/opt/php@8.3/bin/php"
        "/opt/homebrew/opt/php@8.2/bin/php"
        "/opt/homebrew/opt/php@8.1/bin/php"
    )
    
    for php_path in "${php_paths[@]}"; do
        if command -v "$php_path" >/dev/null 2>&1 || [ -f "$php_path" ]; then
            if "$php_path" --version >/dev/null 2>&1; then
                echo "$php_path"
                return 0
            fi
        fi
    done
    
    return 1
}

PHP_CMD=$(detect_php)

if [ -z "$PHP_CMD" ]; then
    print_error "PHP executable not found. Please install PHP or update the script with your PHP path."
    exit 1
fi

print_info "Using PHP: $PHP_CMD ($($PHP_CMD --version | head -n 1))"

# Track overall success
OVERALL_SUCCESS=true

# ============================================================================
# MASTER DATABASE SETUP
# ============================================================================
print_section "MASTER DATABASE SETUP"

# Step 1: Run Master DB Migrations
print_info "Running Master DB migrations..."
if $PHP_CMD artisan migrate --path=database/migrations 2>&1; then
    print_success "Master DB migrations completed"
else
    print_error "Master DB migrations failed"
    OVERALL_SUCCESS=false
fi

# Step 2: Run Master DB Seeders (includes TenantSubscriptionSeeder)
print_info "Running Master DB seeders (includes TenantSubscriptionSeeder)..."
if $PHP_CMD artisan db:seed --class=Database\\Seeders\\MasterDatabaseSeeder 2>&1; then
    print_success "Master DB seeders completed"
else
    print_error "Master DB seeders failed"
    OVERALL_SUCCESS=false
fi

# ============================================================================
# TENANT DATABASES SETUP
# ============================================================================
print_section "TENANT DATABASES SETUP"

# Step 4: Run Tenant Migrations (for all tenants)
print_info "Running Tenant DB migrations for all tenants..."
if $PHP_CMD artisan tenant:migrate 2>&1; then
    print_success "Tenant DB migrations completed"
else
    print_error "Tenant DB migrations failed"
    OVERALL_SUCCESS=false
fi

# Step 5: Run Tenant Seeders (for all tenants)
print_info "Running Tenant DB seeders for all tenants..."
if $PHP_CMD artisan tenant:seed --class=Database\\Seeders\\TenantDatabaseSeeder 2>&1; then
    print_success "Tenant DB seeders completed"
else
    print_error "Tenant DB seeders failed"
    OVERALL_SUCCESS=false
fi

# ============================================================================
# SUMMARY
# ============================================================================
print_section "SETUP SUMMARY"

if [ "$OVERALL_SUCCESS" = true ]; then
    print_success "All database setup completed successfully!"
    echo ""
    print_info "Master DB: Migrations and seeders completed"
    print_info "Tenant DBs: Migrations and seeders completed for all tenants"
    exit 0
else
    print_error "Some steps failed. Please review the output above."
    exit 1
fi
