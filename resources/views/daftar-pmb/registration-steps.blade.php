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
                      <i class="ti ti-circle-check me-1"></i> Tahap ujian telah diselesaikan. Silakan tunggu pengumuman hasil.
                    </div>
                  @endif
                </div>
              @else
                <div class="step-status text-success">
                  <i class="ti ti-circle-check me-1"></i> Tahap ini telah diselesaikan
                </div>
              @endif
            @else
              <div class="step-status text-muted">
                <i class="ti ti-lock me-1"></i> Selesaikan tahap sebelumnya untuk membuka
              </div>
            @endif
          </div>
        </div>
        @endif

        <!-- Step Final: Selesai / Registrasi Ulang (dynamic step number) -->
        @php
          $finalStep = $totalSteps;
          $finalStepStatus = 'locked';
          if ($registration && $registration->status !== 'rejected') {
              if (in_array($registration->status, ['Menunggu Verifikasi Registrasi Ulang', 'registered'])) {
                  $finalStepStatus = 'completed';
              } elseif ($currentStep == $totalSteps) {
                  $finalStepStatus = 'active';
              }
          }
        @endphp
        <div class="step-item {{ $finalStepStatus }}" id="stepFinal">
          <div class="step-indicator">
            <div class="step-circle {{ $finalStepStatus === 'completed' ? 'bg-success' : ($finalStepStatus === 'active' ? 'bg-primary' : 'bg-light') }}">
              @if($finalStepStatus === 'completed')
                <i class="ti ti-check text-white"></i>
              @elseif($finalStepStatus === 'active')
                <i class="ti ti-id-badge text-white"></i>
              @else
                <i class="ti ti-lock text-muted"></i>
              @endif
            </div>
          </div>
          <div class="step-content">
            <div class="step-header">
              <span class="badge {{ $finalStepStatus === 'completed' ? 'bg-success-subtle text-success' : ($finalStepStatus === 'active' ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary') }} px-3 py-2 fw-semibold">Langkah {{ $finalStep }}</span>
              @if($finalStepStatus === 'completed')
                <span class="badge bg-success-subtle text-success px-3 py-2"><i class="ti ti-check me-1"></i> Selesai</span>
              @elseif($finalStepStatus === 'active')
                <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="ti ti-loader me-1"></i> Sedang Aktif</span>
              @else
                <span class="badge bg-secondary-subtle text-secondary px-3 py-2"><i class="ti ti-lock me-1"></i> Terkunci</span>
              @endif
            </div>

            @if($finalStepStatus === 'active')
              @if($registration && $registration->status === 'accepted')
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
                      <select class="form-select" name="kebutuhan_khusus" required>
                        <option value="" disabled selected>-- Pilih --</option>
                        <option value="Ya" {{ old('kebutuhan_khusus', $registration->kebutuhan_khusus) == 'Ya' ? 'selected' : '' }}>Ya</option>
                        <option value="Tidak" {{ old('kebutuhan_khusus', $registration->kebutuhan_khusus) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                      </select>
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

                    <!-- 14. Kabupaten (Searchable Combo Dropdown) -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Kabupaten/Kota <span class="text-danger">*</span></label>
                      <select class="form-select" name="regency_id" id="kabupaten_select" required style="width: 100%;">
                        @php
                          $selectedRegency = old('regency_id', $registration->regency_id ?? auth()->user()->regency_id);
                          $regencyObj = $selectedRegency ? \App\Models\Regency::find($selectedRegency) : null;
                        @endphp
                        @if($regencyObj)
                          <option value="{{ $regencyObj->id }}" selected>{{ $regencyObj->type }} {{ $regencyObj->name }}, {{ $regencyObj->province }}</option>
                        @endif
                      </select>
                      <small class="text-muted">Ketik nama kabupaten/kota untuk mencari.</small>
                    </div>

                    <!-- 15. Kecamatan (Cascading) -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Kecamatan <span class="text-danger">*</span></label>
                      <select class="form-select" name="kecamatan_id" id="kecamatan_select" required>
                        <option value="" disabled selected>-- Pilih Kecamatan --</option>
                        @if(old('kecamatan_id', $registration->kecamatan_id))
                          @php
                            $selectedKec = \App\Models\Kecamatan::find(old('kecamatan_id', $registration->kecamatan_id));
                          @endphp
                          @if($selectedKec)
                            <option value="{{ $selectedKec->id }}" selected>{{ $selectedKec->name }}</option>
                          @endif
                        @endif
                      </select>
                    </div>

                    <!-- 16. Kelurahan (Cascading) -->
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-uppercase small text-muted">Desa/Kelurahan <span class="text-danger">*</span></label>
                      <select class="form-select" name="kelurahan_id" id="kelurahan_select" required>
                        <option value="" disabled selected>-- Pilih Desa/Kelurahan --</option>
                        @if(old('kelurahan_id', $registration->kelurahan_id))
                          @php
                            $selectedKel = \App\Models\Kelurahan::find(old('kelurahan_id', $registration->kelurahan_id));
                          @endphp
                          @if($selectedKel)
                            <option value="{{ $selectedKel->id }}" selected>{{ $selectedKel->name }}</option>
                          @endif
                        @endif
                      </select>
                    </div>
                  </div>

                  <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-5">
                      <i class="ti ti-send me-1"></i> Submit Registrasi Ulang
                    </button>
                  </div>
                </form>
              @else
                <h5 class="fw-bold mt-3">Selesai</h5>
                <p class="text-muted mb-3">
                  @if($hasExam)
                    Pendaftaran selesai. Silakan tunggu hasil pengumuman kelulusan.
                  @else
                    Pendaftaran selesai. Silakan lanjut ke menu Tagihan untuk penyelesaian administrasi.
                  @endif
                </p>
                
                <div class="d-flex flex-wrap gap-2">
                  <a href="{{ route('daftar-pmb.review', $path?->code) }}" class="btn btn-success">
                    <i class="ti ti-eye"></i> Lihat Ringkasan
                  </a>
                  <a href="{{ route('tagihan.index') }}" class="btn btn-warning">
                    <i class="ti ti-receipt"></i> Lihat Tagihan
                  </a>
                </div>
              @endif
            @elseif($finalStepStatus === 'completed')
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

<script>
  $(document).ready(function() {
      // Initialize Select2 with dynamic AJAX lookup for Kabupaten/Kota
      var kabSelect = $('#kabupaten_select');
      
      // Failsafe: Destroy if already initialized
      if (kabSelect.hasClass("select2-hidden-accessible")) {
          kabSelect.select2('destroy');
      }

      kabSelect.select2({
          placeholder: "-- Pilih Kabupaten/Kota --",
          allowClear: true,
          ajax: {
              url: "{{ route('api.regencies.select2') }}",
              dataType: 'json',
              delay: 250,
              data: function (params) {
                  return {
                      q: params.term, // search term
                      page: params.page || 1
                  };
              },
              processResults: function (data, params) {
                  params.page = params.page || 1;
                  return {
                      results: data.results,
                      pagination: {
                          more: data.pagination.more
                      }
                  };
              },
              cache: true
          },
          width: '100%'
      });

      // Handle Kabupaten select change -> load Kecamatan
      kabSelect.on('change', function() {
          var regencyId = $(this).val();
          
          // Clear cascading dropdowns
          $('#kecamatan_select').empty().append('<option value="" disabled selected>-- Pilih Kecamatan --</option>');
          $('#kelurahan_select').empty().append('<option value="" disabled selected>-- Pilih Desa/Kelurahan --</option>');
          
          if (regencyId) {
              $.ajax({
                  url: '/api/wilayah/kecamatan/' + regencyId,
                  type: 'GET',
                  dataType: 'json',
                  success: function(data) {
                      $.each(data, function(key, val) {
                          $('#kecamatan_select').append('<option value="' + val.id + '">' + val.name + '</option>');
                      });
                  }
              });
          }
      });

      // Handle Kecamatan select change -> load Kelurahan
      $('#kecamatan_select').on('change', function() {
          var kecamatanId = $(this).val();
          
          // Clear cascading dropdown
          $('#kelurahan_select').empty().append('<option value="" disabled selected>-- Pilih Desa/Kelurahan --</option>');
          
          if (kecamatanId) {
              $.ajax({
                  url: '/api/wilayah/kelurahan/' + kecamatanId,
                  type: 'GET',
                  dataType: 'json',
                  success: function(data) {
                      $.each(data, function(key, val) {
                          $('#kelurahan_select').append('<option value="' + val.id + '">' + val.name + '</option>');
                      });
                  }
              });
          }
      });

      // Prefill cascade if kabupaten is already selected initially (e.g. from user profile or old input)
      var initialKabupaten = kabSelect.val();
      if (initialKabupaten) {
          var selectedKecId = "{{ old('kecamatan_id', $registration->kecamatan_id ?? '') }}";
          var selectedKelId = "{{ old('kelurahan_id', $registration->kelurahan_id ?? '') }}";
          
          $.ajax({
              url: '/api/wilayah/kecamatan/' + initialKabupaten,
              type: 'GET',
              dataType: 'json',
              success: function(data) {
                  $.each(data, function(key, val) {
                      var isSelected = (val.id == selectedKecId) ? 'selected' : '';
                      $('#kecamatan_select').append('<option value="' + val.id + '" ' + isSelected + '>' + val.name + '</option>');
                  });
                  
                  // If kecamatan was already selected, trigger load for Kelurahan
                  if (selectedKecId) {
                      $.ajax({
                          url: '/api/wilayah/kelurahan/' + selectedKecId,
                          type: 'GET',
                          dataType: 'json',
                          success: function(data) {
                              $.each(data, function(key, val) {
                                  var isSelected = (val.id == selectedKelId) ? 'selected' : '';
                                  $('#kelurahan_select').append('<option value="' + val.id + '" ' + isSelected + '>' + val.name + '</option>');
                              });
                          }
                      });
                  }
              }
          });
      }
  });
</script>
@endsection