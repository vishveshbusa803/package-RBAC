# Param RBAC - Developer Guide

## 🛠️ Development Setup

This guide explains how to develop, test, and prepare the `param-rbac` package for production release.

## 📦 Project Structure

```
param-rbac/
├── src/
│   ├── ParamRbacServiceProvider.php     # Main service provider
│   └── Console/
│       └── InstallCommand.php           # Installation command
├── stubs/                               # Application skeleton files
│   ├── app/
│   ├── database/
│   ├── resources/
│   ├── public/
│   ├── routes/
│   └── config/
├── tests/                               # Package tests (optional)
├── composer.json                        # Package metadata
├── README.md                            # User documentation
└── DEVELOPMENT_GUIDE.md                # This file
```

## 🚀 Local Development

### 1. Initial Setup

```bash
# Clone/navigate to package repository
cd /path/to/param-rbac

# Verify structure
ls -la

# Check composer.json is valid
composer validate
```

### 2. Populate Stubs Directory

The stubs directory contains the application skeleton that gets copied to new Laravel projects during installation.

#### Option A: Copy from Reference Project (RECOMMENDED)

```bash
# Set your reference project path
REFERENCE_PROJECT="/path/to/your/acpc"

# Copy application structure
cp -r "$REFERENCE_PROJECT/app" stubs/
cp -r "$REFERENCE_PROJECT/database" stubs/
cp -r "$REFERENCE_PROJECT/resources" stubs/
cp -r "$REFERENCE_PROJECT/public/assets" stubs/public/
cp -r "$REFERENCE_PROJECT/routes" stubs/
cp -r "$REFERENCE_PROJECT/config" stubs/
```

#### Option B: Manual Structure (For Git Repositories)

If you want a clean repository without vendor files:

```
stubs/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   ├── ForgotPasswordController.php
│   │   │   │   └── [other auth controllers]
│   │   │   ├── HomeController.php
│   │   │   ├── RoleController.php
│   │   │   ├── PermissionController.php
│   │   │   ├── UserController.php
│   │   │   └── [other controllers]
│   │   └── Middleware/
│   │       ├── Authenticate.php
│   │       └── [other middleware]
│   ├── Models/
│   │   ├── User.php
│   │   ├── AuthSetting.php
│   │   ├── PasswordRule.php
│   │   ├── RoleMaster.php
│   │   ├── UserTwoFactor.php
│   │   └── [other models]
│   └── Providers/
│       ├── AppServiceProvider.php
│       └── [other providers]
├── database/
│   ├── migrations/
│   │   ├── 2026_01_06_101500_add_address_to_users_table.php
│   │   ├── 2026_01_06_101550_create_authentication_settings_table.php
│   │   ├── 2026_01_06_101621_create_password_rules_table.php
│   │   ├── 2026_01_07_100307_create_user_two_factor_table.php
│   │   ├── 2026_01_07_100308_create_email_login_otp_table.php
│   │   └── [other migrations]
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       └── [other seeders]
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── auth/
│   │   ├── roles/
│   │   ├── permissions/
│   │   └── [other views]
│   ├── js/
│   │   └── app.js
│   ├── scss/
│   │   └── app.scss
│   ├── fonts/
│   └── images/
├── public/
│   └── assets/
│       ├── css/
│       ├── js/
│       ├── images/
│       └── fonts/
├── routes/
│   ├── web.php
│   └── api.php
└── config/
    ├── app.php
    ├── auth.php
    ├── permission.php
    ├── mail.php
    └── [other config files]
```

### 3. Test Locally with Path Repository

#### Create Test Project

```bash
# Create fresh Laravel project
laravel new test-app
cd test-app
```

#### Register Local Package

```bash
# Edit composer.json to add local repository
{
    "repositories": [
        {
            "type": "path",
            "url": "/path/to/param-rbac"
        }
    ]
}
```

Or run:

```bash
composer config repositories.local path /path/to/param-rbac
```

#### Install Package

```bash
composer require param/rbac:dev-main
# or @dev if you want development version
composer require param/rbac:@dev
```

#### Run Installation Command

```bash
php artisan param-rbac:install --force

# Expected output:
# 🚀 Installing Param RBAC Package...
# ⚠️  This operation will:
#    • Remove default Laravel controllers, models, views
#    • Remove default auth scaffolding
#    • Replace with Param RBAC implementation
#    • Update route files
#
# Do you want to continue? (yes/no) [no]: yes
```

#### Complete Setup

```bash
# Configure database
cp .env.example .env
# Edit .env with your database credentials

# Run migrations
php artisan migrate

# Seed default data (optional)
php artisan db:seed

# Install npm dependencies
npm install

# Build assets
npm run dev

# Start server
php artisan serve

# Visit http://localhost:8000
# Login: admin@example.com / password
```

## 🧪 Testing the Package

### Test Scenarios

#### Scenario 1: Clean Installation

```bash
# Create fresh project
laravel new fresh
cd fresh

# Add package
composer config repositories.local path /path/to/param-rbac
composer require param/rbac:dev-main

# Configure database
cp .env.example .env
# Edit .env

# Run install
php artisan param-rbac:install --force
php artisan migrate
npm install && npm run dev
php artisan serve
```

#### Scenario 2: Force Installation (Overwrite)

```bash
# In test directory with existing structure
php artisan param-rbac:install --force

# Should complete without prompting for confirmation
```

#### Scenario 3: Interactive Installation

```bash
# Without --force flag
php artisan param-rbac:install

# Should prompt for confirmation
# Should allow cancellation
```

#### Scenario 4: Reinstallation

```bash
# Simulate reinstalling over existing installation
rm -rf app/Http/Controllers app/Http/Middleware app/Models resources/views

php artisan param-rbac:install --force
php artisan migrate --fresh
npm run dev
```

### Verification Checklist

After installation, verify:

- [ ] **Controllers** - All custom controllers exist in `app/Http/Controllers/`
- [ ] **Models** - User, AuthSetting, PasswordRule, RoleMaster, UserTwoFactor exist
- [ ] **Migrations** - All custom migrations appear in migrations directory
- [ ] **Views** - Blade templates in `resources/views/` including layouts and auth
- [ ] **Routes** - `routes/web.php` and `routes/api.php` are populated
- [ ] **Config** - All config files are present and accessible
- [ ] **Middleware** - Custom middleware in `app/Http/Middleware/`
- [ ] **Assets** - CSS, JS, images in `public/assets/`
- [ ] **Database** - Tables created with `php artisan migrate`
- [ ] **Public Site** - Application displays correctly at http://localhost:8000
- [ ] **Login** - Can login with admin@example.com / password
- [ ] **Admin Panel** - All admin features accessible and functional

## 📝 Making Changes

### Adding New Controllers

1. Add controller to `stubs/app/Http/Controllers/`
2. Update routes in `stubs/routes/web.php`
3. Test in fresh installation
4. Commit changes

### Modifying Views

1. Edit template in `stubs/resources/views/`
2. Test layout rendering
3. Verify all pages display correctly
4. Commit changes

### Database Structure Changes

1. Create new migration in `stubs/database/migrations/`
2. Update seeders if needed
3. Test migration runs cleanly
4. Document breaking changes

### Configuration Options

1. Add config in `stubs/config/`
2. Update ServiceProvider to publish configs
3. Document available options in README

## 🔄 Version Management

### Semantic Versioning

```
Version: MAJOR.MINOR.PATCH
Example: 1.0.0

- MAJOR: Breaking changes, major features
- MINOR: New features, backward compatible
- PATCH: Bug fixes, updates
```

### Creating Release

1. **Update version in composer.json:**

```json
"version": "1.0.0"
```

2. **Create Git tag:**

```bash
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0
```

3. **Release notes** (in GitHub/CHANGELOG.md):

```markdown
# Version 1.0.0 - Initial Release

## Features
- Complete RBAC system
- Multi-factor authentication
- User management interface
- Permission management
- Role management
- Bootstrap 5 UI

## Requirements
- PHP 8.2+
- Laravel 10+

## Installation
composer require param/rbac
php artisan param-rbac:install
```

## 📦 Publishing to Packagist

### Prerequisites

1. GitHub account with repository
2. Packagist account
3. Repository at `github.com/your-username/param-rbac`

### Steps

1. **Create GitHub repository:**

```bash
cd /path/to/param-rbac
git init
git add .
git commit -m "Initial commit: Param RBAC v1.0.0"
git branch -M main
git remote add origin https://github.com/your-username/param-rbac.git
git push -u origin main
```

2. **Create release on GitHub:**

- Go to GitHub > Releases
- Click "Create a new release"
- Tag version: `v1.0.0`
- Title: `Param RBAC v1.0.0`
- Description: Release notes
- Publish release

3. **Submit to Packagist:**

- Visit [packagist.org](https://packagist.org)
- Click "Submit Package"
- Enter: `https://github.com/your-username/param-rbac.git`
- Click Submit
- Accept service provider

4. **Enable GitHub webhook:**

- Get Packagist API token from account settings
- Go to GitHub > Settings > Webhooks
- Add webhook:
  - Payload URL: `https://packagist.org/api/github`
  - Content type: application/json
  - Events: Push events
  - Active: Yes

5. **Verify:**

```bash
# Test installation from Packagist
composer create-project laravel/laravel my-test-project
cd my-test-project
composer require param/rbac
php artisan param-rbac:install
```

## 🐛 Troubleshooting Development

### Issue: Changes not reflected in test install

**Solution:**

```bash
# Clear Composer cache
composer clear-cache

# Reinstall package
composer update param/rbac

# Regenerate autoload
composer dump-autoload
```

### Issue: Cannot publish files

**Solution:**

```bash
# Check stubs directory exists
ls -la stubs/

# Verify file permissions
chmod -R 755 stubs/

# Test copy manually
cp -r stubs/app tests-app/app
```

### Issue: Migrations not running

**Solution:**

```bash
# Check migration files exist
php artisan migrate:status

# Run specific migration
php artisan migrate --path=database/migrations/2026_01_06_101500_add_address_to_users_table.php

# Reset and try again
php artisan migrate:fresh
```

### Issue: Service provider not loading

**Solution:**

```bash
# Check provider registration
php artisan provider:list

# Manually add to config/app.php if needed
'providers' => [
    // ... other providers
    ParamRbac\ParamRbacServiceProvider::class,
],

# Regenerate autoload
composer dump-autoload
```

## 📚 Testing Commands

```bash
# Verify package structure
find . -type f -name "*.php" | head -20

# Check PHP syntax
php -l src/ParamRbacServiceProvider.php
php -l src/Console/InstallCommand.php

# Validate composer.json
composer validate

# List installed versions
composer show param/rbac

# Check dependencies
composer depends param/rbac
```

## 🚀 Continuous Integration

### GitHub Actions (Optional)

Create `.github/workflows/tests.yml`:

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    strategy:
      matrix:
        php-version: ['8.2', '8.3']
        laravel-version: ['10.*', '11.*', '12.*']

    steps:
      - uses: actions/checkout@v3

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php-version }}

      - name: Install dependencies
        run: composer install

      - name: Run tests
        run: vendor/bin/phpunit
```

## 📞 Support & Issues

When maintaining the package:

1. **Set up Issues template** - Guide users on reporting
2. **Create Discussion board** - For questions and ideas
3. **Monitor Packagist** - Check for dependency conflicts
4. **Test major version updates** - Laravel updates may affect compatibility

---

**Happy developing! 🎉**
