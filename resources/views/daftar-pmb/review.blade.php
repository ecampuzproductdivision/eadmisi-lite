@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      <!-- Header -->
      <div class="text-center mb-5">
        <div class="rounded-circle bg-success d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
          <i class="ti ti-check text-white" style="font-size: 2rem;"></i>
        </div>
        <h2 class="fw-bold mb-2">Pendaftaran Berhasil!</h2>
        <p class="text-muted mb-0">Selamat! Proses pendaftaran Anda telah selesai. Berikut ringkasan data Anda.</p>
      </div>

      <!-- Mini Stepper -->
      <div class="d-flex align-items-center justify-content-center gap-2 mb-5">
        <div class="stepper-item-sm completed">
          <div class="stepper-circle-sm bg-success text-white"><i class="ti ti-check"></i></div>
          <span class="stepper-label-sm text-success fw-semibold">Data Pribadi</span>
        </div>
        <div class="stepper-line-sm bg-success"></div>
        <div class="stepper-item-sm completed">
          <div class="stepper-circle-sm bg-success text-white"><i class="ti ti-check"></i></div>
          <span class="stepper-label-sm text-success fw-semibold">Prodi</span>
        </div>
        <div class="stepper-line-sm bg-success"></div>
        <div class="stepper-item-sm completed">
          <div class="stepper-circle-sm bg-success text-white"><i class="ti ti-check"></i></div>
          <span class="stepper-label-sm text-success fw-semibold">Berkas</span>
        </div>
        <div class="stepper-line-sm bg-success"></div>
        <div class="stepper-item-sm completed">
          <div class="stepper-circle-sm bg-success text-white"><i class="ti ti-check"></i></div>
          <span class="stepper-label-sm text-success fw-semibold">Ujian</span>
        </div>
        <div class="stepper-line-sm bg-success"></div>
        <div class="stepper-item-sm current">
          <div class="stepper-circle-sm bg-success text-white fw-bold">
            <i class="ti ti-check"></i>
          </div>
          <span class="stepper-label-sm text-success fw-semibold">Selesai</span>
        </div>
      </div>

      @if($registration)
      <!-- Card Data Pribadi -->
      <div class="card border-1 shadow-sm mb-4">
        <div class="card-header bg-light py-3">
          <h5 class="fw-bold mb-0">
            <i class="ti ti-user text-primary me-2"></i>Data Pribadi
          </h5>
        </div>
        <div class="card-body p-4">
          <div class="row g-3">
            <div class="col-md-6">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Nama Lengkap</small>
              <strong>{{ $registration->nama_lengkap }}</strong>
            </div>
            <div class="col-md-3">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Tempat Lahir</small>
              <strong>{{ $registration->tempat_lahir ?? '-' }}</strong>
            </div>
            <div class="col-md-3">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Tanggal Lahir</small>
              <strong>{{ $registration->tanggal_lahir ? date('d/m/Y', strtotime($registration->tanggal_lahir)) : '-' }}</strong>
            </div>
            <div class="col-md-3">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Jenis Kelamin</small>
              <strong>{{ $registration->jenis_kelamin == 'L' ? 'Laki-laki' : ($registration->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</strong>
            </div>
            <div class="col-md-3">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Agama</small>
              <strong>{{ $registration->agama ?? '-' }}</strong>
            </div>
            <div class="col-md-6">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">NIK</small>
              <strong>{{ $registration->nik ?? '-' }}</strong>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Kontak & Alamat -->
      <div class="card border-1 shadow-sm mb-4">
        <div class="card-header bg-light py-3">
          <h5 class="fw-bold mb-0">
            <i class="ti ti-mail text-primary me-2"></i>Kontak & Alamat
          </h5>
        </div>
        <div class="card-body p-4">
          <div class="row g-3">
            <div class="col-12">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Alamat</small>
              <strong>{{ $registration->alamat ?? '-' }}</strong>
            </div>
            <div class="col-md-3">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Kode Pos</small>
              <strong>{{ $registration->kode_pos ?? '-' }}</strong>
            </div>
            <div class="col-md-4">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">No. HP</small>
              <strong>{{ $registration->no_hp ?? '-' }}</strong>
            </div>
            <div class="col-md-5">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Email</small>
              <strong>{{ $registration->email ?? '-' }}</strong>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Pendidikan -->
      <div class="card border-1 shadow-sm mb-4">
        <div class="card-header bg-light py-3">
          <h5 class="fw-bold mb-0">
            <i class="ti ti-school text-primary me-2"></i>Pendidikan Terakhir
          </h5>
        </div>
        <div class="card-body p-4">
          <div class="row g-3">
            <div class="col-md-6">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Nama Sekolah</small>
              <strong>{{ $registration->nama_sekolah ?? '-' }}</strong>
            </div>
            <div class="col-md-4">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Jurusan</small>
              <strong>{{ $registration->jurusan ?? '-' }}</strong>
            </div>
            <div class="col-md-2">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Tahun Lulus</small>
              <strong>{{ $registration->tahun_lulus ?? '-' }}</strong>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Program Studi & Jalur -->
      <div class="card border-1 shadow-sm mb-4">
        <div class="card-header bg-light py-3">
          <h5 class="fw-bold mb-0">
            <i class="ti ti-book text-primary me-2"></i>Program Studi & Jalur Pendaftaran
          </h5>
        </div>
        <div class="card-body p-4">
          <div class="row g-3">
            @if($registration->programStudi1)
            <div class="col-md-6">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Pilihan 1</small>
              <strong>{{ $registration->programStudi1->nama }} ({{ $registration->programStudi1->jenjang }})</strong>
            </div>
            @endif
            @if($registration->programStudi2)
            <div class="col-md-6">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Pilihan 2</small>
              <strong>{{ $registration->programStudi2->nama }} ({{ $registration->programStudi2->jenjang }})</strong>
            </div>
            @endif
            @if($registration->registrationPath)
            <div class="col-md-6">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Jalur Pendaftaran</small>
              <span class="badge bg-primary-subtle text-primary px-3 py-2">{{ $registration->registrationPath->name }}</span>
            </div>
            @endif
            <div class="col-md-6">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Status Pendaftaran</small>
              <span class="badge bg-success-subtle text-success px-3 py-2">Selesai</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Hasil Ujian -->
      @if($examResult)
      <div class="card border-1 shadow-sm mb-4">
        <div class="card-header bg-light py-3">
          <h5 class="fw-bold mb-0">
            <i class="ti ti-pencil text-primary me-2"></i>Hasil Ujian
          </h5>
        </div>
        <div class="card-body p-4">
          <div class="row g-3">
            <div class="col-md-3 text-center">
              <div class="rounded-circle bg-{{ $examResult->score >= 60 ? 'success' : ($examResult->score >= 40 ? 'warning' : 'danger') }}-subtle d-inline-flex align-items-center justify-content-center mb-2" style="width: 70px; height: 70px;">
                <span class="fw-bold fs-4 text-{{ $examResult->score >= 60 ? 'success' : ($examResult->score >= 40 ? 'warning' : 'danger') }}">{{ number_format($examResult->score, 0) }}%</span>
              </div>
              <small class="text-muted d-block">Skor Akhir</small>
            </div>
            <div class="col-md-3">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Benar</small>
              <strong class="text-success">{{ $examResult->correct_answers }}</strong>
            </div>
            <div class="col-md-3">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Salah</small>
              <strong class="text-danger">{{ $examResult->wrong_answers }}</strong>
            </div>
            <div class="col-md-3">
              <small class="text-muted d-block text-uppercase" style="font-size: 0.75rem;">Total Soal</small>
              <strong>{{ $examResult->total_questions }}</strong>
            </div>
            <div class="col-12 mt-2">
              <small class="text-muted d-block">Durasi: {{ $examResult->duration_formatted }}</small>
            </div>
          </div>
        </div>
      </div>
      @endif

      <!-- Dokumen Terupload -->
      @if($documentLabels && count($documentLabels) > 0)
      <div class="card border-1 shadow-sm mb-4">
        <div class="card-header bg-light py-3">
          <h5 class="fw-bold mb-0">
            <i class="ti ti-file text-primary me-2"></i>Dokumen Terupload
          </h5>
        </div>
        <div class="card-body p-4">
          <div class="row g-3">
            @foreach($documentLabels as $type => $label)
              @php $doc = isset($documents[$type]) ? $documents[$type] : null; @endphp
              <div class="col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light">
                  <i class="ti ti-file-check text-success fs-4"></i>
                  <div>
                    <strong style="font-size: 0.85rem;">{{ $label }}</strong>
                    <small class="text-muted d-block">
                      @if($doc)
                        {{ $doc->file_size_formatted ?? 'Sudah diunggah' }}
                      @else
                        <span class="text-warning">Belum diunggah</span>
                      @endif
                    </small>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      @endif

      <!-- Action Buttons -->
      <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('daftar-pmb.steps', $path?->code) }}" class="btn btn-outline-secondary px-4">
          <i class="ti ti-arrow-left me-1"></i> Kembali ke Alur
        </a>
        <a href="{{ route('home') }}" class="btn btn-primary px-4">
          Ke Dashboard <i class="ti ti-arrow-right ms-1"></i>
        </a>
      </div>

      @else
      <div class="alert alert-warning">
        <i class="ti ti-alert-triangle me-2"></i> Data pendaftaran tidak ditemukan.
      </div>
      @endif
    </div>
  </div>
</main>

<style>
  .stepper-item-sm { display: flex; flex-direction: column; align-items: center; gap: 4px; }
  .stepper-circle-sm { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; }
  .stepper-label-sm { font-size: 0.7rem; white-space: nowrap; }
  .stepper-line-sm { width: 50px; height: 3px; background: #dee2e6; margin-bottom: 20px; }
  .stepper-item-sm.current .stepper-circle-sm { box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.25); }
</style>
@endsection