@extends('layouts.app')

@section('content')
<main class="p-6">
  <!-- Header -->
  <div class="row mb-4">
    <div class="col-12">
      <h2 class="fw-bold mb-1">Formulir Biodata Pribadi</h2>
      <p class="text-muted mb-0">Mohon isi data di bawah ini dengan informasi yang sebenar-benarnya untuk proses seleksi mahasiswa baru.</p>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-triangle fs-4 me-2"></i>
      <strong>Terjadi kesalahan:</strong>
      <ul class="mb-0 mt-1">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <form action="{{ route('daftar-pmb.registration.store', $path?->code) }}" method="POST" id="registrationForm">
    @csrf

    <div class="row">
      <div class="col-lg-8">

        <!-- Section: Data Pribadi -->
        <div class="card card-lg mb-4">
          <div class="card-header bg-light py-3">
            <h5 class="fw-bold mb-0"><i class="ti ti-user me-2"></i>Data Pribadi</h5>
          </div>
          <div class="card-body p-4">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label fw-semibold text-uppercase" style="font-size: 0.8rem;">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama_lengkap" value="{{ old('nama_lengkap', auth()->user()->name) }}" placeholder="Masukkan nama sesuai ijazah" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold text-uppercase" style="font-size: 0.8rem;">Tempat Lahir <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Kota/Kabupaten" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold text-uppercase" style="font-size: 0.8rem;">Tanggal Lahir <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold text-uppercase" style="font-size: 0.8rem;">Jenis Kelamin <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 mt-1">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="laki">Laki-laki</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}>
                    <label class="form-check-label" for="perempuan">Perempuan</label>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold text-uppercase" style="font-size: 0.8rem;">Agama <span class="text-danger">*</span></label>
                <select class="form-select" name="agama" required>
                  <option value="" disabled {{ old('agama') ? '' : 'selected' }}>Pilih Agama</option>
                  <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                  <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                  <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                  <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                  <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                  <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                </select>
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold text-uppercase" style="font-size: 0.8rem;">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nik" value="{{ old('nik') }}" placeholder="16 Digit Nomor KTP" maxlength="16" pattern="[0-9]{16}" required>
                <small class="text-muted">Pastikan NIK sesuai dengan Kartu Keluarga atau KTP.</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Section: Kontak & Alamat -->
        <div class="card card-lg mb-4">
          <div class="card-header bg-light py-3">
            <h5 class="fw-bold mb-0"><i class="ti ti-mail me-2"></i>Kontak & Alamat</h5>
          </div>
          <div class="card-body p-4">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label fw-semibold text-uppercase" style="font-size: 0.8rem;">Alamat Lengkap <span class="text-danger">*</span></label>
                <textarea class="form-control" name="alamat" rows="3" placeholder="Nama Jalan, RT/RW, Desa/Kelurahan, Kecamatan" required>{{ old('alamat') }}</textarea>
              </div>
              <div class="col-md-3">
                <label class="form-label fw-semibold text-uppercase" style="font-size: 0.8rem;">Kode Pos <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="kode_pos" value="{{ old('kode_pos') }}" placeholder="5 Digit" maxlength="5" pattern="[0-9]{5}" required>
              </div>
              <div class="col-md-5">
                <label class="form-label fw-semibold text-uppercase" style="font-size: 0.8rem;">No. HP / WhatsApp <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" name="no_hp" value="{{ old('no_hp', '+62') }}" placeholder="+62 812xxxxxx" required>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold text-uppercase" style="font-size: 0.8rem;">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" name="email" value="{{ old('email', auth()->user()->email) }}" placeholder="contoh@email.com" required>
              </div>
            </div>
          </div>
        </div>

        <!-- Section: Pendidikan Terakhir -->
        <div class="card card-lg mb-4">
          <div class="card-header bg-light py-3">
            <h5 class="fw-bold mb-0"><i class="ti ti-school me-2"></i>Pendidikan Terakhir</h5>
          </div>
          <div class="card-body p-4">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label fw-semibold text-uppercase" style="font-size: 0.8rem;">Nama Sekolah / Instansi <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama_sekolah" value="{{ old('nama_sekolah') }}" placeholder="SMA / SMK / MA / Perguruan Tinggi Asal" required>
              </div>
              <div class="col-md-8">
                <label class="form-label fw-semibold text-uppercase" style="font-size: 0.8rem;">Jurusan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="jurusan" value="{{ old('jurusan') }}" placeholder="IPA / IPS / Nama Program Studi" required>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold text-uppercase" style="font-size: 0.8rem;">Tahun Lulus <span class="text-danger">*</span></label>
                <select class="form-select" name="tahun_lulus" required>
                  <option value="" disabled {{ old('tahun_lulus') ? '' : 'selected' }}>Pilih Tahun</option>
                  @for($year = date('Y'); $year >= date('Y') - 10; $year--)
                    <option value="{{ $year }}" {{ old('tahun_lulus') == $year ? 'selected' : '' }}>{{ $year }}</option>
                  @endfor
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-3 mb-4">
          <a href="{{ route('daftar-pmb.steps', $path?->code) }}" class="btn btn-outline-secondary px-4">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
          <button type="submit" class="btn btn-primary fw-semibold px-4">
            Simpan Data Pribadi <i class="ti ti-arrow-right ms-1"></i>
          </button>
        </div>

      </div>
      <!-- Help Section -->
      <div class="col-lg-4">
        <div class="sticky-sidebar">
          <!-- Card Bantuan -->
          <div class="card border-1 shadow-sm mb-3" style="background: linear-gradient(135deg, #e8f0fe 0%, #f0f4ff 100%);">
            <div class="card-body p-4">
              <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                  <i class="ti ti-headset text-white fs-5"></i>
                </div>
                <div>
                  <h6 class="fw-bold mb-0">Butuh Bantuan?</h6>
                  <small class="text-muted">Tim helpdesk kami siap membantu</small>
                </div>
              </div>
              <p class="text-muted mb-3" style="font-size: 0.85rem;">Jika mengalami kendala dalam proses pendaftaran, jangan ragu untuk menghubungi kami.</p>
              <a href="#" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-2">
                <i class="ti ti-brand-whatsapp"></i> Chat Admin WhatsApp
              </a>
            </div>
          </div>

          <!-- Card Tips -->
          <div class="card border-1 shadow-sm mb-3">
            <div class="card-body p-4">
              <h6 class="fw-bold mb-3"><i class="ti ti-lightbulb text-warning me-2"></i>Tips Mengisi Formulir</h6>
              <ul class="list-unstyled mb-0" style="font-size: 0.85rem;">
                <li class="d-flex align-items-start gap-2 mb-2">
                  <i class="ti ti-check text-success mt-1 flex-shrink-0"></i>
                  <span>Isi data sesuai dokumen resmi (KTP, Ijazah)</span>
                </li>
                <li class="d-flex align-items-start gap-2 mb-2">
                  <i class="ti ti-check text-success mt-1 flex-shrink-0"></i>
                  <span>NIK harus 16 digit tanpa spasi</span>
                </li>
                <li class="d-flex align-items-start gap-2 mb-2">
                  <i class="ti ti-check text-success mt-1 flex-shrink-0"></i>
                  <span>Gunakan email aktif untuk notifikasi</span>
                </li>
                <li class="d-flex align-items-start gap-2 mb-0">
                  <i class="ti ti-check text-success mt-1 flex-shrink-0"></i>
                  <span>Simpan draft jika belum selesai</span>
                </li>
              </ul>
            </div>
          </div>

          <!-- Card FAQ -->
          <div class="card border-1 shadow-sm">
            <div class="card-body p-4">
              <h6 class="fw-bold mb-3"><i class="ti ti-help-circle text-info me-2"></i>Pertanyaan Umum</h6>
              @include('components.accordion', [
                  'id' => 'faqAccordion',
                  'flush' => true,
                  'items' => [
                      [
                          'id' => 'faq1',
                          'title' => 'Bagaimana jika data salah?',
                          'content' => 'Anda dapat mengedit data sebelum mengirim. Setelah submit, hubungi admin untuk perubahan.',
                          'item_class' => 'border-0',
                          'body_class' => 'p-2 text-muted',
                      ],
                      [
                          'id' => 'faq3',
                          'title' => 'Berapa lama proses seleksi?',
                          'content' => 'Proses seleksi biasanya 1-2 minggu setelah batas pendaftaran ditutup.',
                          'item_class' => 'border-0',
                          'body_class' => 'p-2 text-muted',
                      ],
                  ],
              ])
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</main>

<style>
  .stepper-wrapper {
    background: #fff;
    border-radius: 12px;
    padding: 24px 32px;
    border: 1px solid #e9ecef;
  }

  .stepper-container {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    gap: 0;
  }

  .stepper-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    position: relative;
    z-index: 2;
  }

  .stepper-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
  }

  .stepper-label {
    font-size: 0.78rem;
    white-space: nowrap;
    text-align: center;
  }

  .stepper-line {
    width: 80px;
    height: 3px;
    background: #dee2e6;
    flex-shrink: 0;
    margin: 0 4px;
    position: relative;
    top: 20px;
  }

  .stepper-line.active-line {
    background: #198754;
  }

  .stepper-item.active .stepper-circle {
    background: #198754 !important;
    color: #fff !important;
  }

  .stepper-item.active .stepper-label {
    color: #198754 !important;
  }

  .stepper-item.current .stepper-circle {
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
  }

  .card-header {
    border-bottom: 1px solid #e9ecef;
  }

  @media (max-width: 576px) {
    .stepper-wrapper {
      padding: 16px 12px;
    }
    .stepper-line {
      width: 40px;
    }
    .stepper-circle {
      width: 32px;
      height: 32px;
      font-size: 0.8rem;
    }
    .stepper-label {
      font-size: 0.65rem;
    }
  }
</style>

<script>
  function saveDraft() {
    // Create a temporary form to save as draft
    const form = document.getElementById('registrationForm');
    const formData = new FormData(form);
    formData.append('is_draft', '1');

    fetch(form.action, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Draft berhasil disimpan!');
      }
    })
    .catch(() => {
      alert('Draft berhasil disimpan!');
    });
  }
</script>
@endsection
