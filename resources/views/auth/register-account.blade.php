<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Buat Akun | Penerimaan Mahasiswa Baru</title>
    <link rel="icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" />
    <meta name="theme-color" content="#ffffff" />
    <script src="{{ asset('assets/js/vendors/color-modes.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" />
    <link rel="stylesheet" href="{{ asset('assets/libs/simplebar/dist/simplebar.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/@tabler/icons-webfont/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/css/select2.min.css') }}">
    <style>
        body {
            background-image: url('{{ App\Models\LandingSetting::getValue("login_register_background") ? asset(App\Models\LandingSetting::getValue("login_register_background")) : "" }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        [data-bs-theme="dark"] body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            z-index: 0;
        }
        [data-bs-theme="dark"] main {
            position: relative;
            z-index: 1;
        }
        .btn-primary { --ds-btn-hover-bg: #d82939; --ds-btn-hover-border-color: #d82939; --ds-btn-active-bg: #c82635; --ds-btn-active-border-color: #c82635; }
        .site-logo-text { color: #1c252e !important; }
        [data-bs-theme="dark"] .site-logo-text { color: #cfd1d2 !important; }
        .password-field { position: relative; }
        .password-field .passwordToggler {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%); cursor: pointer;
            color: #6c757d; font-size: 1.2rem; z-index: 5;
        }
        .section-header {
            background: #f8f9fa;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #495057;
        }
        [data-bs-theme="dark"] .section-header {
            background: #2b2c40;
            color: #b2b2c4;
        }
        .select2-container--default .select2-selection--single {
            height: 42px; border: 1px solid #d9dee3; border-radius: 0.375rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px; padding-left: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }
        [data-bs-theme="dark"] .select2-container--default .select2-selection--single {
            background: #2b2c40; border-color: #3e3f5a; color: #b2b2c4;
        }
        [data-bs-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #b2b2c4;
        }
        [data-bs-theme="dark"] .select2-dropdown {
            background: #2b2c40; border-color: #3e3f5a;
        }
        [data-bs-theme="dark"] .select2-container--default .select2-results__option--highlighted {
            background: #383a5c;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered .select2-tag {
            background: transparent;
            color: inherit;
        }
    </style>
</head>
<body>
    <main class="d-flex flex-column justify-content-center py-6">
        <section>
            <div class="container">
                <div class="row mb-6">
                    <div class="col-xl-4 offset-xl-4 col-md-12 col-12">
                        <div class="text-center">
                            <a href="/" class="fs-2 fw-bold d-flex align-items-center gap-2 justify-content-center mb-4 text-decoration-none">
                                <img src="{{ asset('assets/images/brand/logo/logo-light.png') }}" class="brand-logo-img" width="36" alt="" />
                                <span class="site-logo-text">eAdmisi</span>
                            </a>
                            <h1 class="mb-1">Buat Akun Portal eAdmisi</h1>
                            <p class="mb-0 text-muted">
                                Isi data diri untuk membuat akun portal. Setelah login, Anda bisa memilih jalur pendaftaran dan mendaftar.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-8 col-md-10 col-12">
                        <div class="card card-lg mb-6">
                            <div class="card-body p-6">

                                <!-- Google Fast Registration Button -->
                                <div class="mb-4 text-center">
                                    <a href="{{ route('auth.google.register') }}" class="btn btn-outline-danger btn-lg w-100 d-flex align-items-center justify-content-center gap-2 py-3 shadow-sm font-weight-bold">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                                        </svg>
                                        Daftar Lebih Cepat via Google
                                    </a>
                                    <div class="d-flex align-items-center my-3">
                                        <hr class="flex-grow-1">
                                        <span class="px-3 text-muted small">atau isi formulir pendaftaran manual</span>
                                        <hr class="flex-grow-1">
                                    </div>
                                </div>

                                <form action="{{ route('register-account.post') }}" method="POST" novalidate>
                                    @csrf

                                    @if ($errors->any())
                                        <div class="alert alert-danger mb-4 py-2 small">
                                            <ul class="mb-0 ps-3">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if(session('error'))
                                        <div class="alert alert-danger mb-4 py-2 small">
                                            <i class="ti ti-alert-circle me-1"></i>{{ session('error') }}
                                        </div>
                                    @endif

                                    <!-- ═══ FORM SECTION: DATA DIRI ═══ -->
                                    <div class="section-header d-flex align-items-center gap-2 mb-3">
                                        <i class="ti ti-user"></i> Data Diri
                                    </div>

                                    <div class="row g-3">
                                        <!-- 1. Nama Lengkap -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Masukkan nama lengkap">
                                        </div>

                                        <!-- 2. Jenis Kelamin -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                            <div class="d-flex flex-wrap gap-3 mt-1">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="L" id="jk_l" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="jk_l">Laki-Laki</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="P" id="jk_p" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="jk_p">Perempuan</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 3. Alamat Email -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="contoh@email.com">
                                        </div>

                                        <!-- 4. Nomor Handphone -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nomor Handphone <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" name="nomor_handphone" value="{{ old('nomor_handphone') }}" required placeholder="08xxxxxxxxxx">
                                        </div>

                                        <!-- 5. Dimana Kamu Tinggal? (Select2 AJAX + tags) -->
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Dimana Kamu Tinggal? <span class="text-danger">*</span></label>
                                            <select class="form-control select2-location-search" name="domisili" required style="width: 100%;">
                                                <option value=""></option>
                                                @php
                                                    $locations = json_decode(file_get_contents(public_path('assets/data/wilayah_indonesia.json')), true) ?? [];
                                                    $oldDomisili = old('domisili');
                                                    $oldDomisiliExists = false;
                                                @endphp
                                                @foreach($locations as $loc)
                                                    @php
                                                        $isSelected = $oldDomisili === $loc['id'];
                                                        if ($isSelected) $oldDomisiliExists = true;
                                                    @endphp
                                                    <option value="{{ $loc['id'] }}" {{ $isSelected ? 'selected' : '' }}>{{ $loc['text'] }}</option>
                                                @endforeach
                                                @if($oldDomisili && !$oldDomisiliExists)
                                                    <option value="{{ $oldDomisili }}" selected>{{ $oldDomisili }}</option>
                                                @endif
                                            </select>
                                            <small class="text-muted d-block mt-1">Ketik nama kota/kabupaten. Jika tidak ditemukan, Anda bisa mengetikkan lokasi secara manual.</small>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <!-- ═══ PASSWORD AUTHENTICATION FIELDS ═══ -->
                                    <div class="section-header d-flex align-items-center gap-2 mb-3">
                                        <i class="ti ti-lock"></i> Buat Password Portal
                                    </div>
                                    <p class="text-muted small mb-3">Buat password untuk mengakses portal pendaftaran Anda.</p>
                                    <div class="row g-3">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Buat Password Portal <span class="text-danger">*</span></label>
                                            <div class="password-field">
                                                <input type="password" class="form-control fakePassword" name="password" required placeholder="Minimal 8 karakter" />
                                                <span><i class="ti ti-eye-off passwordToggler"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                            <div class="password-field">
                                                <input type="password" class="form-control fakePassword" name="password_confirmation" required placeholder="Ulangi password" />
                                                <span><i class="ti ti-eye-off passwordToggler"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid mt-4">
                                        <button class="btn btn-primary btn-lg d-flex align-items-center justify-content-center gap-2" type="submit">
                                            <i class="ti ti-send fs-4"></i> Buat Akun
                                        </button>
                                    </div>
                                </form>

                                <hr class="my-4">
                                <div class="text-center">
                                    <p class="mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-semibold">Masuk di sini</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/id.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/password.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/location-search.js') }}"></script>
</body>
</html>