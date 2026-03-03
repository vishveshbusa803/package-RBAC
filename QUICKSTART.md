# ⚡ Param RBAC - Quick Start (5 Minutes)

## What You Have
A complete Laravel package that installs RBAC in one command.

## Where It Is
```
c:\vishvesh\param-rbac\
```

## What's Inside
- ✅ Service Provider (auto-discovery)
- ✅ Install Command (php artisan param-rbac:install)
- ✅ 5 documentation files
- ✅ Stubs directory (needs your files)

## 🚀 Next 5 Steps

### Step 1: Copy Your App to Stubs (2 min)
Run in PowerShell:
```powershell
$source = "c:\vishvesh\ACPC"
$target = "c:\vishvesh\param-rbac\stubs"

Copy-Item -Path "$source\app" -Destination "$target\" -Recurse -Force
Copy-Item -Path "$source\database" -Destination "$target\" -Recurse -Force
Copy-Item -Path "$source\resources" -Destination "$target\" -Recurse -Force
Copy-Item -Path "$source\public\assets" -Destination "$target\public\" -Recurse -Force
Copy-Item -Path "$source\routes" -Destination "$target\" -Recurse -Force
Copy-Item -Path "$source\config" -Destination "$target\" -Recurse -Force

Write-Host "✓ Complete!" -ForegroundColor Green
```

### Step 2: Test Installation (2 min)
```bash
# Create test project
laravel new test-rbac
cd test-rbac

# Add package from local
composer config repositories.local path "c:\vishvesh\param-rbac"
composer require param/rbac:dev-main

# Run install
php artisan param-rbac:install --force

# Configure database
cp .env.example .env
# Edit .env with database credentials

# Run migrations
php artisan migrate
php artisan db:seed

# Build frontend
npm install
npm run dev

# Start
php artisan serve
```

Visit: http://localhost:8000
Login: admin@example.com / password

### Step 3: Verify It Works (1 min)
- [ ] Can login with admin@example.com
- [ ] Can see admin dashboard
- [ ] CSS/JS loads correctly
- [ ] Can view roles
- [ ] Can view permissions

### Step 4: Read Documentation (Optional)
Priority order:
1. README.md - Overview
2. GETTING_STARTED.md - Full setup
3. DEVELOPMENT_GUIDE.md - Developer info

### Step 5: Publish to GitHub
```bash
cd c:\vishvesh\param-rbac
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/username/param-rbac.git
git branch -M main
git push -u origin main

# Create tag
git tag -a v1.0.0 -m "Version 1.0.0"
git push origin v1.0.0
```

## 📦 Then Publish to Packagist

1. Go to https://packagist.org
2. Click "Submit Package"
3. Enter: https://github.com/username/param-rbac.git
4. Done!

Users can now run:
```bash
composer require username/param-rbac
php artisan param-rbac:install
```

## 📚 Documentation Files

| File | Purpose | Read Time |
|------|---------|-----------|
| README.md | Complete guide | 15 min |
| GETTING_STARTED.md | Step-by-step | 20 min |
| DEVELOPMENT_GUIDE.md | Developer setup | 25 min |
| STUBS_SETUP.md | Technical details | 20 min |
| SUMMARY.md | Overview | 10 min |

## ✅ Verification Commands

```bash
# Check package loads
php artisan list | grep param-rbac

# Check service provider
php artisan provider:list | grep ParamRbac

# Check installed correctly
php artisan param-rbac:install --help
```

## 🎯 Success Criteria

You're done when:
1. ✓ Stubs populated from ACPC
2. ✓ Fresh Laravel project installs package
3. ✓ Installation command runs successfully
4. ✓ Database tables created
5. ✓ Can login with admin credentials
6. ✓ Admin panel accessible
7. ✓ Published to Packagist

## 💡 Pro Tips

- Use `--force` to skip confirmations
- Test in fresh Laravel projects
- Keep vendor/ out of git
- Update version in composer.json for releases
- Create GitHub tags for versions

## ❓ Common Questions

**Q: Do I need to copy stubs manually?**
A: Yes, for now. They are your application files.

**Q: Will this overwrite my ACPC project?**
A: No, we're only copying TO the package.

**Q: Can I test without Packagist?**
A: Yes, use local path repository (shown in Step 2).

**Q: What if installation fails?**
A: Check storage/logs/laravel.log for errors.

**Q: Can I customize the package?**
A: Yes, edit files before publishing.

## 🆘 Quick Troubleshooting

```bash
# Package command not found?
composer clear-cache
composer dump-autoload

# Database errors?
php artisan migrate:fresh --seed

# Asset issues?
npm install
npm run dev

# Need to reset?
composer remove param/rbac
composer require param/rbac:dev-main
```

---

**That's it! You're ready to distribute param-rbac to the world! 🎉**

For deeper details, see the full documentation files.

Next: Copy ACPC files to stubs → Test → Publish!
