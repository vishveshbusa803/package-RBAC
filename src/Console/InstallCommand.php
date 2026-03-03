<?php

namespace ParamRbac\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'param-rbac:install {--force : Force installation by removing existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Param RBAC package with all required scaffolding';

    /**
     * The Filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * The base path of the stub files.
     *
     * @var string
     */
    protected $stubPath;

    /**
     * Create a new command instance.
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
        // Path to stubs relative to package
        $this->stubPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'stubs';
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Installing Param RBAC Package...\n');

        $force = $this->option('force');

        if (!$force && !$this->confirmDestructiveOperation()) {
            $this->warn('Installation cancelled by user.');
            return 1;
        }

        try {
            // Step 1: Remove default Laravel scaffolding
            $this->info('📦 Step 1: Removing default Laravel scaffolding...');
            $this->removeDefaultLaravelFiles();
            $this->newLine();

            // Step 2: Copy package stubs
            $this->info('📂 Step 2: Publishing package files...');
            $this->publishPackageFiles();
            $this->newLine();

            // Step 3: Install required dependencies
            $this->info('📥 Step 3: Installing required dependencies...');
            $this->installDependencies();
            $this->newLine();

            // Step 4: Publish Spatie permissions
            $this->info('🔐 Step 4: Publishing Spatie permission tables...');
            $this->publishSpatiePermissions();
            $this->newLine();

            // Step 5: Success message
            $this->info('✅ Param RBAC installation completed successfully!');
            $this->newLine();
            $this->info('📝 Next steps:');
            $this->line('   1. Configure your .env file with database credentials');
            $this->line('   2. Run: php artisan migrate');
            $this->line('   3. Run: php artisan db:seed (optional - creates default admin user)');
            $this->line('   4. Install npm packages: npm install');
            $this->line('   5. Build assets: npm run dev');
            $this->line('   6. Start server: php artisan serve');
            $this->newLine();
            $this->info('🎉 Visit http://localhost:8000 and login with admin@example.com / password');
            $this->newLine();

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Installation failed: ' . $e->getMessage());
            if ($this->option('verbose')) {
                $this->error('Stack trace: ' . $e->getTraceAsString());
            }
            return 1;
        }
    }

    /**
     * Confirm destructive operation.
     */
    protected function confirmDestructiveOperation(): bool
    {
        $this->warn('⚠️  This operation will:');
        $this->line('   • Remove default Laravel controllers, models, views');
        $this->line('   • Remove default auth scaffolding');
        $this->line('   • Replace with Param RBAC implementation');
        $this->line('   • Update route files');
        $this->newLine();

        return $this->confirm('Do you want to continue?', false);
    }

    /**
     * Remove default Laravel files/folders.
     */
    protected function removeDefaultLaravelFiles(): void
    {
        $pathsToRemove = [
            'controllers' => app_path('Http/Controllers'),
            'models' => app_path('Models'),
            'views' => resource_path('views'),
            'public_assets' => public_path('assets'),
            'public_css' => public_path('css'),
            'public_js' => public_path('js'),
        ];

        foreach ($pathsToRemove as $name => $path) {
            try {
                if ($this->files->exists($path)) {
                    if ($this->files->isDirectory($path)) {
                        // Get file count for logging
                        $allFiles = $this->files->allFiles($path);
                        $this->files->deleteDirectory($path);
                        $this->line("  ✓ Removed: {$name} ({$path})");
                    } else {
                        $this->files->delete($path);
                        $this->line("  ✓ Removed file: {$path}");
                    }
                }
            } catch (\Exception $e) {
                $this->warn("  ⚠ Could not remove {$name}: " . $e->getMessage());
            }
        }

        // Clear specific route files
        $this->clearRouteFiles();
    }

    /**
     * Clear default route files.
     */
    protected function clearRouteFiles(): void
    {
        $routeFiles = [
            base_path('routes/web.php'),
            base_path('routes/api.php'),
        ];

        foreach ($routeFiles as $file) {
            if ($this->files->exists($file)) {
                $this->files->put($file, "<?php\n\nuse Illuminate\Support\Facades\Route;\n\n// Routes will be defined after package installation\n");
            }
        }
    }

    /**
     * Publish package files to the application.
     */
    protected function publishPackageFiles(): void
    {
        // Define source to destination mappings
        $mappings = [
            'app/Http/Controllers' => app_path('Http/Controllers'),
            'app/Http/Middleware' => app_path('Http/Middleware'),
            'app/Models' => app_path('Models'),
            'app/Providers' => app_path('Providers'),
            'database/migrations' => database_path('migrations'),
            'database/seeders' => database_path('seeders'),
            'resources/views' => resource_path('views'),
            'resources/js' => resource_path('js'),
            'resources/scss' => resource_path('scss'),
            'resources/fonts' => resource_path('fonts'),
            'resources/images' => resource_path('images'),
            'public/assets' => public_path('assets'),
            'routes' => base_path('routes'),
            'config' => config_path(),
        ];

        $publishedCount = 0;
        $skippedCount = 0;

        foreach ($mappings as $source => $destination) {
            $sourcePath = $this->stubPath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $source);

            if ($this->files->exists($sourcePath)) {
                try {
                    // For files, copy directly; for directories, recursively copy
                    if ($this->files->isDirectory($sourcePath)) {
                        $this->copyDirectory($sourcePath, $destination);
                    } else {
                        $this->files->ensureDirectoryExists(dirname($destination));
                        $this->files->copy($sourcePath, $destination);
                    }
                    $this->line("  ✓ Published: {$source}");
                    $publishedCount++;
                } catch (\Exception $e) {
                    $this->warn("  ⚠ Could not publish {$source}: " . $e->getMessage());
                    $skippedCount++;
                }
            } else {
                $this->line("  ⊘ Skipped (not found): {$source}");
                $skippedCount++;
            }
        }

        $this->line("\n  Published: {$publishedCount}, Skipped: {$skippedCount}");
    }

    /**
     * Copy a directory recursively.
     *
     * @param string $source
     * @param string $destination
     */
    protected function copyDirectory(string $source, string $destination): void
    {
        // Ensure destination directory exists
        $this->files->ensureDirectoryExists($destination);

        // Get iterator for all files
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $relativePath = substr($item->getPathname(), strlen($source) + 1);
            $destinationPath = $destination . DIRECTORY_SEPARATOR . $relativePath;

            if ($item->isDir()) {
                $this->files->ensureDirectoryExists($destinationPath);
            } else {
                $this->files->ensureDirectoryExists(dirname($destinationPath));
                $this->files->copy($item->getPathname(), $destinationPath);
            }
        }
    }

    /**
     * Install required composer dependencies.
     */
    protected function installDependencies(): void
    {
        $dependencies = [
            'spatie/laravel-permission:^6.0',
            'pragmarx/google2fa-laravel:^2.3',
            'yajra/laravel-datatables:^12.0',
            'laravel/ui:^4.2',
        ];

        $this->line('  Installing package dependencies...');

        foreach ($dependencies as $package) {
            try {
                $this->callQuietly('composer', ['require', $package]);
                $this->line("  ✓ Installed: {$package}");
            } catch (\Exception $e) {
                $this->warn("  ⚠ Could not install {$package}: " . $e->getMessage());
            }
        }

        $this->line('  ✓ Dependencies installation completed');
    }

    /**
     * Publish Spatie permission migrations.
     */
    protected function publishSpatiePermissions(): void
    {
        try {
            $this->line('  Publishing Spatie permission migrations...');

            $this->callQuietly('vendor:publish', [
                '--provider' => 'Spatie\\Permission\\PermissionServiceProvider',
                '--force' => true,
            ]);

            $this->line('  ✓ Spatie permission tables published');
        } catch (\Exception $e) {
            $this->warn('  ⚠ Could not publish Spatie permission tables: ' . $e->getMessage());
        }
    }
}
