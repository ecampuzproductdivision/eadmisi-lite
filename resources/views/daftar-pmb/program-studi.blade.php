@extends('layouts.app')

@section('content')
<main class="p-6">
  <!-- Header -->
  <div class="row mb-4">
    <div class="col-12">
      <h2 class="fw-bold mb-1">Pilihan Program Studi</h2>
      <p class="text-muted mb-0">Pilih program studi yang Anda minati. Anda dapat memilih hingga 2 program studi (pilihan 1 dan pilihan 2).</p>
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

  <form action="{{ route('daftar-pmb.program-studi.store', $path?->code) }}" method="POST">
    @csrf

    <div class="row">
      <div class="col-lg-8">

        <!-- Pilihan 1 -->
        <div class="card card-lg mb-4 border-primary">
          <div class="card-header bg-primary text-white py-3">
            <h5 class="fw-bold mb-0"><i class="ti ti-star me-2"></i>Pilihan 1 (Utama)</h5>
          </div>
          <div class="card-body p-4">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label fw-semibold text-uppercase" style="font-size: 0.8rem;">Program Studi Pilihan 1 <span class="text-danger">*</span></label>
                <select class="form-select form-select-lg" name="program_studi_1_id" required>
                  <option value="" disabled {{ old('program_studi_1_id', $registration?->program_studi_1_id) ? '' : 'selected' }}>-- Pilih Program Studi --</option>
                  @foreach($programStudis as $prodi)
                    <option value="{{ $prodi->id }}" {{ old('program_studi_1_id', $registration?->program_studi_1_id) == $prodi->id ? 'selected' : '' }}>
                      {{ $prodi->kode }} - {{ $prodi->nama }} ({{ $prodi->jenjang }})
                    </option>
                  @endforeach
                </select>
                <small class="text-muted">Pilih program studi utama yang Anda inginkan.</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Pilihan 2 -->
        <div class="card card-lg mb-4 border-secondary">
          <div class="card-header bg-secondary text-white py-3">
            <h5 class="fw-bold mb-0"><i class="ti ti-bookmark me-2"></i>Pilihan 2 (Cadangan)</h5>
          </div>
          <div class="card-body p-4">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label fw-semibold text-uppercase" style="font-size: 0.8rem;">Program Studi Pilihan 2 <span class="text-muted">(opsional)</span></label>
                <select class="form-select form-select-lg" name="program_studi_2_id">
                  <option value="" {{ old('program_studi_2_id', $registration?->program_studi_2_id) ? '' : 'selected' }}>-- Tidak ada pilihan cadangan --</option>
                  @foreach($programStudis as $prodi)
                    <option value="{{ $prodi->id }}" {{ old('program_studi_2_id', $registration?->program_studi_2_id) == $prodi->id ? 'selected' : '' }}>
                      {{ $prodi->kode }} - {{ $prodi->nama }} ({{ $prodi->jenjang }})
                    </option>
                  @endforeach
                </select>
                <small class="text-muted">Kosongkan jika tidak ada pilihan cadangan.</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Informasi -->
        <div class="card card-lg mb-4 border-warning bg-warning-subtle">
          <div class="card-body p-4">
            <div class="d-flex align-items-start gap-3">
              <i class="ti ti-info-circle fs-3 text-warning flex-shrink-0 mt-1"></i>
              <div>
                <h6 class="fw-bold mb-2">Informasi Penting</h6>
                <ul class="mb-0" style="font-size: 0.9rem;">
                  <li>Pilihan 1 adalah program studi utama yang Anda prioritaskan.</li>
                  <li>Pilihan 2 bersifat opsional sebagai cadangan jika tidak lolos di pilihan 1.</li>
                  <li>Pastikan Anda memilih program studi yang sesuai dengan minat dan kemampuan Anda.</li>
                  <li>Pilihan program studi dapat diubah selama proses pendaftaran belum disubmit.</li>
                </ul>
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
            Simpan & Lanjutkan <i class="ti ti-arrow-right ms-1"></i>
          </button>
        </div>

      </div>
      <!-- Sidebar -->
      <div class="col-lg-4">
        <div class="sticky-sidebar">
          <!-- Card Data Terisi -->
          <div class="card border-0 shadow-sm mb-3 bg-success-subtle">
            <div class="card-body p-4">
              <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-success d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                  <i class="ti ti-user-check text-white fs-5"></i>
                </div>
                <div>
                  <h6 class="fw-bold mb-0">Data Pribadi</h6>
                  <small class="text-muted">Telah diisi</small>
                </div>
              </div>
              <p class="mb-1" style="font-size: 0.85rem;"><strong>{{ $registration->nama_lengkap }}</strong></p>
              <p class="mb-0 text-muted" style="font-size: 0.85rem;">{{ $registration->email }}</p>
            </div>
          </div>

          <!-- Card Bantuan -->
          <div class="card border-0 shadow-sm mb-3" style="background: linear-gradient(135deg, #e8f0fe 0%, #f0f4ff 100%);">
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
              <p class="text-muted mb-3" style="font-size: 0.85rem;">Jika bingung memilih program studi, konsultasikan dengan admin kami.</p>
              <a href="#" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                <i class="ti ti-brand-whatsapp"></i> Chat Admin WhatsApp
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</main>

<style>
  .card-header {
    border-bottom: 1px solid rgba(0,0,0,0.08);
  }
  @media (max-width: 576px) {
    .sticky-sidebar {
      margin-top: 1rem;
    }
  }
</style>
@endsection