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
use App\Http\Controllers\ProgramStudiController;
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
use App\Http\Controllers\Admin\FormPendaftaranController;
use App\Http\Controllers\Admin\LandingPageController;
use App\Http\Controllers\Api\RegencyController;
use App\Http\Controllers\SoalUjianController;
use App\Http\Controllers\PaketSoalController;
use App\Http\Controllers\SyaratBerkasController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\WawancaraController;
use App\Http\Controllers\CrmLeadController;

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])
    ->whereIn('locale', ['id', 'en'])
    ->name('locale.switch');

Route::get('/', function () {
    $activePaths = \App\Models\RegistrationPath::with('kategori')
        ->byActivePeriode()
        ->where('is_active', true)
        ->orderBy('code')
        ->get();

    $activePaths->loadCount(['registrations as terdaftar' => function ($q) {
        $q->whereIn('status', ['submitted', 'documents_uploaded', 'payment_pending', 'payment_verified', 'exam_completed', 'reviewed', 'accepted']);
    }]);

    // Dynamic landing data
    $features = \App\Models\LandingFeature::active()->get();
    $programStudis = \App\Models\LandingProgramStudi::with('programStudi')->published()->get();
    $settings = \App\Models\LandingSetting::all()->keyBy('key');
    $facilities = \App\Models\LandingFacility::active()->get();

    return view('pmb.landing', compact('activePaths', 'features', 'programStudis', 'settings', 'facilities'));
})->name('pmb.landing');

Route::get('/jalur-pendaftaran', [RegistrationPathController::class, 'publicIndex'])->name('pmb.registration-paths');
Route::get('/api/registration-paths', [RegistrationPathController::class, 'apiList'])->name('api.registration-paths');

// Menu "Pendaftaran" untuk Super Admin (data pendaftaran yang sudah disubmit)
    Route::middleware(['auth', 'permission'])->group(function () {
        Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
        Route::get('/pendaftaran/{id}', [PendaftaranController::class, 'show'])->name('pendaftaran.show');
        Route::post('/payment/{paymentId}/verify', [PaymentController::class, 'manualVerify'])->name('payment.manual-verify');
        Route::post('/pendaftaran/{id}/verify-re-registration', [PendaftaranController::class, 'verifyReRegistration'])->name('pendaftaran.verify-re-registration');
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
        Route::post('/daftar-pmb/registrasi/{pathCode?}/re-registration', [RegistrationPathController::class, 'reRegistrationStore'])->name('daftar-pmb.re-registration.store');

        // Dynamic re-registration route
        Route::get('/mahasiswa/registrasi-ulang', function() {
            $reg = \App\Models\Registration::where('user_id', auth()->id())
                ->latest()
                ->first();
            $pathCode = $reg && $reg->registrationPath ? $reg->registrationPath->code : null;
            return redirect()->route('daftar-pmb.steps', ['pathCode' => $pathCode, 're_registration' => 1]);
        })->name('mahasiswa.registrasi-ulang');

        // Riwayat Pendaftaran
        Route::get('/riwayat-pendaftaran', [RiwayatPendaftaranController::class, 'index'])->name('riwayat-pendaftaran.index');

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

// FLOW B: Path-specific dynamic registration (landing page path cards)
Route::get('/register/{jalur_id?}', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// FLOW A: Standalone account sign-up (navbar "Daftar" button)
Route::get('/register-account', [RegisterController::class, 'showAccountForm'])->name('register-account');
Route::post('/register-account', [RegisterController::class, 'storeAccount'])->name('register-account.post');

Route::get('/verify-otp', [OtpVerificationController::class, 'showVerificationForm'])->name('otp.verify.form');
Route::post('/verify-otp', [OtpVerificationController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/verify-otp/resend', [OtpVerificationController::class, 'resendOtp'])->name('otp.resend');

Route::get('/auth/google/login', [GoogleAuthController::class, 'redirectToGoogleLogin'])->name('auth.google.login');
Route::get('/auth/google/register', [GoogleAuthController::class, 'redirectToGoogleRegister'])->name('auth.google.register');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/auth/google/complete-registration', [GoogleAuthController::class, 'showCompleteRegistrationForm'])->name('google.complete.registration');
Route::post('/auth/google/complete-registration', [GoogleAuthController::class, 'completeRegistration'])->name('google.complete.registration.post');
Route::get('/auth/google/simulation', [GoogleAuthController::class, 'showSimulationForm'])->name('auth.google.simulation');

Route::post('/crm-leads/store', [CrmLeadController::class, 'storePublic'])->name('crm-leads.store-public');

Route::get('/api/regencies/select2', [RegencyController::class, 'select2'])->name('api.regencies.select2');
Route::get('/api/wilayah/kabupaten', [\App\Http\Controllers\Api\WilayahController::class, 'getKabupatens'])->name('api.wilayah.kabupaten');
Route::get('/api/wilayah/kecamatan/{kabupaten_id}', [\App\Http\Controllers\Api\WilayahController::class, 'getKecamatans'])->name('api.wilayah.kecamatan');
Route::get('/api/wilayah/kelurahan/{kecamatan_id}', [\App\Http\Controllers\Api\WilayahController::class, 'getKelurahans'])->name('api.wilayah.kelurahan');

Route::get('/api/local/kecamatan/{kabupaten_id}', function($kabupaten_id) {
    if (!is_numeric($kabupaten_id)) {
        $kabupaten = \DB::table('kabupatens')
            ->where('nama_kabupaten', 'like', '%' . $kabupaten_id . '%')
            ->first();
        $kabupaten_id = $kabupaten ? $kabupaten->id : 0;
    }
    
    $data = \DB::table('kecamatans')
                ->where('kabupaten_id', $kabupaten_id)
                ->select('id', 'nama_kecamatan as text') // Explicitly map column names to 'text'
                ->get();
    return response()->json($data);
});

Route::get('/api/local/kelurahan/{kecamatan_id}', function($kecamatan_id) {
    $data = \DB::table('kelurahans')
                ->where('kecamatan_id', $kecamatan_id)
                ->select('id', 'nama_kelurahan as text') // Explicitly map column names to 'text'
                ->get();
    return response()->json($data);
});


Route::middleware(['auth'])->group(function () {
    // Onboarding routes
    Route::get('/onboarding/progress', [App\Http\Controllers\OnboardingController::class, 'progress'])->name('onboarding.progress');
    Route::post('/onboarding/complete-welcome', [App\Http\Controllers\OnboardingController::class, 'completeWelcome'])->name('onboarding.complete-welcome');
    Route::post('/onboarding/complete-step', [App\Http\Controllers\OnboardingController::class, 'completeStep'])->name('onboarding.complete-step');
    Route::post('/onboarding/dismiss', [App\Http\Controllers\OnboardingController::class, 'dismiss'])->name('onboarding.dismiss');
    Route::post('/onboarding/reset', [App\Http\Controllers\OnboardingController::class, 'reset'])->name('onboarding.reset');

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
        Route::resource('settings/program-studi', ProgramStudiController::class);
        // Paket Soal - exam package management
        Route::get('settings/paket-soal', [PaketSoalController::class, 'index'])->name('paket-soal.index');
        Route::post('settings/paket-soal', [PaketSoalController::class, 'store'])->name('paket-soal.store');
        Route::put('settings/paket-soal/{paketSoal}', [PaketSoalController::class, 'update'])->name('paket-soal.update');
        Route::delete('settings/paket-soal/{paketSoal}', [PaketSoalController::class, 'destroy'])->name('paket-soal.destroy');
        Route::post('settings/paket-soal/{paketSoal}/toggle-status', [PaketSoalController::class, 'toggleStatus'])->name('paket-soal.toggle-status');
        // Drill-down: questions inside a package
        Route::get('settings/paket-soal/{paketSoal}/kelola-soal', [PaketSoalController::class, 'kelolaSoal'])->name('paket-soal.kelola-soal');
        Route::post('settings/paket-soal/{paketSoal}/store-question', [PaketSoalController::class, 'storeQuestion'])->name('paket-soal.store-question');
        Route::put('settings/paket-soal/{paketSoal}/update-question/{soalUjian}', [PaketSoalController::class, 'updateQuestion'])->name('paket-soal.update-question');
        Route::delete('settings/paket-soal/{paketSoal}/destroy-question/{soalUjian}', [PaketSoalController::class, 'destroyQuestion'])->name('paket-soal.destroy-question');
        Route::post('settings/paket-soal/{paketSoal}/toggle-question-status/{soalUjian}', [PaketSoalController::class, 'toggleQuestionStatus'])->name('paket-soal.toggle-question-status');
        // Static helper route for edit question JSON
        Route::get('settings/paket-soal/{soalUjian}/edit-question', [PaketSoalController::class, 'edit'])->name('paket-soal.edit-question');
        // Deprecated: old soal-ujian route redirects to paket-soal
        Route::get('settings/soal-ujian', function () {
            return redirect()->route('paket-soal.index');
        })->name('soal-ujian.index');

        // Syarat Berkas - document requirement templates
        Route::get('settings/syarat-berkas', [SyaratBerkasController::class, 'index'])->name('syarat-berkas.index');
        Route::post('settings/syarat-berkas', [SyaratBerkasController::class, 'store'])->name('syarat-berkas.store');
        Route::put('settings/syarat-berkas/{templateBerkas}', [SyaratBerkasController::class, 'update'])->name('syarat-berkas.update');
        Route::delete('settings/syarat-berkas/{templateBerkas}', [SyaratBerkasController::class, 'destroy'])->name('syarat-berkas.destroy');
        Route::post('settings/syarat-berkas/{templateBerkas}/toggle-status', [SyaratBerkasController::class, 'toggleStatus'])->name('syarat-berkas.toggle-status');
        Route::get('settings/syarat-berkas/{templateBerkas}/kelola-dokumen', [SyaratBerkasController::class, 'kelolaDokumen'])->name('syarat-berkas.kelola-dokumen');
        Route::post('settings/syarat-berkas/{templateBerkas}/store-dokumen', [SyaratBerkasController::class, 'storeDokumen'])->name('syarat-berkas.store-dokumen');
        Route::put('settings/syarat-berkas/{templateBerkas}/update-dokumen/{syaratDokumen}', [SyaratBerkasController::class, 'updateDokumen'])->name('syarat-berkas.update-dokumen');
        Route::delete('settings/syarat-berkas/{templateBerkas}/destroy-dokumen/{syaratDokumen}', [SyaratBerkasController::class, 'destroyDokumen'])->name('syarat-berkas.destroy-dokumen');
        Route::get('settings/syarat-berkas/{syaratDokumen}/edit-dokumen', [SyaratBerkasController::class, 'edit'])->name('syarat-berkas.edit-dokumen');
        Route::get('settings/logs', [ActivityLogController::class, 'index'])->name('logs.index');
        Route::get('settings/tagihan', [AdminTagihanController::class, 'index'])->name('settings.tagihan.index');
        Route::post('settings/tagihan/{paymentId}/verify', [AdminTagihanController::class, 'verify'])->name('settings.tagihan.verify');

        // Periode Akademik (Settings)
        Route::get('settings/periode', [PeriodeController::class, 'index'])->name('periode.index');
        Route::get('settings/periode/create', [PeriodeController::class, 'create'])->name('periode.create');
        Route::get('settings/periode/{periode}/edit', [PeriodeController::class, 'edit'])->name('periode.edit');
        Route::post('settings/periode', [PeriodeController::class, 'store'])->name('periode.store');
        Route::put('settings/periode/{periode}', [PeriodeController::class, 'update'])->name('periode.update');
        Route::post('settings/periode/{periode}/toggle-active', [PeriodeController::class, 'toggleActive'])->name('periode.toggle-active');
        Route::delete('settings/periode/{periode}', [PeriodeController::class, 'destroy'])->name('periode.destroy');
        Route::get('settings/periode/active', [PeriodeController::class, 'getActive'])->name('periode.active');

        // Kelola Wawancara (Settings)
        Route::get('settings/wawancara', [WawancaraController::class, 'index'])->name('wawancara.index');
        Route::post('settings/wawancara/schedule', [WawancaraController::class, 'storeSchedule'])->name('wawancara.schedule');
        Route::post('settings/wawancara/hasil', [WawancaraController::class, 'storeHasil'])->name('wawancara.hasil');

        // CRM Leads
        Route::get('settings/crm-leads', [CrmLeadController::class, 'index'])->name('crm-leads.index');
        Route::post('crm-leads/{crmLead}/status', [CrmLeadController::class, 'updateStatus'])->name('crm-leads.status');
        Route::post('crm-leads/{crmLead}/notes', [CrmLeadController::class, 'updateNotes'])->name('crm-leads.notes');
        Route::get('crm-leads/{crmLead}', [CrmLeadController::class, 'show'])->name('crm-leads.show');
        Route::delete('crm-leads/{crmLead}', [CrmLeadController::class, 'destroy'])->name('crm-leads.destroy');

        // Landing Page Configuration
        Route::get('settings/landing-page', [LandingPageController::class, 'index'])->name('settings.landing-page.index');
        Route::post('settings/landing-page/feature', [LandingPageController::class, 'storeFeature'])->name('settings.landing-page.store-feature');
        Route::put('settings/landing-page/feature/{landingFeature}', [LandingPageController::class, 'updateFeature'])->name('settings.landing-page.update-feature');
        Route::post('settings/landing-page/feature/{landingFeature}/toggle', [LandingPageController::class, 'toggleFeature'])->name('settings.landing-page.toggle-feature');
        Route::delete('settings/landing-page/feature/{landingFeature}', [LandingPageController::class, 'destroyFeature'])->name('settings.landing-page.destroy-feature');
        Route::post('settings/landing-page/settings', [LandingPageController::class, 'updateSettings'])->name('settings.landing-page.update-settings');
        Route::post('settings/landing-page/prodi', [LandingPageController::class, 'storeLandingProdi'])->name('settings.landing-page.store-prodi');
        Route::post('settings/landing-page/prodi/{landingProgramStudi}/toggle', [LandingPageController::class, 'toggleLandingProdi'])->name('settings.landing-page.toggle-prodi');
        Route::delete('settings/landing-page/prodi/{landingProgramStudi}', [LandingPageController::class, 'destroyLandingProdi'])->name('settings.landing-page.destroy-prodi');
        Route::post('settings/landing-page/facility', [LandingPageController::class, 'storeFacility'])->name('settings.landing-page.store-facility');
        Route::post('settings/landing-page/facility/{landingFacility}/toggle', [LandingPageController::class, 'toggleFacility'])->name('settings.landing-page.toggle-facility');
        Route::delete('settings/landing-page/facility/{landingFacility}', [LandingPageController::class, 'destroyFacility'])->name('settings.landing-page.destroy-facility');

        // Form Pendaftaran (Settings > Form Pendaftaran)
        Route::prefix('settings/form-pendaftaran')->name('settings.form-pendaftaran.')->group(function () {
            Route::get('/', [FormPendaftaranController::class, 'index'])->name('index');
            Route::get('/create', [FormPendaftaranController::class, 'create'])->name('create');
            Route::post('/', [FormPendaftaranController::class, 'store'])->name('store');
            Route::get('/{id}/builder', [FormPendaftaranController::class, 'builder'])->name('builder');
            Route::get('/{id}/edit', [FormPendaftaranController::class, 'edit'])->name('edit');
            Route::put('/{id}', [FormPendaftaranController::class, 'update'])->name('update');
            Route::post('/{id}/toggle-status', [FormPendaftaranController::class, 'toggleStatus'])->name('toggle-status');
            Route::delete('/{id}', [FormPendaftaranController::class, 'destroy'])->name('destroy');
        });

        // Form Builder (AJAX routes untuk form builder)
        Route::prefix('settings/form-builder')->name('settings.form-builder.')->group(function () {
            Route::get('/fields/{formId}', [FormPendaftaranController::class, 'getFields'])->name('get-fields');
            Route::post('/', [FormPendaftaranController::class, 'storeField'])->name('store');
            Route::post('/reorder', [FormPendaftaranController::class, 'reorderFields'])->name('reorder');
            Route::post('/{id}/toggle-status', [FormPendaftaranController::class, 'toggleFieldStatus'])->name('toggle-status');
            Route::post('/{id}/duplicate', [FormPendaftaranController::class, 'duplicateField'])->name('duplicate');
            Route::put('/{id}', [FormPendaftaranController::class, 'updateField'])->name('update');
            Route::delete('/{id}', [FormPendaftaranController::class, 'destroyField'])->name('destroy');
        });
    });
});
