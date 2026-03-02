<?php

use Illuminate\Support\Facades\Route;
use Param\RBAC\Http\Controllers\HomeController;
use Param\RBAC\Http\Controllers\AuthSettingController;

/*
|--------------------------------------------------------------------------
| RBAC Package Routes
|--------------------------------------------------------------------------
|
| These routes are essential for the RBAC package functionality
|
*/

Route::prefix('rbac')->group(function () {

    // Home/Dashboard Route
    Route::get('/dashboard', [HomeController::class, 'index'])->name('rbac.dashboard');

    // Authentication Settings Routes
    Route::prefix('auth-settings')->group(function () {
        Route::get('/', [AuthSettingController::class, 'index'])->name('rbac.auth-settings.index');
        Route::get('/{id}/edit', [AuthSettingController::class, 'edit'])->name('rbac.auth-settings.edit');
        Route::put('/{id}', [AuthSettingController::class, 'update'])->name('rbac.auth-settings.update');
        Route::post('/', [AuthSettingController::class, 'store'])->name('rbac.auth-settings.store');
        Route::delete('/{id}', [AuthSettingController::class, 'destroy'])->name('rbac.auth-settings.destroy');
    });

    // User Routes (CRUD)
    Route::prefix('users')->group(function () {
        Route::get('/', [AuthSettingController::class, 'listUsers'])->name('rbac.users.index');
        Route::get('/create', [AuthSettingController::class, 'createUser'])->name('rbac.users.create');
        Route::post('/', [AuthSettingController::class, 'storeUser'])->name('rbac.users.store');
        Route::get('/{id}/edit', [AuthSettingController::class, 'editUser'])->name('rbac.users.edit');
        Route::put('/{id}', [AuthSettingController::class, 'updateUser'])->name('rbac.users.update');
        Route::delete('/{id}', [AuthSettingController::class, 'destroyUser'])->name('rbac.users.destroy');
    });

    // Role Routes
    Route::prefix('roles')->group(function () {
        Route::get('/', [AuthSettingController::class, 'listRoles'])->name('rbac.roles.index');
        Route::get('/create', [AuthSettingController::class, 'createRole'])->name('rbac.roles.create');
        Route::post('/', [AuthSettingController::class, 'storeRole'])->name('rbac.roles.store');
        Route::get('/{id}/edit', [AuthSettingController::class, 'editRole'])->name('rbac.roles.edit');
        Route::put('/{id}', [AuthSettingController::class, 'updateRole'])->name('rbac.roles.update');
        Route::delete('/{id}', [AuthSettingController::class, 'destroyRole'])->name('rbac.roles.destroy');
    });

    // Permission Routes
    Route::prefix('permissions')->group(function () {
        Route::get('/', [AuthSettingController::class, 'listPermissions'])->name('rbac.permissions.index');
        Route::get('/create', [AuthSettingController::class, 'createPermission'])->name('rbac.permissions.create');
        Route::post('/', [AuthSettingController::class, 'storePermission'])->name('rbac.permissions.store');
        Route::get('/{id}/edit', [AuthSettingController::class, 'editPermission'])->name('rbac.permissions.edit');
        Route::put('/{id}', [AuthSettingController::class, 'updatePermission'])->name('rbac.permissions.update');
        Route::delete('/{id}', [AuthSettingController::class, 'destroyPermission'])->name('rbac.permissions.destroy');
    });

    // Two-Factor Authentication Routes
    Route::prefix('2fa')->group(function () {
        Route::get('/', [AuthSettingController::class, 'list2FA'])->name('rbac.2fa.index');
        Route::post('/enable/{userId}', [AuthSettingController::class, 'enable2FA'])->name('rbac.2fa.enable');
        Route::post('/disable/{userId}', [AuthSettingController::class, 'disable2FA'])->name('rbac.2fa.disable');
        Route::post('/verify', [AuthSettingController::class, 'verify2FA'])->name('rbac.2fa.verify');
    });

    // Password Rules Routes
    Route::prefix('password-rules')->group(function () {
        Route::get('/', [AuthSettingController::class, 'listPasswordRules'])->name('rbac.password-rules.index');
        Route::post('/', [AuthSettingController::class, 'storePasswordRule'])->name('rbac.password-rules.store');
        Route::put('/{id}', [AuthSettingController::class, 'updatePasswordRule'])->name('rbac.password-rules.update');
    });

});

// Public Routes (No Authentication Required)
Route::prefix('rbac')->group(function () {
    Route::get('/', [HomeController::class, 'welcome'])->name('rbac.welcome');
});
