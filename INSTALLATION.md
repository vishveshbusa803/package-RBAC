# Installation Guide for New Developers

This guide will help you set up the project quickly on your local machine.

## Prerequisites

- PHP 8.2 or higher
- Composer (latest version)
- Node.js and npm
- MySQL/MariaDB (or any Laravel-supported database)
- Git

## Step-by-Step Installation

### 1. Clone the Repository

```bash
git clone <your-repository-url>
cd <project-directory>
```

### 2. Install PHP Dependencies

```bash
composer install
```

This will automatically install the `param/rbac` package along with all required dependencies. The package will:
- Register all custom models
- Load all database migrations
- Set up the authentication system

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Configure Environment Variables

```bash
# Copy the example environment file
cp .env.example .env
```

Edit `.env` and update the following:

```env
APP_NAME="Your Project Name"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Database Migrations

All migrations from the `param/rbac` package are automatically loaded. Run:

```bash
php artisan migrate
```

This will:
1. Create Laravel's default tables (users, cache, jobs)
2. Modify the users table to add custom `address` field
3. Create additional tables:
   - authentication_settings
   - password_rules
   - user_two_factor
   - email_login_otp

**⚠️ Important**: The package uses `ALTER TABLE` for the users table (not `CREATE TABLE`) to avoid conflicts with Laravel's default migrations.
- email_login_otp
- And other Laravel default tables (if not already present)

### 7. Seed Database (Optional)

If you have seeders set up:

```bash
php artisan db:seed
```

### 8. Build Frontend Assets

```bash
# For development (with auto-reload)
npm run hot

# OR for production build
npm run production
```

### 9. Start the Development Server

```bash
php artisan serve
```

The application will be available at: **http://localhost:8000**

## Verification

After installation, verify everything is working:

1. Navigate to `http://localhost:8000` in your browser
2. You should see the application homepage
3. Check the database for the created tables in MySQL

## Quick Commands Reference

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Fresh database (WARNING: This deletes all data!)
php artisan migrate:fresh

# Tinker - Interactive shell
php artisan tinker

# Run tests
php artisan test

# Generate API documentation
php artisan ide-helper:generate
```

## Troubleshooting

### Database Connection Error

**Problem**: "SQLSTATE[HY000] [2002] Connection refused"

**Solution**:
- Ensure MySQL is running: `mysql -u root -p`
- Verify database credentials in `.env`
- Ensure the database exists: Create it manually in MySQL if needed

### Permission Error on Storage

**Problem**: "Permission denied" for storage folder

**Solution**:
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Composer out of memory

**Problem**: "Allowed memory size exhausted"

**Solution**:
```bash
php -d memory_limit=-1 /usr/local/bin/composer install
```

### Node modules not found

**Problem**: npm packages not working

**Solution**:
```bash
rm -rf node_modules package-lock.json
npm install
```

## Package Information

This project uses the `param/rbac` Laravel package which provides:

- **Models**: User, AuthSetting, PasswordRule, RoleMaster, UserTwoFactor
- **Migrations**: Database schema for all models
- **Service Provider**: Automatic registration and loading
- **Authentication**: Two-factor authentication support with Google2FA

For more information, see the [Package README](../../packages/param/rbac/README.md)

## Getting Help

- Check the main README.md in project root
- Review Laravel documentation: https://laravel.com/docs
- Check package documentation: See packages/param/rbac/README.md
- Contact the development team

## Next Steps

1. Review the `.env` file configuration
2. Understand the package models and migrations
3. Check the routes in `routes/web.php` and `routes/api.php`
4. Review existing controllers in `app/Http/Controllers`
5. Start developing!

Happy coding! 🚀
