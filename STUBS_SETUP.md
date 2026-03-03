# Param RBAC - Stubs Setup Guide

This guide explains how to properly configure the `stubs/` directory with your application code for distribution.

## 📋 Overview

The `stubs/` directory contains the application skeleton that gets copied to fresh Laravel projects during the `php artisan param-rbac:install` command execution.

## 📁 Directory Structure

```
param-rbac/
└── stubs/
    ├── app/                              # Application logic
    │   ├── Http/
    │   │   ├── Controllers/              # All controllers
    │   │   ├── Middleware/               # Custom middleware
    │   │   └── Kernel.php
    │   ├── Models/                       # Eloquent models
    │   ├── Providers/                    # Service providers
    │   ├── Exceptions/                   # Exception handlers
    │   └── Console/
    ├── database/
    │   ├── migrations/                   # Database migrations
    │   └── seeders/                      # Database seeders
    ├── resources/
    │   ├── views/                        # Blade templates
    │   ├── js/                           # JavaScript files
    │   ├── scss/                         # Stylesheets
    │   ├── fonts/                        # Custom fonts
    │   └── images/                       # Images and icons
    ├── public/
    │   └── assets/                       # Compiled & public assets
    │       ├── css/
    │       ├── js/
    │       ├── images/
    │       └── fonts/
    ├── routes/
    │   ├── web.php                       # Web routes
    │   ├── api.php                       # API routes
    │   └── channels.php                  # Broadcasting channels
    ├── config/                           # Configuration files
    │   ├── app.php
    │   ├── auth.php
    │   ├── database.php
    │   ├── mail.php
    │   ├── permission.php
    │   └── [other config files]
    └── webpack.mix.js                    # Laravel Mix configuration
```

## 🚀 Setup Instructions

### Step 1: Clone Your Reference Project

```bash
# Your ACPC project is the reference
ACPC_PATH="/path/to/ACPC"
STUBS_PATH="/path/to/param-rbac/stubs"

# Verify paths exist
test -d "$ACPC_PATH" || echo "ACPC path not found!"
test -d "$STUBS_PATH" || echo "Creating stubs directory..."
mkdir -p "$STUBS_PATH"
```

### Step 2: Copy Application Structure

#### 2.1 Copy App Directory

```bash
cp -r "$ACPC_PATH/app" "$STUBS_PATH/"

# Verify critical files
ls -la "$STUBS_PATH/app/Models/"          # Should show User.php, etc.
ls -la "$STUBS_PATH/app/Http/Controllers/"  # Should show controllers
```

**What gets copied:**
- Controllers (including Auth controllers)
- Models (User, AuthSetting, PasswordRule, etc.)
- Middleware (custom middleware)
- Service Providers
- Exception handlers

#### 2.2 Copy Database Structure

```bash
mkdir -p "$STUBS_PATH/database"
cp -r "$ACPC_PATH/database/migrations" "$STUBS_PATH/database/"
cp -r "$ACPC_PATH/database/seeders" "$STUBS_PATH/database/"

# Verify migrations
ls -la "$STUBS_PATH/database/migrations/"
```

**Critical migrations to include:**
- `*_add_address_to_users_table.php`
- `*_create_authentication_settings_table.php`
- `*_create_password_rules_table.php`
- `*_create_user_two_factor_table.php`
- `*_create_email_login_otp_table.php`
- `*_create_permission_tables.php` (from Spatie)

#### 2.3 Copy Views

```bash
cp -r "$ACPC_PATH/resources/views" "$STUBS_PATH/resources/"

# Verify critical views
ls -la "$STUBS_PATH/resources/views/layouts/"
ls -la "$STUBS_PATH/resources/views/auth/"
```

**Must-have view directories:**
- `layouts/` - Layout templates
- `auth/` - Authentication pages
- `roles/` - Role management pages
- `permissions/` - Permission management pages
- `errors/` - Error pages

#### 2.4 Copy Frontend Assets

```bash
# JavaScript and SCSS
cp -r "$ACPC_PATH/resources/js" "$STUBS_PATH/resources/"
cp -r "$ACPC_PATH/resources/scss" "$STUBS_PATH/resources/"
cp -r "$ACPC_PATH/resources/fonts" "$STUBS_PATH/resources/"
cp -r "$ACPC_PATH/resources/images" "$STUBS_PATH/resources/"

# Compiled public assets
mkdir -p "$STUBS_PATH/public/assets"
cp -r "$ACPC_PATH/public/assets"/* "$STUBS_PATH/public/assets/"

# Copy Laravel Mix file
cp "$ACPC_PATH/webpack.mix.js" "$STUBS_PATH/"
```

#### 2.5 Copy Routes

```bash
mkdir -p "$STUBS_PATH/routes"
cp "$ACPC_PATH/routes/web.php" "$STUBS_PATH/routes/"
cp "$ACPC_PATH/routes/api.php" "$STUBS_PATH/routes/"

# Optional: copy channels.php if you use broadcasting
cp "$ACPC_PATH/routes/channels.php" "$STUBS_PATH/routes/" 2>/dev/null || true
```

#### 2.6 Copy Configuration Files

```bash
mkdir -p "$STUBS_PATH/config"

# Copy only necessary config files
for config in app.php auth.php database.php mail.php permission.php;
do
    if [ -f "$ACPC_PATH/config/$config" ]; then
        cp "$ACPC_PATH/config/$config" "$STUBS_PATH/config/"
        echo "✓ Copied config/$config"
    fi
done
```

### Step 3: Clean Up Stubs

Remove unnecessary files before distribution:

```bash
# Remove vendor directory if it exists
rm -rf "$STUBS_PATH/vendor"

# Remove node_modules if it exists
rm -rf "$STUBS_PATH/node_modules"

# Remove .env files
rm -f "$STUBS_PATH/.env"
rm -f "$STUBS_PATH/.env.example"

# Remove storage directory
rm -rf "$STUBS_PATH/storage"

# Remove bootstrap cache
rm -rf "$STUBS_PATH/bootstrap/cache"

# Verify cleanup
echo "Remaining files in stubs:"
find "$STUBS_PATH" -maxdepth 1 -type f
```

### Step 4: Verify Stubs Structure

Create a verification script `verify_stubs.sh`:

```bash
#!/bin/bash

STUBS_PATH="./stubs"
REQUIRED_DIRS=(
    "app/Http/Controllers"
    "app/Http/Middleware"
    "app/Models"
    "app/Providers"
    "database/migrations"
    "database/seeders"
    "resources/views"
    "resources/js"
    "resources/scss"
    "public/assets"
    "routes"
    "config"
)

REQUIRED_FILES=(
    "app/Http/Controllers/HomeController.php"
    "app/Http/Controllers/Auth/LoginController.php"
    "app/Models/User.php"
    "database/migrations"
    "resources/views/layouts/master.blade.php"
    "resources/views/auth"
    "routes/web.php"
    "routes/api.php"
    "config/app.php"
    "config/auth.php"
)

echo "🔍 Verifying stubs structure..."

# Check directories
for dir in "${REQUIRED_DIRS[@]}"; do
    if [ -d "$STUBS_PATH/$dir" ]; then
        echo "✓ Directory: $dir"
    else
        echo "✗ Missing: $dir"
    fi
done

# Check files
for file in "${REQUIRED_FILES[@]}"; do
    if [ -e "$STUBS_PATH/$file" ]; then
        echo "✓ File: $file"
    else
        echo "✗ Missing: $file"
    fi
done

echo ""
echo "Summary:"
echo "Total items in stubs: $(find "$STUBS_PATH" -type f | wc -l) files"
echo "✅ Stubs verification complete"
```

Run verification:

```bash
chmod +x verify_stubs.sh
./verify_stubs.sh
```

## 🗂️ File Organization Best Practices

### Controllers Organization

```
stubs/app/Http/Controllers/
├── Auth/
│   ├── LoginController.php
│   ├── RegisterController.php
│   ├── ForgotPasswordController.php
│   ├── ResetPasswordController.php
│   ├── VerificationController.php
│   └── ConfirmPasswordController.php
├── HomeController.php
├── RoleController.php
├── PermissionController.php
├── UserController.php
└── AuthSettingController.php
```

### Model Organization

```
stubs/app/Models/
├── User.php
├── AuthSetting.php
├── PasswordRule.php
├── RoleMaster.php
└── UserTwoFactor.php
```

### Views Organization

```
stubs/resources/views/
├── layouts/
│   ├── master.blade.php
│   ├── app.blade.php
│   ├── footer.blade.php
│   ├── sidebar.blade.php
│   ├── topbar.blade.php
│   └── [other layout files]
├── auth/
│   ├── login.blade.php
│   ├── register.blade.php
│   ├── otp-verify.blade.php
│   ├── two-factor-setup.blade.php
│   ├── two-factor-verify.blade.php
│   └── [other auth views]
├── roles/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
├── permissions/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── errors/
│   ├── 404.blade.php
│   ├── 500.blade.php
│   └── [other error pages]
└── [other views]
```

## 🔄 Automated Setup Script

Create `setup_stubs.sh` for one-command setup:

```bash
#!/bin/bash

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m'

# Configuration
ACPC_PATH="${1:-.}"
STUBS_PATH="./stubs"

# Check if ACPC path exists
if [ ! -d "$ACPC_PATH/app" ]; then
    echo -e "${RED}Error: Invalid ACPC path${NC}"
    echo "Usage: ./setup_stubs.sh /path/to/ACPC"
    exit 1
fi

echo "📦 Setting up stubs from $ACPC_PATH..."

# Create stubs structure
mkdir -p "$STUBS_PATH/{app,database,resources/views,public,routes,config}"

# Copy directories
echo "Copying directories..."
for dir in app/Http/Controllers app/Http/Middleware app/Models app/Providers database/migrations database/seeders resources/views resources/js resources/scss resources/fonts resources/images public/assets; do
    if [ -d "$ACPC_PATH/$dir" ]; then
        cp -r "$ACPC_PATH/$dir" "$STUBS_PATH/${dir%/*}/" 2>/dev/null || true
        echo -e "${GREEN}✓${NC} Copied: $dir"
    fi
done

# Copy files
echo "Copying files..."
for file in routes/web.php routes/api.php webpack.mix.js; do
    if [ -f "$ACPC_PATH/$file" ]; then
        cp "$ACPC_PATH/$file" "$STUBS_PATH/$file"
        echo -e "${GREEN}✓${NC} Copied: $file"
    fi
done

# Copy config files
echo "Copying config files..."
mkdir -p "$STUBS_PATH/config"
for config in $ACPC_PATH/config/*.php; do
    if [ -f "$config" ]; then
        cp "$config" "$STUBS_PATH/config/"
        echo -e "${GREEN}✓${NC} Copied: $(basename $config)"
    fi
done

# Clean up
echo "Cleaning up..."
rm -rf "$STUBS_PATH/vendor" "$STUBS_PATH/node_modules" "$STUBS_PATH/.env" "$STUBS_PATH/storage"

echo -e "${GREEN}✅ Stubs setup complete!${NC}"
echo "📂 Stubs directory: $STUBS_PATH"
echo "📊 Files count: $(find "$STUBS_PATH" -type f | wc -l)"
```

Usage:

```bash
chmod +x setup_stubs.sh
./setup_stubs.sh /path/to/ACPC
```

## ✅ Verification Checklist

Before distributing the package, verify:

- [ ] **app/** - All controllers, models, middleware present
- [ ] **database/migrations/** - All custom migrations included
- [ ] **database/seeders/** - Seeders present
- [ ] **resources/views/** - All Blade templates present
- [ ] **resources/js/** - JavaScript files included
- [ ] **resources/scss/** - SCSS/CSS files included
- [ ] **public/assets/** - Fonts, images included
- [ ] **routes/** - web.php and api.php correctly configured
- [ ] **config/** - All necessary config files
- [ ] **webpack.mix.js** - Correctly configured
- [ ] **NO vendor/** - vendor/ directory removed
- [ ] **NO node_modules/** - node_modules directory removed
- [ ] **NO .env** - Environment files removed
- [ ] **NO storage/** - Storage directory removed
- [ ] **NO bootstrap/cache** - Cache files removed

## 🔐 Security Considerations

Before distribution:

- [ ] **Remove secrets** - No API keys in .env.example
- [ ] **Reset credentials** - Change default passwords before public release
- [ ] **Remove debug info** - Set APP_DEBUG=false in config
- [ ] **Check migrations** - No hardcoded sensitive data in seeds
- [ ] **Verify auth** - Ensure authentication is properly secured
- [ ] **Test permissions** - Verify access control works

## 📤 Preparing for Distribution

### Documentation

- [ ] Create comprehensive README.md
- [ ] Create INSTALLATION.md
- [ ] Create USAGE.md
- [ ] Add CHANGELOG.md
- [ ] Add LICENSE file (MIT)

### Code Quality

- [ ] Remove unused imports
- [ ] Fix any PHP warnings/errors
- [ ] Check code style consistency
- [ ] Add comments to complex logic
- [ ] Document custom functions

### Testing

- [ ] Test clean installation
- [ ] Test with --force flag
- [ ] Test migrations
- [ ] Test seeding
- [ ] Test authentication
- [ ] Test admin panel

---

**Now you're ready to distribute param-rbac! 🚀**
