# Developer Setup Checklist

Use this checklist to verify your development environment is properly set up.

## Pre-Installation

### Computer Setup
- [ ] PHP 8.2+ installed (`php -v`)
- [ ] MySQL running (`mysql -u root -p`)
- [ ] Composer installed (`composer --version`)
- [ ] Node.js installed (`node -v`)
- [ ] npm installed (`npm -v`)
- [ ] Git installed (`git --version`)
- [ ] Code editor ready (VS Code, PhpStorm, etc.)

### Repository Access
- [ ] Access to Git repository
- [ ] SSH keys configured (if using SSH)
- [ ] Can clone the repository

## Code Checkout

- [ ] Repository cloned to local machine
- [ ] Correct branch checked out
- [ ] All project files present
- [ ] `.git` folder exists
- [ ] No uncommitted changes in branch

## PHP Dependencies

- [ ] `composer install` completed without errors
- [ ] `vendor/` directory created
- [ ] `vendor/autoload.php` exists
- [ ] Package `param/rbac` installed
- [ ] Check with: `composer show param/rbac`

## JavaScript Dependencies

- [ ] `npm install` completed without errors
- [ ] `node_modules/` directory created
- [ ] `package-lock.json` exists
- [ ] Build system working: `npm run dev`
- [ ] Watch mode working: `npm run hot`

## Environment Configuration

### .env File
- [ ] `.env` file created (from `.env.example`)
- [ ] `APP_NAME` set
- [ ] `APP_URL` set to `http://localhost:8000`
- [ ] `APP_KEY` generated (`php artisan key:generate`)
- [ ] `APP_DEBUG` set to `true` for development

### Database Configuration
- [ ] `DB_CONNECTION=mysql`
- [ ] `DB_HOST=127.0.0.1`
- [ ] `DB_PORT=3306`
- [ ] `DB_DATABASE` set to project database name
- [ ] `DB_USERNAME=root` (or your username)
- [ ] `DB_PASSWORD` set correctly
- [ ] MySQL database created
- [ ] Test connection works

### Authentication & Session
- [ ] `SESSION_DRIVER=cookie`
- [ ] `CACHE_DRIVER=file`
- [ ] `QUEUE_CONNECTION=sync`
- [ ] `MAIL_MAILER` configured (optional for dev)

## Database Setup

### Migration Execution
- [ ] Database connection verified
- [ ] `php artisan migrate` executed successfully
- [ ] All tables created in database
- [ ] Check tables: `php artisan tinker` → `DB::table('users')->limit(1)->get()`

### Tables Verification
- [ ] `users` table exists
- [ ] `authentication_settings` table exists
- [ ] `password_rules` table exists
- [ ] `user_two_factor` table exists
- [ ] `email_login_otp` table exists
- [ ] Spatie tables created (roles, permissions, etc.)

### Sample Data (Optional)
- [ ] Seeder created (optional)
- [ ] `php artisan db:seed` ran (if seeders exist)
- [ ] Sample data in database

## Application Testing

### Artisan Commands
- [ ] `php artisan tinker` starts interactive shell
- [ ] `php artisan route:list` shows all routes
- [ ] `php artisan cache:clear` works
- [ ] `php artisan migrate --refresh` works

### Server Start
- [ ] `php artisan serve` starts successfully
- [ ] Server runs on `http://localhost:8000`
- [ ] No address already in use errors
- [ ] Server responds to requests

### Frontend Build
- [ ] `npm run dev` compiles assets
- [ ] `npm run hot` starts watch mode
- [ ] CSS files in `public/css/`
- [ ] JS files in `public/js/`
- [ ] No TypeScript/compilation errors

## Application Access

### Browser Testing
- [ ] Application loads at `http://localhost:8000`
- [ ] Homepage displays correctly
- [ ] CSS/styling applied
- [ ] JavaScript components working
- [ ] No 404 errors for assets

### Routes Testing
- [ ] `/` route works
- [ ] `/login` route accessible
- [ ] `/clear` route shows "Cleared!"
- [ ] Unknown routes return 404 (not errors)

## Package Integration

### Models Available
- [ ] Can import User model: `use Param\RBAC\Models\User;`
- [ ] Can import AuthSetting model
- [ ] Can import PasswordRule model
- [ ] Can import UserTwoFactor model
- [ ] Can import RoleMaster model

### Service Provider
- [ ] Check registered providers: `php artisan tinker` → `app()->getProvider(Param\RBAC\ProjectSetupServiceProvider::class)`
- [ ] Package auto-discovered

### Migrations
- [ ] Package migrations loaded
- [ ] Check with: `php artisan migrate:status`
- [ ] All package migrations show as completed

## IDE & Editor Setup

### Code Completion
- [ ] PHP intellisense working
- [ ] Laravel helpers recognized
- [ ] Model autocomplete working
- [ ] No red squiggly lines for valid code

### Formatting
- [ ] PHP formatter configured
- [ ] Laravel Pint ready: `./vendor/bin/pint`
- [ ] ESLint/Prettier configured for JS

### Version Control
- [ ] Git initialized
- [ ] `.gitignore` configured properly
- [ ] Can commit changes
- [ ] Can pull/push changes

## Testing Setup (Optional)

### PHPUnit
- [ ] PHPUnit installed
- [ ] `phpunit.xml` exists
- [ ] Can run tests: `php artisan test`
- [ ] Tests directory exists and accessible

## Development Tools

### Available Commands
- [ ] `php artisan make:model` works
- [ ] `php artisan make:migration` works
- [ ] `php artisan make:controller` works
- [ ] `php artisan make:request` works
- [ ] `php artisan make:middleware` works

### Helpful Tools
- [ ] Postman/Insomnia installed (for API testing)
- [ ] MySQL GUI tool installed (DBeaver, Sequel Pro, etc.)
- [ ] Git GUI tool available (optional)

## Documentation Review

- [ ] Read [QUICKSTART.md](QUICKSTART.md) - 5min
- [ ] Read [README.md](README.md) - 10min
- [ ] Read [INSTALLATION.md](INSTALLATION.md) - 15min
- [ ] Skim [CONFIGURATION.md](CONFIGURATION.md)
- [ ] Understand package structure

## Final Verification

### Complete Workflow Test
- [ ] Can start PHP server
- [ ] Can start frontend watch
- [ ] Can access application in browser
- [ ] Can connect to database
- [ ] Can access database in browser (optional)
- [ ] Can make a simple code change and see it reflected

### Troubleshooting Documentation
- [ ] Familiarize with common errors
- [ ] Know where to find logs: `storage/logs/laravel.log`
- [ ] Know how to check database connection
- [ ] Know how to clear caches

## Sign-Off

When all items are checked, you're ready to start development!

### Ready to Code?
- [ ] All checklist items completed
- [ ] No outstanding issues
- [ ] Ready to start coding
- [ ] Know where to ask for help

---

## Quick Reference

### Start Development
```bash
# Terminal 1 - PHP Server
php artisan serve

# Terminal 2 - Frontend Build
npm run hot

# Access at http://localhost:8000
```

### Common Issues

| Problem | Solution |
|---------|----------|
| "Connection refused" | Check MySQL service is running |
| "Table doesn't exist" | Run `php artisan migrate` |
| "npm ERR!" | Delete `node_modules/` and run `npm install` again |
| "Port 8000 in use" | Change port: `php artisan serve --port=8001` |

### Get Help

1. Check [INSTALLATION.md](INSTALLATION.md) troubleshooting
2. Check [CONFIGURATION.md](CONFIGURATION.md) for config issues
3. Check Laravel logs: `tail -f storage/logs/laravel.log`
4. Use `php artisan tinker` for quick debugging
5. Ask development team

---

**You're all set! Begin development 🚀**

Keep this checklist handy for reference and to help other developers get set up quickly.
