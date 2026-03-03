<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthSettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleMasterController;
use App\Http\Controllers\UniversityMasterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Standard Auth Routes (includes login, register, password reset, etc.)
// Standard Auth Routes

/* all cache clear route start */

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    return "Cleared!";
});
/* all cache clear route end */

// In web.php
Auth::routes();

// Captcha route
Route::get('/captcha', [LoginController::class, 'generateCaptcha'])->name('captcha');

// Email OTP routes
Route::get('/otp-verify', [LoginController::class, 'showOtpForm'])->name('otp.form');
Route::post('/otp-verify', [LoginController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/otp-resend', [LoginController::class, 'resendOtp'])->name('otp.resend');

// Mobile OTP routes
Route::get('/mobile-otp-verify', [LoginController::class, 'showMobileOtpForm'])
    ->name('mobile.otp.form');
Route::post('/mobile-otp-verify', [LoginController::class, 'verifyMobileOtp'])
    ->name('mobile.otp.verify');

// Two Factor routes
Route::get('/two-factor', [LoginController::class, 'showTwoFactorForm'])->name('twofa.form');
Route::get('/two-factor-verify', [LoginController::class, 'showTwoFactorVerify'])->name('twofa.verify.form');
Route::post('/two-factor', [LoginController::class, 'verifyTwoFactor'])->name('twofa.verify');

// Home route
Route::get('/', [HomeController::class, 'root'])->name('home')->middleware('can:dashboard-view');
// Language Translation
Route::get('index/{locale}', [HomeController::class, 'lang']);

Route::post('/formsubmit', [HomeController::class, 'FormSubmit'])->name('FormSubmit');

// Route::get('/settings', [AuthSettingController::class, 'index'])->name('authsettings');
// Route::put('settings', [AuthSettingController::class, 'update'])->name('settings.update');

Route::resource('roles', RoleMasterController::class);
Route::post('delete-roles', [RoleMasterController::class, 'destroy'])->name('delete-roles');
Route::post('edit-roles', [RoleMasterController::class, 'edit'])->name('edit-roles');

Route::resource('universities', UniversityMasterController::class);

// If you want to keep your additional routes:
Route::post('delete-universities', [UniversityMasterController::class, 'destroy'])
    ->name('delete-universities');
Route::post('edit-universities', [UniversityMasterController::class, 'edit'])
    ->name('edit-universities');

// Role and Permission Routes
Route::middleware('can:role-view')->group(function () {
    Route::resource('roles', RoleController::class)->middleware('can:role-view')
        ->except(['index', 'show'])->middleware('can:role-create')
        ->except(['edit', 'update'])->middleware('can:role-edit')
        ->except(['destroy'])->middleware('can:role-delete');
    Route::get('roles', [RoleController::class, 'index'])->middleware('can:role-view')->name('roles.index');
    Route::get('roles/{id}', [RoleController::class, 'show'])->middleware('can:role-view')->name('roles.show');
});

Route::middleware('can:permission-view')->group(function () {
    Route::resource('permissions', PermissionController::class)->middleware('can:permission-view')
        ->except(['index', 'show'])->middleware('can:permission-create')
        ->except(['edit', 'update'])->middleware('can:permission-edit')
        ->except(['destroy'])->middleware('can:permission-delete');
    Route::get('permissions', [PermissionController::class, 'index'])->middleware('can:permission-view')->name('permissions.index');
    Route::get('permissions/{id}', [PermissionController::class, 'show'])->middleware('can:permission-view')->name('permissions.show');
});

Route::prefix('AuthPasswordSetting')->group(function () {

    // PAGE (Blade view)
    Route::get('/', [AuthSettingController::class, 'index'])
        ->name('auth.settings');

    // AJAX APIs
    Route::get('/GetAuthenticationSettings', [AuthSettingController::class, 'getAuthenticationSettings'])
        ->name('getAuthenticationSettings');

    Route::post('/UpdateAuthenticationSettings', [AuthSettingController::class, 'updateAuthenticationSettings'])
        ->name('updateAuthenticationSettings');

    Route::get('/GetPasswordRules', [AuthSettingController::class, 'getPasswordRules'])
        ->name('getPasswordRules');

    Route::post('/UpdatePasswordRules', [AuthSettingController::class, 'updatePasswordRules'])
        ->name('updatePasswordRules');
});




// Catch-all Route for SPA
Route::get('{any}', [HomeController::class, 'index'])->where('any', '.*');

// Settings routes
