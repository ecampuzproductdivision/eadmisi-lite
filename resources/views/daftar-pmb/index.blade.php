@extends('layouts.app')

@section('content')
<main class="p-6">
  <!-- Header -->
  <div class="bg-gradient-mixed p-8 py-10 rounded-3 p-lg-7 mb-6">
    <h1 class="fs-3">📋 Pilih Jalur Pendaftaran</h1>
    <p class="mb-0">Tersedia berbagai jalur pendaftaran yang dapat disesuaikan</p>
    <p>dengan latar belakang dan prestasi Anda.</p>
    <a href="{{ route('riwayat-pendaftaran.index') }}" class="btn btn-dark">
      <i class="ti ti-history me-1"></i> Lihat Riwayat
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Filter Kategori -->
  <div class="card card-lg rounded-3 mb-5">
    <div class="card-body px-4 py-3">
      <div class="d-flex align-items-center gap-3 flex-wrap">
        <span class="fw-semibold small text-muted"><i class="ti ti-filter me-1"></i>Filter:</span>
        <button class="btn btn-sm rounded-pill px-3 py-2 fw-semibold btn-primary filter-btn active" data-kategori="">
          <i class="ti ti-list me-1"></i> Semua Jalur
        </button>
        @foreach($kategoris as $kategori)
          <button class="btn btn-sm rounded-pill px-3 py-2 fw-semibold btn-outline-secondary filter-btn" data-kategori="{{ $kategori->id }}">
            <i class="ti ti-tag me-1"></i> {{ $kategori->nama }}
          </button>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Card Grid -->
  <div class="d-flex flex-column gap-3" id="pathsContainer">
    <!-- Cards will be loaded here via JS -->
  </div>

  <!-- Empty State -->
  <div id="emptyContainer" class="text-center py-8 d-none">
    <div class="card border-1 shadow-sm rounded-4">
      <div class="card-body py-8">
        <i class="ti ti-road-off text-muted" style="font-size: 4rem;"></i>
        <h4 class="fw-bold mt-4 text-muted">Belum Ada Jalur Tersedia</h4>
        <p class="text-muted small">Belum ada jalur pendaftaran yang tersedia saat ini.</p>
      </div>
    </div>
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
  .filter-btn {
    border: 2px solid #e9ecef;
    transition: all 0.2s;
  }
  .filter-btn.active {
    border-color: var(--bs-primary);
  }
</style>

<script>
  // Color scheme mapping menggunakan warna theme
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

    return `
      <div class="card card-lift border cursor-pointer">
        <div class="card-body">
          <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
              <div>
                <div class="fw-semibold d-flex flex-column gap-1">
                  ${path.name}
                  <div class="d-flex gap-1 flex-wrap align-items-center mt-1">
                    ${path.periode_label
                      ? `<span class="badge bg-info-subtle text-info fw-semibold"><i class="ti ti-calendar-event me-1"></i>${path.periode_label}</span>`
                      : ''
                    }
                    ${path.is_open
                      ? '<span class="badge bg-success-subtle text-success fw-semibold"><i class="ti ti-circle-check me-1"></i> Dibuka</span>'
                      : '<span class="badge bg-danger-subtle text-danger fw-semibold"><i class="ti ti-circle-off me-1"></i> Ditutup</span>'
                    }
                  </div>
                </div>
                <div class="text-secondary small mt-2">
                  <span class="me-3"><i class="ti ti-wallet me-1"></i>${path.fee_formatted}</span>
                  <span class="me-3"><i class="ti ti-users me-1"></i>${path.quota ? path.quota + ' Kursi' : 'Tak Terbatas'}</span>
                  ${path.registration_start && path.registration_end ? `<span class="d-inline-block mt-1"><i class="ti ti-calendar-time me-1"></i>${path.registration_start} - ${path.registration_end}</span>` : ''}
                </div>
                ${path.description ? `<p class="text-muted small mb-0 mt-2">${path.description}</p>` : ''}
              </div>
            </div>
            <div class="d-flex align-items-center gap-4">
              ${path.is_open
                ? `<a href="/daftar-pmb/registrasi/${path.code}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
                  <i class="ti ti-user-plus"></i> Daftar
                </a>`
                : `<button class="btn btn-secondary btn-sm d-inline-flex align-items-center gap-1" disabled>
                  <i class="ti ti-lock"></i> Ditutup
                </button>`
              }
              <div class="dropdown dropstart">
                <button type="button" class="btn btn-icon btn-ghost btn-sm rounded-circle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dots-vertical" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                    <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                    <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                  </svg>
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item d-flex align-items-center" href="/jalur-pendaftaran?detail=${path.code}">
                    <i class="ti ti-info-circle me-2"></i> Syarat & Ketentuan
                  </a>
                  <a class="dropdown-item d-flex align-items-center" href="/daftar-pmb/registrasi/${path.code}">
                    <i class="ti ti-user-plus me-2"></i> Daftar Sekarang
                  </a>
                </div>
              </div>
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
@php
  // Suppress undefined variable warnings for JS init
@endphp
@endsection
