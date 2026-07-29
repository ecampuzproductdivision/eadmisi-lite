@extends('layouts.app')

@section('content')
<main class="p-2">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('registration-paths.index') }}">Registration Paths</a></li>
            <li class="breadcrumb-item active">Edit Jalur Pendaftaran</li>
        </ol>
    </nav>
    <hr>

    {{-- Title with Back Button --}}
    <div class="d-flex align-items-top gap-3 my-5">
        <a href="{{ route('registration-paths.index') }}" class="btn btn-light d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 36px; height: 36px;" title="Back to List">
            <i class="ti ti-arrow-left fs-5"></i>
        </a>
        <div>
            <h1 class="mb-1 fw-bold">Edit Jalur Pendaftaran</h1>
            <p class="text-muted mb-0">Perbarui informasi jalur pendaftaran: {{ $registrationPath->name }}.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle fs-4 me-2"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Card Form --}}
    <div class="card border-1 shadow-sm px-4 py-4">
        <div class="col-xl-12 col-12">
            <form action="{{ route('registration-paths.update', $registrationPath) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Section: Informasi Jalur --}}
                    <div class="col-12">
                        <h6 class="text-secondary fw-bold pb-2 mb-0" style="border-bottom: 1px dashed #dee2e6;">Informasi Jalur</h6>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="kategori_jalur" class="form-label fw-semibold">Kategori Jalur</label>
                        <select name="kategori_jalur" id="kategori_jalur" class="form-select @error('kategori_jalur') is-invalid @enderror">
                            <option value="">Pilih kategori...</option>
                            @php $selectedKategori = old('kategori_jalur', $registrationPath->kategori?->nama ?? ''); @endphp
                            @foreach($kategoriJalurList as $kat)
                                <option value="{{ $kat }}" {{ $selectedKategori == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                        @error('kategori_jalur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="jenis_pendaftaran" class="form-label fw-semibold">Jenis Pendaftaran</label>
                        <select name="jenis_pendaftaran" id="jenis_pendaftaran" class="form-select @error('jenis_pendaftaran') is-invalid @enderror">
                            <option value="">Pilih jenis pendaftaran...</option>
                            @php $selectedJenis = old('jenis_pendaftaran', $registrationPath->jenis_pendaftaran ?? ''); @endphp
                            @foreach($jenisPendaftaranList as $jenis)
                                <option value="{{ $jenis }}" {{ $selectedJenis == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                            @endforeach
                        </select>
                        @error('jenis_pendaftaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="code" class="form-label fw-semibold">Kode Jalur <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" placeholder="Contoh: SNBP, SNBT, MANDIRI" value="{{ old('code', $registrationPath->code) }}" required maxlength="50">
                        <div class="form-text">Kode unik untuk jalur pendaftaran (huruf kapital, tanpa spasi).</div>
                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="name" class="form-label fw-semibold">Nama Jalur <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Seleksi Nasional Berdasarkan Prestasi" value="{{ old('name', $registrationPath->name) }}" required maxlength="200">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 col-12"></div>

                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Deskripsi jalur pendaftaran...">{{ old('description', $registrationPath->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Section: Periode & Kuota --}}
                    <div class="col-12">
                        <h6 class="text-secondary fw-bold pb-2 mb-0 mt-2" style="border-bottom: 1px dashed #dee2e6;">Periode & Kuota</h6>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="periode_id" class="form-label fw-semibold">Periode Akademik <span class="text-danger">*</span></label>
                        <select name="periode_id" id="periode_id" class="form-select @error('periode_id') is-invalid @enderror" required>
                            <option value="">Pilih periode...</option>
                            @foreach($periodes as $periode)
                                <option value="{{ $periode->id }}" {{ old('periode_id', $registrationPath->periode_id ?? $activePeriodeId) == $periode->id ? 'selected' : '' }}>
                                    {{ $periode->label }} {!! $periode->status_aktif ? '(Aktif)' : '' !!}
                                </option>
                            @endforeach
                        </select>
                        @error('periode_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="registration_start" class="form-label fw-semibold">Tanggal Mulai</label>
                        <input type="date" name="registration_start" id="registration_start" class="form-control @error('registration_start') is-invalid @enderror" value="{{ old('registration_start', $registrationPath->registration_start?->format('Y-m-d')) }}">
                        @error('registration_start') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="registration_end" class="form-label fw-semibold">Tanggal Akhir</label>
                        <input type="date" name="registration_end" id="registration_end" class="form-control @error('registration_end') is-invalid @enderror" value="{{ old('registration_end', $registrationPath->registration_end?->format('Y-m-d')) }}">
                        @error('registration_end') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="quota" class="form-label fw-semibold">Kuota</label>
                        <input type="number" name="quota" id="quota" class="form-control @error('quota') is-invalid @enderror" placeholder="Kosongkan jika tidak terbatas" value="{{ old('quota', $registrationPath->quota) }}" min="0">
                        @error('quota') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 col-12"></div>

                    {{-- Program Studi Ditawarkan (Tag Input) --}}
                    <div class="col-12">
                        <label for="tag-input-prodi" class="form-label fw-semibold">Program Studi Ditawarkan <span class="text-danger">*</span></label>
                        <div class="tag-input-wrapper position-relative @error('program_studi_ids') is-invalid @enderror" id="tag-input-wrapper">
                            <div class="tag-input-container d-flex flex-wrap align-items-center gap-1 p-1" id="tag-input-container">
                                <div class="tag-input-tags d-inline-flex flex-wrap align-items-center gap-1" id="tag-input-tags"></div>
                                <input type="text" class="tag-input-field flex-grow-1" id="tag-input-field" placeholder="Ketik untuk mencari program studi..." autocomplete="off">
                            </div>
                            <div class="tag-dropdown" id="tag-dropdown" style="display:none;"></div>
                        </div>
                        <select name="program_studi_ids[]" id="program_studi_ids" multiple required style="display:none;">
                            @foreach($programStudis as $prodi)
                                <option value="{{ $prodi->id }}"
                                    {{ (old('program_studi_ids') && in_array($prodi->id, old('program_studi_ids'))) || (old('program_studi_ids') === null && $registrationPath->programStudis->contains($prodi->id)) ? 'selected' : '' }}>
                                    {{ $prodi->nama_prodi ?: $prodi->nama }} ({{ $prodi->jenjang_akademik ?? $prodi->jenjang }})
                                </option>
                            @endforeach
                        </select>
                        @error('program_studi_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @error('program_studi_ids.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Ketik nama program studi lalu klik atau tekan Enter untuk menambahkan.</div>
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="jumlah_pilihan_prodi" class="form-label fw-semibold">Jumlah Pilihan Prodi <span class="text-danger">*</span></label>
                        <select name="jumlah_pilihan_prodi" id="jumlah_pilihan_prodi" class="form-select @error('jumlah_pilihan_prodi') is-invalid @enderror" required>
                            <option value="1" {{ old('jumlah_pilihan_prodi', $registrationPath->jumlah_pilihan_prodi ?? 1) == 1 ? 'selected' : '' }}>1 Pilihan</option>
                            <option value="2" {{ old('jumlah_pilihan_prodi', $registrationPath->jumlah_pilihan_prodi) == 2 ? 'selected' : '' }}>2 Pilihan</option>
                            <option value="3" {{ old('jumlah_pilihan_prodi', $registrationPath->jumlah_pilihan_prodi) == 3 ? 'selected' : '' }}>3 Pilihan</option>
                        </select>
                        @error('jumlah_pilihan_prodi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Jumlah maksimal pilihan program studi.</div>
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="fee" class="form-label fw-semibold">Biaya Pendaftaran (Rp)</label>
                        <input type="number" name="fee" id="fee" class="form-control @error('fee') is-invalid @enderror" placeholder="0" value="{{ old('fee', $registrationPath->fee) }}" min="0">
                        @error('fee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="color" class="form-label fw-semibold">Warna Badge</label>
                        <select name="color" id="color" class="form-select @error('color') is-invalid @enderror">
                            <option value="">Pilih warna...</option>
                            <option value="primary" {{ old('color', $registrationPath->color) == 'primary' ? 'selected' : '' }}>Biru (Primary)</option>
                            <option value="success" {{ old('color', $registrationPath->color) == 'success' ? 'selected' : '' }}>Hijau (Success)</option>
                            <option value="warning" {{ old('color', $registrationPath->color) == 'warning' ? 'selected' : '' }}>Kuning (Warning)</option>
                            <option value="danger" {{ old('color', $registrationPath->color) == 'danger' ? 'selected' : '' }}>Merah (Danger)</option>
                            <option value="info" {{ old('color', $registrationPath->color) == 'info' ? 'selected' : '' }}>Biru Muda (Info)</option>
                            <option value="secondary" {{ old('color', $registrationPath->color) == 'secondary' ? 'selected' : '' }}>Abu-abu (Secondary)</option>
                            <option value="dark" {{ old('color', $registrationPath->color) == 'dark' ? 'selected' : '' }}>Hitam (Dark)</option>
                        </select>
                        @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $registrationPath->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label fw-semibold">Aktif</label>
                        </div>
                    </div>

                    {{-- Form Template Selection --}}
                    <div class="col-12 mt-3">
                        <label for="form_pendaftaran_id" class="form-label fw-semibold">Pilih Template Formulir Pendaftaran <span class="text-danger">*</span></label>
                        <select name="form_pendaftaran_id" id="form_pendaftaran_id" class="form-select @error('form_pendaftaran_id') is-invalid @enderror">
                            <option value="">Pilih template formulir...</option>
                            @foreach($forms as $form)
                                <option value="{{ $form->id }}"
                                    {{ (old('form_pendaftaran_id') !== null && old('form_pendaftaran_id') == $form->id) || (old('form_pendaftaran_id') === null && $registrationPath->form_pendaftaran_id == $form->id) ? 'selected' : '' }}>
                                    {{ $form->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('form_pendaftaran_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Pilih template form pendaftaran yang akan digunakan.</div>
                    </div>

                    {{-- Feature Configuration Toggles --}}
                    <div class="col-12">
                        <div class="form-check form-switch mt-2">
                            <input type="hidden" name="gunakan_berkas" value="0">
                            <input type="checkbox" name="gunakan_berkas" id="gunakan_berkas" class="form-check-input" value="1" {{ old('gunakan_berkas', $registrationPath->gunakan_berkas) ? 'checked' : '' }}>
                            <label for="gunakan_berkas" class="form-check-label fw-semibold">Gunakan Unggah Berkas</label>
                        </div>
                    </div>

                    <div class="col-12" id="template-berkas-section" style="{{ old('gunakan_berkas', $registrationPath->gunakan_berkas) ? '' : 'display:none;' }}">
                        <label for="template_berkas_id" class="form-label fw-semibold">Pilih Template Syarat Berkas <span class="text-danger">*</span></label>
                        <select name="template_berkas_id" id="template_berkas_id" class="form-select @error('template_berkas_id') is-invalid @enderror">
                            <option value="">Pilih template...</option>
                            @foreach($templateBerkas as $tb)
                                <option value="{{ $tb->id }}"
                                    {{ (old('template_berkas_id') !== null && old('template_berkas_id') == $tb->id) || (old('template_berkas_id') === null && $registrationPath->template_berkas_id == $tb->id) ? 'selected' : '' }}>
                                    {{ $tb->nama_template }} ({{ $tb->total_dokumen }} dokumen)
                                </option>
                            @endforeach
                        </select>
                        @error('template_berkas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Pilih template syarat berkas untuk menentukan dokumen yang harus diunggah.</div>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch mt-2">
                            <input type="hidden" name="gunakan_ujian" value="0">
                            <input type="checkbox" name="gunakan_ujian" id="gunakan_ujian" class="form-check-input" value="1" {{ old('gunakan_ujian', $registrationPath->gunakan_ujian) ? 'checked' : '' }}>
                            <label for="gunakan_ujian" class="form-check-label fw-semibold">Gunakan Ujian Online</label>
                        </div>
                    </div>

                    <div class="col-12" id="paket-soal-section" style="{{ old('gunakan_ujian', $registrationPath->gunakan_ujian) ? '' : 'display:none;' }}">
                        <div class="mb-3">
                            <label for="paket_soal_id" class="form-label fw-semibold">Pilih Paket Soal Ujian <span class="text-danger">*</span></label>
                            <select name="paket_soal_id" id="paket_soal_id" class="form-select @error('paket_soal_id') is-invalid @enderror">
                                <option value="">Pilih paket soal...</option>
                                @foreach($paketSoals as $paket)
                                    <option value="{{ $paket->id }}"
                                        {{ (old('paket_soal_id') !== null && old('paket_soal_id') == $paket->id) || (old('paket_soal_id') === null && $registrationPath->paket_soal_id == $paket->id) ? 'selected' : '' }}>
                                        {{ $paket->nama_paket }} ({{ $paket->total_soal }} soal, skor: {{ $paket->total_skor }})
                                    </option>
                                @endforeach
                            </select>
                            @error('paket_soal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Pilih paket soal ujian yang akan digunakan.</div>
                        </div>

                        <div class="mb-3">
                            <label for="nilai_ambang_batas" class="form-label fw-semibold">Nilai Ambang Batas Kelulusan <span class="text-danger">*</span></label>
                            <input type="number" name="nilai_ambang_batas" id="nilai_ambang_batas" class="form-control @error('nilai_ambang_batas') is-invalid @enderror" value="{{ old('nilai_ambang_batas', $registrationPath->nilai_ambang_batas) }}" placeholder="Contoh: 80" min="0" max="100">
                            @error('nilai_ambang_batas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Masukkan nilai ambang batas kelulusan untuk jalur ini (0 - 100).</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch mt-2">
                            <input type="hidden" name="gunakan_wawancara" value="0">
                            <input type="checkbox" name="gunakan_wawancara" id="gunakan_wawancara" class="form-check-input" value="1" {{ old('gunakan_wawancara', $registrationPath->gunakan_wawancara) ? 'checked' : '' }}>
                            <label for="gunakan_wawancara" class="form-check-label fw-semibold">Gunakan Tahapan Wawancara</label>
                        </div>
                    </div>

                    @include('registration-paths.partials.komponen_biaya_repeater')

                    <div class="col-12">
                        <label for="metode_pengumuman" class="form-label fw-semibold">Metode Pengumuman Hasil Ujian <span class="text-danger">*</span></label>
                        <select name="metode_pengumuman" id="metode_pengumuman" class="form-select @error('metode_pengumuman') is-invalid @enderror">
                            <option value="langsung" {{ old('metode_pengumuman', $registrationPath->metode_pengumuman) == 'langsung' ? 'selected' : '' }}>Langsung (One Day Service)</option>
                            <option value="ditahan" {{ old('metode_pengumuman', $registrationPath->metode_pengumuman) == 'ditahan' ? 'selected' : '' }}>Ditahan (Menunggu Verifikasi/Wawancara)</option>
                            <option value="penilaian_manual" {{ old('metode_pengumuman', $registrationPath->metode_pengumuman) == 'penilaian_manual' ? 'selected' : '' }}>Penilaian Manual / Verifikasi Langsung</option>
                        </select>
                        <div class="form-text">Pilih apakah hasil ujian diumumkan langsung atau ditahan.</div>
                        @error('metode_pengumuman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>



                    {{-- Submit --}}
                    <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('registration-paths.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="ti ti-device-floppy me-1"></i> Perbarui
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

@push('scripts')
<script>
// Custom Multi-Select Tag Input for Program Studi
document.addEventListener('DOMContentLoaded', function() {
  const selectEl = document.getElementById('program_studi_ids');
  const tagsEl = document.getElementById('tag-input-tags');
  const fieldEl = document.getElementById('tag-input-field');
  const dropdownEl = document.getElementById('tag-dropdown');
  const wrapperEl = document.getElementById('tag-input-wrapper');

  const options = [];
  selectEl.querySelectorAll('option').forEach(opt => {
    if (opt.value) {
      options.push({ value: opt.value, text: opt.textContent.trim() });
    }
  });

  function initTags() {
    selectEl.querySelectorAll('option').forEach(opt => {
      if (opt.selected && opt.value) {
        addTag(opt.value, opt.textContent.trim(), false);
      }
    });
  }

  function addTag(value, text, sync) {
    if (tagsEl.querySelector(`[data-value="${value}"]`)) return;
    const tag = document.createElement('span');
    tag.className = 'tag-item';
    tag.dataset.value = value;
    tag.innerHTML = `${text} <span class="tag-remove" data-value="${value}">&times;</span>`;
    tag.querySelector('.tag-remove').addEventListener('click', function(e) {
      e.stopPropagation();
      removeTag(value);
    });
    tagsEl.appendChild(tag);
    syncSelect();
    fieldEl.focus();
  }

  function removeTag(value) {
    const tag = tagsEl.querySelector(`[data-value="${value}"]`);
    if (tag) tag.remove();
    syncSelect();
    fieldEl.focus();
  }

  function syncSelect() {
    const selectedValues = [];
    tagsEl.querySelectorAll('.tag-item').forEach(tag => {
      selectedValues.push(tag.dataset.value);
    });
    selectEl.querySelectorAll('option').forEach(opt => {
      opt.selected = selectedValues.includes(opt.value);
    });
    selectEl.dispatchEvent(new Event('change', { bubbles: true }));
  }

  function showDropdown(filter) {
    const normalized = filter.toLowerCase().trim();
    let filtered = options;
    if (normalized) {
      filtered = options.filter(o => o.text.toLowerCase().includes(normalized));
    }
    const selectedValues = [];
    tagsEl.querySelectorAll('.tag-item').forEach(tag => {
      selectedValues.push(tag.dataset.value);
    });
    filtered = filtered.filter(o => !selectedValues.includes(o.value));

    if (filtered.length === 0) {
      dropdownEl.style.display = 'none';
      return;
    }

    dropdownEl.innerHTML = '';
    filtered.forEach(o => {
      const item = document.createElement('div');
      item.className = 'tag-dropdown-item';
      item.textContent = o.text;
      item.dataset.value = o.value;
      item.addEventListener('click', function() {
        addTag(this.dataset.value, this.textContent, false);
        fieldEl.value = '';
        dropdownEl.style.display = 'none';
      });
      dropdownEl.appendChild(item);
    });
    dropdownEl.style.display = 'block';
  }

  function hideDropdown() {
    dropdownEl.style.display = 'none';
  }

  fieldEl.addEventListener('input', function() { showDropdown(this.value); });
  fieldEl.addEventListener('focus', function() { showDropdown(this.value); });
  fieldEl.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      const visibleItems = dropdownEl.querySelectorAll('.tag-dropdown-item');
      if (visibleItems.length > 0) {
        const first = visibleItems[0];
        addTag(first.dataset.value, first.textContent, false);
        this.value = '';
        hideDropdown();
      }
    } else if (e.key === 'Backspace' && this.value === '') {
      const lastTag = tagsEl.querySelector('.tag-item:last-child');
      if (lastTag) removeTag(lastTag.dataset.value);
    } else if (e.key === 'Escape') {
      hideDropdown();
    }
  });

  document.addEventListener('click', function(e) {
    if (!wrapperEl.contains(e.target)) hideDropdown();
  });

  initTags();
});

// Toggle Gunakan Ujian Online
document.addEventListener('DOMContentLoaded', function() {
  const toggle = document.getElementById('gunakan_ujian');
  const section = document.getElementById('paket-soal-section');
  const paketSoalSelect = document.getElementById('paket_soal_id');
  const thresholdInput = document.getElementById('nilai_ambang_batas');
  
  function togglePaketSection() {
    if (toggle.checked) {
      section.style.display = 'block';
      paketSoalSelect.setAttribute('required', 'required');
      thresholdInput.setAttribute('required', 'required');
    } else {
      section.style.display = 'none';
      paketSoalSelect.removeAttribute('required');
      thresholdInput.removeAttribute('required');
      paketSoalSelect.value = '';
      thresholdInput.value = '';
    }
  }
  
  toggle.addEventListener('change', togglePaketSection);
  togglePaketSection();
});

// Toggle Gunakan Unggah Berkas
document.addEventListener('DOMContentLoaded', function() {
  const toggle = document.getElementById('gunakan_berkas');
  const section = document.getElementById('template-berkas-section');
  const templateSelect = document.getElementById('template_berkas_id');
  
  function toggleBerkasSection() {
    if (toggle.checked) {
      section.style.display = 'block';
      templateSelect.setAttribute('required', 'required');
    } else {
      section.style.display = 'none';
      templateSelect.removeAttribute('required');
      templateSelect.value = '';
    }
  }
  
  toggle.addEventListener('change', toggleBerkasSection);
  toggleBerkasSection();
});

// Dynamic disabler for "Metode Pengumuman Hasil Ujian" based on "Gunakan Ujian Online" & "Gunakan Tahapan Wawancara"
$(document).ready(function() {
  const $ujian = $('#gunakan_ujian');
  const $wawancara = $('#gunakan_wawancara');
  const $metode = $('#metode_pengumuman');

  function updateMetodePengumumanOptions() {
    const ujianOn = $ujian.is(':checked');
    const wawancaraOn = $wawancara.is(':checked');
    const currentValue = $metode.val();

    // Enable all options first
    $metode.find('option').prop('disabled', false);

    if (ujianOn && !wawancaraOn) {
      // KONDISI 1 (Ujian Online: ON, Wawancara: OFF)
      // Aktifkan: Option A (langsung) & Option C (penilaian_manual)
      // Disable & Uncheck: Option B (ditahan)
      $metode.find('option[value="ditahan"]').prop('disabled', true);
      if (currentValue === 'ditahan') {
        $metode.val('langsung');
      }
    } else if (!ujianOn && wawancaraOn) {
      // KONDISI 2 (Ujian Online: OFF, Wawancara: ON)
      // Aktifkan: Option B (ditahan) & Option C (penilaian_manual)
      // Disable & Uncheck: Option A (langsung)
      $metode.find('option[value="langsung"]').prop('disabled', true);
      if (currentValue === 'langsung') {
        $metode.val('ditahan');
      }
    } else if (ujianOn && wawancaraOn) {
      // KONDISI 3 (Ujian Online: ON, Wawancara: ON)
      // Aktifkan: Option B (ditahan) saja. Disable: Option A (langsung) & Option C (penilaian_manual)
      // Otomatis set value ke Option B
      $metode.find('option[value="langsung"]').prop('disabled', true);
      $metode.find('option[value="penilaian_manual"]').prop('disabled', true);
      $metode.val('ditahan');
    } else {
      // KONDISI 4 (Ujian Online: OFF, Wawancara: OFF)
      // Aktifkan: Option C (penilaian_manual) saja. Disable: Option A (langsung) & Option B (ditahan)
      // Otomatis set value ke Option C
      $metode.find('option[value="langsung"]').prop('disabled', true);
      $metode.find('option[value="ditahan"]').prop('disabled', true);
      $metode.val('penilaian_manual');
    }

    // Trigger change to refresh Select2/UI
    $metode.trigger('change');
  }

  // Bind change listeners to both switches
  $ujian.on('change', updateMetodePengumumanOptions);
  $wawancara.on('change', updateMetodePengumumanOptions);

  // Run on initial page load
  updateMetodePengumumanOptions();
});
</script>
@endpush
@endsection