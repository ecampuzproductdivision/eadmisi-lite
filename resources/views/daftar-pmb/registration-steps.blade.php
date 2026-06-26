@extends('layouts.app')

@section('content')
@php
    $path = $path ?? null;
    $registration = $registration ?? null;
    $currentStep = $currentStep ?? 1;
    $hasExam = $path && $path->is_ujian_online;
    $totalSteps = $hasExam ? 5 : 4;
    $berkasList = $path && $path->templateBerkas && $path->templateBerkas->syaratDokumens
        ? $path->templateBerkas->syaratDokumens : collect();
@endphp

<main class="p-6">
  <!-- Header -->
  <div class="row mb-5">
    <div class="col-12">
      <h2 class="fw-bold mb-2">Alur Pendaftaran PMB</h2>
      <p class="text-muted mb-0">Ikuti setiap tahapan secara berurutan. Tahap berikutnya akan terbuka otomatis setelah Anda menyelesaikan tahap sebelumnya.</p>
      @if($path)
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mt-2">
          <i class="ti ti-road me-1"></i> {{ $path->name }}
        </span>
      @endif
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-circle fs-4 me-2"></i>
      {{ session('error') }}
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
        <div class="step-item {{ $currentStep > 2 ? 'completed' : ($currentStep == 2 ? 'active' : 'locked') }}" id="step2">
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

        <!-- Step 3: Unggah Persyaratan (Dynamic dari BO settings) -->
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
            <p class="text-muted mb-3">
              @if($path && $path->is_upload_berkas && $berkasList->isNotEmpty())
                Upload dokumen yang diperlukan: <strong>{{ $berkasList->pluck('nama_syarat')->implode(', ') }}</strong>.
                @if($path->templateBerkas)
                  @if($path->templateBerkas->ekstensi_diizinkan)
                    <br><small>Format: {{ $path->templateBerkas->ekstensi_diizinkan }}, Maks: {{ $path->templateBerkas->max_size ?? 2048 }} KB</small>
                  @endif
                @endif
              @elseif($path && $path->is_upload_berkas)
                Upload dokumen persyaratan yang diperlukan sesuai ketentuan jalur ini.
              @else
                Jalur ini tidak memerlukan unggahan dokumen tambahan.
              @endif
            </p>
            
            @if($currentStep == 3)
              @if($path && $path->is_upload_berkas)
                <a href="{{ route('daftar-pmb.document.upload', $path?->code) }}" class="btn btn-primary">
                  <i class="ti ti-upload"></i> Unggah Dokumen <i class="ti ti-arrow-right"></i>
                </a>
              @else
                <div class="alert alert-info py-2 small mb-0 d-inline-block">
                  <i class="ti ti-info-circle me-1"></i> Jalur ini tidak memerlukan dokumen. Lanjut ke tahap berikutnya.
                </div>
              @endif
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

        @if($hasExam)
        <!-- Step 4: Ujian Online (CBT) - ONLY if is_ujian_online = true -->
        <div class="step-item {{ $currentStep > 4 ? 'completed' : ($currentStep == 4 ? 'active' : 'locked') }}" id="step4">
          <div class="step-indicator">
            <div class="step-circle {{ $currentStep > 4 ? 'bg-success' : ($currentStep == 4 ? 'bg-primary' : 'bg-light') }}">
              @if($currentStep > 4)
                <i class="ti ti-check text-white"></i>
              @elseif($currentStep == 4)
                <i class="ti ti-edit text-white"></i>
              @else
                <i class="ti ti-lock text-muted"></i>
              @endif
            </div>
            <div class="step-line"></div>
          </div>
          <div class="step-content">
            <div class="step-header">
              <span class="badge {{ $currentStep > 4 ? 'bg-success-subtle text-success' : ($currentStep == 4 ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary') }} px-3 py-2 fw-semibold">Langkah 4</span>
              @if($currentStep > 4)
                <span class="badge bg-success-subtle text-success px-3 py-2"><i class="ti ti-check me-1"></i> Selesai</span>
              @elseif($currentStep == 4)
                <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="ti ti-loader me-1"></i> Sedang Aktif</span>
              @else
                <span class="badge bg-secondary-subtle text-secondary px-3 py-2"><i class="ti ti-lock me-1"></i> Terkunci</span>
              @endif
            </div>
            <h5 class="fw-bold mt-3">Ujian Online (CBT)</h5>
            <p class="text-muted mb-3">Silakan ikuti tes online sesuai jadwal yang ditentukan melalui menu Tes Online.</p>
            
            @if($currentStep == 4)
              <a href="{{ route('daftar-pmb.exam.page', $path?->code) }}" class="btn btn-primary">
                <i class="ti ti-edit"></i> Ikuti Tes Online <i class="ti ti-arrow-right"></i>
              </a>
            @elseif($currentStep > 4)
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
        @endif

        <!-- Step Final: Selesai (dynamic step number) -->
        @php $finalStep = $totalSteps; @endphp
        <div class="step-item {{ $currentStep >= $totalSteps ? 'completed' : 'locked' }}" id="stepFinal">
          <div class="step-indicator">
            <div class="step-circle {{ $currentStep >= $totalSteps ? 'bg-success' : 'bg-light' }}">
              @if($currentStep >= $totalSteps)
                <i class="ti ti-check text-white"></i>
              @else
                <i class="ti ti-lock text-muted"></i>
              @endif
            </div>
          </div>
          <div class="step-content">
            <div class="step-header">
              <span class="badge {{ $currentStep >= $totalSteps ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} px-3 py-2 fw-semibold">Langkah {{ $finalStep }}</span>
              @if($currentStep >= $totalSteps)
                <span class="badge bg-success-subtle text-success px-3 py-2"><i class="ti ti-check me-1"></i> Selesai</span>
              @else
                <span class="badge bg-secondary-subtle text-secondary px-3 py-2"><i class="ti ti-lock me-1"></i> Terkunci</span>
              @endif
            </div>
            <h5 class="fw-bold mt-3">Selesai</h5>
            <p class="text-muted mb-3">
              @if($hasExam)
                Pendaftaran selesai. Silakan tunggu hasil pengumuman kelulusan.
              @else
                Pendaftaran selesai. Silakan lanjut ke menu Tagihan untuk penyelesaian administrasi.
              @endif
            </p>
            
            @if($currentStep >= $totalSteps)
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