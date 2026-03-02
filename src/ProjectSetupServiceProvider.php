<?php

namespace Param\RBAC;

use Illuminate\Support\ServiceProvider;

class ProjectSetupServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Publish migrations
        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ]);

        // Publishing assets to app Models directory
        $this->publishes([
            __DIR__.'/Models' => app_path('Models'),
        ], 'project-setup-models');

        // Publishing controllers
        $this->publishes([
            __DIR__.'/Http/Controllers' => app_path('Http/Controllers'),
        ], 'project-setup-controllers');

        // Publishing public assets
        $this->publishes([
            __DIR__.'/../public' => public_path(),
        ], 'project-setup-assets');

        // Publishing resources (SCSS, fonts, images, JS)
        $this->publishes([
            __DIR__.'/../resources' => resource_path(),
        ], 'project-setup-resources');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
