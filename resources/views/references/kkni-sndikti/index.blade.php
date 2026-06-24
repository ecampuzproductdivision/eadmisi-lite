@extends('layouts.app')

@section('content')
<main class="p-2">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2 text-success"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-triangle fs-4 me-2 text-danger"></i>
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- ================================================================ -->
  <!-- PRD 13.1 — DASHBOARD REFERENSI                                    -->
  <!-- ================================================================ -->
  <div class="card border-1 mb-6">
    <div class="card-body bg-white py-4">
      <div class="row mb-4 align-items-center">
        <div class="col-md-6 col-12">
          <h3 class="mb-1 mt-2 fw-bold">Referensi KKNI & SN-Dikti</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Data Master</a></li>
              <li class="breadcrumb-item active" aria-current="page">KKNI SNDikti</li>
            </ol>
          </nav>
        </div>
        <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end flex-wrap">
          <a href="{{ route('kkni-sndikti.admin.regulasi.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-circle-plus fs-4"></i> Tambah Regulasi
          </a>
          <a href="{{ route('kkni-sndikti.changelog') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
            <i class="ti ti-history fs-4"></i> Riwayat
          </a>
          <a href="{{ route('kkni-sndikti.laporan') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-report fs-4"></i> Laporan CPL
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- Alert Perubahan Draft -->
  @if($draftRegulasi->count() > 0)
    <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <i class="ti ti-alert-triangle fs-4 me-2 text-warning"></i>
        <div>
          <strong>Perhatian!</strong> Terdapat {{ $draftRegulasi->count() }} regulasi berstatus Draft yang belum diaktifkan:
          @foreach($draftRegulasi as $draft)
            <a href="{{ route('kkni-sndikti.admin.regulasi.edit', $draft->id_regulasi) }}" class="badge bg-warning text-dark ms-2 text-decoration-none" title="Kelola regulasi draft ini">
              <i class="ti ti-external-link me-1" style="font-size: 0.7rem;"></i>{{ $draft->nomor_peraturan }} ({{ $draft->versi }})
            </a>
          @endforeach
        </div>
      </div>
      <a href="#kelola" class="btn btn-sm btn-outline-dark" onclick="document.getElementById('kelola-tab').click(); return false;">
        <i class="ti ti-settings me-1"></i>Kelola Regulasi
      </a>
    </div>
  @endif

  <!-- Panel Regulasi Aktif -->
  <div class="row g-4 mb-6">
    <div class="col-md-6">
      <div class="card card-lg h-100">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <div class="icon-box bg-primary bg-opacity-10 p-3 rounded-3 me-3">
              <i class="ti ti-certificate-2 fs-4 text-primary"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-0">KKNI (Aktif)</h6>
              <small class="text-muted">Kerangka Kualifikasi Nasional Indonesia</small>
            </div>
            @if($regulasiKkni)
              <span class="badge bg-success ms-auto">Aktif</span>
            @else
              <span class="badge bg-danger ms-auto">Tidak Ada</span>
            @endif
          </div>
          @if($regulasiKkni)
            <table class="table table-sm table-borderless mb-0">
              <tr><td class="text-muted ps-0" width="140">Nomor Peraturan</td><td class="fw-semibold">{{ $regulasiKkni->nomor_peraturan }}</td></tr>
              <tr><td class="text-muted ps-0">Tanggal Terbit</td><td>{{ $regulasiKkni->tanggal_terbit->format('d F Y') }}</td></tr>
              <tr><td class="text-muted ps-0">Versi</td><td><span class="badge bg-secondary">{{ $regulasiKkni->versi }}</span></td></tr>
              <tr><td class="text-muted ps-0">Instansi</td><td>{{ $regulasiKkni->instansi_penerbit }}</td></tr>
            </table>
          @else
            <p class="text-muted mb-0">Belum ada data regulasi KKNI.</p>
          @endif
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card card-lg h-100">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <div class="icon-box bg-success bg-opacity-10 p-3 rounded-3 me-3">
              <i class="ti ti-file-text fs-4 text-success"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-0">SN-Dikti (Aktif)</h6>
              <small class="text-muted">Standar Nasional Pendidikan Tinggi</small>
            </div>
            @if($regulasiSndikti)
              <span class="badge bg-success ms-auto">Aktif</span>
            @else
              <span class="badge bg-danger ms-auto">Tidak Ada</span>
            @endif
          </div>
          @if($regulasiSndikti)
            <table class="table table-sm table-borderless mb-0">
              <tr><td class="text-muted ps-0" width="140">Nomor Peraturan</td><td class="fw-semibold">{{ $regulasiSndikti->nomor_peraturan }}</td></tr>
              <tr><td class="text-muted ps-0">Tanggal Terbit</td><td>{{ $regulasiSndikti->tanggal_terbit->format('d F Y') }}</td></tr>
              <tr><td class="text-muted ps-0">Versi</td><td><span class="badge bg-secondary">{{ $regulasiSndikti->versi }}</span></td></tr>
              <tr><td class="text-muted ps-0">Instansi</td><td>{{ $regulasiSndikti->instansi_penerbit }}</td></tr>
            </table>
          @else
            <p class="text-muted mb-0">Belum ada data regulasi SN-Dikti.</p>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Statistik Data -->
  <div class="row g-3 mb-6">
    <div class="col-md-3 col-6">
      <div class="card card-lg bg-primary-subtle text-center py-4">
        <h2 class="fw-bold mb-0 ">{{ $totalLevelKkni }}</h2>
        <label class="text-muted">Level KKNI</label>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card card-lg bg-success-subtle text-center py-4">
        <h2 class="fw-bold mb-0">{{ $totalButirSikap }}</h2>
        <label class="text-muted">Butir Sikap</label>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card card-lg bg-warning-subtle text-center py-4">
        <h2 class="fw-bold mb-0">{{ $totalButirKu }}</h2>
        <label class="text-muted">Butir Keterampilan Umum</label>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card card-lg bg-info-subtle text-center py-4">
        <h2 class="fw-bold mb-0">{{ $totalDeskriptor }}</h2>
        <label class="text-muted">Deskriptor KKNI</label>
      </div>
    </div>
  </div>

  <!-- Riwayat Perubahan Terbaru -->
  @if($recentChanges->count() > 0)
  <div class="card border-1 bg-light mt-4">
    <div class="card-body p-3">
      <h6 class="fw-bold mb-2 d-flex align-items-center">
        <i class="ti ti-history me-2"></i> Riwayat Perubahan Terbaru
      </h6>
      <div class="table-responsive">
        <table class="table table-sm table-borderless mb-0">
          <thead class="table-light">
            <tr>
              <th>Tanggal</th>
              <th>Tabel</th>
              <th>Aksi</th>
              <th>Alasan</th>
              <th>Oleh</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentChanges as $log)
            <tr>
              <td class="text-nowrap">{{ $log->changed_at instanceof \Carbon\Carbon ? $log->changed_at->format('d/m/Y H:i') : $log->changed_at }}</td>
              <td><span class="badge bg-secondary">{{ $log->tabel_terdampak }}</span></td>
              <td>
                @php
                  $aksiColors = ['INSERT' => 'success', 'UPDATE' => 'primary', 'DEACTIVATE' => 'danger', 'ACTIVATE' => 'warning'];
                @endphp
                <span class="badge bg-{{ $aksiColors[$log->aksi] ?? 'secondary' }}">{{ $log->aksi }}</span>
              </td>
              <td class="text-wrap" style="max-width: 300px;">{{ $log->alasan_perubahan }}</td>
              <td>{{ $log->changedBy->name ?? '-' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endif

  <!-- ================================================================ -->
  <!-- TABS: KKNI | SN-Dikti | Pemetaan Jenjang                          -->
  <!-- ================================================================ -->
  <ul class="nav nav-lb-tab border-bottom" id="refTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active fw-semibold" id="kkni-tab" data-bs-toggle="tab" data-bs-target="#kkni" type="button" role="tab" aria-controls="kkni" aria-selected="true">
        <i class="ti ti-certificate-2 me-1"></i> Referensi KKNI
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-semibold" id="sndikti-tab" data-bs-toggle="tab" data-bs-target="#sndikti" type="button" role="tab" aria-controls="sndikti" aria-selected="false">
        <i class="ti ti-file-text me-1"></i> Referensi SN-Dikti
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-semibold" id="pemetaan-tab" data-bs-toggle="tab" data-bs-target="#pemetaan" type="button" role="tab" aria-controls="pemetaan" aria-selected="false">
        <i class="ti ti-mapping me-1"></i> Pemetaan Jenjang
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-semibold" id="kelola-tab" data-bs-toggle="tab" data-bs-target="#kelola" type="button" role="tab" aria-controls="kelola" aria-selected="false">
        <i class="ti ti-settings me-1"></i> Kelola Regulasi
        @if($draftRegulasi->count() > 0)
          <span class="badge bg-warning text-dark ms-1">{{ $draftRegulasi->count() }} Draft</span>
        @endif
      </button>
    </li>
  </ul>

  <div class="tab-content" id="refTabsContent">
    <!-- ========== TAB 1: REFERENSI KKNI ========== -->
    <div class="tab-pane fade show active" id="kkni" role="tabpanel" aria-labelledby="kkni-tab">
      <div class="card card-lg">
        <div class="card-body p-4">
          <!-- PRD 13.2: Selector Versi -->
          <div class="row mb-4 align-items-center">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Pilih Versi Regulasi KKNI</label>
              <select class="form-select" id="kkni-versi-selector">
                @foreach($semuaRegulasiKkni as $reg)
                  <option value="{{ $reg->id_regulasi }}" {{ $reg->is_aktif ? 'selected' : '' }}>
                    {{ $reg->nomor_peraturan }} ({{ $reg->versi }}) {{ $reg->is_aktif ? '— Aktif' : '' }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
              <div class="alert alert-danger d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-info-circle me-2">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                  <path d="M12 9h.01"></path>
                  <path d="M11 12h1v4h1"></path>
                </svg>
                <div class="py-2">
                  Data bersifat read-only. 
                  @if($regulasiKkni)
                    Regulasi aktif: <span class="fw-semibold">{{ $regulasiKkni->nomor_peraturan }}</span>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <!-- PRD 13.2: Tabel Pemetaan Jenjang (small reference table) -->
          <div class="row">
            <div class="col-12">
              <h6 class="fw-bold mb-2">Pemetaan Jenjang Pendidikan → Level KKNI</h6>
              <div class="table-responsive">
                <table class="table table-sm mb-4">
                  <thead class="table-white">
                    <tr>
                      <th>Jenjang</th>
                      <th>Level KKNI</th>
                      <th>Deskripsi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($pemetaanJenjang as $p)
                    <tr>
                      <td><span class="badge text-bg-dark">{{ $p->jenjang }}</span></td>
                      <td>{{ $p->level_kkni }}</td>
                      <td>{{ $p->keterangan }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- PRD 13.2: Accordion per Level KKNI -->
          <div class="accordion accordion-flush" id="kkniAccordion">
            @forelse($kkniLevels as $level)
            <div class="accordion-item kkni-level-card" data-level="{{ $level->level }}" data-jenjang="{{ $level->jenjang_pendidikan }}">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $level->level }}" aria-expanded="false" aria-controls="flush-collapse{{ $level->level }}">
                  <div class="d-flex align-items-center gap-3 w-100 me-2">
                    <span class="badge bg-{{ $level->level >= 6 ? 'primary' : 'secondary' }} px-3 py-2 rounded-pill">Level {{ $level->level }}</span>
                    <div>
                      <h6 class="fw-bold mb-0 text-dark text-start">{{ $level->nama_level }}</h6>
                      @if($level->jenjang_pendidikan)
                        <small class="text-muted d-block text-start">Jenjang: {{ $level->jenjang_pendidikan }}</small>
                      @endif
                    </div>
                    @if($level->level >= 6)
                      <span class="badge bg-warning text-dark ms-2">Relevan</span>
                    @endif
                  </div>
                </button>
              </h2>
              <div id="flush-collapse{{ $level->level }}" class="accordion-collapse collapse" data-bs-parent="#kkniAccordion">
                <div class="accordion-body">
                  <div class="py-3 mb-4">
                    <div class="d-flex gap-2">
                      <i class="ti ti-info-circle text-info fs-5 mt-1"></i>
                      <div>
                        <h6 class="fw-bold text-dark mb-1">Deskripsi Umum</h6>
                        <p class="text-muted mb-0 small">{{ $level->deskripsi_umum }}</p>
                      </div>
                    </div>
                  </div>

                  @php
                    $deskriptorByArea = $level->deskriptors->groupBy('area_kompetensi');
                    $areaLabels = [
                      'Sikap & Tata Nilai' => ['icon' => 'ti ti-heart-handshake', 'color' => 'danger'],
                      'Kemampuan Kerja' => ['icon' => 'ti ti-tools', 'color' => 'primary'],
                      'Pengetahuan' => ['icon' => 'ti ti-brain', 'color' => 'success'],
                      'Tanggung Jawab & Hak' => ['icon' => 'ti ti-shield-check', 'color' => 'warning'],
                    ];
                  @endphp

                  <div class="row g-3">
                    @foreach($areaLabels as $area => $meta)
                      <div class="col-md-6">
                        <div class="card h-100 bg-light bg-opacity-30">
                          <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-3">
                              <span class="d-inline-flex align-items-center justify-content-center bg-{{ $meta['color'] }} bg-opacity-10 text-{{ $meta['color'] }} rounded p-2 me-2">
                                <i class="ti {{ $meta['icon'] }} fs-5"></i>
                              </span>
                              <h6 class="fw-bold mb-0 text-dark">{{ $area }}</h6>
                            </div>
                            @if(isset($deskriptorByArea[$area]))
                              <div class="d-flex flex-column gap-2">
                                @foreach($deskriptorByArea[$area] as $desk)
                                  <div class="border-{{ $meta['color'] }}">
                                    <div class="d-flex align-items-start gap-2">
                                      <span class="badge bg-{{ $meta['color'] }} bg-opacity-10 text-{{ $meta['color'] }} fw-semibold" style="font-size: 0.75rem;">{{ $desk->kode_deskriptor }}</span>
                                      <span class="small text-dark lh-base">{{ $desk->deskripsi }}</span>
                                    </div>
                                  </div>
                                @endforeach
                              </div>
                            @else
                              <div class="text-center py-4 bg-white rounded-3 border border-dashed border-2 text-muted">
                                <i class="ti ti-ban fs-5 mb-1 d-block text-secondary"></i>
                                <span class="small">Belum tersedia</span>
                              </div>
                            @endif
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
            @empty
            <div class="text-center py-5">
              <i class="ti ti-file-unknown fs-1 text-muted"></i>
              <p class="text-muted mt-2">Belum ada data KKNI Level.</p>
            </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    <!-- ========== TAB 2: REFERENSI SN-Dikti ========== -->
    <div class="tab-pane fade" id="sndikti" role="tabpanel" aria-labelledby="sndikti-tab">
      <div class="card border-1 shadow-sm">
        <div class="card-body p-4">

          <!-- PRD 13.3: Selector Versi -->
          <div class="row mb-4 align-items-center">
            <div class="col-md-4">
              <label class="form-label fw-semibold small">Pilih Versi Regulasi SN-Dikti</label>
              <select class="form-select form-select-sm" id="sndikti-versi-selector">
                @foreach($semuaRegulasiSndikti as $reg)
                  <option value="{{ $reg->id_regulasi }}" {{ $reg->is_aktif ? 'selected' : '' }}>
                    {{ $reg->nomor_peraturan }} ({{ $reg->versi }}) {{ $reg->is_aktif ? '— Aktif' : '' }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small">Cari Butir</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" class="form-control border-start-0" id="sndikti-search" placeholder="Cari berdasarkan kata kunci...">
              </div>
            </div>
            <div class="col-md-4 text-md-end mt-2 mt-md-0">
              <span class="badge bg-success" id="sndikti-wajib-count"></span>
              <span class="badge bg-secondary ms-1" id="sndikti-total-count"></span>
            </div>
          </div>

          <!-- PRD 13.3: Tab per Jenjang -->
          <ul class="nav nav-pills mb-4 gap-2" id="jenjangTabs" role="tablist">
            @foreach($jenjangList as $jenjang)
            <li class="nav-item" role="presentation">
              <button class="nav-link {{ $loop->first ? 'active' : '' }} fw-semibold" 
                      id="jenjang-{{ $jenjang }}-tab" 
                      data-bs-toggle="tab" 
                      data-bs-target="#jenjang-{{ $jenjang }}" 
                      type="button" role="tab"
                      data-jenjang="{{ $jenjang }}">
                {{ $jenjang }}
              </button>
            </li>
            @endforeach
          </ul>

          <div class="tab-content" id="jenjangTabsContent">
            @foreach($jenjangList as $jenjang)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                 id="jenjang-{{ $jenjang }}" 
                 role="tabpanel" 
                 data-jenjang="{{ $jenjang }}">

              @php 
                $dataJenjang = $sndiktiPerJenjang[$jenjang];
                $kategoriMeta = [
                  'Sikap' => ['icon' => 'ti ti-heart-handshake', 'color' => 'danger'],
                  'Pengetahuan' => ['icon' => 'ti ti-brain', 'color' => 'info'],
                  'Keterampilan Umum' => ['icon' => 'ti ti-tools', 'color' => 'primary'],
                  'Keterampilan Khusus' => ['icon' => 'ti ti-star', 'color' => 'warning'],
                ];
              @endphp

              <div class="row g-3">
                @foreach($kategoriMeta as $kategori => $meta)
                <div class="col-md-6">
                  <div class="card border-1 h-100">
                    <div class="card-header bg-white border-bottom py-2 d-flex align-items-center justify-content-between">
                      <h6 class="fw-bold mb-0"><i class="ti {{ $meta['icon'] }} text-{{ $meta['color'] }} me-1"></i> {{ $kategori }}</h6>
                      <span class="badge bg-{{ $meta['color'] }}">{{ $dataJenjang[$kategori]->count() }} butir</span>
                    </div>
                    <div class="card-body p-2">
                      @forelse($dataJenjang[$kategori] as $butir)
                        <div class="p-2 mb-1 bg-light rounded border-start border-3 border-{{ $meta['color'] }} sndikti-item {{ $butir->is_wajib ? 'wajib' : 'opsional' }}" data-kode="{{ $butir->kode_butir }}" data-search="{{ $butir->kode_butir }} {{ $butir->deskripsi }}">
                          <div class="d-flex align-items-start justify-content-between">
                            <div>
                              <span class="badge bg-{{ $meta['color'] }} bg-opacity-10 text-{{ $meta['color'] }} me-1">{{ $butir->kode_butir }}</span>
                              @if($butir->is_wajib)
                                <span class="badge bg-success badge-sm">Wajib</span>
                              @else
                                <span class="badge bg-secondary badge-sm">Opsional</span>
                              @endif
                            </div>
                          </div>
                          <small class="d-block mt-1 text-wrap">{{ $butir->deskripsi }}</small>
                        </div>
                      @empty
                        <p class="text-muted small text-center py-3"><em>Tidak ada data</em></p>
                      @endforelse
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            @endforeach
          </div>

        </div>
      </div>
    </div>

    <!-- ========== TAB 3: PEMETAAN JENJANG ========== -->
    <div class="tab-pane fade" id="pemetaan" role="tabpanel" aria-labelledby="pemetaan-tab">
      <div class="card border-1 shadow-sm">
        <div class="card-body p-4">
          <h5 class="fw-bold mb-4">
            <i class="ti ti-mapping me-2"></i> Pemetaan Jenjang Pendidikan Tinggi
          </h5>

          <div class="row g-4 mb-4">
            @foreach($pemetaanJenjang as $p)
            <div class="col-md-4 col-6">
              <div class="card border-1 shadow-sm h-100">
                <div class="card-body text-center py-4">
                  <span class="badge bg-dark fs-6 mb-2">{{ $p->jenjang }}</span>
                  <h2 class="fw-bold mb-1 text-primary">Level {{ $p->level_kkni }}</h2>
                  <small class="text-muted d-block mb-2">{{ $p->keterangan }}</small>
                  <div class="row g-1 mt-2">
                    <div class="col-6">
                      <small class="text-muted d-block">Min. Sikap</small>
                      <span class="fw-bold">{{ $p->min_butir_sikap }}</span>
                    </div>
                    <div class="col-6">
                      <small class="text-muted d-block">Min. KU</small>
                      <span class="fw-bold">{{ $p->min_butir_ku }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>

          <!-- Ringkasan Minimum Butir per Jenjang -->
          <div class="card border-1 bg-light">
            <div class="card-body py-3">
              <h6 class="fw-bold mb-2">Ringkasan Minimum Butir Wajib SN-Dikti</h6>
              <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0">
                  <thead class="table-white">
                    <tr>
                      <th>Jenjang</th>
                      <th>Level KKNI</th>
                      <th class="text-center">Min. Butir Sikap</th>
                      <th class="text-center">Min. Butir KU</th>
                      <th class="text-center">Total Minimum</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($pemetaanJenjang as $p)
                    <tr>
                      <td><span class="badge bg-dark">{{ $p->jenjang }}</span></td>
                      <td class="fw-bold text-center">{{ $p->level_kkni }}</td>
                      <td class="text-center">{{ $p->min_butir_sikap }}</td>
                      <td class="text-center">{{ $p->min_butir_ku }}</td>
                      <td class="text-center fw-bold text-primary">{{ $p->min_butir_sikap + $p->min_butir_ku }}</td>
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

    <!-- ========== TAB 4: KELOLA REGULASI ========== -->
    <div class="tab-pane fade" id="kelola" role="tabpanel" aria-labelledby="kelola-tab">
      <div class="card border-1 shadow-sm">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">
              <i class="ti ti-settings me-2"></i> Kelola Regulasi Draft
              @if($draftRegulasi->count() > 0)
                <span class="badge bg-warning text-dark ms-2">{{ $draftRegulasi->count() }} Draft</span>
              @endif
            </h5>
            <a href="{{ route('kkni-sndikti.admin.regulasi.create') }}" class="btn btn-dark btn-sm">
              <i class="ti ti-circle-plus me-1"></i> Tambah Regulasi Baru
            </a>
          </div>

          @if($draftRegulasi->count() > 0)
          <div class="alert alert-info border-0 d-flex align-items-center mb-4">
            <i class="ti ti-info-circle fs-4 me-2 text-info"></i>
            <div>Berikut adalah daftar regulasi berstatus <strong>Draft</strong> yang siap untuk dikelola, dipreview, atau dihapus.</div>
          </div>

          <!-- Tabel Semua Regulasi Draft (KKNI + SN-Dikti) -->
          <div class="table-responsive">
            <table class="table table-sm table-hover align-middle" id="draft-table">
              <thead class="table-warning">
                <tr>
                  <th>No</th>
                  <th>Jenis</th>
                  <th>Nomor Peraturan</th>
                  <th>Versi</th>
                  <th>Tanggal Berlaku</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($draftRegulasi as $reg)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td><span class="badge bg-{{ $reg->jenis_regulasi === 'KKNI' ? 'primary' : 'success' }}">{{ $reg->jenis_regulasi }}</span></td>
                  <td class="fw-semibold">{{ $reg->nomor_peraturan }}</td>
                  <td><span class="badge bg-secondary">{{ $reg->versi }}</span></td>
                  <td>{{ $reg->tanggal_berlaku ? \Carbon\Carbon::parse($reg->tanggal_berlaku)->format('d/m/Y') : '-' }}</td>
                  <td class="text-center">
                    <div class="d-flex gap-1 justify-content-center">
                      <a href="{{ route('kkni-sndikti.admin.regulasi.edit', $reg->id_regulasi) }}" class="btn btn-sm btn-outline-primary" title="Edit & Kelola Butir">
                        <i class="ti ti-edit"></i> Edit
                      </a>
                      <a href="{{ route('kkni-sndikti.admin.preview-aktivasi', $reg->id_regulasi) }}" class="btn btn-sm btn-outline-success" title="Preview & Aktifkan">
                        <i class="ti ti-toggle-left"></i> Aktifkan
                      </a>
                      <form action="{{ route('kkni-sndikti.admin.regulasi.destroy', $reg->id_regulasi) }}" method="POST" onsubmit="return confirm('Hapus regulasi Draft \"{{ $reg->nomor_peraturan }}\" secara permanen? Seluruh data butir terkait juga akan dihapus.')" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Draft">
                          <i class="ti ti-trash"></i> Hapus
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @else
          <div class="text-center py-5">
            <i class="ti ti-circle-check fs-1 text-success"></i>
            <p class="mt-2 text-muted">Tidak ada regulasi berstatus Draft. Semua regulasi sudah aktif atau tidak berlaku.</p>
            <a href="{{ route('kkni-sndikti.admin.regulasi.create') }}" class="btn btn-dark mt-2">
              <i class="ti ti-circle-plus me-1"></i> Buat Regulasi Baru
            </a>
          </div>
          @endif
        </div>
      </div>
    </div>

  </div>

</main>

<style>
.sndikti-item {
  transition: background-color 0.2s;
}
.sndikti-item:hover {
  background-color: #e9ecef !important;
}
.sndikti-item.d-none {
  display: none !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // === KKNI Versi Selector ===
  const kkniSelector = document.getElementById('kkni-versi-selector');
  if (kkniSelector) {
    kkniSelector.addEventListener('change', function() {
      const regulasiId = this.value;
      fetch(`{{ url('/references/kkni-sndikti/kkni-by-versi') }}/${regulasiId}`)
        .then(res => res.json())
        .then(data => {
          // Reload page with selected version
          window.location.href = `{{ route('kkni-sndikti.index') }}?regulasi_kkni=${regulasiId}`;
        })
        .catch(err => console.error('Error:', err));
    });
  }

  // === SN-Dikti Versi Selector ===
  const sndiktiSelector = document.getElementById('sndikti-versi-selector');
  if (sndiktiSelector) {
    sndiktiSelector.addEventListener('change', function() {
      window.location.href = `{{ route('kkni-sndikti.index') }}?regulasi_sndikti=${this.value}`;
    });
  }

  // === SN-Dikti Search ===
  const searchInput = document.getElementById('sndikti-search');
  if (searchInput) {
    searchInput.addEventListener('keyup', function() {
      const keyword = this.value.toLowerCase().trim();
      document.querySelectorAll('.sndikti-item').forEach(item => {
        const searchText = item.getAttribute('data-search') || '';
        if (keyword === '' || searchText.toLowerCase().includes(keyword)) {
          item.classList.remove('d-none');
        } else {
          item.classList.add('d-none');
        }
      });
      updateSndiktiCounts();
    });
  }

  // === Update Counts ===
  function updateSndiktiCounts() {
    const visibleItems = document.querySelectorAll('.sndikti-item:not(.d-none)');
    const visibleWajib = document.querySelectorAll('.sndikti-item:not(.d-none).wajib');
    const totalItems = document.querySelectorAll('.sndikti-item');
    
    const wajibCount = document.getElementById('sndikti-wajib-count');
    const totalCount = document.getElementById('sndikti-total-count');
    
    if (wajibCount) wajibCount.textContent = visibleWajib.length + ' Wajib';
    if (totalCount) totalCount.textContent = visibleItems.length + ' / ' + totalItems.length + ' ditampilkan';
  }
  
  updateSndiktiCounts();

  // === Filter by Jenjang Tab ===
  document.querySelectorAll('#jenjangTabs .nav-link').forEach(tab => {
    tab.addEventListener('shown.bs.tab', function() {
      updateSndiktiCounts();
    });
  });
});
</script>
@endsection