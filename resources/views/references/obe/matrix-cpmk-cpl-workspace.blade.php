@extends('layouts.app')

@section('content')
<main class="p-2">

  {{-- ========== HEADER ========== --}}
  <div class="card border-1 mb-3 shadow-xs">
    <div class="card-body p-3 p-md-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
          <h3 class="mb-1 fw-bold text-dark d-flex align-items-center gap-2 flex-wrap">
            <i class="ti ti-layout-align-middle text-primary"></i>
            Matriks CPMK–CPL
            <span class="badge bg-primary-subtle text-primary border border-primary fs-6 px-3 py-1">{{ $kurikulum->kurNama }}</span>
            @if($kurikulum->kurIsAktif)
              <span class="badge bg-success-subtle text-success border border-success-subtle fs-7 px-2 py-1">AKTIF / Read-Only</span>
            @else
              <span class="badge bg-warning-subtle text-warning border border-warning-subtle fs-7 px-2 py-1">DRAFT / Dapat Diedit</span>
            @endif
          </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('obe.matrix-cpmk-cpl.index') }}">Matriks CPMK–CPL</a></li>
              <li class="breadcrumb-item active">{{ $kurikulum->kurKode }}</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap">
          @if(!$isReadOnly)
            <div class="dropdown">
              <button class="btn btn-outline-secondary fw-semibold dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="ti ti-copy me-1"></i> Salin dari Kurikulum Lain
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 260px;">
                @if($otherKurikulums->isEmpty())
                  <li><span class="dropdown-item text-muted small">Tidak ada kurikulum lain</span></li>
                @else
                  @foreach($otherKurikulums as $other)
                    <li>
                      <button type="button" class="dropdown-item btn-copy-matrix" data-kur="{{ $other->kurKode }}" data-nama="{{ $other->kurNama }}">
                        <i class="ti ti-layout-align-middle me-2 text-muted"></i>{{ $other->kurNama }}
                        <span class="badge bg-light text-muted ms-1 small">{{ $other->kurKode }}</span>
                      </button>
                    </li>
                  @endforeach
                @endif
              </ul>
            </div>
          @endif

          <div class="dropdown">
            <button class="btn btn-outline-dark fw-semibold dropdown-toggle" type="button" data-bs-toggle="dropdown">
              <i class="ti ti-download me-1"></i> Ekspor
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
              <li>
                <a class="dropdown-item" href="{{ route('curiculum.matrix-cpmk-cpl.export-excel', $kurikulum->kurKode) }}">
                  <i class="ti ti-file-type-csv me-2 text-success"></i> Ekspor ke Excel
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('curiculum.matrix-cpmk-cpl.export-pdf', $kurikulum->kurKode) }}" target="_blank">
                  <i class="ti ti-file-type-pdf me-2 text-danger"></i> Cetak / Simpan PDF
                </a>
              </li>
            </ul>
          </div>

          <a href="{{ route('obe.matrix-cpmk-cpl.index') }}" class="btn btn-light border fw-semibold text-dark">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- ========== FLASH MESSAGES ========== --}}
  <div id="flash-container">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
        <i class="ti ti-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
  </div>

  {{-- ========== ANALYTICS SUMMARY PANEL ========== --}}
  @php
    $totalCpl = $cpls->count();
    $totalCpmk = $cpmkList->count();

    // Calculate coverage
    $mappedCplIds = [];
    $mappedCpmkIds = [];
    $totalMappedCells = 0;

    foreach ($cpls as $cpl) {
      foreach ($cpmkList as $cpmk) {
        $key = $cpl->id_cpl . '_' . $cpmk->id;
        if (isset($matrixCells[$key])) {
          $mappedCplIds[$cpl->id_cpl] = true;
          $mappedCpmkIds[$cpmk->id] = true;
          $totalMappedCells++;
        }
      }
    }

    $cplCountCovered = count($mappedCplIds);
    $cpmkCountCovered = count($mappedCpmkIds);

    $cplPct = $totalCpl > 0 ? round(($cplCountCovered / $totalCpl) * 100) : 0;
    $cpmkPct = $totalCpmk > 0 ? round(($cpmkCountCovered / $totalCpmk) * 100) : 0;
    $density = ($totalCpl > 0 && $totalCpmk > 0) ? round(($totalMappedCells / ($totalCpl * $totalCpmk)) * 100, 1) : 0;
    $unmappedCount = count($unmappedCplMk);
  @endphp
  <div class="row g-3 mb-3">
    <div class="col-md-3 col-6">
      <div class="card border-1 shadow-sm" style="border-radius: 10px;">
        <div class="card-body p-3">
          <div class="text-muted small fw-semibold mb-1">CPL Tercover (Min 1 CPMK)</div>
          <div class="d-flex align-items-center justify-content-between">
            <h4 class="mb-0 fw-bold text-dark">{{ $cplCountCovered }} <span class="fs-6 text-muted fw-normal">/ {{ $totalCpl }} CPL</span></h4>
            <span class="badge bg-{{ $cplPct == 100 ? 'success' : ($cplPct >= 75 ? 'primary' : 'warning') }}-subtle text-{{ $cplPct == 100 ? 'success' : ($cplPct >= 75 ? 'primary' : 'warning') }}">{{ $cplPct }}%</span>
          </div>
          <div class="progress mt-2" style="height: 5px;">
            <div class="progress-bar bg-{{ $cplPct == 100 ? 'success' : 'primary' }}" style="width: {{ $cplPct }}%"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card border-1 shadow-sm" style="border-radius: 10px;">
        <div class="card-body p-3">
          <div class="text-muted small fw-semibold mb-1">CPMK Terpetakan (Min 1 CPL)</div>
          <div class="d-flex align-items-center justify-content-between">
            <h4 class="mb-0 fw-bold text-dark">{{ $cpmkCountCovered }} <span class="fs-6 text-muted fw-normal">/ {{ $totalCpmk }} CPMK</span></h4>
            <span class="badge bg-primary-subtle text-primary">{{ $cpmkPct }}%</span>
          </div>
          <div class="progress mt-2" style="height: 5px;">
            <div class="progress-bar bg-info" style="width: {{ $cpmkPct }}%"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card border-1 shadow-sm" style="border-radius: 10px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background:#eef4ff;"><i class="ti ti-chart-pie text-primary fs-3"></i></div>
          <div>
            <div class="text-muted small fw-semibold">Kepadatan Matriks</div>
            <div class="h4 mb-0 fw-bold">{{ $density }}% <span class="fs-7 text-muted fw-normal">({{ $totalMappedCells }} sel terisi)</span></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card border-1 shadow-sm" style="border-radius: 10px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background:#ffebee;"><i class="ti ti-alert-triangle text-danger fs-3"></i></div>
          <div>
            <div class="text-muted small fw-semibold">Isu Konsistensi</div>
            <div class="h4 mb-0 fw-bold text-{{ $unmappedCount > 0 ? 'danger' : 'success' }}" id="consistency-issues-count">
              {{ $unmappedCount }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ========== GRID MATRIX AREA ========== --}}
  <div class="card border-1 shadow-sm" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
        <i class="ti ti-layout-grid-add text-primary"></i>
        Interactive Mapping Board (CPMK → CPL)
        <span class="small text-muted fw-normal">Hover kolom untuk detail CPL · Klik sel untuk hubungkan / edit bobot CPMK</span>
      </h6>
      <span class="badge bg-light text-muted border font-monospace px-3 py-1">Skema Kurikulum: {{ $kurikulum->skema_kontribusi_cpl }}</span>
    </div>
    
    <div class="card-body p-0">
      <div class="table-responsive no-sticky-global" style="max-height: 600px; overflow: auto;">
        <table class="table table-bordered table-hover align-middle mb-0 text-center no-sticky-global" id="matrix-table" style="min-width: 1000px;">
          <thead style="position: sticky; top: 0; z-index: 10; background: #f8fafc;">
            <tr>
              <!-- Sticky Left Column Header -->
              <th rowspan="2" style="position: sticky; left: 0; top: 0; z-index: 12; background: #f8fafc; min-width: 200px; width: 200px; text-align: left; vertical-align: middle; border-bottom: 2px solid #dee2e6;">
                Struktur Target CPMK
              </th>
              <th rowspan="2" style="top: 0; z-index: 10; background: #f8fafc; width: 80px; min-width: 80px; vertical-align: middle; border-bottom: 2px solid #dee2e6;">Bloom</th>
              <th rowspan="2" style="top: 0; z-index: 10; background: #f8fafc; width: 70px; min-width: 70px; vertical-align: middle; border-bottom: 2px solid #dee2e6;">Bobot MK</th>
              
              <!-- Categories header grouping -->
              @php
                $categories = $cpls->groupBy('kategori');
              @endphp
              @foreach($categories as $cat => $catCpls)
                <th colspan="{{ $catCpls->count() }}" class="small fw-bold text-uppercase border-bottom" style="font-size: 10px; background: #eef2ff;">
                  {{ $cat }}
                </th>
              @endforeach
            </tr>
            <tr style="border-bottom: 2px solid #dee2e6;">
              @foreach($cpls as $cpl)
                <th style="min-width: 52px; width: 52px; font-size: 11px; cursor: help;" 
                    title="{{ $cpl->kategori }} · {{ $cpl->deskripsi }}" 
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top">
                  <strong>{{ $cpl->kode_cpl }}</strong>
                </th>
              @endforeach
            </tr>
          </thead>
          
          <tbody>
            @if($courses->isEmpty())
              <tr>
                <td colspan="{{ $cpls->count() + 3 }}" class="py-5 text-muted">
                  <i class="ti ti-alert-circle fs-1 d-block mb-1"></i>
                  Mata kuliah belum ditambahkan ke Struktur Kurikulum.
                </td>
              </tr>
            @else
              @foreach($courses as $kmk)
                @php
                  $courseCpmks = $cpmksGrouped->get($kmk->id) ?? collect();
                @endphp
                
                <!-- Group Course Header Row -->
                <tr class="table-light-custom text-start" style="background-color: #f1f3f5; border-top: 2px solid #dee2e6;">
                  <td colspan="{{ $cpls->count() + 3 }}" class="ps-3 py-2 fw-bold text-dark font-monospace" style="font-size: 13px;">
                    <i class="ti ti-book me-1 text-primary"></i>
                    {{ $kmk->mataKuliah->mk_kode }} &mdash; {{ $kmk->mataKuliah->mk_nama }} 
                    <span class="badge bg-light text-muted border ms-2">Semester {{ $kmk->semester_anjuran }} • {{ $kmk->sks_override ?? $kmk->mataKuliah->mk_sks_total }} SKS</span>
                  </td>
                </tr>

                @forelse($courseCpmks as $cpmk)
                  <tr>
                    <!-- CPMK Code Column (sticky left corner) -->
                    <td style="position: sticky; left: 0; background: #ffffff; z-index: 5; text-align: left;" class="ps-4 fw-semibold">
                      <div style="font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px;" title="{{ $cpmk->kode_cpmk }} · {{ $cpmk->deskripsi_singkat }}">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace me-1" style="font-size: 10px;">{{ $cpmk->kode_cpmk }}</span>
                        {{ $cpmk->deskripsi_singkat ?: 'Deskripsi CPMK' }}
                      </div>
                    </td>
                    <td style="font-size: 11px;">
                      <span class="badge bg-info-subtle text-info border border-info-subtle font-monospace" title="Ranah: {{ $cpmk->ranah_taksonomi }} · KKO: {{ $cpmk->kko_bloom }}">
                        {{ $cpmk->level_bloom ?: 'COG-3' }}
                      </span>
                    </td>
                    <td style="font-size: 12px;" class="font-monospace text-muted">{{ number_format($cpmk->bobot_cpmk, 0) }}%</td>
                    
                    <!-- Matrix Cells -->
                    @foreach($cpls as $cpl)
                      @php
                        $key = $cpl->id_cpl . '_' . $cpmk->id;
                        $cell = $matrixCells[$key] ?? null;
                        
                        $cellBg = 'transparent';
                        $cellText = '';
                        
                        if ($cell) {
                          if ($kurikulum->skema_kontribusi_cpl == 'KUALITATIF') {
                            $cellText = substr($cell->tingkat_kontribusi, 0, 1); // T / S / R
                            if ($cell->tingkat_kontribusi === 'Tinggi') $cellBg = '#e8f5e9; color: #2e7d32; border: 1.5px solid #a5d6a7 !important;';
                            elseif ($cell->tingkat_kontribusi === 'Sedang') $cellBg = '#fffde7; color: #f57f17; border: 1.5px solid #fff59d !important;';
                            elseif ($cell->tingkat_kontribusi === 'Rendah') $cellBg = '#ffebee; color: #c62828; border: 1.5px solid #ffcdd2 !important;';
                          } else {
                            // Quantitative
                            $cellText = round($cell->bobot_kontribusi) . '%';
                            $cellBg = '#ede7f6; color: #4527a0; border: 1.5px solid #d1c4e9 !important;';
                          }
                        }
                      @endphp
                      
                      <td class="matrix-cell-node cursor-pointer" 
                          data-cpl-id="{{ $cpl->id_cpl }}"
                          data-cpl-kode="{{ $cpl->kode_cpl }}"
                          data-cpl-deskripsi="{{ $cpl->deskripsi }}"
                          
                          data-cpmk-id="{{ $cpmk->id }}"
                          data-cpmk-kode="{{ $cpmk->kode_cpmk }}"
                          data-cpmk-desc-singkat="{{ $cpmk->deskripsi_singkat }}"
                          data-cpmk-desc="{{ $cpmk->deskripsi }}"
                          data-cpmk-bloom="{{ $cpmk->level_bloom }}"
                          data-cpmk-bobot="{{ $cpmk->bobot_cpmk }}"
                          
                          data-cell-id="{{ $cell ? $cell->id_cpmk_cpl : '' }}"
                          data-tingkat="{{ $cell ? $cell->tingkat_kontribusi : '' }}"
                          data-bobot="{{ $cell ? $cell->bobot_kontribusi : '' }}"
                          data-keterangan="{{ $cell ? $cell->keterangan : '' }}"
                          
                          style="{{ $cellBg }} cursor: pointer; transition: background 0.15s; font-size: 11.5px; font-weight: bold; min-width: 52px; width: 52px;"
                          onmouseover="if(!this.style.background.includes('solid')) this.style.background='#f1f5f9';"
                          onmouseout="if(!this.style.background.includes('solid')) this.style.background='transparent';">
                        {{ $cellText }}
                      </td>
                    @endforeach
                  </tr>
                @empty
                  <tr>
                    <td class="ps-4 text-start font-monospace small text-muted" style="font-size: 11px;">(Belum ada CPMK)</td>
                    <td colspan="{{ $cpls->count() + 2 }}" class="text-center py-2 small text-muted italic">
                      Mata kuliah ini belum memiliki target CPMK yang aktif. Kelola di menu CPMK per MK.
                    </td>
                  </tr>
                @endforelse
              @endforeach
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- ========== BOTTOM ANALYTICS & SIMULATION PANEL ========== --}}
  <div class="card border-1 shadow-sm mt-3" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom p-0">
      <ul class="nav nav-tabs card-header-tabs m-0 border-0" id="analysisTab" role="tablist">
        <li class="nav-item">
          <button class="nav-link active py-3 px-4 fw-bold text-dark border-0 border-bottom" id="consistency-tab" data-bs-toggle="tab" data-bs-target="#consistency-panel" type="button" role="tab">
            <i class="ti ti-alert-triangle text-danger me-1"></i> Isu Konsistensi CPL–MK
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link py-3 px-4 fw-bold text-dark border-0 border-bottom" id="distribution-tab" data-bs-toggle="tab" data-bs-target="#distribution-panel" type="button" role="tab">
            <i class="ti ti-chart-bar text-primary me-1"></i> Analisis Distribusi & Bloom
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link py-3 px-4 fw-bold text-dark border-0 border-bottom" id="simulator-tab" data-bs-toggle="tab" data-bs-target="#simulator-panel" type="button" role="tab">
            <i class="ti ti-calculator text-success me-1"></i> Simulator Ketercapaian CPL
          </button>
        </li>
      </ul>
    </div>
    
    <div class="card-body p-4">
      <div class="tab-content" id="analysisTabContent">
        
        {{-- TAB 1: CONSISTENCY PANEL --}}
        <div class="tab-pane fade show active" id="consistency-panel" role="tabpanel">
          <h6 class="fw-bold text-dark mb-2">Checklist Sinkronisasi Matriks Mikro (CPMK–CPL) vs Makro (CPL–MK)</h6>
          <p class="text-muted small mb-4">
            Berdasarkan matriks makro CPL-MK, mata kuliah berikut berkontribusi pada CPL terkait. Tim Kurikulum wajib mendistribusikan kontribusi makro ini dengan memetakan setidaknya satu CPMK detail dari MK tersebut ke CPL target di atas.
          </p>

          <div class="table-responsive rounded border">
            <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
              <thead class="table-light">
                <tr>
                  <th class="ps-3 py-2">Mata Kuliah</th>
                  <th class="py-2">CPL Target</th>
                  <th class="py-2 text-center" style="width: 150px;">Status Matriks</th>
                  <th class="pe-3 py-2 text-end" style="width: 150px;">Aksi Cepat</th>
                </tr>
              </thead>
              <tbody>
                @forelse($unmappedCplMk as $item)
                  <tr>
                    <td class="ps-3 font-monospace">
                      <strong class="text-dark">{{ $item['course']->mataKuliah->mk_kode }}</strong>
                      <span class="text-muted ms-1">{{ $item['course']->mataKuliah->mk_nama }}</span>
                    </td>
                    <td>
                      <span class="badge bg-primary-subtle text-primary border font-monospace">{{ $item['cpl']->kode_cpl }}</span>
                      <span class="text-muted small ms-2">{{ Str::limit($item['cpl']->deskripsi, 80) }}</span>
                    </td>
                    <td class="text-center">
                      <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1">Belum Ada CPMK</span>
                    </td>
                    <td class="pe-3 text-end">
                      <button type="button" class="btn btn-xs btn-outline-primary py-0.5 px-2 small" onclick="focusQuickAssign('{{ $item['course']->id }}', '{{ $item['cpl']->id_cpl }}')">
                        Buka Grid Sel
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center py-4 text-success small">
                      <i class="ti ti-circle-check fs-2 d-block mb-1"></i>
                      <strong>Matriks Konsisten!</strong> Seluruh pemetaan CPL–MK makro telah diuraikan ke CPMK mikro secara penuh.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        {{-- TAB 2: DISTRIBUTION ANALYSIS --}}
        <div class="tab-pane fade" id="distribution-panel" role="tabpanel">
          <div class="row g-4">
            <!-- Left: Category coverage -->
            <div class="col-md-6 border-end">
              <h6 class="fw-bold text-dark mb-3"><i class="ti ti-chart-bar me-1 text-primary"></i>Coverage per Kategori CPL</h6>
              <div class="table-responsive rounded border bg-light-subtle">
                <table class="table table-bordered table-hover mb-0 small">
                  <thead class="table-light text-center">
                    <tr>
                      <th>Kategori CPL</th>
                      <th>Total CPL</th>
                      <th>Tercover</th>
                      <th>Rata-rata Bloom</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $cplCats = $cpls->groupBy('kategori');
                    @endphp
                    @foreach($cplCats as $catName => $catItems)
                      @php
                        $catCplIds = $catItems->pluck('id_cpl');
                        $catMappedCplIds = array_intersect($catCplIds->toArray(), array_keys($mappedCplIds));
                        $catCoveredCount = count($catMappedCplIds);
                        
                        // Calculate average bloom
                        $catCells = $matrixCells->filter(fn($cell) => in_array($cell->id_cpl, $catCplIds->toArray()));
                        $totalLvl = 0;
                        $countLvl = 0;
                        foreach($catCells as $cell) {
                          if ($cell->cpmk && $cell->cpmk->level_bloom) {
                            $num = (int) filter_var($cell->cpmk->level_bloom, FILTER_SANITIZE_NUMBER_INT);
                            if ($num > 0) { $totalLvl += $num; $countLvl++; }
                          }
                        }
                        $avgBloom = $countLvl > 0 ? 'C' . round($totalLvl / $countLvl, 1) : '-';
                      @endphp
                      <tr>
                        <td class="fw-semibold">{{ $catName }}</td>
                        <td class="text-center">{{ $catItems->count() }}</td>
                        <td class="text-center">
                          <span class="badge bg-{{ $catCoveredCount == $catItems->count() ? 'success' : 'warning' }}-subtle text-{{ $catCoveredCount == $catItems->count() ? 'success' : 'warning' }} border px-2 py-0.5">
                            {{ $catCoveredCount }} / {{ $catItems->count() }}
                          </span>
                        </td>
                        <td class="text-center font-monospace">{{ $avgBloom }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <!-- Right: Semester Gantt Distribution horizontal chart -->
            <div class="col-md-6">
              <h6 class="fw-bold text-dark mb-2"><i class="ti ti-calendar me-1 text-primary"></i>Distribusi Semester Kontribusi CPL</h6>
              <p class="text-muted small mb-3">Pilih salah satu CPL di bawah ini untuk memproyeksikan sebaran semester MK pendukungnya:</p>
              
              <div class="d-flex gap-2 mb-3">
                <select id="dist-cpl-select" class="form-select w-50">
                  @foreach($cpls as $cpl)
                    <option value="{{ $cpl->id_cpl }}">{{ $cpl->kode_cpl }} &mdash; {{ Str::limit($cpl->deskripsi, 40) }}</option>
                  @endforeach
                </select>
                <button type="button" class="btn btn-outline-primary" onclick="renderSemesterDistribution()">Proyeksikan</button>
              </div>

              <!-- Bar visualizer -->
              <div class="p-3 border rounded bg-light" id="sem-dist-container">
                <span class="text-muted small italic">Klik Proyeksikan untuk me-load data sebaran.</span>
              </div>
            </div>
          </div>
        </div>

        {{-- TAB 3: SIMULATOR PANEL --}}
        <div class="tab-pane fade" id="simulator-panel" role="tabpanel">
          <div class="row g-4">
            <!-- Simulator left: Selector and sliders -->
            <div class="col-lg-7 border-end">
              <h6 class="fw-bold text-dark mb-2"><i class="ti ti-settings me-1 text-success"></i>Parameter Simulasi Nilai CPMK</h6>
              <p class="text-muted small mb-3">
                Pilih target CPL prodi yang ingin disimulasikan. Slider di bawah mewakili nilai mahasiswa hipotetis (skala 0–100) pada setiap target CPMK pendukung. Geser slider untuk melihat dampaknya ke nilai ketercapaian CPL.
              </p>

              <div class="mb-4">
                <label class="form-label small fw-bold text-dark" for="sim-cpl-select">Target CPL Program Studi</label>
                <select id="sim-cpl-select" class="form-select w-50" onchange="loadSimulatorSliders(this.value)">
                  <option value="">-- Pilih CPL untuk Simulasi --</option>
                  @foreach($cpls as $cpl)
                    @if(isset($mappedCplIds[$cpl->id_cpl]))
                      <option value="{{ $cpl->id_cpl }}">{{ $cpl->kode_cpl }} &mdash; {{ Str::limit($cpl->deskripsi, 60) }}</option>
                    @endif
                  @endforeach
                </select>
              </div>

              <div id="simulator-sliders-area" class="d-flex flex-column gap-3">
                <div class="p-4 text-center text-muted small">
                  <i class="ti ti-info-circle d-block fs-3 mb-1"></i>
                  Silakan pilih CPL untuk memuat parameter simulator.
                </div>
              </div>
            </div>

            <!-- Simulator right: Realtime calculation result -->
            <div class="col-lg-5">
              <h6 class="fw-bold text-dark mb-3"><i class="ti ti-calculator me-1 text-success"></i>Output Nilai CPL Mahasiswa</h6>
              
              <div class="card bg-success-subtle border border-success p-4 rounded-3 text-center mb-3">
                <span class="text-muted small d-block mb-1 text-uppercase fw-bold">Nilai Capaian CPL Hasil Simulasi</span>
                <strong class="text-success display-4 fw-bold" id="sim-cpl-result">0.00</strong>
                <span class="badge bg-success text-white px-3 py-1.5 mt-2 mx-auto fw-bold" style="width:fit-content; font-size:12px;" id="sim-status-badge">TIDAK AKTIF</span>
              </div>

              <div class="p-3 border rounded bg-light">
                <strong class="text-dark small d-block mb-2">Penjelasan Rumus (Transparency Formula)</strong>
                <div id="sim-formula-explanation" class="font-monospace small text-muted" style="line-height: 1.6; font-size: 11px;">
                  Pilih CPL untuk melihat kalkulasi transparansi formula matematis.
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- ========== SIDE DRAWER DETAIL PANEL ========== --}}
  <div class="offcanvas offcanvas-end" tabindex="-1" id="cellDetailDrawer" style="width: 420px; border-left: none; box-shadow: -5px 0 25px rgba(0,0,0,0.15);">
    <div class="offcanvas-header border-bottom bg-light">
      <h6 class="offcanvas-title fw-bold text-dark d-flex align-items-center gap-2">
        <i class="ti ti-edit-circle text-primary fs-4"></i>
        Pemetaan Detail CPMK–CPL
      </h6>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    
    <div class="offcanvas-body p-4 d-flex flex-column justify-content-between">
      <div>
        {{-- Selected info --}}
        <div class="p-3 bg-light rounded-3 mb-3 border">
          <div class="mb-2">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace fw-bold" id="drawer-cpl-kode">S1</span>
            <span class="text-muted small ms-1" id="drawer-cpl-desc" style="display:block; margin-top:4px; font-size:11.5px; line-height:1.4;">Deskripsi CPL</span>
          </div>
          <hr class="my-2">
          <div>
            <span class="badge bg-info-subtle text-info border font-monospace" id="drawer-cpmk-kode" style="font-size:10px;">CPMK-1</span>
            <span class="badge bg-light border text-muted font-monospace" id="drawer-cpmk-bloom" style="font-size:10px;">COG-3</span>
            <span class="badge bg-light border text-muted font-monospace" id="drawer-cpmk-bobot" style="font-size:10px;">25%</span>
            <strong class="text-dark d-block mt-2" id="drawer-cpmk-desc-singkat" style="font-size:13px;">Deskripsi Singkat</strong>
            <p class="text-muted small mb-0 mt-1" id="drawer-cpmk-desc" style="font-size:11px; line-height:1.4;">Deskripsi Lengkap Rumusan CPMK</p>
          </div>
        </div>

        {{-- Form inputs --}}
        <form id="matrix-cell-form">
          <input type="hidden" id="form-cpl-id">
          <input type="hidden" id="form-cpmk-id">
          <input type="hidden" id="form-cell-id">

          <!-- Skema Kualitatif Inputs -->
          <div class="mb-3" id="group-kualitatif">
            <label class="form-label fw-bold text-dark small">Tingkat Kontribusi *</label>
            <div class="d-flex gap-2">
              <input type="radio" class="btn-check" name="tingkat_kontribusi" id="radio-tingkat-t" value="Tinggi">
              <label class="btn btn-outline-success flex-grow-1 py-2 fw-semibold" for="radio-tingkat-t">Tinggi (T)</label>
              
              <input type="radio" class="btn-check" name="tingkat_kontribusi" id="radio-tingkat-s" value="Sedang">
              <label class="btn btn-outline-warning flex-grow-1 py-2 fw-semibold" for="radio-tingkat-s">Sedang (S)</label>
              
              <input type="radio" class="btn-check" name="tingkat_kontribusi" id="radio-tingkat-r" value="Rendah">
              <label class="btn btn-outline-danger flex-grow-1 py-2 fw-semibold" for="radio-tingkat-r">Rendah (R)</label>
            </div>
          </div>

          <!-- Skema Kuantitatif Inputs -->
          <div class="mb-3 d-none" id="group-kuantitatif">
            <label class="form-label fw-bold text-dark small" for="input-bobot">Bobot Kontribusi (%) *</label>
            <div class="input-group">
              <input type="number" id="input-bobot" class="form-control" placeholder="25.00" min="0" max="100" step="0.01">
              <span class="input-group-text">%</span>
            </div>
            <p class="text-muted small mt-1 mb-0" style="font-size: 10px;">Bobot numerik seberapa besar proporsi kontribusi CPMK ini untuk CPL target. (Rekomendasi total bobot = 100%)</p>
          </div>

          <!-- Keterangan -->
          <div class="mb-3">
            <label class="form-label fw-bold text-dark small" for="textarea-keterangan">Keterangan Relasi</label>
            <textarea id="textarea-keterangan" class="form-control small" rows="2" placeholder="Tulis catatan atau justifikasi keterkaitan CPMK ke CPL..."></textarea>
          </div>

          <!-- Audit Reason -->
          <div class="mb-3">
            <label class="form-label fw-bold text-dark small" for="textarea-reason">Alasan Perubahan (Audit Trail)</label>
            <textarea id="textarea-reason" class="form-control small" rows="1" placeholder="Opsional alasan revisi"></textarea>
          </div>
        </form>
      </div>

      <div>
        {{-- Audit Log History Accordion --}}
        <div class="accordion accordion-flush mb-4" id="historyAccordion">
          <div class="accordion-item border rounded shadow-xs bg-light">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed py-2 px-3 fw-bold small text-muted bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHistory">
                <i class="ti ti-history me-1"></i> Riwayat Perubahan Sel (Audit Log)
              </button>
            </h2>
            <div id="collapseHistory" class="accordion-collapse collapse" data-bs-parent="#historyAccordion">
              <div class="accordion-body p-2" id="drawer-history-list" style="max-height: 150px; overflow-y: auto; font-size:10.5px;">
                <span class="text-muted italic">Pilih sel untuk melihat riwayat.</span>
              </div>
            </div>
          </div>
        </div>

        {{-- Actions --}}
        @if(!$isReadOnly)
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary fw-semibold flex-grow-1" id="btn-save-cell">
              <i class="ti ti-check me-1"></i> Simpan Pemetaan
            </button>
            <button type="button" class="btn btn-outline-danger" id="btn-delete-cell" title="Hapus Pemetaan">
              <i class="ti ti-trash"></i>
            </button>
          </div>
        @else
          <div class="alert alert-light border small text-muted text-center mb-0">
            <i class="ti ti-lock me-1"></i> Kurikulum berstatus Aktif. Pemetaan bersifat Read-Only.
          </div>
        @endif
      </div>
    </div>
  </div>

</main>
@endsection

@push('scripts')
<script>
const kurKode = '{{ $kurikulum->kurKode }}';
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const isReadOnly = {{ $isReadOnly ? 'true' : 'false' }};
let currentSkema = '{{ $kurikulum->skema_kontribusi_cpl }}';

// ==========================================
// FLASH HELPER
// ==========================================
function showFlash(msg, type = 'success') {
  const c = document.getElementById('flash-container');
  const div = document.createElement('div');
  div.className = `alert alert-${type} alert-dismissible fade show border-0 shadow-sm mb-3`;
  div.innerHTML = `<i class="ti ti-${type === 'success' ? 'circle-check' : 'alert-circle'} me-2"></i>${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
  c.prepend(div);
  setTimeout(() => {
    const bsAlert = bootstrap.Alert.getOrCreateInstance(div);
    if (bsAlert) bsAlert.close();
  }, 4000);
}

// ==========================================
// API CALL HELPER
// ==========================================
async function apiCall(url, method, data) {
  const res = await fetch(url, {
    method,
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json'
    },
    body: JSON.stringify(data)
  });
  return res.json();
}

// ==========================================
// MATRIX INTERACTION & DRAWING
// ==========================================
let activeCellNode = null;
const drawer = new bootstrap.Offcanvas(document.getElementById('cellDetailDrawer'));

// Click Matrix Cells
document.querySelectorAll('.matrix-cell-node').forEach(cell => {
  cell.addEventListener('click', function() {
    activeCellNode = cell;
    openCellDrawer(cell);
  });
});

function openCellDrawer(cell) {
  const cplId = cell.dataset.cplId;
  const cplKode = cell.dataset.cplKode;
  const cplDeskripsi = cell.dataset.cplDeskripsi;
  
  const cpmkId = cell.dataset.cpmkId;
  const cpmkKode = cell.dataset.cpmkKode;
  const cpmkDescSingkat = cell.dataset.cpmkDescSingkat;
  const cpmkDesc = cell.dataset.cpmkDesc;
  const cpmkBloom = cell.dataset.cpmkBloom;
  const cpmkBobot = cell.dataset.cpmkBobot;
  
  const cellId = cell.dataset.cellId;
  const tingkat = cell.dataset.tingkat;
  const bobot = cell.dataset.bobot;
  const keterangan = cell.dataset.keterangan;

  // Set header values
  document.getElementById('drawer-cpl-kode').textContent = cplKode;
  document.getElementById('drawer-cpl-desc').textContent = cplDeskripsi;
  document.getElementById('drawer-cpmk-kode').textContent = cpmkKode;
  document.getElementById('drawer-cpmk-bloom').textContent = cpmkBloom;
  document.getElementById('drawer-cpmk-bobot').textContent = 'Bobot: ' + parseInt(cpmkBobot) + '%';
  document.getElementById('drawer-cpmk-desc-singkat').textContent = cpmkDescSingkat;
  document.getElementById('drawer-cpmk-desc').textContent = cpmkDesc;

  // Set form values
  document.getElementById('form-cpl-id').value = cplId;
  document.getElementById('form-cpmk-id').value = cpmkId;
  document.getElementById('form-cell-id').value = cellId;
  document.getElementById('textarea-keterangan').value = keterangan || '';
  document.getElementById('textarea-reason').value = '';

  // Toggle schema groups
  if (currentSkema === 'KUALITATIF') {
    document.getElementById('group-kualitatif').classList.remove('d-none');
    document.getElementById('group-kuantitatif').classList.add('d-none');
    
    // reset radios
    document.querySelectorAll('input[name="tingkat_kontribusi"]').forEach(r => r.checked = false);
    if (tingkat) {
      const radio = document.querySelector(`input[name="tingkat_kontribusi"][value="${tingkat}"]`);
      if (radio) radio.checked = true;
    }
  } else {
    document.getElementById('group-kualitatif').classList.add('d-none');
    document.getElementById('group-kuantitatif').classList.remove('d-none');
    document.getElementById('input-bobot').value = bobot ? parseFloat(bobot) : '';
  }

  // Load audit history
  loadCellHistory(cellId);

  drawer.show();
}

async function loadCellHistory(cellId) {
  const container = document.getElementById('drawer-history-list');
  if (!cellId) {
    container.innerHTML = '<span class="text-muted italic">Pemetaan baru. Belum ada riwayat.</span>';
    return;
  }
  container.innerHTML = '<span class="text-muted">Memuat riwayat...</span>';
  
  try {
    const data = await apiCall(`/references/curiculum/${kurKode}/matrix-cpmk-cpl/history/${cellId}`, 'GET');
    if (data.success && data.histories.length > 0) {
      container.innerHTML = '';
      data.histories.forEach(h => {
        const item = document.createElement('div');
        item.className = 'border-bottom pb-2 mb-2';
        
        let details = '';
        if (h.aksi === 'INSERT') {
          details = h.tk_baru ? `Assign <strong>${h.tk_baru}</strong>` : `Assign <strong>${parseFloat(h.b_baru)}%</strong>`;
        } else if (h.aksi === 'UPDATE') {
          if (h.tk_lama !== h.tk_baru) details = `Tingkat: ${h.tk_lama} → <strong>${h.tk_baru}</strong>. `;
          if (h.b_lama !== h.b_baru) details += `Bobot: ${parseFloat(h.b_lama)}% → <strong>${parseFloat(h.b_baru)}%</strong>. `;
        } else {
          details = 'Menghapus pemetaan.';
        }

        item.innerHTML = `
          <div class="d-flex justify-content-between mb-0.5">
            <span class="badge bg-light text-dark font-monospace">${h.aksi}</span>
            <span class="text-muted">${h.tgl}</span>
          </div>
          <div>${details}</div>
          <div class="text-muted small italic">Oleh: ${h.user} ${h.ket ? `(${h.ket})` : ''}</div>
        `;
        container.appendChild(item);
      });
    } else {
      container.innerHTML = '<span class="text-muted italic">Tidak ada catatan perubahan.</span>';
    }
  } catch (err) {
    container.innerHTML = '<span class="text-danger">Gagal memuat log riwayat.</span>';
  }
}

// Save Matrix Cell changes
document.getElementById('btn-save-cell')?.addEventListener('click', async () => {
  if (isReadOnly) return;
  const cplId = document.getElementById('form-cpl-id').value;
  const cpmkId = document.getElementById('form-cpmk-id').value;
  
  let tingkat = null;
  if (currentSkema === 'KUALITATIF') {
    const checked = document.querySelector('input[name="tingkat_kontribusi"]:checked');
    tingkat = checked ? checked.value : null;
  }
  
  const bobot = document.getElementById('input-bobot').value;
  const keterangan = document.getElementById('textarea-keterangan').value;
  const reason = document.getElementById('textarea-reason').value;

  const payload = {
    id_cpl: cplId,
    id_cpmk: parseInt(cpmkId),
    tingkat_kontribusi: tingkat,
    bobot_kontribusi: bobot ? parseFloat(bobot) : null,
    keterangan: keterangan,
    alasan: reason
  };

  const data = await apiCall(`/references/curiculum/${kurKode}/matrix-cpmk-cpl/update-cell`, 'POST', payload);
  if (data.success) {
    showFlash(data.message);
    drawer.hide();
    setTimeout(() => location.reload(), 800);
  } else {
    showFlash(data.message, 'danger');
  }
});

// Delete Matrix Cell
document.getElementById('btn-delete-cell')?.addEventListener('click', async () => {
  if (isReadOnly) return;
  const cplId = document.getElementById('form-cpl-id').value;
  const cpmkId = document.getElementById('form-cpmk-id').value;
  const reason = document.getElementById('textarea-reason').value || 'Penghapusan manual';

  if (!(await confirmAsync('Apakah Anda yakin ingin menghapus pemetaan CPMK ke CPL ini?')) return;

  const payload = {
    id_cpl: cplId,
    id_cpmk: parseInt(cpmkId),
    tingkat_kontribusi: 'HAPUS',
    alasan: reason
  };

  const data = await apiCall(`/references/curiculum/${kurKode}/matrix-cpmk-cpl/update-cell`, 'POST', payload);
  if (data.success) {
    showFlash(data.message);
    drawer.hide();
    setTimeout(() => location.reload(), 800);
  } else {
    showFlash(data.message, 'danger');
  }
});

// Copy Matrix copy handler
document.querySelectorAll('.btn-copy-matrix').forEach(btn => {
  btn.addEventListener('click', async function() {
    if (isReadOnly) return;
    const source = this.dataset.kur;
    const nama = this.dataset.nama;
    
    if (!(await confirmAsync(`PENTING: Seluruh pemetaan CPMK-CPL di kurikulum ini akan dihapus dan disalin dari ${nama} (${source}). Lanjutkan?`)) return;
    
    const data = await apiCall(`/references/curiculum/${kurKode}/matrix-cpmk-cpl/copy`, 'POST', {
      source_kurikulum_kode: source
    });
    
    if (data.success) {
      showFlash(data.message);
      setTimeout(() => location.reload(), 1000);
    } else {
      showFlash(data.message, 'danger');
    }
  });
});

// Focus Quick Assign for Consistency Tab
function focusQuickAssign(courseId, cplId) {
  // Find the exact cell in the grid
  const node = document.querySelector(`.matrix-cell-node[data-cpmk-id][data-cpl-id="${cplId}"]`);
  if (node) {
    node.scrollIntoView({ behavior: 'smooth', block: 'center' });
    node.focus();
    // Highlight effect
    node.style.outline = '3px solid #ffc107';
    setTimeout(() => { node.style.outline = ''; }, 3000);
  } else {
    alert("Silakan cari baris mata kuliah bersangkutan pada tabel di atas.");
  }
}

// ==========================================
// TAB 2: SEMESTER DISTRIBUTION GANTT PROJECTOR
// ==========================================
function renderSemesterDistribution() {
  const cplId = document.getElementById('dist-cpl-select').value;
  const container = document.getElementById('sem-dist-container');
  
  // Find all cell nodes mapped to this CPL
  const nodes = document.querySelectorAll(`.matrix-cell-node[data-cpl-id="${cplId}"][data-cell-id]:not([data-cell-id=""])`);
  
  if (nodes.length === 0) {
    container.innerHTML = '<span class="text-danger small fw-semibold">Tidak ada CPMK pendukung yang aktif untuk CPL ini.</span>';
    return;
  }

  // Count CPMK by Semester
  const semCounts = {1:0, 2:0, 3:0, 4:0, 5:0, 6:0, 7:0, 8:0};
  nodes.forEach(n => {
    // Find parent TR's semester
    const tr = n.closest('tr');
    // Find preceding course header row to extract semester, or look at siblings. 
    // A robust way: find preceding TR that has .table-light-custom and parse its text
    let sibling = tr;
    while (sibling) {
      if (sibling.classList.contains('table-light-custom')) {
        const text = sibling.textContent || '';
        const match = text.match(/Semester\s+(\d+)/i);
        if (match && match[1]) {
          const sem = parseInt(match[1]);
          if (semCounts[sem] !== undefined) {
            semCounts[sem]++;
          }
        }
        break;
      }
      sibling = sibling.previousElementSibling;
    }
  });

  // Render horizontal bar chart
  let html = '<div class="d-flex flex-column gap-2">';
  for (let s = 1; s <= 8; s++) {
    const count = semCounts[s];
    const widthPct = nodes.length > 0 ? (count / nodes.length) * 100 : 0;
    
    html += `
      <div class="d-flex align-items-center gap-3">
        <span class="font-monospace fw-bold" style="width: 50px;">Sem ${s}</span>
        <div class="progress flex-grow-1" style="height: 15px; border-radius: 4px;">
          <div class="progress-bar bg-primary" role="progressbar" style="width: ${widthPct}%" aria-valuenow="${count}" aria-valuemin="0" aria-valuemax="${nodes.length}">
            ${count > 0 ? count + ' CPMK' : ''}
          </div>
        </div>
      </div>
    `;
  }
  html += '</div>';
  container.innerHTML = html;
}

// ==========================================
// TAB 3: SIMULATOR CALCULATOR
// ==========================================
let simCpmkList = [];
function loadSimulatorSliders(cplId) {
  const area = document.getElementById('simulator-sliders-area');
  const explanation = document.getElementById('sim-formula-explanation');
  
  if (!cplId) {
    area.innerHTML = '<div class="p-4 text-center text-muted small"><i class="ti ti-info-circle d-block fs-3 mb-1"></i>Silakan pilih CPL untuk memuat parameter simulator.</div>';
    explanation.textContent = 'Pilih CPL untuk melihat kalkulasi transparansi formula matematis.';
    document.getElementById('sim-cpl-result').textContent = '0.00';
    document.getElementById('sim-status-badge').className = 'badge bg-secondary text-white px-3 py-1.5 mt-2 mx-auto fw-bold';
    document.getElementById('sim-status-badge').textContent = 'TIDAK AKTIF';
    return;
  }

  // Find all cell nodes mapped to this CPL
  const nodes = document.querySelectorAll(`.matrix-cell-node[data-cpl-id="${cplId}"][data-cell-id]:not([data-cell-id=""])`);
  simCpmkList = [];

  if (nodes.length === 0) {
    area.innerHTML = '<div class="p-4 text-center text-danger small"><i class="ti ti-circle-x d-block fs-3 mb-1"></i>CPL ini belum memiliki CPMK pendukung yang dipetakan.</div>';
    explanation.textContent = 'Tidak ada formula ketercapaian.';
    return;
  }

  let html = '';
  nodes.forEach(n => {
    const cpmkId = n.dataset.cpmkId;
    const cpmkKode = n.dataset.cpmkKode;
    const cpmkDesc = n.dataset.cpmkDescSingkat;
    
    // Parse weight: if qualitative it uses default (T=1, S=0.5, R=0.25). if quantitative it uses dataset bobot
    let w = 1.0;
    if (currentSkema === 'KUALITATIF') {
      const t = n.dataset.tingkat;
      if (t === 'Tinggi') w = 1.0;
      else if (t === 'Sedang') w = 0.5;
      else if (t === 'Rendah') w = 0.25;
    } else {
      w = parseFloat(n.dataset.bobot) || 0;
    }

    simCpmkList.push({
      id: cpmkId,
      kode: cpmkKode,
      bobot: w
    });

    html += `
      <div class="card p-3 shadow-xs border">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <div>
            <span class="badge bg-light border text-dark font-monospace">${cpmkKode}</span>
            <span class="text-muted small ms-1">${cpmkDesc}</span>
          </div>
          <span class="badge bg-primary-subtle text-primary border font-monospace" id="sim-lbl-weight-${cpmkId}">
            ${currentSkema === 'KUALITATIF' ? 'Faktor: ' + w : 'Bobot: ' + w + '%'}
          </span>
        </div>
        <div class="d-flex align-items-center gap-3">
          <input type="range" class="form-range flex-grow-1 sim-range-slider" min="0" max="100" value="80" data-cpmk-id="${cpmkId}" oninput="document.getElementById('sim-lbl-val-${cpmkId}').textContent = this.value; calculateSimulatedCpl();">
          <span class="font-monospace fw-bold text-dark text-center" style="width: 45px;" id="sim-lbl-val-${cpmkId}">80</span>
        </div>
      </div>
    `;
  });

  area.innerHTML = html;
  calculateSimulatedCpl();
}

function calculateSimulatedCpl() {
  if (simCpmkList.length === 0) return;

  let totalWeightedScore = 0;
  let sumWeights = 0;
  
  let formulaTerms = [];
  let formulaSumWeightsTerms = [];

  simCpmkList.forEach(item => {
    const slider = document.querySelector(`.sim-range-slider[data-cpmk-id="${item.id}"]`);
    const val = slider ? parseFloat(slider.value) : 80;

    totalWeightedScore += (val * item.bobot);
    sumWeights += item.bobot;

    formulaTerms.push(`(${val} × ${item.bobot})`);
    formulaSumWeightsTerms.push(item.bobot);
  });

  const cplScore = sumWeights > 0 ? (totalWeightedScore / sumWeights) : 0;
  const resultDisplay = document.getElementById('sim-cpl-result');
  resultDisplay.textContent = cplScore.toFixed(2);

  // Set status badge based on target ketercapaian (default threshold 75)
  const statusBadge = document.getElementById('sim-status-badge');
  if (cplScore >= 75) {
    statusBadge.className = 'badge bg-success text-white px-3 py-1.5 mt-2 mx-auto fw-bold';
    statusBadge.textContent = 'TERCAPAI (>= 75)';
  } else {
    statusBadge.className = 'badge bg-danger text-white px-3 py-1.5 mt-2 mx-auto fw-bold';
    statusBadge.textContent = 'BELUM TERCAPAI (< 75)';
  }

  // Update explanation
  const explanation = document.getElementById('sim-formula-explanation');
  explanation.innerHTML = `
    <strong>Formula:</strong><br>
    Nilai CPL = Σ (Nilai_CPMK × Bobot) ÷ Σ Bobot<br><br>
    <strong>Kalkulasi Aktual:</strong><br>
    = [ ${formulaTerms.join(' + ')} ]<br>
    &nbsp;&nbsp;───────────────────────────<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ ${formulaSumWeightsTerms.join(' + ')} ]<br><br>
    = ${totalWeightedScore.toFixed(2)} ÷ ${sumWeights.toFixed(2)}<br>
    = <strong>${cplScore.toFixed(2)}</strong>
  `;
}
</script>

<style>
  /* Custom styles for self-contained scrollable matrix board */
  .no-sticky-global thead {
    position: sticky;
    top: 0;
    z-index: 10;
  }
  
  /* Ensure background color is preserved for sticky headers in light/dark modes */
  [data-bs-theme="light"] .no-sticky-global thead tr:nth-child(1) th {
    background-color: #f8fafc !important;
  }
  [data-bs-theme="light"] .no-sticky-global thead tr:nth-child(1) th:not([rowspan="2"]) {
    background-color: #eef2ff !important; /* Indigo category bg */
  }
  [data-bs-theme="light"] .no-sticky-global thead tr:nth-child(2) th {
    background-color: #f8fafc !important;
  }

  [data-bs-theme="dark"] .no-sticky-global thead tr:nth-child(1) th {
    background-color: #1e293b !important;
  }
  [data-bs-theme="dark"] .no-sticky-global thead tr:nth-child(1) th:not([rowspan="2"]) {
    background-color: #27272a !important;
  }
  [data-bs-theme="dark"] .no-sticky-global thead tr:nth-child(2) th {
    background-color: #1e293b !important;
  }
  
  /* Sticky first column (Struktur Target CPMK) */
  #matrix-table td:first-child:not([colspan]) {
    position: sticky;
    left: 0;
    background-color: var(--bs-body-bg) !important;
    z-index: 4;
    border-right: 2px solid #dee2e6 !important;
  }
  
  #matrix-table thead tr:first-child th:first-child {
    position: sticky;
    left: 0;
    z-index: 12 !important;
    border-right: 2px solid #dee2e6 !important;
  }

  .table-light-custom td {
    border-top: 1.5px solid #dee2e6 !important;
    border-bottom: 1.5px solid #dee2e6 !important;
  }
  .matrix-cell-node:focus {
    outline: 3px solid #ffc107;
  }
</style>
@endpush
