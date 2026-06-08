<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun | Penerimaan Mahasiswa Baru</title>
    <link rel="icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" />
    <meta name="theme-color" content="#ffffff" />
    <script src="{{ asset('assets/js/vendors/color-modes.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" />
    <link rel="stylesheet" href="{{ asset('assets/libs/simplebar/dist/simplebar.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/@tabler/icons-webfont/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/css/select2.min.css') }}">
    <style>
        .btn-primary { --ds-btn-hover-bg: #d82939; --ds-btn-hover-border-color: #d82939; --ds-btn-active-bg: #c82635; --ds-btn-active-border-color: #c82635; }
        .site-logo-text { color: #1c252e !important; }
        [data-bs-theme="dark"] .site-logo-text { color: #cfd1d2 !important; }
        .select2-container--default .select2-selection--single {
            height: 42px;
            border: 1px solid #d9dee3;
            border-radius: 0.375rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            padding-left: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }
        [data-bs-theme="dark"] .select2-container--default .select2-selection--single {
            background: #2b2c40;
            border-color: #3e3f5a;
            color: #b2b2c4;
        }
        [data-bs-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #b2b2c4;
        }
        [data-bs-theme="dark"] .select2-dropdown {
            background: #2b2c40;
            border-color: #3e3f5a;
        }
        [data-bs-theme="dark"] .select2-container--default .select2-results__option--highlighted {
            background: #383a5c;
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
                                <span class="site-logo-text">Akademik</span>
                            </a>
                            <h1 class="mb-1">Buat Akun Baru</h1>
                            <p class="mb-0">Daftar untuk memulai proses pendaftaran mahasiswa baru.</p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-md-8 col-12">
                        <div class="card card-lg mb-6">
                            <div class="card-body p-6">
                                <!-- Google Sign In - TOP -->
                                <div class="text-center mb-4">
                                    <a href="{{ route('auth.google.register') }}" class="btn btn-white w-100 d-flex align-items-center justify-content-center gap-2 py-2 border">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                                        </svg>
                                        <span class="fw-semibold">Daftar dengan Google</span>
                                    </a>
                                </div>

                                <div class="text-center mb-4">
                                    <span class="text-muted small">atau daftar secara manual</span>
                                </div>

                                <form action="{{ route('register.post') }}" method="POST" novalidate>
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

                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="username" value="{{ old('username') }}" required placeholder="Buat username" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="Masukkan email aktif" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">No. WhatsApp <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" required placeholder="Contoh: 08123456789" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Asal Wilayah (Kabupaten/Kota)</label>
                                        <select class="form-control select2-regencies" name="regency_id" style="width: 100%;">
                                            <option value="">-- Pilih Kabupaten/Kota --</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <div class="password-field position-relative">
                                            <input type="password" class="form-control fakePassword" name="password" required placeholder="Minimal 8 karakter" />
                                            <span><i class="ti ti-eye-off passwordToggler"></i></span>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                        <div class="password-field position-relative">
                                            <input type="password" class="form-control fakePassword" name="password_confirmation" required placeholder="Ulangi password" />
                                            <span><i class="ti ti-eye-off passwordToggler"></i></span>
                                        </div>
                                    </div>

                                    <div class="d-grid mb-4">
                                        <button class="btn btn-primary" type="submit">Daftar Akun</button>
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
    <script>
        $(document).ready(function() {
            $('.select2-regencies').select2({
                language: 'id',
                placeholder: 'Cari Kabupaten/Kota...',
                allowClear: true,
                ajax: {
                    url: '{{ route("api.regencies.select2") }}',
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            q: params.term || '',
                            page: params.page || 1,
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.results,
                            pagination: data.pagination,
                        };
                    },
                    cache: true,
                },
                minimumInputLength: 1,
            });

            @if(old('regency_id'))
                $.ajax({
                    url: '{{ route("api.regencies.select2") }}?q=&page=1',
                    dataType: 'json',
                    success: function(data) {
                        var selected = data.results.find(function(item) {
                            return item.id == {{ old('regency_id') }};
                        });
                        if (selected) {
                            var option = new Option(selected.text, selected.id, true, true);
                            $('.select2-regencies').append(option).trigger('change');
                        }
                    }
                });
            @endif
        });
    </script>
</body>
</html>