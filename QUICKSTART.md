# Quick Start Guide

Get up and running in 5 minutes!

## Prerequisites Checklist

- [ ] PHP 8.2+ installed
- [ ] MySQL running
- [ ] Composer installed
- [ ] Node.js installed
- [ ] Git access to project

## 5-Minute Setup

### 1. Clone & Install (2 min)

```bash
git clone <repo-url>
cd project
composer install
npm install
```

### 2. Configure Environment (1 min)

```bash
cp .env.example .env

# Edit .env and set:
# DB_DATABASE=your_database
# DB_USERNAME=root
# DB_PASSWORD=your_password
```

### 3. Generate Key (30 sec)

```bash
php artisan key:generate
```

### 4. Setup Database (1 min)

```bash
php artisan migrate
```

> **Note**: Package migrations extend Laravel's default schema by adding `address` field to users table and creating additional RBAC tables.

### 5. Start Development (30 sec)

```bash
# Terminal 1:
php artisan serve

# Terminal 2:
npm run hot
```

**App ready at:** `http://localhost:8000`

---

## Common Commands

```bash
# Clear caches
php artisan cache:clear
php artisan config:clear

# Database
php artisan migrate
php artisan migrate:refresh
php artisan db:seed

# View logs
tail -f storage/logs/laravel.log

# Interactive shell
php artisan tinker

# Run tests
php artisan test
```

---

## Database Access

Via MySQL client:

```bash
mysql -u root -p
USE param_rbac;
SHOW TABLES;
```

Or use Laravel:

```bash
php artisan tinker
>>> DB::table('users')->get()
```

---

## Package Models

Quick reference:

```php
use Param\RBAC\Models\{
    User,
    AuthSetting,
    PasswordRule,
    RoleMaster,
    UserTwoFactor
};

// Examples
$user = User::first();
$settings = AuthSetting::getSetting('GOOGLE_2FA');
$rules = PasswordRule::all();
```

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| "Connection refused" | Check DB_HOST in .env, start MySQL |
| "No key generated" | Run `php artisan key:generate` |
| "npm not found" | Install Node.js |
| "Permission denied" | Run `chmod -R 755 storage bootstrap` |

---

## Need More Help?

- **Installation Details**: See [INSTALLATION.md](INSTALLATION.md)
- **Configuration**: See [CONFIGURATION.md](CONFIGURATION.md)
- **Package Setup**: See [PACKAGE_SETUP.md](PACKAGE_SETUP.md)
- **Package Info**: See [README.md](README.md)

---

## Next Steps

1. ✅ Review the dashboard at `http://localhost:8000`
2. ✅ Explore database tables in MySQL
3. ✅ Check `routes/web.php` for available routes
4. ✅ Review models in `app/Models/`
5. ✅ Start developing!

---

**Happy coding! 🚀**
