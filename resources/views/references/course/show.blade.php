@extends('layouts.app')

@section('content')
<main class="p-4">
  <!-- Header & Breadcrumbs -->
  <div class="card border-1 mb-2">
    <div class="card-body p-4"> 
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h3 class="mb-1 fw-bold d-flex align-items-center">
            {{ $course->mk_nama }}
          </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">References</a></li>
              <li class="breadcrumb-item"><a href="{{ route('course.index') }}">Mata Kuliah</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ $course->mk_kode }}</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('course.edit', $course->id) }}" class="btn btn-dark fw-semibold px-3">
            <i class="ti ti-edit me-1"></i> Ubah
          </a>
          <a href="{{ route('course.index') }}" class="btn btn-light border fw-semibold text-dark">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
      <i class="ti ti-circle-check me-2 fs-5 align-middle"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="row g-4">
    <!-- Left Column: Details & OBE Panels -->
    <div class="col-lg-8">
      
      <!-- Card 1: Informasi Dasar -->
      <div class="card card-lg mb-6">
        <div class="card-body p-4">
          <h4 class="fw-bold mb-4 pb-2">
            <i class="ti ti-info-circle text-primary me-2"></i> Informasi Dasar & SKS
          </h4>

          <div class="row g-4 mb-4">
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-3 h-100">
                <span class="d-block text-muted small fw-semibold text-uppercase">Nama Mata Kuliah</span>
                <span class="d-block  fw-bold fs-5">{{ $course->mk_nama }} <span class="badge text-bg-secondary ms-2 fs-6 px-3 py-1">{{ $course->mk_kode }}</span></span>
                @if($course->mk_nama_asing)
                  <span class="d-block text-muted small fst-italic mt-1">{{ $course->mk_nama_asing }}</span>
                @endif
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-3 h-100">
                <span class="d-block text-muted small fw-semibold text-uppercase">Program Studi & Dosen Koordinator</span>
                <span class="d-block text-defaullt fw-bold">{{ $course->programStudi ? $course->programStudi->prodiNamaResmi : '-' }}</span>
                <span class="d-block text-secondary small mt-1">Koordinator: <strong>{{ $course->dosenKoordinator ? $course->dosenKoordinator->name : 'Belum Ditentukan' }}</strong></span>
              </div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-3">
              <div class="p-3 bg-warning-subtle rounded-3 text-center">
                <span class="d-block text-warning small fw-semibold text-uppercase">SKS Teori</span>
                <span class="d-block fw-bold text-warning fs-4">{{ $course->mk_sks_tatap_muka }}</span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="p-3 bg-info-subtle rounded-3 text-center">
                <span class="d-block text-info small fw-semibold text-uppercase">SKS Praktikum</span>
                <span class="d-block fw-bold text-info fs-4">{{ $course->mk_sks_praktikum ?: 0 }}</span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="p-3 bg-success-subtle rounded-3 text-center">
                <span class="d-block text-success small fw-semibold text-uppercase">SKS Lapangan</span>
                <span class="d-block fw-bold text-success fs-4">{{ $course->mk_sks_praktek_lapangan ?: 0 }}</span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="p-3 bg-primary-subtle rounded-3 text-center text-primary">
                <span class="d-block text-primary small fw-semibold text-uppercase">Total SKS</span>
                <span class="d-block fw-bolder text-primary fs-4">{{ $course->mk_sks_total }}</span>
              </div>
            </div>
          </div>

          <div class="row g-3 mt-3">
            <div class="col-md-4">
              <span class="text-muted small fw-semibold">Jenis / Level:</span>
              <div class="fw-bold mt-1">
                <span class="badge text-bg-primary">{{ $course->mk_jenis }}</span>
                <span class="badge text-bg-dark">{{ $course->mk_level ?: 'Dasar' }}</span>
              </div>
            </div>
            <div class="col-md-4">
              <span class="text-muted small fw-semibold">Metode / Bahasa:</span>
              <div class="fw-bold mt-1">
                <span class="badge text-bg-primary">{{ $course->mk_metode_pelaksanaan ?: 'Tatap Muka' }}</span>
                <span class="badge text-bg-dark">{{ $course->mk_bahasa_pengantar ?: 'Indonesia' }}</span>
              </div>
            </div>
            <div class="col-md-4">
              <span class="text-muted small fw-semibold">Pertemuan / Shared:</span>
              <div class="fw-bold mt-1">
                <span class="badge bg-primary">{{ $course->mk_jumlah_pertemuan ?: 16 }} Pertemuan</span>
                <span class="badge {{ $course->mk_is_shared ? 'text-bg-success' : 'text-bg-dark' }}">
                  {{ $course->mk_is_shared ? 'Shared/Lintas Prodi' : 'Internal Prodi' }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2: Panel CPMK -->
      <div class="card card-lg mb-6">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center pb-3 mb-4">
            <h4 class="fw-bold mb-0">
              <i class="ti ti-target text-success me-2"></i> Capaian Pembelajaran Mata Kuliah (CPMK)
            </h4>
            @if($kurikulums->isNotEmpty())
              <button class="btn btn-sm btn-outline-success fw-bold px-3 btn-manage-cpmk">
                <i class="ti ti-settings me-1"></i> Kelola CPMK
              </button>
            @endif
          </div>

          @if($kurikulums->isEmpty())
            <div class="alert alert-warning border-0 shadow-xs mb-0">
              <i class="ti ti-exclamation-circle me-2 fs-5 align-middle"></i>
              Kurikulum belum tersedia untuk program studi ini. Buat kurikulum terlebih dahulu untuk mengelola CPMK.
            </div>
          @else
            <div class="row g-3 mb-3 align-items-center">
              <div class="col-md-6">
                <label for="cpmkKurikulumSelect" class="form-label fw-semibold text-muted small text-uppercase mb-1">Pilih Versi Kurikulum</label>
                <select class="form-select" id="cpmkKurikulumSelect">
                  @foreach($kurikulums as $index => $k)
                    <option value="{{ $k->kurKode }}" {{ $index === 0 ? 'selected' : '' }}>
                      {{ $k->kurNama }} ({{ $k->kurKode }}) {{ $k->kurIsAktif ? '[Aktif]' : '' }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6 text-md-end pt-3">
                <span class="fw-bold text-muted small text-uppercase">Total Bobot CPMK:</span>
                <span class="fs-5 fw-bold ms-2" id="cpmkTotalWeightBadge">0%</span>
              </div>
            </div>

            <!-- CPMK Table -->
            <div class="table-responsive">
              <table class="table table-hover align-middle" id="cpmkTable">
                <thead class="table-light text-secondary">
                  <tr>
                    <th width="15%">Kode</th>
                    <th width="50%">Deskripsi CPMK</th>
                    <th width="15%" class="text-center">Ranah Bloom</th>
                    <th width="10%" class="text-end">Bobot</th>
                    <th width="10%" class="text-end">Target %</th>
                  </tr>
                </thead>
                <tbody id="cpmkTableBody">
                  <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Memuat data CPMK...</td>
                  </tr>
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      <!-- Card 3: Panel Komponen Penilaian -->
      <div class="card card-lg mb-6">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center pb-3 mb-4">
            <h4 class="fw-bold mb-0">
              <i class="ti ti-report-analytics text-warning me-2"></i> Komponen Penilaian Baku
            </h4>
            <button class="btn btn-sm btn-outline-warning fw-bold px-3 btn-manage-komponen">
              <i class="ti ti-settings me-1"></i> Kelola Komponen
            </button>
          </div>

          <div class="row g-3 mb-3 align-items-center">
            <div class="col-12 text-end">
              <span class="fw-bold text-muted small text-uppercase">Total Bobot Penilaian:</span>
              <span class="fs-5 fw-bold ms-2" id="komponenTotalWeightBadge">0%</span>
            </div>
          </div>

          <!-- Komponen Penilaian Table -->
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light text-secondary">
                <tr>
                  <th width="30%">Nama Komponen</th>
                  <th width="20%">Jenis Komponen</th>
                  <th width="35%">Target CPMK</th>
                  <th width="15%" class="text-end">Bobot (%)</th>
                </tr>
              </thead>
              <tbody id="komponenTableBody">
                <tr>
                  <td colspan="4" class="text-center py-4 text-muted">Memuat data komponen penilaian...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Completeness Indicators & Pengampu -->
    <div class="col-lg-4">
      
      <!-- Card 1: Indikator Kelengkapan OBE -->
      <div class="card card-lg mb-6">
        <div class="card-body p-4">
          <h4 class="fw-bold mb-3 ">
            <i class="ti ti-activity text-info me-2"></i> Kesiapan OBE
          </h4>
          
          <div class="text-center my-6">
            <div class="position-relative d-inline-block">
              <!-- Radial/Circular progress simulated in CSS -->
              <div class="d-flex align-items-center justify-content-center bg-info-subtle border border-info rounded-circle" style="width: 100px; height: 100px; margin: 0 auto;">
                <span class="fs-3 fw-bold text-info" id="obeReadinessPercent">0%</span>
              </div>
            </div>
            <p class="text-muted mt-2 small mb-0">Kelengkapan Master Data OBE Mata Kuliah</p>
          </div>

          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex align-items-center justify-content-between px-0 py-2 border-0">
              <div class="d-flex align-items-center">
                <i class="ti ti-circle-dot text-success me-2" id="indicatorCpmkIcon"></i>
                <span class="small fw-semibold">Parameter CPMK (100%)</span>
              </div>
              <span class="badge bg-secondary-subtle text-secondary" id="indicatorCpmkStatus">Belum Lengkap</span>
            </li>
            <li class="list-group-item d-flex align-items-center justify-content-between px-0 py-2 border-0">
              <div class="d-flex align-items-center">
                <i class="ti ti-circle-dot text-success me-2" id="indicatorKomponenIcon"></i>
                <span class="small fw-semibold">Komponen Penilaian (100%)</span>
              </div>
              <span class="badge bg-secondary-subtle text-secondary" id="indicatorKomponenStatus">Belum Lengkap</span>
            </li>
            <li class="list-group-item d-flex align-items-center justify-content-between px-0 py-2 border-0">
              <div class="d-flex align-items-center">
                <i class="ti ti-circle-dot text-success me-2" id="indicatorDosenIcon"></i>
                <span class="small fw-semibold">Dosen Pengampu / Koord</span>
              </div>
              <span class="badge bg-secondary-subtle text-secondary" id="indicatorDosenStatus">Belum Lengkap</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Card 2: Dosen Pengampu -->
      <div class="card card-lg mb-6">
        <div class="card-body p-4">
          <h4 class="fw-bold mb-3 pb-2">
            <i class="ti ti-users text-primary me-2"></i> Tim Dosen Pengampu
          </h4>

          @if($course->dosenPengampus->isEmpty())
            <div class="text-center py-4 text-muted border border-dashed rounded-3">
              <i class="ti ti-user-x d-block fs-3 mb-1"></i>
              <span style="font-size: 12px;">Belum ada tim dosen pengampu.</span>
            </div>
          @else
            <ul class="list-group list-group-flush">
              @foreach($course->dosenPengampus as $dp)
                <li class="list-group-item d-flex align-items-center px-0 py-2">
                  <div class="avatar bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                    <i class="ti ti-user fs-5"></i>
                  </div>
                  <div>
                    <span class="d-block fw-bold small">{{ $dp->dosen ? $dp->dosen->name : 'Nama Dosen' }}</span>
                    <span class="badge bg-light text-secondary fs-8 border">{{ $dp->peran ?: 'Pengampu' }}</span>
                  </div>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>

    </div>
  </div>
</main>

<!-- Modal 1: Manage CPMK -->
<div class="modal fade" id="manageCpmkModal" tabindex="-1" aria-labelledby="manageCpmkModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-success text-white py-3" style="border-radius: 16px 16px 0 0;">
        <h5 class="modal-title fw-bold" id="manageCpmkModalLabel">
          <i class="ti ti-target me-2"></i> Kelola CPMK
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="manageCpmkForm">
        @csrf
        <div class="modal-body p-4">
          <div class="alert alert-info border-0 shadow-xs mb-3 py-2 px-3 small d-flex align-items-center">
            <i class="ti ti-info-circle fs-5 me-2"></i>
            <span>Total bobot dari semua CPMK harus tepat berjumlah <strong>100%</strong> agar dapat disimpan.</span>
          </div>

          <div class="table-responsive">
            <table class="table align-middle table-sm" id="modalCpmkTable">
              <thead>
                <tr class="table-light">
                  <th width="15%">Kode *</th>
                  <th width="45%">Deskripsi *</th>
                  <th width="15%">Ranah *</th>
                  <th width="12%">Bobot *</th>
                  <th width="12%">Target % *</th>
                  <th width="8%">Aksi</th>
                </tr>
              </thead>
              <tbody id="modalCpmkTableBody">
                <!-- Dynamically populated rows -->
              </tbody>
            </table>
          </div>

          <button type="button" class="btn btn-sm btn-outline-success fw-bold rounded-pill px-3 mt-2" id="btnAddCpmkRow">
            <i class="ti ti-plus me-1"></i> Tambah Baris CPMK
          </button>

          <!-- Warning Box -->
          <div class="alert alert-danger mt-3 mb-0 d-none" id="cpmkModalWarning">
            <i class="ti ti-exclamation-circle me-2 fs-5 align-middle"></i>
            <span id="cpmkModalWarningText">Total bobot CPMK saat ini: 0% (Kurang 100%)</span>
          </div>
        </div>
        <div class="modal-footer bg-light py-3 border-top-0 d-flex justify-content-between" style="border-radius: 0 0 16px 16px;">
          <span class="text-secondary fw-semibold">Total: <span id="cpmkModalSumText">0%</span></span>
          <div>
            <button type="button" class="btn btn-secondary fw-semibold px-4 me-1" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success fw-semibold px-4" id="btnSaveCpmk" disabled>Simpan CPMK</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal 2: Manage Komponen Penilaian -->
<div class="modal fade" id="manageKomponenModal" tabindex="-1" aria-labelledby="manageKomponenModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-warning py-3" style="border-radius: 16px 16px 0 0;">
        <h5 class="modal-title fw-bold" id="manageKomponenModalLabel">
          <i class="ti ti-report-analytics me-2"></i> Kelola Komponen Penilaian Baku
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="manageKomponenForm">
        @csrf
        <div class="modal-body p-4">
          <div class="alert alert-info border-0 shadow-xs mb-3 py-2 px-3 small d-flex align-items-center">
            <i class="ti ti-info-circle fs-5 me-2 text-warning"></i>
            <span>Total bobot komponen penilaian harus tepat berjumlah <strong>100%</strong> agar dapat disimpan.</span>
          </div>

          <div class="table-responsive">
            <table class="table align-middle table-sm" id="modalKomponenTable">
              <thead>
                <tr class="table-light">
                  <th width="30%">Nama Komponen *</th>
                  <th width="20%">Jenis *</th>
                  <th width="35%">Target CPMK</th>
                  <th width="15%">Bobot (%) *</th>
                  <th width="8%">Aksi</th>
                </tr>
              </thead>
              <tbody id="modalKomponenTableBody">
                <!-- Dynamically populated rows -->
              </tbody>
            </table>
          </div>

          <button type="button" class="btn btn-sm btn-outline-warning fw-bold rounded-pill px-3 mt-2" id="btnAddKomponenRow">
            <i class="ti ti-plus me-1"></i> Tambah Komponen
          </button>

          <!-- Warning Box -->
          <div class="alert alert-danger mt-3 mb-0 d-none" id="komponenModalWarning">
            <i class="ti ti-exclamation-circle me-2 fs-5 align-middle"></i>
            <span id="komponenModalWarningText">Total bobot komponen penilaian saat ini: 0%</span>
          </div>
        </div>
        <div class="modal-footer bg-light py-3 border-top-0 d-flex justify-content-between" style="border-radius: 0 0 16px 16px;">
          <span class="text-secondary fw-semibold">Total: <span id="komponenModalSumText">0%</span></span>
          <div>
            <button type="button" class="btn btn-secondary fw-semibold px-4 me-1" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning fw-semibold px-4" id="btnSaveKomponen" disabled>Simpan Komponen</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const courseId = "{{ $course->id }}";
    const kurSelect = document.getElementById('cpmkKurikulumSelect');
    
    // Globals for storing fetched CPMKs and Komponens
    let currentCpmks = [];
    let currentKomponens = [];

    // Modals
    const manageCpmkModal = new bootstrap.Modal(document.getElementById('manageCpmkModal'));
    const manageKomponenModal = new bootstrap.Modal(document.getElementById('manageKomponenModal'));

    // Fetch and render data
    function loadData() {
        const kurKode = kurSelect ? kurSelect.value : null;
        if (!kurKode) return;

        // Load CPMK
        fetch(`/references/curiculum/${kurKode}/course/${courseId}/cpmk`)
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    currentCpmks = res.data;
                    renderCpmkTable(res.data);
                    
                    // Once CPMKs are loaded, fetch components (since we link component to CPMK)
                    return fetch(`/references/course/${courseId}/komponen-penilaian`);
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    currentKomponens = res.data;
                    renderKomponenTable(res.data);
                    calculateReadiness();
                }
            })
            .catch(err => {
                console.error("Gagal memuat data OBE:", err);
            });
    }

    // Render CPMK Table (Read Mode)
    function renderCpmkTable(cpmks) {
        const body = document.getElementById('cpmkTableBody');
        const sumBadge = document.getElementById('cpmkTotalWeightBadge');
        body.innerHTML = '';
        
        let sum = 0;

        if (cpmks.length === 0) {
            body.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted">Belum ada data CPMK untuk kurikulum ini.</td></tr>';
            sumBadge.innerText = '0%';
            sumBadge.className = 'fs-5 fw-bold text-danger ms-2';
            return;
        }

        cpmks.forEach(c => {
            const bobot = parseFloat(c.bobot_cpmk);
            sum += bobot;
            
            body.innerHTML += `
                <tr>
                    <td><strong class="text-dark">${c.kode_cpmk}</strong></td>
                    <td class="text-wrap">${c.deskripsi}</td>
                    <td class="text-center"><span class="badge bg-secondary-subtle text-secondary">${c.ranah_bloom}</span></td>
                    <td class="text-end fw-bold">${bobot}%</td>
                    <td class="text-end text-muted">${parseFloat(c.target_ketercapaian)}%</td>
                </tr>
            `;
        });

        sumBadge.innerText = sum + '%';
        if (Math.abs(sum - 100) < 0.001) {
            sumBadge.className = 'fs-5 fw-bold text-success ms-2';
        } else {
            sumBadge.className = 'fs-5 fw-bold text-danger ms-2';
        }
    }

    // Render Komponen Table (Read Mode)
    function renderKomponenTable(komponens) {
        const body = document.getElementById('komponenTableBody');
        const sumBadge = document.getElementById('komponenTotalWeightBadge');
        body.innerHTML = '';
        
        let sum = 0;

        if (komponens.length === 0) {
            body.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data komponen penilaian baku.</td></tr>';
            sumBadge.innerText = '0%';
            sumBadge.className = 'fs-5 fw-bold text-danger ms-2';
            return;
        }

        komponens.forEach(k => {
            const bobot = parseFloat(k.bobot);
            sum += bobot;
            
            const targetCpmk = k.cpmk_target ? `<span class="badge bg-success-subtle text-success">${k.cpmk_target.kode_cpmk} - ${k.cpmk_target.deskripsi.substring(0, 30)}...</span>` : '<span class="text-muted small">Semua CPMK / Umum</span>';
            
            body.innerHTML += `
                <tr>
                    <td><strong class="text-dark">${k.nama_komponen}</strong></td>
                    <td><span class="badge bg-light border">${k.jenis_komponen}</span></td>
                    <td>${targetCpmk}</td>
                    <td class="text-end fw-bold">${bobot}%</td>
                </tr>
            `;
        });

        sumBadge.innerText = sum + '%';
        if (Math.abs(sum - 100) < 0.001) {
            sumBadge.className = 'fs-5 fw-bold text-success ms-2';
        } else {
            sumBadge.className = 'fs-5 fw-bold text-danger ms-2';
        }
    }

    // Calculate OBE Readiness Indicators
    function calculateReadiness() {
        const cpmkSum = currentCpmks.reduce((acc, c) => acc + parseFloat(c.bobot_cpmk), 0);
        const komponenSum = currentKomponens.reduce((acc, k) => acc + parseFloat(k.bobot), 0);
        const hasDosen = parseInt("{{ $course->dosenPengampus->count() }}") > 0 || "{{ $course->mk_dosen_koord_id }}" !== "";

        const isCpmkValid = currentCpmks.length > 0 && Math.abs(cpmkSum - 100) < 0.001;
        const isKomponenValid = currentKomponens.length > 0 && Math.abs(komponenSum - 100) < 0.001;

        // 1. CPMK Status
        const cpmkStatusEl = document.getElementById('indicatorCpmkStatus');
        const cpmkIcon = document.getElementById('indicatorCpmkIcon');
        if (isCpmkValid) {
            cpmkStatusEl.className = 'badge bg-success-subtle text-success';
            cpmkStatusEl.innerText = 'Lengkap (100%)';
            cpmkIcon.className = 'ti ti-circle-check-filled text-success me-2';
        } else {
            cpmkStatusEl.className = 'badge bg-danger-subtle text-danger';
            cpmkStatusEl.innerText = `Belum Lengkap (${cpmkSum}%)`;
            cpmkIcon.className = 'ti ti-circle-x-filled text-danger me-2';
        }

        // 2. Komponen Status
        const komponenStatusEl = document.getElementById('indicatorKomponenStatus');
        const komponenIcon = document.getElementById('indicatorKomponenIcon');
        if (isKomponenValid) {
            komponenStatusEl.className = 'badge bg-success-subtle text-success';
            komponenStatusEl.innerText = 'Lengkap (100%)';
            komponenIcon.className = 'ti ti-circle-check-filled text-success me-2';
        } else {
            komponenStatusEl.className = 'badge bg-danger-subtle text-danger';
            komponenStatusEl.innerText = `Belum Lengkap (${komponenSum}%)`;
            komponenIcon.className = 'ti ti-circle-x-filled text-danger me-2';
        }

        // 3. Dosen Status
        const dosenStatusEl = document.getElementById('indicatorDosenStatus');
        const dosenIcon = document.getElementById('indicatorDosenIcon');
        if (hasDosen) {
            dosenStatusEl.className = 'badge bg-success-subtle text-success';
            dosenStatusEl.innerText = 'Ada';
            dosenIcon.className = 'ti ti-circle-check-filled text-success me-2';
        } else {
            dosenStatusEl.className = 'badge bg-warning-subtle text-warning';
            dosenStatusEl.innerText = 'Belum Ada';
            dosenIcon.className = 'ti ti-circle-dot-filled text-warning me-2';
        }

        // Calculate score
        let score = 0;
        if (isCpmkValid) score += 40;
        if (isKomponenValid) score += 40;
        if (hasDosen) score += 20;

        document.getElementById('obeReadinessPercent').innerText = score + '%';
    }

    if (kurSelect) {
        kurSelect.addEventListener('change', loadData);
        loadData(); // Initial load
    }

    // --- CPMK Modal Logic ---
    const btnManageCpmk = document.querySelector('.btn-manage-cpmk');
    const modalCpmkTableBody = document.getElementById('modalCpmkTableBody');
    const btnAddCpmkRow = document.getElementById('btnAddCpmkRow');
    const cpmkModalWarning = document.getElementById('cpmkModalWarning');
    const cpmkModalWarningText = document.getElementById('cpmkModalWarningText');
    const cpmkModalSumText = document.getElementById('cpmkModalSumText');
    const btnSaveCpmk = document.getElementById('btnSaveCpmk');

    if (btnManageCpmk) {
        btnManageCpmk.addEventListener('click', () => {
            modalCpmkTableBody.innerHTML = '';
            
            if (currentCpmks.length === 0) {
                // Add initial empty row
                addCpmkRow();
            } else {
                currentCpmks.forEach(c => addCpmkRow(c));
            }
            
            validateCpmkSum();
            manageCpmkModal.show();
        });
    }

    function addCpmkRow(data = null) {
        const index = modalCpmkTableBody.children.length;
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>
                <input type="hidden" name="cpmks[${index}][id]" value="${data ? data.id : ''}">
                <input type="text" class="form-control form-control-sm font-monospace" name="cpmks[${index}][kode_cpmk]" value="${data ? data.kode_cpmk : 'CPMK-' + (index + 1)}" required>
            </td>
            <td>
                <textarea class="form-control form-control-sm" name="cpmks[${index}][deskripsi]" rows="1" required>${data ? data.deskripsi : ''}</textarea>
            </td>
            <td>
                <select class="form-select form-select-sm" name="cpmks[${index}][ranah_bloom]" required>
                    <option value="C1" ${data && data.ranah_bloom === 'C1' ? 'selected' : ''}>C1</option>
                    <option value="C2" ${data && data.ranah_bloom === 'C2' ? 'selected' : ''}>C2</option>
                    <option value="C3" ${data && data.ranah_bloom === 'C3' ? 'selected' : ''}>C3 (Default)</option>
                    <option value="C4" ${data && data.ranah_bloom === 'C4' ? 'selected' : ''}>C4</option>
                    <option value="C5" ${data && data.ranah_bloom === 'C5' ? 'selected' : ''}>C5</option>
                    <option value="C6" ${data && data.ranah_bloom === 'C6' ? 'selected' : ''}>C6</option>
                </select>
            </td>
            <td>
                <input type="number" step="0.1" class="form-control form-control-sm text-end input-cpmk-weight" name="cpmks[${index}][bobot_cpmk]" value="${data ? data.bobot_cpmk : ''}" placeholder="0" required>
            </td>
            <td>
                <input type="number" step="1" class="form-control form-control-sm text-end" name="cpmks[${index}][target_ketercapaian]" value="${data ? data.target_ketercapaian : '75'}" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-xs btn-outline-danger btn-delete-row"><i class="ti ti-trash"></i></button>
            </td>
        `;
        
        modalCpmkTableBody.appendChild(row);

        // Bind events for weight input
        row.querySelector('.input-cpmk-weight').addEventListener('input', validateCpmkSum);
        
        row.querySelector('.btn-delete-row').addEventListener('click', () => {
            row.remove();
            reindexRows(modalCpmkTableBody, 'cpmks');
            validateCpmkSum();
        });
    }

    btnAddCpmkRow.addEventListener('click', () => addCpmkRow());

    function validateCpmkSum() {
        const weights = document.querySelectorAll('.input-cpmk-weight');
        let sum = 0;
        weights.forEach(w => {
            sum += parseFloat(w.value) || 0;
        });

        cpmkModalSumText.innerText = sum + '%';
        
        if (Math.abs(sum - 100) < 0.001) {
            cpmkModalWarning.classList.add('d-none');
            btnSaveCpmk.removeAttribute('disabled');
            cpmkModalSumText.className = 'text-success fw-bold';
        } else {
            cpmkModalWarning.classList.remove('d-none');
            cpmkModalWarningText.innerText = `Total bobot CPMK saat ini: ${sum}% (Harus tepat 100%)`;
            btnSaveCpmk.setAttribute('disabled', 'disabled');
            cpmkModalSumText.className = 'text-danger fw-bold';
        }
    }

    document.getElementById('manageCpmkForm').addEventListener('submit', (e) => {
        e.preventDefault();
        const kurKode = kurSelect.value;
        const form = document.getElementById('manageCpmkForm');
        
        // Serialise
        const fd = new FormData(form);
        const data = { cpmks: [] };
        
        fd.forEach((value, key) => {
            const matches = key.match(/cpmks\[(\d+)\]\[(\w+)\]/);
            if (matches) {
                const index = matches[1];
                const field = matches[2];
                if (!data.cpmks[index]) data.cpmks[index] = {};
                data.cpmks[index][field] = value;
            }
        });

        // Filter out empty indices
        data.cpmks = data.cpmks.filter(x => x !== null);

        fetch(`/references/curiculum/${kurKode}/course/${courseId}/cpmk/sync`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                manageCpmkModal.hide();
                loadData();
            } else {
                alert(res.message || 'Gagal menyimpan CPMK.');
            }
        })
        .catch(() => alert('Terjadi kesalahan koneksi.'));
    });

    // --- Komponen Modal Logic ---
    const btnManageKomponen = document.querySelector('.btn-manage-komponen');
    const modalKomponenTableBody = document.getElementById('modalKomponenTableBody');
    const btnAddKomponenRow = document.getElementById('btnAddKomponenRow');
    const komponenModalWarning = document.getElementById('komponenModalWarning');
    const komponenModalWarningText = document.getElementById('komponenModalWarningText');
    const komponenModalSumText = document.getElementById('komponenModalSumText');
    const btnSaveKomponen = document.getElementById('btnSaveKomponen');

    if (btnManageKomponen) {
        btnManageKomponen.addEventListener('click', () => {
            modalKomponenTableBody.innerHTML = '';
            
            if (currentKomponens.length === 0) {
                addKomponenRow();
            } else {
                currentKomponens.forEach(k => addKomponenRow(k));
            }
            
            validateKomponenSum();
            manageKomponenModal.show();
        });
    }

    function addKomponenRow(data = null) {
        const index = modalKomponenTableBody.children.length;
        const row = document.createElement('tr');
        
        let cpmkOptions = '<option value="">-- Pilih Target CPMK (Umum) --</option>';
        currentCpmks.forEach(c => {
            cpmkOptions += `<option value="${c.id}" ${data && data.cpmk_target_id == c.id ? 'selected' : ''}>${c.kode_cpmk} - ${c.deskripsi.substring(0, 30)}...</option>`;
        });

        row.innerHTML = `
            <td>
                <input type="hidden" name="komponens[${index}][id]" value="${data ? data.id : ''}">
                <input type="text" class="form-control form-control-sm" name="komponens[${index}][nama_komponen]" value="${data ? data.nama_komponen : ''}" placeholder="Tugas / UTS / UAS" required>
            </td>
            <td>
                <select class="form-select form-select-sm" name="komponens[${index}][jenis_komponen]" required>
                    <option value="Formatif" ${data && data.jenis_komponen === 'Formatif' ? 'selected' : ''}>Formatif</option>
                    <option value="Sumatif" ${data && data.jenis_komponen === 'Sumatif' ? 'selected' : ''}>Sumatif</option>
                    <option value="Portofolio" ${data && data.jenis_komponen === 'Portofolio' ? 'selected' : ''}>Portofolio</option>
                </select>
            </td>
            <td>
                <select class="form-select form-select-sm" name="komponens[${index}][cpmk_target_id]">
                    ${cpmkOptions}
                </select>
            </td>
            <td>
                <input type="number" step="0.1" class="form-control form-control-sm text-end input-komponen-weight" name="komponens[${index}][bobot]" value="${data ? data.bobot : ''}" placeholder="0" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-xs btn-outline-danger btn-delete-row"><i class="ti ti-trash"></i></button>
            </td>
        `;
        
        modalKomponenTableBody.appendChild(row);

        row.querySelector('.input-komponen-weight').addEventListener('input', validateKomponenSum);
        
        row.querySelector('.btn-delete-row').addEventListener('click', () => {
            row.remove();
            reindexRows(modalKomponenTableBody, 'komponens');
            validateKomponenSum();
        });
    }

    btnAddKomponenRow.addEventListener('click', () => addKomponenRow());

    function validateKomponenSum() {
        const weights = document.querySelectorAll('.input-komponen-weight');
        let sum = 0;
        weights.forEach(w => {
            sum += parseFloat(w.value) || 0;
        });

        komponenModalSumText.innerText = sum + '%';
        
        if (Math.abs(sum - 100) < 0.001) {
            komponenModalWarning.classList.add('d-none');
            btnSaveKomponen.removeAttribute('disabled');
            komponenModalSumText.className = 'text-success fw-bold';
        } else {
            komponenModalWarning.classList.remove('d-none');
            komponenModalWarningText.innerText = `Total bobot komponen saat ini: ${sum}% (Harus tepat 100%)`;
            btnSaveKomponen.setAttribute('disabled', 'disabled');
            komponenModalSumText.className = 'text-danger fw-bold';
        }
    }

    document.getElementById('manageKomponenForm').addEventListener('submit', (e) => {
        e.preventDefault();
        const form = document.getElementById('manageKomponenForm');
        const fd = new FormData(form);
        const data = { komponens: [] };
        
        fd.forEach((value, key) => {
            const matches = key.match(/komponens\[(\d+)\]\[(\w+)\]/);
            if (matches) {
                const index = matches[1];
                const field = matches[2];
                if (!data.komponens[index]) data.komponens[index] = {};
                data.komponens[index][field] = value;
            }
        });

        data.komponens = data.komponens.filter(x => x !== null);

        fetch(`/references/course/${courseId}/komponen-penilaian/sync`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                manageKomponenModal.hide();
                loadData();
            } else {
                alert(res.message || 'Gagal menyimpan komponen penilaian.');
            }
        })
        .catch(() => alert('Terjadi kesalahan koneksi.'));
    });

    // Utility: Helper to re-index input names when a row is deleted
    function reindexRows(tbody, arrayName) {
        Array.from(tbody.children).forEach((row, newIndex) => {
            // Update hidden ID input name
            const idInput = row.querySelector(`input[name^="${arrayName}"][name$="[id]"]`);
            if (idInput) idInput.name = `${arrayName}[${newIndex}][id]`;

            // Update main inputs
            row.querySelectorAll('input, select, textarea').forEach(el => {
                if (el.name && el.name !== '_token') {
                    const match = el.name.match(new RegExp(`${arrayName}\\[\\d+\\]\\[(\\w+)\\]`));
                    if (match) {
                        el.name = `${arrayName}[${newIndex}][${match[1]}]`;
                    }
                }
            });
        });
    }
});
</script>
@endsection
