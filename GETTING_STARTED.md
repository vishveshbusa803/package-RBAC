# Getting Started with Param RBAC

Complete step-by-step guide to install and start using the param-rbac package.

## 🎯 What is Param RBAC?

Param RBAC is a complete Role-Based Access Control system for Laravel that:

- **Transforms Laravel** - Turns a fresh Laravel installation into a fully functional RBAC system
- **Provides Authentication** - Multi-method login with 2FA, OTP, CAPTCHA
- **Manages Roles & Permissions** - Easy role and permission management
- **Includes Admin Panel** - Beautiful UI for managing users, roles, permissions
- **Database Ready** - Pre-configured database schema with migrations
- **UI Complete** - Bootstrap 5 based responsive design

## 📋 Prerequisites

Before you begin, make sure you have:

- **PHP 8.2** or higher
- **Composer** installed
- **Laravel 10, 11, or 12**
- **Database** (MySQL, PostgreSQL, or SQLite)
- **Node.js & npm** (for asset compilation)
- **Git** (optional, for version control)

### Check Your System

```bash
# Check PHP version
php --version
# Output should be: PHP 8.2.0 or higher

# Check Composer
composer --version
# Output should be: Composer version

# Check Node.js
node --version
npm --version
# Both should be recent versions
```

Not installed? Quick setup:
- [PHP Installation](https://www.php.net/manual/en/install.php)
- [Composer](https://getcomposer.org/download/)
- [Node.js & npm](https://nodejs.org/)

## 🚀 Installation Steps

### Step 1: Create Fresh Laravel Project

```bash
# Using Laravel installer
laravel new my-project
cd my-project

# OR using Composer
composer create-project laravel/laravel my-project
cd my-project
```

### Step 2: Configure Database

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

**Update .env file with your database credentials:**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=param_rbac_db
DB_USERNAME=root
DB_PASSWORD=
```

**Using SQLite (easiest for testing):**

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

Create SQLite database:

```bash
touch database/database.sqlite
```

### Step 3: Require the Package

```bash
composer require param/rbac
```

**Output should show:**

```
Using version ^1.0 for param/rbac
...
Updating dependencies
...
✓ Complete
```

### Step 4: Run Installation Command

```bash
php artisan param-rbac:install --force
```

**What this does:**

1. ✓ Removes default Laravel scaffolding
2. ✓ Publishes all package files
3. ✓ Installs required dependencies
4. ✓ Prepares the application

**Expected output:**

```
🚀 Installing Param RBAC Package...

📦 Step 1: Removing default Laravel scaffolding...
  ✓ Removed: controllers (app/Http/Controllers)
  ✓ Removed: models (app/Models)
  ✓ Removed: views (resources/views)

📂 Step 2: Publishing package files...
  ✓ Published: app/Http/Controllers
  ✓ Published: app/Models
  [... more files ...]

📥 Step 3: Installing required dependencies...
  ✓ Installing: spatie/laravel-permission:^6.0
  [... more packages ...]

🔐 Step 4: Publishing Spatie permission tables...
  ✓ Spatie permission tables published

✅ Param RBAC installation completed successfully!

📝 Next steps:
   1. Configure your .env file with database credentials
   2. Run: php artisan migrate
   3. Run: php artisan db:seed
   4. Install npm packages: npm install
   5. Build assets: npm run dev
   6. Start server: php artisan serve

🎉 Visit http://localhost:8000 and login with admin@example.com / password
```

### Step 5: Run Database Migrations

```bash
php artisan migrate

# Expected output:
# Migration table created successfully.
# Migrated: 2026_01_06_101500_add_address_to_users_table
# Migrated: 2026_01_06_101550_create_authentication_settings_table
# [... more migrations ...]
```

### Step 6: Seed Default Data (Optional)

This creates a default admin user:

```bash
php artisan db:seed

# Expected output:
# Seeding: Database\Seeders\DatabaseSeeder
# Seeded: Database\Seeders\UserSeeder
# Database seeding completed successfully.
```

### Step 7: Install Frontend Dependencies

```bash
npm install

# This installs all npm packages listed in package.json
# Takes a few minutes depending on internet speed
```

### Step 8: Build Assets

```bash
# Development build
npm run dev

# Production build (optimized)
npm run production
```

### Step 9: Start the Server

```bash
php artisan serve

# Output:
#   INFO  Server running on [http://127.0.0.1:8000]
#
#   Press Ctrl+C to quit
```

### Step 10: Access the Application

Open your browser and visit:

```
http://localhost:8000
```

You should see the login page!

## 🔓 Default Credentials

After installation with seeding, use these credentials:

| Field | Value |
|-------|-------|
| Email | `admin@example.com` |
| Password | `password` |

## 🎭 First Login Experience

### Login Steps

1. **Enter Email**: `admin@example.com`
2. **Enter Password**: `password`
3. **CAPTCHA** (if enabled): Click to verify
4. **OTP Verification** (if enabled):
   - Email OTP: `111111`
   - Mobile OTP: `222222`
5. **2FA Code** (if enabled): `333333`
6. **Dashboard**: You're logged in!

## 📊 Exploring the Admin Panel

After login, you'll see the dashboard. Key sections:

### 👥 User Management

Navigate to **Admin > Users** to:
- View all users
- Create new users
- Edit user details
- Assign roles
- Delete users

### 🔐 Roles Management

Navigate to **Admin > Roles** to:
- View all roles
- Create new roles with permissions
- Edit role permissions
- Delete roles

### ✅ Permissions Management

Navigate to **Admin > Permissions** to:
- View all permissions
- Create permission modules
- Edit permissions
- Delete permissions

## ⚙️ Configuration

### Authentication Settings

Edit `app/Models/AuthSetting.php` or directly in database:

```bash
php artisan tinker
```

```php
use App\Models\AuthSetting;

// Enable/disable features
AuthSetting::where('AuthCode', 'CAPTCHA')->update(['IsEnabled' => 1]);
AuthSetting::where('AuthCode', 'EMAIL_VERIFY')->update(['IsEnabled' => 1]);
AuthSetting::where('AuthCode', 'TWO_FACTOR')->update(['IsEnabled' => 1]);

exit
```

### Application Settings

Edit `.env`:

```env
APP_NAME="My RBAC Application"
APP_ENV=local
APP_DEBUG=true

# Mail settings for OTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
```

## 🧪 Testing Features

### Create a Test Role

1. Go to **Admin > Roles > Create Role**
2. Enter name: `Editor`
3. Select permissions:
   - `post-create`
   - `post-edit`
   - `post-view`
4. Click Create

### Create a Test User

1. Go to **Admin > Users > Create User**
2. Fill in details:
   - Name: `John Doe`
   - Email: `john@example.com`
   - Password: `password123`
3. Assign role: `Editor`
4. Click Create

### Test Login

1. Logout from admin
2. Login with `john@example.com` / `password123`
3. Verify Editor can only access assigned permissions

## 📁 Project Structure

After installation, your project structure looks like:

```
my-project/
├── app/
│   ├── Models/                    # Database models
│   │   ├── User.php
│   │   ├── AuthSetting.php
│   │   └── [other models]
│   └── Http/
│       ├── Controllers/           # Controllers
│       │   ├── Auth/
│       │   ├── RoleController.php
│       │   └── [other controllers]
│       └── Middleware/
├── database/
│   ├── migrations/               # Database schema
│   ├── seeders/                  # Sample data
│   └── database.sqlite
├── resources/
│   ├── views/                    # Blade templates
│   ├── js/                       # JavaScript
│   └── scss/                     # Stylesheets
├── routes/
│   ├── web.php                   # Web routes
│   └── api.php                   # API routes
├── config/                       # Configuration files
├── storage/                      # Application storage
├── public/                       # Web root
│   └── assets/                   # CSS, JS, images
├── .env                          # Environment variables
└── composer.json                 # PHP dependencies
```

## 🐛 Common Issues

### Issue: "Command not found: param-rbac:install"

**Solution:** Clear Composer cache

```bash
composer clear-cache
composer dump-autoload
php artisan list | grep param-rbac
```

### Issue: "SQLSTATE: No such table"

**Solution:** Run migrations

```bash
php artisan migrate
php artisan migrate:fresh  # If needed to reset
```

### Issue: "Class 'App\Models\User' not found"

**Solution:** Regenerate autoloader

```bash
composer dump-autoload
```

### Issue: Assets not loading (CSS/JS)

**Solution:** Build assets

```bash
npm install
npm run dev

# Or with watch mode
npm run watch
```

### Issue: "Could not find driver" (Database connection)

**Solution:** Verify database connection in `.env`

```bash
# Test connection
php artisan tinker
> DB::connection()->getPdo();
> exit
```

### Issue: Blank page or 500 error

**Check Laravel logs:**

```bash
tail -f storage/logs/laravel.log
```

## 📚 Next Steps

After successful installation:

1. **Customize Theme** - Edit `resources/views/layouts/`
2. **Add Your Models** - Create new models in `app/Models/`
3. **Create Controllers** - Add controllers in `app/Http/Controllers/`
4. **Define Routes** - Add routes in `routes/web.php`
5. **Set Permissions** - Create permissions in admin panel
6. **Assign Roles** - Assign roles to users
7. **Build Features** - Create your application features

## 🔗 Useful Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Database commands
php artisan migrate                 # Run migrations
php artisan migrate:rollback       # Undo last migration
php artisan migrate:fresh          # Reset database
php artisan db:seed                # Seed database
php artisan tinker                 # Interactive PHP shell

# User management
php artisan tinker
> $user = App\Models\User::find(1);
> $user->assignRole('admin');
> $user->givePermissionTo('post-create');

# Artisan serve options
php artisan serve --host=0.0.0.0 --port=8000

# Build options
npm run dev         # Development build
npm run production  # Production build
npm run watch      # Watch for changes
```

## 📞 Getting Help

If you encounter issues:

1. **Check the Documentation**: See README.md for detailed info
2. **Check Development Guide**: DEVELOPMENT_GUIDE.md for advanced setup
3. **Review Logs**: `storage/logs/laravel.log`
4. **Test Manually**: Use `php artisan tinker` to debug
5. **Contact Support**: Open an issue or contact support

## ✅ Completion Checklist

You're ready to go when you can:

- [ ] Create a fresh Laravel project
- [ ] Install param-rbac package
- [ ] Run migrations successfully
- [ ] Login with admin@example.com
- [ ] Access admin panel
- [ ] Create roles
- [ ] Create users
- [ ] Assign permissions
- [ ] Assets display correctly (CSS/JS working)
- [ ] Can test a simple feature

---

**Congratulations! You're ready to build with Param RBAC! 🎉**

For more information, see the [README.md](README.md) and [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md)
