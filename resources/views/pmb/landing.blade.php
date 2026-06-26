<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PMB | Penerimaan Mahasiswa Baru - eAdmisi</title>
    <link rel="icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" />
    <meta name="theme-color" content="#ffffff" />
    <script src="{{ asset('assets/js/vendors/color-modes.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" />
    <link rel="stylesheet" href="{{ asset('assets/libs/simplebar/dist/simplebar.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/@tabler/icons-webfont/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/theme.min.css') }}">
    <style>
        .btn-primary {
            --ds-btn-hover-bg: #d82939;
            --ds-btn-hover-border-color: #d82939;
            --ds-btn-active-bg: #c82635;
            --ds-btn-active-border-color: #c82635;
        }
        .hero-section {
            background: linear-gradient(135deg, #1c252e 0%, #2d3748 50%, #1a365d 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: rgba(246, 58, 76, 0.08);
        }
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(246, 58, 76, 0.05);
        }
        .floating-card { animation: float 6s ease-in-out infinite; }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .stats-card {
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-5px);
        }
        .path-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 16px;
            overflow: hidden;
        }
        .path-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .feature-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .nav-pmb {
            background: rgba(28,37,46,0.95);
            backdrop-filter: blur(10px);
        }
        .step-number {
            width: 48px; height: 48px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; font-weight: 700; font-size: 1.2rem;
        }
        [data-bs-theme="dark"] .feature-card,
        [data-bs-theme="dark"] .path-card { border-color: rgba(255,255,255,0.05); }

        [data-bs-theme="light"] .bg-body-tertiary { background-color: #ffffff !important; }
        [data-bs-theme="light"] section { background-color: #ffffff; }
        [data-bs-theme="light"] .nav-pmb {
            background: #ffffff !important;
            border-bottom: 1px solid #e9ecef;
        }
        [data-bs-theme="light"] .nav-pmb .nav-link { color: #495057 !important; }
        [data-bs-theme="light"] .nav-pmb .navbar-brand .fw-bold { color: #1c252e !important; }
        [data-bs-theme="light"] .hero-section { background: #ffffff !important; }
        [data-bs-theme="light"] .hero-section h1,
        [data-bs-theme="light"] .hero-section .text-white-50,
        [data-bs-theme="light"] .hero-section .lead { color: #1c252e !important; }
        [data-bs-theme="light"] .hero-section .btn-outline-light {
            border-color: #dee2e6; color: #495057;
        }
        [data-bs-theme="light"] .hero-section .btn-outline-light:hover { background: #f8f9fa; }
        [data-bs-theme="light"] .hero-section .card .text-dark,
        [data-bs-theme="light"] .hero-section .card .text-muted { color: #495057 !important; }
        [data-bs-theme="light"] .hero-section .card hr { border-color: #e9ecef !important; }
        [data-bs-theme="light"] .hero-section::before,
        [data-bs-theme="light"] .hero-section::after { display: none; }
        [data-bs-theme="light"] .hero-section .badge { color: #dc3545 !important; }
        [data-bs-theme="light"] .path-card { border-color: #e9ecef !important; }
        [data-bs-theme="light"] .stats-card {
            background: #ffffff !important;
            border-color: #e9ecef !important;
        }
        [data-bs-theme="light"] .stats-card .text-white-50 { color: #6c757d !important; }
        [data-bs-theme="light"] .stats-card .text-danger { color: #dc3545 !important; }
        [data-bs-theme="light"] section[style*="linear-gradient"] { background: #ffffff !important; }
        [data-bs-theme="light"] section[style*="linear-gradient"] h2 { color: #1c252e !important; }
        [data-bs-theme="light"] section[style*="linear-gradient"] p { color: #6c757d !important; }
        [data-bs-theme="light"] section[style*="linear-gradient"] .btn-outline-light {
            border-color: #dee2e6; color: #495057;
        }
        [data-bs-theme="light"] section[style*="linear-gradient"] .btn-outline-light:hover { background: #f8f9fa; }
        [data-bs-theme="light"] footer {
            background: #ffffff !important;
            border-top: 1px solid #e9ecef;
        }
        [data-bs-theme="light"] footer .text-white-50,
        [data-bs-theme="light"] footer .text-white-50.small { color: #6c757d !important; }
        [data-bs-theme="light"] footer .fw-bold.text-white { color: #1c252e !important; }
        [data-bs-theme="light"] footer hr { border-color: #e9ecef !important; }
        [data-bs-theme="light"] footer a.text-white-50 { color: #6c757d !important; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg nav-pmb fixed-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <img src="{{ asset('assets/images/brand/logo/logo-light.png') }}" width="32" alt="">
                <span class="fw-bold text-white">eAdmisi</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPMB">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarPMB">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                    <li class="nav-item"><a class="nav-link text-white-50" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link text-white-50" href="#jalur">Jalur</a></li>
                    <li class="nav-item"><a class="nav-link text-white-50" href="#alur">Alur PMB</a></li>
                    <li class="nav-item"><a class="nav-link text-white-50" href="#tanya-dulu">Tanya Dulu</a></li>
                    <li class="nav-item"><a class="nav-link text-white-50" href="#faq">FAQ</a></li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-outline-dark d-flex align-items-center gap-2">
                            <i class="ti ti-user-plus fs-5"></i> Daftar
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('login') }}" class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="ti ti-login fs-5"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ═══ HERO SECTION ═══ -->
    <section class="hero-section d-flex align-items-center">
        <div class="container position-relative" style="z-index: 1;">
            <div class="row align-items-center min-vh-100 py-8">
                <div class="col-lg-7 py-8">
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 fs-6 px-3 py-2 mb-4">
                        <i class="ti ti-sparkles me-1"></i> Penerimaan Mahasiswa Baru TA 2026/2027
                    </span>
                    <h1 class="display-3 fw-bold text-white mb-4">
                        Mulai Perjalanan<br>
                        <span class="text-danger">Akademik</span> Anda
                    </h1>
                    <p class="lead text-white-50 mb-6" style="max-width: 540px;">
                        Bergabunglah dengan ribuan mahasiswa lainnya. Proses pendaftaran mudah, cepat, dan transparan. Raih masa depanmu bersama kami.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg d-flex align-items-center gap-2 px-5">
                            <i class="ti ti-user-plus fs-4"></i> Daftar Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg d-flex align-items-center gap-2 px-5">
                            <i class="ti ti-login fs-4"></i> Masuk
                        </a>
                        <a href="#jalur" class="btn btn-outline-light btn-lg d-flex align-items-center gap-2 px-5">
                            <i class="ti ti-info-circle fs-4"></i> Lihat Jalur
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                    <div class="floating-card">
                        <div class="card shadow-lg border-0" style="width: 320px; border-radius: 20px;">
                            <div class="card-body p-5 text-center">
                                <div class="icon-shape icon-xl bg-danger-subtle text-danger rounded-circle mx-auto mb-4" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                    <i class="ti ti-school fs-1"></i>
                                </div>
                                <h4 class="fw-bold">Telah Bergabung</h4>
                                <div class="display-4 fw-bold text-danger mb-2">12,450+</div>
                                <p class="text-muted mb-0">Mahasiswa Aktif</p>
                                <hr class="my-4">
                                <div class="d-flex justify-content-between text-start small">
                                    <div><div class="fw-bold text-dark">98%</div><div class="text-muted">Kelulusan</div></div>
                                    <div><div class="fw-bold text-dark">150+</div><div class="text-muted">Program Studi</div></div>
                                    <div><div class="fw-bold text-dark">4.8</div><div class="text-muted">Rating</div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ STATS BAR ═══ -->
    <section class="position-relative" style="margin-top: -80px;">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="stats-card rounded-4 p-4 text-center">
                        <div class="text-danger fs-1 fw-bold mb-1">12K+</div>
                        <div class="text-white-50 small">Mahasiswa</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stats-card rounded-4 p-4 text-center">
                        <div class="text-danger fs-1 fw-bold mb-1">150+</div>
                        <div class="text-white-50 small">Program Studi</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stats-card rounded-4 p-4 text-center">
                        <div class="text-danger fs-1 fw-bold mb-1">500+</div>
                        <div class="text-white-50 small">Dosen Ahli</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stats-card rounded-4 p-4 text-center">
                        <div class="text-danger fs-1 fw-bold mb-1">50+</div>
                        <div class="text-white-50 small">Tahun Pengabdian</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ TENTANG ═══ -->
    <section id="tentang" class="py-8">
        <div class="container">
            <div class="row align-items-center g-6">
                <div class="col-lg-6">
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 mb-3">Tentang Kami</span>
                    <h2 class="fw-bold display-6 mb-4">{!! $settings['landing_about_title']->value ?? 'Mengapa Memilih<br>Kampus Kami?' !!}</h2>
                    <p class="text-muted mb-6" style="max-width: 480px;">{{ $settings['landing_about_description']->value ?? 'Kami berkomitmen untuk memberikan pendidikan berkualitas dengan kurikulum yang relevan terhadap kebutuhan industri dan perkembangan teknologi terkini.' }}</p>
                    <div class="d-flex flex-column gap-4">
                        @forelse($features as $f)
                        <div class="d-flex gap-3">
                            <div class="icon-shape icon-md bg-{{ $f->warna_skema }}-subtle text-{{ $f->warna_skema }} rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;"><i class="ti {{ $f->nama_icon }} fs-4"></i></div>
                            <div><h6 class="fw-bold mb-1">{{ $f->judul_poin }}</h6><p class="text-muted small mb-0">{{ $f->deskripsi_poin }}</p></div>
                        </div>
                        @empty
                        <p class="text-muted">Belum ada data keunggulan.</p>
                        @endforelse
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://placehold.co/600x500/f0f0f0/333?text=Kampus+Kami" alt="Kampus" class="img-fluid rounded-4 shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ JALUR PENDAFTARAN (DYNAMIC) ═══ -->
    <section id="jalur" class="py-8 bg-body-tertiary">
        <div class="container">
            <div class="text-center mb-8">
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 mb-3">Jalur Pendaftaran</span>
                <h2 class="fw-bold display-6 mb-3">Pilih Jalur Pendaftaran</h2>
                <p class="text-muted mx-auto" style="max-width: 540px;">Tersedia berbagai jalur pendaftaran yang dapat dipilih sesuai dengan kriteria Anda</p>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="ti ti-circle-check fs-4 me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="row g-4">
                @forelse($activePaths as $path)
                @php
                    $isOpen = $path->registration_start && $path->registration_end
                        ? now()->between($path->registration_start, $path->registration_end)
                        : true;
                    $sisaKuota = $path->quota ? $path->quota - ($path->terdaftar ?? 0) : null;
                @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="card path-card h-100 border-0 shadow-sm">
                        <div class="card-body p-5 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge bg-{{ $path->color ?? 'secondary' }}-subtle text-{{ $path->color ?? 'secondary' }} px-3 py-2 fs-6 mb-2">
                                        {{ $path->kategori->nama ?? 'Jalur' }}
                                    </span>
                                    <h5 class="fw-bold mb-1 mt-2">{{ $path->name }}</h5>
                                </div>
                                @if($isOpen)
                                    <span class="badge bg-success-subtle text-success px-3 py-2 fs-6">
                                        <i class="ti ti-circle-check me-1"></i>Buka
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 fs-6">
                                        <i class="ti ti-clock me-1"></i>Tutup
                                    </span>
                                @endif
                            </div>

                            @if($path->description)
                            <p class="text-muted small mb-4">{{ Str::limit($path->description, 100) }}</p>
                            @endif

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center border-top pt-3 mb-3 small">
                                    <span class="text-muted">
                                        <i class="ti ti-calendar me-1"></i>
                                        @if($path->registration_start && $path->registration_end)
                                            {{ $path->registration_start->format('d/m/Y') }} - {{ $path->registration_end->format('d/m/Y') }}
                                        @else
                                            Sepanjang Tahun
                                        @endif
                                    </span>
                                    <span class="fw-bold text-danger fs-5">Rp {{ number_format($path->fee, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <small class="text-muted">
                                        <i class="ti ti-users me-1"></i>
                                        @if($sisaKuota !== null && $sisaKuota > 0)
                                            Sisa <strong>{{ $sisaKuota }}</strong> kursi
                                        @elseif($sisaKuota !== null && $sisaKuota <= 0)
                                            <span class="text-danger">Penuh</span>
                                        @else
                                            Kuota tak terbatas
                                        @endif
                                    </small>
                                    @if($path->jumlah_pilihan_prodi)
                                    <small class="text-muted"><i class="ti ti-checks me-1"></i>{{ $path->jumlah_pilihan_prodi }} pilihan</small>
                                    @endif
                                </div>
                                <a href="{{ route('register') }}?path={{ $path->code }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i class="ti ti-arrow-right fs-5"></i> Daftar Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-6">
                    <i class="ti ti-road-off text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted">Belum ada jalur pendaftaran yang tersedia saat ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ═══ PROGRAM STUDI ═══ -->
    <section id="program" class="py-8">
        <div class="container">
            <div class="text-center mb-8">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 mb-3">Program Studi</span>
                <h2 class="fw-bold display-6 mb-3">Pilihan Program Studi</h2>
                <p class="text-muted mx-auto" style="max-width: 540px;">Tersedia berbagai program studi yang dapat disesuaikan dengan minat dan bakat Anda</p>
            </div>
            <div class="row g-4">
                @forelse($programStudis as $lp)
                @php
                    $prodi = $lp->programStudi;
                    $color = ['primary', 'danger', 'success', 'warning', 'info', 'secondary', 'dark'][$loop->index % 7];
                    $jenjang = $prodi->jenjang_akademik ?? $prodi->jenjang ?? 'S1';
                    $nama = $prodi->nama_prodi ?: $prodi->nama;
                @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body p-5">
                            <div class="icon-shape icon-lg bg-{{ $color }}-subtle text-{{ $color }} rounded-3 mb-4 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;"><i class="ti {{ $lp->kode_icon }} fs-2"></i></div>
                            <h5 class="fw-bold mb-2">{{ $nama }}</h5>
                            <p class="text-muted small mb-3">{{ $prodi->kode ?? $jenjang }}</p>
                            <p class="text-muted small mb-4">{{ $lp->deskripsi_singkat ?: ($prodi->deskripsi_singkat ?? 'Program studi unggulan dengan kurikulum berbasis kompetensi.') }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success-subtle text-success">Akreditasi {{ $lp->akreditasi ?? ($prodi->akreditasi ?? 'A') }}</span>
                                <small class="text-muted"><i class="ti ti-clock me-1"></i> {{ $lp->jumlah_semester ?? ($jenjang == 'D3' ? '6' : '8') }} Semester</small>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-6">
                    <i class="ti ti-school-off text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted">Belum ada program studi yang tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ═══ ALUR PMB ═══ -->
    <section id="alur" class="py-8 bg-body-tertiary">
        <div class="container">
            <div class="text-center mb-8">
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 mb-3">Alur Pendaftaran</span>
                <h2 class="fw-bold display-6 mb-3">Langkah Mudah Mendaftar</h2>
                <p class="text-muted mx-auto" style="max-width: 540px;">Ikuti langkah-langkah berikut untuk memulai perjalanan akademik Anda</p>
            </div>
            <div class="row g-4">
                @php
                    $steps = [
                        ['num' => '1', 'color' => 'danger', 'title' => 'Buat Akun', 'desc' => 'Daftar dan buat akun PMB Anda dengan mengisi data diri'],
                        ['num' => '2', 'color' => 'warning', 'title' => 'Isi Data Pendaftaran', 'desc' => 'Lengkapi formulir pendaftaran dan pilih program studi'],
                        ['num' => '3', 'color' => 'info', 'title' => 'Upload Dokumen', 'desc' => 'Unggah dokumen persyaratan yang diperlukan'],
                        ['num' => '4', 'color' => 'success', 'title' => 'Konfirmasi & Seleksi', 'desc' => 'Tunggu hasil seleksi dan lakukan pembayaran jika diterima'],
                    ];
                @endphp
                @foreach($steps as $s)
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="step-number bg-{{ $s['color'] }}-subtle text-{{ $s['color'] }} mx-auto mb-3">{{ $s['num'] }}</div>
                        <h6 class="fw-bold mb-2">{{ $s['title'] }}</h6>
                        <p class="text-muted small mb-0">{{ $s['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg d-inline-flex align-items-center gap-2 px-5">
                    <i class="ti ti-arrow-right fs-4"></i> Mulai Pendaftaran
                </a>
            </div>
        </div>
    </section>

    <!-- ═══ FASILITAS ═══ -->
    <section class="py-8">
        <div class="container">
            <div class="text-center mb-8">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 mb-3">Fasilitas</span>
                <h2 class="fw-bold display-6 mb-3">{!! $settings['landing_facility_title']->value ?? 'Fasilitas Unggulan' !!}</h2>
                <p class="text-muted mx-auto" style="max-width: 540px;">{{ $settings['landing_facility_description']->value ?? 'Nikmati berbagai fasilitas modern untuk mendukung perkuliahan Anda' }}</p>
            </div>
            <div class="row g-4">
                @forelse($facilities as $f)
                <div class="col-lg-3 col-md-6">
                    <div class="card border-1 shadow-sm h-100">
                        <div class="card-body text-center p-5">
                            <i class="ti {{ $f->kode_icon }} fs-1 text-danger mb-3 d-block"></i>
                            <h6 class="fw-bold">{{ $f->nama_fasilitas }}</h6>
                            <p class="text-muted small mb-0">{{ $f->deskripsi_fasilitas }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="ti ti-building-off text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted">Belum ada data fasilitas.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ═══ TANYA DULU (CRM LEADS FORM) ═══ -->
    <section id="tanya-dulu" class="py-8 bg-body-tertiary">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-6">
                            <div class="text-center mb-5">
                                <div class="icon-shape icon-xl bg-danger-subtle text-danger rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 72px; height: 72px;">
                                    <i class="ti ti-message-dots fs-1"></i>
                                </div>
                                <h3 class="fw-bold mb-2">Tanya Dulu, Daftar Kemudian</h3>
                                <p class="text-muted mb-0" style="max-width: 480px; margin: 0 auto;">Masih ragu? Tim kami siap menjawab pertanyaan Anda seputar pendaftaran, program studi, beasiswa, dan biaya kuliah.</p>
                            </div>

                            <form action="{{ route('crm-leads.store-public') }}" method="POST" class="row g-4 justify-content-center">
                                @csrf
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control form-control-lg" placeholder="Masukkan nama Anda" required maxlength="200">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nomor WhatsApp <span class="text-danger">*</span></label>
                                    <input type="tel" name="whatsapp" class="form-control form-control-lg" placeholder="08xxxxxxxxxx" required maxlength="50">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
                                    <textarea name="pertanyaan" class="form-control form-control-lg" rows="4" placeholder="Tulis pertanyaan Anda di sini..." required></textarea>
                                </div>
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg px-6 d-inline-flex align-items-center gap-2">
                                        <i class="ti ti-brand-whatsapp fs-4"></i> Kirim Pertanyaan
                                    </button>
                                    <p class="text-muted small mt-3 mb-0"><i class="ti ti-shield-check me-1"></i> Tim kami akan merespon via WhatsApp dalam 1x24 jam</p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ FAQ ═══ -->
    <section id="faq" class="py-8">
        <div class="container">
            <div class="text-center mb-8">
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 mb-3">FAQ</span>
                <h2 class="fw-bold display-6 mb-3">Pertanyaan Umum</h2>
                <p class="text-muted mx-auto" style="max-width: 540px;">Temukan jawaban untuk pertanyaan yang sering diajukan</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        @php
                            $faqs = [
                                ['id' => 'faq1', 'q' => 'Bagaimana cara mendaftar sebagai mahasiswa baru?', 'a' => 'Anda dapat mendaftar secara online melalui portal PMB kami. Buat akun, lengkapi data diri, pilih program studi, upload dokumen persyaratan, dan submit pendaftaran Anda.'],
                                ['id' => 'faq2', 'q' => 'Apa saja persyaratan pendaftaran?', 'a' => 'Persyaratan umum meliputi: Ijazah SMA/SMK/MA sederajat, rapor, kartu identitas, pas foto terbaru, dan dokumen pendukung lainnya sesuai program studi yang dipilih.'],
                                ['id' => 'faq3', 'q' => 'Apakah ada beasiswa yang tersedia?', 'a' => 'Ya, kami menyediakan berbagai program beasiswa seperti Beasiswa Prestasi Akademik, Beasiswa Atlet, Beasiswa Kurang Mampu (KIP Kuliah), dan beasiswa kerjasama dengan perusahaan mitra.'],
                                ['id' => 'faq4', 'q' => 'Kapan batas waktu pendaftaran?', 'a' => 'Pendaftaran dibuka dalam beberapa gelombang. Gelombang I: Januari - Maret, Gelombang II: April - Juni, Gelombang III: Juli - Agustus. Informasi detail dapat dilihat di portal PMB.'],
                            ];
                        @endphp
                        @foreach($faqs as $faq)
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $faq['id'] }}">{{ $faq['q'] }}</button>
                            </h2>
                            <div id="{{ $faq['id'] }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">{{ $faq['a'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ CTA ═══ -->
    <section class="py-8" style="background: linear-gradient(135deg, #1c252e 0%, #2d3748 100%);">
        <div class="container text-center">
            <h2 class="fw-bold display-6 text-white mb-4">Siap Memulai Perjalanan?</h2>
            <p class="text-white-50 mb-6 mx-auto" style="max-width: 540px;">Daftarkan diri Anda sekarang dan jadilah bagian dari keluarga besar kampus kami.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 d-flex align-items-center gap-2"><i class="ti ti-user-plus fs-4"></i> Daftar Sekarang</a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5 d-flex align-items-center gap-2"><i class="ti ti-login fs-4"></i> Login</a>
            </div>
        </div>
    </section>

    <!-- ═══ FOOTER ═══ -->
    <footer class="py-6" style="background: #0f172a;">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset('assets/images/brand/logo/logo-light.png') }}" width="28" alt="">
                        <span class="fw-bold text-white">eAdmisi</span>
                    </div>
                    <p class="text-white-50 small">Platform Sistem Informasi Manajemen Akademik terintegrasi untuk mendukung proses belajar mengajar yang efektif dan efisien.</p>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h6 class="fw-bold text-white mb-3">Tautan</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#tentang" class="text-white-50 text-decoration-none">Tentang</a></li>
                        <li class="mb-2"><a href="#jalur" class="text-white-50 text-decoration-none">Jalur</a></li>
                        <li class="mb-2"><a href="#alur" class="text-white-50 text-decoration-none">Alur PMB</a></li>
                        <li class="mb-2"><a href="#faq" class="text-white-50 text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6 class="fw-bold text-white mb-3">Kontak</h6>
                    <ul class="list-unstyled small text-white-50">
                        <li class="mb-2"><i class="ti ti-map-pin me-2"></i>Jl. Pendidikan No. 123, Jakarta</li>
                        <li class="mb-2"><i class="ti ti-phone me-2"></i>(021) 1234-5678</li>
                        <li class="mb-2"><i class="ti ti-mail me-2"></i>info@eadmisi.ac.id</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6 class="fw-bold text-white mb-3">Ikuti Kami</h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-light btn-icon rounded-circle" style="width: 40px; height: 40px;"><i class="ti ti-brand-facebook"></i></a>
                        <a href="#" class="btn btn-light btn-icon rounded-circle" style="width: 40px; height: 40px;"><i class="ti ti-brand-instagram"></i></a>
                        <a href="#" class="btn btn-light btn-icon rounded-circle" style="width: 40px; height: 40px;"><i class="ti ti-brand-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4 border-white-10">
            <div class="text-center">
                <p class="text-white-50 small mb-0">&copy; {{ date('Y') }} eAdmisi. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <div class="position-fixed bottom-0 end-0 m-4" style="z-index: 999;">
        <div class="dropdown">
            <button class="btn btn-light btn-icon rounded-circle d-flex align-items-center shadow" type="button" data-bs-toggle="dropdown">
                <i class="ti theme-icon-active lh-1"><i class="ti theme-icon ti-sun"></i></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li><button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light"><i class="ti theme-icon ti ti-sun"></i><span class="ms-2">Light</span></button></li>
                <li><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark"><i class="ti theme-icon ti-moon-stars"></i><span class="ms-2">Dark</span></button></li>
                <li><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto"><i class="ti theme-icon ti-circle-half-2"></i><span class="ms-2">Auto</span></button></li>
            </ul>
        </div>
    </div>

    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme.min.js') }}"></script>
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    </script>
</body>
</html>
