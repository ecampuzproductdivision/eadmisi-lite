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
        .path-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            display: inline-block;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .password-field {
            position: relative;
        }
        .password-field .passwordToggler {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            font-size: 1.2rem;
            z-index: 5;
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
                            <h1 class="mb-1">Selamat Datang di Portal eAdmisi</h1>
                            <p class="mb-0 text-muted">
                                @if($path)
                                    Daftarkan akun portal eAdmisi Anda untuk memulai proses pendaftaran/admisi jalur <strong>{{ $path->name }}</strong>.
                                @else
                                    Isi data diri untuk membuat akun portal eAdmisi Anda dan memulai proses pendaftaran/admisi.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-8 col-md-10 col-12">
                        <div class="card card-lg mb-6">
                            <div class="card-body p-6">

                                @if($path)
                                <div class="text-center mb-4">
                                    <span class="path-badge">
                                        <i class="ti ti-arrow-right-circle me-1"></i>
                                        {{ $path->name }}
                                    </span>
                                </div>
                                @endif

                                <!-- Jika tidak ada path yang dipilih, tampilkan pilihan jalur -->
                                @if(!$path)
                                <div class="alert alert-info d-flex align-items-center gap-2 mb-4 py-2 small">
                                    <i class="ti ti-info-circle fs-5"></i>
                                    Silakan pilih jalur pendaftaran terlebih dahulu melalui halaman utama.
                                </div>
                                <div class="text-center mb-4">
                                    <a href="{{ route('pmb.landing') }}" class="btn btn-primary btn-lg d-inline-flex align-items-center gap-2">
                                        <i class="ti ti-arrow-left fs-4"></i> Kembali ke Beranda
                                    </a>
                                </div>
                                @else
                                <form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data" novalidate>
                                    @csrf
                                    <input type="hidden" name="jalur_id" value="{{ $path->id }}">

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

                                    <!-- ═══ DYNAMIC FORM FIELDS ═══ -->
                                    @if($formFields->isNotEmpty())
                                        @php
                                            $currentSection = '';
                                        @endphp
                                        <div class="row g-3">
                                        @foreach($formFields as $field)
                                            @php
                                                $fieldName = 'field_' . $field->id;
                                                $oldValue = old($fieldName);
                                                $widthClass = $field->width ?: 'col-md-6';
                                                $isRequired = $field->is_required ? ' <span class="text-danger">*</span>' : '';
                                                $isRequiredAttr = $field->is_required ? ' required' : '';
                                            @endphp

                                            @if($field->section && $field->section !== $currentSection)
                                                @php $currentSection = $field->section; @endphp
                                                @if(!$loop->first)
                                                    </div></div>
                                                @endif
                                                <div class="mb-4">
                                                <div class="section-header d-flex align-items-center gap-2 mb-3">
                                                    <i class="ti ti-file-text"></i> {{ $currentSection }}
                                                </div>
                                                <div class="row g-3">
                                            @endif

                                            <div class="{{ $widthClass }} mb-3">
                                                <label class="form-label">{{ $field->field_label }}{!! $isRequired !!}</label>

                                                @switch($field->field_type)
                                                    @case('textarea')
                                                        <textarea class="form-control" name="{{ $fieldName }}" rows="3" placeholder="{{ $field->placeholder ?? '' }}"{{ $isRequiredAttr }}>{{ $oldValue }}</textarea>
                                                        @break

                                                    @case('select')
                                                        <select class="form-select" name="{{ $fieldName }}"{{ $isRequiredAttr }}>
                                                            <option value="">-- {{ $field->placeholder ?? 'Pilih ' . $field->field_label }} --</option>
                                                            @if($field->options && is_array($field->options))
                                                                @foreach($field->options as $opt)
                                                                    <option value="{{ $opt }}" {{ $oldValue == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @break

                                                    @case('radio')
                                                        @if($field->options && is_array($field->options))
                                                            <div class="d-flex flex-wrap gap-3">
                                                                @foreach($field->options as $opt)
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="{{ $fieldName }}" value="{{ $opt }}" id="{{ $fieldName }}_{{ $loop->index }}" {{ $oldValue == $opt ? 'checked' : '' }}{{ $isRequiredAttr }}>
                                                                    <label class="form-check-label" for="{{ $fieldName }}_{{ $loop->index }}">{{ $opt }}</label>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="{{ $fieldName }}" value="1" id="{{ $fieldName }}" {{ $oldValue == '1' ? 'checked' : '' }}{{ $isRequiredAttr }}>
                                                                <label class="form-check-label" for="{{ $fieldName }}">Ya</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="{{ $fieldName }}" value="0" id="{{ $fieldName }}_no" {{ $oldValue === '0' ? 'checked' : '' }}{{ $isRequiredAttr }}>
                                                                <label class="form-check-label" for="{{ $fieldName }}_no">Tidak</label>
                                                            </div>
                                                        @endif
                                                        @break

                                                    @case('checkbox')
                                                        @if($field->options && is_array($field->options))
                                                            @foreach($field->options as $opt)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="{{ $fieldName }}[]" value="{{ $opt }}" id="{{ $fieldName }}_{{ $loop->index }}" {{ is_array($oldValue) && in_array($opt, $oldValue) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="{{ $fieldName }}_{{ $loop->index }}">{{ $opt }}</label>
                                                            </div>
                                                            @endforeach
                                                        @else
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="{{ $fieldName }}" value="1" id="{{ $fieldName }}" {{ $oldValue == '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="{{ $fieldName }}">{{ $field->field_label }}</label>
                                                            </div>
                                                        @endif
                                                        @break

                                                    @case('date')
                                                        <input type="date" class="form-control" name="{{ $fieldName }}" value="{{ $oldValue }}"{{ $isRequiredAttr }}>
                                                        @break

                                                    @case('number')
                                                        <input type="number" class="form-control" name="{{ $fieldName }}" value="{{ $oldValue }}" placeholder="{{ $field->placeholder ?? '' }}"{{ $isRequiredAttr }}>
                                                        @break

                                                    @case('email')
                                                        <input type="email" class="form-control" name="{{ $fieldName }}" value="{{ $oldValue }}" placeholder="{{ $field->placeholder ?? '' }}"{{ $isRequiredAttr }}>
                                                        @break

                                                    @case('tel')
                                                        <input type="tel" class="form-control" name="{{ $fieldName }}" value="{{ $oldValue }}" placeholder="{{ $field->placeholder ?? '' }}"{{ $isRequiredAttr }}>
                                                        @break

                                                    @case('file')
                                                        <input type="file" class="form-control" name="{{ $fieldName }}"{{ $isRequiredAttr }}>
                                                        @break

                                                    @default
                                                        <input type="text" class="form-control" name="{{ $fieldName }}" value="{{ $oldValue }}" placeholder="{{ $field->placeholder ?? '' }}"{{ $isRequiredAttr }}>
                                                @endswitch

                                                @if($field->help_text)
                                                    <small class="text-muted d-block mt-1">{{ $field->help_text }}</small>
                                                @endif
                                            </div>

                                            @if($loop->last)
                                                </div></div>
                                            @endif
                                        @endforeach
                                    @else
                                        <!-- Jika form tidak punya fields, tampilkan default fields -->
                                        <div class="row g-3">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="field_default_nama" value="{{ old('field_default_nama') }}" required placeholder="Masukkan nama lengkap">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
                                                <input type="tel" class="form-control" name="field_default_hp" value="{{ old('field_default_hp') }}" required placeholder="08xxxxxxxxxx">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" name="field_default_email" value="{{ old('field_default_email') }}" required placeholder="contoh@email.com">
                                            </div>
                                        </div>
                                    @endif

                                    <!-- ═══ DYNAMIC DOCUMENT UPLOAD SECTION ═══ -->
                                    @if($path && $path->is_upload_berkas)
                                        <div class="section-header d-flex align-items-center gap-2 mb-3 mt-4">
                                            <i class="ti ti-upload"></i> Unggah Berkas Persyaratan
                                        </div>
                                        <p class="text-muted small mb-3">Silakan unggah berkas persyaratan yang diperlukan untuk pendaftaran jalur ini.</p>
                                        <div class="row g-3 mb-4">
                                            @foreach($path->syaratBerkas as $berkas)
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Unggah {{ $berkas->nama_syarat }} (Format: {{ $berkas->ekstensi_diizinkan }} / Max: {{ $berkas->max_size }} KB) @if($berkas->status_wajib) <span class="text-danger">*</span> @endif</label>
                                                    <input type="file" name="dokumen_berkas[{{ $berkas->id }}]" class="form-control" {{ $berkas->status_wajib ? 'required' : '' }}>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- ═══ PROGRAM STUDI PILIHAN DROPDOWN ═══ -->
                                    @if($path && $path->jumlah_pilihan_prodi > 0)
                                        <div class="section-header d-flex align-items-center gap-2 mb-3 mt-4">
                                            <i class="ti ti-school"></i> Pilih Program Studi Pilihan
                                        </div>
                                        <p class="text-muted small mb-3">Pilih program studi yang ingin Anda ambil pada pendaftaran ini.</p>
                                        <div class="row g-3 mb-4">
                                            @for ($i = 1; $i <= $path->jumlah_pilihan_prodi; $i++)
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Program Studi Pilihan {{ $i }} <span class="text-danger">*</span></label>
                                                    <select name="pilihan_prodi[{{ $i }}]" class="form-select prodi-select" required>
                                                        <option value="">-- Pilih Program Studi Pilihan {{ $i }} --</option>
                                                        @foreach($path->programStudis as $prodi)
                                                            <option value="{{ $prodi->id }}" {{ old("pilihan_prodi.{$i}") == $prodi->id ? 'selected' : '' }}>
                                                                {{ $prodi->nama_prodi ?: $prodi->nama }} ({{ $prodi->jenjang_akademik ?? $prodi->jenjang }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endfor
                                        </div>
                                    @endif

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
                                            <i class="ti ti-send fs-4"></i> Daftar Sekarang
                                        </button>
                                    </div>
                                </form>
                                @endif

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
        document.addEventListener('DOMContentLoaded', function () {
            const selects = document.querySelectorAll('.prodi-select');
            
            function updateDropdowns() {
                // Collect all currently selected values (excluding empty strings)
                const selectedValues = Array.from(selects).map(s => s.value).filter(v => v !== "");
                
                selects.forEach(currentSelect => {
                    const options = currentSelect.querySelectorAll('option');
                    options.forEach(option => {
                        if (option.value !== "") {
                            // Disable the option if it is selected in ANOTHER dropdown, but keep it enabled in the current one
                            const isSelectedElsewhere = selectedValues.includes(option.value) && currentSelect.value !== option.value;
                            option.disabled = isSelectedElsewhere;
                        }
                    });
                });
            }

            selects.forEach(select => {
                select.addEventListener('change', updateDropdowns);
            });

            // Initial run to apply states if old values are loaded
            updateDropdowns();
        });
    </script>
</body>
</html>
