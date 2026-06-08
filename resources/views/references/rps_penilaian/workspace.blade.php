@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
  <div class="row g-0" style="min-height: calc(100vh - 70px);">
    {{-- Main Workspace Area --}}
    <div class="col-12 bg-white p-4" style="height: calc(100vh - 70px); overflow-y: auto;">
      
      {{-- Header --}}
      <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
        <div>
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-primary-subtle text-primary font-monospace fw-bold">{{ $rps->kurikulumMataKuliah->mataKuliah->mk_kode }}</span>
            <span class="badge bg-slate-900 text-white">{{ $rps->tahunAkademik->nama_ta }}</span>
            <span class="badge {{ abs($totalWeight - 100.0) < 0.01 ? 'bg-success' : 'bg-danger' }} text-white" id="badge-total-weight">Total Bobot: {{ $totalWeight }}%</span>
          </div>
          <h3 class="fw-bold text-dark mb-1">Workspace Metode &amp; Bobot Penilaian</h3>
          <p class="text-muted small mb-0">
            Mata Kuliah: <strong>{{ $rps->kurikulumMataKuliah->mataKuliah->mk_nama }}</strong>
            &nbsp;•&nbsp; {{ $rps->kurikulumMataKuliah->kurikulum->programStudi->prodiNamaResmi }}
          </p>
        </div>
        <a href="{{ route('rps-penilaian.index') }}" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-1">
          <i class="ti ti-arrow-left"></i> Kembali ke Daftar
        </a>
      </div>

      {{-- Workspace Tabs Navigation --}}
      <ul class="nav nav-tabs nav-fill mb-4" id="assessmentTabs" role="tablist">
        <li class="nav-item">
          <button class="nav-link active fw-bold py-2" id="bobot-tab" data-bs-toggle="tab" data-bs-target="#panel-bobot" type="button" role="tab"><i class="ti ti-report-analytics me-1"></i>Bobot &amp; Komponen</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-bold py-2" id="mapping-tab" data-bs-toggle="tab" data-bs-target="#panel-mapping" type="button" role="tab"><i class="ti ti-layout-align-middle me-1"></i>Pemetaan CPMK</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-bold py-2" id="rubrik-tab" data-bs-toggle="tab" data-bs-target="#panel-rubrik" type="button" role="tab"><i class="ti ti-table me-1"></i>Penyusunan Rubrik</button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-bold py-2" id="policies-tab" data-bs-toggle="tab" data-bs-target="#panel-policies" type="button" role="tab"><i class="ti ti-certificate me-1"></i>Skala &amp; Kebijakan Kelulusan</button>
        </li>
      </ul>

      {{-- Tabs Content --}}
      <div class="tab-content">
        
        {{-- TAB 1: BOBOT & KOMPONEN --}}
        <div class="tab-pane fade show active" id="panel-bobot" role="tabpanel">
          <div class="row">
            <div class="col-lg-8">
              <h5 class="fw-bold text-dark mb-3">Daftar Komponen Penilaian</h5>
              <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>Komponen Asesmen</th>
                      <th width="100px" class="text-center">Bobot</th>
                      <th>Bentuk &amp; Waktu</th>
                      <th>Deskripsi Tugas</th>
                      <th width="100px" class="text-end">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($rps->penilaian as $pen)
                      <tr id="row-pen-{{ $pen->id_rps_penilaian }}">
                        <td class="fw-semibold text-dark">
                          {{ $pen->komponenPenilaian->komponenNama ?? 'Komponen' }}
                          <span class="badge bg-light text-dark border d-block mt-1 font-monospace" style="font-size: 0.65rem; width: fit-content;">{{ $pen->komponenPenilaian->komponenJenis ?? 'Sumatif' }}</span>
                        </td>
                        <td class="text-center fw-bold text-slate-800" style="font-size: 1.1rem;">
                          <span class="weight-display">{{ $pen->komponenPenilaian->bobot ?? 0 }}</span>%
                        </td>
                        <td class="small">
                          <div><strong>Bentuk:</strong> <span class="bentuk-display">{{ $pen->bentuk_soal ?: 'Essay' }}</span></div>
                          <div class="mt-1"><strong>Waktu:</strong> <span class="waktu-display">{{ $pen->waktu_pelaksanaan ?: 'Minggu' }}</span></div>
                        </td>
                        <td class="small text-muted text-truncate" style="max-width: 250px;">
                          <span class="desc-display">{{ $pen->deskripsi_tugas ?: 'Belum ada penjelasan deskripsi tugas.' }}</span>
                        </td>
                        <td class="text-end">
                          <button type="button" class="btn btn-outline-dark btn-sm edit-pen-btn" 
                                  data-id="{{ $pen->id_rps_penilaian }}"
                                  data-name="{{ $pen->komponenPenilaian->komponenNama }}"
                                  data-bobot="{{ $pen->komponenPenilaian->bobot }}"
                                  data-bentuk="{{ $pen->bentuk_soal ?: 'Essay' }}"
                                  data-waktu="{{ $pen->waktu_pelaksanaan ?: '' }}"
                                  data-desc="{{ $pen->deskripsi_tugas ?: '' }}">
                            <i class="ti ti-pencil"></i> Edit
                          </button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>

            {{-- Right Editor Card --}}
            <div class="col-lg-4">
              <div class="card border border-slate-200 p-4 shadow-sm bg-light" style="border-radius: 16px;" id="editor-pen-card">
                <h5 class="fw-bold text-dark mb-3"><i class="ti ti-pencil text-primary me-1"></i> Edit Komponen</h5>
                <p class="text-muted small">Pilih tombol **Edit** pada baris tabel komponen di sebelah kiri untuk memuat data pengerjaan.</p>
                <form id="form-edit-pen" style="display: none;">
                  <input type="hidden" id="edit_id">
                  <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Nama Komponen</label>
                    <input type="text" class="form-control" id="edit_name" disabled>
                  </div>
                  <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Bobot Komponen (%)</label>
                    <input type="number" class="form-control border-primary" id="edit_bobot" min="0" max="100" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Bentuk Asesmen</label>
                    <select id="edit_bentuk" class="form-select" required>
                      <option value="Essay">Essay / Uraian</option>
                      <option value="PG">Pilihan Ganda (PG)</option>
                      <option value="Proyek">Proyek Kelompok / Individu</option>
                      <option value="Presentasi">Presentasi / Viva</option>
                      <option value="Portofolio">Portofolio Karya</option>
                      <option value="Praktikum">Praktikum Lapangan / Lab</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Waktu Pelaksanaan</label>
                    <input type="text" class="form-control" id="edit_waktu" placeholder="Misal: Pertemuan 8, Minggu ke-16" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Deskripsi Tugas</label>
                    <textarea id="edit_desc" class="form-control" rows="4" placeholder="Jelaskan rincian instruksi tugas asesmen..."></textarea>
                  </div>
                  <button type="submit" class="btn btn-dark w-100 py-2 fw-bold">Simpan Komponen</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        {{-- TAB 2: PEMETAAN CPMK --}}
        <div class="tab-pane fade" id="panel-mapping" role="tabpanel">
          <h5 class="fw-bold text-dark mb-3">Matriks Pemetaan Asesmen ke CPMK</h5>
          <p class="text-muted small">Tentukan bobot kontribusi instrumen penilaian terhadap pemenuhan masing-masing Capaian Pembelajaran Mata Kuliah (CPMK).</p>

          <form id="form-save-mapping">
            <div class="table-responsive mb-4">
              <table class="table table-bordered align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Komponen Penilaian</th>
                    @foreach($cpmkList as $c)
                      <th class="text-center" width="120px">
                        <div>{{ $c->kode_cpmk }}</div>
                        <span class="text-muted" style="font-size: 0.65rem; font-weight: normal;">Bloom: {{ $c->ranah_bloom ?? $c->level_bloom }}</span>
                      </th>
                    @endforeach
                  </tr>
                </thead>
                <tbody>
                  @foreach($rps->penilaian as $pen)
                    <tr>
                      <td class="fw-semibold text-dark">{{ $pen->komponenPenilaian->komponenNama ?? 'Komponen' }}</td>
                      @foreach($cpmkList as $c)
                        @php
                          $existingWeight = $mappedCpmkWeights->get($pen->id_rps_penilaian)?->firstWhere('id_cpmk', $c->id)?->bobot_dalam_cpmk;
                        @endphp
                        <td>
                          <div class="input-group input-group-sm">
                            <input type="number" class="form-control text-center font-monospace" 
                                   name="mappings[{{ $pen->id_rps_penilaian }}][{{ $c->id }}]" 
                                   min="0" max="100" placeholder="—"
                                   value="{{ $existingWeight }}">
                            <span class="input-group-text bg-light">%</span>
                          </div>
                        </td>
                      @endforeach
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-dark py-2 px-4 fw-bold">Simpan Matriks Pemetaan</button>
            </div>
          </form>
        </div>

        {{-- TAB 3: PENYUSUNAN RUBRIK --}}
        <div class="tab-pane fade" id="panel-rubrik" role="tabpanel">
          <div class="row">
            <div class="col-md-4">
              <h5 class="fw-bold text-dark mb-3">Pilih Komponen</h5>
              <div class="list-group">
                @foreach($rps->penilaian as $pen)
                  <button type="button" class="list-group-item list-group-item-action p-3 d-flex justify-content-between align-items-center component-rubric-btn" data-id="{{ $pen->id_rps_penilaian }}" data-name="{{ $pen->komponenPenilaian->komponenNama }}">
                    <div>
                      <strong class="text-dark">{{ $pen->komponenPenilaian->komponenNama }}</strong>
                      <span class="text-muted d-block small">{{ $pen->bentuk_soal ?: 'Essay' }}</span>
                    </div>
                    <i class="ti ti-chevron-right text-muted"></i>
                  </button>
                @endforeach
              </div>
            </div>

            <div class="col-md-8 border-start ps-4">
              <div id="rubric-workspace-card" class="card border border-slate-200 p-4 bg-white" style="border-radius: 16px; display:none;">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                  <div>
                    <h5 class="fw-bold text-dark mb-1" id="rubric-comp-name">Komponen Rubrik</h5>
                    <span class="badge bg-light text-dark border">Penyusunan Rubrik Analitik</span>
                  </div>
                  <button type="button" class="btn btn-primary btn-sm fw-bold" id="btn-add-crit"><i class="ti ti-plus"></i> Kriteria Baru</button>
                </div>

                {{-- Interactive Kriteria & Levels form --}}
                <form id="form-save-rubrik">
                  <input type="hidden" id="rubric_penilaian_id">
                  <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Nama Kriteria Penilaian</label>
                    <input type="text" class="form-control" name="nama_kriteria" placeholder="Misal: Kelengkapan Desain Database, Kualitas Presentasi" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Bobot Kriteria dalam Komponen (%)</label>
                    <input type="number" class="form-control" name="bobot_kriteria" min="0" max="100" placeholder="Contoh: 30" required>
                  </div>

                  <h6 class="fw-bold text-dark mt-4 mb-3 border-bottom pb-2">Level Capaian Rubrik</h6>
                  <div class="d-flex flex-column gap-3">
                    @php
                      $lvlTemplates = [
                        ['no' => 4, 'label' => 'Sangat Baik', 'min' => 85, 'max' => 100],
                        ['no' => 3, 'label' => 'Baik', 'min' => 70, 'max' => 84],
                        ['no' => 2, 'label' => 'Cukup', 'min' => 55, 'max' => 69],
                        ['no' => 1, 'label' => 'Kurang', 'min' => 0, 'max' => 54],
                      ];
                    @endphp
                    @foreach($lvlTemplates as $lt)
                      <div class="p-3 bg-light border rounded" style="border-radius: 12px;">
                        <div class="row g-2 mb-2 align-items-center">
                          <div class="col-md-3">
                            <span class="fw-bold text-dark" style="font-size: 0.85rem;">Level {{ $lt['no'] }} ({{ $lt['label'] }})</span>
                            <input type="hidden" name="levels[{{ $lt['no'] }}][nomor_level]" value="{{ $lt['no'] }}">
                            <input type="hidden" name="levels[{{ $lt['no'] }}][label_level]" value="{{ $lt['label'] }}">
                          </div>
                          <div class="col-md-4">
                            <div class="input-group input-group-sm">
                              <span class="input-group-text bg-white small" style="font-size: 0.72rem;">Min</span>
                              <input type="number" class="form-control text-center font-monospace" name="levels[{{ $lt['no'] }}][nilai_min]" value="{{ $lt['min'] }}" min="0" max="100" required>
                              <span class="input-group-text bg-white small" style="font-size: 0.72rem;">Max</span>
                              <input type="number" class="form-control text-center font-monospace" name="levels[{{ $lt['no'] }}][nilai_max]" value="{{ $lt['max'] }}" min="0" max="100" required>
                            </div>
                          </div>
                        </div>
                        <textarea class="form-control form-control-sm" name="levels[{{ $lt['no'] }}][deskripsi]" rows="2" placeholder="Deskripsikan kriteria capaian mahasiswa pada level ini..." required></textarea>
                      </div>
                    @endforeach
                  </div>

                  <button type="submit" class="btn btn-dark w-100 py-2 fw-bold mt-4">Simpan Rubrik Kriteria</button>
                </form>
              </div>

              <div id="no-rubric-alert" class="alert alert-light border text-center py-5">
                <i class="ti ti-table fs-1 text-muted d-block mb-3" style="font-size: 3.5rem !important;"></i>
                <h6 class="fw-bold text-dark">Belum Ada Komponen Dipilih</h6>
                <p class="text-muted small mb-0">Klik tombol komponen asesmen di sebelah kiri untuk mulai mengelola kriteria rubrik.</p>
              </div>
            </div>
          </div>
        </div>

        {{-- TAB 4: SKALA & KEBIJAKAN KELULUSAN --}}
        <div class="tab-pane fade" id="panel-policies" role="tabpanel">
          <div class="row">
            {{-- Skala Nilai Huruf --}}
            <div class="col-lg-6 mb-4">
              <h5 class="fw-bold text-dark mb-3">Skala Nilai Huruf Mutu</h5>
              <p class="text-muted small">Rentang nilai konversi angka (0-100) ke huruf mutu standar yang berlaku pada RPS mata kuliah ini.</p>
              
              <form id="form-save-policies">
                <div class="table-responsive mb-3">
                  <table class="table table-bordered align-middle">
                    <thead class="table-light">
                      <tr>
                        <th class="text-center">Huruf</th>
                        <th class="text-center" width="200px">Rentang Nilai</th>
                        <th class="text-center">Bobot Mutu</th>
                        <th class="text-center">Lulus?</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($skalaNilai as $index => $sn)
                        <tr>
                          <td class="text-center fw-bold text-primary" style="font-size: 1.1rem;">
                            {{ $sn->huruf_mutu }}
                            <input type="hidden" name="scales[{{ $index }}][id_skala]" value="{{ $sn->id_skala }}">
                            <input type="hidden" name="scales[{{ $index }}][huruf_mutu]" value="{{ $sn->huruf_mutu }}">
                          </td>
                          <td>
                            <div class="input-group input-group-sm">
                              <input type="number" class="form-control text-center font-monospace" name="scales[{{ $index }}][nilai_min]" value="{{ $sn->nilai_min }}" step="0.01" required>
                              <span class="input-group-text bg-light">-</span>
                              <input type="number" class="form-control text-center font-monospace" name="scales[{{ $index }}][nilai_max]" value="{{ $sn->nilai_max }}" step="0.01" required>
                            </div>
                          </td>
                          <td class="text-center font-monospace">
                            <input type="number" class="form-control form-control-sm text-center" name="scales[{{ $index }}][bobot_mutu]" value="{{ $sn->bobot_mutu }}" step="0.1" min="0" max="4" required>
                          </td>
                          <td class="text-center">
                            <select class="form-select form-select-sm" name="scales[{{ $index }}][is_lulus]">
                              <option value="1" {{ $sn->is_lulus ? 'selected' : '' }}>Lulus</option>
                              <option value="0" {{ !$sn->is_lulus ? 'selected' : '' }}>Gagal</option>
                            </select>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>

                {{-- Kebijakan Syarat Kelulusan --}}
                <h5 class="fw-bold text-dark mt-5 mb-3">Kebijakan Syarat Kelulusan Tambahan</h5>
                <div class="d-flex flex-column gap-3 mb-4" id="policies-list">
                  @forelse($policies as $p)
                    <div class="card border border-slate-200 p-3 bg-light shadow-none position-relative" style="border-radius: 12px;">
                      <strong class="text-dark small d-block mb-1">{{ str_replace('_', ' ', $p->jenis_syarat) }}</strong>
                      <p class="small text-muted mb-1">{{ $p->deskripsi_syarat }}</p>
                      <span class="text-danger small fw-bold">Konsekuensi: {{ $p->konsekuensi }}</span>
                    </div>
                  @empty
                    <div class="alert alert-light border text-center py-4 text-muted small" id="no-policies-alert">
                      Belum ada syarat kelulusan tambahan yang dikonfigurasi.
                    </div>
                  @endforelse
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-dark py-2 px-5 fw-bold">Simpan Kebijakan Kelulusan</button>
                </div>
              </form>
            </div>

            {{-- Policy Editor --}}
            <div class="col-lg-6">
              <div class="card border border-slate-200 p-4 shadow-sm bg-light" style="border-radius: 16px;">
                <h5 class="fw-bold text-dark mb-3"><i class="ti ti-plus text-primary me-1"></i> Tambah Syarat Kelulusan</h5>
                <form id="form-add-policy">
                  <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Jenis Syarat</label>
                    <select id="policy_jenis" class="form-select" required>
                      <option value="Nilai_Min_Akhir">Minimum Nilai Akhir</option>
                      <option value="Nilai_Min_Komponen">Minimum Nilai Komponen Asesmen</option>
                      <option value="Kehadiran_Min">Minimum Persentase Kehadiran</option>
                      <option value="Wajib_Ikut_Komponen">Wajib Mengikuti Ujian/Tugas</option>
                      <option value="Lainnya">Syarat Khusus Lainnya</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Nilai Ambang Batas (%)</label>
                    <input type="number" class="form-control" id="policy_ambang" placeholder="Contoh: 55, 75">
                  </div>
                  <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Deskripsi Syarat</label>
                    <textarea id="policy_desc" class="form-control" rows="2" placeholder="Misal: Mahasiswa wajib memperoleh nilai UAS minimal 40..." required></textarea>
                  </div>
                  <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Konsekuensi Kelalaian</label>
                    <textarea id="policy_consequence" class="form-control" rows="2" placeholder="Misal: Dinyatakan tidak lulus MK meskipun nilai akhir mencukupi..." required></textarea>
                  </div>
                  <button type="button" class="btn btn-dark w-100 py-2 fw-bold" id="btn-add-policy-tolist">Tambahkan Syarat</button>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const rpsId = "{{ $rps->id_rps }}";

  // Tab 1: Edit Component Penilaian
  document.querySelectorAll('.edit-pen-btn').forEach(btn => {
    btn.onclick = function() {
      const id = this.getAttribute('data-id');
      const name = this.getAttribute('data-name');
      const bobot = this.getAttribute('data-bobot');
      const bentuk = this.getAttribute('data-bentuk');
      const waktu = this.getAttribute('data-waktu');
      const desc = this.getAttribute('data-desc');

      document.getElementById('edit_id').value = id;
      document.getElementById('edit_name').value = name;
      document.getElementById('edit_bobot').value = bobot;
      document.getElementById('edit_bentuk').value = bentuk;
      document.getElementById('edit_waktu').value = waktu;
      document.getElementById('edit_desc').value = desc;

      document.getElementById('form-edit-pen').style.display = 'block';
    };
  });

  document.getElementById('form-edit-pen').onsubmit = function(e) {
    e.preventDefault();
    const id = document.getElementById('edit_id').value;
    const bobot = document.getElementById('edit_bobot').value;
    const bentuk = document.getElementById('edit_bentuk').value;
    const waktu = document.getElementById('edit_waktu').value;
    const desc = document.getElementById('edit_desc').value;

    const url = "{{ route('rps-penilaian.bobot.update', ':id') }}".replace(':id', rpsId);

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        id_rps_penilaian: id,
        bobot: bobot,
        bentuk_soal: bentuk,
        waktu_pelaksanaan: waktu,
        deskripsi_tugas: desc
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        // Update Row UI
        const row = document.getElementById('row-pen-' + id);
        row.querySelector('.weight-display').textContent = bobot;
        row.querySelector('.bentuk-display').textContent = bentuk;
        row.querySelector('.waktu-display').textContent = waktu;
        row.querySelector('.desc-display').textContent = desc;

        // Update total weight badge
        const badge = document.getElementById('badge-total-weight');
        badge.textContent = `Total Bobot: ${data.total_weight}%`;
        badge.className = `badge ${Math.abs(data.total_weight - 100.0) < 0.01 ? 'bg-success' : 'bg-danger'} text-white`;

        alert(data.message);
      }
    });
  };

  // Tab 2: Save CPMK Mapping matrices
  document.getElementById('form-save-mapping').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const url = "{{ route('rps-penilaian.mapping.save', ':id') }}".replace(':id', rpsId);

    fetch(url, {
      method: 'POST',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert(data.message);
      }
    });
  };

  // Tab 3: Rubric workspace selection
  document.querySelectorAll('.component-rubric-btn').forEach(btn => {
    btn.onclick = function() {
      const id = this.getAttribute('data-id');
      const name = this.getAttribute('data-name');

      document.getElementById('no-rubric-alert').style.display = 'none';
      const card = document.getElementById('rubric-workspace-card');
      card.style.display = 'block';

      document.getElementById('rubric-comp-name').textContent = `Rubrik Penilaian: ${name}`;
      document.getElementById('rubric_penilaian_id').value = id;
    };
  });

  document.getElementById('form-save-rubrik').onsubmit = function(e) {
    e.preventDefault();
    const idPen = document.getElementById('rubric_penilaian_id').value;
    const url = "{{ route('rps-penilaian.rubrik.save', ':id') }}".replace(':id', idPen);
    const formData = new FormData(this);

    fetch(url, {
      method: 'POST',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert(data.message);
      }
    });
  };

  // Tab 4: Add policies locally before final submit
  let temporaryPolicies = [];
  document.getElementById('btn-add-policy-tolist').addEventListener('click', function() {
    const jenis = document.getElementById('policy_jenis').value;
    const ambang = document.getElementById('policy_ambang').value;
    const desc = document.getElementById('policy_desc').value;
    const consequence = document.getElementById('policy_consequence').value;

    if (!desc || !consequence) {
      alert('Deskripsi dan Konsekuensi wajib diisi.');
      return;
    }

    // Append to list UI
    const noAlert = document.getElementById('no-policies-alert');
    if (noAlert) noAlert.remove();

    const policyIndex = temporaryPolicies.length;
    const cardHtml = `
      <div class="card border border-slate-200 p-3 bg-light shadow-none position-relative" style="border-radius: 12px;">
        <strong class="text-dark small d-block mb-1">${jenis.replace(/_/g, ' ')}</strong>
        <p class="small text-muted mb-1">${desc}</p>
        <span class="text-danger small fw-bold">Konsekuensi: ${consequence}</span>
        
        <input type="hidden" name="policies[${policyIndex}][jenis_syarat]" value="${jenis}">
        <input type="hidden" name="policies[${policyIndex}][nilai_ambang]" value="${ambang}">
        <input type="hidden" name="policies[${policyIndex}][deskripsi_syarat]" value="${desc}">
        <input type="hidden" name="policies[${policyIndex}][konsekuensi]" value="${consequence}">
      </div>
    `;
    
    document.getElementById('policies-list').insertAdjacentHTML('beforeend', cardHtml);
    temporaryPolicies.push({ jenis, ambang, desc, consequence });

    // Reset inputs
    document.getElementById('policy_ambang').value = '';
    document.getElementById('policy_desc').value = '';
    document.getElementById('policy_consequence').value = '';
  });

  // Tab 4: Save final policies & scales
  document.getElementById('form-save-policies').onsubmit = function(e) {
    e.preventDefault();
    const url = "{{ route('rps-penilaian.policies.save', ':id') }}".replace(':id', rpsId);
    const formData = new FormData(this);

    fetch(url, {
      method: 'POST',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert(data.message);
      }
    });
  };
});
</script>
@endsection
