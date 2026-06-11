@extends('layouts.app')

@section('content')
<main class="p-6">
  <!-- Header -->
  <div class="row mb-5">
    <div class="col-12 text-center">
      <h2 class="fw-bold mb-2">Pilih Jalur Pendaftaran Anda</h2>
      <p class="text-muted mb-0">Silakan pilih jalur masuk yang sesuai. Perhatikan rentang tanggal, biaya, dan sisa kuota yang tersedia.</p>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Filter Kategori -->
  <div class="row mb-4">
    <div class="col-12 d-flex justify-content-center gap-2 flex-wrap">
      <button class="btn btn-sm rounded-pill px-3 py-2 fw-semibold btn-primary filter-btn active" data-kategori="">
        Semua Jalur
      </button>
      @foreach($kategoris as $kategori)
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-semibold btn-outline-secondary filter-btn" data-kategori="{{ $kategori->id }}">
          {{ $kategori->nama }}
        </button>
      @endforeach
    </div>
  </div>

  <!-- Card List -->
  <div class="row g-4" id="pathsContainer">
    <!-- Cards will be loaded here via JS -->
  </div>

  <!-- Empty State -->
  <div id="emptyContainer" class="text-center py-5 d-none">
    <i class="ti ti-road-off text-muted" style="font-size: 3.5rem;"></i>
    <h5 class="mt-3 text-muted">Belum ada jalur pendaftaran tersedia</h5>
    <p class="text-muted small">Silakan hubungi panitia untuk informasi lebih lanjut.</p>
  </div>

  <!-- Loading -->
  <div id="loadingContainer" class="text-center py-5 d-none">
    <div class="spinner-border text-primary" role="status" style="width: 2.5rem; height: 2.5rem;">
      <span class="visually-hidden">Loading...</span>
    </div>
    <p class="text-muted small mt-2 mb-0">Memuat data jalur pendaftaran...</p>
  </div>
</main>

<style>
  .path-card {
    border: 2px solid transparent !important;
    border-radius: 16px !important;
    transition: all 0.3s ease;
    overflow: hidden;
  }

  .path-card .icon-badge {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
  }
  .path-card .info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
  }
  .path-card .info-item .info-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
  }
  .path-card .terms-link {
    font-weight: 600;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    text-decoration: none;
    transition: all 0.2s;
  }
  .path-card .terms-link:hover {
    gap: 8px;
  }
  .popular-badge {
    position: absolute;
    top: 16px;
    right: 16px;
    z-index: 10;
  }
  .filter-btn {
    border: 2px solid #e9ecef;
    transition: all 0.2s;
  }
  .filter-btn.active {
    border-color: var(--bs-primary);
  }
</style>

<script>
  // Color scheme mapping
  const colorSchemes = {
    primary:   { bg: '#e8f0fe', icon: '#1a73e8', border: '#1a73e8' },
    success:   { bg: '#e6f4ea', icon: '#1e8e3e', border: '#1e8e3e' },
    warning:   { bg: '#fef7e0', icon: '#e37400', border: '#e37400' },
    danger:    { bg: '#fce8e6', icon: '#d93025', border: '#d93025' },
    info:      { bg: '#e8f5e9', icon: '#00897b', border: '#00897b' },
    secondary: { bg: '#f1f3f4', icon: '#5f6368', border: '#5f6368' },
    dark:      { bg: '#3c4043', icon: '#ffffff', border: '#3c4043' },
  };

  let currentPage = 1;
  let isLoading = false;
  let hasMore = true;
  let currentKategori = '';

  function getColorScheme(color) {
    return colorSchemes[color] || colorSchemes['primary'];
  }

  function renderCard(path) {
    const scheme = getColorScheme(path.color);
    const isPopular = path.quota && path.quota <= 20;

    return `
      <div class="col-12">
        <div class="card path-card shadow-lg position-relative">
          ${isPopular ? `<div class="popular-badge"><span class="badge rounded-pill px-3 py-2 fw-bold" style="background:${scheme.border};color:#fff;">POPULER</span></div>` : ''}

          <div class="card-body p-4">
            <div class="row align-items-start">
              <!-- Left: Icon + Title + Description -->
              <div class="col-md-7 col-lg-8">
                <div class="d-flex align-items-start gap-3 mb-3">
                  <div class="icon-badge flex-shrink-0" style="background:${scheme.bg}; color:${scheme.icon};">
                    <i class="ti ti-school"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold mb-1">${path.name}</h5>
                    ${path.kategori ? `<span class="badge bg-light text-dark fw-semibold mb-2">${path.kategori}</span>` : ''}
                  </div>
                </div>
                ${path.description ? `<p class="text-muted small mb-3">${path.description}</p>` : ''}
              </div>

              <!-- Right: Info Items -->
              <div class="col-md-5 col-lg-4">
                <div class=" ps-3 h-100">
                  ${path.registration_start && path.registration_end ? `
                    <div class="info-item">
                      <div class="info-icon" style="background:${scheme.bg}; color:${scheme.icon};">
                        <i class="ti ti-calendar-time"></i>
                      </div>
                      <div>
                        <small class="text-muted d-block">Periode Pendaftaran</small>
                        <span class="fw-semibold small">${path.registration_start} - ${path.registration_end}</span>
                      </div>
                    </div>
                  ` : ''}

                  <div class="info-item">
                    <div class="info-icon" style="background:${scheme.bg}; color:${scheme.icon};">
                      <i class="ti ti-users"></i>
                    </div>
                    <div>
                      <small class="text-muted d-block">Sisa Kuota</small>
                      <span class="fw-semibold small">${path.quota ? path.quota + ' Kursi' : 'Tidak Terbatas'}</span>
                    </div>
                  </div>

                  <div class="info-item">
                    <div class="info-icon" style="background:${scheme.bg}; color:${scheme.icon};">
                      <i class="ti ti-wallet"></i>
                    </div>
                    <div>
                      <small class="text-muted d-block">Biaya Formulir</small>
                      <span class="fw-bold small">${path.fee_formatted}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action -->
            <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
              <a href="/jalur-pendaftaran?detail=${path.code}" class="terms-link text-primary">
                Lihat Syarat & Ketentuan <i class="ti ti-external-link"></i>
              </a>
              <a href="/daftar-pmb/registrasi/${path.code}" class="btn btn-primary btn-sm px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="ti ti-user-plus"></i> Daftar Sekarang
              </a>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  async function loadPaths() {
    if (isLoading || !hasMore) return;
    isLoading = true;

    document.getElementById('loadingContainer').classList.remove('d-none');

    try {
      const url = `/api/registration-paths?page=${currentPage}&kategori_id=${currentKategori}`;
      const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const result = await response.json();

      document.getElementById('loadingContainer').classList.add('d-none');

      if (result.data.length === 0 && currentPage === 1) {
        document.getElementById('emptyContainer').classList.remove('d-none');
        isLoading = false;
        return;
      }

      const container = document.getElementById('pathsContainer');
      result.data.forEach(path => {
        container.insertAdjacentHTML('beforeend', renderCard(path));
      });

      hasMore = result.has_more;
      currentPage++;

      if (!hasMore) {
        document.getElementById('loadingContainer').classList.add('d-none');
      }
    } catch (error) {
      console.error('Error loading paths:', error);
      document.getElementById('loadingContainer').classList.add('d-none');
    }

    isLoading = false;
  }

  // Infinite scroll
  window.addEventListener('scroll', function() {
    if ((window.innerHeight + window.scrollY) >= (document.body.scrollHeight - 400)) {
      loadPaths();
    }
  });

  // Filter by kategori
  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active', 'btn-primary'));
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.add('btn-outline-secondary'));
      this.classList.add('active', 'btn-primary');
      this.classList.remove('btn-outline-secondary');

      currentKategori = this.dataset.kategori;
      currentPage = 1;
      hasMore = true;
      document.getElementById('pathsContainer').innerHTML = '';
      document.getElementById('emptyContainer').classList.add('d-none');
      loadPaths();
    });
  });

  // Load initial data
  loadPaths();
</script>
@endsection