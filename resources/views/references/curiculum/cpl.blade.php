@extends('layouts.app')

@section('content')
<main class="p-2">
  <!-- Header Card -->
  <div class="card border-1 mb-4 shadow-xs">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <h3 class="mb-1 fw-bold text-dark d-flex align-items-center">
            Kurikulum OBE — CPL Program Studi
            <span class="badge bg-primary-subtle text-primary border border-primary ms-2 fs-6 px-3 py-1">
              {{ $kurikulum->kurNama }}
            </span>
          </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">References</a></li>
              <li class="breadcrumb-item"><a href="{{ route('curiculum.index') }}">Kurikulum</a></li>
              <li class="breadcrumb-item"><a href="{{ route('curiculum.show', $kurikulum->kurKode) }}">{{ $kurikulum->kurKode }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">OBE CPL</li>
            </ol>
          </nav>
        </div>
        <div class="d-flex gap-2">
          <div class="dropdown">
            <button class="btn btn-outline-dark fw-semibold dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="ti ti-download me-1"></i> Ekspor Matriks
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
              <li>
                <a class="dropdown-item" href="{{ route('curiculum.cpl.export-excel', $kurikulum->kurKode) }}">
                  <i class="ti ti-file-type-csv me-2 text-success"></i> Ekspor ke CSV / Excel
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('curiculum.cpl.export-pdf', $kurikulum->kurKode) }}" target="_blank">
                  <i class="ti ti-file-type-pdf me-2 text-danger"></i> Cetak / Simpan PDF
                </a>
              </li>
            </ul>
          </div>
          <a href="{{ route('curiculum.show', $kurikulum->kurKode) }}" class="btn btn-light border fw-semibold text-dark">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Kurikulum
          </a>
        </div>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
      <i class="ti ti-circle-check me-2 align-middle"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Dynamic Validasi SN-Dikti Checklist Banner -->
  <div class="card border-1 mb-4 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #f8fafd 0%, #edf4fc 100%);">
    <div class="card-body p-4">
      <div class="d-flex align-items-start gap-3">
        <div class="p-3 bg-white rounded-3 shadow-xs">
          <i class="ti {{ empty($missingSikap) && empty($missingKu) ? 'ti-shield-check text-success' : 'ti-shield-alert text-warning' }} fs-2"></i>
        </div>
        <div class="flex-grow-1">
          <h5 class="fw-bold text-dark mb-1">Status Kepatuhan Regulasi SN-Dikti</h5>
          @if(empty($missingSikap) && empty($missingKu))
            <p class="text-success fw-medium mb-0 small d-flex align-items-center">
              <i class="ti ti-circle-check-filled me-1"></i> Semua butir wajib SN-Dikti Sikap (S1-S10) dan Keterampilan Umum telah diadopsi ke kurikulum ini.
            </p>
          @else
            <p class="text-muted mb-2 small">
              Kurikulum OBE wajib mengadopsi butir-butir standar Sikap dan Keterampilan Umum SN-Dikti. Beberapa butir wajib belum diadopsi:
            </p>
            <div class="d-flex flex-wrap gap-2">
              @if(!empty($missingSikap))
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 fs-7">
                  Sikap Kurang: {{ implode(', ', $missingSikap) }}
                </span>
              @endif
              @if(!empty($missingKu))
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1.5 fs-7">
                  Keterampilan Umum Kurang: {{ implode(', ', $missingKu) }}
                </span>
              @endif
              <button class="btn btn-xs btn-primary fw-bold ms-auto py-1 px-3" data-bs-toggle="modal" data-bs-target="#sndiktiLookupModal">
                <i class="ti ti-search me-1"></i> Adopsi dari SN-Dikti
              </button>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Main Tabs Navigation -->
  <div class="card border-1 shadow-xs mb-4">
    <div class="card-header bg-white border-bottom-0 pb-0">
      <ul class="nav nav-tabs nav-line-tabs border-bottom-0 gap-4" id="cplWorkspaceTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active fw-bold text-uppercase fs-7 pb-3" id="cpl-list-tab" data-bs-toggle="tab" data-bs-target="#cpl-list" type="button" role="tab" aria-controls="cpl-list" aria-selected="true">
            <i class="ti ti-list me-1"></i> Daftar CPL
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-bold text-uppercase fs-7 pb-3" id="cpl-mk-tab" data-bs-toggle="tab" data-bs-target="#cpl-mk" type="button" role="tab" aria-controls="cpl-mk" aria-selected="false">
            <i class="ti ti-table me-1"></i> Matriks CPL–Mata Kuliah
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-bold text-uppercase fs-7 pb-3" id="cpl-pl-tab" data-bs-toggle="tab" data-bs-target="#cpl-pl" type="button" role="tab" aria-controls="cpl-pl" aria-selected="false">
            <i class="ti ti-git-merge me-1"></i> Matriks CPL–Profil Lulusan
          </button>
        </li>
      </ul>
    </div>
    
    <div class="card-body p-0">
      <div class="tab-content" id="cplWorkspaceTabsContent">
        
        <!-- TAB 1: DAFTAR CPL -->
        <div class="tab-pane fade show active p-4" id="cpl-list" role="tabpanel" aria-labelledby="cpl-list-tab">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h5 class="fw-bold mb-1 text-dark">Pengelolaan Capaian Pembelajaran Lulusan (CPL)</h5>
              <p class="text-muted mb-0 small">Kebutuhan standar kompetensi kelulusan prodi.</p>
            </div>
            @if(!$isReadOnly)
              <div class="d-flex gap-2">
                <button class="btn btn-dark d-flex align-items-center gap-1.5 fw-semibold" id="btnCreateCpl">
                  <i class="ti ti-plus fs-4"></i> Tambah CPL
                </button>
                <button class="btn btn-outline-primary d-flex align-items-center gap-1.5 fw-semibold" data-bs-toggle="modal" data-bs-target="#sndiktiLookupModal">
                  <i class="ti ti-database fs-4"></i> Referensi SN-Dikti
                </button>
              </div>
            @endif
          </div>

          <!-- Summary stats -->
          <div class="row g-3 mb-4">
            @foreach(['Sikap' => '#0d6efd', 'Pengetahuan' => '#198754', 'Keterampilan Umum' => '#ffc107', 'Keterampilan Khusus' => '#dc3545'] as $cat => $color)
              @php $count = $cpls->where('kategori', $cat)->count(); @endphp
              <div class="col-md-3">
                <div class="card shadow-none border rounded-3 p-3 position-relative overflow-hidden">
                  <div style="width: 4px; height: 100%; position: absolute; left:0; top:0; background: {{ $color }};"></div>
                  <span class="text-muted small fw-bold text-uppercase d-block mb-1">{{ $cat }}</span>
                  <span class="h3 mb-0 fw-bold">{{ $count }} <span class="fs-6 text-muted font-normal">CPL</span></span>
                </div>
              </div>
            @endforeach
          </div>

          <!-- CPL Table Grouped by Category -->
          <div class="table-responsive">
            <table class="table table-hover align-middle border mb-0">
              <thead class="bg-light">
                <tr>
                  <th width="50" class="text-center fw-semibold">No</th>
                  <th width="100" class="fw-semibold">Kode</th>
                  <th width="200" class="fw-semibold">Kategori</th>
                  <th class="fw-semibold">Deskripsi CPL</th>
                  <th width="110" class="text-center fw-semibold">Target / Lulus</th>
                  <th width="90" class="text-center fw-semibold">Status</th>
                  @if(!$isReadOnly)
                    <th width="100" class="text-center fw-semibold">Aksi</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                @if($cpls->isEmpty())
                  <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                      <i class="ti ti-folder-open fs-1 d-block mb-2"></i>
                      <span class="fw-semibold">Belum Ada Data CPL</span>
                      <p class="small text-secondary mb-0">Silakan tambahkan CPL secara manual atau import melalui Referensi SN-Dikti.</p>
                    </td>
                  </tr>
                @else
                  @foreach($cpls as $idx => $cpl)
                    <tr class="{{ $cpl->is_aktif ? '' : 'opacity-50 bg-light' }}">
                      <td class="text-center text-muted fw-medium">{{ $idx + 1 }}</td>
                      <td>
                        <strong class="text-primary">{{ $cpl->kode_cpl }}</strong>
                      </td>
                      <td>
                        @php
                          $badgeClass = match($cpl->kategori) {
                              'Sikap' => 'bg-primary-subtle text-primary border-primary-subtle',
                              'Pengetahuan' => 'bg-success-subtle text-success border-success-subtle',
                              'Keterampilan Umum' => 'bg-warning-subtle text-warning border-warning-subtle',
                              'Keterampilan Khusus' => 'bg-danger-subtle text-danger border-danger-subtle',
                          };
                        @endphp
                        <span class="badge {{ $badgeClass }} border px-2 py-1">{{ $cpl->kategori }}</span>
                      </td>
                      <td>
                        <div class="fw-semibold text-dark mb-0.5">{{ $cpl->deskripsi_singkat ?? $cpl->kode_cpl }}</div>
                        <p class="text-muted small mb-0 line-clamp-2" title="{{ $cpl->deskripsi }}">{{ $cpl->deskripsi }}</p>
                        <span class="d-block text-secondary mt-1" style="font-size: 10px;">
                          Sumber: <strong>{{ $cpl->sumber }}</strong>
                          @if($cpl->is_dari_sndikti) <span class="badge bg-success-subtle text-success ms-1 px-1.5 py-0.5" style="font-size: 8px;">SN-Dikti ADOPT</span> @endif
                        </span>
                      </td>
                      <td class="text-center font-monospace small">
                        <div>{{ number_format($cpl->target_ketercapaian, 0) }}%</div>
                        <div class="text-muted" style="font-size: 10px;">Min: {{ number_format($cpl->batas_bawah_lulus, 0) }}</div>
                      </td>
                      <td class="text-center">
                        @if($cpl->is_aktif)
                          <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1">Aktif</span>
                        @else
                          <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1">Non-Aktif</span>
                        @endif
                      </td>
                      @if(!$isReadOnly)
                        <td class="text-center">
                          <div class="d-flex justify-content-center gap-1">
                            <button class="btn btn-xs btn-link p-1 text-primary btn-edit-cpl" 
                                    data-cpl="{{ json_encode($cpl) }}" title="Ubah CPL">
                              <i class="ti ti-edit fs-5"></i>
                            </button>
                            <button class="btn btn-xs btn-link p-1 text-secondary btn-toggle-cpl" 
                                    data-id="{{ $cpl->id_cpl }}" title="Toggle Status Aktif">
                              <i class="ti ti-power fs-5"></i>
                            </button>
                            <button class="btn btn-xs btn-link p-1 text-danger btn-delete-cpl" 
                                    data-id="{{ $cpl->id_cpl }}" title="Hapus CPL">
                              <i class="ti ti-trash fs-5"></i>
                            </button>
                          </div>
                        </td>
                      @endif
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>
        </div>

        <!-- TAB 2: MATRIKS CPL - MATA KULIAH -->
        <div class="tab-pane fade p-4" id="cpl-mk" role="tabpanel" aria-labelledby="cpl-mk-tab">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h5 class="fw-bold mb-1 text-dark">Matriks Hubungan CPL dengan Mata Kuliah</h5>
              <p class="text-muted mb-0 small">Petakan kontribusi mata kuliah terhadap pencapaian CPL Program Studi secara kualitatif.</p>
            </div>
            @if(!$isReadOnly && !$cpls->isEmpty())
              <button class="btn btn-primary fw-semibold btn-save-mk-matrix">
                <i class="ti ti-device-floppy me-1"></i> Simpan Matriks
              </button>
            @endif
          </div>

          @if($cpls->isEmpty() || $kurikulumMataKuliahs->isEmpty())
            <div class="text-center py-5 text-muted border border-dashed rounded-3">
              <i class="ti ti-table-alias fs-1 d-block mb-2"></i>
              <span class="fw-semibold">Matriks Belum Tersedia</span>
              <p class="small text-secondary mb-0">Pastikan CPL dan Mata Kuliah sudah terdefinisi pada Kurikulum.</p>
            </div>
          @else
            <form id="mkMatrixForm">
              <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
                <table class="table table-hover table-bordered align-middle mb-0" id="matrixTable">
                  <thead class="bg-light sticky-top" style="z-index: 10;">
                    <tr>
                      <th width="40" class="text-center bg-light">No</th>
                      <th width="120" class="bg-light">Kode MK</th>
                      <th width="300" class="bg-light">Nama Mata Kuliah</th>
                      <th width="60" class="text-center bg-light">SKS</th>
                      @foreach($cpls as $cpl)
                        <th class="text-center text-primary fw-bold bg-light" width="70" title="{{ $cpl->deskripsi }}">
                          <div class="text-uppercase" style="font-size: 11px;">{{ $cpl->kode_cpl }}</div>
                        </th>
                      @endforeach
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($kurikulumMataKuliahs as $mIdx => $kmk)
                      <tr>
                        <td class="text-center text-muted fw-semibold">{{ $mIdx + 1 }}</td>
                        <td><span class="badge bg-light text-dark font-monospace border px-2 py-1">{{ $kmk->mataKuliah->mk_kode ?? '-' }}</span></td>
                        <td class="fw-medium text-dark">{{ $kmk->mataKuliah->mk_nama ?? '-' }}</td>
                        <td class="text-center text-secondary fw-bold">{{ $kmk->sks_override ?? ($kmk->mataKuliah->mk_sks_total ?? 0) }}</td>
                        @foreach($cpls as $cpl)
                          @php
                            $cVal = $mkMappings->get($cpl->id_cpl)->get($kmk->id)['tingkat_kontribusi'] ?? '';
                          @endphp
                          <td class="p-1 text-center">
                            @if($isReadOnly)
                              @if($cVal === 'Tinggi') <span class="badge bg-danger text-white">H</span>
                              @elseif($cVal === 'Sedang') <span class="badge bg-warning text-dark">M</span>
                              @elseif($cVal === 'Rendah') <span class="badge bg-info text-white">L</span>
                              @else - @endif
                            @else
                              <select name="mappings[{{ $cpl->id_cpl }}][{{ $kmk->id }}]" class="form-select form-select-xs border-0 text-center font-bold cell-select" style="font-size: 11px; font-weight: 600; cursor: pointer;">
                                <option value="" class="text-muted">-</option>
                                <option value="Tinggi" class="text-danger" {{ $cVal === 'Tinggi' ? 'selected' : '' }}>H (Tinggi)</option>
                                <option value="Sedang" class="text-warning" {{ $cVal === 'Sedang' ? 'selected' : '' }}>M (Sedang)</option>
                                <option value="Rendah" class="text-info" {{ $cVal === 'Rendah' ? 'selected' : '' }}>L (Rendah)</option>
                              </select>
                            @endif
                          </td>
                        @endforeach
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </form>
          @endif
        </div>

        <!-- TAB 3: MATRIKS CPL - PROFIL LULUSAN -->
        <div class="tab-pane fade p-4" id="cpl-pl" role="tabpanel" aria-labelledby="cpl-pl-tab">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h5 class="fw-bold mb-1 text-dark">Matriks Hubungan CPL dengan Profil Lulusan (PL)</h5>
              <p class="text-muted mb-0 small">Hubungkan CPL prodi ke Profil Lulusan untuk menjamin keterlacakan kurikulum (traceability).</p>
            </div>
            @if(!$isReadOnly && !$cpls->isEmpty() && !$profilLulusans->isEmpty())
              <button class="btn btn-primary fw-semibold btn-save-pl-matrix">
                <i class="ti ti-device-floppy me-1"></i> Simpan Pemetaan PL
              </button>
            @endif
          </div>

          @if($cpls->isEmpty() || $profilLulusans->isEmpty())
            <div class="text-center py-5 text-muted border border-dashed rounded-3">
              <i class="ti ti-network fs-1 d-block mb-2"></i>
              <span class="fw-semibold">Matriks Belum Tersedia</span>
              <p class="small text-secondary mb-0">Pastikan CPL dan Profil Lulusan sudah terdefinisi pada Kurikulum.</p>
            </div>
          @else
            <form id="plMatrixForm">
              <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                  <thead class="bg-light">
                    <tr>
                      <th width="120" class="fw-semibold">Kode CPL</th>
                      <th class="fw-semibold">Rumusan Capaian Pembelajaran Lulusan (CPL)</th>
                      @foreach($profilLulusans as $pl)
                        <th class="text-center fw-semibold text-wrap" width="160" title="{{ $pl->deskripsi }}">
                          <div class="text-primary fw-bold text-uppercase">{{ $pl->kode_pl }}</div>
                          <div class="text-muted" style="font-size: 10px; font-weight: normal; max-width: 140px; display: inline-block;">
                            {{ Str::limit($pl->deskripsi, 40) }}
                          </div>
                        </th>
                      @endforeach
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($cpls as $cpl)
                      @php $cplMappedPl = $plMappings->get($cpl->id_cpl) ?? []; @endphp
                      <tr>
                        <td><strong class="text-primary">{{ $cpl->kode_cpl }}</strong></td>
                        <td>
                          <span class="fw-semibold text-dark block-text mb-0.5">{{ $cpl->deskripsi_singkat ?? $cpl->kode_cpl }}</span>
                          <div class="text-muted small">{{ Str::limit($cpl->deskripsi, 100) }}</div>
                        </td>
                        @foreach($profilLulusans as $pl)
                          <td class="text-center p-3">
                            <div class="form-check form-check-inline justify-content-center m-0">
                              <input class="form-check-input check-large" type="checkbox" 
                                     name="mappings[{{ $cpl->id_cpl }}][]" 
                                     value="{{ $pl->id }}"
                                     {{ in_array($pl->id, $cplMappedPl) ? 'checked' : '' }}
                                     {{ $isReadOnly ? 'disabled' : '' }}
                                     style="width: 18px; height: 18px; cursor: pointer;">
                            </div>
                          </td>
                        @endforeach
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </form>
          @endif
        </div>

      </div>
    </div>
  </div>
</main>

<!-- Modal: Add / Edit CPL Form -->
<div class="modal fade" id="cplFormModal" tabindex="-1" aria-labelledby="cplFormModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header bg-dark text-white py-3" style="border-radius: 16px 16px 0 0;">
        <h5 class="modal-title fw-bold" id="cplFormModalLabel">
          <i class="ti ti-edit me-2"></i> Tambah / Edit CPL
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="cplForm">
        @csrf
        <input type="hidden" id="cpl_id" name="cpl_id" value="">
        <div class="modal-body p-4">
          <!-- Alert for Error -->
          <div class="alert alert-danger d-none" id="cplFormErrorAlert">
            <i class="ti ti-exclamation-circle me-1"></i> <span id="cplFormErrorMessage"></span>
          </div>

          <div class="row g-3">
            <div class="col-md-4">
              <label for="kode_cpl" class="form-label fw-semibold text-dark">Kode CPL <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="kode_cpl" name="kode_cpl" placeholder="Contoh: S1, P1, KU1" required>
            </div>
            
            <div class="col-md-8">
              <label for="kategori" class="form-label fw-semibold text-dark">Kategori CPL <span class="text-danger">*</span></label>
              <select class="form-select" id="kategori" name="kategori" required>
                <option value="" disabled selected>-- Pilih Kategori --</option>
                <option value="Sikap">Sikap</option>
                <option value="Pengetahuan">Pengetahuan</option>
                <option value="Keterampilan Umum">Keterampilan Umum</option>
                <option value="Keterampilan Khusus">Keterampilan Khusus</option>
              </select>
            </div>

            <div class="col-12">
              <label for="deskripsi_singkat" class="form-label fw-semibold text-dark">Deskripsi Singkat / Label</label>
              <input type="text" class="form-control" id="deskripsi_singkat" name="deskripsi_singkat" placeholder="Contoh: Bertanggung jawab, Pemrograman Basis Data">
            </div>

            <div class="col-12">
              <label for="deskripsi" class="form-label fw-semibold text-dark">Rumusan Deskripsi Lengkap CPL <span class="text-danger">*</span></label>
              <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" placeholder="Tuliskan rumusan lengkap CPL yang operasional dan dapat diukur..." required></textarea>
            </div>

            <div class="col-md-6">
              <label for="sumber" class="form-label fw-semibold text-dark">Sumber CPL <span class="text-danger">*</span></label>
              <select class="form-select" id="sumber" name="sumber" required>
                <option value="Institusi" selected>Institusi / Perguruan Tinggi</option>
                <option value="SN-Dikti">SN-Dikti (Regulasi Pusat)</option>
                <option value="KKNI">KKNI (Nasional)</option>
                <option value="Asosiasi Profesi">Asosiasi Profesi / Industri</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="level_kkni" class="form-label fw-semibold text-dark">Level KKNI (Opsional)</label>
              <select class="form-select" id="level_kkni" name="level_kkni">
                <option value="">-- Tanpa Level --</option>
                <option value="5">Level 5 (D3)</option>
                <option value="6">Level 6 (D4 / S1)</option>
                <option value="7">Level 7 (Profesi)</option>
                <option value="8">Level 8 (S2)</option>
                <option value="9">Level 9 (S3)</option>
              </select>
            </div>

            <input type="hidden" id="id_ref_sndikti" name="id_ref_sndikti" value="">

            <div class="col-md-4">
              <label for="target_ketercapaian" class="form-label fw-semibold text-dark">Target Capaian (%) <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="target_ketercapaian" name="target_ketercapaian" min="0" max="100" value="75" required>
            </div>

            <div class="col-md-4">
              <label for="batas_bawah_lulus" class="form-label fw-semibold text-dark">Batas Bawah Lulus <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="batas_bawah_lulus" name="batas_bawah_lulus" min="0" max="100" value="55" required>
            </div>

            <div class="col-md-4">
              <label for="metode_pengukuran" class="form-label fw-semibold text-dark">Metode Pengukuran <span class="text-danger">*</span></label>
              <select class="form-select" id="metode_pengukuran" name="metode_pengukuran" required>
                <option value="Agregasi CPMK" selected>Agregasi CPMK</option>
                <option value="Direct Assessment">Direct Assessment</option>
                <option value="Indirect Assessment">Indirect Assessment</option>
              </select>
            </div>
          </div>
        </div>
        
        <div class="modal-footer bg-light py-3 border-top-0 d-flex justify-content-end" style="border-radius: 0 0 16px 16px;">
          <button type="button" class="btn btn-secondary fw-semibold px-4" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary fw-semibold px-4" id="btnSaveCplSubmit">Simpan CPL</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: SN-Dikti Lookup / Adoption Panel -->
<div class="modal fade" id="sndiktiLookupModal" tabindex="-1" aria-labelledby="sndiktiLookupModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header bg-dark text-white py-3" style="border-radius: 16px 16px 0 0;">
        <h5 class="modal-title fw-bold" id="sndiktiLookupModalLabel">
          <i class="ti ti-database me-2"></i> Referensi Butir Wajib SN-Dikti
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3">
          <p class="text-muted small">
            Butir standar di bawah ini mengacu pada regulasi **Permendikbud No. 3 Tahun 2020**. Pilih butir yang ingin diadopsi langsung menjadi CPL Program Studi Anda.
          </p>
        </div>

        <ul class="nav nav-tabs mb-3 gap-3 border-bottom" id="sndiktiTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold text-dark fs-7" id="sndikti-sikap-tab" data-bs-toggle="tab" data-bs-target="#sndikti-sikap" type="button" role="tab" aria-controls="sndikti-sikap" aria-selected="true">
              Sikap (10 Butir Wajib)
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold text-dark fs-7" id="sndikti-ku-tab" data-bs-toggle="tab" data-bs-target="#sndikti-ku" type="button" role="tab" aria-controls="sndikti-ku" aria-selected="false">
              Keterampilan Umum
            </button>
          </li>
        </ul>

        <div class="tab-content" id="sndiktiTabContent">
          <!-- Sikap Tab -->
          <div class="tab-pane fade show active" id="sndikti-sikap" role="tabpanel" aria-labelledby="sndikti-sikap-tab">
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="bg-light">
                  <tr>
                    <th width="80">Kode</th>
                    <th>Deskripsi Butir Wajib</th>
                    <th width="120" class="text-center">Status</th>
                    <th width="100" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($sndiktiSikap as $ref)
                    @php 
                      $isAdopted = $cpls->where('id_ref_sndikti', $ref->id_sndikti)->first(); 
                    @endphp
                    <tr class="{{ $isAdopted ? 'bg-light-success-subtle' : '' }}">
                      <td><strong class="text-primary">{{ $ref->kode_butir }}</strong></td>
                      <td class="small">{{ $ref->deskripsi }}</td>
                      <td class="text-center">
                        @if($isAdopted)
                          <span class="badge bg-success-subtle text-success">Sudah Diadopsi</span>
                        @else
                          <span class="badge bg-secondary-subtle text-secondary">Belum Diadopsi</span>
                        @endif
                      </td>
                      <td class="text-center">
                        <button class="btn btn-sm btn-dark btn-adopt" 
                                data-ref-id="{{ $ref->id_sndikti }}"
                                data-kode="{{ $ref->kode_butir }}"
                                data-kategori="Sikap"
                                data-deskripsi="{{ $ref->deskripsi }}"
                                {{ $isAdopted || $isReadOnly ? 'disabled' : '' }}>
                          Adopsi
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <!-- Keterampilan Umum Tab -->
          <div class="tab-pane fade" id="sndikti-ku" role="tabpanel" aria-labelledby="sndikti-ku-tab">
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="bg-light">
                  <tr>
                    <th width="80">Kode</th>
                    <th width="80">Jenjang</th>
                    <th>Deskripsi Butir Wajib</th>
                    <th width="120" class="text-center">Status</th>
                    <th width="100" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($sndiktiKu as $ref)
                    @php 
                      $isAdopted = $cpls->where('id_ref_sndikti', $ref->id_sndikti)->first(); 
                    @endphp
                    <tr class="{{ $isAdopted ? 'bg-light-success-subtle' : '' }}">
                      <td><strong class="text-primary">{{ $ref->kode_butir }}</strong></td>
                      <td><span class="badge bg-dark text-white">{{ $ref->jenjang }}</span></td>
                      <td class="small">{{ $ref->deskripsi }}</td>
                      <td class="text-center">
                        @if($isAdopted)
                          <span class="badge bg-success-subtle text-success">Sudah Diadopsi</span>
                        @else
                          <span class="badge bg-secondary-subtle text-secondary">Belum Diadopsi</span>
                        @endif
                      </td>
                      <td class="text-center">
                        <button class="btn btn-sm btn-dark btn-adopt" 
                                data-ref-id="{{ $ref->id_sndikti }}"
                                data-kode="{{ $ref->kode_butir }}"
                                data-kategori="Keterampilan Umum"
                                data-deskripsi="{{ $ref->deskripsi }}"
                                {{ $isAdopted || $isReadOnly ? 'disabled' : '' }}>
                          Adopsi
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<style>
  .nav-line-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: #495057;
    background: none;
  }
  .nav-line-tabs .nav-link.active {
    border-bottom-color: #0d6efd;
    color: #0d6efd !important;
  }
  .bg-light-success-subtle {
    background-color: #e8f7ec !important;
  }
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;  
    overflow: hidden;
  }
  .cell-select:hover {
    background-color: #f1f3f5;
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Initial Modals setup
    const cplFormModalEl = document.getElementById('cplFormModal');
    const cplFormModal = new bootstrap.Modal(cplFormModalEl);
    const cplForm = document.getElementById('cplForm');
    const formErrorAlert = document.getElementById('cplFormErrorAlert');
    const formErrorMessage = document.getElementById('cplFormErrorMessage');

    // 2. Click Add CPL Action
    const btnCreateCpl = document.getElementById('btnCreateCpl');
    if (btnCreateCpl) {
        btnCreateCpl.addEventListener('click', () => {
            cplForm.reset();
            document.getElementById('cpl_id').value = '';
            document.getElementById('id_ref_sndikti').value = '';
            document.getElementById('kode_cpl').removeAttribute('readonly');
            document.getElementById('kategori').removeAttribute('disabled');
            document.getElementById('sumber').removeAttribute('disabled');
            
            formErrorAlert.classList.add('d-none');
            document.getElementById('cplFormModalLabel').innerHTML = '<i class="ti ti-plus me-2"></i> Tambah CPL Baru';
            cplFormModal.show();
        });
    }

    // 3. Click Edit CPL Action
    document.querySelectorAll('.btn-edit-cpl').forEach(btn => {
        btn.addEventListener('click', () => {
            cplForm.reset();
            const c = JSON.parse(btn.dataset.cpl);

            document.getElementById('cpl_id').value = c.id_cpl;
            document.getElementById('kode_cpl').value = c.kode_cpl;
            document.getElementById('kategori').value = c.kategori;
            document.getElementById('deskripsi_singkat').value = c.deskripsi_singkat || '';
            document.getElementById('deskripsi').value = c.deskripsi;
            document.getElementById('sumber').value = c.sumber;
            document.getElementById('level_kkni').value = c.level_kkni || '';
            document.getElementById('id_ref_sndikti').value = c.id_ref_sndikti || '';
            document.getElementById('target_ketercapaian').value = Math.round(c.target_ketercapaian);
            document.getElementById('batas_bawah_lulus').value = Math.round(c.batas_bawah_lulus);
            document.getElementById('metode_pengukuran').value = c.metode_pengukuran;

            // Make immutable fields readonly
            if(c.is_dari_sndikti) {
                document.getElementById('kode_cpl').setAttribute('readonly', 'readonly');
                document.getElementById('kategori').setAttribute('disabled', 'disabled');
                document.getElementById('sumber').setAttribute('disabled', 'disabled');
            } else {
                document.getElementById('kode_cpl').removeAttribute('readonly');
                document.getElementById('kategori').removeAttribute('disabled');
                document.getElementById('sumber').removeAttribute('disabled');
            }

            formErrorAlert.classList.add('d-none');
            document.getElementById('cplFormModalLabel').innerHTML = '<i class="ti ti-edit me-2"></i> Edit CPL ' + c.kode_cpl;
            cplFormModal.show();
        });
    });

    // 4. Submit CPL Form
    cplForm.addEventListener('submit', (e) => {
        e.preventDefault();
        formErrorAlert.classList.add('d-none');

        const cplId = document.getElementById('cpl_id').value;
        const isEdit = cplId !== '';
        const url = isEdit ? `/references/curiculum/{{ $kurikulum->kurKode }}/cpl/${cplId}` : '/references/curiculum/{{ $kurikulum->kurKode }}/cpl';
        
        // Prepare payload
        const formData = new FormData(cplForm);
        const data = {};
        formData.forEach((val, key) => {
            data[key] = val;
        });

        // Re-add disabled inputs (kategori / sumber) so they are submitted
        if (document.getElementById('kategori').disabled) {
            data['kategori'] = document.getElementById('kategori').value;
        }
        if (document.getElementById('sumber').disabled) {
            data['sumber'] = document.getElementById('sumber').value;
        }

        const method = isEdit ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(resData => {
            if (resData.success) {
                cplFormModal.hide();
                window.location.reload();
            } else {
                let msg = resData.message || 'Terjadi kesalahan.';
                if (resData.errors) {
                    msg = Object.values(resData.errors).flat().join('<br>');
                }
                formErrorMessage.innerHTML = msg;
                formErrorAlert.classList.remove('d-none');
            }
        })
        .catch(() => {
            formErrorMessage.innerText = 'Gagal menyimpan CPL. Periksa koneksi jaringan Anda.';
            formErrorAlert.classList.remove('d-none');
        });
    });

    // 5. Adopt SN-Dikti Action
    document.querySelectorAll('.btn-adopt').forEach(btn => {
        btn.addEventListener('click', () => {
            cplForm.reset();
            
            const refId = btn.dataset.refId;
            const kode = btn.dataset.kode;
            const kategori = btn.dataset.kategori;
            const deskripsi = btn.dataset.deskripsi;

            document.getElementById('cpl_id').value = '';
            document.getElementById('kode_cpl').value = kode;
            document.getElementById('kategori').value = kategori;
            document.getElementById('deskripsi').value = deskripsi;
            document.getElementById('deskripsi_singkat').value = (kategori === 'Sikap' ? 'Sikap ' : 'KU ') + kode.replace(/\D/g, '');
            document.getElementById('sumber').value = 'SN-Dikti';
            document.getElementById('id_ref_sndikti').value = refId;

            // Make immutable fields readonly
            document.getElementById('kode_cpl').setAttribute('readonly', 'readonly');
            document.getElementById('kategori').setAttribute('disabled', 'disabled');
            document.getElementById('sumber').setAttribute('disabled', 'disabled');

            // Hide the SN-Dikti modal, open the Cpl form modal
            bootstrap.Modal.getInstance(document.getElementById('sndiktiLookupModal')).hide();
            
            formErrorAlert.classList.add('d-none');
            document.getElementById('cplFormModalLabel').innerHTML = '<i class="ti ti-plus me-2"></i> Adopsi CPL dari SN-Dikti';
            cplFormModal.show();
        });
    });

    // 6. Delete CPL
    document.querySelectorAll('.btn-delete-cpl').forEach(btn => {
        btn.addEventListener('click', () => {
            if(confirm('Apakah Anda yakin ingin menghapus CPL ini? Semua data pemetaan PL dan MK yang bersangkutan akan ikut terhapus.')) {
                const id = btn.dataset.id;
                fetch(`/references/curiculum/{{ $kurikulum->kurKode }}/cpl/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Gagal menghapus CPL.');
                    }
                });
            }
        });
    });

    // 7. Toggle CPL Status
    document.querySelectorAll('.btn-toggle-cpl').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            fetch(`/references/curiculum/{{ $kurikulum->kurKode }}/cpl/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal mengubah status CPL.');
                }
            });
        });
    });

    // 8. Save PL Matrix
    const btnSavePl = document.querySelector('.btn-save-pl-matrix');
    if (btnSavePl) {
        btnSavePl.addEventListener('click', () => {
            btnSavePl.disabled = true;
            btnSavePl.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
            
            const form = document.getElementById('plMatrixForm');
            const formData = new FormData(form);
            
            // Build the JSON payload to avoid default empty field exclusions
            const payload = { mappings: {} };
            
            // Gather checkboxes
            form.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                const nameMatch = cb.name.match(/mappings\[([^\]]+)\]/);
                if (nameMatch) {
                    const cplId = nameMatch[1];
                    if (!payload.mappings[cplId]) {
                        payload.mappings[cplId] = [];
                    }
                    if (cb.checked) {
                        payload.mappings[cplId].push(cb.value);
                    }
                }
            });

            fetch('/references/curiculum/{{ $kurikulum->kurKode }}/cpl/pl-mapping', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                btnSavePl.disabled = false;
                btnSavePl.innerHTML = '<i class="ti ti-device-floppy me-1"></i> Simpan Pemetaan PL';
                if(data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal menyimpan.');
                }
            })
            .catch(() => {
                btnSavePl.disabled = false;
                btnSavePl.innerHTML = '<i class="ti ti-device-floppy me-1"></i> Simpan Pemetaan PL';
                alert('Gagal mengirim data.');
            });
        });
    }

    // 9. Save MK Matrix
    const btnSaveMk = document.querySelector('.btn-save-mk-matrix');
    if (btnSaveMk) {
        btnSaveMk.addEventListener('click', () => {
            btnSaveMk.disabled = true;
            btnSaveMk.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';

            const form = document.getElementById('mkMatrixForm');
            const payload = { mappings: {} };

            // Read selects
            form.querySelectorAll('select.cell-select').forEach(sel => {
                const nameMatch = sel.name.match(/mappings\[([^\]]+)\]\[([^\]]+)\]/);
                if (nameMatch) {
                    const cplId = nameMatch[1];
                    const kmkId = nameMatch[2];
                    if (!payload.mappings[cplId]) {
                        payload.mappings[cplId] = {};
                    }
                    if (sel.value) {
                        payload.mappings[cplId][kmkId] = sel.value;
                    }
                }
            });

            fetch('/references/curiculum/{{ $kurikulum->kurKode }}/cpl/mk-mapping', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                btnSaveMk.disabled = false;
                btnSaveMk.innerHTML = '<i class="ti ti-device-floppy me-1"></i> Simpan Matriks';
                if(data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal menyimpan.');
                }
            })
            .catch(() => {
                btnSaveMk.disabled = false;
                btnSaveMk.innerHTML = '<i class="ti ti-device-floppy me-1"></i> Simpan Matriks';
                alert('Gagal mengirim data.');
            });
        });
    }
});
</script>
@endsection
