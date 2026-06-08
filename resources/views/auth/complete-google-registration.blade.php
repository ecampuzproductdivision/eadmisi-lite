<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lengkapi Data | Penerimaan Mahasiswa Baru</title>
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
        .select2-container--default .select2-selection--single { height: 42px; border: 1px solid #d9dee3; border-radius: 0.375rem; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 40px; padding-left: 12px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px; }
        [data-bs-theme="dark"] .select2-container--default .select2-selection--single { background: #2b2c40; border-color: #3e3f5a; color: #b2b2c4; }
        [data-bs-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered { color: #b2b2c4; }
        [data-bs-theme="dark"] .select2-dropdown { background: #2b2c40; border-color: #3e3f5a; }
        [data-bs-theme="dark"] .select2-container--default .select2-results__option--highlighted { background: #383a5c; }
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
                            <div class="icon-shape icon-xl bg-success-subtle text-success rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                <i class="ti ti-brand-google fs-1"></i>
                            </div>
                            <h1 class="mb-1">Lengkapi Data Diri</h1>
                            <p class="mb-0">Hai <strong>{{ $user->name }}</strong>, lengkapi data berikut untuk menyelesaikan pendaftaran.</p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-md-8 col-12">
                        <div class="card card-lg mb-6">
                            <div class="card-body p-6">
                                @if (session('success'))
                                    <div class="alert alert-success py-2 small">{{ session('success') }}</div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger mb-4 py-2 small">
                                        <ul class="mb-0 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 mb-4">
                                    <img src="{{ $user->avatar_url }}" class="rounded-circle" width="50" height="50" style="object-fit: cover;" alt="">
                                    <div>
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>

                                <form action="{{ route('google.complete.registration.post') }}" method="POST" novalidate>
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label">No. WhatsApp <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" required placeholder="Contoh: 08123456789" />
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Asal Wilayah (Kabupaten/Kota)</label>
                                        <select class="form-control select2-regencies" name="regency_id" style="width: 100%;">
                                            <option value="">-- Pilih Kabupaten/Kota --</option>
                                        </select>
                                    </div>

                                    <div class="d-grid">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="ti ti-check me-1"></i> Selesai & Masuk
                                        </button>
                                    </div>
                                </form>
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
                        return { q: params.term || '', page: params.page || 1 };
                    },
                    processResults: function (data) {
                        return { results: data.results, pagination: data.pagination };
                    },
                    cache: true,
                },
                minimumInputLength: 1,
            });
        });
    </script>
</body>
</html>