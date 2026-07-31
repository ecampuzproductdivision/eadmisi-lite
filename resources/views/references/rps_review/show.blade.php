@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
  <div class="row g-0" style="min-height: calc(100vh - 70px);">
    {{-- ============================================================ --}}
    {{-- PANEL KIRI: RPS CONTENT (65% width)                          --}}
    {{-- ============================================================ --}}
    <div class="col-lg-8 border-end bg-white p-4" style="height: calc(100vh - 70px); overflow-y: auto;">
      {{-- RPS Identity Header --}}
      <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
        <div>
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-primary-subtle text-primary font-monospace fw-bold">{{ $rps->kurikulumMataKuliah->mataKuliah->mk_kode }}</span>
            <span class="badge bg-slate-900 text-white">{{ $rps->tahunAkademik->nama_ta }}</span>
            <span class="badge bg-warning text-dark border-warning">Iterasi Review: Ke-{{ $activeReview->iterasi_ke }}</span>
          </div>
          <h3 class="fw-bold text-dark mb-1">{{ $rps->kurikulumMataKuliah->mataKuliah->mk_nama }}</h3>
          <p class="text-muted small mb-0">
            <i class="ti ti-school me-1"></i>{{ $rps->kurikulumMataKuliah->kurikulum->programStudi->prodiNamaResmi }}
            &nbsp;•&nbsp; SKS: {{ $rps->kurikulumMataKuliah->mataKuliah->sks_total }} (Teori: {{ $rps->kurikulumMataKuliah->mataKuliah->sks_tatap_muka }}, Prak: {{ $rps->kurikulumMataKuliah->mataKuliah->sks_praktikum }})
          </p>
        </div>
        <a href="{{ route('rps-review.index') }}" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-1">
          <i class="ti ti-arrow-left"></i> Kembali ke Antrean
        </a>
      </div>

      {{-- RPS Tab Navigation --}}
      <ul class="nav nav-tabs nav-fill mb-4" id="rpsContentTabs" role="tablist">
        <li class="nav-item">
          <button class="nav-link active fw-bold py-2" id="identitas-tab" data-bs-toggle="tab" data-bs-target="#identitas" type="button" role="tab"><i class="ti ti-info-circle me-1"></i>Identitas</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-bold py-2" id="cpl-cpmk-tab" data-bs-toggle="tab" data-bs-target="#cpl-cpmk" type="button" role="tab"><i class="ti ti-award me-1"></i>CPL &amp; CPMK</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-bold py-2" id="pertemuan-tab" data-bs-toggle="tab" data-bs-target="#pertemuan" type="button" role="tab"><i class="ti ti-timeline me-1"></i>Pertemuan</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-bold py-2" id="penilaian-tab" data-bs-toggle="tab" data-bs-target="#penilaian" type="button" role="tab"><i class="ti ti-report-analytics me-1"></i>Penilaian</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-bold py-2" id="referensi-tab" data-bs-toggle="tab" data-bs-target="#referensi" type="button" role="tab"><i class="ti ti-books me-1"></i>Referensi</button>
        </li>
      </ul>

      {{-- RPS Tab Content --}}
      <div class="tab-content" id="rpsContentTabsContent">
        {{-- 1. IDENTITAS TAB --}}
        <div class="tab-pane fade show active" id="identitas" role="tabpanel">
          <div class="card border-1 bg-light p-4 mb-4" style="border-radius: 12px;">
            <h5 class="fw-bold text-dark mb-3"><i class="ti ti-info-circle text-primary me-1"></i> Deskripsi Mata Kuliah</h5>
            <p class="text-slate-700 mb-0" style="line-height: 1.6; text-align: justify;">
              {{ $rps->deskripsi_mk ?: 'Belum ada deskripsi yang diinput.' }}
            </p>
          </div>

          <div class="row g-4">
            <div class="col-md-6">
              <div class="card border border-slate-200 h-100" style="border-radius: 12px;">
                <div class="card-header bg-slate-50 border-bottom p-3">
                  <h6 class="fw-bold text-dark mb-0"><i class="ti ti-users me-1 text-primary"></i> Tim Pengajar (Dosen)</h6>
                </div>
                <div class="card-body p-3">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center justify-content-between px-0">
                      <div>
                        <div class="small fw-semibold text-dark">{{ $rps->dosenKoordinator->nama_lengkap ?? '-' }}</div>
                        <div class="text-muted small">Koordinator Mata Kuliah</div>
                      </div>
                      <span class="badge bg-primary text-white">Koordinator</span>
                    </li>
                    @foreach($rps->rpsDosens as $rd)
                      @if($rd->id_dosen !== $rps->id_dosen_koordinator)
                        <li class="list-group-item d-flex align-items-center justify-content-between px-0">
                          <div>
                            <div class="small fw-semibold text-dark">{{ $rd->dosen->nama_lengkap ?? '-' }}</div>
                            <div class="text-muted small">Anggota Pengampu</div>
                          </div>
                          <span class="badge bg-light text-dark border">Anggota</span>
                        </li>
                      @endif
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card border border-slate-200 h-100" style="border-radius: 12px;">
                <div class="card-header bg-slate-50 border-bottom p-3">
                  <h6 class="fw-bold text-dark mb-0"><i class="ti ti-notes me-1 text-primary"></i> Catatan Khusus &amp; Media</h6>
                </div>
                <div class="card-body p-3 small">
                  <div class="mb-3">
                    <strong class="text-dark d-block mb-1">Media Pembelajaran:</strong>
                    <div class="text-muted">{{ $rps->media_pembelajaran ?: 'Belum diisi' }}</div>
                  </div>
                  <div>
                    <strong class="text-dark d-block mb-1">Catatan Khusus:</strong>
                    <div class="text-muted">{{ $rps->catatan_khusus ?: 'Belum diisi' }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- 2. CPL & CPMK TAB --}}
        <div class="tab-pane fade" id="cpl-cpmk" role="tabpanel">
          <div class="mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="ti ti-circle-number-1 text-primary me-1"></i> CPL Program Studi yang Dibebankan</h5>
            <div class="table-responsive">
              <table class="table table-bordered align-middle">
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
                      <td class="fw-bold font-monospace text-primary">{{ $rcpl->cpl->kode_cpl }}</td>
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

          <div>
            <h5 class="fw-bold text-dark mb-3"><i class="ti ti-circle-number-2 text-primary me-1"></i> CPMK (Capaian Pembelajaran Mata Kuliah) &amp; Indikator</h5>
            @forelse($rps->rpsCpmks as $rcpmk)
              <div class="card border border-slate-200 mb-3 shadow-none" style="border-radius: 12px;">
                <div class="card-header bg-slate-50 border-bottom p-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark">{{ $rcpmk->cpmk->kode_cpmk }}</span>
                    <span class="badge bg-light text-dark border small">Taksonomi Bloom: {{ $rcpmk->cpmk->ranah_bloom ?? $rcpmk->cpmk->level_bloom }}</span>
                  </div>
                </div>
                <div class="card-body p-3">
                  <p class="text-slate-600 small mb-2"><strong>Deskripsi:</strong> {{ $rcpmk->cpmk->deskripsi }}</p>
                  <div class="p-2 rounded bg-light border small text-slate-700">
                    <strong>Indikator Capaian:</strong> {{ $rcpmk->indikator_capaian ?: 'Indikator belum diisi.' }}
                  </div>
                </div>
              </div>
            @empty
              <div class="alert alert-warning">Tidak ada CPMK yang dikaitkan dengan mata kuliah ini.</div>
            @endforelse
          </div>
        </div>

        {{-- 3. RENCANA PERTEMUAN TAB --}}
        <div class="tab-pane fade" id="pertemuan" role="tabpanel">
          <h5 class="fw-bold text-dark mb-3"><i class="ti ti-timeline text-primary me-1"></i> Rencana Kegiatan Pembelajaran per Pertemuan</h5>
          <div class="accordion" id="pertemuanAccordion">
            @foreach($rps->pertemuan as $p)
              @php
                $pCpmkCode = $p->id_cpmk && isset($p->cpmk) && $p->cpmk ? $p->cpmk->kode_cpmk : null;
              @endphp
              <div class="accordion-item border border-slate-200 mb-2 shadow-none" style="border-radius: 8px; overflow: hidden;">
                <h2 class="accordion-header" id="heading-{{ $p->id_pertemuan }}">
                  <button class="accordion-button collapsed py-3 px-4 bg-white d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $p->id_pertemuan }}">
                    <div class="d-flex align-items-center justify-content-between w-100 me-3">
                      <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-pill px-3 py-1 fw-bold {{ in_array($p->jenis_pertemuan, ['UTS','UAS']) ? 'bg-info text-white' : 'bg-slate-900 text-white' }}" style="font-size:0.72rem;">{{ in_array($p->jenis_pertemuan, ['UTS','UAS']) ? $p->jenis_pertemuan : 'Minggu '.$p->nomor_pertemuan }}</span>
                        <span class="fw-semibold text-dark">{{ $p->topik ?: '—' }}</span>
                      </div>
                      @if($pCpmkCode)
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size:0.68rem;"><i class="ti ti-award me-1"></i>{{ $pCpmkCode }}</span>
                      @endif
                    </div>
                    <i class="ti ti-chevron-down accordion-chevron fs-5 text-muted flex-shrink-0"></i>
                  </button>
                </h2>
                <div id="collapse-{{ $p->id_pertemuan }}" class="accordion-collapse collapse" data-bs-parent="#pertemuanAccordion">
                  <div class="accordion-body p-4 bg-light border-top">
                    <div class="row g-3 small">
                      <div class="col-md-6">
                        <div class="mb-2"><strong>Sub-CPMK:</strong> <span class="text-slate-600">{{ $p->subCpmk->deskripsi ?? '—' }}</span></div>
                        <div class="mb-2"><strong>Bahan Kajian:</strong> <div class="text-slate-600 mt-1">{!! nl2br(e($p->bahan_kajian)) !!}</div></div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-2"><strong>Metode Pembelajaran:</strong> <span class="text-slate-600">{{ $p->metode_pembelajaran ?: '—' }}</span></div>
                        <div class="mb-2"><strong>Aktivitas Mahasiswa:</strong> <div class="text-slate-600 mt-1">{!! nl2br(e($p->aktivitas_mahasiswa)) !!}</div></div>
                        <div class="mb-2"><strong>Durasi Waktu:</strong> <span class="text-slate-600">{{ $p->durasi_menit }} Menit</span></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        {{-- 4. PENILAIAN TAB --}}
        <div class="tab-pane fade" id="penilaian" role="tabpanel">
          <h5 class="fw-bold text-dark mb-3"><i class="ti ti-report-analytics text-primary me-1"></i> Komponen &amp; Bobot Asesmen</h5>
          <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
              <thead class="table-light">
                <tr>
                  <th>Nama Asesmen</th>
                  <th>Kategori</th>
                  <th>Bobot</th>
                  <th>Kriteria &amp; Deskripsi Tugas</th>
                </tr>
              </thead>
              <tbody>
                @forelse($rps->penilaian as $pen)
                  <tr>
                    <td class="fw-semibold text-dark">{{ $pen->komponenPenilaian->komponenNama ?? '-' }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $pen->komponenPenilaian->komponenJenis ?? '-' }}</span></td>
                    <td class="fw-bold text-slate-800">{{ $pen->komponenPenilaian->bobot ?? 0 }}%</td>
                    <td class="small text-slate-600">
                      <div class="mb-1"><strong>Kriteria:</strong> {{ $pen->kriteria_penilaian }}</div>
                      <div><strong>Deskripsi:</strong> {{ $pen->deskripsi_tugas }}</div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted py-3">Tidak ada data penilaian untuk mata kuliah ini.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        {{-- 5. REFERENSI TAB --}}
        <div class="tab-pane fade" id="referensi" role="tabpanel">
          <h5 class="fw-bold text-dark mb-3"><i class="ti ti-books text-primary me-1"></i> Daftar Pustaka &amp; Referensi</h5>
          <div class="list-group">
            @forelse($rps->referensi as $ref)
              <div class="list-group-item p-3 border border-slate-200 mb-2 rounded shadow-none">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <h6 class="fw-bold text-dark mb-0">{{ $ref->judul }}</h6>
                  <span class="badge bg-light text-slate-800 border">{{ $ref->jenis }}</span>
                </div>
                <div class="small text-slate-600 mb-1"><strong>Pengarang/Penerbit:</strong> {{ $ref->pengarang }} ({{ $ref->penerbit }}, {{ $ref->tahun_terbit }})</div>
                @if($ref->tautan_sumber)
                  <a href="{{ $ref->tautan_sumber }}" target="_blank" class="small text-decoration-none d-inline-flex align-items-center gap-1 mt-1 text-primary">
                    <i class="ti ti-link"></i> Buka Tautan Sumber
                  </a>
                @endif
              </div>
            @empty
              <div class="alert alert-warning py-3 text-center">Daftar pustaka/referensi belum diinput.</div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    {{-- ============================================================ --}}
    {{-- PANEL KANAN: CHECKLIST, KOMENTAR & KEPUTUSAN (35% width)    --}}
    {{-- ============================================================ --}}
    <div class="col-lg-4 bg-light p-4" style="height: calc(100vh - 70px); overflow-y: auto; border-left: 1px solid #e2e8f0;">
      {{-- Right Panel Tabs --}}
      <ul class="nav nav-pills nav-fill mb-4 bg-white p-1 rounded-3 border" role="tablist">
        <li class="nav-item">
          <button class="nav-link active fw-bold py-2" id="checklist-tab" data-bs-toggle="pill" data-bs-target="#panel-checklist" type="button" role="tab">Checklist Evaluasi</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-bold py-2" id="comments-tab" data-bs-toggle="pill" data-bs-target="#panel-comments" type="button" role="tab">Komentar &amp; Revisi</button>
        </li>
      </ul>

      <form action="{{ route('rps-review.decision', $rps->id_rps) }}" method="POST" id="form-decision">
        @csrf
        <div class="tab-content">
          {{-- TAB 1: CHECKLIST EVALUASI --}}
          <div class="tab-pane fade show active" id="panel-checklist" role="tabpanel">
            <h5 class="fw-bold text-dark mb-3">Checklist Mutu RPS</h5>
            <p class="text-muted small">Setiap poin kelengkapan beriku wajib dijawab Kaprodi untuk memastikan RPS berkualitas prima.</p>

            <div class="accordion" id="checklistAccordion">
              @php
                $categories = [
                  'Kelengkapan' => 'Kelengkapan Komponen (KL)',
                  'Kualitas_CPMK' => 'Kualitas CPMK (QC)',
                  'Kessesuaian_Penilaian' => 'Kesesuaian Penilaian (KP)',
                  'Distribusi_Pertemuan' => 'Distribusi Pertemuan (DP)',
                  'Referensi' => 'Referensi & Pustaka (REF)',
                  'OBE_Alignment' => 'Keselarasan OBE (OBE)'
                ];
              @endphp

              @foreach($categories as $catCode => $catLabel)
                @php
                  // Handle typo in database seeder category naming
                  $items = $checklistItems->filter(fn($i) => $i->kategori === $catCode || ($catCode === 'Kessesuaian_Penilaian' && $i->kategori === 'Kesesuaian_Penilaian'));
                @endphp
                @if($items->isNotEmpty())
                  <div class="accordion-item border border-slate-200 mb-2 shadow-none" style="border-radius: 8px; overflow:hidden;">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed py-2 px-3 bg-white d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-check-{{ $catCode }}">
                        <strong class="text-dark small flex-grow-1">{{ $catLabel }}</strong>
                        <i class="ti ti-chevron-down accordion-chevron fs-5 text-muted"></i>
                      </button>
                    </h2>
                    <div id="collapse-check-{{ $catCode }}" class="accordion-collapse collapse" data-bs-parent="#checklistAccordion">
                      <div class="accordion-body p-3 bg-white border-top">
                        @foreach($items as $item)
                          <div class="mb-4 pb-3 border-bottom border-slate-100 last-border-none">
                            <label class="form-label d-block text-dark small mb-2" style="font-weight: 600;">
                              {{ $item->kode_item }} — {{ $item->pertanyaan }}
                              @if($item->is_wajib)
                                <span class="text-danger">*</span>
                              @endif
                            </label>
                            @if($item->panduan)
                              <span class="text-muted d-block small mb-2" style="font-size: 0.72rem;">{{ $item->panduan }}</span>
                            @endif

                            @if($item->tipe_jawaban === 'Ya_Tidak')
                              <div class="d-flex gap-4">
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input check-input" type="radio" name="checklist[{{ $item->id_item }}]" id="check-ya-{{ $item->id_item }}" value="1" {{ ($answers[$item->id_item] ?? null) === 1 ? 'checked' : '' }} {{ $item->is_wajib ? 'required' : '' }}>
                                  <label class="form-check-label text-slate-700 small" for="check-ya-{{ $item->id_item }}">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input check-input" type="radio" name="checklist[{{ $item->id_item }}]" id="check-tidak-{{ $item->id_item }}" value="0" {{ ($answers[$item->id_item] ?? null) === 0 ? 'checked' : '' }} {{ $item->is_wajib ? 'required' : '' }}>
                                  <label class="form-check-label text-slate-700 small" for="check-tidak-{{ $item->id_item }}">Tidak</label>
                                </div>
                              </div>
                            @elseif($item->tipe_jawaban === 'Skala_1_4')
                              <select class="form-select form-select-sm check-input" name="checklist_skala[{{ $item->id_item }}]" {{ $item->is_wajib ? 'required' : '' }}>
                                <option value="">-- Pilih Nilai --</option>
                                <option value="4" {{ ($answersSkala[$item->id_item] ?? null) === 4 ? 'selected' : '' }}>4 — Sangat Baik</option>
                                <option value="3" {{ ($answersSkala[$item->id_item] ?? null) === 3 ? 'selected' : '' }}>3 — Baik</option>
                                <option value="2" {{ ($answersSkala[$item->id_item] ?? null) === 2 ? 'selected' : '' }}>2 — Cukup</option>
                                <option value="1" {{ ($answersSkala[$item->id_item] ?? null) === 1 ? 'selected' : '' }}>1 — Kurang</option>
                              </select>
                            @else
                              <textarea class="form-control form-control-sm" name="checklist_teks[{{ $item->id_item }}]" rows="2" placeholder="Catatan atau masukan khusus...">{{ $answersTeks[$item->id_item] ?? '' }}</textarea>
                            @endif
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                @endif
              @endforeach
            </div>
          </div>

          {{-- TAB 2: KOMENTAR & REVISI --}}
          <div class="tab-pane fade" id="panel-comments" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="fw-bold text-dark mb-0">Catatan &amp; Masukan</h5>
              <button type="button" class="btn btn-primary btn-xs" data-bs-toggle="collapse" data-bs-target="#newCommentFormCard">
                <i class="ti ti-plus"></i> Tambah Masukan
              </button>
            </div>

            {{-- New Comment Form (Collapsible) --}}
            <div class="collapse mb-3" id="newCommentFormCard">
              <div class="card border border-slate-200 p-3 shadow-sm bg-white" style="border-radius: 12px;">
                <div class="mb-2">
                  <label class="form-label text-muted small fw-bold">Bagian (Seksi) RPS</label>
                  <select id="comment_seksi" class="form-select form-select-sm">
                    <option value="UMUM">Umum (Satu RPS)</option>
                    <option value="IDENTITAS">Identitas &amp; Deskripsi</option>
                    <option value="CPL_CPMK">CPL &amp; CPMK</option>
                    <option value="PERTEMUAN">Rencana Pertemuan</option>
                    <option value="PENILAIAN">Komponen Penilaian</option>
                    <option value="REFERENSI">Referensi / Pustaka</option>
                  </select>
                </div>
                <div class="mb-2">
                  <label class="form-label text-muted small fw-bold">Prioritas Masukan</label>
                  <div class="d-flex gap-2 flex-wrap">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="comment_priority" id="prio-wajib" value="Wajib_Diperbaiki" checked>
                      <label class="form-check-label small text-danger fw-semibold" for="prio-wajib">🔴 Wajib Perbaiki</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="comment_priority" id="prio-saran" value="Saran">
                      <label class="form-check-label small text-warning fw-semibold" for="prio-saran">🟡 Saran</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="comment_priority" id="prio-tanya" value="Pertanyaan">
                      <label class="form-check-label small text-info fw-semibold" for="prio-tanya">🔵 Tanya</label>
                    </div>
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label text-muted small fw-bold">Isi Masukan / Komentar</label>
                  <textarea id="comment_content" class="form-control form-control-sm" rows="3" placeholder="Tuliskan detail masukan di sini..."></textarea>
                </div>
                <button type="button" class="btn btn-dark btn-sm w-100" id="btn-save-comment">Simpan Catatan</button>
              </div>
            </div>

            {{-- Comments List Container --}}
            <div id="comments-list" class="d-flex flex-column gap-3">
              @forelse($comments as $c)
                @php
                  $prioColors = [
                    'Wajib_Diperbaiki' => 'danger',
                    'Saran' => 'warning text-dark',
                    'Pertanyaan' => 'info',
                    'Apresiasi' => 'success'
                  ];
                  $pCol = $prioColors[$c->prioritas] ?? 'dark';
                @endphp
                <div class="card border border-slate-200 p-3 bg-white shadow-none" style="border-radius: 12px;" id="comment-card-{{ $c->id_komentar }}">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-{{ $pCol }} small">{{ str_replace('_', ' ', $c->prioritas) }}</span>
                    <span class="badge bg-light text-dark border">{{ $c->seksi }}</span>
                  </div>
                  <p class="small text-slate-800 mb-2" style="white-space: pre-wrap;">{{ $c->isi_komentar }}</p>
                  
                  @if($c->balasan_dosen)
                    <div class="p-2 rounded bg-light border border-slate-100 small mt-2 mb-2">
                      <strong class="text-primary d-block mb-1">Balasan Dosen:</strong>
                      <span class="text-slate-700">{{ $c->balasan_dosen }}</span>
                    </div>
                  @endif

                  <div class="d-flex justify-content-between align-items-center mt-2 border-top pt-2">
                    <span class="text-muted small" style="font-size: 0.68rem;">
                      Oleh: {{ $c->created_by->name ?? 'Reviewer' }}
                    </span>
                    <div class="d-flex align-items-center gap-1">
                      <button type="button" class="btn btn-xs {{ $c->status_komentar === 'Ditutup' ? 'btn-success' : 'btn-outline-secondary' }} toggle-comment-btn" data-id="{{ $c->id_komentar }}">
                        <i class="ti ti-checkbox"></i> {{ $c->status_komentar === 'Ditutup' ? 'Selesai' : 'Tandai Selesai' }}
                      </button>
                    </div>
                  </div>
                </div>
              @empty
                <div class="alert alert-light border text-center py-4 text-muted small" id="no-comments-alert">
                  Belum ada komentar atau catatan yang ditambahkan.
                </div>
              @endforelse
            </div>
          </div>
        </div>

        {{-- ============================================================ --}}
        {{-- KEPUTUSAN PANEL (Always pinned at bottom)                    --}}
        {{-- ============================================================ --}}
        <div class="mt-4 pt-4 border-top">
          <h6 class="fw-bold text-dark mb-2">Langkah Keputusan Review</h6>
          <div class="mb-3">
            <select class="form-select border-primary" name="keputusan" id="select-keputusan" required>
              <option value="">-- Pilih Keputusan --</option>
              <option value="Disetujui">Setujui RPS (Mutu Terpenuhi) ✅</option>
              <option value="Kembalikan">Kembalikan (Perlu Perbaikan) ↩</option>
              <option value="Ditolak">Tolak RPS (Dibuat Ulang) ❌</option>
            </select>
          </div>
          <div class="mb-3" id="catatan-keputusan-group">
            <label class="form-label small fw-bold text-muted">Catatan Keputusan / Alasan</label>
            <textarea class="form-control" name="catatan_keputusan" rows="3" placeholder="Masukkan alasan keputusan (wajib jika ditolak atau dikembalikan)..."></textarea>
          </div>

          <button type="submit" class="btn btn-dark py-2 fw-bold" id="btn-submit-decision">Simpan &amp; Terapkan Keputusan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const rpsId = "{{ $rps->id_rps }}";
  const activeReviewId = "{{ $activeReview->id_review }}";
  const storeCommentUrl = "{{ route('rps-review.comment.store', ':id') }}".replace(':id', activeReviewId);

  // Prio tag helper
  const prioBadges = {
    'Wajib_Diperbaiki': 'danger',
    'Saran': 'warning text-dark',
    'Pertanyaan': 'info',
    'Apresiasi': 'success'
  };

  // Add comment event Handler
  document.getElementById('btn-save-comment').addEventListener('click', function() {
    const seksi = document.getElementById('comment_seksi').value;
    const prioritas = document.querySelector('input[name="comment_priority"]:checked').value;
    const content = document.getElementById('comment_content').value;

    if (!content.trim()) {
      alert('Isi masukan tidak boleh kosong.');
      return;
    }

    fetch(storeCommentUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        seksi: seksi,
        prioritas: prioritas,
        isi_komentar: content
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        // Remove no comments placeholder
        const noCommentsAlert = document.getElementById('no-comments-alert');
        if (noCommentsAlert) noCommentsAlert.remove();

        const badgeClass = prioBadges[data.komentar.prioritas] || 'dark';

        // Prepend new comment to list
        const commentHtml = `
          <div class="card border border-slate-200 p-3 bg-white shadow-none" style="border-radius: 12px;" id="comment-card-${data.komentar.id_komentar}">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="badge bg-${badgeClass} small">${data.komentar.prioritas.replace('_', ' ')}</span>
              <span class="badge bg-light text-dark border">${data.komentar.seksi}</span>
            </div>
            <p class="small text-slate-800 mb-2" style="white-space: pre-wrap;">${data.komentar.isi_komentar}</p>
            <div class="d-flex justify-content-between align-items-center mt-2 border-top pt-2">
              <span class="text-muted small" style="font-size: 0.68rem;">Oleh: ${data.komentar.creator_name}</span>
              <button type="button" class="btn btn-xs btn-outline-secondary toggle-comment-btn" data-id="${data.komentar.id_komentar}">
                <i class="ti ti-checkbox"></i> Tandai Selesai
              </button>
            </div>
          </div>
        `;
        document.getElementById('comments-list').insertAdjacentHTML('afterbegin', commentHtml);
        document.getElementById('comment_content').value = '';
        
        // Collapse new comment card
        const collapseElement = document.getElementById('newCommentFormCard');
        const bsCollapse = bootstrap.Collapse.getInstance(collapseElement) || new bootstrap.Collapse(collapseElement);
        bsCollapse.hide();
        
        attachCommentToggleListeners();
      }
    });
  });

  // Comment Resolving Toggle handler
  function attachCommentToggleListeners() {
    document.querySelectorAll('.toggle-comment-btn').forEach(btn => {
      btn.onclick = function() {
        const idComment = this.getAttribute('data-id');
        const url = "{{ route('rps-review.comment.toggle', ':id') }}".replace(':id', idComment);

        fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            if (data.status === 'Ditutup') {
              this.classList.remove('btn-outline-secondary');
              this.classList.add('btn-success');
              this.innerHTML = '<i class="ti ti-checkbox"></i> Selesai';
            } else {
              this.classList.remove('btn-success');
              this.classList.add('btn-outline-secondary');
              this.innerHTML = '<i class="ti ti-checkbox"></i> Tandai Selesai';
            }
          }
        });
      };
    });
  }

  attachCommentToggleListeners();
});
</script>
@endsection
