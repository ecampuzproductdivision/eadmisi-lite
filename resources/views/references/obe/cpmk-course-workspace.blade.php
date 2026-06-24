@extends('layouts.app')

@section('content')
<main class="p-2">
  <!-- Header Card -->
  <div class="card border-1 mb-4 shadow-xs">
    <div class="card-body p-4">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
        <div>
          <h3 class="mb-1 fw-bold text-dark d-flex align-items-center">
            <i class="ti ti-notebook me-2 text-primary"></i>
            Workspace CPMK & Sub-CPMK
          </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('obe.cpmk.index') }}">CPMK per Mata Kuliah</a></li>
              <li class="breadcrumb-item"><a href="{{ route('curiculum.cpmk.workspace', $kurikulum->kurKode) }}">{{ $kurikulum->kurNama }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ $course->mk_nama }}</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('curiculum.course.cpmk.export-pdf', ['kurKode' => $kurikulum->kurKode, 'courseId' => $course->id]) }}" target="_blank" class="btn btn-light border d-inline-flex align-items-center gap-1">
            <i class="ti ti-file-text text-danger"></i> PDF
          </a>
          <a href="{{ route('curiculum.course.cpmk.export-excel', ['kurKode' => $kurikulum->kurKode, 'courseId' => $course->id]) }}" class="btn btn-light border d-inline-flex align-items-center gap-1">
            <i class="ti ti-file-spreadsheet text-success"></i> Excel
          </a>
          @if(!$isReadOnly)
            <button class="btn btn-light border d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#copyCpmkModal">
              <i class="ti ti-copy text-primary"></i> Salin dari Versi Lain
            </button>
            <button class="btn btn-primary d-inline-flex align-items-center gap-1" onclick="openAddCpmkModal()">
              <i class="ti ti-plus"></i> Tambah CPMK
            </button>
          @endif
        </div>
      </div>

      <!-- Course Meta Details Info -->
      <div class="row g-3 p-3 bg-light rounded-3">
        <div class="col-md-3 border-end">
          <small class="text-muted d-block">Kode Mata Kuliah</small>
          <strong class="text-dark font-monospace">{{ $course->mk_kode ?? '-' }}</strong>
        </div>
        <div class="col-md-3 border-end">
          <small class="text-muted d-block">Nama Mata Kuliah</small>
          <strong class="text-dark">{{ $course->mk_nama ?? '-' }}</strong>
        </div>
        <div class="col-md-3 border-end">
          <small class="text-muted d-block">SKS & Semester Anjuran</small>
          <strong class="text-dark">{{ $kurikulumMataKuliah->sks_override ?? ($course->mk_sks_total ?? 0) }} SKS • Semester {{ $kurikulumMataKuliah->semester_anjuran }}</strong>
        </div>
        <div class="col-md-3">
          <small class="text-muted d-block">Kelompok Mata Kuliah</small>
          <span class="badge mt-1" style="background-color: {{ $kurikulumMataKuliah->kelompokMk->warna_ui ?? '#6c757d' }}20; color: {{ $kurikulumMataKuliah->kelompokMk->warna_ui ?? '#6c757d' }}; font-size: 11px;">
            {{ $kurikulumMataKuliah->kelompokMk->nama_kelompok ?? 'Kelompok MK' }}
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Workspace Grid Layout -->
  <div class="row g-4">
    <!-- Left Panel: CPMK and Sub-CPMK list -->
    <div class="col-xl-8 col-lg-7">
      <div class="card border-1 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
          <h6 class="fw-bold text-dark mb-0"><i class="ti ti-list me-1 text-primary"></i> Struktur Capaian Pembelajaran (CPMK & Sub-CPMK)</h6>
          <small class="text-muted">Klik baris CPMK untuk melihat rincian Sub-CPMK</small>
        </div>
        
        <div class="card-body p-0">
          <div class="accordion accordion-flush" id="cpmkAccordion">
            @forelse($cpmks as $idx => $cpmk)
              @php
                $isWeightSumOk = abs($cpmks->where('is_aktif', true)->sum('bobot_cpmk') - 100.0) < 0.01;
                $mappedCpls = $cpmk->cpls;
                $mappedAssessments = $cpmk->komponenPemetaan;
                $subCpmks = $cpmk->subCpmks;
              @endphp
              <div class="accordion-item {{ !$cpmk->is_aktif ? 'opacity-50' : '' }}" style="border-bottom: 1px solid #f1f3f5;">
                <h2 class="accordion-header" id="heading-{{ $cpmk->id }}">
                  <div class="w-100 p-3 collapsed border-0 text-start d-flex flex-wrap align-items-start gap-3 bg-white" 
                       style="cursor: pointer; transition: background-color 0.2s;" 
                       onmouseover="this.style.backgroundColor='#fafafa'" 
                       onmouseout="this.style.backgroundColor=''"
                       data-bs-toggle="collapse" 
                       data-bs-target="#collapse-{{ $cpmk->id }}" 
                       aria-expanded="false" 
                       aria-controls="collapse-{{ $cpmk->id }}">
                    
                    <!-- Chevron toggle -->
                    <div class="pt-1 text-muted accordion-arrow">
                      <i class="ti ti-chevron-down fs-5"></i>
                    </div>

                    <!-- CPMK Code and Urutan -->
                    <div style="min-width: 90px;">
                      <span class="badge bg-primary text-white font-monospace py-2 px-3 fw-bold fs-6" style="border-radius: 8px;">{{ $cpmk->kode_cpmk }}</span>
                      @if(!$cpmk->is_aktif)
                        <span class="badge bg-danger text-white mt-1 d-block text-center" style="font-size: 9px;">NON-AKTIF</span>
                      @endif
                    </div>

                    <!-- CPMK Description -->
                    <div class="flex-grow-1" style="max-width: 50%;">
                      <div class="fw-bold text-dark fs-6">{{ $cpmk->deskripsi_singkat ?: 'CPMK Description' }}</div>
                      <p class="text-muted small mb-2 mt-1 lh-base">{{ $cpmk->deskripsi }}</p>
                      
                      <!-- Supported CPL List Badges -->
                      <div class="d-flex flex-wrap gap-1 align-items-center">
                        <small class="text-muted me-1">CPL Didukung:</small>
                        @forelse($mappedCpls as $cpl)
                          <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace px-2 py-1" style="font-size: 10px;" title="{{ $cpl->deskripsi }}">
                            {{ $cpl->kode_cpl }}
                          </span>
                        @empty
                          <span class="text-danger small" style="font-size: 11px;"><i class="ti ti-alert-triangle text-danger me-1"></i>Belum memetakan CPL</span>
                        @endforelse
                      </div>
                    </div>

                    <!-- Bloom Taxonomy Rank & level -->
                    <div style="min-width: 100px;">
                      <small class="text-muted d-block small">Ranah & Level</small>
                      <strong class="text-dark d-block small mt-1">{{ $cpmk->ranah_taksonomi ?: 'Kognitif' }}</strong>
                      <span class="badge bg-info-subtle text-info border border-info-subtle font-monospace px-2 py-1 mt-1" style="font-size: 10px;">
                        {{ $cpmk->level_bloom ?: 'COG-3' }}
                      </span>
                    </div>

                    <!-- Bobot CPMK -->
                    <div class="text-center" style="min-width: 80px;">
                      <small class="text-muted d-block small">Bobot CPMK</small>
                      <strong class="fs-5 text-dark mt-1 d-block">{{ number_format($cpmk->bobot_cpmk, 0) }}%</strong>
                    </div>

                    <!-- Action buttons -->
                    @if(!$isReadOnly)
                      <div class="ms-auto d-flex gap-1" onclick="event.stopPropagation();">
                        <div class="dropdown">
                          <button class="btn btn-sm btn-light border py-1 px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-dots-vertical"></i>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 10px;">
                            <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="openEditCpmkModal({{ $cpmk->id }})"><i class="ti ti-edit text-warning me-2"></i> Edit CPMK</a></li>
                            <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="openCplMappingModal({{ $cpmk->id }}, {{ json_encode($mappedCpls->pluck('id_cpl')->toArray()) }})"><i class="ti ti-award text-primary me-2"></i> Petakan CPL</a></li>
                            <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="openKomponenMappingModal({{ $cpmk->id }}, {{ json_encode($mappedAssessments) }})"><i class="ti ti-checklist text-success me-2"></i> Petakan Komponen Nilai</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="javascript:void(0)" onclick="confirmDeleteCpmk({{ $cpmk->id }}, '{{ $cpmk->kode_cpmk }}')"><i class="ti ti-trash me-2"></i> Hapus CPMK</a></li>
                            <li><a class="dropdown-item py-2 {{ $cpmk->is_aktif ? 'text-danger' : 'text-success' }}" href="javascript:void(0)" onclick="toggleCpmkStatus({{ $cpmk->id }}, '{{ $cpmk->kode_cpmk }}', {{ $cpmk->is_aktif ? 'true' : 'false' }})">
                              <i class="ti {{ $cpmk->is_aktif ? 'ti-circle-x' : 'ti-circle-check' }} me-2"></i> {{ $cpmk->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                            </a></li>
                          </ul>
                        </div>
                      </div>
                    @endif

                  </div>
                </h2>

                <!-- Sub-CPMK accordion expand details -->
                <div id="collapse-{{ $cpmk->id }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $cpmk->id }}" data-bs-parent="#cpmkAccordion">
                  <div class="accordion-body bg-light-subtle p-4 border-top">
                    <!-- Heading for Sub-CPMK -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <span class="fw-bold text-dark mb-0 small"><i class="ti ti-corner-down-right me-1 text-primary"></i> Daftar Sub-CPMK (Breakdown Pertemuan)</span>
                      @if(!$isReadOnly && $cpmk->is_aktif)
                        <button class="btn btn-xs btn-outline-primary py-1 px-2 fw-semibold" style="font-size: 11px;" onclick="openAddSubModal({{ $cpmk->id }}, '{{ $cpmk->kode_cpmk }}')">
                          <i class="ti ti-plus"></i> Tambah Sub-CPMK
                        </button>
                      @endif
                    </div>

                    <!-- Sub-CPMK table -->
                    <div class="table-responsive rounded-3 bg-white border">
                      <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                        <thead class="table-light">
                          <tr>
                            <th class="ps-3 py-2" style="width: 100px;">Kode Sub</th>
                            <th class="py-2">Deskripsi Detail</th>
                            <th class="py-2">Ranah & Level Bloom</th>
                            <th class="py-2">KKO</th>
                            <th class="py-2 text-center" style="width: 90px;">Bobot (%)</th>
                            <th class="py-2 text-center" style="width: 100px;">Pertemuan Ke</th>
                            @if(!$isReadOnly && $cpmk->is_aktif)
                              <th class="pe-3 py-2 text-end" style="width: 80px;">Aksi</th>
                            @endif
                          </tr>
                        </thead>
                        <tbody>
                          @forelse($subCpmks as $sub)
                            <tr>
                              <td class="ps-3 font-monospace fw-bold text-primary">{{ $sub->kode_sub_cpmk }}</td>
                              <td>{{ $sub->deskripsi }}</td>
                              <td>
                                <strong class="small text-muted d-block">{{ $sub->ranah_taksonomi }}</strong>
                                <span class="badge bg-secondary-subtle text-secondary border font-monospace px-2 py-0.5 mt-0.5" style="font-size: 9px;">
                                  {{ $sub->level_bloom }}
                                </span>
                              </td>
                              <td class="font-monospace text-dark">{{ $sub->kko_bloom }}</td>
                              <td class="text-center font-monospace fw-semibold">
                                {{ $sub->bobot_dalam_cpmk ? number_format($sub->bobot_dalam_cpmk, 0) . '%' : '-' }}
                              </td>
                              <td class="text-center font-monospace">
                                @if($sub->pertemuan_ke)
                                  <span class="badge bg-light text-dark border px-2 py-1">Pert. {{ $sub->pertemuan_ke }}</span>
                                @else
                                  <span class="text-muted small">-</span>
                                @endif
                              </td>
                              @if(!$isReadOnly && $cpmk->is_aktif)
                                <td class="pe-3 text-end">
                                  <div class="d-flex gap-1 justify-content-end">
                                    <button class="btn btn-xs btn-light border py-1 px-2" title="Edit" onclick="openEditSubModal({{ $cpmk->id }}, '{{ $sub->id_sub_cpmk }}', '{{ $cpmk->kode_cpmk }}')">
                                      <i class="ti ti-edit text-warning"></i>
                                    </button>
                                    <button class="btn btn-xs btn-light border py-1 px-2" title="Hapus" onclick="confirmDeleteSub('{{ $sub->id_sub_cpmk }}', '{{ $sub->kode_sub_cpmk }}')">
                                      <i class="ti ti-trash text-danger"></i>
                                    </button>
                                  </div>
                                </td>
                              @endif
                            </tr>
                          @empty
                            <tr>
                              <td colspan="7" class="text-center py-4 text-muted small">
                                <i class="ti ti-info-circle d-block fs-3 mb-1"></i>
                                Belum ada Sub-CPMK terdaftar untuk CPMK ini.
                              </td>
                            </tr>
                          @endforelse
                        </tbody>
                      </table>
                    </div>

                    <!-- Mapped Assessment Component Details -->
                    <div class="mt-4 p-3 rounded-3 bg-light border border-light-subtle">
                      <div class="fw-bold text-dark small mb-2"><i class="ti ti-checklist text-success me-1"></i>Instrumen Evaluasi & Pembobotan Nilai CPMK</div>
                      <div class="d-flex flex-wrap gap-3">
                        @forelse($mappedAssessments as $mapAss)
                          <div class="bg-white rounded-3 shadow-xs border p-2 d-flex align-items-center gap-3" style="min-width: 200px;">
                            <div class="p-2 rounded-2 bg-success-subtle text-success">
                              <i class="ti ti-calculator fs-5"></i>
                            </div>
                            <div>
                              <small class="text-muted d-block small">Komponen Penilaian</small>
                              <strong class="text-dark small d-block">{{ $mapAss->komponen->nama_komponen ?? '-' }}</strong>
                              <span class="badge bg-light text-muted border font-monospace mt-1" style="font-size: 9px;">
                                @if($mapAss->bobot_dalam_cpmk)
                                  Bobot dlm CPMK: {{ number_format($mapAss->bobot_dalam_cpmk, 0) }}%
                                @else
                                  Bobot dlm CPMK: Rata (divided)
                                @endif
                              </span>
                            </div>
                          </div>
                        @empty
                          <span class="text-danger small"><i class="ti ti-alert-triangle text-danger me-1"></i>Belum terhubung ke instrumen penilaian. Nilai CPMK ini tidak akan dikalkulasi otomatis oleh sistem.</span>
                        @endforelse
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            @empty
              <div class="text-center py-5 text-muted">
                <i class="ti ti-notebook fs-1 d-block mb-2"></i>
                <span class="fw-semibold d-block">Mata Kuliah Belum Memiliki CPMK</span>
                <p class="small">Silakan tambahkan data CPMK baru di atas untuk mulai menyusun target pembelajaran.</p>
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    <!-- Right Panel: Analysis and Logs -->
    <div class="col-xl-4 col-lg-5">
      <!-- Bloom Taxonomy Analysis -->
      <div class="card border-1 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-0">
          <h6 class="fw-bold text-dark mb-0"><i class="ti ti-chart-bar me-1 text-primary"></i> Analisis Distribusi Taksonomi Bloom</h6>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <h6 class="text-muted small fw-semibold mb-3">Distribusi Ranah Taksonomi</h6>
            <div style="height: 150px; position: relative;">
              <canvas id="bloomRanahChart"></canvas>
            </div>
          </div>
          <hr class="my-3">
          <div class="mb-3">
            <h6 class="text-muted small fw-semibold mb-3">Distribusi Level Kognitif/Bloom</h6>
            <div style="height: 150px; position: relative;">
              <canvas id="bloomLevelChart"></canvas>
            </div>
          </div>

          <!-- Semantic recommendation banner -->
          <div class="mt-3 p-3 rounded-3" id="bloomRecommendationBanner" style="background-color: #fafafa; border: 1px solid #f1f3f5;">
            <div class="d-flex gap-2">
              <i class="ti ti-message-chatbot text-primary fs-4 mt-0.5"></i>
              <div>
                <div class="fw-bold text-dark small">Rekomendasi Tim OBE</div>
                <p class="text-muted small mb-0 mt-1 lh-sm" id="bloomRecommendationText">
                  Menghitung data CPMK untuk memformulasikan rekomendasi penyelarasan tingkat kognitif...
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Audit Trail / Changelogs -->
      <div class="card border-1 shadow-sm">
        <div class="card-header bg-white py-3 border-0">
          <h6 class="fw-bold text-dark mb-0"><i class="ti ti-history me-1 text-primary"></i> Riwayat Perubahan (Audit Trail)</h6>
        </div>
        <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
          <ul class="list-group list-group-flush small">
            @php
              $allChangelogs = collect();
              foreach($cpmks as $cpmk) {
                foreach($cpmk->changelogs as $log) {
                  $log->cpmk_kode = $cpmk->kode_cpmk;
                  $allChangelogs->push($log);
                }
              }
              $allChangelogs = $allChangelogs->sortByDesc('changed_at');
            @endphp
            @forelse($allChangelogs as $log)
              <li class="list-group-item p-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="badge font-monospace {{ $log->aksi === 'INSERT' ? 'bg-success' : ($log->aksi === 'UPDATE' ? 'bg-warning text-dark' : 'bg-danger') }}" style="font-size: 9px;">
                    {{ $log->aksi }}
                  </span>
                  <span class="text-muted font-monospace" style="font-size: 10px;">{{ $log->changed_at->format('d M Y H:i') }}</span>
                </div>
                <div class="fw-bold text-dark mt-1" style="font-size: 12px;">{{ $log->cpmk_kode }} &mdash; {{ $log->alasan ?: 'Revisi data' }}</div>
                <div class="text-muted mt-1 small">
                  Oleh: <strong>{{ $log->user->name ?? 'System' }}</strong>
                </div>
              </li>
            @empty
              <li class="list-group-item p-4 text-center text-muted">
                <i class="ti ti-info-circle fs-3 d-block mb-1"></i>
                Belum ada catatan riwayat perubahan.
              </li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Realtime Weight Validator Footer Sticky Bar -->
  <div class="position-sticky bottom-0 start-0 end-0 bg-white border-top shadow p-3 z-3 mt-4" style="border-radius: 12px 12px 0 0; margin-left: -12px; margin-right: -12px;">
    <div class="container-fluid d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
      @php
        $activeCpmks = $cpmks->where('is_aktif', true);
        $totalCpmkWeight = $activeCpmks->sum('bobot_cpmk');
        $weightIsOk = abs($totalCpmkWeight - 100.0) < 0.01;
      @endphp
      <div class="d-flex align-items-center gap-3">
        <div class="p-2 rounded-3 bg-{{ $weightIsOk ? 'success' : 'danger' }}-subtle text-{{ $weightIsOk ? 'success' : 'danger' }}">
          <i class="ti ti-{{ $weightIsOk ? 'check-circle' : 'alert-triangle' }} fs-3"></i>
        </div>
        <div>
          <h6 class="fw-bold text-dark mb-0">Bobot Akumulatif CPMK</h6>
          <p class="text-muted small mb-0 mt-0.5">
            Total bobot semua CPMK yang aktif dalam mata kuliah ini harus tepat <strong>100%</strong>.
          </p>
        </div>
      </div>
      <div class="d-flex align-items-center gap-4">
        <div class="text-md-end">
          <small class="text-muted d-block small">Akumulasi Saat Ini</small>
          <span class="h3 mb-0 fw-bold text-{{ $weightIsOk ? 'success' : 'danger' }}">{{ number_format($totalCpmkWeight, 0) }}%</span>
        </div>
        @if(!$isReadOnly)
          @if($weightIsOk)
            <span class="badge bg-success-subtle text-success border border-success px-3 py-2 fw-semibold" style="font-size: 12px;">STRUKTUR VALID</span>
          @else
            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 fw-semibold animate-pulse" style="font-size: 12px; animation: pulse 2s infinite;">BELUM VALID</span>
          @endif
        @endif
      </div>
    </div>
  </div>

  <!-- MODALS & DIALOGS -->

  <!-- Modal: Copy CPMK Wizard -->
  @if(!$isReadOnly)
    <div class="modal fade" id="copyCpmkModal" tabindex="-1" aria-labelledby="copyCpmkModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 14px; border: 0;">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold text-dark" id="copyCpmkModalLabel"><i class="ti ti-copy text-primary me-1"></i> Salin Struktur CPMK</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="copyCpmkForm">
            <div class="modal-body py-4">
              <p class="text-muted small">
                Salin seluruh CPMK beserta Sub-CPMK dan pemetaan dari kurikulum versi lain yang memiliki mata kuliah <strong>{{ $course->mk_nama }}</strong>. Tindakan ini akan menghapus CPMK target saat ini terlebih dahulu.
              </p>
              <div class="mb-3">
                <label class="form-label fw-semibold text-dark">Versi Kurikulum Sumber</label>
                <select name="source_kurikulum_kode" id="source_kurikulum_kode" class="form-select" required>
                  <option value="">-- Pilih Kurikulum Sumber --</option>
                  @foreach($otherKurikulums as $ok)
                    <option value="{{ $ok->kurKode }}">{{ $ok->kurNama }} ({{ $ok->kurKode }})</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-light border" data-bs-toggle="modal">Batal</button>
              <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Mulai Salin</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal: Add / Edit CPMK -->
    <div class="modal fade" id="cpmkFormModal" tabindex="-1" aria-labelledby="cpmkFormModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 14px; border: 0;">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold text-dark" id="cpmkFormModalLabel">CPMK Form</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="cpmkForm">
            <input type="hidden" name="id" id="cpmk_id">
            <div class="modal-body py-4 row g-3">
              <div class="col-md-4">
                <label class="form-label fw-semibold text-dark">Kode CPMK</label>
                <input type="text" name="kode_cpmk" id="kode_cpmk" class="form-control font-monospace" placeholder="contoh: CPMK-1" required>
              </div>
              <div class="col-md-8">
                <label class="form-label fw-semibold text-dark">Deskripsi Singkat (Label)</label>
                <input type="text" name="deskripsi_singkat" id="deskripsi_singkat" class="form-control" placeholder="contoh: Merancang Basis Data Relasional" required>
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold text-dark">Deskripsi Lengkap (Rumusan OBE)</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="contoh: Mampu merancang skema basis data relasional dengan menerapkan aturan normalisasi 1NF-3NF dari studi kasus perkuliahan." required></textarea>
                <small class="text-muted block mt-1" style="font-size: 11px;">Rekomendasi rumus: <strong>[KKO Bloom] + [Objek] + [Konteks]</strong>.</small>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold text-dark">Ranah Taksonomi</label>
                <select name="ranah_taksonomi" id="ranah_taksonomi" class="form-select" onchange="populateBloomLevels(this.value)" required>
                  <option value="Kognitif">Kognitif (Pengetahuan)</option>
                  <option value="Afektif">Afektif (Sikap)</option>
                  <option value="Psikomotorik">Psikomotorik (Keterampilan)</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold text-dark">Level Bloom</label>
                <select name="level_bloom" id="level_bloom" class="form-select" onchange="populateKkoSuggestions(this.value)" required>
                  <!-- Populated dynamically -->
                </select>
              </div>
              <div class="col-md-4 position-relative">
                <label class="form-label fw-semibold text-dark">KKO Bloom (Kata Kerja)</label>
                <input type="text" name="kko_bloom" id="kko_bloom" class="form-control" placeholder="Menganalisis/Merancang" required autocomplete="off">
                <div id="kkoSuggestions" class="list-group position-absolute w-100 shadow border-0 z-3" style="display: none; max-height: 150px; overflow-y: auto; border-radius: 8px;"></div>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold text-dark">Bobot CPMK (%)</label>
                <input type="number" name="bobot_cpmk" id="bobot_cpmk" class="form-control" min="1" max="100" placeholder="e.g. 25" required>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold text-dark">Target Ketercapaian (%)</label>
                <input type="number" name="target_ketercapaian" id="target_ketercapaian" class="form-control" min="0" max="100" value="75" required>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold text-dark">Batas Bawah Kelulusan</label>
                <input type="number" name="batas_bawah_lulus" id="batas_bawah_lulus" class="form-control" min="0" max="100" value="55" required>
              </div>
              <div class="col-12" id="alasanPerubahanContainer" style="display: none;">
                <label class="form-label fw-semibold text-dark text-danger">Alasan Perubahan (Diperlukan)</label>
                <textarea name="alasan_perubahan" id="alasan_perubahan" class="form-control border-danger" rows="2" placeholder="Tulis alasan mengapa CPMK ini diperbarui..."></textarea>
              </div>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Simpan CPMK</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal: CPL Mapping -->
    <div class="modal fade" id="cplMappingModal" tabindex="-1" aria-labelledby="cplMappingModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 14px; border: 0;">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold text-dark" id="cplMappingModalLabel"><i class="ti ti-award text-primary me-1"></i> Petakan CPMK ke CPL</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="cplMappingForm">
            <input type="hidden" name="id_cpmk" id="cpl_id_cpmk">
            <div class="modal-body py-4">
              <div class="fw-bold text-dark mb-1" id="cplModalTitle">CPMK-1</div>
              <p class="text-muted small mb-3">Pilih Capaian Pembelajaran Lulusan (CPL) yang dicapai melalui CPMK ini. Hanya CPL yang telah dipetakan ke mata kuliah ini di Matriks CPL-MK yang tersedia.</p>
              
              <div class="list-group" style="max-height: 250px; overflow-y: auto; border-radius: 10px;">
                @forelse($availableCpls as $cpl)
                  <label class="list-group-item d-flex gap-3 py-3" style="cursor: pointer;">
                    <input class="form-check-input flex-shrink-0" type="checkbox" name="cpl_ids[]" value="{{ $cpl->id_cpl }}">
                    <div>
                      <strong class="text-dark font-monospace d-block">{{ $cpl->kode_cpl }}</strong>
                      <span class="text-muted small mt-1 d-block lh-sm">{{ $cpl->deskripsi }}</span>
                    </div>
                  </label>
                @empty
                  <div class="text-center p-4 text-danger small">
                    <i class="ti ti-alert-triangle d-block fs-3 mb-1"></i>
                    Belum ada CPL yang dipetakan ke mata kuliah ini di Matriks CPL-MK. Hubungkan dulu di sub-menu Matriks CPL-MK.
                  </div>
                @endforelse
              </div>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary" {{ $availableCpls->isEmpty() ? 'disabled' : '' }}><i class="ti ti-check me-1"></i> Simpan Pemetaan</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal: Komponen Penilaian Mapping -->
    <div class="modal fade" id="komponenMappingModal" tabindex="-1" aria-labelledby="komponenMappingModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 14px; border: 0;">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold text-dark" id="komponenMappingModalLabel"><i class="ti ti-checklist text-success me-1"></i> Petakan ke Instrumen Asesmen</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="komponenMappingForm">
            <input type="hidden" name="id_cpmk" id="komponen_id_cpmk">
            <div class="modal-body py-4">
              <div class="fw-bold text-dark mb-1" id="komponenModalTitle">CPMK-1</div>
              <p class="text-muted small mb-3">Pilih komponen penilaian yang digunakan untuk mengukur CPMK ini. Tentukan bobot (%) kontribusi komponen terhadap nilai CPMK ini (Opsional, jika kosong dibagi rata).</p>

              <div class="list-group rounded-3 border">
                @forelse($komponenPenilaians as $komp)
                  <div class="list-group-item d-flex align-items-center justify-content-between gap-3 py-3">
                    <div class="d-flex align-items-center gap-3">
                      <input class="form-check-input" type="checkbox" name="id_komponen[]" value="{{ $komp->id }}" id="chk-komp-{{ $komp->id }}" onchange="toggleKompBobotInput({{ $komp->id }})">
                      <label for="chk-komp-{{ $komp->id }}" style="cursor: pointer;">
                        <strong class="text-dark d-block">{{ $komp->nama_komponen }}</strong>
                        <span class="text-muted small">Bobot dlm MK: {{ number_format($komp->bobot, 0) }}%</span>
                      </label>
                    </div>
                    <div style="width: 100px;">
                      <div class="input-group input-group-sm">
                        <input type="number" name="bobot_dalam_cpmk_{{ $komp->id }}" id="bobot_dalam_cpmk_{{ $komp->id }}" class="form-control text-center" min="0" max="100" placeholder="Proporsi" disabled>
                        <span class="input-group-text">%</span>
                      </div>
                    </div>
                  </div>
                @empty
                  <div class="text-center p-4 text-danger small">
                    <i class="ti ti-alert-triangle d-block fs-3 mb-1"></i>
                    Belum ada Komponen Penilaian/RPS yang didefinisikan untuk mata kuliah ini.
                  </div>
                @endforelse
              </div>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary" {{ $komponenPenilaians->isEmpty() ? 'disabled' : '' }}><i class="ti ti-check me-1"></i> Simpan Asesmen</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal: Add / Edit Sub-CPMK -->
    <div class="modal fade" id="subFormModal" tabindex="-1" aria-labelledby="subFormModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 14px; border: 0;">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold text-dark" id="subFormModalLabel">Sub-CPMK Form</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="subForm">
            <input type="hidden" name="id" id="sub_id">
            <input type="hidden" name="id_cpmk" id="sub_cpmk_id">
            <div class="modal-body py-4 row g-3">
              <div class="col-12 mb-1">
                <span class="text-muted small">Sub-CPMK untuk induk: <strong class="text-dark" id="subModalIndukCode">CPMK-1</strong></span>
              </div>
              <div class="col-md-5">
                <label class="form-label fw-semibold text-dark">Kode Sub-CPMK</label>
                <input type="text" name="kode_sub_cpmk" id="sub_kode" class="form-control font-monospace" placeholder="e.g. Sub-CPMK-1.1" required>
              </div>
              <div class="col-md-7">
                <label class="form-label fw-semibold text-dark">Pertemuan Ke-</label>
                <input type="text" name="pertemuan_ke" id="sub_pert" class="form-control font-monospace" placeholder="e.g. 1-2 atau 4" required>
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold text-dark">Deskripsi Rumusan Sub-CPMK</label>
                <textarea name="deskripsi" id="sub_desc" class="form-control" rows="3" placeholder="e.g. Menjelaskan konsep entitas, atribut, dan relasi dalam perancangan ERD." required></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold text-dark">Ranah Taksonomi</label>
                <select name="ranah_taksonomi" id="sub_ranah" class="form-select" onchange="populateSubBloomLevels(this.value)" required>
                  <option value="Kognitif">Kognitif (Pengetahuan)</option>
                  <option value="Afektif">Afektif (Sikap)</option>
                  <option value="Psikomotorik">Psikomotorik (Keterampilan)</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold text-dark">Level Bloom</label>
                <select name="level_bloom" id="sub_level" class="form-select" onchange="populateSubKkoSuggestions(this.value)" required>
                  <!-- Populated dynamically -->
                </select>
              </div>
              <div class="col-md-7 position-relative">
                <label class="form-label fw-semibold text-dark">KKO Bloom (Kata Kerja)</label>
                <input type="text" name="kko_bloom" id="sub_kko" class="form-control" placeholder="Menyebutkan/Menjelaskan" required autocomplete="off">
                <div id="subKkoSuggestions" class="list-group position-absolute w-100 shadow border-0 z-3" style="display: none; max-height: 150px; overflow-y: auto; border-radius: 8px;"></div>
              </div>
              <div class="col-md-5">
                <label class="form-label fw-semibold text-dark">Bobot dlm CPMK (%)</label>
                <input type="number" name="bobot_dalam_cpmk" id="sub_bobot" class="form-control" min="0" max="100" placeholder="e.g. 20 (opsional)">
              </div>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Simpan Sub-CPMK</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

</main>

<!-- Chart.js and Custom Autocomplete & AJAX scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Map of all CPMKs for easy lookup in modals
  const cpmksMap = {!! json_encode($cpmks->keyBy('id')->toArray()) !!};

  // KKO Bloom Reference Dictionary
  const kkoDictionary = {
    // Kognitif
    'COG-1': ['Mengingat', 'Menyebutkan', 'Mengidentifikasi', 'Mendefinisikan', 'Mendaftar', 'Menyatakan', 'Memilih', 'Menuliskan'],
    'COG-2': ['Memahami', 'Menjelaskan', 'Mengklasifikasikan', 'Merangkum', 'Membandingkan', 'Menguraikan', 'Mengidentifikasi', 'Menerjemahkan'],
    'COG-3': ['Menerapkan', 'Menggunakan', 'Menhitung', 'Mendemonstrasikan', 'Mengimplementasikan', 'Memecahkan', 'Menentukan', 'Mengoperasikan'],
    'COG-4': ['Menganalisis', 'Membedakan', 'Mengurai', 'Mengorganisasi', 'Menginferensi', 'Menelaah', 'Mendiagnosis', 'Mendekomposisi'],
    'COG-5': ['Mengevaluasi', 'Menilai', 'Mengkritisi', 'Membenarkan', 'Merekomendasikan', 'Menguji', 'Memvalidasi', 'Memutuskan'],
    'COG-6': ['Mencipta', 'Merancang', 'Membangun', 'Mengembangkan', 'Menyusun', 'Membuat', 'Merumuskan', 'Memformulasikan'],
    // Afektif
    'AFF-1': ['Menerima', 'Mendengarkan', 'Memperhatikan', 'Menyadari', 'Menerima stimulus'],
    'AFF-2': ['Merespons', 'Berpartisipasi', 'Mematuhi', 'Melaporkan', 'Menjawab', 'Menyetujui'],
    'AFF-3': ['Menghargai', 'Menunjukkan', 'Menginisiasi', 'Membela', 'Mendukung', 'Mengapresiasi'],
    'AFF-4': ['Mengorganisasi', 'Mengintegrasikan', 'Mengharmoniskan', 'Memprioritaskan', 'Menghubungkan'],
    'AFF-5': ['Menginternalisasi', 'Merefleksikan', 'Mempertahankan', 'Mendemonstrasikan konsistensi', 'Mempengaruhi'],
    // Psikomotorik
    'PSY-1': ['Meniru', 'Menyalin', 'Mengikuti', 'Mereplikasi', 'Mengamati'],
    'PSY-2': ['Memanipulasi', 'Melaksanakan', 'Mengoperasikan', 'Menjalankan', 'Mengikuti instruksi'],
    'PSY-3': ['Presisi', 'Menghasilkan', 'Menyelesaikan', 'Memperagakan', 'Melakukan dengan akurat'],
    'PSY-4': ['Artikulasi', 'Mengadaptasi', 'Memodifikasi', 'Mengintegrasikan', 'Menggabungkan keterampilan'],
    'PSY-5': ['Naturalisasi', 'Merancang', 'Menciptakan', 'Menginvensi', 'Melakukan secara otomatis']
  };

  // Bloom Levels helper
  const levelsByRanah = {
    'Kognitif': [
      { code: 'COG-1', name: 'C1 — Mengingat' },
      { code: 'COG-2', name: 'C2 — Memahami' },
      { code: 'COG-3', name: 'C3 — Menerapkan' },
      { code: 'COG-4', name: 'C4 — Menganalisis' },
      { code: 'COG-5', name: 'C5 — Mengevaluasi' },
      { code: 'COG-6', name: 'C6 — Mencipta' }
    ],
    'Afektif': [
      { code: 'AFF-1', name: 'A1 — Menerima' },
      { code: 'AFF-2', name: 'A2 — Merespons' },
      { code: 'AFF-3', name: 'A3 — Menghargai' },
      { code: 'AFF-4', name: 'A4 — Mengorganisasi' },
      { code: 'AFF-5', name: 'A5 — Menginternalisasi' }
    ],
    'Psikomotorik': [
      { code: 'PSY-1', name: 'P1 — Meniru' },
      { code: 'PSY-2', name: 'P2 — Memanipulasi' },
      { code: 'PSY-3', name: 'P3 — Presisi' },
      { code: 'PSY-4', name: 'P4 — Artikulasi' },
      { code: 'PSY-5', name: 'P5 — Naturalisasi' }
    ]
  };

  // Populate levels for CPMK Form
  function populateBloomLevels(ranah, selectVal = '') {
    const el = document.getElementById('level_bloom');
    el.innerHTML = '';
    const lvls = levelsByRanah[ranah] || [];
    lvls.forEach(l => {
      const opt = document.createElement('option');
      opt.value = l.code;
      opt.textContent = l.name;
      if (l.code === selectVal) opt.selected = true;
      el.appendChild(opt);
    });
    populateKkoSuggestions(el.value);
  }

  // Autocomplete KKO suggestions for CPMK Form
  function populateKkoSuggestions(level) {
    const sugBox = document.getElementById('kkoSuggestions');
    const input = document.getElementById('kko_bloom');
    sugBox.innerHTML = '';
    
    const verbs = kkoDictionary[level] || [];
    verbs.forEach(v => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'list-group-item list-group-item-action py-1 px-3 border-0 small';
      btn.textContent = v;
      btn.onclick = () => {
        input.value = v;
        sugBox.style.display = 'none';
      };
      sugBox.appendChild(btn);
    });

    input.onfocus = () => {
      if (sugBox.children.length > 0) sugBox.style.display = 'block';
    };
    
    document.addEventListener('click', (e) => {
      if (e.target !== input && e.target !== sugBox) {
        sugBox.style.display = 'none';
      }
    });
  }

  // Populate levels for Sub-CPMK Form
  let parentLevelValue = 6;
  function populateSubBloomLevels(ranah, selectVal = '') {
    const el = document.getElementById('sub_level');
    el.innerHTML = '';
    const lvls = levelsByRanah[ranah] || [];
    
    lvls.forEach(l => {
      // Filter out levels exceeding the parent level value (BR-CPMK-09)
      const numVal = parseInt(l.code.replace(/[^0-9]/g, ''));
      if (numVal <= parentLevelValue) {
        const opt = document.createElement('option');
        opt.value = l.code;
        opt.textContent = l.name;
        if (l.code === selectVal) opt.selected = true;
        el.appendChild(opt);
      }
    });
    
    if (el.value) populateSubKkoSuggestions(el.value);
  }

  function populateSubKkoSuggestions(level) {
    const sugBox = document.getElementById('subKkoSuggestions');
    const input = document.getElementById('sub_kko');
    sugBox.innerHTML = '';
    
    const verbs = kkoDictionary[level] || [];
    verbs.forEach(v => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'list-group-item list-group-item-action py-1 px-3 border-0 small';
      btn.textContent = v;
      btn.onclick = () => {
        input.value = v;
        sugBox.style.display = 'none';
      };
      sugBox.appendChild(btn);
    });

    input.onfocus = () => {
      if (sugBox.children.length > 0) sugBox.style.display = 'block';
    };
    
    document.addEventListener('click', (e) => {
      if (e.target !== input && e.target !== sugBox) {
        sugBox.style.display = 'none';
      }
    });
  }

  // Initialize distribution charts
  document.addEventListener('DOMContentLoaded', () => {
    // Chart 1: Ranah Taksonomi (Kognitif vs Afektif vs Psikomotorik)
    const ranahCtx = document.getElementById('bloomRanahChart').getContext('2d');
    new Chart(ranahCtx, {
      type: 'doughnut',
      data: {
        labels: ['Kognitif', 'Afektif', 'Psikomotorik'],
        datasets: [{
          data: [
            {{ $bloomRanahCounts['Kognitif'] }}, 
            {{ $bloomRanahCounts['Afektif'] }}, 
            {{ $bloomRanahCounts['Psikomotorik'] }}
          ],
          backgroundColor: ['#0d6efd', '#ffc107', '#198754'],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'right', labels: { boxWidth: 12, font: { size: 10 } } }
        }
      }
    });

    // Chart 2: Level Bloom
    const lvlCtx = document.getElementById('bloomLevelChart').getContext('2d');
    const lvlLabels = ['COG-1', 'COG-2', 'COG-3', 'COG-4', 'COG-5', 'COG-6'];
    const bloomLevelCountsObj = {!! json_encode($bloomLevelCounts) !!};
    const lvlData = lvlLabels.map(lbl => bloomLevelCountsObj[lbl] || 0);

    new Chart(lvlCtx, {
      type: 'bar',
      data: {
        labels: ['C1', 'C2', 'C3', 'C4', 'C5', 'C6'],
        datasets: [{
          label: 'Jumlah CPMK',
          data: lvlData,
          backgroundColor: '#0d6efd',
          borderRadius: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, ticks: { precision: 0 } }
        }
      }
    });

    // Formulate Recommendation Semantics
    const totalCpmks = {{ $activeCpmks->count() }};
    const c1c2Count = ({{ $bloomLevelCounts['COG-1'] ?? 0 }}) + ({{ $bloomLevelCounts['COG-2'] ?? 0 }});
    const c5c6Count = ({{ $bloomLevelCounts['COG-5'] ?? 0 }}) + ({{ $bloomLevelCounts['COG-6'] ?? 0 }});
    const sem = {{ $kurikulumMataKuliah->semester_anjuran ?? 0 }};
    const banner = document.getElementById('bloomRecommendationBanner');
    const text = document.getElementById('bloomRecommendationText');

    if (totalCpmks === 0) {
      text.textContent = "Belum ada CPMK yang dibuat. Rekomendasi akan diformulasikan setelah target CPMK disimpan.";
    } else {
      if (sem >= 5 && c1c2Count / totalCpmks > 0.5) {
        banner.style.backgroundColor = '#fff3cd';
        banner.style.borderColor = '#ffecb5';
        text.innerHTML = '<span class="text-warning-emphasis fw-bold">⚠️ Rekomendasi Kedalaman Kognitif Rendah!</span><br>Mata kuliah semester atas (' + sem + ') disarankan memiliki target level analisis (C4-C6) yang lebih tinggi. Saat ini didominasi level ingatan/pemahaman (C1-C2).';
      } else if (sem <= 2 && c5c6Count / totalCpmks > 0.5) {
        banner.style.backgroundColor = '#fff3cd';
        banner.style.borderColor = '#ffecb5';
        text.innerHTML = '<span class="text-warning-emphasis fw-bold">⚠️ Tingkat Kognitif Terlalu Tinggi!</span><br>Mata kuliah dasar semester awal (' + sem + ') disarankan mematangkan dasar pemahaman & aplikasi (C2-C3) terlebih dahulu sebelum menuntut perancangan/kreasi rumit (C5-C6).';
      } else {
        banner.style.backgroundColor = '#e8f5e9';
        banner.style.borderColor = '#c8e6c9';
        text.innerHTML = '<span class="text-success-emphasis fw-bold">✅ Distribusi Bloom Proporsional!</span><br>Berdasarkan semester mata kuliah ini (' + sem + '), pembagian level kognitif CPMK Anda dinilai sudah selaras dengan standar mutu kurikulum OBE.';
      }
    }
  });

  // Modal open helpers
  function openAddCpmkModal() {
    document.getElementById('cpmkForm').reset();
    document.getElementById('cpmk_id').value = '';
    document.getElementById('cpmkFormModalLabel').textContent = 'Tambah Capaian Pembelajaran MK (CPMK)';
    document.getElementById('alasanPerubahanContainer').style.display = 'none';
    document.getElementById('kode_cpmk').readOnly = false;
    populateBloomLevels('Kognitif');
    
    const myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('cpmkFormModal'));
    myModal.show();
  }

  function openEditCpmkModal(cpmkId) {
    const cpmk = cpmksMap[String(cpmkId)];
    if (!cpmk) return;

    document.getElementById('cpmkForm').reset();
    document.getElementById('cpmk_id').value = cpmk.id;
    document.getElementById('kode_cpmk').value = cpmk.kode_cpmk;
    document.getElementById('kode_cpmk').readOnly = true;
    document.getElementById('deskripsi_singkat').value = cpmk.deskripsi_singkat;
    document.getElementById('deskripsi').value = cpmk.deskripsi;
    document.getElementById('ranah_taksonomi').value = cpmk.ranah_taksonomi || 'Kognitif';
    document.getElementById('bobot_cpmk').value = Math.round(cpmk.bobot_cpmk);
    document.getElementById('target_ketercapaian').value = Math.round(cpmk.target_ketercapaian);
    document.getElementById('batas_bawah_lulus').value = Math.round(cpmk.batas_bawah_lulus);
    document.getElementById('kko_bloom').value = cpmk.kko_bloom;
    
    document.getElementById('cpmkFormModalLabel').textContent = 'Edit Capaian Pembelajaran: ' + cpmk.kode_cpmk;
    document.getElementById('alasanPerubahanContainer').style.display = 'block';
    document.getElementById('alasan_perubahan').required = true;

    populateBloomLevels(cpmk.ranah_taksonomi || 'Kognitif', cpmk.level_bloom);
    
    const myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('cpmkFormModal'));
    myModal.show();
  }

  // CPMK Form Submit via AJAX
  document.getElementById('cpmkForm').onsubmit = function(e) {
    e.preventDefault();
    const id = document.getElementById('cpmk_id').value;
    const url = id 
      ? "{{ route('curiculum.course.cpmk.update', ['kurKode' => $kurikulum->kurKode, 'courseId' => $course->id, 'id' => ':id']) }}".replace(':id', id)
      : "{{ route('curiculum.course.cpmk.store', ['kurKode' => $kurikulum->kurKode, 'courseId' => $course->id]) }}";

    const method = id ? 'PUT' : 'POST';
    const data = {};
    const fd = new FormData(this);
    fd.forEach((v, k) => { data[k] = v; });

    fetch(url, {
      method: method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        location.reload();
      } else {
        alert(res.message);
      }
    })
    .catch(err => alert('Terjadi kesalahan koneksi server. Silakan coba kembali.'));
  };

  // Confirm delete CPMK
  function confirmDeleteCpmk(id, kode) {
    const alasan = prompt('Anda yakin ingin menghapus ' + kode + '? Tulis alasan penghapusan / penonaktifan:');
    if (alasan === null) return; // user cancelled

    const url = "{{ route('curiculum.course.cpmk.destroy', ['kurKode' => $kurikulum->kurKode, 'courseId' => $course->id, 'id' => ':id']) }}".replace(':id', id);
    
    fetch(url, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ alasan: alasan })
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        alert(res.message);
        location.reload();
      } else {
        alert(res.message);
      }
    });
  }

  // Toggle active status CPMK
  function toggleCpmkStatus(id, kode, currStatus) {
    const verb = currStatus ? 'menonaktifkan' : 'mengaktifkan';
    const alasan = prompt('Tulis alasan Anda ' + verb + ' ' + kode + ':');
    if (alasan === null) return;

    const url = "{{ route('curiculum.course.cpmk.toggle-status', ['kurKode' => $kurikulum->kurKode, 'courseId' => $course->id, 'id' => ':id']) }}".replace(':id', id);
    
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ alasan: alasan })
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        location.reload();
      } else {
        alert(res.message);
      }
    });
  }

  // Open CPL Mapping Modal
  function openCplMappingModal(cpmkId, selectedCplIds) {
    const cpmk = cpmksMap[String(cpmkId)];
    if (!cpmk) return;

    document.getElementById('cpl_id_cpmk').value = cpmk.id;
    document.getElementById('cplModalTitle').textContent = cpmk.kode_cpmk + ' &mdash; ' + (cpmk.deskripsi_singkat || '');
    
    // Clear checklist
    const chks = document.querySelectorAll('#cplMappingForm input[name="cpl_ids[]"]');
    chks.forEach(chk => {
      chk.checked = selectedCplIds.includes(chk.value);
    });

    const myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('cplMappingModal'));
    myModal.show();
  }

  // Submit CPL Mapping
  document.getElementById('cplMappingForm').onsubmit = function(e) {
    e.preventDefault();
    const id = document.getElementById('cpl_id_cpmk').value;
    const url = "{{ route('cpmk.cpl-mapping', ['id' => ':id']) }}".replace(':id', id);

    const checkedChks = document.querySelectorAll('#cplMappingForm input[name="cpl_ids[]"]:checked');
    const cplIds = Array.from(checkedChks).map(chk => chk.value);

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ cpl_ids: cplIds })
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        location.reload();
      } else {
        alert(res.message);
      }
    });
  };

  // Open Assessment/Komponen Mapping Modal
  function openKomponenMappingModal(cpmkId, mappedAssessments) {
    const cpmk = cpmksMap[String(cpmkId)];
    if (!cpmk) return;

    document.getElementById('komponen_id_cpmk').value = cpmk.id;
    document.getElementById('komponenModalTitle').textContent = cpmk.kode_cpmk + ' &mdash; ' + (cpmk.deskripsi_singkat || '');

    // Reset components list
    const chks = document.querySelectorAll('#komponenMappingForm input[name="id_komponen[]"]');
    chks.forEach(chk => {
      chk.checked = false;
      const inp = document.getElementById('bobot_dalam_cpmk_' + chk.value);
      if (inp) {
        inp.value = '';
        inp.disabled = true;
      }
    });

    mappedAssessments.forEach(ma => {
      const chk = document.getElementById('chk-komp-' + ma.id_komponen);
      if (chk) {
        chk.checked = true;
        const inp = document.getElementById('bobot_dalam_cpmk_' + ma.id_komponen);
        if (inp) {
          inp.disabled = false;
          inp.value = ma.bobot_dalam_cpmk ? Math.round(ma.bobot_dalam_cpmk) : '';
        }
      }
    });

    const myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('komponenMappingModal'));
    myModal.show();
  }

  function toggleKompBobotInput(id) {
    const chk = document.getElementById('chk-komp-' + id);
    const inp = document.getElementById('bobot_dalam_cpmk_' + id);
    if (inp) {
      inp.disabled = !chk.checked;
      if (!chk.checked) inp.value = '';
    }
  }

  // Submit Assessment Component Mapping
  document.getElementById('komponenMappingForm').onsubmit = function(e) {
    e.preventDefault();
    const id = document.getElementById('komponen_id_cpmk').value;
    const url = "{{ route('cpmk.komponen-mapping', ['id' => ':id']) }}".replace(':id', id);

    const checkedChks = document.querySelectorAll('#komponenMappingForm input[name="id_komponen[]"]:checked');
    const komponenList = Array.from(checkedChks).map(chk => {
      const inp = document.getElementById('bobot_dalam_cpmk_' + chk.value);
      return {
        id_komponen: parseInt(chk.value),
        bobot: inp.value ? parseFloat(inp.value) : null
      };
    });

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ komponen: komponenList })
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        location.reload();
      } else {
        alert(res.message);
      }
    });
  };

  // Open Add Sub-CPMK Modal
  function openAddSubModal(cpmkId, cpmkCode) {
    document.getElementById('subForm').reset();
    document.getElementById('sub_id').value = '';
    document.getElementById('sub_cpmk_id').value = cpmkId;
    document.getElementById('subModalIndukCode').textContent = cpmkCode;
    document.getElementById('subFormModalLabel').textContent = 'Tambah Sub-CPMK Baru untuk ' + cpmkCode;
    
    const parentCpmkObj = cpmksMap[String(cpmkId)];

    if (parentCpmkObj) {
      const levelCode = parentCpmkObj.level_bloom || 'COG-3';
      parentLevelValue = parseInt(levelCode.replace(/[^0-9]/g, '')) || 6;
      const parentRanah = parentCpmkObj.ranah_taksonomi || 'Kognitif';
      document.getElementById('sub_ranah').value = parentRanah;
      populateSubBloomLevels(parentRanah);
    } else {
      // Fallback: no parent restriction
      parentLevelValue = 6;
      populateSubBloomLevels('Kognitif');
    }

    const myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('subFormModal'));
    myModal.show();
  }

  function openEditSubModal(cpmkId, subId, cpmkCode) {
    const cpmk = cpmksMap[String(cpmkId)];
    if (!cpmk) return;
    const sub = cpmk.sub_cpmks.find(s => String(s.id_sub_cpmk) === String(subId));
    if (!sub) return;

    document.getElementById('subForm').reset();
    document.getElementById('sub_id').value = sub.id_sub_cpmk;
    document.getElementById('sub_cpmk_id').value = sub.id_cpmk;
    document.getElementById('sub_kode').value = sub.kode_sub_cpmk;
    document.getElementById('sub_pert').value = sub.pertemuan_ke;
    document.getElementById('sub_desc').value = sub.deskripsi;
    document.getElementById('sub_ranah').value = sub.ranah_taksonomi;
    document.getElementById('sub_kko').value = sub.kko_bloom;
    document.getElementById('sub_bobot').value = sub.bobot_dalam_cpmk ? Math.round(sub.bobot_dalam_cpmk) : '';
    
    document.getElementById('subModalIndukCode').textContent = cpmkCode;
    document.getElementById('subFormModalLabel').textContent = 'Edit Sub-CPMK: ' + sub.kode_sub_cpmk;

    const levelCode = cpmk.level_bloom || 'COG-3';
    parentLevelValue = parseInt(levelCode.replace(/[^0-9]/g, '')) || 6;

    populateSubBloomLevels(sub.ranah_taksonomi, sub.level_bloom);

    const myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('subFormModal'));
    myModal.show();
  }

  // Submit Sub-CPMK via AJAX
  document.getElementById('subForm').onsubmit = function(e) {
    e.preventDefault();
    const id = document.getElementById('sub_id').value;
    const cpmkId = document.getElementById('sub_cpmk_id').value;

    const url = id 
      ? "{{ route('sub-cpmk.update', ['id' => ':id']) }}".replace(':id', id)
      : "{{ route('sub-cpmk.store', ['cpmkId' => ':cpmkId']) }}".replace(':cpmkId', cpmkId);

    const method = id ? 'PUT' : 'POST';
    const data = {};
    const fd = new FormData(this);
    fd.forEach((v, k) => { data[k] = v; });

    fetch(url, {
      method: method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        location.reload();
      } else {
        alert(res.message);
      }
    })
    .catch(err => alert('Terjadi kesalahan koneksi server.'));
  };

  // Delete Sub-CPMK
  function confirmDeleteSub(id, kode) {
    if (!confirm('Anda yakin ingin menghapus ' + kode + '?')) return;

    const url = "{{ route('sub-cpmk.destroy', ['id' => ':id']) }}".replace(':id', id);

    fetch(url, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        location.reload();
      } else {
        alert(res.message);
      }
    });
  }

  // Copy CPMK Form Submit
  @if(!$isReadOnly)
    document.getElementById('copyCpmkForm').onsubmit = function(e) {
      e.preventDefault();
      const url = "{{ route('curiculum.course.cpmk.copy', ['kurKode' => $kurikulum->kurKode, 'courseId' => $course->id]) }}";
      const sourceKode = document.getElementById('source_kurikulum_kode').value;

      if (!confirm('PENTING: Seluruh CPMK yang ada pada mata kuliah ini akan terhapus dan digantikan oleh struktur yang disalin. Lanjutkan?')) return;

      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ source_kurikulum_kode: sourceKode })
      })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          alert(res.message);
          location.reload();
        } else {
          alert(res.message);
        }
      });
    };
  @endif
</script>

<style>
  /* Custom micro-animations and typography style */
  .accordion-arrow {
    transition: transform 0.2s ease;
  }
  .collapsed .accordion-arrow {
    transform: rotate(-90deg);
  }
  
  @keyframes pulse {
    0% {
      transform: scale(1);
      opacity: 1;
    }
    50% {
      transform: scale(1.05);
      opacity: 0.8;
    }
    100% {
      transform: scale(1);
      opacity: 1;
    }
  }
  .animate-pulse {
    animation: pulse 2s infinite;
  }
</style>
@endsection
