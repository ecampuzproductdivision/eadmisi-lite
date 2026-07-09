@extends('layouts.app')

@section('content')
@php
    $path = $path ?? null;
    $registration = $registration ?? null;
    $currentStep = $currentStep ?? 1;
    $hasExam = $path && $path->is_ujian_online;
    $hasManualVerification = $path && $path->metode_pengumuman === 'penilaian_manual';
    $totalSteps = $hasExam ? 5 : ($hasManualVerification ? 5 : 4);
    $hasStep4Content = $hasExam || $hasManualVerification;
    $berkasList = $path && $path->templateBerkas && $path->templateBerkas->syaratDokumens
        ? $path->templateBerkas->syaratDokumens : collect();

    $pendaftaran = $registration;
    if ($registration) {
        $registration->skor_ujian = (isset($examResult) && $examResult) ? $examResult->score : null;
    }
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
            
              @if($isPaymentLocked ?? false)
                <div class="alert alert-danger py-2 small mb-0 d-inline-block" role="alert">
                  <i class="ti ti-lock me-1"></i> Silakan selesaikan pembayaran formulir Anda pada menu <strong>Tagihan</strong> untuk membuka tahap unggah berkas.
                  <br>
                  <a href="{{ route('tagihan.index') }}" class="btn btn-sm btn-primary mt-2">
                    <i class="ti ti-receipt"></i> Bayar Tagihan
                  </a>
                </div>
              @elseif($currentStep == 3)
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

        @if($hasStep4Content)
        <!-- Step 4: Ujian Online (CBT) or Proses Verifikasi Manual -->
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
            @if($hasManualVerification)
              <h5 class="fw-bold mt-3">Proses Verifikasi / Penilaian</h5>
              <p class="text-muted mb-3">Dokumen dan data Anda sedang dalam proses verifikasi oleh tim seleksi. Silakan menunggu pengumuman hasil kelulusan.</p>
              
              @if($currentStep == 4)
                <div class="alert alert-info border-0 shadow-sm mb-0 d-flex gap-3 mt-2">
                  <div class="bg-info-subtle text-info rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                    <i class="ti ti-hourglass fs-3"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-1">Menunggu Verifikasi</h6>
                    <p class="small mb-0">Berkas dan data Anda sedang direview oleh tim seleksi. Hasil kelulusan akan diumumkan melalui halaman ini secara otomatis setelah admin melakukan penilaian.</p>
                  </div>
                </div>
              @elseif($currentStep > 4)
                @if($registration->status === 'Lulus' || $registration->status === 'accepted')
                  <div class="alert alert-success border-0 shadow-sm mb-0 d-flex gap-3 mt-2">
                    <div class="bg-success-subtle text-success rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                      <i class="ti ti-circle-check fs-3"></i>
                    </div>
                    <div>
                      <h6 class="fw-bold mb-1">Dinyatakan Lulus Seleksi</h6>
                      <p class="small mb-0">Selamat! Anda dinyatakan <strong>Lulus</strong> seleksi berdasarkan penilaian manual oleh tim seleksi. Silakan lanjut ke tahap Registrasi Ulang.</p>
                      <a href="{{ route('daftar-pmb.steps', ['pathCode' => $path?->code, 're_registration' => 1]) }}" class="btn btn-success btn-sm mt-2 px-3">
                        <i class="ti ti-id-badge me-1"></i> Mulai Registrasi Ulang
                      </a>
                    </div>
                  </div>
                @elseif($registration->status === 'Gagal')
                  <div class="alert alert-danger border-0 shadow-sm mb-0 d-flex gap-3 mt-2">
                    <div class="bg-danger-subtle text-danger rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                      <i class="ti ti-alert-triangle fs-3"></i>
                    </div>
                    <div>
                      <h6 class="fw-bold mb-1">Dinyatakan Tidak Lulus</h6>
                      <p class="small mb-0">Mohon maaf, berdasarkan hasil penilaian oleh tim seleksi, Anda dinyatakan <strong>Tidak Lulus</strong>. Terima kasih atas partisipasi Anda.</p>
                    </div>
                  </div>
                @endif
              @else
                <div class="step-status text-muted">
                  <i class="ti ti-lock me-1"></i> Selesaikan tahap sebelumnya untuk membuka
                </div>
              @endif
            @else
              <h5 class="fw-bold mt-3">Ujian Online (CBT)</h5>
              <p class="text-muted mb-3">Silakan ikuti tes online sesuai jadwal yang ditentukan melalui menu Tes Online.</p>
              
              @if($currentStep == 4)
                <a href="{{ route('tes-online.start', $registration?->id) }}" class="btn btn-primary">
                  <i class="ti ti-edit"></i> Ikuti Tes Online <i class="ti ti-arrow-right"></i>
                </a>
            @elseif($currentStep > 4)
              @if($path && in_array($path->metode_pengumuman, ['langsung', 'Langsung (One Day Service)']) && $examResult)
                <div class="card border-0 bg-light rounded-3 p-4 mt-3">
                  <h6 class="fw-bold text-dark mb-3"><i class="ti ti-report me-1"></i> Hasil Ujian (CBT)</h6>
                  <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                      <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary-subtle text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                          <i class="ti ti-award fs-3"></i>
                        </div>
                        <div>
                          <small class="text-muted d-block text-uppercase fw-semibold small">Skor Anda</small>
                          <strong class="fs-4 text-dark">{{ number_format($examResult->score, 1) }}</strong>
                          <span class="text-muted small">/ 100</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 border-start ps-4">
                      <small class="text-muted d-block text-uppercase fw-semibold small">Ambang Batas Kelulusan</small>
                      <strong class="fs-5 text-dark">{{ $path->nilai_ambang_batas ?? 75 }}</strong>
                    </div>
                  </div>

                  @if(in_array($registration->status, ['accepted', 'Menunggu Verifikasi Registrasi Ulang', 'registered']))
                    <div class="alert alert-success border-0 shadow-none mb-0 d-flex gap-3 mt-4">
                      <i class="ti ti-circle-check fs-3 mt-1"></i>
                      <div>
                        <h6 class="fw-bold mb-1">Dinyatakan Lulus Seleksi</h6>
                        <p class="small mb-0">Selamat! Nilai ujian Anda memenuhi ambang batas minimal kelulusan. Silakan klik tombol di bawah untuk melakukan registrasi ulang.</p>
                        <a href="#stepFinal" class="btn btn-success btn-sm mt-3 px-4">
                          <i class="ti ti-id-badge me-1"></i> Mulai Registrasi Ulang
                        </a>
                      </div>
                    </div>
                  @elseif($registration->status === 'rejected')
                    <div class="alert alert-danger border-0 shadow-none mb-0 d-flex gap-3 mt-4">
                      <i class="ti ti-alert-triangle fs-3 mt-1"></i>
                      <div>
                        <h6 class="fw-bold mb-1">Dinyatakan Tidak Lulus Seleksi</h6>
                        <p class="small mb-0">Mohon maaf, nilai ujian Anda belum mencapai ambang batas kelulusan minimum yang ditetapkan. Terima kasih atas partisipasi Anda.</p>
                      </div>
                    </div>
                  @else
                    <div class="step-status text-success mt-4">
                      @if($path && in_array($path->metode_pengumuman, ['langsung', 'Langsung (One Day Service)']))
                        <i class="ti ti-circle-check me-1"></i> Evaluasi skor ujian selesai diproses secara langsung.
                      @else
                        <i class="ti ti-circle-check me-1"></i> Tahap ujian telah diselesaikan. Silakan tunggu pengumuman hasil.
                      @endif
                    </div>
                  @endif
                </div>
              @else
                <div class="step-status text-success">
                  <i class="ti ti-circle-check me-1"></i> Tahap ini telah diselesaikan
                </div>
              @endif
              @endif
            @endif
          </div>
        </div>
        @endif

        <!-- Step Final: Selesai / Registrasi Ulang (dynamic step number) -->
        @php
          $finalStep = $totalSteps;
          $finalStepStatus = 'locked';
          if ($registration && !in_array($registration->status, ['rejected', 'Gagal']) && $registration->status_kelulusan !== 'Tidak Lulus') {
              if (in_array($registration->status, ['registered'])) {
                  // Fully registered - show completed recap
                  $finalStepStatus = 'completed';
              } elseif (in_array($registration->status, ['Menunggu Verifikasi Registrasi Ulang']) || $currentStep == $totalSteps) {
                  // Menunggu Verifikasi or current step is final - show active with payment info
                  $finalStepStatus = 'active';
              }
          }

          // Stabilize status layout for Langkah 5
          $isReRegistrationActive = request()->has('re_registration') || request()->get('step') == '5';
        @endphp
        <div class="step-item {{ $finalStepStatus }}" id="stepFinal">
          <div class="step-indicator">
            <div class="step-circle {{ $finalStepStatus === 'completed' ? 'bg-success' : ($finalStepStatus === 'active' ? ($isReRegistrationActive ? 'bg-primary' : 'bg-secondary') : 'bg-light') }}">
              @if($finalStepStatus === 'completed')
                <i class="ti ti-check text-white"></i>
              @elseif($finalStepStatus === 'active')
                <i class="ti {{ $isReRegistrationActive ? 'ti-id-badge' : 'ti-file-text' }} text-white"></i>
              @else
                <i class="ti ti-lock text-muted"></i>
              @endif
            </div>
          </div>
          <div class="step-content">
            <div class="step-header">
              <span class="badge {{ $finalStepStatus === 'completed' ? 'bg-success-subtle text-success' : ($finalStepStatus === 'active' ? ($isReRegistrationActive ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary') : 'bg-secondary-subtle text-secondary') }} px-3 py-2 fw-semibold">Langkah {{ $finalStep }}</span>
              @if($finalStepStatus === 'completed')
                <span class="badge bg-success-subtle text-success px-3 py-2"><i class="ti ti-check me-1"></i> Selesai</span>
              @elseif($finalStepStatus === 'active')
                @if($isReRegistrationActive)
                  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="ti ti-loader me-1"></i> Sedang Aktif</span>
                @else
                  <span class="badge bg-secondary-subtle text-secondary px-3 py-2"><i class="ti ti-info-circle me-1"></i> Siap Diisi</span>
                @endif
              @else
                <span class="badge bg-secondary-subtle text-secondary px-3 py-2"><i class="ti ti-lock me-1"></i> Terkunci</span>
              @endif
            </div>

            @if($finalStepStatus === 'active')
              @if($registration && in_array($registration->status, ['Menunggu Verifikasi Registrasi Ulang']))
                <h5 class="fw-bold mt-3 text-primary"><i class="ti ti-receipt me-2"></i>Menunggu Pembayaran Registrasi Ulang</h5>
                <p class="text-muted mb-4">Registrasi ulang Anda telah kami terima. Silakan selesaikan pembayaran biaya registrasi ulang di bawah ini.</p>

                @if($ulangPayment && $ulangPayment->invoice_number)
                <div class="alert alert-info border-0 shadow-sm mb-3 d-flex align-items-center gap-3">
                  <i class="ti ti-file-text fs-3 text-info"></i>
                  <div>
                    <strong>No. Invoice: {{ $ulangPayment->invoice_number }}</strong>
                    @if($ulangPayment->expired_at)
                      <br><small>Batas Pembayaran: <span class="text-danger">{{ $ulangPayment->expired_at->format('d/m/Y H:i') }}</span></small>
                    @endif
                  </div>
                </div>
                @else
                <div class="alert alert-warning border-0 shadow-sm mb-3 d-flex align-items-center gap-3">
                  <i class="ti ti-clock fs-3 text-warning"></i>
                  <div>
                    <strong>Invoice sedang diproses</strong>
                    <br><small>Silakan klik tombol "Bayar Sekarang" untuk membuat invoice.</small>
                  </div>
                </div>
                @endif

                <div class="card bg-light border-0 rounded-3 mt-3">
                  <div class="card-body p-4">
                    <table class="table table-borderless mb-0">
                      <thead>
                        <tr>
                          <th class="fw-semibold ps-0">Komponen Biaya</th>
                          <th class="fw-semibold text-end pe-0">Nominal (Rp)</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($ulangBiayaList as $b)
                        <tr>
                          <td class="ps-0">{{ $b->komponenBiaya?->nama_komponen ?? '-' }}</td>
                          <td class="text-end pe-0">Rp {{ number_format($b->nominal, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td class="ps-0" colspan="2">Belum ada komponen biaya yang ditetapkan untuk jalur ini.</td></tr>
                        @endforelse
                      </tbody>
                      <tfoot class="border-top">
                        <tr>
                          <td class="fw-bold fs-5 ps-0 pt-3">Total Tagihan</td>
                          <td class="fw-bold fs-5 text-primary text-end pe-0 pt-3">Rp {{ number_format($ulangTotalBiaya, 0, ',', '.') }}</td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                  <form action="{{ route('payment.invoice', $registration->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_type" value="registrasi_ulang">
                    <button type="submit" class="btn btn-success px-4">
                      <i class="ti ti-credit-card me-2"></i> Bayar Sekarang
                    </button>
                  </form>
                  <a href="{{ route('tagihan.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-receipt"></i> Lihat Semua Tagihan
                  </a>
                </div>
              @elseif($registration && ($registration->status === 'accepted' || $registration->status === 'Lulus' || $registration->status_kelulusan === 'Lulus'))
                
                @if(!$isReRegistrationActive)
                  <!-- Automated Re-registration Entry Trigger -->
                  @if($pendaftaran->status_kelulusan === 'Lulus')
                      <div class="card border-success shadow-sm my-4" style="background-color: #f8fff9; border-left: 5px solid #28a745;">
                          <div class="card-body p-4">
                              <div class="d-flex align-items-center mb-3">
                                  <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; flex-shrink: 0;">
                                      <i class="ti ti-trophy fs-4"></i>
                                  </div>
                                  <div>
                                      <span class="badge bg-success px-3 py-1 font-weight-bold" style="font-size: 0.85rem;">PENGUMUMAN HASIL SELEKSI</span>
                                      <h4 class="card-title text-success font-weight-bold mb-0 mt-1">Selamat! Anda Dinyatakan LULUS</h4>
                                  </div>
                              </div>
                              
                              <p class="card-text text-dark" style="font-size: 0.95rem; line-height: 1.6;">
                                  Berdasarkan hasil evaluasi ujian online CBT dengan perolehan skor <strong>{{ $pendaftaran->skor_ujian }}</strong>, Anda telah memenuhi nilai ambang batas kelulusan yang ditentukan. Silakan melanjutkan ke tahap berikutnya untuk melengkapi berkas administrasi dan mengunci status kemahasiswaan Anda.
                              </p>
                              
                              <hr class="my-3" style="border-top: 1px dashed #28a745;">
                              
                              <div class="d-flex justify-content-between align-items-center flex-wrap">
                                  <span class="text-muted small mb-2 mb-md-0">
                                      <i class="ti ti-clock me-1"></i> Diproses secara instan melalui sistem One Day Service.
                                  </span>
                                  <a href="{{ route('daftar-pmb.steps', ['pathCode' => $path?->code, 're_registration' => 1]) }}" class="btn btn-success px-4 py-2 font-weight-bold shadow-sm">
                                      <i class="ti ti-arrow-right me-2"></i> Mulai Registrasi Ulang <i class="ti ti-file-text ms-1"></i>
                                  </a>
                              </div>
                          </div>
                      </div>
                  @elseif($pendaftaran->status_kelulusan === 'Tidak Lulus')
                      <div class="card border-danger shadow-sm my-4" style="background-color: #fffafb; border-left: 5px solid #dc3545;">
                          <div class="card-body p-4">
                              <h5 class="text-danger font-weight-bold">Mohon Maaf, Anda Dinyatakan Belum Lulus</h5>
                              <p class="small text-muted mb-0">Skor Anda belum mencapai kriteria minimal ambang batas kelulusan jalur ini. Terima kasih telah berpartisipasi.</p>
                          </div>
                      </div>
                  @endif
                @else
                  <h5 class="fw-bold mt-3 text-primary"><i class="ti ti-id-badge me-2"></i>Formulir Registrasi Ulang</h5>
                  <p class="text-muted mb-4">Selamat! Anda dinyatakan <strong>Lulus Seleksi</strong>. Silakan lengkapi data registrasi ulang di bawah ini untuk penerbitan NIM dan pelaporan PDDikti.</p>

                <form action="{{ route('daftar-pmb.re-registration.store', $path?->code) }}" method="POST" id="reRegistrationForm" class="mt-3">
                  @csrf
                  
                  <div class="row g-3">
                    <!-- 1. Nama Lengkap (Autofilled & Readonly) -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Nama Lengkap</label>
                      <input type="text" class="form-control bg-light" name="nama_lengkap" value="{{ old('nama_lengkap', $registration->nama_lengkap ?? auth()->user()->name) }}" readonly>
                    </div>

                    <!-- 2. Jenis Kelamin (Autofilled & Readonly) -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Jenis Kelamin</label>
                      <input type="hidden" name="jenis_kelamin" value="{{ $registration->jenis_kelamin }}">
                      <input type="text" class="form-control bg-light" value="{{ $registration->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}" readonly>
                    </div>

                    <!-- 3. Tempat Lahir -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Tempat Lahir <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir', $registration->tempat_lahir) }}" placeholder="Contoh: Sleman" required>
                    </div>

                    <!-- 4. Tanggal Lahir -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Tanggal Lahir <span class="text-danger">*</span></label>
                      <input type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir', $registration->tanggal_lahir) }}" required>
                    </div>

                    <!-- 5. Agama -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Agama <span class="text-danger">*</span></label>
                      <select class="form-select" name="agama" required>
                        <option value="" disabled selected>-- Pilih Agama --</option>
                        @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Khonghucu', 'Penghayat Kepercayaan', 'Lainnya'] as $rel)
                          <option value="{{ $rel }}" {{ old('agama', $registration->agama) == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                        @endforeach
                      </select>
                    </div>

                    <!-- 6. NIK (16 Digits) -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                      <input type="text" class="form-control @error('nik') is-invalid @enderror" name="nik" value="{{ old('nik', $registration->nik) }}" placeholder="16 Digit Nomor KTP" maxlength="16" pattern="[0-9]{16}" required>
                      @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                      <small class="text-muted">Harus bernilai tepat 16 digit angka.</small>
                    </div>

                    <!-- 7. NISN (10 Digits) -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">NISN (Nomor Induk Siswa Nasional) <span class="text-danger">*</span></label>
                      <input type="text" class="form-control @error('nisn') is-invalid @enderror" name="nisn" value="{{ old('nisn', $registration->nisn) }}" placeholder="10 Digit NISN" maxlength="10" pattern="[0-9]{10}" required>
                      @error('nisn')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                      <small class="text-muted">Harus bernilai tepat 10 digit angka.</small>
                    </div>

                    <!-- 8. Nomor Handphone (Autofilled & Readonly) -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Nomor Handphone</label>
                      <input type="tel" class="form-control bg-light" name="no_hp" value="{{ old('no_hp', $registration->no_hp ?? auth()->user()->phone) }}" readonly>
                    </div>

                    <!-- 9. Alamat Email (Autofilled & Readonly) -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Alamat Email</label>
                      <input type="email" class="form-control bg-light" name="email" value="{{ old('email', $registration->email ?? auth()->user()->email) }}" readonly>
                    </div>

                    <!-- 10. Nama Ibu Kandung -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Nama Ibu Kandung <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="nama_ibu_kandung" value="{{ old('nama_ibu_kandung', $registration->nama_ibu_kandung) }}" placeholder="Masukkan nama ibu kandung" required>
                    </div>

                    <!-- 11. Penerima KPS -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Penerima KPS? <span class="text-danger">*</span></label>
                      <select class="form-select" name="penerima_kps" required>
                        <option value="" disabled selected>-- Pilih --</option>
                        <option value="Ya" {{ old('penerima_kps', $registration->penerima_kps) == 'Ya' ? 'selected' : '' }}>Ya</option>
                        <option value="Tidak" {{ old('penerima_kps', $registration->penerima_kps) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                      </select>
                    </div>

                    <!-- 12. Kebutuhan Khusus -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Kebutuhan Khusus? <span class="text-danger">*</span></label>
                      <select class="form-select" name="kebutuhan_khusus" id="kebutuhan-khusus-select" required>
                        <option value="" disabled selected>-- Pilih --</option>
                        <option value="Ya" {{ old('kebutuhan_khusus', $registration->kebutuhan_khusus) == 'Ya' ? 'selected' : '' }}>Ya</option>
                        <option value="Tidak" {{ old('kebutuhan_khusus', $registration->kebutuhan_khusus) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                      </select>
                    </div>

                    <!-- ═══ CONDITIONAL NEEDS CHECKBOX GRID (PDDIKTI) ═══ -->
                    @php
                      $needsList = [
                        'A' => 'Tuna Netra', 'B' => 'Tuna Rungu', 'C' => 'Tuna Grahita Ringan', 'C1' => 'Tuna Grahita Sedang',
                        'D' => 'Tuna Daksa Ringan', 'D1' => 'Tuna Daksa Sedang', 'E' => 'Tuna Laras', 'F' => 'Tuna Wicara',
                        'H' => 'Hiperaktif', 'I' => 'Cerdas Istimewa', 'J' => 'Bakat Istimewa', 'K' => 'Kesulitan Belajar',
                        'N' => 'Narkoba', 'O' => 'Indigo', 'P' => 'Down Syndrome', 'Q' => 'Autis',
                      ];
                      $oldMhs = old('kebutuhan_khusus_mhs', $registration->kebutuhan_khusus_mhs ?? []);
                      $oldAyah = old('kebutuhan_khusus_ayah', $registration->kebutuhan_khusus_ayah ?? []);
                      $oldIbu = old('kebutuhan_khusus_ibu', $registration->kebutuhan_khusus_ibu ?? []);
                      if (is_string($oldMhs)) $oldMhs = json_decode($oldMhs, true) ?? [];
                      if (is_string($oldAyah)) $oldAyah = json_decode($oldAyah, true) ?? [];
                      if (is_string($oldIbu)) $oldIbu = json_decode($oldIbu, true) ?? [];
                    @endphp
                    <div class="col-12" id="kebutuhan-khusus-container" style="display: none;">
                      <div class="card border-0 bg-light rounded-3 p-4 mt-2 mb-3">
                        <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="ti ti-clipboard-list me-1"></i>Detail Kebutuhan Khusus (PDDIKTI)</h6>
                        <div class="row">
                          <!-- MAHASISWA Column -->
                          <div class="col-md-4">
                            <div class="fw-semibold small text-uppercase text-muted mb-2 border-bottom pb-1">MAHASISWA</div>
                            @foreach($needsList as $key => $label)
                            <div class="custom-control custom-checkbox mb-2">
                              <input type="checkbox" name="kebutuhan_khusus_mhs[]" value="{{ $key }}" class="custom-control-input" id="kebutuhan_mhs_{{ strtolower($key) }}" {{ in_array($key, $oldMhs) ? 'checked' : '' }}>
                              <label class="custom-control-label small text-dark" for="kebutuhan_mhs_{{ strtolower($key) }}">
                                <span class="badge bg-secondary me-1">{{ $key }}</span> {{ $label }}
                              </label>
                            </div>
                            @endforeach
                          </div>
                          <!-- AYAH Column -->
                          <div class="col-md-4">
                            <div class="fw-semibold small text-uppercase text-muted mb-2 border-bottom pb-1">AYAH</div>
                            @foreach($needsList as $key => $label)
                            <div class="custom-control custom-checkbox mb-2">
                              <input type="checkbox" name="kebutuhan_khusus_ayah[]" value="{{ $key }}" class="custom-control-input" id="kebutuhan_ayah_{{ strtolower($key) }}" {{ in_array($key, $oldAyah) ? 'checked' : '' }}>
                              <label class="custom-control-label small text-dark" for="kebutuhan_ayah_{{ strtolower($key) }}">
                                <span class="badge bg-secondary me-1">{{ $key }}</span> {{ $label }}
                              </label>
                            </div>
                            @endforeach
                          </div>
                          <!-- IBU Column -->
                          <div class="col-md-4">
                            <div class="fw-semibold small text-uppercase text-muted mb-2 border-bottom pb-1">IBU</div>
                            @foreach($needsList as $key => $label)
                            <div class="custom-control custom-checkbox mb-2">
                              <input type="checkbox" name="kebutuhan_khusus_ibu[]" value="{{ $key }}" class="custom-control-input" id="kebutuhan_ibu_{{ strtolower($key) }}" {{ in_array($key, $oldIbu) ? 'checked' : '' }}>
                              <label class="custom-control-label small text-dark" for="kebutuhan_ibu_{{ strtolower($key) }}">
                                <span class="badge bg-secondary me-1">{{ $key }}</span> {{ $label }}
                              </label>
                            </div>
                            @endforeach
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- 13. Kewarganegaraan -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Kewarganegaraan <span class="text-danger">*</span></label>
                      <select class="form-select" name="kewarganegaraan" required>
                        @php
                          $countries = ['Indonesia', 'Malaysia', 'Singapore', 'Australia', 'Brunei Darussalam', 'East Timor', 'Philippines', 'Thailand', 'Vietnam', 'Cambodia', 'Myanmar', 'Japan', 'South Korea'];
                        @endphp
                        @foreach($countries as $country)
                          <option value="{{ $country }}" {{ old('kewarganegaraan', $registration->kewarganegaraan ?? 'Indonesia') == $country ? 'selected' : '' }}>{{ $country }}</option>
                        @endforeach
                      </select>
                    </div>

                    <!-- 14. Kabupaten (server-side rendered options) -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Kabupaten/Kota <span class="text-danger">*</span></label>
                      <select class="form-select" name="regency_id" id="regency-select" required style="width: 100%;">
                        <option value="">-- Pilih Kabupaten/Kota --</option>
                        @php $selectedRegency = old('regency_id', $registration->regency_id ?? ''); @endphp
                        @if(isset($masterRegencies) && $masterRegencies->isNotEmpty())
                          @foreach($masterRegencies as $kab)
                            <option value="{{ $kab->id }}" {{ $selectedRegency == $kab->id ? 'selected' : '' }}>{{ $kab->display }}</option>
                          @endforeach
                        @else
                          <option value="1">Kab. Sleman, D.I. Yogyakarta</option>
                          <option value="3">Kab. Bantul, D.I. Yogyakarta</option>
                          <option value="4">Kota Jakarta Selatan, D.K.I. Jakarta</option>
                          <option value="5">Kota Bandung, Jawa Barat</option>
                          <option value="6">Kota Surabaya, Jawa Timur</option>
                          <option value="7">Kota Medan, Sumatera Utara</option>
                          <option value="2">Kota Palu, Sulawesi Tengah</option>
                        @endif
                      </select>
                      <small class="text-muted">Ketik nama kabupaten/kota untuk mencari.</small>
                    </div>

                    <!-- 15. Kecamatan (API-powered live search) -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Kecamatan <span class="text-danger">*</span></label>
                      <select class="form-select" name="kecamatan_id" id="district-select" required style="width: 100%;">
                        <option value="">-- Pilih Kecamatan --</option>
                      </select>
                    </div>

                    <!-- 16. Kelurahan (API-powered live search) -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Desa/Kelurahan <span class="text-danger">*</span></label>
                      <select class="form-select" name="kelurahan_id" id="village-select" required style="width: 100%;">
                        <option value="">-- Pilih Desa/Kelurahan --</option>
                      </select>
                    </div>
                  </div>

                  <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-5">
                      <i class="ti ti-send me-1"></i> Submit Registrasi Ulang
                    </button>
                  </div>
                  </form>
                @endif
              @else
              @endif
            @elseif($finalStepStatus === 'completed' && $registration && $registration->status === 'registered')
              <h5 class="fw-bold mt-3 text-success"><i class="ti ti-circle-check me-2"></i>Registrasi Ulang Selesai</h5>
              
              @if($registration->status === 'Menunggu Verifikasi Registrasi Ulang')
                <div class="alert alert-warning border-0 shadow-none mb-4 d-flex gap-3 mt-3">
                  <i class="ti ti-loader fs-3 mt-1"></i>
                  <div>
                    <h6 class="fw-bold mb-1">Menunggu Verifikasi Akademik</h6>
                    <p class="small mb-0">Terima kasih! Data registrasi ulang Anda telah berhasil kami terima. Saat ini tim administrasi akademik sedang melakukan verifikasi berkas dan generate <strong>NIM (Nomor Induk Mahasiswa)</strong> Anda. Silakan cek halaman ini secara berkala.</p>
                  </div>
                </div>
              @elseif($registration->status === 'registered')
                <div class="alert alert-success border-0 shadow-none mb-4 d-flex gap-3 mt-3">
                  <i class="ti ti-circle-check fs-3 mt-1"></i>
                  <div>
                    <h6 class="fw-bold mb-1">Registrasi Ulang Terverifikasi</h6>
                    <p class="small mb-0">Selamat! Anda telah resmi terdaftar sebagai mahasiswa baru. NIM Anda telah digenerate: <strong class="fs-5">{{ $registration->nim }}</strong></p>
                  </div>
                </div>
              @endif

              <div class="card bg-light border-0 rounded-3 mt-4">
                <div class="card-body p-4">
                  <h6 class="fw-bold mb-3 border-bottom pb-2 text-dark"><i class="ti ti-file-text me-1"></i>Recap Data Registrasi Ulang</h6>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <small class="text-muted d-block">Nama Lengkap</small>
                      <strong class="text-dark">{{ $registration->nama_lengkap }}</strong>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Jenis Kelamin</small>
                      <strong class="text-dark">{{ $registration->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</strong>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Tempat & Tanggal Lahir</small>
                      <strong class="text-dark">{{ $registration->tempat_lahir }}, {{ $registration->tanggal_lahir ? date('d/m/Y', strtotime($registration->tanggal_lahir)) : '-' }}</strong>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Agama</small>
                      <strong class="text-dark">{{ $registration->agama }}</strong>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">NIK</small>
                      <strong class="text-dark">{{ $registration->nik }}</strong>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">NISN</small>
                      <strong class="text-dark">{{ $registration->nisn }}</strong>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Nama Ibu Kandung</small>
                      <strong class="text-dark">{{ $registration->nama_ibu_kandung }}</strong>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Kewarganegaraan</small>
                      <strong class="text-dark">{{ $registration->kewarganegaraan }}</strong>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Penerima KPS</small>
                      <strong class="text-dark">{{ $registration->penerima_kps }}</strong>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Kebutuhan Khusus</small>
                      <strong class="text-dark">{{ $registration->kebutuhan_khusus }}</strong>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Kabupaten/Kota</small>
                      <strong class="text-dark">{{ $registration->regency ? ($registration->regency->type . ' ' . $registration->regency->name) : '-' }}</strong>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Kecamatan</small>
                      <strong class="text-dark">{{ $registration->kecamatan?->name ?? '-' }}</strong>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Desa/Kelurahan</small>
                      <strong class="text-dark">{{ $registration->kelurahan?->name ?? '-' }}</strong>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">No. HP</small>
                      <strong class="text-dark">{{ $registration->no_hp }}</strong>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted d-block">Alamat Email</small>
                      <strong class="text-dark">{{ $registration->email }}</strong>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-start mt-4">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 me-2">
                  <i class="ti ti-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
                <a href="{{ route('daftar-pmb.review', $path?->code) }}" class="btn btn-primary px-4">
                  Lihat Detail Pendaftaran <i class="ti ti-arrow-right ms-1"></i>
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

@push('scripts')
<script>
$(document).ready(function() {
    var r = $('#regency-select'), d = $('#district-select'), v = $('#village-select');

    console.log("Initializing Select2 fields...");

    // Force reset: remove any select2 wrapper, re-init native select clean
    r.next('.select2').remove(); r.removeAttr('data-select2-id'); r.find('option').remove();
    d.next('.select2').remove(); d.removeAttr('data-select2-id'); d.find('option').remove();
    v.next('.select2').remove(); v.removeAttr('data-select2-id'); v.find('option').remove();

    r.append('<option value="">-- Pilih Kabupaten/Kota --</option>');
    d.append('<option value="">-- Pilih Kecamatan --</option>');
    v.append('<option value="">-- Pilih Desa/Kelurahan --</option>');

    // Re-render pre-existing options in the HTML select element if any
    @if(isset($masterRegencies) && $masterRegencies->isNotEmpty())
        @foreach($masterRegencies as $kab)
            r.append(new Option("{{ $kab->display }}", "{{ $kab->id }}", false, false));
        @endforeach
    @else
        r.append(new Option("Kab. Sleman, D.I. Yogyakarta", "1", false, false));
        r.append(new Option("Kab. Bantul, D.I. Yogyakarta", "3", false, false));
        r.append(new Option("Kota Jakarta Selatan, D.K.I. Jakarta", "4", false, false));
        r.append(new Option("Kota Bandung, Jawa Barat", "5", false, false));
        r.append(new Option("Kota Surabaya, Jawa Timur", "6", false, false));
        r.append(new Option("Kota Medan, Sumatera Utara", "7", false, false));
        r.append(new Option("Kota Palu, Sulawesi Tengah", "2", false, false));
    @endif

    // Set selected value if exist from database/old input
    var preselectedRegency = "{{ old('regency_id', $registration->regency_id ?? '') }}";
    if (preselectedRegency) {
        r.val(preselectedRegency);
    }

    r.select2({ 
        placeholder: "-- Pilih Kabupaten/Kota --", 
        allowClear: true, 
        width: '100%' 
    });
    d.select2({ 
        placeholder: "-- Pilih Kecamatan --", 
        allowClear: true, 
        width: '100%' 
    });
    v.select2({ 
        placeholder: "-- Pilih Desa/Kelurahan --", 
        allowClear: true, 
        width: '100%' 
    });

    r.on('change', function() {
        var regencyId = $(this).val();
        console.log("Regency changed to:", regencyId);

        // Reset district and village options
        d.empty().append('<option value="">-- Pilih Kecamatan --</option>').val(null).trigger('change');
        v.empty().append('<option value="">-- Pilih Desa/Kelurahan --</option>').val(null).trigger('change');

        if (!regencyId) return;

        var url = '{{ url("/api/local/kecamatan") }}/' + encodeURIComponent(regencyId);
        console.log("Fetching Kecamatan from:", url);

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                console.log("Kecamatan API returned:", data);
                d.empty().append('<option value="">-- Pilih Kecamatan --</option>');
                
                $.each(data, function (index, item) {
                    var displayText = item.text || item.nama_kecamatan;
                    d.append(new Option(displayText, item.id, false, false));
                });
                
                d.trigger('change');
            },
            error: function(xhr, status, error) {
                console.error("Failed to load Kecamatan:", status, error, xhr.responseText);
            }
        });
    });

    d.on('change', function() {
        var districtId = $(this).val();
        console.log("District changed to:", districtId);

        // Reset village options
        v.empty().append('<option value="">-- Pilih Desa/Kelurahan --</option>').val(null).trigger('change');

        if (!districtId) return;

        var url = '{{ url("/api/local/kelurahan") }}/' + encodeURIComponent(districtId);
        console.log("Fetching Kelurahan from:", url);

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                console.log("Kelurahan API returned:", data);
                v.empty().append('<option value="">-- Pilih Desa/Kelurahan --</option>');
                
                $.each(data, function (index, item) {
                    var displayText = item.text || item.nama_kelurahan;
                    v.append(new Option(displayText, item.id, false, false));
                });
                
                v.trigger('change');
            },
            error: function(xhr, status, error) {
                console.error("Failed to load Kelurahan:", status, error, xhr.responseText);
            }
        });
    });

    // If there was a pre-selected regency, trigger change to load districts initially
    if (r.val()) {
        console.log("Triggering initial regency load for:", r.val());
        r.trigger('change');
    }

    // ─── TOGGLE: Kebutuhan Khusus checkbox grid ───
    var needsSelect = $('#kebutuhan-khusus-select');
    var needsContainer = $('#kebutuhan-khusus-container');
    
    function toggleNeedsGrid() {
        if (needsSelect.val() === 'Ya') {
            needsContainer.slideDown(300);
        } else {
            needsContainer.slideUp(250);
            needsContainer.find('input[type="checkbox"]').prop('checked', false);
        }
    }
    
    needsSelect.on('change', toggleNeedsGrid);
    // Initial state check (for old() values after validation error)
    toggleNeedsGrid();
});
</script>
@endpush
@endsection