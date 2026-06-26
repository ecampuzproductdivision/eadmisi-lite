@extends('layouts.app')

@section('content')
<main class="p-6">
  <!-- Header -->
  <div class="row mb-5">
    <div class="col-12">
      <h2 class="fw-bold mb-2">Alur Pendaftaran PMB</h2>
      <p class="text-muted mb-0">Ikuti setiap tahapan secara berurutan. Tahap berikutnya akan terbuka otomatis setelah Anda menyelesaikan tahap sebelumnya.</p>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="row justify-content-center">
    <div class="col-lg-12">
      <!-- Step Timeline -->
      <div class="steps-timeline">
        
        <!-- Step 1: Pendaftaran PMB (Biodata) -->
        <div class="step-item {{ $currentStep > 1 ? 'completed' : ($currentStep == 1 ? 'active' : 'locked') }}" id="step1">
          <div class="step-indicator">
            <div class="step-circle {{ $currentStep > 1 ? 'bg-success' : ($currentStep == 1 ? 'bg-primary' : 'bg-light') }}">
              @if($currentStep > 1)
                <i class="ti ti-check text-white"></i>
              @elseif($currentStep == 1)
                <i class="ti ti-user-edit text-white"></i>
              @else
                <i class="ti ti-lock text-muted"></i>
              @endif
            </div>
            <div class="step-line"></div>
          </div>
          <div class="step-content">
            <div class="step-header">
              <span class="badge {{ $currentStep > 1 ? 'bg-success-subtle text-success' : ($currentStep == 1 ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary') }} px-3 py-2 fw-semibold">Langkah 1</span>
              @if($currentStep > 1)
                <span class="badge bg-success-subtle text-success px-3 py-2"><i class="ti ti-check me-1"></i> Selesai</span>
              @elseif($currentStep == 1)
                <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="ti ti-loader me-1"></i> Sedang Aktif</span>
              @else
                <span class="badge bg-secondary-subtle text-secondary px-3 py-2"><i class="ti ti-lock me-1"></i> Terkunci</span>
              @endif
            </div>
            <h5 class="fw-bold mt-3">Data Pribadi</h5>
            <p class="text-muted mb-3">Lengkapi formulir pendaftaran dengan data pribadi, informasi kontak, dan pendidikan terakhir.</p>
            
            @if($currentStep == 1)
              @if($registration && $registration->nama_lengkap)
                <div class="d-flex flex-wrap gap-2">
                  <a href="{{ route('daftar-pmb.registration.form', $path?->code) }}" class="btn btn-outline-primary">
                    <i class="ti ti-edit"></i> Edit Data Pribadi
                  </a>
                  <a href="{{ route('daftar-pmb.program-studi.form', $path?->code) }}" class="btn btn-primary">
                    Lanjut Pilih Program Studi <i class="ti ti-arrow-right"></i>
                  </a>
                </div>
              @else
                <a href="{{ route('daftar-pmb.registration.form', $path?->code) }}" class="btn btn-primary">
                  <i class="ti ti-user-edit"></i> Lengkapi Data Pribadi <i class="ti ti-arrow-right"></i>
                </a>
              @endif
            @elseif($currentStep > 1)
              <div class="step-status text-success">
                <i class="ti ti-circle-check me-1"></i> Tahap ini telah diselesaikan
              </div>
            @else
              <div class="step-status text-muted">
                <i class="ti ti-lock me-1"></i> Selesaikan tahap sebelumnya untuk membuka
              </div>
            @endif
          </div>
        </div>

        <!-- Step 2: Pilih Program Studi -->
        <div class="step-item {{ $currentStep > 2 ? 'completed' : ($currentStep == 2 ? 'active' : ($currentStep == 1 && $registration ? 'locked' : 'locked')) }}" id="step2">
          <div class="step-indicator">
            <div class="step-circle {{ $currentStep > 2 ? 'bg-success' : ($currentStep == 2 ? 'bg-primary' : 'bg-light') }}">
              @if($currentStep > 2)
                <i class="ti ti-check text-white"></i>
              @elseif($currentStep == 2)
                <i class="ti ti-selector text-white"></i>
              @else
                <i class="ti ti-lock text-muted"></i>
              @endif
            </div>
            <div class="step-line"></div>
          </div>
          <div class="step-content">
            <div class="step-header">
              <span class="badge {{ $currentStep > 2 ? 'bg-success-subtle text-success' : ($currentStep == 2 ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary') }} px-3 py-2 fw-semibold">Langkah 2</span>
              @if($currentStep > 2)
                <span class="badge bg-success-subtle text-success px-3 py-2"><i class="ti ti-check me-1"></i> Selesai</span>
              @elseif($currentStep == 2)
                <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="ti ti-loader me-1"></i> Sedang Aktif</span>
              @else
                <span class="badge bg-secondary-subtle text-secondary px-3 py-2"><i class="ti ti-lock me-1"></i> Terkunci</span>
              @endif
            </div>
            <h5 class="fw-bold mt-3">Pilih Program Studi</h5>
            <p class="text-muted mb-3">Pilih program studi yang Anda minati. Anda dapat memilih pilihan utama dan pilihan cadangan.</p>
            
            @if($currentStep == 2)
              <a href="{{ route('daftar-pmb.program-studi.form', $path?->code) }}" class="btn btn-primary">
                <i class="ti ti-selector"></i> Pilih Program Studi <i class="ti ti-arrow-right"></i>
              </a>
            @elseif($currentStep > 2)
              <div class="step-status text-success">
                <i class="ti ti-circle-check me-1"></i> Tahap ini telah diselesaikan
              </div>
            @else
              <div class="step-status text-muted">
                <i class="ti ti-lock me-1"></i> Selesaikan tahap sebelumnya untuk membuka
              </div>
            @endif
          </div>
        </div>

        <!-- Step 3: Unggah Persyaratan -->
        <div class="step-item {{ $currentStep > 3 ? 'completed' : ($currentStep == 3 ? 'active' : 'locked') }}" id="step3">
          <div class="step-indicator">
            <div class="step-circle {{ $currentStep > 3 ? 'bg-success' : ($currentStep == 3 ? 'bg-primary' : 'bg-light') }}">
              @if($currentStep > 3)
                <i class="ti ti-check text-white"></i>
              @elseif($currentStep == 3)
                <i class="ti ti-upload text-white"></i>
              @else
                <i class="ti ti-lock text-muted"></i>
              @endif
            </div>
            <div class="step-line"></div>
          </div>
          <div class="step-content">
            <div class="step-header">
              <span class="badge {{ $currentStep > 3 ? 'bg-success-subtle text-success' : ($currentStep == 3 ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary') }} px-3 py-2 fw-semibold">Langkah 3</span>
              @if($currentStep > 3)
                <span class="badge bg-success-subtle text-success px-3 py-2"><i class="ti ti-check me-1"></i> Selesai</span>
              @elseif($currentStep == 3)
                <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="ti ti-loader me-1"></i> Sedang Aktif</span>
              @else
                <span class="badge bg-secondary-subtle text-secondary px-3 py-2"><i class="ti ti-lock me-1"></i> Terkunci</span>
              @endif
            </div>
            <h5 class="fw-bold mt-3">Unggah Persyaratan</h5>
            <p class="text-muted mb-3">Upload dokumen yang diperlukan: foto formal, ijazah/SKHUN, kartu keluarga, dan akta kelahiran.</p>
            
            @if($currentStep == 3)
              <a href="{{ route('daftar-pmb.document.upload', $path?->code) }}" class="btn btn-primary">
                <i class="ti ti-upload"></i> Unggah Dokumen <i class="ti ti-arrow-right"></i>
              </a>
            @elseif($currentStep > 3)
              <div class="step-status text-success">
                <i class="ti ti-circle-check me-1"></i> Tahap ini telah diselesaikan
              </div>
            @else
              <div class="step-status text-muted">
                <i class="ti ti-lock me-1"></i> Selesaikan tahap sebelumnya untuk membuka
              </div>
            @endif
          </div>
        </div>

        <!-- Step 4: Selesai -->
        <div class="step-item {{ $currentStep >= 4 ? 'completed' : 'locked' }}" id="step4">
          <div class="step-indicator">
            <div class="step-circle {{ $currentStep >= 4 ? 'bg-success' : 'bg-light' }}">
              @if($currentStep >= 4)
                <i class="ti ti-check text-white"></i>
              @else
                <i class="ti ti-lock text-muted"></i>
              @endif
            </div>
          </div>
          <div class="step-content">
            <div class="step-header">
              <span class="badge {{ $currentStep >= 4 ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} px-3 py-2 fw-semibold">Langkah 4</span>
              @if($currentStep >= 4)
                <span class="badge bg-success-subtle text-success px-3 py-2"><i class="ti ti-check me-1"></i> Selesai</span>
              @else
                <span class="badge bg-secondary-subtle text-secondary px-3 py-2"><i class="ti ti-lock me-1"></i> Terkunci</span>
              @endif
            </div>
            <h5 class="fw-bold mt-3">Selesai</h5>
            <p class="text-muted mb-3">Pendaftaran selesai. Silakan lanjut ke menu <strong>Tagihan</strong> untuk informasi pembayaran, lalu ikuti <strong>Tes Online</strong>.</p>
            
            @if($currentStep >= 4)
              <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('daftar-pmb.review', $path?->code) }}" class="btn btn-success">
                  <i class="ti ti-eye"></i> Lihat Ringkasan
                </a>
                <a href="{{ route('tagihan.index') }}" class="btn btn-warning">
                  <i class="ti ti-receipt"></i> Lihat Tagihan
                </a>
              </div>
            @else
              <div class="step-status text-muted">
                <i class="ti ti-lock me-1"></i> Selesaikan tahap sebelumnya untuk membuka
              </div>
            @endif
          </div>
        </div>

      </div>
    </div>
  </div>
</main>

<style>
  .steps-timeline {
    position: relative;
    padding-left: 0;
  }

  .step-item {
    display: flex;
    gap: 20px;
    margin-bottom: 0;
    position: relative;
  }

  .step-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-shrink: 0;
    width: 50px;
  }

  .step-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
    z-index: 2;
  }

  .step-line {
    width: 3px;
    flex-grow: 1;
    background: #dee2e6;
    min-height: 20px;
  }

  .step-item.completed .step-line {
    background: #198754;
  }

  .step-item.active .step-line {
    background: linear-gradient(180deg, #0d6efd 0%, #dee2e6 100%);
  }

  .step-content {
    flex-grow: 1;
    background: #fff;
    border: 2px solid #e9ecef;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
  }

  .step-item.completed .step-content {
    border-color: #198754;
    background: #f8fdf9;
  }

  .step-item.active .step-content {
    border-color: #0d6efd;
    background: #f8faff;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.1);
  }

  .step-item.locked .step-content {
    opacity: 0.7;
  }

  .step-header {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }

  .step-status {
    font-weight: 600;
    font-size: 0.9rem;
  }

  @media (max-width: 576px) {
    .step-indicator {
      width: 40px;
    }
    .step-circle {
      width: 40px;
      height: 40px;
      font-size: 1rem;
    }
    .step-content {
      padding: 16px;
    }
  }
</style>
@endsection
