@extends('layouts.app')

@section('content')
<main class="p-2">
  <!-- Header Card -->
  <div class="card border-1 mb-4 shadow-xs">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <h3 class="mb-1 fw-bold text-dark d-flex align-items-center">
            <i class="ti ti-award me-2 text-primary"></i>
            CPL Program Studi
            <span class="badge bg-primary-subtle text-primary border border-primary ms-2 fs-6 px-3 py-1">
              Kurikulum OBE
            </span>
          </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Kurikulum OBE</a></li>
              <li class="breadcrumb-item active" aria-current="page">CPL Program Studi</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <!-- Info Banner -->
  <div class="card border-1 mb-4 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #f0f7ff 0%, #e8f0fe 100%); border-left: 4px solid #0d6efd !important;">
    <div class="card-body p-4">
      <div class="d-flex align-items-start gap-3">
        <div class="p-2 bg-white rounded-3 shadow-sm">
          <i class="ti ti-info-circle text-primary fs-3"></i>
        </div>
        <div>
          <h6 class="fw-bold text-dark mb-1">Panduan Pengelolaan CPL Program Studi</h6>
          <p class="text-muted small mb-0">
            Pilih kurikulum di bawah ini untuk mengelola Capaian Pembelajaran Lulusan (CPL). Setiap kurikulum memiliki daftar CPL tersendiri yang dapat dipetakan ke Mata Kuliah dan Profil Lulusan sesuai standar <strong>SN-Dikti (Permendikbud No. 3/2020)</strong>.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter & Search -->
  <div class="card border-1 shadow-sm mb-4">
    <div class="card-body p-3">
      <form action="{{ route('obe.cpl.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-5">
          <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
            <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama atau kode kurikulum..." value="{{ request('search') }}">
          </div>
        </div>
        <div class="col-md-3">
          <select name="prodi" class="form-select">
            <option value="">-- Semua Program Studi --</option>
            @foreach($prodiList as $prodi)
              <option value="{{ $prodi->prodiKode }}" {{ request('prodi') == $prodi->prodiKode ? 'selected' : '' }}>
                {{ $prodi->prodiNamaResmi }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <select name="status" class="form-select">
            <option value="">-- Semua Status --</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Draft</option>
          </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
          <button type="submit" class="btn btn-primary"><i class="ti ti-filter me-1"></i> Filter</button>
          <a href="{{ route('obe.cpl.index') }}" class="btn btn-light border px-3" title="Reset"><i class="ti ti-refresh"></i></a>
        </div>
      </form>
    </div>
  </div>

  <!-- Summary Stats -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card border-1 shadow-sm h-100" style="border-radius: 12px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background: #e8f5e9;">
            <i class="ti ti-book text-success fs-3"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold">Total Kurikulum</div>
            <div class="h4 mb-0 fw-bold text-dark">{{ $kurikulums->total() }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-1 shadow-sm h-100" style="border-radius: 12px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background: #e3f2fd;">
            <i class="ti ti-check-circle text-primary fs-3"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold">Kurikulum Aktif</div>
            <div class="h4 mb-0 fw-bold text-dark">{{ $totalAktif }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-1 shadow-sm h-100" style="border-radius: 12px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background: #fff3e0;">
            <i class="ti ti-award text-warning fs-3"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold">Kurikulum Sudah CPL</div>
            <div class="h4 mb-0 fw-bold text-dark">{{ $totalDenganCpl }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-1 shadow-sm h-100" style="border-radius: 12px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background: #fce4ec;">
            <i class="ti ti-alert-circle text-danger fs-3"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold">Belum Ada CPL</div>
            <div class="h4 mb-0 fw-bold text-dark">{{ $totalTanpaCpl }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Kurikulum Cards Grid -->
  @if($kurikulums->isEmpty())
    <div class="text-center py-5 text-muted">
      <i class="ti ti-folder-open fs-1 d-block mb-2"></i>
      <span class="fw-semibold d-block">Tidak Ada Data Kurikulum</span>
      <p class="small">Tidak ada kurikulum yang sesuai filter pencarian Anda.</p>
    </div>
  @else
    <div class="row g-3">
      @foreach($kurikulums as $kur)
        @php
          $hasCpl = isset($cplCounts[$kur->kurKode]) && $cplCounts[$kur->kurKode] > 0;
          $cplCount = $cplCounts[$kur->kurKode] ?? 0;
          $hasMkMapping = isset($mkMappingCounts[$kur->kurKode]) && $mkMappingCounts[$kur->kurKode] > 0;
        @endphp
        <div class="col-xl-4 col-lg-6">
          <div class="card border-1 shadow-sm h-100 position-relative overflow-hidden" style="border-radius: 14px; transition: box-shadow 0.2s, transform 0.2s;" onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.12)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
            <!-- Status Color Bar -->
            <div style="height: 4px; background: {{ $kur->kurIsAktif ? '#198754' : '#6c757d' }};"></div>
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="font-monospace fw-bold text-dark" style="font-size: 13px;">{{ $kur->kurKode }}</span>
                    @if($kur->kurIsAktif)
                      <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 10px;">AKTIF</span>
                    @else
                      <span class="badge bg-secondary-subtle text-secondary border px-2 py-1" style="font-size: 10px;">DRAFT</span>
                    @endif
                  </div>
                  <h6 class="fw-bold text-dark mb-1" style="font-size: 15px;">{{ $kur->kurNama }}</h6>
                  <div class="text-muted small">{{ $kur->prodiNamaResmi ?? '-' }}</div>
                </div>
              </div>

              <!-- CPL Status Summary -->
              <div class="d-flex gap-2 mb-3 flex-wrap">
                @if($hasCpl)
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size: 11px;">
                    <i class="ti ti-award me-1"></i> {{ $cplCount }} CPL
                  </span>
                @else
                  <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1" style="font-size: 11px;">
                    <i class="ti ti-alert-circle me-1"></i> Belum Ada CPL
                  </span>
                @endif

                @if($hasMkMapping)
                  <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 11px;">
                    <i class="ti ti-check me-1"></i> MK Terpetakan
                  </span>
                @else
                  <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1" style="font-size: 11px;">
                    <i class="ti ti-x me-1"></i> Belum Dipetakan
                  </span>
                @endif
              </div>

              <!-- Meta Info -->
              <div class="d-flex gap-3 text-muted mb-4" style="font-size: 12px;">
                <span><i class="ti ti-calendar me-1"></i> {{ $kur->kurTahunMulai }} - {{ $kur->kurTahunSelesai ?: 'Skrg' }}</span>
                <span><i class="ti ti-book me-1"></i> {{ $kur->kurSksLulus }} SKS</span>
              </div>

              <!-- Action Button -->
              <a href="{{ route('curiculum.cpl.index', $kur->kurKode) }}" 
                 class="btn btn-primary fw-semibold d-flex align-items-center justify-content-center gap-2">
                <i class="ti ti-settings fs-5"></i>
                Kelola CPL
                <i class="ti ti-chevron-right fs-6 ms-auto"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Pagination -->
    @if($kurikulums->hasPages())
      <div class="d-flex justify-content-center mt-4">
        {{ $kurikulums->withQueryString()->links() }}
      </div>
    @endif
  @endif

</main>
@endsection
