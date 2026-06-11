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
use App\Http\Controllers\RegistrationPathController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\OtpVerificationController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\TesOnlineController;
use App\Http\Controllers\RiwayatPendaftaranController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\TagihanController as AdminTagihanController;
use App\Http\Controllers\Api\RegencyController;

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])
    ->whereIn('locale', ['id', 'en'])
    ->name('locale.switch');

Route::get('/', function () {
    return view('pmb.landing');
})->name('pmb.landing');

Route::get('/jalur-pendaftaran', [RegistrationPathController::class, 'publicIndex'])->name('pmb.registration-paths');
Route::get('/api/registration-paths', [RegistrationPathController::class, 'apiList'])->name('api.registration-paths');

// Menu "Pendaftaran" untuk Super Admin (data pendaftaran yang sudah disubmit)
    Route::middleware(['auth', 'permission'])->group(function () {
        Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
        Route::get('/pendaftaran/{id}', [PendaftaranController::class, 'show'])->name('pendaftaran.show');
        Route::post('/payment/{paymentId}/verify', [PaymentController::class, 'manualVerify'])->name('payment.manual-verify');
    });

// Halaman "Daftar PMB" untuk calon mahasiswa (login required)
    Route::middleware(['auth'])->group(function () {
        Route::get('/daftar-pmb', [RegistrationPathController::class, 'daftarPmb'])->name('daftar-pmb');
        Route::get('/daftar-pmb/registrasi/{pathCode?}', [RegistrationPathController::class, 'registrationSteps'])->name('daftar-pmb.steps');
        Route::get('/daftar-pmb/registrasi/{pathCode?}/form', [RegistrationPathController::class, 'registrationForm'])->name('daftar-pmb.registration.form');
        Route::post('/daftar-pmb/registrasi/{pathCode?}/form', [RegistrationPathController::class, 'registrationStore'])->name('daftar-pmb.registration.store');
        Route::get('/daftar-pmb/registrasi/{pathCode?}/program-studi', [RegistrationPathController::class, 'programStudiForm'])->name('daftar-pmb.program-studi.form');
        Route::post('/daftar-pmb/registrasi/{pathCode?}/program-studi', [RegistrationPathController::class, 'programStudiStore'])->name('daftar-pmb.program-studi.store');
        Route::get('/daftar-pmb/registrasi/{pathCode?}/upload', [RegistrationPathController::class, 'documentUpload'])->name('daftar-pmb.document.upload');
        Route::post('/daftar-pmb/registrasi/{pathCode?}/upload', [RegistrationPathController::class, 'documentStore'])->name('daftar-pmb.document.store');
        Route::get('/daftar-pmb/registrasi/{pathCode?}/review', [RegistrationPathController::class, 'review'])->name('daftar-pmb.review');

        // Riwayat Pendaftaran
        Route::get('/riwayat-pendaftaran', [RiwayatPendaftaranController::class, 'index'])->name('riwayat-pendaftaran.index');
        Route::get('/riwayat-pendaftaran/{id}', [RiwayatPendaftaranController::class, 'show'])->name('riwayat-pendaftaran.show');

        // Tagihan (menu baru)
        Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');

        // Payment / Invoice
        Route::post('/payment/invoice/{registrationId}', [PaymentController::class, 'createInvoice'])->name('payment.invoice');

        // Tes Online (menu baru - ujian dipindahkan kesini)
        Route::get('/tes-online', [TesOnlineController::class, 'index'])->name('tes-online.index');
        Route::get('/tes-online/start/{registrationId?}', [TesOnlineController::class, 'start'])->name('tes-online.start');
        Route::get('/tes-online/question/{registrationId}/{index?}', [TesOnlineController::class, 'question'])->name('tes-online.question');
        Route::post('/tes-online/answer', [TesOnlineController::class, 'answer'])->name('tes-online.answer');
        Route::post('/tes-online/submit', [TesOnlineController::class, 'submit'])->name('tes-online.submit');
    });

// Payment Callback (public - untuk webhook dari aggregator)
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/verify-otp', [OtpVerificationController::class, 'showVerificationForm'])->name('otp.verify.form');
Route::post('/verify-otp', [OtpVerificationController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/verify-otp/resend', [OtpVerificationController::class, 'resendOtp'])->name('otp.resend');

Route::get('/auth/google/login', [GoogleAuthController::class, 'redirectToGoogleLogin'])->name('auth.google.login');
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
        Route::resource('settings/registration-paths', RegistrationPathController::class);
        Route::get('settings/logs', [ActivityLogController::class, 'index'])->name('logs.index');
        Route::get('settings/tagihan', [AdminTagihanController::class, 'index'])->name('settings.tagihan.index');
        Route::post('settings/tagihan/{paymentId}/verify', [AdminTagihanController::class, 'verify'])->name('settings.tagihan.verify');
    });
});