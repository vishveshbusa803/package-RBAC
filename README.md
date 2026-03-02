# Param RBAC Package

Complete Laravel project setup package with models, migrations, controllers, and authentication system. This package includes everything needed to get a new developer started quickly with the project.

## Features

- **Pre-built Models**: User, AuthSetting, PasswordRule, RoleMaster, UserTwoFactor
- **Database Migrations**: All custom tables ready to migrate
- **Authentication System**: Two-factor authentication support
- **Permission System**: Integration with Spatie Laravel Permission
- **Controllers**: Pre-built controllers for easy extension
- **Complete Admin Theme**: Responsive Bootstrap 5 design with SCSS customization
- **Design Assets**: Fonts, icons, images, and compiled CSS/JS
- **SCSS Source Files**: Customizable styling with variables and dark mode
- **Third-party Libraries**: DataTables, Chart.js, ApexCharts, CKEditor, and more
- **Automatic Setup**: Migrations and assets run automatically on composer installation

## Installation

### Step 1: Add to composer.json

Add the package repository to your Laravel project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "./packages/param/rbac"
        }
    ]
}
```

### Step 2: Require the Package

Run the following command:

```bash
composer require param/rbac
```

### Step 3: Publish Assets (Optional)

If you want to copy the models and controllers to your app:

```bash
# Publish models
php artisan vendor:publish --provider="Param\\RBAC\\ProjectSetupServiceProvider" --tag="project-setup-models"

# Publish controllers
php artisan vendor:publish --provider="Param\\RBAC\\ProjectSetupServiceProvider" --tag="project-setup-controllers"

# Publish public assets (CSS, JS, fonts, images, libraries)
php artisan vendor:publish --provider="Param\\RBAC\\ProjectSetupServiceProvider" --tag="project-setup-assets"

# Publish resources (SCSS sources, fonts, images, JS sources)
php artisan vendor:publish --provider="Param\\RBAC\\ProjectSetupServiceProvider" --tag="project-setup-resources"

# Publish everything at once
php artisan vendor:publish --provider="Param\\RBAC\\ProjectSetupServiceProvider"
```

### Step 4: Run Migrations

```bash
php artisan migrate
```

## Database Schema

The package creates the following tables through migrations:

### 1. users (Modified)
- Extended from Laravel's default users table
- Adds custom `address` field (nullable, text)
- Contains: id, name, email, password, address, timestamps
- **Note**: The package modifies the existing users table created by Laravel, not creates a new one

### 2. authentication_settings
- AuthSettingId (primary key)
- AuthCode (unique)
- AuthName
- IsEnabled (bool)
- OTPAttempt
- OTPResetTime
- OTPExpiryTime
- CreatedOn
- UpdatedOn
- DeletionDate
- IsActive
- UserId

### 3. password_rules
- RuleId (primary key)
- RuleCode
- RuleName
- IsEnabled (bool)
- RuleValue
- CreatedOn
- UpdatedOn
- IsActive
- timestamps

### 4. user_two_factor
- id (primary key)
- emp_id
- user_id (foreign key to users)
- secret_key
- is_enabled (bool)
- is_active (bool)
- is_setup_completed (bool)
- timestamps

### 5. email_login_otp
- id (primary key)
- emp_id
- email
- otp_type
- otp_hash
- expiry_time
- is_used (bool)
- used_at
- timestamps



## Models

All models are available in your application after installation. You can use them directly:

```php
use Param\RBAC\Models\User;
use Param\RBAC\Models\AuthSetting;
use Param\RBAC\Models\PasswordRule;
use Param\RBAC\Models\RoleMaster;
use Param\RBAC\Models\UserTwoFactor;

// Example usage
$user = User::find(1);
$authSettings = AuthSetting::getSetting('GOOGLE_2FA');
$passwordRules = PasswordRule::all();
```

## Key Features

### User Model
- Includes Spatie Permission traits for role-based access control
- Two-factor authentication relationship
- Email verification support

### AuthSetting Model
- Manage authentication methods and settings
- OTP configuration
- Built-in `getSetting()` helper method

### UserTwoFactor Model
- Two-factor authentication setup
- Google 2FA support integration
- User relationship

## Theme & Design

The package includes a complete responsive admin theme with:

- **Bootstrap 5.3.5**: Modern CSS framework with customization
- **SCSS Source Files**: Easily customizable styling
- **Dark Mode**: Built-in light and dark theme support
- **Icon Libraries**: FontAwesome, Material Design, Bootstrap Icons, and more
- **Charts & Tables**: DataTables, Chart.js, ApexCharts integration
- **RTL Support**: Ready for right-to-left languages
- **Responsive Design**: Works perfectly on mobile, tablet, and desktop

### Publishing Theme Assets

```bash
# Publish all design assets and resources
php artisan vendor:publish --provider="Param\\RBAC\\ProjectSetupServiceProvider" --tag="project-setup-assets"
php artisan vendor:publish --provider="Param\\RBAC\\ProjectSetupServiceProvider" --tag="project-setup-resources"
```

### Building CSS/JS

```bash
# Development with watch mode
npm run hot

# Production build
npm run production
```

**For detailed theme customization, see [THEME_GUIDE.md](THEME_GUIDE.md)**

## Environment Setup for New Developers

After installing the package, a new developer should:

1. Clone the project
2. Run `composer install`
3. Copy `.env.example` to `.env`
4. Set database credentials in `.env`
5. Run `php artisan key:generate`
6. Run `php artisan migrate` (migrations are automatic with this package)
7. Run `npm install && npm run dev`
8. Start the application with `php artisan serve`

## Requirements

- PHP 8.2 or higher
- Laravel 12.0 or higher
- MySQL/MariaDB or any Laravel-supported database

## Dependencies

This package requires:
- `laravel/framework`: ^12.0
- `spatie/laravel-permission`: ^6.24
- `pragmarx/google2fa-laravel`: ^2.3

## License

MIT License - see LICENSE file for details

## Support

For issues or questions, contact the development team.

## Notes

- All migrations are automatically published and loaded
- Models use custom primary keys and table names as per your requirements
- Two-factor authentication is pre-configured but requires proper setup in controllers
- Ensure your `.env` file has correct database credentials before running migrations

## Quick Start

```bash
# Clone/pull the project
git clone <your-repo>
cd <project-directory>

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Migrations run automatically when package is installed
# If needed, manually run:
php artisan migrate

# Compile assets
npm run dev

# Start development server
php artisan serve
```

The application will be available at `http://localhost:8000`
