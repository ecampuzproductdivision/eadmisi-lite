@extends('layouts.app')

@section('content')
<main class="p-2">

  {{-- ============================================================
       HEADER
       ============================================================ --}}
  <div class="card border-1 mb-3 shadow-xs">
    <div class="card-body p-3 p-md-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
          <h3 class="mb-1 fw-bold text-dark d-flex align-items-center gap-2 flex-wrap">
            <i class="ti ti-git-branch text-primary"></i>
            Prasyarat Mata Kuliah
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
              <li class="breadcrumb-item"><a href="{{ route('obe.prereq.index') }}">Prasyarat Mata Kuliah</a></li>
              <li class="breadcrumb-item active">{{ $kurikulum->kurKode }}</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap">
          <div class="dropdown">
            <button class="btn btn-outline-dark fw-semibold dropdown-toggle" type="button" data-bs-toggle="dropdown">
              <i class="ti ti-download me-1"></i>Ekspor
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
              <li>
                <a class="dropdown-item" href="{{ route('curiculum.prereq.export-excel', $kurikulum->kurKode) }}">
                  <i class="ti ti-file-type-csv me-2 text-success"></i>Ekspor ke Excel
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('curiculum.prereq.export-pdf', $kurikulum->kurKode) }}" target="_blank">
                  <i class="ti ti-file-type-pdf me-2 text-danger"></i>Cetak / Simpan PDF
                </a>
              </li>
            </ul>
          </div>
          <a href="{{ route('obe.prereq.index') }}" class="btn btn-light border fw-semibold text-dark">
            <i class="ti ti-arrow-left me-1"></i>Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- ============================================================
       FLASH MESSAGES
       ============================================================ --}}
  <div id="flash-container">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3">
        <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
  </div>

  {{-- ============================================================
       SUMMARY STATS BAR
       ============================================================ --}}
  <div class="row g-3 mb-3">
    <div class="col-md-3 col-6">
      <div class="card border-1 shadow-sm h-100" style="border-radius:10px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background:#f0fdf4;">
            <i class="ti ti-books text-success fs-3"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold">Total MK</div>
            <div class="h4 mb-0 fw-bold">{{ $totalMk }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card border-1 shadow-sm h-100" style="border-radius:10px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background:#eff6ff;">
            <i class="ti ti-git-branch text-primary fs-3"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold">MK Berprasyarat</div>
            <div class="h4 mb-0 fw-bold">{{ $mkBerprasyarat }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card border-1 shadow-sm h-100" style="border-radius:10px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background:#faf5ff;">
            <i class="ti ti-list-check text-purple fs-3"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold">Total Aturan</div>
            <div class="h4 mb-0 fw-bold">{{ $totalRules }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card border-1 shadow-sm h-100" style="border-radius:10px;">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="p-2 rounded-3" style="background: {{ $dagValid ? '#f0fdf4' : '#fef2f2' }};">
            <i class="ti ti-{{ $dagValid ? 'circle-check text-success' : 'alert-triangle text-danger' }} fs-3"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold">Validasi DAG</div>
            <div class="h5 mb-0 fw-bold text-{{ $dagValid ? 'success' : 'danger' }}">
              {{ $dagValid ? '✔ Valid' : '✘ Siklus!' }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ============================================================
       TAB NAVIGATION
       ============================================================ --}}
  <ul class="nav nav-tabs border-bottom-0 mb-0 gap-1" id="prereqTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active fw-semibold px-4" id="tab-tabel-btn" data-bs-toggle="tab" data-bs-target="#tab-tabel" type="button">
        <i class="ti ti-table me-1"></i>Tabel Prasyarat
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-semibold px-4" id="tab-graph-btn" data-bs-toggle="tab" data-bs-target="#tab-graph" type="button">
        <i class="ti ti-chart-dots-3 me-1"></i>Dependency Graph
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-semibold px-4" id="tab-sim-btn" data-bs-toggle="tab" data-bs-target="#tab-simulator" type="button">
        <i class="ti ti-user-check me-1"></i>Simulasi KRS
      </button>
    </li>
  </ul>

  <div class="tab-content">

    {{-- ============================================================
         TAB 1: TABEL PRASYARAT
         ============================================================ --}}
    <div class="tab-pane fade show active" id="tab-tabel" role="tabpanel">
      <div class="card border-1 shadow-sm" style="border-radius: 0 12px 12px 12px;">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
          <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="ti ti-table text-primary"></i>
            Daftar Mata Kuliah &amp; Konfigurasi Prasyarat
            <span class="small text-muted fw-normal">Klik baris untuk melihat detail · Klik + untuk tambah prasyarat</span>
          </h6>
          @if(!$isReadOnly)
            <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 small">
              <i class="ti ti-pencil me-1"></i>Mode Edit Aktif
            </span>
          @endif
        </div>
        <div class="card-body p-0">

          @foreach($semesters as $sem)
            @php $mkInSmt = $kmkBySemester->get($sem->nomor_semester, collect()); @endphp
            @if($mkInSmt->count() > 0)
              {{-- Semester Header --}}
              <div class="d-flex align-items-center gap-2 px-4 py-2"
                   style="background: linear-gradient(90deg, #1e40af, #3b82f6); color:white;">
                <i class="ti ti-calendar-stats"></i>
                <strong>Semester {{ $sem->nomor_semester }}</strong>
                <span class="opacity-75 small">— {{ $sem->label_semester }}</span>
                <span class="ms-auto badge bg-white text-primary px-2 py-1 small">{{ $mkInSmt->count() }} MK</span>
              </div>

              <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" style="min-width:700px;">
                  <thead class="table-light" style="font-size:11px;">
                    <tr>
                      <th style="width:40px;"></th>
                      <th style="width:100px;">Kode MK</th>
                      <th>Nama Mata Kuliah</th>
                      <th style="width:60px;" class="text-center">SKS</th>
                      <th>Ekspresi Prasyarat</th>
                      <th style="width:60px;" class="text-center">Aturan</th>
                      @if(!$isReadOnly)
                        <th style="width:60px;" class="text-center">Aksi</th>
                      @endif
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($mkInSmt as $kmk)
                      @php
                        $rules = $kmk->prasyarats;
                        $hasRules = $rules->count() > 0;

                        // Build expression text
                        $grouped = $rules->groupBy('grup_logika');
                        $exprParts = [];
                        foreach ($grouped as $grp => $items) {
                          $parts = $items->map(function($r) {
                            $pNama = optional(optional($r->prasyaratKurikulumMataKuliah)->mataKuliah)->mk_kode ?? '';
                            return match($r->jenis_prasyarat) {
                              'PASS'    => 'Lulus ' . $pNama . ($r->nilai_min ? ' (min '.$r->nilai_min.')' : ''),
                              'TAKEN'   => 'Pernah ambil ' . $pNama,
                              'COREQ'   => 'Bersamaan ' . $pNama,
                              'CREDITS' => 'SKS ≥ ' . ($r->sks_kumulatif_min ?? '?'),
                              default   => '?',
                            };
                          })->implode(' ATAU ');
                          $exprParts[] = $parts;
                        }
                        $expression = implode(' DAN ', $exprParts);
                      @endphp
                      {{-- Main MK row --}}
                      <tr class="kmk-row {{ $hasRules ? 'table-row-has-prereq' : '' }}"
                          data-kmk="{{ $kmk->id }}"
                          style="cursor: {{ $hasRules ? 'pointer' : 'default' }};"
                          onclick="{{ $hasRules ? 'togglePrereqDetail('.$kmk->id.')' : '' }}">
                        <td class="text-center">
                          @if($hasRules)
                            <i class="ti ti-chevron-right text-muted prereq-toggle-icon" id="icon-{{ $kmk->id }}" style="transition: transform 0.2s;"></i>
                          @else
                            <i class="ti ti-minus text-muted opacity-25"></i>
                          @endif
                        </td>
                        <td>
                          <span class="font-monospace fw-semibold text-dark small">{{ $kmk->mataKuliah->mk_kode ?? '-' }}</span>
                        </td>
                        <td>
                          <div class="fw-semibold text-dark small">{{ $kmk->mataKuliah->mk_nama ?? '-' }}</div>
                          @if($kmk->kelompokMk)
                            <span class="badge px-2 py-0" style="font-size:9px; background:{{ $kmk->kelompokMk->warna_ui ?? '#6c757d' }}20; color:{{ $kmk->kelompokMk->warna_ui ?? '#6c757d' }}; border: 1px solid {{ $kmk->kelompokMk->warna_ui ?? '#6c757d' }}40;">
                              {{ $kmk->kelompokMk->kode_kelompok ?? '' }}
                            </span>
                          @endif
                        </td>
                        <td class="text-center fw-bold text-dark small">{{ $kmk->sks_override ?? $kmk->mataKuliah->mk_sks_total ?? 0 }}</td>
                        <td>
                          @if($hasRules)
                            <span class="text-dark small" style="font-size:12px;">
                              <i class="ti ti-git-branch text-primary me-1"></i>{{ $expression }}
                            </span>
                          @else
                            <span class="text-muted small fst-italic">Tidak ada prasyarat</span>
                          @endif
                        </td>
                        <td class="text-center">
                          @if($hasRules)
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $rules->count() }}</span>
                          @else
                            <span class="text-muted small">—</span>
                          @endif
                        </td>
                        @if(!$isReadOnly)
                          <td class="text-center" onclick="event.stopPropagation();">
                            <button class="btn btn-sm btn-outline-primary px-2 py-1"
                                    onclick="openAddPrereq({{ $kmk->id }}, '{{ addslashes($kmk->mataKuliah->mk_nama ?? '') }}', {{ $kmk->semester_anjuran }})"
                                    title="Tambah Prasyarat">
                              <i class="ti ti-plus" style="font-size:14px;"></i>
                            </button>
                          </td>
                        @endif
                      </tr>

                      {{-- Expandable detail row --}}
                      @if($hasRules)
                        <tr id="detail-{{ $kmk->id }}" class="prereq-detail-row" style="display:none;">
                          <td colspan="{{ $isReadOnly ? 6 : 7 }}" class="p-0">
                            <div class="p-3" style="background: linear-gradient(90deg, #f8fafc, #f1f5f9); border-left: 3px solid #3b82f6;">
                              <div class="fw-semibold text-primary small mb-2">
                                <i class="ti ti-sitemap me-1"></i>Detail Aturan Prasyarat — {{ $kmk->mataKuliah->mk_kode ?? '' }} {{ $kmk->mataKuliah->mk_nama ?? '' }}
                              </div>
                              {{-- Logic group display --}}
                              @php
                                $groupedRules = $rules->groupBy('grup_logika');
                                $totalGroups = $groupedRules->count();
                              @endphp
                              <div class="d-flex flex-wrap gap-3">
                                @foreach($groupedRules as $grpKey => $grpItems)
                                  <div class="rounded-3 border p-3" style="background:white; min-width: 220px; flex:1;">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                      <span class="badge bg-primary font-monospace px-3 py-1" style="font-size:11px;">
                                        {{ is_null($grpKey) ? 'GRP (tunggal)' : $grpKey }}
                                      </span>
                                      @if($grpItems->count() > 1)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle small">OR dalam grup</span>
                                      @endif
                                      @if($totalGroups > 1)
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle small">AND antar grup</span>
                                      @endif
                                    </div>
                                    @foreach($grpItems as $rule)
                                      <div class="d-flex align-items-start gap-2 mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        @php
                                          $jenisConfig = [
                                            'PASS'    => ['color' => 'success', 'icon' => 'ti-circle-check', 'label' => 'Wajib Lulus'],
                                            'TAKEN'   => ['color' => 'secondary', 'icon' => 'ti-clock', 'label' => 'Pernah Ambil'],
                                            'COREQ'   => ['color' => 'warning', 'icon' => 'ti-git-merge', 'label' => 'Ko-Requisit'],
                                            'CREDITS' => ['color' => 'purple', 'icon' => 'ti-star', 'label' => 'SKS Kumulatif'],
                                          ];
                                          $jc = $jenisConfig[$rule->jenis_prasyarat] ?? ['color'=>'secondary','icon'=>'ti-help','label'=>'?'];
                                        @endphp
                                        <i class="ti {{ $jc['icon'] }} text-{{ $jc['color'] }} mt-1"></i>
                                        <div class="flex-grow-1">
                                          <div class="small fw-semibold text-dark">
                                            @if($rule->jenis_prasyarat === 'CREDITS')
                                              SKS Kumulatif ≥ {{ $rule->sks_kumulatif_min }} SKS
                                              <span class="badge bg-light text-muted border ms-1 small">{{ $rule->sks_kumulatif_tipe ?? 'LULUS' }}</span>
                                            @else
                                              {{ optional(optional($rule->prasyaratKurikulumMataKuliah)->mataKuliah)->mk_nama ?? '-' }}
                                              @if($rule->jenis_prasyarat === 'PASS' && $rule->nilai_min)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle ms-1 small">min {{ $rule->nilai_min }}</span>
                                              @endif
                                            @endif
                                          </div>
                                          <div style="font-size:10px;" class="text-muted">
                                            {{ $jc['label'] }}
                                            @if($rule->keterangan)
                                              · {{ $rule->keterangan }}
                                            @endif
                                          </div>
                                        </div>
                                        @if(!$isReadOnly)
                                          <div class="d-flex gap-1" onclick="event.stopPropagation();">
                                            <button class="btn btn-xs btn-outline-secondary p-1"
                                                    onclick="openEditPrereq({{ json_encode($rule) }}, '{{ addslashes($kmk->mataKuliah->mk_nama ?? '') }}')"
                                                    title="Edit" style="line-height:1;">
                                              <i class="ti ti-pencil" style="font-size:12px;"></i>
                                            </button>
                                            <button class="btn btn-xs btn-outline-danger p-1"
                                                    onclick="deletePrereq('{{ $rule->id }}', '{{ $kurikulum->kurKode }}')"
                                                    title="Hapus" style="line-height:1;">
                                              <i class="ti ti-trash" style="font-size:12px;"></i>
                                            </button>
                                          </div>
                                        @endif
                                      </div>
                                    @endforeach
                                  </div>
                                @endforeach
                              </div>
                            </div>
                          </td>
                        </tr>
                      @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          @endforeach

        </div>
      </div>
    </div>{{-- /tab-tabel --}}

    {{-- ============================================================
         TAB 2: DEPENDENCY GRAPH (vis.js)
         ============================================================ --}}
    <div class="tab-pane fade" id="tab-graph" role="tabpanel">
      <div class="card border-1 shadow-sm" style="border-radius: 0 12px 12px 12px;">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
          <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="ti ti-chart-dots-3 text-primary"></i>
            Visualisasi Dependency Graph
            <span class="small text-muted fw-normal">Klik node MK untuk sorot rantai prasyarat</span>
          </h6>
          <div class="d-flex gap-2 flex-wrap align-items-center">
            {{-- Legend --}}
            <div class="d-flex gap-3 small">
              <span><i class="ti ti-circle-filled text-success me-1"></i>PASS</span>
              <span><i class="ti ti-circle-filled text-secondary me-1"></i>TAKEN</span>
              <span><i class="ti ti-circle-filled" style="color:#f97316;" ></i> <span class="ms-1">COREQ</span></span>
              <span><i class="ti ti-circle-filled" style="color:#a855f7;"></i> <span class="ms-1">CREDITS</span></span>
            </div>
            <div class="d-flex gap-1">
              <button class="btn btn-sm btn-outline-secondary" onclick="graphNetwork && graphNetwork.fit()">
                <i class="ti ti-zoom-reset me-1"></i>Reset Zoom
              </button>
              <select id="graph-filter-jenis" class="form-select form-select-sm" style="width:130px;" onchange="applyGraphFilter()">
                <option value="">Semua Jenis</option>
                <option value="PASS">PASS saja</option>
                <option value="TAKEN">TAKEN saja</option>
                <option value="COREQ">COREQ saja</option>
                <option value="CREDITS">CREDITS saja</option>
              </select>
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <div id="graph-loading" class="text-center py-5">
            <div class="spinner-border text-primary"></div>
            <div class="mt-2 text-muted small">Memuat dependency graph…</div>
          </div>
          <div id="prereq-graph" style="height: 600px; display:none;"></div>
          <div id="graph-node-info" class="px-4 py-2 border-top bg-light d-none" style="font-size:12px;">
            <i class="ti ti-info-circle me-1 text-primary"></i>
            <span id="graph-node-info-text"></span>
          </div>
        </div>
      </div>
    </div>{{-- /tab-graph --}}

    {{-- ============================================================
         TAB 3: SIMULASI KRS
         ============================================================ --}}
    <div class="tab-pane fade" id="tab-simulator" role="tabpanel">
      <div class="card border-1 shadow-sm" style="border-radius: 0 12px 12px 12px;">
        <div class="card-header bg-white py-3 px-4">
          <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <i class="ti ti-user-check text-primary"></i>
            Simulasi Kelayakan KRS
            <span class="small text-muted fw-normal">— Masukkan NIM dan pilih MK untuk mengecek kelayakan prasyarat</span>
          </h6>
        </div>
        <div class="card-body">
          <div class="row g-4">

            {{-- Input form --}}
            <div class="col-md-4">
              <div class="card border shadow-none" style="border-radius:10px;">
                <div class="card-body p-4">
                  <h6 class="fw-bold text-dark mb-3"><i class="ti ti-input-search me-2 text-primary"></i>Parameter Simulasi</h6>

                  <div class="mb-3">
                    <label class="form-label fw-semibold small">NIM Mahasiswa</label>
                    <input type="text" id="sim-nim" class="form-control" placeholder="Contoh: 01020220001" maxlength="20">
                    <div class="form-text small">Simulasi menggunakan data transkrip deterministik berbasis NIM.</div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label fw-semibold small">Mata Kuliah yang Ingin Diambil</label>
                    <select id="sim-kmk-id" class="form-select">
                      <option value="">-- Pilih Mata Kuliah --</option>
                      @foreach($semesters as $sem)
                        @php $mkInSmt = $kmkBySemester->get($sem->nomor_semester, collect()); @endphp
                        @if($mkInSmt->count() > 0)
                          <optgroup label="Semester {{ $sem->nomor_semester }}">
                            @foreach($mkInSmt as $kmk)
                              <option value="{{ $kmk->id }}">
                                {{ $kmk->mataKuliah->mk_kode ?? '?' }} — {{ $kmk->mataKuliah->mk_nama ?? '-' }}
                              </option>
                            @endforeach
                          </optgroup>
                        @endif
                      @endforeach
                    </select>
                  </div>

                  <button class="btn btn-primary fw-semibold" onclick="runSimulation()">
                    <i class="ti ti-player-play me-2"></i>Jalankan Simulasi
                  </button>

                  {{-- Disclaimer --}}
                  <div class="mt-3 p-2 rounded" style="background:#fffbeb; border:1px solid #fef08a; font-size:11px;">
                    <i class="ti ti-info-circle text-warning me-1"></i>
                    <strong>Catatan:</strong> Simulasi ini menggunakan data transkrip mock deterministik. Untuk validasi KRS sesungguhnya, gunakan modul Perkuliahan.
                  </div>
                </div>
              </div>
            </div>

            {{-- Simulation Results --}}
            <div class="col-md-8">
              <div id="sim-placeholder" class="text-center py-5 text-muted">
                <i class="ti ti-user-search fs-1 d-block mb-2 opacity-50"></i>
                <span class="fw-semibold d-block">Hasil simulasi akan muncul di sini</span>
                <p class="small">Masukkan NIM dan pilih MK lalu klik Jalankan Simulasi.</p>
              </div>

              <div id="sim-loading" class="text-center py-5 d-none">
                <div class="spinner-border text-primary"></div>
                <div class="mt-2 text-muted small">Mengevaluasi prasyarat…</div>
              </div>

              <div id="sim-results" class="d-none">
                {{-- Conclusion banner --}}
                <div id="sim-banner" class="rounded-3 p-3 mb-3 d-flex align-items-center gap-3" style="font-size:14px;">
                  <i id="sim-banner-icon" class="fs-3"></i>
                  <div>
                    <div id="sim-banner-title" class="fw-bold"></div>
                    <div id="sim-banner-sub" class="small mt-1"></div>
                  </div>
                </div>

                {{-- Student info --}}
                <div id="sim-student-info" class="mb-3 d-flex gap-3 flex-wrap"></div>

                {{-- Groups / conditions --}}
                <div id="sim-groups"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>{{-- /tab-simulator --}}

  </div>{{-- /tab-content --}}

</main>

{{-- ============================================================
     OFFCANVAS: ADD / EDIT PREREQUISITE FORM
     ============================================================ --}}
@if(!$isReadOnly)
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasPrereq" style="width: min(480px, 100vw);">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title fw-bold" id="offcanvas-title">
      <i class="ti ti-git-branch me-2 text-primary"></i>
      <span id="offcanvas-title-text">Tambah Prasyarat</span>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <form id="prereq-form">
      @csrf
      <input type="hidden" id="form-prereq-id" value="">
      <input type="hidden" id="form-kmk-id" value="">

      {{-- Target MK info --}}
      <div class="p-3 rounded-3 mb-4" style="background:#eff6ff; border-left:3px solid #3b82f6;">
        <div class="text-muted small fw-semibold mb-1">MK yang membutuhkan prasyarat:</div>
        <div id="form-mk-label" class="fw-bold text-dark"></div>
      </div>

      {{-- Jenis Prasyarat --}}
      <div class="mb-3">
        <label class="form-label fw-semibold">Jenis Prasyarat <span class="text-danger">*</span></label>
        <div class="row g-2">
          @foreach(['PASS' => ['label'=>'Wajib Lulus','sub'=>'MK harus lulus dengan nilai min','color'=>'success','icon'=>'ti-circle-check'],
                    'TAKEN' => ['label'=>'Pernah Ambil','sub'=>'MK harus pernah diambil','color'=>'secondary','icon'=>'ti-clock'],
                    'COREQ' => ['label'=>'Ko-Requisit','sub'=>'Harus diambil bersamaan','color'=>'warning','icon'=>'ti-git-merge'],
                    'CREDITS' => ['label'=>'SKS Kumulatif','sub'=>'Sudah tempuh min N SKS','color'=>'purple','icon'=>'ti-star']] as $kode => $cfg)
            <div class="col-6">
              <label class="d-flex align-items-start gap-2 p-2 rounded-3 border cursor-pointer jenis-option"
                     style="cursor:pointer;" for="jenis-{{ strtolower($kode) }}">
                <input type="radio" class="form-check-input mt-1" name="jenis_prasyarat" id="jenis-{{ strtolower($kode) }}"
                       value="{{ $kode }}" {{ $kode === 'PASS' ? 'checked' : '' }} onchange="onJenisChange()">
                <div>
                  <div class="fw-semibold text-dark small"><i class="ti {{ $cfg['icon'] }} text-{{ $cfg['color'] }} me-1"></i>{{ $cfg['label'] }}</div>
                  <div class="text-muted" style="font-size:10px;">{{ $cfg['sub'] }}</div>
                </div>
              </label>
            </div>
          @endforeach
        </div>
      </div>

      {{-- MK Prasyarat (hidden for CREDITS) --}}
      <div id="field-mk-prasyarat" class="mb-3">
        <label class="form-label fw-semibold">Mata Kuliah Prasyarat <span class="text-danger">*</span></label>
        <select id="form-prasyarat-kmk-id" class="form-select" name="prasyarat_kmk_id">
          <option value="">-- Pilih MK Prasyarat --</option>
          @foreach($semesters as $sem)
            @php $mkInSmt = $kmkBySemester->get($sem->nomor_semester, collect()); @endphp
            @if($mkInSmt->count() > 0)
              <optgroup label="Semester {{ $sem->nomor_semester }}">
                @foreach($mkInSmt as $kmk)
                  <option value="{{ $kmk->id }}"
                          data-semester="{{ $kmk->semester_anjuran }}"
                          data-nama="{{ $kmk->mataKuliah->mk_nama ?? '-' }}">
                    {{ $kmk->mataKuliah->mk_kode ?? '?' }} — {{ $kmk->mataKuliah->mk_nama ?? '-' }}
                  </option>
                @endforeach
              </optgroup>
            @endif
          @endforeach
        </select>
        <div id="mk-prasyarat-warning" class="small text-warning mt-1 d-none">
          <i class="ti ti-alert-triangle me-1"></i>MK ini akan difilter secara otomatis untuk mencegah siklus.
        </div>
      </div>

      {{-- Nilai Min (PASS only) --}}
      <div id="field-nilai-min" class="mb-3">
        <label class="form-label fw-semibold">Nilai Minimum <span class="text-muted small fw-normal">(opsional)</span></label>
        <div class="row g-2">
          <div class="col-7">
            <select class="form-select" name="nilai_min" id="form-nilai-min">
              <option value="">Semua nilai lulus (min D)</option>
              <option value="C">C (minimal C)</option>
              <option value="BC">BC (minimal BC)</option>
              <option value="B">B (minimal B)</option>
              <option value="AB">AB (minimal AB)</option>
              <option value="A">A (minimal A)</option>
            </select>
          </div>
          <div class="col-5">
            <select class="form-select" name="nilai_min_tipe" id="form-nilai-min-tipe">
              <option value="HURUF">Nilai Huruf</option>
              <option value="ANGKA">Nilai Angka</option>
            </select>
          </div>
        </div>
      </div>

      {{-- SKS Min (CREDITS only) --}}
      <div id="field-sks-min" class="mb-3 d-none">
        <label class="form-label fw-semibold">SKS Kumulatif Minimum <span class="text-danger">*</span></label>
        <div class="row g-2">
          <div class="col-7">
            <div class="input-group">
              <input type="number" class="form-control" name="sks_kumulatif_min" id="form-sks-min" placeholder="misal: 120" min="1" max="200">
              <span class="input-group-text">SKS</span>
            </div>
          </div>
          <div class="col-5">
            <select class="form-select" name="sks_kumulatif_tipe" id="form-sks-tipe">
              <option value="LULUS">SKS Lulus</option>
              <option value="TEMPUH">SKS Tempuh</option>
            </select>
          </div>
        </div>
        <div class="form-text small">Berapa SKS yang harus sudah ditempuh/lulus oleh mahasiswa.</div>
      </div>

      {{-- Grup Logika --}}
      <div class="mb-3">
        <label class="form-label fw-semibold">
          Grup Logika
          <span class="badge bg-secondary-subtle text-secondary border ms-1 small">AND / OR</span>
        </label>
        <input type="text" class="form-control font-monospace" name="grup_logika" id="form-grup-logika"
               placeholder="GRP-A" maxlength="20">
        <div class="form-text small" style="font-size:11px;">
          <i class="ti ti-info-circle me-1 text-primary"></i>
          Baris dengan <strong>grup sama</strong> = <span class="text-info fw-bold">OR</span> (cukup salah satu terpenuhi).
          Baris dengan <strong>grup berbeda</strong> = <span class="text-warning fw-bold">AND</span> (semua harus terpenuhi).
        </div>
        {{-- Existing groups quick-pick --}}
        @php
          $existingGroups = collect();
          foreach($allKmk as $k) {
            foreach($k->prasyarats as $r) {
              if($r->grup_logika) $existingGroups->push($r->grup_logika);
            }
          }
          $existingGroups = $existingGroups->unique()->sort()->values();
        @endphp
        @if($existingGroups->count() > 0)
          <div class="mt-2 d-flex flex-wrap gap-1">
            @foreach($existingGroups as $grp)
              <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-0"
                      style="font-size:11px; font-family:monospace;"
                      onclick="document.getElementById('form-grup-logika').value='{{ $grp }}'">
                {{ $grp }}
              </button>
            @endforeach
          </div>
        @endif
      </div>

      {{-- Keterangan --}}
      <div class="mb-4">
        <label class="form-label fw-semibold">Keterangan <span class="text-muted small fw-normal">(opsional)</span></label>
        <textarea class="form-control" name="keterangan" id="form-keterangan" rows="2" placeholder="Penjelasan tambahan aturan prasyarat ini…" maxlength="1000"></textarea>
      </div>

      {{-- Form Buttons --}}
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary fw-semibold flex-grow-1" id="form-submit-btn">
          <i class="ti ti-device-floppy me-2"></i>Simpan Prasyarat
        </button>
        <button type="button" class="btn btn-light border" data-bs-dismiss="offcanvas">Batal</button>
      </div>

      <div id="form-error" class="alert alert-danger mt-3 d-none border-0 shadow-sm" style="white-space:pre-line;"></div>
    </form>
  </div>
</div>
@endif

{{-- ============================================================
     DELETE CONFIRMATION MODAL
     ============================================================ --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow">
      <div class="modal-body text-center p-4">
        <div class="mb-3 text-danger" style="font-size:40px;"><i class="ti ti-trash"></i></div>
        <h6 class="fw-bold">Hapus Prasyarat?</h6>
        <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
      </div>
      <div class="modal-footer border-0 justify-content-center gap-2 pt-0">
        <button class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-danger fw-semibold" id="confirm-delete-btn">
          <i class="ti ti-trash me-1"></i>Ya, Hapus
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ============================================================
     JAVASCRIPT
     ============================================================ --}}
<script src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
<script>
const KODE = '{{ $kurikulum->kurKode }}';
const IS_READ_ONLY = {{ $isReadOnly ? 'true' : 'false' }};
const CSRF = '{{ csrf_token() }}';

// All KMK list for lookups
const KMK_LIST = @json($kmkList);

// ──────────────────────────────────────────────────────────
// ROW TOGGLE
// ──────────────────────────────────────────────────────────
function togglePrereqDetail(kmkId) {
  const row = document.getElementById('detail-' + kmkId);
  const icon = document.getElementById('icon-' + kmkId);
  if (!row) return;
  const isVisible = row.style.display !== 'none';
  row.style.display = isVisible ? 'none' : 'table-row';
  if (icon) icon.style.transform = isVisible ? '' : 'rotate(90deg)';
}

// ──────────────────────────────────────────────────────────
// OFFCANVAS OPEN/CLOSE
// ──────────────────────────────────────────────────────────
const offcanvasEl = document.getElementById('offcanvasPrereq');
const offcanvas = offcanvasEl ? new bootstrap.Offcanvas(offcanvasEl) : null;

function openAddPrereq(kmkId, mkNama, semester) {
  document.getElementById('form-prereq-id').value = '';
  document.getElementById('form-kmk-id').value = kmkId;
  document.getElementById('form-mk-label').textContent = mkNama;
  document.getElementById('offcanvas-title-text').textContent = 'Tambah Prasyarat';
  document.getElementById('prereq-form').reset();
  document.getElementById('form-grup-logika').value = 'GRP-A';
  document.getElementById('form-error').classList.add('d-none');

  // Filter MK dropdown: remove self + courses from same or later semester (for PASS/TAKEN)
  filterMkDropdown(kmkId, semester, 'PASS');
  onJenisChange();
  offcanvas && offcanvas.show();
}

function openEditPrereq(rule, mkNama) {
  document.getElementById('form-prereq-id').value = rule.id;
  document.getElementById('form-kmk-id').value = rule.kmk_id;
  document.getElementById('form-mk-label').textContent = mkNama;
  document.getElementById('offcanvas-title-text').textContent = 'Edit Prasyarat';
  document.getElementById('form-error').classList.add('d-none');

  // Set jenis
  const radioEl = document.getElementById('jenis-' + rule.jenis_prasyarat.toLowerCase());
  if (radioEl) { radioEl.checked = true; }
  onJenisChange();

  document.getElementById('form-prasyarat-kmk-id').value = rule.prasyarat_kmk_id ?? '';
  document.getElementById('form-nilai-min').value = rule.nilai_min ?? '';
  document.getElementById('form-sks-min').value = rule.sks_kumulatif_min ?? '';
  document.getElementById('form-sks-tipe').value = rule.sks_kumulatif_tipe ?? 'LULUS';
  document.getElementById('form-grup-logika').value = rule.grup_logika ?? 'GRP-A';
  document.getElementById('form-keterangan').value = rule.keterangan ?? '';

  filterMkDropdown(rule.kmk_id, null, rule.jenis_prasyarat);
  offcanvas && offcanvas.show();
}

// ──────────────────────────────────────────────────────────
// DYNAMIC FORM FIELDS
// ──────────────────────────────────────────────────────────
function onJenisChange() {
  const jenis = document.querySelector('input[name="jenis_prasyarat"]:checked')?.value;
  document.getElementById('field-mk-prasyarat').classList.toggle('d-none', jenis === 'CREDITS');
  document.getElementById('field-nilai-min').classList.toggle('d-none', jenis !== 'PASS');
  document.getElementById('field-sks-min').classList.toggle('d-none', jenis !== 'CREDITS');

  // Re-filter MK dropdown when jenis changes
  const kmkId = parseInt(document.getElementById('form-kmk-id').value);
  const currentKmk = KMK_LIST.find(k => k.id === kmkId);
  if (currentKmk) filterMkDropdown(kmkId, currentKmk.semester, jenis);
}

function filterMkDropdown(kmkId, semester, jenis) {
  const sel = document.getElementById('form-prasyarat-kmk-id');
  const currentSemester = semester || KMK_LIST.find(k => k.id == kmkId)?.semester;

  Array.from(sel.options).forEach(opt => {
    if (!opt.value) return;
    const optSmt = parseInt(opt.dataset.semester);
    const isSelf = parseInt(opt.value) === parseInt(kmkId);
    let hidden = isSelf;

    if (!hidden && jenis === 'PASS' || jenis === 'TAKEN') {
      hidden = optSmt >= currentSemester;
    } else if (!hidden && jenis === 'COREQ') {
      hidden = optSmt !== currentSemester;
    }

    opt.hidden = hidden;
    opt.disabled = hidden;
  });
}

// ──────────────────────────────────────────────────────────
// FORM SUBMIT (ADD / EDIT)
// ──────────────────────────────────────────────────────────
document.getElementById('prereq-form')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('form-submit-btn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan…';
  document.getElementById('form-error').classList.add('d-none');

  const prereqId = document.getElementById('form-prereq-id').value;
  const isEdit   = prereqId !== '';
  const kmkId    = document.getElementById('form-kmk-id').value;
  const jenis    = document.querySelector('input[name="jenis_prasyarat"]:checked')?.value;
  const url      = isEdit
    ? `/references/curiculum/${KODE}/prereq/${prereqId}/update`
    : `/references/curiculum/${KODE}/prereq/store`;
  const method   = isEdit ? 'PUT' : 'POST';

  const body = {
    _token:              CSRF,
    kmk_id:              kmkId,
    prasyarat_kmk_id:    document.getElementById('form-prasyarat-kmk-id').value || null,
    jenis_prasyarat:     jenis,
    nilai_min:           document.getElementById('form-nilai-min').value || null,
    nilai_min_tipe:      document.getElementById('form-nilai-min-tipe').value || null,
    sks_kumulatif_min:   document.getElementById('form-sks-min').value || null,
    sks_kumulatif_tipe:  document.getElementById('form-sks-tipe').value || null,
    grup_logika:         document.getElementById('form-grup-logika').value || 'GRP-A',
    keterangan:          document.getElementById('form-keterangan').value || null,
  };
  if (isEdit) body._method = 'PUT';

  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
      body: JSON.stringify(body),
    });
    const data = await res.json();
    if (data.success) {
      offcanvas && offcanvas.hide();
      showFlash(data.message, 'success');
      setTimeout(() => location.reload(), 800);
    } else {
      const errEl = document.getElementById('form-error');
      errEl.textContent = data.message || 'Terjadi kesalahan.';
      errEl.classList.remove('d-none');
    }
  } catch(err) {
    const errEl = document.getElementById('form-error');
    errEl.textContent = 'Gagal menghubungi server.';
    errEl.classList.remove('d-none');
  }

  btn.disabled = false;
  btn.innerHTML = '<i class="ti ti-device-floppy me-2"></i>Simpan Prasyarat';
});

// ──────────────────────────────────────────────────────────
// DELETE
// ──────────────────────────────────────────────────────────
let pendingDeleteId = null;
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

function deletePrereq(prereqId, kurKode) {
  pendingDeleteId = prereqId;
  deleteModal.show();
}

document.getElementById('confirm-delete-btn')?.addEventListener('click', async function() {
  if (!pendingDeleteId) return;
  this.disabled = true;
  this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>';

  try {
    const res = await fetch(`/references/curiculum/${KODE}/prereq/${pendingDeleteId}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
      body: JSON.stringify({ _method: 'DELETE', _token: CSRF }),
    });
    const data = await res.json();
    deleteModal.hide();
    if (data.success) {
      showFlash(data.message, 'success');
      setTimeout(() => location.reload(), 600);
    } else {
      showFlash(data.message || 'Gagal menghapus.', 'danger');
    }
  } catch(err) {
    deleteModal.hide();
    showFlash('Gagal menghubungi server.', 'danger');
  }

  this.disabled = false;
  this.innerHTML = '<i class="ti ti-trash me-1"></i>Ya, Hapus';
});

// ──────────────────────────────────────────────────────────
// FLASH MESSAGE HELPER
// ──────────────────────────────────────────────────────────
function showFlash(msg, type = 'success') {
  const container = document.getElementById('flash-container');
  const div = document.createElement('div');
  div.className = `alert alert-${type} alert-dismissible fade show border-0 shadow-sm mb-3`;
  div.innerHTML = `<i class="ti ti-${type==='success'?'circle-check':'alert-circle'} me-2"></i>${msg}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
  container.appendChild(div);
  setTimeout(() => div.remove(), 5000);
}

// ──────────────────────────────────────────────────────────
// VIS.JS DEPENDENCY GRAPH
// ──────────────────────────────────────────────────────────
let graphNetwork = null;
let allGraphNodes = [], allGraphEdges = [];

document.getElementById('tab-graph-btn')?.addEventListener('shown.bs.tab', function() {
  if (graphNetwork) return; // Already initialized
  loadGraph();
});

async function loadGraph() {
  document.getElementById('graph-loading').style.display = 'block';
  document.getElementById('prereq-graph').style.display = 'none';

  try {
    const res = await fetch(`/references/curiculum/${KODE}/prereq/graph-data`, {
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    });
    const data = await res.json();
    allGraphNodes = data.nodes;
    allGraphEdges = data.edges;
    renderGraph(allGraphNodes, allGraphEdges);
  } catch(e) {
    document.getElementById('graph-loading').innerHTML = '<div class="text-danger p-4"><i class="ti ti-alert-circle me-1"></i>Gagal memuat data graph.</div>';
  }
}

function renderGraph(nodes, edges) {
  document.getElementById('graph-loading').style.display = 'none';
  const container = document.getElementById('prereq-graph');
  container.style.display = 'block';

  const nodeDataset = new vis.DataSet(nodes);
  const edgeDataset = new vis.DataSet(edges);

  const options = {
    layout: {
      hierarchical: {
        enabled: true,
        direction: 'LR',
        sortMethod: 'directed',
        levelSeparation: 180,
        nodeSpacing: 120,
      }
    },
    nodes: {
      shape: 'box',
      borderWidth: 2,
      borderWidthSelected: 3,
      shadow: { enabled: true, color: 'rgba(0,0,0,0.15)', size:8, x:3, y:3 },
      font: { face: 'Inter, sans-serif', size: 12, multi: true },
      chosen: {
        node: function(values) {
          values.shadowSize = 15;
          values.borderWidth = 3;
        }
      }
    },
    edges: {
      width: 2,
      smooth: { type: 'curvedCW', roundness: 0.2 },
      font: { size: 10, align: 'middle', strokeWidth: 3, strokeColor: '#ffffff' },
      selectionWidth: 3,
    },
    interaction: {
      hover: true,
      tooltipDelay: 100,
      navigationButtons: false,
      keyboard: true,
      multiselect: false,
    },
    physics: { enabled: false },
  };

  graphNetwork = new vis.Network(container, { nodes: nodeDataset, edges: edgeDataset }, options);

  // Click to highlight chain
  graphNetwork.on('click', function(params) {
    if (params.nodes.length > 0) {
      const nodeId = params.nodes[0];
      const node = allGraphNodes.find(n => n.id === nodeId);
      if (node) {
        const info = document.getElementById('graph-node-info');
        const text = document.getElementById('graph-node-info-text');
        text.textContent = `${node.mk_kode} — ${node.mk_nama} (Semester ${node.semester})`;
        info.classList.remove('d-none');
      }
    } else {
      document.getElementById('graph-node-info').classList.add('d-none');
    }
  });
}

function applyGraphFilter() {
  if (!graphNetwork) return;
  const filter = document.getElementById('graph-filter-jenis').value;
  let filteredEdges = allGraphEdges;
  if (filter) {
    filteredEdges = allGraphEdges.filter(e => e.jenis === filter);
  }
  renderGraph(allGraphNodes, filteredEdges);
}

// ──────────────────────────────────────────────────────────
// KRS SIMULATOR
// ──────────────────────────────────────────────────────────
async function runSimulation() {
  const nim   = document.getElementById('sim-nim').value.trim();
  const kmkId = document.getElementById('sim-kmk-id').value;

  if (!nim) { alert('Masukkan NIM mahasiswa terlebih dahulu.'); return; }
  if (!kmkId) { alert('Pilih mata kuliah yang ingin disimulasikan.'); return; }

  document.getElementById('sim-placeholder').classList.add('d-none');
  document.getElementById('sim-results').classList.add('d-none');
  document.getElementById('sim-loading').classList.remove('d-none');

  try {
    const res = await fetch(`/references/curiculum/${KODE}/prereq/simulate`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
      body: JSON.stringify({ nim, kmk_id: kmkId, _token: CSRF }),
    });
    const data = await res.json();
    document.getElementById('sim-loading').classList.add('d-none');

    if (!data.success) {
      showFlash(data.message || 'Gagal menjalankan simulasi.', 'danger');
      document.getElementById('sim-placeholder').classList.remove('d-none');
      return;
    }

    renderSimResults(data, nim);
  } catch(e) {
    document.getElementById('sim-loading').classList.add('d-none');
    showFlash('Gagal menghubungi server.', 'danger');
    document.getElementById('sim-placeholder').classList.remove('d-none');
  }
}

function renderSimResults(data, nim) {
  // Banner
  const banner = document.getElementById('sim-banner');
  const bannerIcon = document.getElementById('sim-banner-icon');
  const bannerTitle = document.getElementById('sim-banner-title');
  const bannerSub = document.getElementById('sim-banner-sub');

  if (data.eligible) {
    banner.style.background = '#f0fdf4';
    banner.style.border = '1px solid #86efac';
    bannerIcon.className = 'ti ti-circle-check fs-3 text-success';
    bannerTitle.textContent = '✔ Dapat Mengambil Mata Kuliah Ini';
    bannerTitle.className = 'fw-bold text-success';
  } else {
    banner.style.background = '#fef2f2';
    banner.style.border = '1px solid #fca5a5';
    bannerIcon.className = 'ti ti-alert-triangle fs-3 text-danger';
    bannerTitle.textContent = '✘ Belum Memenuhi Semua Prasyarat';
    bannerTitle.className = 'fw-bold text-danger';
  }
  bannerSub.textContent = data.conclusion;

  // Student Info
  const infoEl = document.getElementById('sim-student-info');
  infoEl.innerHTML = `
    <div class="badge bg-light text-dark border px-3 py-2 small">
      <i class="ti ti-id-badge me-1"></i>NIM: <strong>${nim}</strong>
    </div>
    ${data.student ? `<div class="badge bg-light text-dark border px-3 py-2 small"><i class="ti ti-user me-1"></i>${data.student}</div>` : ''}
    <div class="badge bg-light text-dark border px-3 py-2 small">
      <i class="ti ti-book me-1"></i>Total SKS Lulus: <strong>${data.total_sks}</strong>
    </div>
  `;

  // Groups
  const groupsEl = document.getElementById('sim-groups');
  if (!data.groups || data.groups.length === 0) {
    groupsEl.innerHTML = '<div class="alert alert-info border-0 shadow-sm"><i class="ti ti-info-circle me-2"></i>Mata kuliah ini tidak memiliki prasyarat. Dapat diambil kapan saja.</div>';
  } else {
    const totalGroups = data.groups.length;
    groupsEl.innerHTML = data.groups.map((grp, gi) => `
      <div class="card border shadow-none mb-2" style="border-radius:10px;">
        <div class="card-header bg-${grp.passed ? 'success' : 'danger'}-subtle border-0 py-2 px-3 d-flex align-items-center gap-2">
          <i class="ti ti-${grp.passed ? 'circle-check text-success' : 'circle-x text-danger'}"></i>
          <span class="fw-semibold small text-dark">
            ${grp.grup ? `Grup <code>${grp.grup}</code>` : 'Kondisi'}
            ${grp.items.length > 1 ? '<span class="badge bg-info-subtle text-info border border-info-subtle ms-2 small">OR</span>' : ''}
            ${totalGroups > 1 && gi < totalGroups - 1 ? '<span class="badge bg-warning-subtle text-warning border border-warning-subtle ms-2 small">AND dgn grup berikutnya</span>' : ''}
          </span>
          <span class="ms-auto badge bg-${grp.passed ? 'success' : 'danger'}-subtle text-${grp.passed ? 'success' : 'danger'} border px-2">
            ${grp.passed ? '✔ Terpenuhi' : '✘ Belum Terpenuhi'}
          </span>
        </div>
        <div class="card-body p-0">
          ${grp.items.map(item => `
            <div class="d-flex align-items-start gap-3 p-3 border-bottom">
              <div class="mt-1">
                <i class="ti ti-${item.passed ? 'circle-check text-success' : 'circle-x text-danger'} fs-5"></i>
              </div>
              <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <span class="badge ${jenisClass(item.jenis)}">${item.jenis}</span>
                  <strong class="small text-dark">${item.mk_nama}</strong>
                </div>
                <div class="text-muted small">${item.detail}</div>
              </div>
            </div>
          `).join('')}
        </div>
      </div>
    `).join('');
  }

  document.getElementById('sim-results').classList.remove('d-none');
}

function jenisClass(jenis) {
  const map = { PASS:'bg-success-subtle text-success border border-success-subtle', TAKEN:'bg-secondary-subtle text-secondary border', COREQ:'bg-warning-subtle text-warning border border-warning-subtle', CREDITS:'bg-purple-subtle text-purple border border-purple-subtle' };
  return map[jenis] || 'bg-light text-dark border';
}
</script>

<style>
.table-row-has-prereq:hover { background: #f0f9ff !important; }
.prereq-detail-row > td { background: #f8fafc; }
.btn-xs { padding: 2px 6px !important; font-size: 11px !important; }
</style>
@endsection
