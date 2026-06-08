<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\OtpVerificationController;
use App\Http\Controllers\Api\RegencyController;

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])
    ->whereIn('locale', ['id', 'en'])
    ->name('locale.switch');

Route::get('/', function () {
    return view('pmb.landing');
})->name('pmb.landing');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/verify-otp', [OtpVerificationController::class, 'showVerificationForm'])->name('otp.verify.form');
Route::post('/verify-otp', [OtpVerificationController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/verify-otp/resend', [OtpVerificationController::class, 'resendOtp'])->name('otp.resend');

// LOGIN by Google (khusus user existing)
Route::get('/auth/google/login', [GoogleAuthController::class, 'redirectToGoogleLogin'])->name('auth.google.login');
// DAFTAR by Google (user baru + lengkapi profil)
Route::get('/auth/google/register', [GoogleAuthController::class, 'redirectToGoogleRegister'])->name('auth.google.register');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/auth/google/complete-registration', [GoogleAuthController::class, 'showCompleteRegistrationForm'])->name('google.complete.registration');
Route::post('/auth/google/complete-registration', [GoogleAuthController::class, 'completeRegistration'])->name('google.complete.registration.post');
Route::get('/auth/google/simulation', [GoogleAuthController::class, 'showSimulationForm'])->name('auth.google.simulation');

Route::get('/api/regencies/select2', [RegencyController::class, 'select2'])->name('api.regencies.select2');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/account-settings', [AccountSettingsController::class, 'index'])->name('account.settings');
    Route::put('/account-settings/profile', [AccountSettingsController::class, 'updateProfile'])->name('account.settings.profile');
    Route::put('/account-settings/password', [AccountSettingsController::class, 'updatePassword'])->name('account.settings.password');
    Route::put('/account-settings/avatar', [AccountSettingsController::class, 'updateAvatar'])->name('account.settings.avatar');
    Route::delete('/account-settings/avatar', [AccountSettingsController::class, 'deleteAvatar'])->name('account.settings.avatar.delete');

    Route::middleware(['permission'])->group(function () {
        Route::post('settings/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::resource('settings/users', UserController::class);
        Route::get('settings/roles/matrix', [RoleController::class, 'matrix'])->name('roles.matrix');
        Route::get('settings/roles/{role}/duplicate', [RoleController::class, 'duplicate'])->name('roles.duplicate');
        Route::get('settings/roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
        Route::put('settings/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');
        Route::resource('settings/roles', RoleController::class);
        Route::post('settings/menus/reorder', [MenuController::class, 'reorder'])->name('menus.reorder');
        Route::resource('settings/menus', MenuController::class);
        Route::resource('settings/pages', PageController::class);
        Route::resource('settings/permissions', PermissionController::class);
        Route::get('settings/logs', [ActivityLogController::class, 'index'])->name('logs.index');
    });
});