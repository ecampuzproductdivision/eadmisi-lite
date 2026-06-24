@extends('layouts.app')

@section('content')
<main class="p-2">
  <!-- Header Card -->
  <div class="card border-1 mb-4 shadow-xs">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <h3 class="mb-1 fw-bold text-dark d-flex align-items-center">
            <i class="ti ti-layout-grid me-2 text-primary"></i>
            Kelengkapan CPMK Kurikulum
          </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('obe.cpmk.index') }}">CPMK per Mata Kuliah</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ $kurikulum->kurNama }}</li>
            </ol>
          </nav>
        </div>
      </div>
      <div class="d-flex gap-3 text-muted small mt-2">
        <span><i class="ti ti-calendar me-1"></i> Tahun Mulai: <strong>{{ $kurikulum->kurTahunMulai }}</strong></span>
        <span><i class="ti ti-school me-1"></i> Prodi: <strong>{{ $kurikulum->programStudi->prodiNamaResmi ?? '-' }}</strong></span>
        <span><i class="ti ti-tag me-1"></i> Status: 
          @if($kurikulum->kurIsAktif)
            <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif (Read-Only)</span>
          @else
            <span class="badge bg-secondary-subtle text-secondary border">Draft</span>
          @endif
        </span>
      </div>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card border-1 shadow-sm h-100" style="border-radius: 12px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background: #e8f5e9;">
            <i class="ti ti-book text-success fs-3"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold">Total Mata Kuliah</div>
            <div class="h4 mb-0 fw-bold text-dark">{{ $courses->count() }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-1 shadow-sm h-100" style="border-radius: 12px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background: #e3f2fd;">
            <i class="ti ti-circle-check text-primary fs-3"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold">Lengkap</div>
            <div class="h4 mb-0 fw-bold text-dark">{{ $totalLengkap }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-1 shadow-sm h-100" style="border-radius: 12px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background: #fff3e0;">
            <i class="ti ti-alert-triangle text-warning fs-3"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold">Perlu Perbaikan</div>
            <div class="h4 mb-0 fw-bold text-dark">{{ $totalPerbaikan }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-1 shadow-sm h-100" style="border-radius: 12px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background: #fce4ec;">
            <i class="ti ti-x text-danger fs-3"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold">Belum Ada CPMK</div>
            <div class="h4 mb-0 fw-bold text-dark">{{ $totalBelumAda }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Course Table Card -->
  <div class="card border-1 shadow-sm">
    <div class="card-header bg-white py-3 border-0">
      <h6 class="fw-bold text-dark mb-0"><i class="ti ti-list me-1 text-primary"></i> Daftar Mata Kuliah & Status Kelengkapan CPMK</h6>
    </div>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" style="border-collapse: separate; border-spacing: 0;">
        <thead class="table-light">
          <tr>
            <th class="ps-4 py-3" style="width: 50px;">No</th>
            <th class="py-3">Mata Kuliah</th>
            <th class="py-3">SKS</th>
            <th class="py-3">Semester</th>
            <th class="py-3">Jumlah CPMK</th>
            <th class="py-3">Total Bobot (%)</th>
            <th class="py-3">CPL Terpetakan</th>
            <th class="py-3">Punya Komponen?</th>
            <th class="py-3">Status Kelengkapan</th>
            <th class="pe-4 py-3 text-end" style="width: 160px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($courses as $idx => $kmk)
            @php
              $c = $kmk->mataKuliah;
              $itemStats = $stats[$kmk->id] ?? [
                'cpmk_count' => 0,
                'total_weight' => 0,
                'cpl_count' => 0,
                'has_assessment' => false,
                'status' => 'Belum Ada'
              ];
            @endphp
            <tr>
              <td class="ps-4 font-monospace">{{ $idx + 1 }}</td>
              <td>
                <div class="fw-bold text-dark">{{ $c->mk_nama ?? '-' }}</div>
                <div class="d-flex align-items-center gap-2 small text-muted mt-1">
                  <span class="font-monospace text-primary fw-semibold">{{ $c->mk_kode ?? '-' }}</span>
                  <span>•</span>
                  <span class="badge" style="background-color: {{ $kmk->kelompokMk->warna_ui ?? '#6c757d' }}20; color: {{ $kmk->kelompokMk->warna_ui ?? '#6c757d' }}; font-size: 10px;">
                    {{ $kmk->kelompokMk->nama_kelompok ?? 'Kelompok MK' }}
                  </span>
                </div>
              </td>
              <td>{{ $kmk->sks_override ?? ($c->mk_sks_total ?? 0) }} SKS</td>
              <td>Semester {{ $kmk->semester_anjuran }}</td>
              <td class="fw-semibold">{{ $itemStats['cpmk_count'] }}</td>
              <td>
                @if($itemStats['cpmk_count'] > 0)
                  @php
                    $isWeightOk = abs($itemStats['total_weight'] - 100.0) < 0.01;
                  @endphp
                  <span class="fw-bold {{ $isWeightOk ? 'text-success' : 'text-danger' }}">
                    {{ number_format($itemStats['total_weight'], 0) }}%
                  </span>
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td>
                @if($itemStats['cpl_count'] > 0)
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                    {{ $itemStats['cpl_count'] }} CPL
                  </span>
                @else
                  <span class="text-danger small"><i class="ti ti-x me-1"></i> Belum ada</span>
                @endif
              </td>
              <td>
                @if($itemStats['has_assessment'])
                  <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Punya</span>
                @else
                  <span class="badge bg-light text-muted border px-2 py-1">Belum</span>
                @endif
              </td>
              <td>
                @if($itemStats['status'] === 'Lengkap')
                  <span class="badge bg-success text-white px-3 py-2" style="border-radius: 8px;">LENGKAP</span>
                @elseif($itemStats['status'] === 'Perlu Perbaikan')
                  <span class="badge bg-warning text-dark px-3 py-2" style="border-radius: 8px;">PERBAIKAN</span>
                @else
                  <span class="badge bg-danger text-white px-3 py-2" style="border-radius: 8px;">BELUM ADA</span>
                @endif
              </td>
              <td class="pe-4 text-end">
                <a href="{{ route('curiculum.course.cpmk.manage', ['kurKode' => $kurikulum->kurKode, 'courseId' => $c->id]) }}" 
                   class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                  <i class="ti ti-settings"></i>
                  Kelola CPMK
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center py-5 text-muted">
                <i class="ti ti-alert-circle fs-2 d-block mb-1"></i>
                Belum ada mata kuliah yang terdaftar di kurikulum ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</main>
@endsection
