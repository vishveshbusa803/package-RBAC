# Package Setup Instructions for Main Project

This file explains how to configure your main Laravel project to use the `param/rbac` package.

## Local Development (Using Path Repository)

### Method 1: Path Repository (Recommended for Development)

Edit your main project's `composer.json` and add the repository configuration:

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

Then require the package:

```bash
composer require param/rbac
```

### Method 2: Direct Installation from Packages Directory

If the package directory is already in your project:

```bash
composer require --dev ./packages/param/rbac
```

## Production Deployment (Using Private Repository)

### Option A: Using Composer Repository (Packagist-style)

1. **Create a Private Git Repository**
   - Host the package on a private Git server (GitHub, GitLab, Bitbucket, etc.)
   - Repository URL: `https://your-server/param/rbac.git`

2. **Update Main Project's composer.json**

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://your-server/param/rbac.git"
        }
    ],
    "require": {
        "param/rbac": "^1.0"
    }
}
```

3. **Authentication (if needed)**

Create or update `auth.json` in your project root:

```json
{
    "github-oauth": {
        "github.com": "your-github-token"
    },
    "gitlab-oauth": {
        "gitlab.com": "your-gitlab-token"
    }
}
```

Then require the package:

```bash
composer require param/rbac
```

### Option B: Using Artifactory/Private Packagist

1. Upload the package to your private Composer repository
2. Update `composer.json`:

```json
{
    "repositories": [
        {
            "type": "composer",
            "url": "https://your-private-repo/composer"
        }
    ],
    "require": {
        "param/rbac": "^1.0"
    }
}
```

## Package Versioning

The package uses semantic versioning. When updating `composer.json`:

```json
{
    "require": {
        "param/rbac": "^1.0"     // ^1.0 < 2.0
    }
}
```

## Complete composer.json Example

Here's a complete example of how your main project's `composer.json` should look:

```json
{
    "name": "param/laravel-project",
    "type": "project",
    "description": "Main Laravel Application",
    "repositories": [
        {
            "type": "path",
            "url": "./packages/param/rbac"
        }
    ],
    "require": {
        "php": "^8.2",
        "laravel/framework": "^12.0",
        "laravel/sanctum": "^4.0",
        "laravel/tinker": "^2.9",
        "param/rbac": "*"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pint": "^1.13",
        "phpunit/phpunit": "^11.0"
    },
    "config": {
        "optimize-autoloader": true,
        "preferred-install": "dist"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        }
    }
}
```

## Post-Installation Setup

After installing the package via Composer, follow these steps:

### 1. Verify Installation

```bash
# Check if package is installed
composer show param/rbac

# Or run artisan to verify service provider is registered
php artisan tinker
>>> app(Param\RBAC\ProjectSetupServiceProvider::class)
```

### 2. Publish Migrations

Migrations are automatically loaded, but you can also publish them:

```bash
php artisan vendor:publish --provider="Param\\RBAC\\ProjectSetupServiceProvider"
```

### 3. Run Migrations

```bash
php artisan migrate
```

### 4. Publish Assets (Optional)

If you need to customize models or controllers:

```bash
# Publish models to app/Models
php artisan vendor:publish --provider="Param\\RBAC\\ProjectSetupServiceProvider" --tag="project-setup-models"

# Publish controllers to app/Http/Controllers
php artisan vendor:publish --provider="Param\\RBAC\\ProjectSetupServiceProvider" --tag="project-setup-controllers"
```

## Updating the Package

To update the package to the latest version:

```bash
composer update param/rbac
```

To update to a specific version:

```bash
composer require param/rbac:^2.0
```

## Troubleshooting

### Package Not Found

**Error**: "composer/Repositories\RepositoryFactory.php:93) The package..."

**Solution**:
- Check the repository URL is correct
- Verify the path exists (for path repositories)
- Clear composer cache: `composer clear-cache`
- Try again: `composer update`

### Authentication Issues

**Error**: "Supplier authentication required"

**Solution**:
- Create/update `auth.json` with valid credentials
- Add token to `.env` or `auth.json`
- Never commit `auth.json` to version control

### Version Conflicts

**Error**: "Your requirements could not be resolved..."

**Solution**:
- Check version constraints in `composer.json`
- Ensure required packages don't have conflicting versions
- Run: `composer require param/rbac --update-with-dependencies`

## Development Workflow

When developing the package itself:

1. Make changes in `packages/param/rbac/`
2. Run `composer install` in the main project to reload package code
3. Test changes in the application
4. Commit and push changes to package repository
5. Update package version in composer.json if needed

## Project Structure Reminder

```
project-root/
├── app/
├── config/
├── database/
├── packages/
│   └── param/
│       └── rbac/
│           ├── src/
│           ├── database/
│           ├── composer.json
│           ├── README.md
│           └── LICENSE
├── resources/
├── routes/
├── storage/
├── tests/
├── composer.json
├── .env.example
└── package.json
```

## References

- [Composer Path Repositories](https://getcomposer.org/doc/05-repositories.md#path)
- [Composer VCS Repositories](https://getcomposer.org/doc/05-repositories.md#vcs)
- [Laravel Package Development](https://laravel.com/docs/packages)
- [Package Auto-Discovery](https://laravel.com/docs/packages#package-discovery)

## Next Steps

1. Follow the [INSTALLATION.md](INSTALLATION.md) guide for new developers
2. Review the [README.md](README.md) for package features
3. Check the package documentation for usage examples
4. Configure your deployment pipeline to handle the package
