@extends('layouts.app')

@section('content')
<main class="p-2">
  <!-- Header -->
  <div class="card border-1 mb-4 shadow-xs">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <h3 class="mb-1 fw-bold text-dark d-flex align-items-center gap-2">
            <i class="ti ti-layout-grid text-primary fs-3"></i>
            Struktur Kurikulum
            <span class="badge bg-primary-subtle text-primary border border-primary ms-1 fs-6 px-3 py-1">Kurikulum OBE</span>
          </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Kurikulum OBE</a></li>
              <li class="breadcrumb-item active">Struktur Kurikulum</li>
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
          <h6 class="fw-bold text-dark mb-1">Panduan Pengelolaan Struktur Kurikulum</h6>
          <p class="text-muted small mb-0">
            Pilih kurikulum di bawah ini untuk menyusun distribusi mata kuliah per semester. Setiap kurikulum memiliki struktur semester, kelompok MK, dan rantai prasyarat tersendiri yang harus memenuhi aturan <strong>SN-Dikti</strong> dan standar akreditasi <strong>BAN-PT/LAM</strong>.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter & Search -->
  <div class="card border-1 shadow-sm mb-4">
    <div class="card-body p-3">
      <form action="{{ route('obe.structure.index') }}" method="GET" class="row g-3 align-items-end">
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
          <a href="{{ route('obe.structure.index') }}" class="btn btn-light border px-3" title="Reset"><i class="ti ti-refresh"></i></a>
        </div>
      </form>
    </div>
  </div>

  <!-- Summary Stats -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card border-1 shadow-sm h-100" style="border-radius: 12px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background: #e3f2fd;">
            <i class="ti ti-books text-primary fs-3"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold">Total Kurikulum</div>
            <div class="h4 mb-0 fw-bold text-dark">{{ $totalKurikulum }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-1 shadow-sm h-100" style="border-radius: 12px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background: #e8f5e9;">
            <i class="ti ti-check-circle text-success fs-3"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold">Kurikulum Aktif</div>
            <div class="h4 mb-0 fw-bold text-dark">{{ $totalAktif }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-1 shadow-sm h-100" style="border-radius: 12px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background: #fff3e0;">
            <i class="ti ti-edit text-warning fs-3"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold">Dalam Penyusunan</div>
            <div class="h4 mb-0 fw-bold text-dark">{{ $totalKurikulum - $totalAktif }}</div>
          </div>
        </div>
      </div>
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
          $totalMk = $stat->total_mk ?? 0;
          $totalSks = $stat->total_sks ?? 0;
          $sksTarget = $kur->kurSksLulus ?? 0;
          $pct = $sksTarget > 0 ? min(100, round(($totalSks / $sksTarget) * 100)) : 0;
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
                  </div>
                  <h6 class="fw-bold text-dark mb-1" style="font-size: 15px;">{{ $kur->kurNama }}</h6>
                  <div class="text-muted small">{{ $kur->prodiNamaResmi ?? '-' }}</div>
                </div>
              </div>

              <!-- Stats -->
              <div class="d-flex gap-2 mb-3 flex-wrap">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size: 11px;">
                  <i class="ti ti-books me-1"></i> {{ $totalMk }} MK
                </span>
                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1" style="font-size: 11px;">
                  <i class="ti ti-stack me-1"></i> {{ $totalSks }} / {{ $sksTarget }} SKS
                </span>
              </div>

              <!-- SKS Progress Bar -->
              <div class="mb-3">
                <div class="d-flex justify-content-between small text-muted mb-1">
                  <span>Kelengkapan SKS</span>
                  <span class="fw-semibold">{{ $pct }}%</span>
                </div>
                <div class="progress" style="height: 6px; border-radius: 4px;">
                  <div class="progress-bar {{ $pct >= 100 ? 'bg-success' : ($pct >= 75 ? 'bg-primary' : 'bg-warning') }}"
                       style="width: {{ $pct }}%; border-radius: 4px;"></div>
                </div>
              </div>

              <div class="d-flex gap-3 text-muted mb-4" style="font-size: 12px;">
                <span><i class="ti ti-calendar me-1"></i> {{ $kur->kurTahunMulai }} - {{ $kur->kurTahunSelesai ?: 'Skrg' }}</span>
              </div>

              <!-- Action Button -->
              <a href="{{ route('curiculum.structure.workspace', $kur->kurKode) }}"
                 class="btn btn-primary fw-semibold d-flex align-items-center justify-content-center gap-2">
                <i class="ti ti-layout-grid fs-5"></i>
                Kelola Struktur
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
