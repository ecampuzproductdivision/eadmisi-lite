@extends('layouts.app')

@section('content')
<main class="p-2">

  {{-- ========== HEADER ========== --}}
  <div class="card border-1 mb-3 shadow-xs">
    <div class="card-body p-3 p-md-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
          <h3 class="mb-1 fw-bold text-dark d-flex align-items-center gap-2 flex-wrap">
            <i class="ti ti-table text-primary"></i>
            Matriks CPL–MK
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
              <li class="breadcrumb-item"><a href="{{ route('obe.matrix.index') }}">Matriks CPL–MK</a></li>
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
                        <i class="ti ti-table me-2 text-muted"></i>{{ $other->kurNama }}
                        <span class="badge bg-light text-muted ms-1 small">{{ $other->kurKode }}</span>
                      </button>
                    </li>
                  @endforeach
                @endif
              </ul>
            </div>

            <div class="dropdown">
              <button class="btn btn-outline-primary fw-semibold dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="ti ti-settings me-1"></i> Skema Kontribusi: <strong id="schema-label-display">{{ $kurikulum->skema_kontribusi_cpl }}</strong>
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li>
                  <button type="button" class="dropdown-item btn-change-schema {{ $kurikulum->skema_kontribusi_cpl == 'KUALITATIF' ? 'active' : '' }}" data-schema="KUALITATIF">
                    Kualitatif (Tinggi / Sedang / Rendah)
                  </button>
                </li>
                <li>
                  <button type="button" class="dropdown-item btn-change-schema {{ $kurikulum->skema_kontribusi_cpl == 'KUANTITATIF' ? 'active' : '' }}" data-schema="KUANTITATIF">
                    Kuantitatif (Bobot Persentase)
                  </button>
                </li>
              </ul>
            </div>
          @endif

          <div class="dropdown">
            <button class="btn btn-outline-dark fw-semibold dropdown-toggle" type="button" data-bs-toggle="dropdown">
              <i class="ti ti-download me-1"></i> Ekspor
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
              <li>
                <a class="dropdown-item" href="{{ route('curiculum.matrix.export-excel', $kurikulum->kurKode) }}">
                  <i class="ti ti-file-type-csv me-2 text-success"></i> Ekspor ke Excel
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('curiculum.matrix.export-pdf', $kurikulum->kurKode) }}" target="_blank">
                  <i class="ti ti-file-type-pdf me-2 text-danger"></i> Cetak / Simpan PDF
                </a>
              </li>
            </ul>
          </div>

          <a href="{{ route('obe.matrix.index') }}" class="btn btn-light border fw-semibold text-dark">
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
    $totalMk = $courses->count();

    // Calculate coverage from PHP side
    $mappedCplIds = [];
    $mappedMkIds = [];
    $totalMappedCells = 0;

    foreach ($cpls as $cpl) {
      foreach ($courses as $kmk) {
        $key = $cpl->id_cpl . '_' . $kmk->id;
        if (isset($matrixCells[$key])) {
          $mappedCplIds[$cpl->id_cpl] = true;
          $mappedMkIds[$kmk->id] = true;
          $totalMappedCells++;
        }
      }
    }

    $cplCountCovered = count($mappedCplIds);
    $mkCountCovered = count($mappedMkIds);

    $cplPct = $totalCpl > 0 ? round(($cplCountCovered / $totalCpl) * 100) : 0;
    $mkPct = $totalMk > 0 ? round(($mkCountCovered / $totalMk) * 100) : 0;
    $density = ($totalCpl > 0 && $totalMk > 0) ? round(($totalMappedCells / ($totalCpl * $totalMk)) * 100, 1) : 0;
  @endphp
  <div class="row g-3 mb-3">
    <div class="col-md-3 col-6">
      <div class="card border-1 shadow-sm" style="border-radius: 10px;">
        <div class="card-body p-3">
          <div class="text-muted small fw-semibold mb-1">CPL Tercover (Min 1 MK)</div>
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
          <div class="text-muted small fw-semibold mb-1">MK Terpetakan (Min 1 CPL)</div>
          <div class="d-flex align-items-center justify-content-between">
            <h4 class="mb-0 fw-bold text-dark">{{ $mkCountCovered }} <span class="fs-6 text-muted fw-normal">/ {{ $totalMk }} MK</span></h4>
            <span class="badge bg-primary-subtle text-primary">{{ $mkPct }}%</span>
          </div>
          <div class="progress mt-2" style="height: 5px;">
            <div class="progress-bar bg-info" style="width: {{ $mkPct }}%"></div>
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
          <div class="p-2 rounded-3" style="background:#fff3e0;"><i class="ti ti-alert-triangle text-warning fs-3"></i></div>
          <div>
            <div class="text-muted small fw-semibold">Isu Validasi</div>
            <div class="h4 mb-0 fw-bold" id="validation-issues-count">
              {{ ($totalCpl - $cplCountCovered) + ($totalMk - $mkCountCovered) }}
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
        Interactive Mapping Board
        <span class="small text-muted fw-normal">Hover kolom untuk detail CPL · Klik sel untuk edit / hubungkan CPMK</span>
      </h6>
      @if(!$isReadOnly)
        <div class="d-flex align-items-center gap-2">
          <button class="btn btn-sm btn-outline-danger btn-bulk-clear d-none">
            <i class="ti ti-trash me-1"></i> Hapus Sel Terpilih
          </button>
          <div class="dropdown btn-bulk-assign-dropdown d-none">
            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
              <i class="ti ti-check me-1"></i> Set Kontribusi (Bulk)
            </button>
            <ul class="dropdown-menu shadow-sm">
              <li><button type="button" class="dropdown-item btn-bulk-assign-val" data-val="Tinggi"><span class="badge bg-success-subtle text-success border me-1">T</span> Tinggi</button></li>
              <li><button type="button" class="dropdown-item btn-bulk-assign-val" data-val="Sedang"><span class="badge bg-warning-subtle text-warning border me-1">S</span> Sedang</button></li>
              <li><button type="button" class="dropdown-item btn-bulk-assign-val" data-val="Rendah"><span class="badge bg-danger-subtle text-danger border me-1">R</span> Rendah</button></li>
            </ul>
          </div>
          <div class="form-check form-switch ms-2">
            <input class="form-check-input" type="checkbox" id="bulkModeSwitch">
            <label class="form-check-label small fw-semibold" for="bulkModeSwitch">Bulk Mode</label>
          </div>
        </div>
      @endif
    </div>
    
    <div class="card-body p-0">
      <div class="table-responsive no-sticky-global" style="max-height: 600px; overflow: auto;">
        <table class="table table-bordered table-hover align-middle mb-0 text-center no-sticky-global" id="matrix-table" style="min-width: 1000px;">
          <thead style="position: sticky; top: 0; z-index: 10; background: #f8fafc;">
            <tr>
              <!-- Double Sticky Header Left Corner -->
              <th rowspan="2" style="position: sticky; left: 0; top: 0; z-index: 12; background: #f8fafc; min-width: 250px; width: 250px; text-align: left; vertical-align: middle; border-bottom: 2px solid #dee2e6;">
                Mata Kuliah
              </th>
              <th rowspan="2" style="top: 0; z-index: 10; background: #f8fafc; width: 50px; min-width: 50px; vertical-align: middle; border-bottom: 2px solid #dee2e6;">Smt</th>
              <th rowspan="2" style="top: 0; z-index: 10; background: #f8fafc; width: 50px; min-width: 50px; vertical-align: middle; border-bottom: 2px solid #dee2e6;">SKS</th>
              
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
                  $courseCpls = $matrixCells->filter(fn($c) => $c->id_kmk == $kmk->id);
                @endphp
                <tr>
                  <!-- First column sticky left -->
                  <td style="position: sticky; left: 0; background: #ffffff; z-index: 5; text-align: left;" class="fw-semibold">
                    <div style="font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 230px;" title="{{ $kmk->mataKuliah->mk_nama }}">
                      <span class="font-monospace text-muted small me-1">{{ $kmk->mataKuliah->mk_kode }}</span>
                      {{ $kmk->mataKuliah->mk_nama }}
                    </div>
                  </td>
                  <td style="font-size: 12px;">{{ $kmk->semester_anjuran }}</td>
                  <td style="font-size: 12px;">{{ $kmk->sks_override ?? $kmk->mataKuliah->mk_sks_total }}</td>
                  
                  <!-- Matrix Cells -->
                  @foreach($cpls as $cpl)
                    @php
                      $key = $cpl->id_cpl . '_' . $kmk->id;
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
                        
                        data-kmk-id="{{ $kmk->id }}"
                        data-mk-kode="{{ $kmk->mataKuliah->mk_kode }}"
                        data-mk-nama="{{ $kmk->mataKuliah->mk_nama }}"
                        data-mk-id="{{ $kmk->mk_id }}"
                        
                        data-cell-id="{{ $cell ? $cell->id_cpl_mk : '' }}"
                        data-tingkat="{{ $cell ? $cell->tingkat_kontribusi : '' }}"
                        data-bobot="{{ $cell ? $cell->bobot_kontribusi : '' }}"
                        data-cpmk-ref="{{ $cell ? $cell->id_cpmk_ref : '' }}"
                        data-keterangan="{{ $cell ? $cell->keterangan : '' }}"
                        
                        style="{{ $cellBg }} cursor: pointer; transition: background 0.15s; font-size: 11.5px; font-weight: bold; min-width: 52px; width: 52px;"
                        onmouseover="if(!this.style.background.includes('solid')) this.style.background='#f1f5f9';"
                        onmouseout="if(!this.style.background.includes('solid')) this.style.background='transparent';">
                      {{ $cellText }}
                    </td>
                  @endforeach
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- ========== COVERAGE WARNING REPORT PANEL ========== --}}
  <div class="row g-3 mt-2">
    <div class="col-md-6">
      <div class="card border-1 shadow-sm" style="border-radius: 12px; height: 100%;">
        <div class="card-header bg-white border-bottom py-3 px-4">
          <h6 class="fw-bold mb-0 text-dark"><i class="ti ti-help-circle text-primary me-2"></i>Status Coverage CPL</h6>
        </div>
        <div class="card-body p-0" style="max-height: 250px; overflow-y: auto;">
          <div class="list-group list-group-flush">
            @foreach($cpls as $cpl)
              @php
                $isCovered = isset($mappedCplIds[$cpl->id_cpl]);
                $cplMks = $matrixCells->filter(fn($c) => $c->id_cpl == $cpl->id_cpl);
              @endphp
              <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-4 border-0 border-bottom">
                <div class="text-truncate me-2" style="max-width: 75%;">
                  <strong class="font-monospace text-dark">{{ $cpl->kode_cpl }}</strong>
                  <span class="text-muted small ms-2">{{ Str::limit($cpl->deskripsi, 60) }}</span>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                  @if($isCovered)
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2">{{ $cplMks->count() }} MK</span>
                  @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2"><i class="ti ti-x me-1"></i>Belum Tercover</span>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-1 shadow-sm" style="border-radius: 12px; height: 100%;">
        <div class="card-header bg-white border-bottom py-3 px-4">
          <h6 class="fw-bold mb-0 text-dark"><i class="ti ti-alert-triangle text-warning me-2"></i>Status Mata Kuliah Tanpa Pemetaan</h6>
        </div>
        <div class="card-body p-0" style="max-height: 250px; overflow-y: auto;">
          <div class="list-group list-group-flush">
            @php $noMapCount = 0; @endphp
            @foreach($courses as $kmk)
              @php $isMapped = isset($mappedMkIds[$kmk->id]); @endphp
              @if(!$isMapped)
                @php $noMapCount++; @endphp
                <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-4 border-0 border-bottom">
                  <div class="text-truncate me-2">
                    <strong class="font-monospace text-muted">{{ $kmk->mataKuliah->mk_kode }}</strong>
                    <span class="text-dark small ms-2 fw-semibold">{{ $kmk->mataKuliah->mk_nama }}</span>
                    <span class="badge bg-light border text-muted ms-2">Semester {{ $kmk->semester_anjuran }}</span>
                  </div>
                  <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2">Tidak mendukung CPL</span>
                </div>
              @endif
            @endforeach
            @if($noMapCount === 0)
              <div class="p-5 text-center text-muted small">
                <i class="ti ti-check-circle text-success fs-1 d-block mb-1"></i>
                Semua mata kuliah berkontribusi pada pencapaian CPL Program Studi.
              </div>
            @endif
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
        Pemetaan Detail CPL–MK
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
            <span class="font-monospace text-muted small" id="drawer-mk-kode">IF101</span>
            <strong class="text-dark d-block" id="drawer-mk-nama" style="font-size:12.5px;">Nama Mata Kuliah</strong>
          </div>
        </div>

        {{-- Form inputs --}}
        <form id="matrix-cell-form">
          <input type="hidden" id="form-cpl-id">
          <input type="hidden" id="form-kmk-id">
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
              <input type="number" id="input-bobot" class="form-control" placeholder="30.00" min="0" max="100" step="0.01">
              <span class="input-group-text">%</span>
            </div>
            <p class="text-muted small mt-1 mb-0" style="font-size: 10px;">Bobot numerik seberapa besar porsi mata kuliah ini untuk CPL target.</p>
          </div>

          <!-- CPMK Ref Dropdown -->
          <div class="mb-3">
            <label class="form-label fw-bold text-dark small" for="select-cpmk">CPMK Referensi (Opsional)</label>
            <select id="select-cpmk" class="form-select">
              <option value="">-- Pilih CPMK --</option>
            </select>
            <p class="text-muted small mt-1 mb-0" style="font-size: 10px;">Menghubungkan sel pemetaan ke item CPMK spesifik di mata kuliah ini.</p>
          </div>

          <!-- Keterangan -->
          <div class="mb-3">
            <label class="form-label fw-bold text-dark small" for="textarea-keterangan">Keterangan Relasi</label>
            <textarea id="textarea-keterangan" class="form-control small" rows="2" placeholder="Tulis catatan atau argumen justifikasi pemetaan..."></textarea>
          </div>

          <!-- Audit Reason -->
          <div class="mb-3">
            <label class="form-label fw-bold text-dark small" for="textarea-reason">Alasan Perubahan (Audit Trail)</label>
            <textarea id="textarea-reason" class="form-control small" rows="1" placeholder="Opsional"></textarea>
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

// CPMK data map per Mata Kuliah (mk_id)
const cpmkMap = @json($cpmks);

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

// Track selected cells in bulk mode
let selectedBulkCells = [];

document.getElementById('bulkModeSwitch')?.addEventListener('change', function() {
  const isBulk = this.checked;
  // Clear selections
  selectedBulkCells.forEach(cell => cell.style.outline = '');
  selectedBulkCells = [];
  toggleBulkButtons();
  
  if (isBulk) {
    showFlash("Bulk mode diaktifkan. Pilih beberapa sel dengan mengkliknya lalu pilih tindakan bulk di atas.", "info");
  }
});

function toggleBulkButtons() {
  const bulkMode = document.getElementById('bulkModeSwitch')?.checked;
  const count = selectedBulkCells.length;
  const clearBtn = document.querySelector('.btn-bulk-clear');
  const assignDropdown = document.querySelector('.btn-bulk-assign-dropdown');
  
  if (bulkMode && count > 0) {
    clearBtn?.classList.remove('d-none');
    assignDropdown?.classList.remove('d-none');
  } else {
    clearBtn?.classList.add('d-none');
    assignDropdown?.classList.add('d-none');
  }
}

// Click Matrix Cells
document.querySelectorAll('.matrix-cell-node').forEach(cell => {
  cell.addEventListener('click', function() {
    const bulkMode = document.getElementById('bulkModeSwitch')?.checked;
    
    if (bulkMode) {
      if (isReadOnly) return;
      const idx = selectedBulkCells.indexOf(cell);
      if (idx > -1) {
        selectedBulkCells.splice(idx, 1);
        cell.style.outline = '';
      } else {
        selectedBulkCells.push(cell);
        cell.style.outline = '3px solid #6f42c1';
      }
      toggleBulkButtons();
      return;
    }

    // Single click: open side drawer
    activeCellNode = cell;
    openCellDrawer(cell);
  });
});

function openCellDrawer(cell) {
  const cplId = cell.dataset.cplId;
  const cplKode = cell.dataset.cplKode;
  const cplDeskripsi = cell.dataset.cplDeskripsi;
  const kmkId = cell.dataset.kmkId;
  const mkKode = cell.dataset.mkKode;
  const mkNama = cell.dataset.mkNama;
  const mkId = cell.dataset.mkId;
  
  const cellId = cell.dataset.cellId;
  const tingkat = cell.dataset.tingkat;
  const bobot = cell.dataset.bobot;
  const cpmkRef = cell.dataset.cpmkRef;
  const keterangan = cell.dataset.keterangan;

  // Set header values
  document.getElementById('drawer-cpl-kode').textContent = cplKode;
  document.getElementById('drawer-cpl-desc').textContent = cplDeskripsi;
  document.getElementById('drawer-mk-kode').textContent = mkKode;
  document.getElementById('drawer-mk-nama').textContent = mkNama;

  // Set form values
  document.getElementById('form-cpl-id').value = cplId;
  document.getElementById('form-kmk-id').value = kmkId;
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

  // Populate CPMK select options
  const cpmkSelect = document.getElementById('select-cpmk');
  cpmkSelect.innerHTML = '<option value="">-- Pilih CPMK --</option>';
  const courseCpmks = cpmkMap[mkId] || [];
  courseCpmks.forEach(c => {
    const opt = document.createElement('option');
    opt.value = c.id;
    opt.textContent = c.cpmk_kode + ' – ' + c.cpmk_deskripsi.substring(0, 50) + (c.cpmk_deskripsi.length > 50 ? '...' : '');
    if (cpmkRef && parseInt(cpmkRef) === c.id) {
      opt.selected = true;
    }
    cpmkSelect.appendChild(opt);
  });

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
    const data = await apiCall(`/references/curiculum/${kurKode}/matrix/history/${cellId}`, 'GET');
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
          if (h.cpmk_lama !== h.cpmk_baru) details += `CPMK: ${h.cpmk_lama || '-'} → <strong>${h.cpmk_baru || '-'}</strong>. `;
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
  const kmkId = document.getElementById('form-kmk-id').value;
  
  let tingkat = null;
  if (currentSkema === 'KUALITATIF') {
    const checked = document.querySelector('input[name="tingkat_kontribusi"]:checked');
    tingkat = checked ? checked.value : null;
  }
  
  const bobot = document.getElementById('input-bobot').value;
  const cpmkRef = document.getElementById('select-cpmk').value;
  const keterangan = document.getElementById('textarea-keterangan').value;
  const reason = document.getElementById('textarea-reason').value;

  const payload = {
    id_cpl: cplId,
    id_kmk: parseInt(kmkId),
    tingkat_kontribusi: tingkat,
    bobot_kontribusi: bobot ? parseFloat(bobot) : null,
    id_cpmk_ref: cpmkRef ? parseInt(cpmkRef) : null,
    keterangan: keterangan,
    keterangan_perubahan: reason
  };

  const data = await apiCall(`/references/curiculum/${kurKode}/matrix/update-cell`, 'POST', payload);
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
  const kmkId = document.getElementById('form-kmk-id').value;
  const reason = document.getElementById('textarea-reason').value || 'Penghapusan manual';

  if (!confirm('Apakah Anda yakin ingin menghapus pemetaan CPL ke MK ini?')) return;

  const payload = {
    id_cpl: cplId,
    id_kmk: parseInt(kmkId),
    tingkat_kontribusi: 'HAPUS',
    keterangan_perubahan: reason
  };

  const data = await apiCall(`/references/curiculum/${kurKode}/matrix/update-cell`, 'POST', payload);
  if (data.success) {
    showFlash(data.message);
    drawer.hide();
    setTimeout(() => location.reload(), 800);
  } else {
    showFlash(data.message, 'danger');
  }
});

// ==========================================
// BULK OPERATION ACTIONS
// ==========================================
document.querySelectorAll('.btn-bulk-assign-val').forEach(btn => {
  btn.addEventListener('click', async function() {
    if (selectedBulkCells.length === 0 || isReadOnly) return;
    const value = this.dataset.val;
    
    if (!confirm(`Terapkan tingkat kontribusi "${value}" ke ${selectedBulkCells.length} sel terpilih?`)) return;
    
    let processed = 0;
    let failed = 0;
    
    for (let cell of selectedBulkCells) {
      const payload = {
        id_cpl: cell.dataset.cplId,
        id_kmk: parseInt(cell.dataset.kmkId),
        tingkat_kontribusi: value,
        keterangan_perubahan: 'Bulk Operation assign'
      };
      
      const res = await apiCall(`/references/curiculum/${kurKode}/matrix/update-cell`, 'POST', payload);
      if (res.success) processed++;
      else failed++;
    }
    
    showFlash(`Bulk assign berhasil: ${processed} terupdate, ${failed} gagal.`);
    setTimeout(() => location.reload(), 1000);
  });
});

document.querySelector('.btn-bulk-clear')?.addEventListener('click', async function() {
  if (selectedBulkCells.length === 0 || isReadOnly) return;
  if (!confirm(`Hapus pemetaan untuk ${selectedBulkCells.length} sel terpilih?`)) return;
  
  let processed = 0;
  let failed = 0;
  
  for (let cell of selectedBulkCells) {
    const payload = {
      id_cpl: cell.dataset.cplId,
      id_kmk: parseInt(cell.dataset.kmkId),
      tingkat_kontribusi: 'HAPUS',
      keterangan_perubahan: 'Bulk Operation clear'
    };
    
    const res = await apiCall(`/references/curiculum/${kurKode}/matrix/update-cell`, 'POST', payload);
    if (res.success) processed++;
    else failed++;
  }
  
  showFlash(`Bulk clear berhasil: ${processed} terhapus, ${failed} gagal.`);
  setTimeout(() => location.reload(), 1000);
});

// ==========================================
// TOGGLE SCHEMA ACTION
// ==========================================
document.querySelectorAll('.btn-change-schema').forEach(btn => {
  btn.addEventListener('click', async function() {
    if (isReadOnly) return;
    const targetSkema = this.dataset.schema;
    if (targetSkema === currentSkema) return;
    
    let msg = `Ganti skema pemetaan kurikulum ke ${targetSkema}?`;
    if (targetSkema === 'KUANTITATIF') {
      msg += "\n\nSetiap tingkat kontribusi T/S/R yang terdaftar akan dikonversi otomatis ke bobot default: Tinggi=50%, Sedang=30%, Rendah=20%.";
    }
    
    if (!confirm(msg)) return;
    
    const data = await apiCall(`/references/curiculum/${kurKode}/matrix/toggle-schema`, 'POST', {
      skema: targetSkema
    });
    
    if (data.success) {
      showFlash(data.message);
      setTimeout(() => location.reload(), 800);
    } else {
      showFlash(data.message, 'danger');
    }
  });
});

// ==========================================
// COPY MATRIX ACTION
// ==========================================
document.querySelectorAll('.btn-copy-matrix').forEach(btn => {
  btn.addEventListener('click', async function() {
    if (isReadOnly) return;
    const sourceKode = this.dataset.kur;
    const sourceNama = this.dataset.nama;
    
    if (!confirm(`Apakah Anda yakin ingin menyalin seluruh pemetaan dari kurikulum "${sourceNama}" (${sourceKode})?\n\nPemetaan CPL-MK yang sudah dibuat di kurikulum saat ini akan DIGANTIKAN seluruhnya.`)) return;
    
    const data = await apiCall(`/references/curiculum/${kurKode}/matrix/copy`, 'POST', {
      source_kurikulum_kode: sourceKode
    });
    
    if (data.success) {
      showFlash(data.message);
      setTimeout(() => location.reload(), 1000);
    } else {
      showFlash(data.message, 'danger');
    }
  });
});

// Initialize Tooltips
document.addEventListener('DOMContentLoaded', function () {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
</script>
@endpush

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
  
  /* Sticky first column (Mata Kuliah) */
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

  .matrix-cell-node:focus {
    outline: 3px solid #ffc107;
  }
</style>
