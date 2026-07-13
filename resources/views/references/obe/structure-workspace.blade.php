@extends('layouts.app')

@section('content')
<main class="p-2">

  {{-- ========== HEADER ========== --}}
  <div class="card border-1 mb-3 shadow-xs">
    <div class="card-body p-3 p-md-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
          <h3 class="mb-1 fw-bold text-dark d-flex align-items-center gap-2 flex-wrap">
            <i class="ti ti-layout-grid text-primary"></i>
            Struktur Kurikulum
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
              <li class="breadcrumb-item"><a href="{{ route('obe.structure.index') }}">Struktur Kurikulum</a></li>
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
                      <button type="button" class="dropdown-item btn-copy-structure" data-kur="{{ $other->kurKode }}" data-nama="{{ $other->kurNama }}">
                        <i class="ti ti-file-text me-2 text-muted"></i>{{ $other->kurNama }}
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
                <a class="dropdown-item" href="{{ route('curiculum.structure.export-excel', $kurikulum->kurKode) }}">
                  <i class="ti ti-file-type-csv me-2 text-success"></i> Ekspor ke Excel
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('curiculum.structure.export-pdf', $kurikulum->kurKode) }}" target="_blank">
                  <i class="ti ti-file-type-pdf me-2 text-danger"></i> Cetak / Simpan PDF
                </a>
              </li>
            </ul>
          </div>
          <a href="{{ route('obe.structure.index') }}" class="btn btn-light border fw-semibold text-dark">
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

  {{-- ========== SKS SUMMARY STATS ========== --}}
  @php
    $totalMkTerpasang = $mappedKmk->count();
    $totalSksTerpasang = $mappedKmk->sum(fn($kmk) => $kmk->sks_override ?? ($kmk->mataKuliah->mk_sks_total ?? 0));
    $targetSks = $kurikulum->kurSksLulus ?? 144;
    $pct = $targetSks > 0 ? min(100, round(($totalSksTerpasang / $targetSks) * 100)) : 0;
  @endphp
  <div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
      <div class="card border-1 shadow-sm" style="border-radius: 10px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background:#e3f2fd;"><i class="ti ti-books text-primary fs-3"></i></div>
          <div>
            <div class="text-muted small fw-semibold">Total MK</div>
            <div class="h4 mb-0 fw-bold">{{ $totalMkTerpasang }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-1 shadow-sm" style="border-radius: 10px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background:#e8f5e9;"><i class="ti ti-stack text-success fs-3"></i></div>
          <div>
            <div class="text-muted small fw-semibold">SKS Terpasang</div>
            <div class="h4 mb-0 fw-bold">{{ $totalSksTerpasang }} <span class="fs-6 text-muted fw-normal">/ {{ $targetSks }}</span></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-1 shadow-sm" style="border-radius: 10px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background:#fff3e0;"><i class="ti ti-category text-warning fs-3"></i></div>
          <div>
            <div class="text-muted small fw-semibold">Kelompok MK</div>
            <div class="h4 mb-0 fw-bold">{{ $kelompoks->count() }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-1 shadow-sm" style="border-radius: 10px;">
        <div class="card-body p-3">
          <div class="text-muted small fw-semibold mb-1">Kelengkapan SKS</div>
          <div class="d-flex align-items-center gap-2">
            <div class="progress flex-grow-1" style="height: 8px; border-radius: 4px;">
              <div class="progress-bar {{ $pct >= 100 ? 'bg-success' : ($pct >= 75 ? 'bg-primary' : 'bg-warning') }}"
                   style="width: {{ $pct }}%; border-radius: 4px;"></div>
            </div>
            <span class="fw-bold small">{{ $pct }}%</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ========== MAIN LAYOUT: BOARD + SIDEBAR ========== --}}
  <div class="d-flex gap-3 align-items-start">

    {{-- ===== SEMESTER BOARD (SCROLLABLE) ===== --}}
    <div class="flex-grow-1 overflow-hidden">
      <div class="card border-1 shadow-sm" style="border-radius: 12px;">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
          <h6 class="fw-bold mb-0 text-dark"><i class="ti ti-layout-columns me-2 text-primary"></i>Semester Board</h6>
          @if(!$isReadOnly)
            <div class="d-flex gap-2">
              <button class="btn btn-sm btn-outline-primary fw-semibold" id="btnManageKelompok" data-bs-toggle="modal" data-bs-target="#kelompokModal">
                <i class="ti ti-category me-1"></i> Kelola Kelompok
              </button>
            </div>
          @endif
        </div>
        <div class="card-body p-0">
          <!-- Horizontal scroll wrapper -->
          <div class="overflow-auto" style="padding: 16px;">
            <div class="d-flex gap-3" style="min-width: max-content;" id="semester-board">

              @foreach($semesters as $semester)
                @php
                  $semMks = $mappedKmk->where('semester_anjuran', $semester->nomor_semester);
                  $semSks = $semMks->sum(fn($kmk) => $kmk->sks_override ?? ($kmk->mataKuliah->mk_sks_total ?? 0));
                  $bebanStatus = 'normal';
                  $bebanColor = 'success';
                  if ($semSks < ($semester->sks_minimum ?? 12)) { $bebanStatus = 'Ringan'; $bebanColor = 'secondary'; }
                  elseif ($semSks > ($semester->sks_maksimum_ipk_tinggi ?? 26)) { $bebanStatus = 'Overload'; $bebanColor = 'danger'; }
                  elseif ($semSks > ($semester->sks_maksimum ?? 24)) { $bebanStatus = 'Padat'; $bebanColor = 'warning'; }
                  else { $bebanStatus = 'Normal'; $bebanColor = 'success'; }
                @endphp
                <div class="semester-column" data-semester="{{ $semester->nomor_semester }}" style="width: 210px; min-width: 210px;">
                  {{-- Column Header --}}
                  <div class="card border-1 mb-2" style="border-radius: 10px; background: #f8fafd;">
                    <div class="card-body p-2 text-center">
                      <div class="fw-bold text-dark small">{{ $semester->label_semester ?: 'Semester '.$semester->nomor_semester }}</div>
                      <div class="text-muted" style="font-size: 10px;">{{ $semester->jenis_semester ?? '' }}</div>
                      <div class="mt-1 d-flex align-items-center justify-content-center gap-1">
                        <span class="badge bg-{{ $bebanColor }}-subtle text-{{ $bebanColor }} border border-{{ $bebanColor }}-subtle px-2" style="font-size: 10px;">
                          <span class="semester-sks-display" data-semester="{{ $semester->nomor_semester }}">{{ $semSks }}</span> SKS · {{ $bebanStatus }}
                        </span>
                      </div>
                    </div>
                  </div>

                  {{-- MK Drop Zone --}}
                  <div class="mk-drop-zone d-flex flex-column gap-2"
                       data-semester="{{ $semester->nomor_semester }}"
                       style="min-height: 200px; border: 2px dashed #dee2e6; border-radius: 10px; padding: 8px; transition: background 0.15s, border-color 0.15s;">

                    @foreach($semMks->sortBy('urutan_dalam_semester') as $kmk)
                      @php
                        $sks = $kmk->sks_override ?? ($kmk->mataKuliah->mk_sks_total ?? 0);
                        $warna = $kmk->kelompokMk->warna_ui ?? '#6c757d';
                        $prereqCount = $kmk->prasyarats->count();
                      @endphp
                      <div class="mk-chip draggable-mk"
                           data-kmk-id="{{ $kmk->id }}"
                           data-mk-id="{{ $kmk->mk_id }}"
                           data-semester="{{ $kmk->semester_anjuran }}"
                           draggable="{{ $isReadOnly ? 'false' : 'true' }}"
                           style="background: #fff; border-radius: 8px; border: 1.5px solid {{ $warna }}40; padding: 8px 10px; cursor: {{ $isReadOnly ? 'default' : 'grab' }}; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
                        <div class="d-flex align-items-start justify-content-between gap-1">
                          <div class="flex-grow-1" style="min-width: 0;">
                            <div class="d-flex align-items-center gap-1 mb-0.5">
                              <span class="d-inline-block" style="width: 8px; height: 8px; border-radius: 50%; background: {{ $warna }}; flex-shrink: 0;"></span>
                              <span class="font-monospace text-muted" style="font-size: 9px; letter-spacing: 0.3px;">{{ $kmk->mataKuliah->mk_kode ?? '-' }}</span>
                            </div>
                            <div class="fw-semibold text-dark" style="font-size: 11.5px; line-height: 1.3; word-break: break-word;">
                              {{ Str::limit($kmk->mataKuliah->mk_nama ?? '-', 38) }}
                            </div>
                            <div class="d-flex align-items-center gap-1 mt-1 flex-wrap">
                              <span class="badge px-1.5" style="font-size: 9px; background: {{ $warna }}20; color: {{ $warna }}; border: 1px solid {{ $warna }}40;">{{ $kmk->kelompokMk->kode_kelompok ?? '?' }}</span>
                              <span class="badge bg-light text-dark border" style="font-size: 9px;">{{ $sks }} SKS</span>
                              @if($prereqCount > 0)
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle" style="font-size: 9px;" title="{{ $prereqCount }} Prasyarat">
                                  <i class="ti ti-arrow-forward-up"></i>{{ $prereqCount }}
                                </span>
                              @endif
                            </div>
                          </div>
                          @if(!$isReadOnly)
                            <div class="d-flex flex-column gap-1" style="flex-shrink: 0;">
                              <button class="btn btn-xs p-0 border-0 text-muted btn-manage-prereq"
                                      data-kmk-id="{{ $kmk->id }}"
                                      data-mk-nama="{{ $kmk->mataKuliah->mk_nama ?? '-' }}"
                                      title="Kelola Prasyarat">
                                <i class="ti ti-arrows-shuffle" style="font-size: 12px;"></i>
                              </button>
                              <button class="btn btn-xs p-0 border-0 text-danger btn-remove-mk"
                                      data-kmk-id="{{ $kmk->id }}"
                                      data-mk-nama="{{ $kmk->mataKuliah->mk_nama ?? '-' }}"
                                      title="Hapus dari Kurikulum">
                                <i class="ti ti-x" style="font-size: 12px;"></i>
                              </button>
                            </div>
                          @endif
                        </div>
                      </div>
                    @endforeach

                    @if($semMks->isEmpty())
                      <div class="text-center text-muted py-3 empty-semester-hint" style="font-size: 11px; pointer-events: none;">
                        <i class="ti ti-drag-drop d-block fs-4 mb-1 opacity-30"></i>
                        @if(!$isReadOnly) Drop MK di sini @else Kosong @endif
                      </div>
                    @endif
                  </div>
                </div>
              @endforeach

            </div>{{-- end semester-board --}}
          </div>
        </div>
      </div>

      {{-- ===== KELOMPOK MK ANALYSIS ===== --}}
      <div class="card border-1 shadow-sm mt-3" style="border-radius: 12px;">
        <div class="card-header bg-white border-bottom py-3 px-4">
          <h6 class="fw-bold mb-0 text-dark"><i class="ti ti-chart-bar me-2 text-info"></i>Analisis Distribusi SKS per Kelompok MK</h6>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-light">
                <tr>
                  <th class="px-4 fw-semibold" style="font-size: 12px;">Kelompok</th>
                  <th class="fw-semibold text-center" style="font-size: 12px;">SKS Terpasang</th>
                  <th class="fw-semibold text-center" style="font-size: 12px;">SKS Minimum</th>
                  <th class="fw-semibold text-center" style="font-size: 12px;">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($kelompoks as $kel)
                  @php
                    $kelMks = $mappedKmk->where('kelompok_id', $kel->id);
                    $kelSks = $kelMks->sum(fn($kmk) => $kmk->sks_override ?? ($kmk->mataKuliah->mk_sks_total ?? 0));
                    $terpenuhi = $kelSks >= $kel->sks_minimum;
                  @endphp
                  <tr>
                    <td class="px-4">
                      <div class="d-flex align-items-center gap-2">
                        <span style="width: 10px; height: 10px; border-radius: 50%; background: {{ $kel->warna_ui ?? '#6c757d' }};"></span>
                        <div>
                          <div class="fw-semibold" style="font-size: 13px;">{{ $kel->nama_kelompok }}</div>
                          <div class="text-muted" style="font-size: 10px;">{{ $kel->kode_kelompok }} · {{ $kelMks->count() }} MK</div>
                        </div>
                      </div>
                    </td>
                    <td class="text-center fw-bold">{{ $kelSks }}</td>
                    <td class="text-center text-muted">{{ $kel->sks_minimum }}</td>
                    <td class="text-center">
                      @if($terpenuhi)
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2">✔ Terpenuhi</span>
                      @else
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2">✘ Kurang {{ $kel->sks_minimum - $kelSks }} SKS</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
                <tr class="bg-light fw-bold">
                  <td class="px-4">Total</td>
                  <td class="text-center">{{ $totalSksTerpasang }}</td>
                  <td class="text-center">{{ $targetSks }}</td>
                  <td class="text-center">
                    @if($totalSksTerpasang >= $targetSks)
                      <span class="badge bg-success-subtle text-success border border-success-subtle px-2">✔ Memenuhi Syarat Lulus</span>
                    @else
                      <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2">Kurang {{ $targetSks - $totalSksTerpasang }} SKS</span>
                    @endif
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>{{-- end board column --}}

    {{-- ===== CATALOG SIDEBAR ===== --}}
    <div style="width: 240px; min-width: 240px; flex-shrink: 0;">
      <div class="card border-1 shadow-sm sticky-top" style="border-radius: 12px; top: 70px;">
        <div class="card-header bg-white border-bottom py-3 px-3">
          <h6 class="fw-bold mb-1 text-dark" style="font-size: 13px;"><i class="ti ti-books me-2 text-primary"></i>Katalog Mata Kuliah</h6>
          <p class="text-muted mb-0" style="font-size: 10px;">{{ $catalogCourses->count() }} MK belum ditambahkan</p>
          <input type="text" id="catalog-search" class="form-control form-control-sm mt-2" placeholder="Cari MK...">
        </div>
        <div class="card-body p-0" style="max-height: calc(100vh - 280px); overflow-y: auto;">
          @if($kelompoks->isEmpty())
            <div class="p-3 text-center text-muted small">
              <i class="ti ti-alert-circle d-block fs-4 mb-1"></i>
              Buat kelompok MK terlebih dahulu
            </div>
          @elseif($catalogCourses->isEmpty())
            <div class="p-3 text-center text-muted small">
              <i class="ti ti-check-circle d-block fs-4 mb-1 text-success"></i>
              Semua MK telah ditambahkan
            </div>
          @else
            <div id="catalog-list" class="p-2 d-flex flex-column gap-1">
              @foreach($catalogCourses as $course)
                <div class="catalog-mk-item p-2"
                     data-mk-id="{{ $course->id }}"
                     data-mk-nama="{{ $course->mk_nama }}"
                     data-mk-kode="{{ $course->mk_kode }}"
                     data-sks="{{ $course->mk_sks_total }}"
                     draggable="{{ $isReadOnly ? 'false' : 'true' }}"
                     style="border-radius: 8px; border: 1.5px dashed #dee2e6; background: #f8f9fa; cursor: {{ $isReadOnly ? 'default' : 'grab' }}; transition: background 0.15s;">
                  <div class="fw-semibold text-dark" style="font-size: 11.5px; line-height: 1.3;">
                    {{ Str::limit($course->mk_nama, 36) }}
                  </div>
                  <div class="d-flex align-items-center gap-1 mt-1">
                    <span class="font-monospace text-muted" style="font-size: 9px;">{{ $course->mk_kode }}</span>
                    <span class="badge bg-light border text-dark ms-auto" style="font-size: 9px;">{{ $course->mk_sks_total }} SKS</span>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
        @if(!$isReadOnly && !$kelompoks->isEmpty())
          <div class="card-footer bg-white border-top p-2">
            <div class="text-muted small text-center">
              <i class="ti ti-drag-drop me-1"></i>Drag MK ke semester
            </div>
          </div>
        @endif
      </div>
    </div>

  </div>{{-- end main layout --}}

</main>

{{-- =========================================
  MODAL: KELOLA KELOMPOK MK
========================================== --}}
<div class="modal fade" id="kelompokModal" tabindex="-1" aria-labelledby="kelompokModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom pb-3">
        <h5 class="modal-title fw-bold" id="kelompokModalLabel"><i class="ti ti-category me-2"></i>Kelola Kelompok Mata Kuliah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="table-responsive mb-4">
          <table class="table table-hover align-middle">
            <thead class="bg-light">
              <tr>
                <th style="font-size: 12px;" class="fw-semibold">Kode</th>
                <th style="font-size: 12px;" class="fw-semibold">Nama Kelompok</th>
                <th style="font-size: 12px;" class="fw-semibold text-center">Min SKS</th>
                <th style="font-size: 12px;" class="fw-semibold text-center">Warna</th>
                <th style="font-size: 12px;" class="fw-semibold"></th>
              </tr>
            </thead>
            <tbody>
              @foreach($kelompoks as $kel)
                <tr data-kelompok-id="{{ $kel->id }}">
                  <td class="font-monospace fw-bold small">{{ $kel->kode_kelompok }}</td>
                  <td class="small">{{ $kel->nama_kelompok }}</td>
                  <td class="text-center small">{{ $kel->sks_minimum }}</td>
                  <td class="text-center">
                    <span class="d-inline-block" style="width: 20px; height: 20px; border-radius: 50%; background: {{ $kel->warna_ui ?? '#6c757d' }};"></span>
                  </td>
                  <td class="text-end">
                    <button class="btn btn-xs btn-outline-primary btn-edit-kelompok"
                            data-id="{{ $kel->id }}"
                            data-kode="{{ $kel->kode_kelompok }}"
                            data-nama="{{ $kel->nama_kelompok }}"
                            data-min="{{ $kel->sks_minimum }}"
                            data-max="{{ $kel->sks_maximum }}"
                            data-warna="{{ $kel->warna_ui }}"
                            data-keterangan="{{ $kel->keterangan }}">Edit</button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <hr>
        <h6 class="fw-bold mb-3" id="kelompok-form-title">Tambah Kelompok Baru</h6>
        <form id="kelompok-form">
          <input type="hidden" id="kelompok-edit-id" value="">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label fw-semibold small">Kode Kelompok *</label>
              <input type="text" id="kelompok-kode" class="form-control form-control-sm text-uppercase" placeholder="WPS" maxlength="10" required>
            </div>
            <div class="col-md-5">
              <label class="form-label fw-semibold small">Nama Kelompok *</label>
              <input type="text" id="kelompok-nama" class="form-control form-control-sm" placeholder="Wajib Program Studi" required>
            </div>
            <div class="col-md-2">
              <label class="form-label fw-semibold small">Min SKS *</label>
              <input type="number" id="kelompok-min" class="form-control form-control-sm" placeholder="20" min="0" required>
            </div>
            <div class="col-md-2">
              <label class="form-label fw-semibold small">Warna *</label>
              <input type="color" id="kelompok-warna" class="form-control form-control-sm form-control-color w-100" value="#198754">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold small">Keterangan</label>
              <input type="text" id="kelompok-keterangan" class="form-control form-control-sm" placeholder="Opsional">
            </div>
          </div>
          <div class="d-flex gap-2 mt-3">
            <button type="submit" id="kelompok-submit-btn" class="btn btn-primary fw-semibold">
              <i class="ti ti-plus me-1"></i> Simpan Kelompok
            </button>
            <button type="button" id="kelompok-cancel-edit" class="btn btn-light border fw-semibold d-none">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- =========================================
  MODAL: KELOLA PRASYARAT
========================================== --}}
<div class="modal fade" id="prereqModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom pb-3">
        <h5 class="modal-title fw-bold"><i class="ti ti-arrows-shuffle me-2"></i>Kelola Prasyarat — <span id="prereq-mk-nama" class="text-primary"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <input type="hidden" id="prereq-kmk-id" value="">
        {{-- List existing --}}
        <div id="prereq-list-container" class="mb-4">
          <h6 class="fw-semibold mb-2 text-muted">Prasyarat yang terdaftar:</h6>
          <div id="prereq-list" class="d-flex flex-column gap-2"></div>
        </div>
        <hr>
        <h6 class="fw-bold mb-3">Tambah Prasyarat Baru</h6>
        <form id="prereq-form" class="row g-3">
          <div class="col-md-4">
            <label class="form-label fw-semibold small">Jenis Prasyarat *</label>
            <select id="prereq-jenis" class="form-select form-select-sm" required>
              <option value="PASS">PASS – Harus Lulus</option>
              <option value="TAKEN">TAKEN – Cukup Pernah Ambil</option>
              <option value="COREQ">COREQ – Ko-Requisite</option>
              <option value="CREDITS">CREDITS – SKS Kumulatif</option>
            </select>
          </div>
          <div class="col-md-5" id="prereq-mk-select-col">
            <label class="form-label fw-semibold small">Mata Kuliah Prasyarat *</label>
            <select id="prereq-mk-prasyarat" class="form-select form-select-sm">
              <option value="">-- Pilih MK --</option>
              @foreach($mappedKmk as $mk)
                <option value="{{ $mk->id }}" data-semester="{{ $mk->semester_anjuran }}">
                  Smt {{ $mk->semester_anjuran }} – {{ $mk->mataKuliah->mk_nama ?? '-' }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3" id="prereq-nilai-col">
            <label class="form-label fw-semibold small">Nilai Min</label>
            <input type="text" id="prereq-nilai" class="form-control form-control-sm" placeholder="C">
          </div>
          <div class="col-md-3 d-none" id="prereq-sks-col">
            <label class="form-label fw-semibold small">SKS Kumulatif Min *</label>
            <input type="number" id="prereq-sks-min" class="form-control form-control-sm" placeholder="120">
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold small">Grup Logika</label>
            <select id="prereq-grup" class="form-select form-select-sm">
              <option value="GRP-A">GRP-A (OR dalam grup)</option>
              <option value="GRP-B">GRP-B (AND dengan GRP-A)</option>
              <option value="GRP-C">GRP-C (AND dengan semua)</option>
            </select>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary fw-semibold">
              <i class="ti ti-plus me-1"></i> Tambah Prasyarat
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- =========================================
  MODAL: ADD MK — Choose Kelompok & Semester
========================================== --}}
<div class="modal fade" id="addMkModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom pb-3">
        <h5 class="modal-title fw-bold"><i class="ti ti-plus me-2"></i>Tambahkan MK ke Kurikulum</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3">
          <label class="form-label fw-semibold small">Mata Kuliah</label>
          <div id="add-mk-name" class="form-control bg-light text-muted"></div>
          <input type="hidden" id="add-mk-id">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold small">Semester Anjuran *</label>
          <select id="add-mk-semester" class="form-select">
            @foreach($semesters as $sem)
              <option value="{{ $sem->nomor_semester }}">{{ $sem->label_semester ?: 'Semester '.$sem->nomor_semester }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold small">Kelompok MK *</label>
          <select id="add-mk-kelompok" class="form-select">
            @foreach($kelompoks as $kel)
              <option value="{{ $kel->id }}">{{ $kel->kode_kelompok }} – {{ $kel->nama_kelompok }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer border-top pt-3">
        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary fw-semibold" id="btn-confirm-add-mk">
          <i class="ti ti-plus me-1"></i> Tambahkan
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
const kurKode = '{{ $kurikulum->kurKode }}';
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const isReadOnly = {{ $isReadOnly ? 'true' : 'false' }};

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
// CATALOG SEARCH
// ==========================================
document.getElementById('catalog-search')?.addEventListener('input', function () {
  const term = this.value.toLowerCase();
  document.querySelectorAll('.catalog-mk-item').forEach(item => {
    const nama = item.dataset.mkNama?.toLowerCase() || '';
    const kode = item.dataset.mkKode?.toLowerCase() || '';
    item.style.display = (!term || nama.includes(term) || kode.includes(term)) ? '' : 'none';
  });
});

// ==========================================
// DRAG & DROP
// ==========================================
if (!isReadOnly) {
  let draggedKmkId = null;
  let draggedMkId = null;
  let dragSource = null; // 'board' or 'catalog'
  let pendingCatalogMk = null; // {id, nama, kode}

  // Draggable MK chips
  document.querySelectorAll('.draggable-mk').forEach(chip => {
    chip.addEventListener('dragstart', e => {
      draggedKmkId = chip.dataset.kmkId;
      draggedMkId = null;
      dragSource = 'board';
      chip.style.opacity = '0.5';
      e.dataTransfer.effectAllowed = 'move';
    });
    chip.addEventListener('dragend', e => {
      chip.style.opacity = '1';
    });
  });

  // Catalog MK items
  document.querySelectorAll('.catalog-mk-item').forEach(item => {
    item.addEventListener('dragstart', e => {
      draggedKmkId = null;
      draggedMkId = item.dataset.mkId;
      dragSource = 'catalog';
      pendingCatalogMk = { id: item.dataset.mkId, nama: item.dataset.mkNama, kode: item.dataset.mkKode };
      item.style.opacity = '0.5';
      e.dataTransfer.effectAllowed = 'move';
    });
    item.addEventListener('dragend', e => {
      item.style.opacity = '1';
    });
  });

  // Drop Zones
  document.querySelectorAll('.mk-drop-zone').forEach(zone => {
    zone.addEventListener('dragover', e => {
      e.preventDefault();
      zone.style.background = '#eef4ff';
      zone.style.borderColor = '#0d6efd';
    });
    zone.addEventListener('dragleave', e => {
      zone.style.background = '';
      zone.style.borderColor = '#dee2e6';
    });
    zone.addEventListener('drop', async e => {
      e.preventDefault();
      zone.style.background = '';
      zone.style.borderColor = '#dee2e6';
      const targetSemester = parseInt(zone.dataset.semester);

      if (dragSource === 'board' && draggedKmkId) {
        // Move existing MK
        const data = await apiCall(`/references/curiculum/${kurKode}/structure/update-position`, 'POST', {
          action: 'move',
          kmk_id: parseInt(draggedKmkId),
          semester_anjuran: targetSemester,
          urutan: zone.querySelectorAll('.mk-chip').length + 1
        });
        if (data.success) {
          showFlash(data.message);
          setTimeout(() => location.reload(), 800);
        } else {
          showFlash(data.message, 'danger');
        }
      } else if (dragSource === 'catalog' && pendingCatalogMk) {
        // Add new MK from catalog
        document.getElementById('add-mk-name').textContent = pendingCatalogMk.nama + ' (' + pendingCatalogMk.kode + ')';
        document.getElementById('add-mk-id').value = pendingCatalogMk.id;
        const semSelect = document.getElementById('add-mk-semester');
        if (semSelect) semSelect.value = targetSemester;
        const addModal = new bootstrap.Modal(document.getElementById('addMkModal'));
        addModal.show();
      }
    });
  });

  // Confirm Add MK
  document.getElementById('btn-confirm-add-mk')?.addEventListener('click', async () => {
    const mkId = document.getElementById('add-mk-id').value;
    const semester = parseInt(document.getElementById('add-mk-semester').value);
    const kelompokId = parseInt(document.getElementById('add-mk-kelompok').value);

    if (!mkId || !semester || !kelompokId) return;

    const data = await apiCall(`/references/curiculum/${kurKode}/structure/update-position`, 'POST', {
      action: 'add',
      mk_id: parseInt(mkId),
      semester_anjuran: semester,
      kelompok_id: kelompokId
    });

    bootstrap.Modal.getInstance(document.getElementById('addMkModal'))?.hide();
    if (data.success) {
      showFlash(data.message);
      setTimeout(() => location.reload(), 800);
    } else {
      showFlash(data.message, 'danger');
    }
  });
}

// ==========================================
// REMOVE MK
// ==========================================
document.addEventListener('click', async function (e) {
  const btn = e.target.closest('.btn-remove-mk');
  if (!btn || isReadOnly) return;
  const kmkId = btn.dataset.kmkId;
  const mkNama = btn.dataset.mkNama;
  if (!(await confirmAsync(`Hapus "${mkNama}" dari kurikulum ini? Prasyarat terkait juga akan terhapus.`)) return;

  const data = await apiCall(`/references/curiculum/${kurKode}/structure/update-position`, 'POST', {
    action: 'remove',
    kmk_id: parseInt(kmkId)
  });

  if (data.success) {
    showFlash(data.message);
    setTimeout(() => location.reload(), 800);
  } else {
    showFlash(data.message, 'danger');
  }
});

// ==========================================
// MANAGE PREREQUISITES
// ==========================================
document.addEventListener('click', function (e) {
  const btn = e.target.closest('.btn-manage-prereq');
  if (!btn) return;
  const kmkId = btn.dataset.kmkId;
  const mkNama = btn.dataset.mkNama;
  document.getElementById('prereq-kmk-id').value = kmkId;
  document.getElementById('prereq-mk-nama').textContent = mkNama;
  loadPrereqs(kmkId);
  new bootstrap.Modal(document.getElementById('prereqModal')).show();
});

async function loadPrereqs(kmkId) {
  const list = document.getElementById('prereq-list');
  list.innerHTML = '<div class="text-muted small text-center p-2">Memuat...</div>';

  try {
    const res = await fetch(`/references/curiculum/${kurKode}/structure/prasyarat?kmk_id=${kmkId}`, {
      headers: { 'Accept': 'application/json' }
    });
    const data = await res.json();
    
    if (data.success) {
      list.innerHTML = '';
      if (data.prereqs.length === 0) {
        list.innerHTML = '<div class="text-muted small">Belum ada prasyarat.</div>';
      } else {
        data.prereqs.forEach(p => {
          list.insertAdjacentHTML('beforeend', `
            <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light border gap-2">
              <div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle me-1">${p.jenis}</span>
                <span class="fw-semibold small">${p.mk_nama}</span>
                ${p.mk_semester ? `<span class="text-muted small ms-1">(Smt ${p.mk_semester})</span>` : ''}
                ${p.nilai_min ? `<span class="text-muted small ms-1">min ${p.nilai_min}</span>` : ''}
                ${p.sks_kumulatif_min ? `<span class="text-muted small ms-1">${p.sks_kumulatif_min} SKS</span>` : ''}
                <span class="text-muted small ms-1">[${p.grup_logika || 'GRP-A'}]</span>
              </div>
              <button class="btn btn-xs btn-outline-danger border-0 btn-delete-prereq flex-shrink-0" data-id="${p.id}">
                <i class="ti ti-trash"></i>
              </button>
            </div>`);
        });
      }
    } else {
      list.innerHTML = '<div class="text-danger small">Gagal memuat prasyarat.</div>';
    }
  } catch (err) {
    list.innerHTML = '<div class="text-danger small">Terjadi kesalahan koneksi.</div>';
  }
}

// Submit new prereq
document.getElementById('prereq-form')?.addEventListener('submit', async function (e) {
  e.preventDefault();
  const kmkId = document.getElementById('prereq-kmk-id').value;
  const jenis = document.getElementById('prereq-jenis').value;
  const mkPrereq = document.getElementById('prereq-mk-prasyarat').value;
  const nilai = document.getElementById('prereq-nilai').value;
  const sksMin = document.getElementById('prereq-sks-min').value;
  const grup = document.getElementById('prereq-grup').value;

  const payload = {
    kmk_id: parseInt(kmkId),
    jenis_prasyarat: jenis,
    prasyarat_kmk_id: jenis !== 'CREDITS' ? (parseInt(mkPrereq) || null) : null,
    nilai_min: jenis === 'PASS' ? nilai : null,
    sks_kumulatif_min: jenis === 'CREDITS' ? parseInt(sksMin) : null,
    grup_logika: grup
  };

  const data = await apiCall(`/references/curiculum/${kurKode}/structure/prasyarat`, 'POST', payload);
  if (data.success) {
    showFlash(data.message);
    bootstrap.Modal.getInstance(document.getElementById('prereqModal'))?.hide();
    setTimeout(() => location.reload(), 800);
  } else {
    showFlash(data.message, 'danger');
  }
});

// Delete prereq
document.addEventListener('click', async function (e) {
  const btn = e.target.closest('.btn-delete-prereq');
  if (!btn) return;
  if (!(await confirmAsync('Hapus prasyarat ini?')) return;
  const id = btn.dataset.id;
  const data = await apiCall(`/references/curiculum/${kurKode}/structure/prasyarat/${id}`, 'DELETE', {});
  if (data.success) {
    showFlash(data.message);
    btn.closest('[class^="d-flex"]')?.remove();
    setTimeout(() => location.reload(), 800);
  } else {
    showFlash(data.message, 'danger');
  }
});

// Toggle prereq fields
document.getElementById('prereq-jenis')?.addEventListener('change', function () {
  const jenis = this.value;
  document.getElementById('prereq-mk-select-col').classList.toggle('d-none', jenis === 'CREDITS');
  document.getElementById('prereq-nilai-col').classList.toggle('d-none', jenis !== 'PASS');
  document.getElementById('prereq-sks-col').classList.toggle('d-none', jenis !== 'CREDITS');
});

// ==========================================
// KELOMPOK MANAGEMENT
// ==========================================
document.getElementById('kelompok-form')?.addEventListener('submit', async function (e) {
  e.preventDefault();
  const editId = document.getElementById('kelompok-edit-id').value;
  const payload = {
    kode_kelompok: document.getElementById('kelompok-kode').value,
    nama_kelompok: document.getElementById('kelompok-nama').value,
    sks_minimum: parseInt(document.getElementById('kelompok-min').value),
    warna_ui: document.getElementById('kelompok-warna').value,
    keterangan: document.getElementById('kelompok-keterangan').value,
  };

  let data;
  if (editId) {
    data = await apiCall(`/references/curiculum/${kurKode}/structure/kelompok/${editId}`, 'PUT', payload);
  } else {
    data = await apiCall(`/references/curiculum/${kurKode}/structure/kelompok`, 'POST', payload);
  }

  if (data.success) {
    showFlash(data.message);
    bootstrap.Modal.getInstance(document.getElementById('kelompokModal'))?.hide();
    setTimeout(() => location.reload(), 800);
  } else {
    showFlash(data.message, 'danger');
  }
});

document.addEventListener('click', function (e) {
  const btn = e.target.closest('.btn-edit-kelompok');
  if (!btn) return;
  document.getElementById('kelompok-form-title').textContent = 'Edit Kelompok';
  document.getElementById('kelompok-edit-id').value = btn.dataset.id;
  document.getElementById('kelompok-kode').value = btn.dataset.kode;
  document.getElementById('kelompok-nama').value = btn.dataset.nama;
  document.getElementById('kelompok-min').value = btn.dataset.min;
  document.getElementById('kelompok-warna').value = btn.dataset.warna || '#198754';
  document.getElementById('kelompok-keterangan').value = btn.dataset.keterangan || '';
  document.getElementById('kelompok-submit-btn').innerHTML = '<i class="ti ti-check me-1"></i> Simpan Perubahan';
  document.getElementById('kelompok-cancel-edit').classList.remove('d-none');
});

document.getElementById('kelompok-cancel-edit')?.addEventListener('click', function () {
  document.getElementById('kelompok-form-title').textContent = 'Tambah Kelompok Baru';
  document.getElementById('kelompok-edit-id').value = '';
  document.getElementById('kelompok-form').reset();
  document.getElementById('kelompok-submit-btn').innerHTML = '<i class="ti ti-plus me-1"></i> Simpan Kelompok';
  this.classList.add('d-none');
});

// ==========================================
// COPY STRUCTURE
// ==========================================
document.querySelectorAll('.btn-copy-structure').forEach(btn => {
  btn.addEventListener('click', async function () {
    const sourceKode = this.dataset.kur;
    const sourceNama = this.dataset.nama;
    if (!(await confirmAsync(`Salin seluruh struktur dari "${sourceNama}" ke kurikulum ini? Data semester, kelompok, MK, dan prasyarat yang ada sekarang akan DIGANTIKAN.`)) return;

    const data = await apiCall(`/references/curiculum/${kurKode}/structure/copy`, 'POST', {
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
</script>
@endpush
