@extends('layouts.app')

@section('content')
<main class="p-2">
  <!-- Header -->
  <div class="card border-1 mb-4 shadow-xs">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <h3 class="mb-1 fw-bold text-dark d-flex align-items-center gap-2">
            <i class="ti ti-table text-primary fs-3"></i>
            Matriks CPL–MK
            <span class="badge bg-primary-subtle text-primary border border-primary ms-1 fs-6 px-3 py-1">Kurikulum OBE</span>
          </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Kurikulum OBE</a></li>
              <li class="breadcrumb-item active">Matriks CPL–MK</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <!-- Info Banner -->
  <div class="card border-1 mb-4 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #f5f0ff 0%, #ede8f5 100%); border-left: 4px solid #6f42c1 !important;">
    <div class="card-body p-4">
      <div class="d-flex align-items-start gap-3">
        <div class="p-2 bg-white rounded-3 shadow-sm text-purple">
          <i class="ti ti-table text-primary fs-3"></i>
        </div>
        <div>
          <h6 class="fw-bold text-dark mb-1">Pemetaan Matriks Capaian Pembelajaran Lulusan (CPL) ke Mata Kuliah (MK)</h6>
          <p class="text-muted small mb-0">
            Matriks CPL-MK menghubungkan profil kompetensi lulusan program studi dengan konten mata kuliah. Pilih kurikulum di bawah ini untuk mengelola matriks, menetapkan tingkat kontribusi (Tinggi, Sedang, Rendah) atau bobot numerik, serta menghubungkan pemetaan ke CPMK spesifik.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter & Search -->
  <div class="card border-1 shadow-sm mb-4">
    <div class="card-body p-3">
      <form action="{{ route('obe.matrix.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
            <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama atau kode kurikulum..." value="{{ request('search') }}">
          </div>
        </div>
        <div class="col-md-4">
          <select name="prodi" class="form-select">
            <option value="">-- Semua Program Studi --</option>
            @foreach($prodiList as $prodi)
              <option value="{{ $prodi->prodiKode }}" {{ request('prodi') == $prodi->prodiKode ? 'selected' : '' }}>
                {{ $prodi->prodiNamaResmi }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
          <button type="submit" class="btn btn-primary"><i class="ti ti-filter me-1"></i> Filter</button>
          <a href="{{ route('obe.matrix.index') }}" class="btn btn-light border px-3" title="Reset"><i class="ti ti-refresh"></i></a>
        </div>
      </form>
    </div>
  </div>

  <!-- Curriculum Cards -->
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
          $stat = $stats[$kur->kurKode] ?? null;
          $totalCpl = $stat['total_cpl'] ?? 0;
          $totalMk = $stat['total_mk'] ?? 0;
          $mappedCpl = $stat['mapped_cpl'] ?? 0;
          $mappedMk = $stat['mapped_mk'] ?? 0;
          $density = $stat['density'] ?? 0;

          // coverage percentages
          $cplPct = $totalCpl > 0 ? round(($mappedCpl / $totalCpl) * 100) : 0;
          $mkPct = $totalMk > 0 ? round(($mappedMk / $totalMk) * 100) : 0;
        @endphp
        <div class="col-xl-4 col-lg-6">
          <div class="card border-1 shadow-sm h-100 position-relative overflow-hidden"
               style="border-radius: 14px; transition: box-shadow 0.2s, transform 0.2s;"
               onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.12)';this.style.transform='translateY(-2px)'"
               onmouseout="this.style.boxShadow='';this.style.transform=''">
            <!-- Status Color Bar -->
            <div style="height: 4px; background: {{ $kur->kurIsAktif ? '#198754' : '#6c757d' }};"></div>
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="font-monospace fw-bold text-dark" style="font-size: 13px;">{{ $kur->kurKode }}</span>
                    @if($kur->kurIsAktif)
                      <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 10px;">AKTIF</span>
                    @else
                      <span class="badge bg-secondary-subtle text-secondary border px-2 py-1" style="font-size: 10px;">DRAFT</span>
                    @endif
                    <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 10px;">{{ $kur->skema_kontribusi_cpl }}</span>
                  </div>
                  <h6 class="fw-bold text-dark mb-1" style="font-size: 15px;">{{ $kur->kurNama }}</h6>
                  <div class="text-muted small">{{ $kur->prodiNamaResmi ?? '-' }}</div>
                </div>
              </div>

              <!-- Stats Indicators -->
              <div class="row g-2 my-3">
                <div class="col-4 text-center border-end">
                  <div class="text-muted small fw-semibold" style="font-size: 10px;">CPL Tercover</div>
                  <div class="h5 mb-0 fw-bold text-dark">{{ $mappedCpl }}<span class="text-muted small fs-6">/{{ $totalCpl }}</span></div>
                </div>
                <div class="col-4 text-center border-end">
                  <div class="text-muted small fw-semibold" style="font-size: 10px;">MK Terpetakan</div>
                  <div class="h5 mb-0 fw-bold text-dark">{{ $mappedMk }}<span class="text-muted small fs-6">/{{ $totalMk }}</span></div>
                </div>
                <div class="col-4 text-center">
                  <div class="text-muted small fw-semibold" style="font-size: 10px;">Kepadatan</div>
                  <div class="h5 mb-0 fw-bold text-dark">{{ $density }}%</div>
                </div>
              </div>

              <!-- CPL Coverage Progress Bar -->
              <div class="mb-3">
                <div class="d-flex justify-content-between small text-muted mb-1">
                  <span>Coverage Kompetensi CPL</span>
                  <span class="fw-semibold">{{ $cplPct }}%</span>
                </div>
                <div class="progress" style="height: 6px; border-radius: 4px;">
                  <div class="progress-bar {{ $cplPct == 100 ? 'bg-success' : ($cplPct >= 75 ? 'bg-primary' : 'bg-warning') }}"
                       style="width: {{ $cplPct }}%; border-radius: 4px;"></div>
                </div>
              </div>

              <!-- Action Button -->
              <a href="{{ route('curiculum.matrix.workspace', $kur->kurKode) }}"
                 class="btn btn-primary fw-semibold d-flex align-items-center justify-content-center gap-2">
                <i class="ti ti-table fs-5"></i>
                Kelola Matriks CPL–MK
                <i class="ti ti-chevron-right fs-6 ms-auto"></i>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    @if($kurikulums->hasPages())
      <div class="d-flex justify-content-center mt-4">
        {{ $kurikulums->withQueryString()->links() }}
      </div>
    @endif
  @endif
</main>
@endsection
