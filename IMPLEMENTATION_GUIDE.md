# IMPLEMENTATION GUIDE - How to Use This Package

## Quick Start for Your Main Project

### Step 1: Update Main Project's composer.json

Add this section to your **main Laravel project's `composer.json`** (at the root, not in the package):

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./packages/param/rbac"
        }
    ],
    "require": {
        "param/rbac": "*"
    }
}
```

**Full example**: See `packages/param/rbac/composer.example.json`

### Step 2: Install the Package

From your project root (not from package directory):

```bash
composer require param/rbac
```

This will:
- Copy package files
- Register service provider
- Load migrations
- Install dependencies (Spatie, Google2FA, etc.)

### Step 3: Run Migrations

```bash
php artisan migrate
```

This creates all 6 tables:
- users
- authentication_settings
- password_rules
- user_two_factor
- email_login_otp

### Step 4: Verify Installation

```bash
php artisan tinker
>>> Param\RBAC\Models\User::count()
```

Two options:
- Option A: Use package models directly with `Param\RBAC\Models\` prefix
- Option B: Publish to app and customize

### Step 5: Start Using

That's it! Your project now has:
- ✅ Database structure
- ✅ Pre-built models
- ✅ Controllers ready to extend
- ✅ Two-factor authentication
- ✅ Role-based permissions

---

## File Organization

```
Your Project Root/
│
├── composer.json         ← UPDATE THIS to add repository
├── .env
├── .env.example
│
├── app/
│   ├── Models/
│   │   └── (use Param\RBAC\Models\* or publish here)
│   └── Http/
│       └── Controllers/
│           └── (extend package controllers or publish)
│
├── packages/
│   └── param/
│       └── project-setup/    ← PACKAGE LOCATION
│           ├── src/
│           │   ├── Models/
│           │   ├── Http/Controllers/
│           │   └── ProjectSetupServiceProvider.php
│           ├── database/
│           │   └── migrations/
│           ├── composer.json
│           └── [DOCUMENTATION FILES]
│
├── database/
│   ├── migrations/
│   └── seeders/
│
├── routes/
│   ├── web.php
│   └── api.php
│
└── [other app files]
```

---

## Using Package Models

### Option 1: Use Package Models Directly (Recommended for Quick Start)

```php
<?php

use Param\RBAC\Models\User;
use Param\RBAC\Models\AuthSetting;

// In your controller
$user = User::find(1);
$authSettings = AuthSetting::getSetting('GOOGLE_2FA');
```

### Option 2: Publish to Your App (For Customization)

```bash
php artisan vendor:publish --provider="Param\\RBAC\\ProjectSetupServiceProvider" --tag="project-setup-models"
```

Then edit in `app/Models/`:

```php
<?php

namespace App\Models;

use Param\RBAC\Models\User as PackageUser;

class User extends PackageUser
{
    // Your custom methods here
    public function customMethod()
    {
        return 'my custom logic';
    }
}
```

---

## Using Package Controllers

### Option 1: Extend Package Controllers

```php
<?php

namespace App\Http\Controllers;

use Param\RBAC\Http\Controllers\HomeController as PackageHomeController;

class HomeController extends PackageHomeController
{
    // Override methods as needed
    public function index(Request $request)
    {
        $custom = 'my custom logic';
        return parent::index($request);
    }
}
```

### Option 2: Publish Controllers

```bash
php artisan vendor:publish --provider="Param\\RBAC\\ProjectSetupServiceProvider" --tag="project-setup-controllers"
```

---

## Database Setup

### First Run (Fresh Installation)

```bash
# All migrations run automatically
php artisan migrate

# Check status
php artisan migrate:status
```

### Fresh Start (Resets Database)

```bash
# WARNING: Deletes all data!
php artisan migrate:fresh

# With seeding
php artisan migrate:fresh --seed
```

### Rollback

```bash
# Undo last migration
php artisan migrate:rollback

# Undo all
php artisan migrate:reset
```

---

## Available Routes Setup

The package includes base controllers. Add these to your `routes/web.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use Param\RBAC\Http\Controllers\HomeController;
use Param\RBAC\Http\Controllers\AuthSettingController;

// Home routes
Route::get('/', [HomeController::class, 'root'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.index');

// Auth Settings routes (requires auth)
Route::middleware('auth')->group(function () {
    Route::get('/settings/auth', [AuthSettingController::class, 'index'])->name('auth-settings');
    Route::get('/api/auth-settings', [AuthSettingController::class, 'getAuthenticationSettings']);
    Route::post('/api/auth-settings', [AuthSettingController::class, 'updateAuthenticationSettings']);
    Route::get('/api/password-rules', [AuthSettingController::class, 'getPasswordRules']);
    Route::post('/api/password-rules', [AuthSettingController::class, 'updatePasswordRules']);
});
```

---

## Environment Configuration

Update `.env` with the package configuration:

```env
# App Configuration
APP_NAME="Your Project Name"
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database (package uses 6 tables)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=

# Two-Factor Authentication
GOOGLE_2FA_ENABLED=true

# Session
SESSION_DRIVER=cookie
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=file
```

---

## Authentication Setup

The package user model includes Spatie permissions. Setup roles:

```php
// In a seeder or command
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Param\RBAC\Models\User;

// Create roles
Role::create(['name' => 'admin']);
Role::create(['name' => 'user']);
Role::create(['name' => 'moderator']);

// Create permissions
Permission::create(['name' => 'edit posts']);
Permission::create(['name' => 'delete posts']);

// Assign to user
$user = User::find(1);
$user->assignRole('admin');
$user->givePermissionTo('edit posts');
```

---

## Two-Factor Authentication

### Enable for User

```php
use Param\RBAC\Models\UserTwoFactor;
use PragmaRX\Google2FA\Google2FA;

$user = auth()->user();

$google2fa = new Google2FA();
$secret = $google2fa->generateSecretKey();

UserTwoFactor::updateOrCreate(
    ['user_id' => $user->id],
    [
        'secret_key' => $secret,
        'is_enabled' => true,
        'is_setup_completed' => true,
    ]
);
```

### Verify Code

```php
$google2fa = new Google2FA();
$twoFactor = UserTwoFactor::where('user_id', auth()->id())->first();

if ($google2fa->verifyKey($twoFactor->secret_key, $code)) {
    // Valid code
} else {
    // Invalid code
}
```

---

## Documentation for Team

### Share This With New Developers

Link them to: **`packages/param/rbac/INDEX.md`**

They'll find all documentation there organized by role:
- New developers → QUICKSTART.md + INSTALLATION.md
- DevOps → PACKAGE_SETUP.md + CONFIGURATION.md
- Back-end developers → README.md + CONFIGURATION.md
- Architects → PACKAGE_SETUP.md + README.md

---

## Directory Listing

```bash
# View package contents
ls -la packages/param/rbac/

# Check size
du -sh packages/param/rbac/

# Tree view (if tree command available)
tree packages/param/rbac/ -L 2
```

---

## Troubleshooting Integration

### "Package not found" error

Check:
1. `composer.json` has correct repository path
2. `packages/param/rbac/` folder exists
3. Path uses `./` relative notation
4. Run `composer clear-cache` then `composer install`

### "Class not found" error

Solution:
```bash
composer dump-autoload
php artisan cache:clear
```

### Migrations not running

Check:
1. Database connection in `.env` is correct
2. Database exists
3. Try: `php artisan migrate --fresh`

### Service provider not detected

Ensure:
1. Package requires Laravel auto-discovery
2. Run: `php artisan package:discover`
3. Check `bootstrap/cache/packages.php`

---

## Version Management

For production, pin the version:

```json
{
    "require": {
        "param/rbac": "^1.0"
    }
}
```

For development:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./packages/param/rbac"
        }
    ],
    "require": {
        "param/rbac": "*"
    }
}
```

---

## Next Steps

1. ✅ Read this file (IMPLEMENTATION_GUIDE.md)
2. ✅ Update your `composer.json` to include the repository
3. ✅ Run `composer require param/rbac`
4. ✅ Run `php artisan migrate`
5. ✅ Test with `php artisan tinker`
6. ✅ Share `packages/param/rbac/INDEX.md` with your team
7. ✅ Start building features on top of the base structure!

---

**Package is ready to use! 🚀**

For questions, check the relevant documentation file:
- **Quick setup**: INDEX.md → QUICKSTART.md
- **New developer**: INSTALLATION.md
- **Configuration**: CONFIGURATION.md
- **Deployment**: PACKAGE_SETUP.md
- **API Reference**: README.md
