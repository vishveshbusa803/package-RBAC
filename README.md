# Param RBAC - Laravel RBAC Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vishveshbusa/rbac.svg?style=flat-square)](https://packagist.org/packages/vishveshbusa/rbac)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

A comprehensive Role-Based Access Control (RBAC) package for Laravel that transforms a fresh Laravel installation into a fully featured RBAC system with authentication, permissions, roles, and complete UI scaffolding.

## ⚠️ **Important Note**

**This package is designed for fresh Laravel projects only.**

⚡ When you run the installation command, it will:
- Remove all default Laravel scaffolding (controllers, models, views, authentication UI)
- Replace it with the complete RBAC UI and system
- Modify your existing `composer.json` dependencies

**⚠️ MAKE SURE YOUR PROJECT IS BACKED UP** before installing this package, as it will significantly modify your project structure and configurations.

## 🎯 Features

- **Complete RBAC System** - Role-based access control with permissions and roles
- **Authentication System** - Multi-factor authentication support (2FA, Email OTP, Mobile OTP, CAPTCHA)
- **User Management** - Complete user CRUD operations with role assignment
- **Permission Management** - Create and manage module-based permissions
- **Role Management** - Full role lifecycle management with permission assignment
- **Beautiful UI** - Bootstrap 5-based responsive admin template
- **Database Migrations** - Pre-configured database structure
- **Seeders** - Sample data and admin user creation
- **Middleware** - Custom middleware for authentication and authorization
- **Service Providers** - Laravel service provider integration

## 📋 Requirements

- PHP ^8.2
- Laravel ^10|^11|^12|^13
- Composer
- Node.js & npm (for asset compilation)

## ⚙️ Installation

Install the package via Composer:

```bash
composer require vishveshbusa/rbac:1.0.0
```

Run the installation command:

```bash
php artisan param-rbac:install --force
```

Run migrations:

```bash
php artisan migrate:fresh --seed
```

Build frontend assets:

```bash
npm install
npm run dev
```


## 📁 Package Structure

```
param-rbac/
├── src/
│   ├── ParamRbacServiceProvider.php
│   └── Console/
│       └── InstallCommand.php
├── stubs/
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   ├── Middleware/
│   │   │   └── Kernel.php
│   │   ├── Models/
│   │   ├── Providers/
│   │   └── Exceptions/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── resources/
│   │   ├── views/
│   │   ├── js/
│   │   └── scss/
│   ├── public/
│   │   └── assets/
│   ├── routes/
│   └── config/
├── composer.json
└── README.md
```

## 🔐 Authentication Methods

The package supports multiple authentication methods that can be configured via the `authentication_settings` table:

### 1. **CAPTCHA** - Image-based verification
- Verify user is human with image captcha
- Default: Disabled

### 2. **EMAIL_VERIFY** - Email OTP verification
- Send one-time password to user email
- Default OTP: `111111`
- Expires: 10 minutes
- Attempts: 3

### 3. **MOBILE_VERIFY** - Mobile OTP verification
- Send one-time password to user phone
- Default OTP: `222222`
- Attempts: 3

### 4. **TWO_FACTOR** - Google Authenticator 2FA
- TOTP-based two-factor authentication
- Default Code: `333333`
- Uses google-authenticator mobile app

## 👥 Default Users

After installation, the following default user is created:

| Email | Password | Role |
|-------|----------|------|
| `admin@example.com` | `password` | Admin |

## 🛠️ Configuration Files

Key configuration files published by the package:

- `config/app.php` - Application settings
- `config/auth.php` - Authentication configuration
- `config/database.php` - Database configuration
- `config/permission.php` - Spatie permission settings
- `config/mail.php` - Mail/SMTP settings

## 📚 Database Structure

### Key Tables

- `users` - User accounts
- `roles` - Available roles
- `permissions` - System permissions
- `role_has_permissions` - Role-permission relationships
- `model_has_roles` - User-role assignments
- `authentication_settings` - Auth method configurations
- `user_two_factor` - 2FA secret keys
- `password_rules` - Password policy configurations

## 🔄 Workflow

### User Registration/Login Flow

```
1. User submits login credentials
2. CAPTCHA verification (if enabled)
3. Credentials validation
4. Email OTP verification (if enabled)
5. Mobile OTP verification (if enabled)
6. Two-Factor Authentication (if enabled)
7. Session established and user logged in
```

## 🎭 Role & Permission Manager

### Creating Roles

Navigate to **Admin > Roles > Create Role** and:
1. Enter role name
2. Select permissions (grouped by module)
3. Click "Create"

### Managing Permissions

Navigate to **Admin > Permissions** to:
- Create new permission modules with operations
- Edit existing permission structures
- Delete permissions (if not assigned)
- View permission assignments

### Assigning Roles to Users

In **User Management**:
1. Select a user
2. Choose one or more roles
3. Save changes

## 🔧 Customization

### Modifying Authentication Settings

Edit the `authentication_settings` table:

```sql
UPDATE authentication_settings
SET IsEnabled = 1
WHERE AuthCode = 'TWO_FACTOR';
```

### Adding Custom Controllers

Place custom controllers in `app/Http/Controllers/`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class YourController extends Controller
{
    // Your logic here
}
```

### Extending Models

All models use Laravel's standard conventions and can be extended:

```php
<?php

namespace App\Models;

class User extends AuthenticatableUser
{
    // Add custom methods
    public function customMethod()
    {
        //
    }
}
```

## 🧪 Testing Locally

### Development Setup

1. Create a test Laravel project:
```bash
laravel new test-project
cd test-project
```

2. Install the package from your local directory:
```bash
composer config repositories.local path /path/to/param-rbac
composer require param/rbac:dev-main
```

3. Run the install command:
```bash
php artisan param-rbac:install
php artisan migrate
npm install && npm run dev
```

### Reset Installation

To reinstall the package in your test project:

```bash
# Remove package from composer.json and autoload
composer remove param/rbac

# Clear all scaffolding
rm -rf app/Http/Controllers app/Http/Middleware app/Models resources/views public/assets

# Run installation again
composer require param/rbac
php artisan param-rbac:install --force
```

## 📦 Publishing to Packagist

### Step 1: Create Packagist Account
- Visit [packagist.org](https://packagist.org)
- Sign up for a free account

### Step 2: Create GitHub Repository

```bash
cd /path/to/param-rbac
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/yourusername/param-rbac.git
git push -u origin main
```

### Step 3: Add to Packagist

1. Log in to Packagist
2. Click "Submit Package"
3. Enter: `https://github.com/yourusername/param-rbac.git`
4. Click Submit

### Step 4: Enable GitHub Hook

In Packagist:
1. Go to your package page
2. Click "Show API Token"
3. In GitHub repo: Settings > Webhooks
4. Add webhook URL: `https://packagist.org/api/github`

## 🔍 Troubleshooting

### Composer Dependency Conflict

**Error:** `illuminate/support ^10|^11|^12 -> found illuminate/support[...] but these were not loaded, likely because it conflicts with another require.`

**Cause:** A dependency in your Laravel project has a conflicting `illuminate/support` requirement.

**Solutions:**

1. **Check your Laravel version**:
```bash
composer show | grep laravel/framework
```

2. **Update with dependency resolution**:
```bash
composer require vishveshbusa/rbac:1.0.0 --with-all-dependencies
```

3. **Clear composer cache and retry**:
```bash
composer clear-cache
composer require vishveshbusa/rbac:1.0.0
```

4. **Verify all dependencies match your Laravel version**:
```bash
composer show | grep -E "laravel|illuminate|spatie|google2fa|datatables"
```

### Package Not Auto-Discovering

```bash
# Clear composer cache and re-dump autoload
composer clear-cache
composer dump-autoload
```

### Command Not Found

```bash
# Verify service provider is registered
php artisan provider:list | grep Param

# If not found, check config/app.php
```

### Files Not Publishing

```bash
# Manually publish resources
php artisan vendor:publish --provider="ParamRbac\\ParamRbacServiceProvider"
```

### Database Migration Errors

```bash
# Check migrations are in database directory
php artisan migrate:status

# Run specific migration
php artisan migrate --path=database/migrations/2026_01_06_101500_add_address_to_users_table.php
```

## 📝 Environment Setup Checklist

- [ ] PHP 8.2+ installed
- [ ] Composer installed
- [ ] Laravel project created
- [ ] Database configured (.env file)
- [ ] Package installed via composer
- [ ] `php artisan param-rbac:install` executed
- [ ] Migrations run with `php artisan migrate`
- [ ] npm packages installed
- [ ] Assets compiled (`npm run dev`)
- [ ] Application running and accessible
- [ ] Admin login successful

## 🤝 Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

## 📄 License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## 🙏 Credits

- Built with [Laravel](https://laravel.com)
- Uses [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- Authentication templates based on [AdminLTE](https://adminlte.io/)
- 2FA powered by [Google2FA](https://github.com/antonioribeiro/google2fa-laravel)

## 📧 Support

For issues, questions, or suggestions:

- Open an issue on GitHub
- Check the [documentation](./README.md)
- Email: support@param.dev

---

**Happy coding! 🚀**
