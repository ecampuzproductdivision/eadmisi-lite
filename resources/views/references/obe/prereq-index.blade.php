@extends('layouts.app')

@section('content')
<main class="p-2">

  {{-- ===== HEADER ===== --}}
  <div class="card border-1 mb-4 shadow-xs">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <h3 class="mb-1 fw-bold text-dark d-flex align-items-center gap-2">
            <i class="ti ti-git-branch text-primary fs-3"></i>
            Prasyarat Mata Kuliah
            <span class="badge bg-primary-subtle text-primary border border-primary ms-1 fs-6 px-3 py-1">Kurikulum OBE</span>
          </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Kurikulum OBE</a></li>
              <li class="breadcrumb-item active">Prasyarat Mata Kuliah</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== INFO BANNER ===== --}}
  <div class="card border-1 mb-4 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-left: 4px solid #22c55e !important;">
    <div class="card-body p-4">
      <div class="d-flex align-items-start gap-3">
        <div class="p-2 bg-white rounded-3 shadow-sm">
          <i class="ti ti-git-branch text-success fs-3"></i>
        </div>
        <div>
          <h6 class="fw-bold text-dark mb-1">Pengelolaan Rantai Prasyarat Antar Mata Kuliah</h6>
          <p class="text-muted small mb-0">
            Sub-modul ini mengelola aturan prasyarat antar MK dalam setiap kurikulum. Dukung empat jenis prasyarat: <strong>PASS</strong> (wajib lulus), <strong>TAKEN</strong> (pernah diambil), <strong>COREQ</strong> (ko-requisit), dan <strong>CREDITS</strong> (SKS kumulatif). Validasi DAG (Directed Acyclic Graph) otomatis memastikan tidak ada rantai prasyarat melingkar yang mustahil diselesaikan.
          </p>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== FILTER ===== --}}
  <div class="card border-1 shadow-sm mb-4">
    <div class="card-body p-3">
      <form action="{{ route('obe.prereq.index') }}" method="GET" class="row g-3 align-items-end">
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
          <button type="submit" class="btn btn-primary"><i class="ti ti-filter me-1"></i>Filter</button>
          <a href="{{ route('obe.prereq.index') }}" class="btn btn-light border px-3" title="Reset"><i class="ti ti-refresh"></i></a>
        </div>
      </form>
    </div>
  </div>

  {{-- ===== CURRICULUM CARDS ===== --}}
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
          $mkStat    = $mkStats[$kur->kurKode] ?? null;
          $prereqSt  = $prereqStats[$kur->kurKode] ?? null;
          $totalMk   = $mkStat->total_mk ?? 0;
          $totalRules = $prereqSt->total_rules ?? 0;
          $berPreq   = $prereqSt->mk_berprasyarat ?? 0;
          $dagOk     = $dagStatus[$kur->kurKode] ?? true;
          $pct       = $totalMk > 0 ? round(($berPreq / $totalMk) * 100) : 0;
        @endphp
        <div class="col-xl-4 col-lg-6">
          <div class="card border-1 shadow-sm h-100 position-relative overflow-hidden prereq-card"
               style="border-radius: 14px; transition: box-shadow 0.2s, transform 0.2s;">
            {{-- Status bar --}}
            <div style="height: 4px; background: {{ $kur->kurIsAktif ? '#22c55e' : '#64748b' }};"></div>

            {{-- DAG validity indicator --}}
            <div class="position-absolute top-0 end-0 m-2 mt-3">
              @if($dagOk)
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 10px;">
                  <i class="ti ti-circle-check me-1"></i>DAG Valid
                </span>
              @else
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1" style="font-size: 10px;">
                  <i class="ti ti-alert-triangle me-1"></i>Siklus Terdeteksi
                </span>
              @endif
            </div>

            <div class="card-body p-4">
              <div class="mb-3">
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

              {{-- Stats indicators --}}
              <div class="row g-2 mb-3">
                <div class="col-4 text-center border-end">
                  <div class="text-muted small fw-semibold" style="font-size: 10px;">Total MK</div>
                  <div class="h5 mb-0 fw-bold text-dark">{{ $totalMk }}</div>
                </div>
                <div class="col-4 text-center border-end">
                  <div class="text-muted small fw-semibold" style="font-size: 10px;">MK Berprasyarat</div>
                  <div class="h5 mb-0 fw-bold text-primary">{{ $berPreq }}</div>
                </div>
                <div class="col-4 text-center">
                  <div class="text-muted small fw-semibold" style="font-size: 10px;">Total Aturan</div>
                  <div class="h5 mb-0 fw-bold text-success">{{ $totalRules }}</div>
                </div>
              </div>

              {{-- Coverage progress --}}
              <div class="mb-3">
                <div class="d-flex justify-content-between small text-muted mb-1">
                  <span>MK Terkonfigurasi Prasyarat</span>
                  <span class="fw-semibold">{{ $pct }}%</span>
                </div>
                <div class="progress" style="height: 6px; border-radius: 4px;">
                  <div class="progress-bar {{ $pct == 100 ? 'bg-success' : ($pct >= 50 ? 'bg-primary' : 'bg-warning') }}"
                       style="width: {{ $pct }}%; border-radius: 4px; transition: width 0.8s ease;">
                  </div>
                </div>
              </div>

              {{-- Action button --}}
              <a href="{{ route('curiculum.prereq.workspace', $kur->kurKode) }}"
                 class="btn btn-primary fw-semibold d-flex align-items-center justify-content-center gap-2">
                <i class="ti ti-git-branch fs-5"></i>
                Kelola Prasyarat Mata Kuliah
                <i class="ti ti-chevron-right fs-6 ms-auto"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
      {{ $kurikulums->links() }}
    </div>
  @endif

</main>

<style>
.prereq-card:hover {
  box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
  transform: translateY(-2px);
}
</style>
@endsection
