<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jalur Pendaftaran - PMB eAkademik</title>
    <link rel="icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" />
    <meta name="theme-color" content="#ffffff" />
    <script src="{{ asset('assets/js/vendors/color-modes.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" />
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
        .nav-pmb {
            background: rgba(28,37,46,0.95);
            backdrop-filter: blur(10px);
        }
        .hero-paths {
            background: linear-gradient(135deg, #1c252e 0%, #2d3748 50%, #1a365d 100%);
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }
        .path-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 16px;
            overflow: hidden;
        }
        .path-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .badge-kategori {
            font-size: 0.75rem;
            padding: 4px 12px;
            border-radius: 20px;
        }
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #e2e8f0;
            border-top-color: #f63a4c;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .filter-btn {
            border-radius: 20px;
            padding: 6px 18px;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .filter-btn.active {
            background-color: #f63a4c;
            color: white;
            border-color: #f63a4c;
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 3px 12px;
            border-radius: 12px;
        }
        @media (max-width: 768px) {
            .hero-paths { padding: 100px 0 50px; }
        }

        [data-bs-theme="light"] .nav-pmb {
            background: #ffffff !important;
            border-bottom: 1px solid #e9ecef;
        }
        [data-bs-theme="light"] .nav-pmb .nav-link { color: #495057 !important; }
        [data-bs-theme="light"] .nav-pmb .navbar-brand .fw-bold { color: #1c252e !important; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg nav-pmb fixed-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <img src="{{ asset('assets/images/brand/logo/logo-light.png') }}" width="32" alt="">
                <span class="fw-bold text-white">eAkademik</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPMB">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarPMB">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                    <li class="nav-item"><a class="nav-link text-white-50" href="/#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link text-white-50" href="/#program">Program</a></li>
                    <li class="nav-item"><a class="nav-link text-white-50" href="/#alur">Alur PMB</a></li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-outline-light d-flex align-items-center gap-2">
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

    <section class="hero-paths">
        <div class="container position-relative" style="z-index: 1;">
            <div class="text-center">
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 fs-6 px-3 py-2 mb-4">
                    <i class="ti ti-road me-1"></i> Jalur Pendaftaran
                </span>
                <h1 class="display-4 fw-bold text-white mb-3">Pilih Jalur Pendaftaran</h1>
                <p class="lead text-white-50 mx-auto" style="max-width: 560px;">
                    Tersedia berbagai jalur pendaftaran yang dapat disesuaikan dengan latar belakang dan prestasi Anda.
                </p>
            </div>

            <!-- Filter Kategori -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mt-6">
                <button class="btn btn-outline-light filter-btn active" data-kategori="" onclick="filterPaths(this, '')">
                    <i class="ti ti-list me-1"></i> Semua
                </button>
                @foreach($kategoris as $kategori)
                    <button class="btn btn-outline-light filter-btn" data-kategori="{{ $kategori->id }}" onclick="filterPaths(this, {{ $kategori->id }})">
                        <i class="ti ti-tag me-1"></i> {{ $kategori->nama }}
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-6">
        <div class="container">
            <div id="pathsContainer" class="row g-4">
                <!-- Akan diisi oleh JavaScript -->
            </div>

            <!-- Loading -->
            <div id="loadingContainer" class="text-center py-5" style="display: none;">
                <div class="loading-spinner"></div>
                <p class="text-muted mt-3">Memuat data...</p>
            </div>

            <!-- No More Data -->
            <div id="noMoreContainer" class="text-center py-5" style="display: none;">
                <i class="ti ti-check text-success" style="font-size: 3rem;"></i>
                <p class="text-muted mt-2">Semua data telah dimuat</p>
            </div>

            <!-- Empty State -->
            <div id="emptyContainer" class="text-center py-8" style="display: none;">
                <i class="ti ti-road-off text-muted" style="font-size: 4rem;"></i>
                <h4 class="fw-bold mt-4">Belum Ada Jalur Pendaftaran</h4>
                <p class="text-muted">Belum ada jalur pendaftaran yang tersedia saat ini.</p>
            </div>

            <!-- Error State -->
            <div id="errorContainer" class="text-center py-5" style="display: none;">
                <i class="ti ti-alert-circle text-danger" style="font-size: 3rem;"></i>
                <p class="text-danger mt-2">Gagal memuat data. Silakan coba lagi.</p>
                <button class="btn btn-primary mt-2" onclick="retryLoad()">
                    <i class="ti ti-refresh me-1"></i> Muat Ulang
                </button>
            </div>
        </div>
    </section>

    <footer class="py-6" style="background: #0f172a;">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset('assets/images/brand/logo/logo-light.png') }}" width="28" alt="">
                        <span class="fw-bold text-white">eAkademik</span>
                    </div>
                    <p class="text-white-50 small">Platform Sistem Informasi Manajemen Akademik terintegrasi.</p>
                </div>
                <div class="col-lg-8 text-end">
                    <a href="/" class="btn btn-outline-light btn-sm me-2">Beranda</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar Sekarang</a>
                </div>
            </div>
            <hr class="my-4 border-white-10">
            <div class="text-center">
                <p class="text-white-50 small mb-0">&copy; {{ date('Y') }} eAkademik. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme.min.js') }}"></script>
    <script>
        let currentPage = 1;
        let isLoading = false;
        let hasMore = true;
        let currentKategori = '';
        let isError = false;

        async function loadPaths() {
            if (isLoading || !hasMore) return;
            isLoading = true;
            isError = false;

            document.getElementById('loadingContainer').style.display = 'block';
            document.getElementById('errorContainer').style.display = 'none';

            try {
                const url = `/api/registration-paths?page=${currentPage}&kategori_id=${currentKategori}`;
                const response = await fetch(url);
                const result = await response.json();

                if (result.data.length === 0 && currentPage === 1) {
                    document.getElementById('emptyContainer').style.display = 'block';
                    document.getElementById('loadingContainer').style.display = 'none';
                    isLoading = false;
                    return;
                }

                const container = document.getElementById('pathsContainer');

                result.data.forEach(path => {
                    const card = document.createElement('div');
                    card.className = 'col-md-6 col-lg-4';
                    card.innerHTML = `
                        <div class="card path-card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <h5 class="fw-bold mb-1">${path.name}</h5>
                                    <div class="d-flex flex-wrap gap-1 align-items-center">
                                        <span class="badge bg-${path.color}-subtle text-${path.color} px-2 py-1">${path.code}</span>
                                        ${path.kategori ? `<span class="badge bg-dark-subtle text-dark px-2 py-1">${path.kategori}</span>` : ''}
                                        ${path.periode_label ? `<span class="badge bg-info-subtle text-info px-2 py-1"><i class="ti ti-calendar-event me-1"></i>${path.periode_label}</span>` : ''}
                                        ${path.is_open
                                            ? '<span class="badge bg-success-subtle text-success status-badge"><i class="ti ti-circle-check me-1"></i> Dibuka</span>'
                                            : '<span class="badge bg-danger-subtle text-danger status-badge"><i class="ti ti-circle-off me-1"></i> Ditutup</span>'
                                        }
                                    </div>
                                </div>

                                ${path.description ? `<p class="text-muted small mb-3">${path.description}</p>` : ''}

                                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2">
                                    <div>
                                        <small class="text-muted d-block">Biaya Pendaftaran</small>
                                        <span class="fw-bold fs-5">${path.fee_formatted}</span>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block">Kuota</small>
                                        <span class="fw-semibold">${path.quota || '∞'}</span>
                                    </div>
                                </div>

                                ${path.registration_start && path.registration_end ? `
                                    <div class="border-top pt-2 mt-2">
                                        <small class="text-muted d-flex align-items-center gap-1">
                                            <i class="ti ti-calendar-time"></i>
                                            ${path.registration_start} - ${path.registration_end}
                                        </small>
                                    </div>
                                ` : ''}

                                <div class="mt-3">
                                    <a href="{{ route('register') }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
                                        <i class="ti ti-user-plus"></i> Daftar via ${path.code}
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                    container.appendChild(card);
                });

                hasMore = result.has_more;
                currentPage++;

                if (!hasMore) {
                    document.getElementById('noMoreContainer').style.display = result.total > 0 ? 'block' : 'none';
                }

                document.getElementById('loadingContainer').style.display = 'none';
            } catch (error) {
                console.error('Error loading paths:', error);
                document.getElementById('loadingContainer').style.display = 'none';
                document.getElementById('errorContainer').style.display = 'block';
                isError = true;
            }

            isLoading = false;
        }

        function filterPaths(btn, kategoriId) {
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Reset state
            currentPage = 1;
            hasMore = true;
            currentKategori = kategoriId;
            document.getElementById('pathsContainer').innerHTML = '';
            document.getElementById('noMoreContainer').style.display = 'none';
            document.getElementById('emptyContainer').style.display = 'none';
            document.getElementById('errorContainer').style.display = 'none';

            loadPaths();
        }

        function retryLoad() {
            document.getElementById('errorContainer').style.display = 'none';
            loadPaths();
        }

        // Infinite scroll
        window.addEventListener('scroll', function() {
            if (isLoading || !hasMore) return;

            const scrollTop = window.scrollY || document.documentElement.scrollTop;
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;

            if (scrollTop + windowHeight >= documentHeight - 300) {
                loadPaths();
            }
        });

        // Initial load
        document.addEventListener('DOMContentLoaded', function() {
            loadPaths();
        });
    </script>
</body>
</html>
