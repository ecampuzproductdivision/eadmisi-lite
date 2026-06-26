@extends('layouts.app')

@section('content')
<main class="p-4">
  <!-- Back & Breadcrumbs -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('rps.index') }}" class="btn btn-light d-inline-flex align-items-center gap-2" style="border-radius: 8px;">
      <i class="ti ti-arrow-left"></i> Kembali ke Daftar
    </a>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('rps.index') }}">Penyusunan RPS</a></li>
        <li class="breadcrumb-item active" aria-current="page">Susun RPS</li>
      </ol>
    </nav>
  </div>

  <!-- Header Card -->
  <div class="card border-1 shadow-sm mb-4" style="border-radius: 16px; background: #0f172a; color: white;">
    <div class="card-body p-4">
      <div class="row align-items-center">
        <div class="col-lg-8 col-12">
          <span class="badge bg-warning text-dark px-3 py-1 mb-2 fw-semibold" style="border-radius: 20px;">
            RPS Versi {{ $rps->versi }} - Status: {{ $rps->status }}
          </span>
          <h3 class="fw-bold text-white mb-1">{{ $rps->kurikulumMataKuliah->mataKuliah->mk_nama }}</h3>
          <p class="text-white-50 mb-0 small">
            <span class="me-3"><i class="ti ti-bookmark me-1"></i> {{ $rps->kurikulumMataKuliah->mataKuliah->mk_kode }}</span>
            <span class="me-3"><i class="ti ti-book me-1"></i> {{ $rps->kurikulumMataKuliah->mataKuliah->sks_total }} SKS</span>
            <span class="me-3"><i class="ti ti-calendar me-1"></i> TA: {{ $rps->tahunAkademik->nama_ta }}</span>
            <span><i class="ti ti-school me-1"></i> {{ $rps->kurikulumMataKuliah->kurikulum->programStudi->prodiNamaResmi }}</span>
          </p>
        </div>
        <div class="col-lg-4 col-12 text-lg-end mt-3 mt-lg-0">
          <div class="d-inline-flex flex-column align-items-lg-end w-100">
            <div class="d-flex justify-content-between w-100 mb-1" style="max-width: 250px;">
              <span class="text-white-50 small">Kelengkapan Dokumen</span>
              <span class="fw-bold text-warning small" id="progress-text">{{ $progress }}%</span>
            </div>
            <div class="progress w-100" style="height: 8px; max-width: 250px; border-radius: 4px; background: rgba(255,255,255,0.1);">
              <div class="progress-bar bg-warning" id="progress-bar" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Left Form Section (Tabs) -->
    <div class="col-xl-9 col-lg-8 col-12 mb-4">
      <div class="card border-1 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom p-0">
          <!-- Navigation Tabs -->
          <ul class="nav nav-tabs border-0 flex-nowrap overflow-auto" id="rpsTabs" role="tablist" style="padding: 10px 15px 0 15px;">
            <li class="nav-item" role="presentation">
              <button class="nav-link active fw-bold text-nowrap py-3 px-4 border-0" id="identitas-tab" data-bs-toggle="tab" data-bs-target="#identitas" type="button" role="tab" aria-controls="identitas" aria-selected="true">
                <i class="ti ti-info-circle me-1"></i> Identitas
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link fw-bold text-nowrap py-3 px-4 border-0" id="cpl-cpmk-tab" data-bs-toggle="tab" data-bs-target="#cpl-cpmk" type="button" role="tab" aria-controls="cpl-cpmk" aria-selected="false">
                <i class="ti ti-award me-1"></i> CPL & CPMK
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link fw-bold text-nowrap py-3 px-4 border-0" id="pertemuan-tab" data-bs-toggle="tab" data-bs-target="#pertemuan" type="button" role="tab" aria-controls="pertemuan" aria-selected="false">
                <i class="ti ti-list-check me-1"></i> 16 Pertemuan
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link fw-bold text-nowrap py-3 px-4 border-0" id="penilaian-tab" data-bs-toggle="tab" data-bs-target="#penilaian" type="button" role="tab" aria-controls="penilaian" aria-selected="false">
                <i class="ti ti-report me-1"></i> Penilaian
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link fw-bold text-nowrap py-3 px-4 border-0" id="referensi-tab" data-bs-toggle="tab" data-bs-target="#referensi" type="button" role="tab" aria-controls="referensi" aria-selected="false">
                <i class="ti ti-books me-1"></i> Pustaka
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link fw-bold text-nowrap py-3 px-4 border-0" id="catatan-tab" data-bs-toggle="tab" data-bs-target="#catatan" type="button" role="tab" aria-controls="catatan" aria-selected="false">
                <i class="ti ti-note me-1"></i> Media & Catatan
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link fw-bold text-nowrap py-3 px-4 border-0" id="preview-tab" data-bs-toggle="tab" data-bs-target="#preview" type="button" role="tab" aria-controls="preview" aria-selected="false">
                <i class="ti ti-eye me-1"></i> Preview & Submit
              </button>
            </li>
          </ul>
        </div>

        <div class="card-body p-4">
          <!-- Status Warning for non-drafts -->
          @if($rps->status !== 'DRAFT')
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4" style="border-radius: 12px; background: rgba(13, 202, 240, 0.08); color: #0dcaf0;">
              <i class="ti ti-info-circle-filled fs-3 me-2"></i>
              <span>RPS ini berstatus <strong>{{ $rps->status }}</strong>. Form penyusunan saat ini terkunci (hanya baca).</span>
            </div>
          @endif

          <div class="tab-content" id="rpsTabsContent">
            <!-- 1. IDENTITAS TAB -->
            <div class="tab-pane fade show active" id="identitas" role="tabpanel" aria-labelledby="identitas-tab">
              <form id="form-identitas" data-tab="identitas">
                <div class="mb-4">
                  <label for="deskripsi_mk" class="form-label fw-bold text-dark">Deskripsi Mata Kuliah <span class="text-danger">*</span></label>
                  <textarea name="deskripsi_mk" id="deskripsi_mk" rows="6" class="form-control" placeholder="Tuliskan deskripsi singkat mengenai cakupan materi, relevansi, dan lingkup mata kuliah ini..." required {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>{{ $rps->deskripsi_mk }}</textarea>
                </div>

                <div class="mb-4">
                  <label for="manfaat_mk" class="form-label fw-bold text-dark">Manfaat Mata Kuliah</label>
                  <textarea name="manfaat_mk" id="manfaat_mk" rows="4" class="form-control" placeholder="Tuliskan manfaat atau kegunaan praktis mata kuliah ini bagi mahasiswa di dunia profesi..." {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>{{ $rps->manfaat_mk }}</textarea>
                </div>

                <div class="mb-4">
                  <label class="form-label fw-bold text-dark d-block">Tim Dosen Pengampu</label>
                  <p class="text-muted small">Pilih tim dosen yang ikut mengajar mata kuliah ini. Dosen koordinator otomatis ditambahkan.</p>
                  
                  <div class="row g-3">
                    <div class="col-md-6 col-12">
                      <div class="card bg-light border-0 p-3" style="border-radius: 12px;">
                        <span class="small text-muted mb-1">Dosen Koordinator (Utama)</span>
                        <span class="fw-bold text-dark">{{ $rps->dosenKoordinator->nama_lengkap }}</span>
                      </div>
                    </div>

                    <div class="col-md-6 col-12">
                      <label class="form-label small fw-semibold">Pilih Anggota Tim Pengampu</label>
                      <select id="tim_dosen_select" class="form-select" multiple style="height: 120px;" {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                        @foreach($dosenList as $dosen)
                          @if($dosen->id_dosen !== $rps->id_dosen_koordinator)
                            @php
                              $isAssigned = $rps->rpsDosens->contains('id_dosen', $dosen->id_dosen);
                              $assignedDosen = $rps->rpsDosens->firstWhere('id_dosen', $dosen->id_dosen);
                            @endphp
                            <option value="{{ $dosen->id_dosen }}" {{ $isAssigned ? 'selected' : '' }}>
                              {{ $dosen->nama_lengkap }}
                            </option>
                          @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>

                @if($rps->status === 'DRAFT')
                  <div class="text-end border-top pt-3 mt-4">
                    <button type="button" class="btn btn-dark btn-save-tab"><i class="ti ti-device-floppy me-1"></i> Simpan Identitas</button>
                  </div>
                @endif
              </form>
            </div>

            <!-- 2. CPL & CPMK TAB -->
            <div class="tab-pane fade" id="cpl-cpmk" role="tabpanel" aria-labelledby="cpl-cpmk-tab">
              <form id="form-cpl-cpmk" data-tab="cpl_cpmk">
                <!-- CPL Section -->
                <div class="mb-5">
                  <h5 class="fw-bold text-dark mb-3"><i class="ti ti-circle-number-1 text-primary me-1"></i> CPL Program Studi yang Dibebankan pada MK</h5>
                  <p class="text-muted small">Daftar CPL di bawah ditarik secara otomatis dari rancangan Kurikulum dan bersifat <strong>Read-Only</strong>.</p>
                  
                  <div class="table-responsive">
                    <table class="table table-bordered align-middle bg-white">
                      <thead class="table-light text-slate-700">
                        <tr>
                          <th width="120px">Kode CPL</th>
                          <th>Deskripsi Capaian Pembelajaran Lulusan (CPL)</th>
                          <th width="150px">Kategori</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($rps->rpsCpls as $rcpl)
                          <tr>
                            <td class="fw-bold font-monospace">{{ $rcpl->cpl->kode_cpl }}</td>
                            <td class="text-slate-600 small">{{ $rcpl->cpl->deskripsi }}</td>
                            <td><span class="badge bg-light text-primary border">{{ $rcpl->cpl->kategori }}</span></td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="3" class="text-center text-muted py-3">Tidak ada CPL yang didefinisikan untuk mata kuliah ini di kurikulum.</td>
                          </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- CPMK Section -->
                <div>
                  <h5 class="fw-bold text-dark mb-3"><i class="ti ti-circle-number-2 text-primary me-1"></i> CPMK (Capaian Pembelajaran Mata Kuliah) & Indikator</h5>
                  <p class="text-muted small">Lengkapi <strong>Indikator Capaian</strong> untuk masing-masing CPMK berikut sebagai parameter kelulusan mahasiswa.</p>
                  
                  @forelse($rps->rpsCpmks as $index => $rcpmk)
                    <div class="card border border-slate-200 mb-3 shadow-none" style="border-radius: 12px;">
                      <div class="card-header bg-slate-50 border-bottom p-3">
                        <div class="d-flex justify-content-between align-items-center">
                          <span class="fw-bold text-dark">{{ $rcpmk->cpmk->kode_cpmk }}</span>
                          <span class="badge bg-light text-dark border small">Taksonomi: {{ $rcpmk->cpmk->ranah_bloom ?? $rcpmk->cpmk->level_bloom }}</span>
                        </div>
                      </div>
                      <div class="card-body p-3">
                        <p class="text-slate-600 small mb-3"><strong>Deskripsi:</strong> {{ $rcpmk->cpmk->deskripsi }}</p>
                        
                        <label for="indikator-{{ $rcpmk->id_rps_cpmk }}" class="form-label small fw-bold text-muted">Indikator Capaian <span class="text-danger">*</span></label>
                        <textarea name="indikator[{{ $rcpmk->id_rps_cpmk }}]" id="indikator-{{ $rcpmk->id_rps_cpmk }}" rows="2" class="form-control" placeholder="Tuliskan indikator ketercapaian CPMK ini (misal: Mahasiswa mampu menjabarkan arsitektur client-server dengan benar...)" required {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>{{ $rcpmk->indikator_capaian }}</textarea>
                      </div>
                    </div>
                  @empty
                    <div class="alert alert-warning">Tidak ada CPMK yang dikaitkan dengan mata kuliah ini.</div>
                  @endforelse
                </div>

                @if($rps->status === 'DRAFT')
                  <div class="text-end border-top pt-3 mt-4">
                    <button type="button" class="btn btn-dark btn-save-tab"><i class="ti ti-device-floppy me-1"></i> Simpan Indikator CPMK</button>
                  </div>
                @endif
              </form>
            </div>

            <!-- 3. RENCANA PERTEMUAN TAB -->
            <div class="tab-pane fade" id="pertemuan" role="tabpanel" aria-labelledby="pertemuan-tab">
              <h5 class="fw-bold text-dark mb-2"><i class="ti ti-timeline text-primary me-1"></i> Rencana Kegiatan Pembelajaran per Pertemuan</h5>
              <p class="text-muted small mb-4">Pengisian 16 pertemuan wajib mencakup topik, bahan kajian, metode, durasi, dan aktivitas mahasiswa. Klik baris pertemuan untuk mengedit detail.</p>

              <div class="accordion" id="pertemuanAccordion">
                @foreach($rps->pertemuan as $p)
                  @php
                $methodLabels = ['CER'=>'Ceramah','DIS'=>'Diskusi','PBL'=>'PBL','CBL'=>'CBL','PJT'=>'Project','PRAK'=>'Praktikum','FLIPPED'=>'Flipped','SELF'=>'Mandiri'];
                $pMethods = !empty($p->metode_pembelajaran) ? array_map('trim', explode(',', $p->metode_pembelajaran)) : [];
                $pCpmkCode = $p->id_cpmk && isset($p->cpmk) && $p->cpmk ? $p->cpmk->kode_cpmk : null;
                $pIsComplete = in_array($p->jenis_pertemuan, ['UTS','UAS']) || (!empty($p->id_cpmk) && !empty($p->bahan_kajian) && !empty($p->metode_pembelajaran) && !empty($p->aktivitas_mahasiswa) && $p->durasi_menit > 0);
              @endphp
              <div class="accordion-item border border-slate-200 mb-2 shadow-none" style="border-radius: 8px; overflow: hidden;">
                    <h2 class="accordion-header" id="heading-{{ $p->id_pertemuan }}">
                      <button class="accordion-button collapsed py-2 px-4 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $p->id_pertemuan }}" aria-expanded="false" aria-controls="collapse-{{ $p->id_pertemuan }}">
                        <div class="d-flex align-items-center justify-content-between w-100 me-3">
                          <div class="d-flex align-items-center gap-2 flex-wrap">
                            {{-- Week badge --}}
                            <span class="badge rounded-pill px-3 py-1 fw-bold {{ in_array($p->jenis_pertemuan, ['UTS','UAS']) ? 'bg-info text-white' : 'bg-slate-900 text-white' }}" style="font-size:0.72rem; min-width:72px; text-align:center;">{{ in_array($p->jenis_pertemuan, ['UTS','UAS']) ? $p->jenis_pertemuan : 'Minggu '.$p->nomor_pertemuan }}</span>
                            {{-- Topic --}}
                            <span class="fw-semibold text-dark" id="accord-topik-{{ $p->id_pertemuan }}" style="max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $p->topik ?: '—' }}</span>
                            {{-- CPMK badge --}}
                            @if($pCpmkCode)
                              <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size:0.68rem;"><i class="ti ti-award me-1" style="font-size:0.7rem;"></i>{{ $pCpmkCode }}</span>
                            @endif
                            {{-- Method chips --}}
                            @foreach(array_slice($pMethods, 0, 3) as $mc)
                              @if(isset($methodLabels[$mc]))
                                <span class="badge bg-light text-secondary border" style="font-size:0.65rem;">{{ $methodLabels[$mc] }}</span>
                              @endif
                            @endforeach
                            @if(count($pMethods) > 3)
                              <span class="text-muted" style="font-size:0.65rem;">+{{ count($pMethods)-3 }}</span>
                            @endif
                            {{-- Duration --}}
                            @if($p->durasi_menit > 0)
                              <span class="text-muted" style="font-size:0.68rem;"><i class="ti ti-clock" style="font-size:0.72rem;"></i> {{ $p->durasi_menit }}m</span>
                            @endif
                          </div>
                          <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-2">
                            @if($pIsComplete)
                              <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size:0.68rem;"><i class="ti ti-circle-check"></i> Lengkap</span>
                            @else
                              <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size:0.68rem;"><i class="ti ti-circle-x"></i> Belum</span>
                            @endif
                          </div>
                        </div>
                      </button>
                    </h2>

                    <div id="collapse-{{ $p->id_pertemuan }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $p->id_pertemuan }}" data-bs-parent="#pertemuanAccordion">
                      <div class="accordion-body bg-light-50 p-4 border-top">
                        <form class="form-pertemuan-item" data-id="{{ $p->id_pertemuan }}">
                          <input type="hidden" name="id_pertemuan" value="{{ $p->id_pertemuan }}">
                          
                          <div class="row g-3">
                            <!-- Topik Utama -->
                            <div class="col-md-8 col-12">
                              <label class="form-label small fw-bold text-slate-700">Topik Utama <span class="text-danger">*</span></label>
                              <input type="text" name="topik" class="form-control" value="{{ $p->topik }}" required {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                            </div>

                            <!-- Durasi (Menit) -->
                            <div class="col-md-3 col-12">
                              <label class="form-label small fw-bold text-slate-700">Durasi Tatap Muka (Menit) <span class="text-danger">*</span></label>
                              <input type="number" name="durasi_menit" class="form-control" value="{{ $p->durasi_menit }}" required {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                            </div>

                            <!-- Sub Topik / Detail Materi -->
                            <div class="col-12">
                              <label class="form-label small fw-bold text-slate-700">Detail Sub-Topik / Rincian Materi</label>
                              <textarea name="sub_topik" rows="2" class="form-control" placeholder="Rincian sub-materi..." {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>{{ $p->sub_topik }}</textarea>
                            </div>

                            <!-- Bahan Kajian -->
                            <div class="col-12">
                              <label class="form-label small fw-bold text-slate-700">Bahan Kajian (Materi Kajian) <span class="text-danger">*</span></label>
                              <textarea name="bahan_kajian" rows="2" class="form-control" placeholder="Materi pokok yang dibahas pada pertemuan ini..." required {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>{{ $p->bahan_kajian }}</textarea>
                            </div>

                            @if(!in_array($p->jenis_pertemuan, ['UTS', 'UAS']))
                              <!-- CPMK Diukur -->
                              <div class="col-md-6 col-12">
                                <label class="form-label small fw-bold text-slate-700">CPMK Terkait <span class="text-danger">*</span></label>
                                <select name="id_cpmk" class="form-select cpmk-select-trigger" data-id="{{ $p->id_pertemuan }}" required {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                                  <option value="">-- Pilih CPMK --</option>
                                  @foreach($cpmkList as $cpmk)
                                    <option value="{{ $cpmk->id }}" {{ $p->id_cpmk == $cpmk->id ? 'selected' : '' }}>
                                      {{ $cpmk->kode_cpmk }} - {{ Str::limit($cpmk->deskripsi, 60) }}
                                    </option>
                                  @endforeach
                                </select>
                              </div>

                              <!-- Sub-CPMK Diukur -->
                              <div class="col-md-6 col-12">
                                <label class="form-label small fw-bold text-slate-700">Sub-CPMK (Tingkat Granular)</label>
                                <select name="id_sub_cpmk" class="form-select sub-cpmk-target-select" id="sub-cpmk-select-{{ $p->id_pertemuan }}" {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                                  <option value="">-- Pilih Sub-CPMK --</option>
                                  @foreach($subCpmkList as $subCpmk)
                                    <option value="{{ $subCpmk->id_sub_cpmk }}" data-parent="{{ $subCpmk->id_cpmk }}" {{ $p->id_sub_cpmk == $subCpmk->id_sub_cpmk ? 'selected' : '' }} style="{{ $p->id_cpmk == $subCpmk->id_cpmk ? '' : 'display:none;' }}">
                                      {{ $subCpmk->kode_sub_cpmk }} - {{ Str::limit($subCpmk->deskripsi, 60) }}
                                    </option>
                                  @endforeach
                                </select>
                              </div>
                            @endif

                            <!-- Metode Pembelajaran -->
                            <div class="col-12">
                              <label class="form-label small fw-bold text-slate-700 d-block">Metode Pembelajaran <span class="text-danger">*</span></label>
                              @php
                                $currentMethods = $p->metode_array;
                                $methods = [
                                    'CER' => 'Ceramah / Kuliah Tatap Muka',
                                    'DIS' => 'Diskusi / Tanya Jawab',
                                    'PBL' => 'Problem-Based Learning',
                                    'CBL' => 'Case-Based Learning',
                                    'PJT' => 'Project-Based Learning',
                                    'PRAK' => 'Praktikum / Praktik Lab',
                                    'FLIPPED' => 'Flipped Classroom',
                                    'SELF' => 'Mandiri / Belajar Terstruktur',
                                ];
                              @endphp
                              <div class="row">
                                @foreach($methods as $code => $label)
                                  <div class="col-md-4 col-sm-6 col-12 mb-2">
                                    <div class="form-check">
                                      <input class="form-check-input" type="checkbox" name="metode_pembelajaran[]" value="{{ $code }}" id="chk-{{ $p->id_pertemuan }}-{{ $code }}" {{ in_array($code, $currentMethods) ? 'checked' : '' }} {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                                      <label class="form-check-label small" for="chk-{{ $p->id_pertemuan }}-{{ $code }}">
                                        {{ $label }}
                                      </label>
                                    </div>
                                  </div>
                                @endforeach
                              </div>
                            </div>

                            <!-- Aktivitas Mahasiswa (Pengalaman Belajar) -->
                            <div class="col-12">
                              <label class="form-label small fw-bold text-slate-700">Pengalaman Belajar / Aktivitas Mahasiswa <span class="text-danger">*</span></label>
                              <textarea name="aktivitas_mahasiswa" rows="2" class="form-control" placeholder="Deskripsikan apa yang dilakukan mahasiswa (misal: Memecahkan studi kasus dalam kelompok kecil dan mempresentasikannya...)" required {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>{{ $p->aktivitas_mahasiswa }}</textarea>
                            </div>

                            <!-- Indikator Pertemuan -->
                            <div class="col-md-6 col-12">
                              <label class="form-label small fw-bold text-slate-700">Kemampuan Akhir yang Direncanakan (Indikator Kinerja)</label>
                              <textarea name="indikator_pertemuan" rows="2" class="form-control" placeholder="Kemampuan spesifik setelah sesi..." {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>{{ $p->indikator_pertemuan }}</textarea>
                            </div>

                            <!-- Bentuk Penilaian -->
                            <div class="col-md-6 col-12">
                              <label class="form-label small fw-bold text-slate-700">Bentuk / Instrumen Penilaian</label>
                              <textarea name="bentuk_penilaian" rows="2" class="form-control" placeholder="Metode asesmen pertemuan ini (misal: Rubrik keaktifan diskusi, Kuis online, Mini-tugas...)" {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>{{ $p->bentuk_penilaian }}</textarea>
                            </div>

                            <!-- Bobot Pertemuan & Media -->
                            <div class="col-md-4 col-6">
                              <label class="form-label small fw-bold text-slate-700">Bobot Asesmen (%)</label>
                              <input type="number" name="bobot_pertemuan" class="form-control" value="{{ $p->bobot_pertemuan }}" {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                            </div>

                            <div class="col-md-8 col-6">
                              <label class="form-label small fw-bold text-slate-700">Referensi / Pustaka Spesifik</label>
                              <input type="text" name="referensi_pertemuan" class="form-control" value="{{ $p->referensi_pertemuan }}" placeholder="Bab / halaman buku rujukan..." {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                            </div>
                          </div>

                          @if($rps->status === 'DRAFT')
                            <div class="text-end border-top pt-3 mt-4">
                              <button type="button" class="btn btn-primary btn-save-pertemuan" data-id="{{ $p->id_pertemuan }}">
                                <i class="ti ti-circle-check-filled me-1"></i> Simpan Pertemuan {{ $p->nomor_pertemuan }}
                              </button>
                            </div>
                          @endif
                        </form>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <!-- 4. PENILAIAN TAB -->
            <div class="tab-pane fade" id="penilaian" role="tabpanel" aria-labelledby="penilaian-tab">
              <form id="form-penilaian" data-tab="penilaian">
                <h5 class="fw-bold text-dark mb-2"><i class="ti ti-chart-pie-2 text-primary me-1"></i> Rencana Evaluasi & Skema Penilaian</h5>
                <p class="text-muted small mb-3">Komponen dan bobot ditarik otomatis dari master Mata Kuliah. Lengkapi <strong>Deskripsi Tugas</strong> dan <strong>Kriteria Penilaian (Rubrik)</strong>.</p>

                {{-- Assessment Weight Summary Card --}}
                @php
                  $totalBobot = 0;
                  foreach($rps->penilaian as $pen) {
                    if($pen->komponenPenilaian) $totalBobot += (float)$pen->komponenPenilaian->bobot;
                  }
                  $bobotStatus = abs($totalBobot - 100) < 0.01 ? 'success' : ($totalBobot > 100 ? 'danger' : 'warning');
                  $penCount = $rps->penilaian->count();
                @endphp
                <div class="card border-1 mb-4" style="border-radius:12px; background: linear-gradient(135deg, {{ $bobotStatus === 'success' ? '#f0fdf4, #dcfce7' : ($bobotStatus === 'danger' ? '#fef2f2, #fee2e2' : '#fffbeb, #fef3c7') }});">
                  <div class="card-body p-3">
                    <div class="row align-items-center g-3">
                      <div class="col-auto">
                        <div class="d-flex align-items-center justify-content-center rounded-circle" style="width:56px;height:56px;background:{{ $bobotStatus === 'success' ? 'rgba(34,197,94,0.15)' : ($bobotStatus === 'danger' ? 'rgba(239,68,68,0.15)' : 'rgba(245,158,11,0.15)') }};">
                          <i class="ti ti-chart-pie fs-2 {{ $bobotStatus === 'success' ? 'text-success' : ($bobotStatus === 'danger' ? 'text-danger' : 'text-warning') }}"></i>
                        </div>
                      </div>
                      <div class="col">
                        <div class="d-flex align-items-baseline gap-2">
                          <span class="fw-bold fs-2 {{ $bobotStatus === 'success' ? 'text-success' : ($bobotStatus === 'danger' ? 'text-danger' : 'text-warning') }}" id="bobot-total-display">{{ number_format($totalBobot, 0) }}%</span>
                          <span class="text-muted small">/ 100% target</span>
                        </div>
                        <div class="small text-muted">{{ $penCount }} komponen penilaian &mdash; 
                          @if($bobotStatus === 'success')
                            <span class="text-success fw-semibold"><i class="ti ti-circle-check me-1"></i>Total bobot sudah tepat 100%</span>
                          @elseif($bobotStatus === 'danger')
                            <span class="text-danger fw-semibold"><i class="ti ti-alert-triangle me-1"></i>Total bobot melebihi 100%</span>
                          @else
                            <span class="text-warning fw-semibold"><i class="ti ti-info-circle me-1"></i>Total bobot belum mencapai 100%</span>
                          @endif
                        </div>
                      </div>
                      <div class="col-auto">
                        <div class="progress" style="width:80px;height:8px;border-radius:4px;">
                          <div class="progress-bar bg-{{ $bobotStatus === 'success' ? 'success' : ($bobotStatus === 'danger' ? 'danger' : 'warning') }}" style="width:{{ min($totalBobot, 100) }}%;"></div>
                        </div>
                        <div class="text-center mt-1" style="font-size:0.65rem;color:#94a3b8;">{{ min($totalBobot, 100) }}%</div>
                      </div>
                    </div>
                  </div>
                </div>

                @forelse($rps->penilaian as $pen)
                  <div class="card border border-slate-200 mb-4 shadow-none" style="border-radius: 12px;">
                    <div class="card-header bg-slate-50 border-bottom p-3">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <span class="fw-bold text-dark fs-6">{{ $pen->komponenPenilaian->nama_komponen }}</span>
                          <span class="badge bg-light text-slate-700 border ms-2">Bobot: {{ $pen->komponenPenilaian->bobot }}%</span>
                        </div>
                        <span class="badge bg-slate-100 text-slate-700 font-monospace border">{{ $pen->komponenPenilaian->jenis_komponen }}</span>
                      </div>
                    </div>
                    <div class="card-body p-4">
                      <div class="row g-3">
                        <!-- Deskripsi Tugas -->
                        <div class="col-12">
                          <label for="desc-{{ $pen->id_rps_penilaian }}" class="form-label small fw-bold text-muted">Deskripsi Tugas / Asesmen <span class="text-danger">*</span></label>
                          <textarea name="deskripsi_tugas[{{ $pen->id_rps_penilaian }}]" id="desc-{{ $pen->id_rps_penilaian }}" rows="2" class="form-control" placeholder="Jelaskan instruksi tugas atau apa yang diujikan dalam komponen ini..." required {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>{{ $pen->deskripsi_tugas }}</textarea>
                        </div>

                        <!-- CPMK yang diukur -->
                        <div class="col-md-6 col-12">
                          <label class="form-label small fw-bold text-muted">CPMK yang Diukur</label>
                          <select name="id_cpmk_diukur[{{ $pen->id_rps_penilaian }}]" class="form-select" {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                            <option value="">-- Hubungkan dengan CPMK --</option>
                            @foreach($cpmkList as $cpmk)
                              <option value="{{ $cpmk->id }}" {{ $pen->id_cpmk_diukur == $cpmk->id ? 'selected' : '' }}>
                                {{ $cpmk->kode_cpmk }} - {{ Str::limit($cpmk->deskripsi, 60) }}
                              </option>
                            @endforeach
                          </select>
                        </div>

                        <!-- Bentuk Soal -->
                        <div class="col-md-6 col-12">
                          <label class="form-label small fw-bold text-muted">Bentuk Soal / Format Asesmen</label>
                          <select name="bentuk_soal[{{ $pen->id_rps_penilaian }}]" class="form-select" {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                            <option value="">-- Pilih Bentuk Soal --</option>
                            @foreach(['Essay', 'PG', 'Proyek', 'Presentasi', 'Portofolio', 'Praktikum'] as $val)
                              <option value="{{ $val }}" {{ $pen->bentuk_soal === $val ? 'selected' : '' }}>{{ $val }}</option>
                            @endforeach
                          </select>
                        </div>

                        <!-- Kriteria / Rubrik Penilaian -->
                        <div class="col-md-8 col-12">
                          <label for="rubrik-{{ $pen->id_rps_penilaian }}" class="form-label small fw-bold text-muted">Kriteria Penilaian / Rubrik <span class="text-danger">*</span></label>
                          <textarea name="kriteria_penilaian[{{ $pen->id_rps_penilaian }}]" id="rubrik-{{ $pen->id_rps_penilaian }}" rows="2" class="form-control" placeholder="Kriteria penilaian nilai A, B, C, dst. (misal: Ketepatan logika, orisinalitas ide, kerapian kode program)..." required {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>{{ $pen->kriteria_penilaian }}</textarea>
                        </div>

                        <!-- Waktu Pelaksanaan -->
                        <div class="col-md-3 col-12">
                          <label class="form-label small fw-bold text-muted">Waktu Pelaksanaan</label>
                          <input type="text" name="waktu_pelaksanaan[{{ $pen->id_rps_penilaian }}]" class="form-control" value="{{ $pen->waktu_pelaksanaan }}" placeholder="Misal: Pertemuan 8, UTS, Akhir semester..." {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                        </div>
                      </div>
                    </div>
                  </div>
                @empty
                  <div class="alert alert-warning">Tidak ada komponen penilaian yang dikaitkan dengan mata kuliah ini di kurikulum.</div>
                @endforelse

                @if($rps->status === 'DRAFT')
                  <div class="text-end border-top pt-3 mt-4">
                    <button type="button" class="btn btn-dark btn-save-tab"><i class="ti ti-device-floppy me-1"></i> Simpan Penilaian</button>
                  </div>
                @endif
              </form>
            </div>

            <!-- 5. REFERENSI TAB -->
            <div class="tab-pane fade" id="referensi" role="tabpanel" aria-labelledby="referensi-tab">
              <form id="form-referensi" data-tab="referensi">
                <h5 class="fw-bold text-dark mb-2"><i class="ti ti-bookmark-filled text-primary me-1"></i> Daftar Pustaka / Referensi Rujukan</h5>
                <p class="text-muted small mb-4">Wajib mengisi **minimal 1 Referensi Utama (Wajib)** untuk kelayakan RPS.</p>

                <div id="referensi-container">
                  @forelse($rps->referensi as $index => $ref)
                    <div class="card border border-slate-200 mb-3 shadow-none referensi-row" style="border-radius: 12px;" data-index="{{ $index }}">
                      <input type="hidden" name="referensi_id[{{ $index }}]" value="{{ $ref->id_referensi }}">
                      
                      <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                          <span class="fw-bold small text-slate-700">Pustaka Rujukan #{{ $index + 1 }}</span>
                          @if($rps->status === 'DRAFT')
                            <button type="button" class="btn btn-subtle-danger btn-sm btn-remove-referensi" title="Hapus Referensi">
                              <i class="ti ti-trash"></i> Hapus
                            </button>
                          @endif
                        </div>

                        <div class="row g-3">
                          <!-- Jenis Referensi -->
                          <div class="col-md-3 col-6">
                            <label class="form-label small fw-bold text-muted">Kategori Pustaka <span class="text-danger">*</span></label>
                            <select name="jenis[{{ $index }}]" class="form-select" required {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                              <option value="Wajib" {{ $ref->jenis === 'Wajib' ? 'selected' : '' }}>Wajib (Utama)</option>
                              <option value="Anjuran" {{ $ref->jenis === 'Anjuran' ? 'selected' : '' }}>Anjuran</option>
                              <option value="Tambahan" {{ $ref->jenis === 'Tambahan' ? 'selected' : '' }}>Pendukung</option>
                            </select>
                          </div>

                          <!-- Tipe Sumber -->
                          <div class="col-md-3 col-6">
                            <label class="form-label small fw-bold text-muted">Tipe Sumber <span class="text-danger">*</span></label>
                            <select name="tipe_sumber[{{ $index }}]" class="form-select" required {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                              @foreach(['Buku', 'Jurnal', 'Artikel', 'Website', 'Standar', 'Lainnya'] as $t)
                                <option value="{{ $t }}" {{ $ref->tipe_sumber === $t ? 'selected' : '' }}>{{ $t }}</option>
                              @endforeach
                            </select>
                          </div>

                          <!-- Penulis -->
                          <div class="col-md-6 col-12">
                            <label class="form-label small fw-bold text-muted">Penulis / Pengarang <span class="text-danger">*</span></label>
                            <input type="text" name="penulis[{{ $index }}]" class="form-control" value="{{ $ref->penulis }}" placeholder="Nama pengarang / penulis..." required {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                          </div>

                          <!-- Judul -->
                          <div class="col-md-9 col-12">
                            <label class="form-label small fw-bold text-muted">Judul Referensi <span class="text-danger">*</span></label>
                            <input type="text" name="judul[{{ $index }}]" class="form-control" value="{{ $ref->judul }}" placeholder="Judul buku, artikel, jurnal..." required {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                          </div>

                          <!-- Tahun Terbit -->
                          <div class="col-md-3 col-12">
                            <label class="form-label small fw-bold text-muted">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit[{{ $index }}]" class="form-control" value="{{ $ref->tahun_terbit }}" placeholder="YYYY" {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                          </div>

                          <!-- Penerbit & Edisi -->
                          <div class="col-md-4 col-6">
                            <label class="form-label small fw-bold text-muted">Penerbit</label>
                            <input type="text" name="penerbit[{{ $index }}]" class="form-control" value="{{ $ref->penerbit }}" placeholder="Nama penerbit..." {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                          </div>

                          <div class="col-md-2 col-6">
                            <label class="form-label small fw-bold text-muted">Edisi / Vol</label>
                            <input type="text" name="edisi[{{ $index }}]" class="form-control" value="{{ $ref->edisi }}" placeholder="Misal: ke-3, Vol 2" {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                          </div>

                          <!-- ISBN/ISSN -->
                          <div class="col-md-3 col-6">
                            <label class="form-label small fw-bold text-muted">ISBN / ISSN</label>
                            <input type="text" name="isbn_issn[{{ $index }}]" class="form-control" value="{{ $ref->isbn_issn }}" placeholder="Nomor ISBN/ISSN..." {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                          </div>

                          <!-- URL -->
                          <div class="col-md-3 col-6">
                            <label class="form-label small fw-bold text-muted">URL Online (Link)</label>
                            <input type="url" name="url[{{ $index }}]" class="form-control" value="{{ $ref->url }}" placeholder="http://..." {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>
                          </div>
                        </div>
                      </div>
                    </div>
                  @empty
                    <!-- Initial placeholder row if empty -->
                    <div class="alert alert-light border p-4 text-center text-muted" id="no-ref-placeholder">
                      <i class="ti ti-books fs-1 text-slate-300 d-block mb-2"></i>
                      <span>Referensi belum ditambahkan. Tambahkan minimal 1 Referensi Wajib (Utama).</span>
                    </div>
                  @endforelse
                </div>

                @if($rps->status === 'DRAFT')
                  <div class="d-flex justify-content-between border-top pt-3 mt-4">
                    <button type="button" class="btn btn-light-primary border" id="btn-add-referensi">
                      <i class="ti ti-circle-plus me-1"></i> Tambah Baris Pustaka
                    </button>
                    <button type="button" class="btn btn-dark btn-save-tab"><i class="ti ti-device-floppy me-1"></i> Simpan Pustaka</button>
                  </div>
                @endif
              </form>
            </div>

            <!-- 6. CATATAN & MEDIA TAB -->
            <div class="tab-pane fade" id="catatan" role="tabpanel" aria-labelledby="catatan-tab">
              <form id="form-catatan" data-tab="catatan">
                <div class="mb-4">
                  <label for="media_pembelajaran" class="form-label fw-bold text-dark">Media Pembelajaran</label>
                  <textarea name="media_pembelajaran" id="media_pembelajaran" rows="4" class="form-control" placeholder="Sebutkan perangkat keras (hardware) dan lunak (software) yang digunakan, contoh: LCD Proyektor, Google Classroom, VS Code, MySQL Workbench..." {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>{{ $rps->media_pembelajaran }}</textarea>
                </div>

                <div class="mb-4">
                  <label for="catatan_khusus" class="form-label fw-bold text-dark">Kebijakan / Catatan Khusus Mata Kuliah</label>
                  <textarea name="catatan_khusus" id="catatan_khusus" rows="4" class="form-control" placeholder="Kebijakan kelas, aturan kehadiran, toleransi keterlambatan, pengumpulan tugas terlambat, dll..." {{ $rps->status !== 'DRAFT' ? 'disabled' : '' }}>{{ $rps->catatan_khusus }}</textarea>
                </div>

                @if($rps->status === 'DRAFT')
                  <div class="text-end border-top pt-3 mt-4">
                    <button type="button" class="btn btn-dark btn-save-tab"><i class="ti ti-device-floppy me-1"></i> Simpan Catatan & Media</button>
                  </div>
                @endif
              </form>
            </div>

            <!-- 7. PREVIEW & SUBMIT TAB -->
            <div class="tab-pane fade" id="preview" role="tabpanel" aria-labelledby="preview-tab">
              <div class="row">
                <div class="col-xl-6 col-12 mb-4">
                  <h5 class="fw-bold text-dark mb-3"><i class="ti ti-check-list text-primary me-1"></i> Checklist Kelengkapan Dokumen</h5>
                  <p class="text-muted small">Semua komponen wajib berikut harus tercentang agar dokumen layak diajukan ke Kaprodi.</p>

                  @php
                    $checklistDisplay = [
                      'identitas_isi'        => ['label' => 'Identitas & Deskripsi MK', 'icon' => 'ti-id'],
                      'dosen_isi'            => ['label' => 'Tim Dosen Pengampu', 'icon' => 'ti-users'],
                      'cpmk_indikator'       => ['label' => 'Indikator Capaian CPMK', 'icon' => 'ti-award'],
                      'cpmk_terdistribusi'   => ['label' => 'CPMK Terdistribusi ke Pertemuan', 'icon' => 'ti-arrows-split'],
                      'pertemuan_16'         => ['label' => 'Min. 16 Pertemuan tersusun', 'icon' => 'ti-calendar-event'],
                      'pertemuan_topik'      => ['label' => 'Setiap Pertemuan ada Topik', 'icon' => 'ti-list'],
                      'pertemuan_cpmk'       => ['label' => 'Pertemuan Reguler ada CPMK', 'icon' => 'ti-link'],
                      'pertemuan_metode'     => ['label' => 'Metode Pembelajaran terisi', 'icon' => 'ti-school'],
                      'pertemuan_aktivitas'  => ['label' => 'Aktivitas Mahasiswa terisi', 'icon' => 'ti-activity'],
                      'pertemuan_durasi'     => ['label' => 'Durasi Pertemuan terisi', 'icon' => 'ti-clock'],
                      'penilaian_kriteria'   => ['label' => 'Rubrik & Deskripsi Penilaian', 'icon' => 'ti-report'],
                      'penilaian_bobot'      => ['label' => 'Total Bobot = 100%', 'icon' => 'ti-chart-pie-2'],
                      'referensi_wajib'      => ['label' => 'Min. 1 Referensi Utama (Wajib)', 'icon' => 'ti-books'],
                    ];
                    $passedCount = count(array_filter($checklist));
                    $totalCount = count($checklist);
                  @endphp

                  {{-- Progress summary bar --}}
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-slate-700">{{ $passedCount }} dari {{ $totalCount }} terpenuhi</span>
                    <span class="small fw-bold {{ $passedCount === $totalCount ? 'text-success' : 'text-warning' }}">{{ round(($passedCount/$totalCount)*100) }}%</span>
                  </div>
                  <div class="progress mb-4" style="height:8px;border-radius:4px;">
                    <div class="progress-bar {{ $passedCount === $totalCount ? 'bg-success' : 'bg-warning' }}" style="width:{{ round(($passedCount/$totalCount)*100) }}%"></div>
                  </div>

                  <ul class="list-group list-group-flush border rounded-3 overflow-hidden bg-white mb-4">
                    @foreach($checklistDisplay as $key => $meta)
                      <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3 {{ isset($checklist[$key]) && $checklist[$key] ? '' : 'bg-danger-subtle' }}">
                        <div class="d-flex align-items-center">
                          <i class="ti {{ $meta['icon'] }} me-2 {{ isset($checklist[$key]) && $checklist[$key] ? 'text-success' : 'text-danger' }}" style="font-size:1rem;"></i>
                          <span class="small">{{ $meta['label'] }}</span>
                        </div>
                        <span id="chk-{{ $key }}">
                          @if(isset($checklist[$key]) && $checklist[$key])
                            <i class="ti ti-circle-check-filled text-success fs-4"></i>
                          @else
                            <i class="ti ti-circle-x-filled text-danger fs-4"></i>
                          @endif
                        </span>
                      </li>
                    @endforeach
                  </ul>
                </div>

                <div class="col-xl-6 col-12 d-flex flex-column justify-content-between mb-4">
                  <div>
                    <h5 class="fw-bold text-dark mb-3"><i class="ti ti-device-laptop text-primary me-1"></i> Aksi Akhir Dokumen</h5>
                    <p class="text-muted small">Cetak atau simpan draf PDF untuk koreksi mandiri. Jika checklist sudah terpenuhi semua, Anda dapat mengirimkannya langsung untuk di-review oleh Ketua Program Studi.</p>
                    
                    <div class="d-flex gap-2 mb-4">
                      <a href="{{ route('rps.print', $rps->id_rps) }}" target="_blank" class="btn btn-outline-dark d-inline-flex align-items-center gap-1">
                        <i class="ti ti-printer fs-4"></i> Cetak / Print RPS
                      </a>
                    </div>
                  </div>

                  @if($rps->status === 'DRAFT')
                    <div class="card bg-light border-0 p-4" style="border-radius: 16px;">
                      <h6 class="fw-bold text-dark mb-2">Pengajuan Review Kaprodi</h6>
                      <p class="text-muted small mb-3">Setelah dikirim, RPS akan berstatus <strong>Menunggu Review</strong> dan terkunci dari perubahan. Anda akan menerima notifikasi jika ada perbaikan yang diajukan.</p>
                      
                      @php
                        $allPassed = !in_array(false, array_values($checklist), true);
                      @endphp
                      
                      <form action="{{ route('rps.submit-review', $rps->id_rps) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 d-inline-flex justify-content-center align-items-center gap-2 py-2" id="btn-submit-review" {{ $allPassed ? '' : 'disabled' }}>
                          <i class="ti ti-send"></i> Ajukan Review Sekarang
                        </button>
                      </form>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Summary Section (Checklist & Info Panel) -->
    <div class="col-xl-3 col-lg-4 col-12">
      <div class="card border-1 shadow-sm" style="border-radius: 16px; position:sticky; top: 80px; z-index: 10;">
        {{-- Progress ring header --}}
        @php
          $sidePassedCount = count(array_filter($checklist));
          $sideTotalCount  = count($checklist);
          $sidePercent     = $sideTotalCount > 0 ? round(($sidePassedCount / $sideTotalCount) * 100) : 0;
          $sideRingColor   = $sidePercent >= 100 ? '#22c55e' : ($sidePercent >= 50 ? '#f59e0b' : '#ef4444');
          $sideLabels = [
            'identitas_isi'       => ['l' => 'Identitas MK', 'i' => 'ti-id'],
            'dosen_isi'           => ['l' => 'Tim Dosen', 'i' => 'ti-users'],
            'cpmk_indikator'      => ['l' => 'Indikator CPMK', 'i' => 'ti-award'],
            'cpmk_terdistribusi'  => ['l' => 'Distribusi CPMK', 'i' => 'ti-arrows-split'],
            'pertemuan_16'        => ['l' => '16 Pertemuan', 'i' => 'ti-calendar-event'],
            'pertemuan_topik'     => ['l' => 'Topik Pertemuan', 'i' => 'ti-list'],
            'pertemuan_cpmk'      => ['l' => 'CPMK Reguler', 'i' => 'ti-link'],
            'pertemuan_metode'    => ['l' => 'Metode', 'i' => 'ti-school'],
            'pertemuan_aktivitas' => ['l' => 'Aktivitas MHS', 'i' => 'ti-activity'],
            'pertemuan_durasi'    => ['l' => 'Durasi', 'i' => 'ti-clock'],
            'penilaian_kriteria'  => ['l' => 'Rubrik Penilaian', 'i' => 'ti-report'],
            'penilaian_bobot'     => ['l' => 'Bobot = 100%', 'i' => 'ti-chart-pie-2'],
            'referensi_wajib'     => ['l' => 'Referensi Wajib', 'i' => 'ti-books'],
          ];
        @endphp
        <div class="card-body p-3">
          {{-- Compact progress header --}}
          <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
            <div class="position-relative" style="width:56px;height:56px;flex-shrink:0;">
              <svg viewBox="0 0 36 36" style="width:56px;height:56px;transform:rotate(-90deg);">
                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#e2e8f0" stroke-width="3"/>
                <circle cx="18" cy="18" r="15.9" fill="none" stroke="{{ $sideRingColor }}" stroke-width="3"
                  stroke-dasharray="{{ $sidePercent }}, 100"
                  stroke-linecap="round"
                  style="transition: stroke-dasharray 0.5s ease;"
                  id="progress-ring-circle"
                />
              </svg>
              <span class="position-absolute top-50 start-50 translate-middle fw-bold" id="progress-ring-text" style="font-size:0.72rem;color:{{ $sideRingColor }};">{{ $sidePercent }}%</span>
            </div>
            <div>
              <div class="fw-bold text-dark" style="font-size:0.9rem;">Kelengkapan RPS</div>
              <div class="small text-muted">{{ $sidePassedCount }}/{{ $sideTotalCount }} poin terpenuhi</div>
              <div class="badge mt-1 {{ $sidePercent >= 100 ? 'bg-success' : ($sidePercent >= 50 ? 'bg-warning text-dark' : 'bg-danger') }}" style="font-size:0.65rem;">
                {{ $sidePercent >= 100 ? 'Siap Review' : ($sidePercent >= 50 ? 'Hampir Selesai' : 'Perlu Dilengkapi') }}
              </div>
            </div>
          </div>

          {{-- Checklist items compact --}}
          <div class="d-flex flex-column gap-1 mb-3">
            @foreach($sideLabels as $key => $meta)
              <div class="d-flex justify-content-between align-items-center px-2 py-1 rounded" style="background:{{ isset($checklist[$key]) && $checklist[$key] ? 'rgba(34,197,94,0.07)' : 'rgba(239,68,68,0.05)' }};">
                <div class="d-flex align-items-center gap-2">
                  <i class="ti {{ $meta['i'] }} {{ isset($checklist[$key]) && $checklist[$key] ? 'text-success' : 'text-danger' }}" style="font-size:0.8rem;width:16px;"></i>
                  <span class="small {{ isset($checklist[$key]) && $checklist[$key] ? 'text-slate-700' : 'text-danger' }}" style="font-size:0.78rem;">{{ $meta['l'] }}</span>
                </div>
                <span id="side-chk-{{ $key }}">
                  @if(isset($checklist[$key]) && $checklist[$key])
                    <i class="ti ti-circle-check-filled text-success" style="font-size:1rem;"></i>
                  @else
                    <i class="ti ti-circle-x-filled text-danger" style="font-size:1rem;"></i>
                  @endif
                </span>
              </div>
            @endforeach
          </div>

          {{-- RPS Info compact --}}
          <div class="border-top pt-2 mt-1">
            <div class="d-flex flex-column gap-1" style="font-size:0.75rem;">
              <div class="d-flex justify-content-between">
                <span class="text-muted">Koordinator</span>
                <span class="fw-semibold text-dark" style="max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $rps->dosenKoordinator->nama_lengkap }}">{{ $rps->dosenKoordinator->nama_lengkap }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-muted">Versi</span>
                <span class="fw-semibold text-dark">v{{ $rps->versi }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-muted">Diperbarui</span>
                <span class="fw-semibold text-dark">{{ $rps->updated_at->diffForHumans() }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-muted">Tahun Akademik</span>
                <span class="fw-semibold text-dark">{{ $rps->tahunAkademik->nama_ta }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // 1. AJAX Autosave / Simpan per tab
    document.querySelectorAll('.btn-save-tab').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        const tab = form.dataset.tab;
        saveTab(form, tab);
      });
    });

    function saveTab(form, tab) {
      const formData = new FormData(form);
      
      // Special handling for Tim Dosen multiple select
      if (tab === 'identitas') {
        const select = document.getElementById('tim_dosen_select');
        const values = Array.from(select.selectedOptions).map(option => option.value);
        formData.delete('tim_dosen[]'); // clear any
        values.forEach(val => formData.append('tim_dosen[]', val));
      }

      // Special handling for Referensi inputs
      if (tab === 'referensi') {
        // Collect rows and validate
        // Standard FormData captures it naturally as arrays
      }

      const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

      fetch(`/references/rps/{{ $rps->id_rps }}/update-tab/${tab}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) {
          showNotification('success', data.message);
          updateChecklistAndProgress(data.progress, data.checklist);
        } else {
          showNotification('danger', data.message);
        }
      })
      .catch(error => {
        console.error('Error saving tab:', error);
        showNotification('danger', 'Terjadi kesalahan sistem saat menyimpan data.');
      });
    }

    // 2. AJAX Save specific pertemuan row
    document.querySelectorAll('.btn-save-pertemuan').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        const petId = this.dataset.id;
        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch(`/references/rps/{{ $rps->id_rps }}/update-tab/pertemuan`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if(data.success) {
            showNotification('success', 'Rencana Pertemuan berhasil diperbarui.');
            // Update accordion header title
            document.getElementById(`accord-topik-${petId}`).textContent = formData.get('topik');
            
            // Recalculate status badge of accordion item dynamically if needed
            // By updating checklist
            updateChecklistAndProgress(data.progress, data.checklist);
          } else {
            showNotification('danger', data.message);
          }
        })
        .catch(error => {
          console.error('Error saving pertemuan:', error);
          showNotification('danger', 'Terjadi kesalahan sistem.');
        });
      });
    });

    // 3. CPMK -> Sub CPMK dynamic filter inside pertemuan accordion
    document.querySelectorAll('.cpmk-select-trigger').forEach(select => {
      select.addEventListener('change', function() {
        const petId = this.dataset.id;
        const cpmkVal = this.value;
        const subSelect = document.getElementById(`sub-cpmk-select-${petId}`);
        
        if (!subSelect) return;

        // Reset subselect
        subSelect.value = '';
        
        // Hide all option children and show only related ones
        Array.from(subSelect.options).forEach(opt => {
          if (opt.value === '') {
            opt.style.display = '';
            return;
          }
          if (opt.dataset.parent == cpmkVal) {
            opt.style.display = '';
          } else {
            opt.style.display = 'none';
          }
        });
      });
    });

    // 4. Dynamic Referensi Rows Add/Delete
    let refIndex = document.querySelectorAll('.referensi-row').length;
    const btnAddRef = document.getElementById('btn-add-referensi');
    
    if (btnAddRef) {
      btnAddRef.addEventListener('click', function() {
        // Remove placeholder if present
        const placeholder = document.getElementById('no-ref-placeholder');
        if (placeholder) placeholder.style.display = 'none';

        const container = document.getElementById('referensi-container');
        
        const cardHtml = `
          <div class="card border border-slate-200 mb-3 shadow-none referensi-row" style="border-radius: 12px;" data-index="${refIndex}">
            <input type="hidden" name="referensi_id[${refIndex}]" value="">
            
            <div class="card-body p-3">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-bold small text-slate-700">Pustaka Rujukan Baru</span>
                <button type="button" class="btn btn-subtle-danger btn-sm btn-remove-referensi" title="Hapus Referensi">
                  <i class="ti ti-trash"></i> Hapus
                </button>
              </div>

              <div class="row g-3">
                <div class="col-md-3 col-6">
                  <label class="form-label small fw-bold text-muted">Kategori Pustaka <span class="text-danger">*</span></label>
                  <select name="jenis[${refIndex}]" class="form-select" required>
                    <option value="Wajib">Wajib (Utama)</option>
                    <option value="Anjuran">Anjuran</option>
                    <option value="Tambahan">Pendukung</option>
                  </select>
                </div>

                <div class="col-md-3 col-6">
                  <label class="form-label small fw-bold text-muted">Tipe Sumber <span class="text-danger">*</span></label>
                  <select name="tipe_sumber[${refIndex}]" class="form-select" required>
                    <option value="Buku">Buku</option>
                    <option value="Jurnal">Jurnal</option>
                    <option value="Artikel">Artikel</option>
                    <option value="Website">Website</option>
                    <option value="Standar">Standar</option>
                    <option value="Lainnya">Lainnya</option>
                  </select>
                </div>

                <div class="col-md-6 col-12">
                  <label class="form-label small fw-bold text-muted">Penulis / Pengarang <span class="text-danger">*</span></label>
                  <input type="text" name="penulis[${refIndex}]" class="form-control" placeholder="Nama pengarang / penulis..." required>
                </div>

                <div class="col-md-9 col-12">
                  <label class="form-label small fw-bold text-muted">Judul Referensi <span class="text-danger">*</span></label>
                  <input type="text" name="judul[${refIndex}]" class="form-control" placeholder="Judul buku, artikel, jurnal..." required>
                </div>

                <div class="col-md-3 col-12">
                  <label class="form-label small fw-bold text-muted">Tahun Terbit</label>
                  <input type="number" name="tahun_terbit[${refIndex}]" class="form-control" placeholder="YYYY">
                </div>

                <div class="col-md-4 col-6">
                  <label class="form-label small fw-bold text-muted">Penerbit</label>
                  <input type="text" name="penerbit[${refIndex}]" class="form-control" placeholder="Nama penerbit...">
                </div>

                <div class="col-md-2 col-6">
                  <label class="form-label small fw-bold text-muted">Edisi / Vol</label>
                  <input type="text" name="edisi[${refIndex}]" class="form-control" placeholder="ke-2, Vol 1">
                </div>

                <div class="col-md-3 col-6">
                  <label class="form-label small fw-bold text-muted">ISBN / ISSN</label>
                  <input type="text" name="isbn_issn[${refIndex}]" class="form-control" placeholder="Nomor ISBN/ISSN...">
                </div>

                <div class="col-md-3 col-6">
                  <label class="form-label small fw-bold text-muted">URL Online (Link)</label>
                  <input type="url" name="url[${refIndex}]" class="form-control" placeholder="http://...">
                </div>
              </div>
            </div>
          </div>
        `;
        container.insertAdjacentHTML('beforeend', cardHtml);
        refIndex++;
      });
    }

    // Event delegation for deleting references rows
    document.getElementById('referensi-container').addEventListener('click', function(e) {
      if (e.target.closest('.btn-remove-referensi')) {
        const row = e.target.closest('.referensi-row');
        row.remove();
        
        // Show placeholder if empty
        const remaining = document.querySelectorAll('.referensi-row');
        if (remaining.length === 0) {
          const placeholder = document.getElementById('no-ref-placeholder');
          if (placeholder) placeholder.style.display = '';
        }
      }
    });

    // Helper functions
    function updateChecklistAndProgress(progress, checklist) {
      // Update main progress bar in header
      const progressText = document.getElementById('progress-text');
      const progressBar = document.getElementById('progress-bar');
      if (progressText) progressText.textContent = `${progress}%`;
      if (progressBar) {
        progressBar.style.width = `${progress}%`;
        progressBar.setAttribute('aria-valuenow', progress);
      }

      // Count passed items
      const keys = Object.keys(checklist);
      let passedCount = 0;
      let allPassed = true;
      
      keys.forEach(key => {
        const checkVal = checklist[key];
        if (!checkVal) allPassed = false;
        else passedCount++;

        // Update main checklist icons (Tab 7 full list)
        const el = document.getElementById(`chk-${key}`);
        if (el) {
          el.innerHTML = checkVal 
            ? '<i class="ti ti-circle-check-filled text-success fs-4"></i>'
            : '<i class="ti ti-circle-x-filled text-danger fs-4"></i>';
          // Also update parent li background highlight
          const li = el.closest('li.list-group-item');
          if (li) {
            li.classList.toggle('bg-danger-subtle', !checkVal);
          }
        }

        // Update side panel compact checklist
        const sideEl = document.getElementById(`side-chk-${key}`);
        if (sideEl) {
          sideEl.innerHTML = checkVal 
            ? '<i class="ti ti-circle-check-filled text-success" style="font-size:1rem;"></i>'
            : '<i class="ti ti-circle-x-filled text-danger" style="font-size:1rem;"></i>';
          // Also update the row background
          const row = sideEl.closest('[id^="side-chk-"]')?.parentElement;
          if (row) {
            row.style.background = checkVal ? 'rgba(34,197,94,0.07)' : 'rgba(239,68,68,0.05)';
          }
        }
      });

      // Update SVG progress ring
      const ringCircle = document.getElementById('progress-ring-circle');
      const ringText   = document.getElementById('progress-ring-text');
      const totalKeys  = keys.length;
      const ringPct    = totalKeys > 0 ? Math.round((passedCount / totalKeys) * 100) : 0;
      const ringColor  = ringPct >= 100 ? '#22c55e' : (ringPct >= 50 ? '#f59e0b' : '#ef4444');
      if (ringCircle) {
        ringCircle.setAttribute('stroke-dasharray', `${ringPct}, 100`);
        ringCircle.setAttribute('stroke', ringColor);
      }
      if (ringText) {
        ringText.textContent = `${ringPct}%`;
        ringText.style.color = ringColor;
      }

      // Enable/disable submit review button
      const submitBtn = document.getElementById('btn-submit-review');
      if (submitBtn) {
        submitBtn.disabled = !allPassed;
      }
    }

    function showNotification(type, message) {
      const toastHtml = `
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
          <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0 show" role="alert" aria-live="assertive" aria-atomic="true" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="d-flex">
              <div class="toast-body d-flex align-items-center gap-2">
                <i class="ti ti-${type === 'success' ? 'circle-check' : 'alert-circle'} fs-4"></i>
                <span>${message}</span>
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          </div>
        </div>
      `;
      document.body.insertAdjacentHTML('beforeend', toastHtml);
      const toastEl = document.body.lastElementChild;
      setTimeout(() => {
        toastEl.remove();
      }, 4000);
      
      // Bind close button
      toastEl.querySelector('.btn-close').addEventListener('click', () => {
        toastEl.remove();
      });
    }
  });
</script>
@endpush
