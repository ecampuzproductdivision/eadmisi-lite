@extends('layouts.app')

@section('content')
<main class="p-6">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('pendaftaran.index') }}">Pendaftaran PMB</a></li>
      <li class="breadcrumb-item active" aria-current="page">Detail Pendaftar</li>
    </ol>
  </nav>

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div class="d-flex align-items-center gap-3">
      <div class="avatar avatar-md bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
        <span class="fw-bold text-primary fs-5">{{ strtoupper(substr($registration->nama_lengkap, 0, 1)) }}</span>
      </div>
      <div>
        <h4 class="fw-bold mb-1">{{ $registration->nama_lengkap }}</h4>
        <p class="text-muted small mb-0">
          {{ $registration->registrationPath?->name ?? '-' }} &middot; 
          {{ $registration->created_at->format('d/m/Y H:i') }}
        </p>
      </div>
    </div>
    <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="ti ti-arrow-left me-1"></i> Kembali
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="row g-4">
    <!-- Left Column: Biodata -->
    <div class="col-lg-8">
      <!-- Status Card -->
      <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body">
          <div class="d-flex align-items-center gap-2 mb-3">
            <i class="ti ti-flag"></i>
            <h6 class="fw-bold mb-0">Status Pendaftaran</h6>
          </div>
          @php
            $statusBadge = [
              'submitted' => ['bg-warning', 'text-dark', 'Submitted', 'Data telah disubmit menunggu upload dokumen'],
              'documents_uploaded' => ['bg-info', 'text-dark', 'Dokumen Diupload', 'Dokumen telah diupload, menunggu pembayaran'],
              'payment_pending' => ['bg-info', 'text-dark', 'Menunggu Pembayaran', 'Menunggu pembayaran diverifikasi'],
              'payment_verified' => ['bg-success', 'text-dark', 'Pembayaran Terverifikasi', 'Pembayaran sudah diverifikasi, bisa mengikuti tes online'],
              'exam_completed' => ['bg-primary', 'text-white', 'Ujian Selesai', 'Ujian telah selesai, menunggu review'],
              'reviewed' => ['bg-secondary', 'text-white', 'Direview', 'Sedang dalam proses review'],
              'accepted' => ['bg-success', 'text-white', 'Diterima', 'Pendaftaran telah diterima'],
              'rejected' => ['bg-danger', 'text-white', 'Ditolak', 'Pendaftaran ditolak'],
              'Menunggu Verifikasi Registrasi Ulang' => ['bg-warning', 'text-dark', 'Verifikasi Registrasi Ulang', 'Menunggu verifikasi data registrasi ulang'],
              'registered' => ['bg-success', 'text-white', 'Terregistrasi', 'Pendaftar telah terregistrasi sebagai mahasiswa'],
            ];
            $badge = $statusBadge[$registration->status] ?? ['bg-secondary', 'text-white', $registration->status, ''];
          @endphp
          <div class="d-flex align-items-center gap-3">
            <span class="badge {{ $badge[0] }} {{ $badge[1] }} rounded-pill px-4 py-2 fw-semibold fs-6">
              {{ $badge[2] }}
            </span>
            <small class="text-muted">{{ $badge[3] }}</small>
          </div>
          
          @if($registration->status === 'payment_pending')
            @php
              $pendingPayment = $registration->payments()->where('transaction_status', 'pending')->first();
            @endphp
            @if($pendingPayment)
            <hr>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-2">
              <div>
                <small class="text-muted d-block">Invoice: <strong>{{ $pendingPayment->invoice_number }}</strong></small>
                <small class="text-muted d-block">Rp {{ number_format($pendingPayment->amount, 0, ',', '.') }}</small>
                @if($pendingPayment->expired_at)
                  <small class="text-danger d-block">Batas: {{ $pendingPayment->expired_at->format('d/m/Y H:i') }}</small>
                @endif
              </div>
              <form action="{{ route('payment.manual-verify', $pendingPayment->id) }}" method="POST" onsubmit="return confirm('Yakin ingin memverifikasi pembayaran ini?')">
                @csrf
                <button type="submit" class="btn btn-success btn-sm">
                  <i class="ti ti-check me-1"></i> Verifikasi Pembayaran
                </button>
              </form>
            </div>
            @endif
          @endif
        </div>
      </div>

      @if($registration->status === 'Menunggu Verifikasi Registrasi Ulang' || $registration->status === 'registered' || $registration->re_registration_submitted_at)
      <!-- Card Registrasi Ulang -->
      <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-warning-subtle border-bottom d-flex align-items-center gap-2 py-3">
          <i class="ti ti-id-badge text-warning fs-4"></i>
          <h6 class="fw-bold mb-0 text-warning-emphasis">Data Registrasi Ulang (PDDikti & EMIS Sync)</h6>
        </div>
        <div class="card-body">
          @if($registration->status === 'Menunggu Verifikasi Registrasi Ulang')
            <div class="alert alert-warning border-0 shadow-none mb-4 d-flex gap-3">
              <i class="ti ti-alert-triangle fs-3 mt-1"></i>
              <div>
                <h6 class="fw-bold mb-1">Persetujuan Registrasi Ulang Diperlukan</h6>
                <p class="small mb-3">Calon mahasiswa telah mengirimkan data registrasi ulang. Masukkan NIM untuk menyetujui dan mengaktifkan status mahasiswa.</p>
                <form action="{{ route('pendaftaran.verify-re-registration', $registration->id) }}" method="POST" class="row g-2 align-items-center">
                  @csrf
                  <div class="col-sm-6 col-md-5">
                    <input type="text" name="nim" class="form-control form-control-sm" placeholder="Nomor Induk Mahasiswa (NIM)" required>
                  </div>
                  <div class="col-sm-6">
                    <button type="submit" class="btn btn-success btn-sm w-100 w-sm-auto">
                      <i class="ti ti-check-double me-1"></i> Setujui & Generate NIM
                    </button>
                  </div>
                </form>
              </div>
            </div>
          @elseif($registration->status === 'registered')
            <div class="alert alert-success border-0 shadow-none mb-4 d-flex align-items-center gap-3">
              <i class="ti ti-circle-check fs-3"></i>
              <div>
                <h6 class="fw-bold mb-0">Registrasi Ulang Terverifikasi</h6>
                <p class="small mb-0">Mahasiswa telah terregistrasi secara resmi dengan NIM: <strong class="fs-5">{{ $registration->nim }}</strong></p>
              </div>
            </div>
          @endif

          <div class="row g-3">
            <div class="col-md-6">
              <label class="small text-muted mb-1">Nama Lengkap</label>
              <p class="fw-semibold mb-0">{{ $registration->nama_lengkap }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Jenis Kelamin</label>
              <p class="fw-semibold mb-0">{{ $registration->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Tempat & Tanggal Lahir</label>
              <p class="fw-semibold mb-0">{{ $registration->tempat_lahir }}, {{ $registration->tanggal_lahir ? \Carbon\Carbon::parse($registration->tanggal_lahir)->format('d/m/Y') : '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Agama</label>
              <p class="fw-semibold mb-0">{{ $registration->agama }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">NIK (16 Digit)</label>
              <p class="fw-semibold mb-0">{{ $registration->nik }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">NISN (10 Digit)</label>
              <p class="fw-semibold mb-0">{{ $registration->nisn ?? '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Nama Ibu Kandung</label>
              <p class="fw-semibold mb-0">{{ $registration->nama_ibu_kandung ?? '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Kewarganegaraan</label>
              <p class="fw-semibold mb-0">{{ $registration->kewarganegaraan ?? '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Penerima KPS / Kebutuhan Khusus</label>
              <p class="fw-semibold mb-0">KPS: {{ $registration->penerima_kps ?? '-' }} / Kebutuhan Khusus: {{ $registration->kebutuhan_khusus ?? '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Kabupaten</label>
              <p class="fw-semibold mb-0">{{ $registration->regency ? ($registration->regency->type . ' ' . $registration->regency->name) : '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Kecamatan</label>
              <p class="fw-semibold mb-0">{{ $registration->kecamatan?->name ?? '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Kelurahan</label>
              <p class="fw-semibold mb-0">{{ $registration->kelurahan?->name ?? '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">No. Handphone</label>
              <p class="fw-semibold mb-0">{{ $registration->no_hp }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Alamat Email</label>
              <p class="fw-semibold mb-0">{{ $registration->email }}</p>
            </div>
            @if($registration->re_registration_submitted_at)
              <div class="col-12 mt-2">
                <small class="text-muted">Data disubmit pada: {{ \Carbon\Carbon::parse($registration->re_registration_submitted_at)->format('d/m/Y H:i') }}</small>
              </div>
            @endif
          </div>
        </div>
      </div>
      @endif

      <!-- Biodata Card -->
      <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-light border-bottom d-flex align-items-center gap-2 py-3">
          <i class="ti ti-user"></i>
          <h6 class="fw-bold mb-0">Biodata Pribadi</h6>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="small text-muted mb-1">Nama Lengkap</label>
              <p class="fw-semibold mb-0">{{ $registration->nama_lengkap }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">NIK</label>
              <p class="fw-semibold mb-0">{{ $registration->nik ?? '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Tempat Lahir</label>
              <p class="fw-semibold mb-0">{{ $registration->tempat_lahir ?? '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Tanggal Lahir</label>
              <p class="fw-semibold mb-0">{{ $registration->tanggal_lahir ? \Carbon\Carbon::parse($registration->tanggal_lahir)->format('d/m/Y') : '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Jenis Kelamin</label>
              <p class="fw-semibold mb-0">
                @if($registration->jenis_kelamin == 'L') Laki-laki
                @elseif($registration->jenis_kelamin == 'P') Perempuan
                @else -
                @endif
              </p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Agama</label>
              <p class="fw-semibold mb-0">{{ $registration->agama ?? '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">No. HP</label>
              <p class="fw-semibold mb-0">{{ $registration->no_hp ?? '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Email</label>
              <p class="fw-semibold mb-0">{{ $registration->email ?? '-' }}</p>
            </div>
            <div class="col-12">
              <label class="small text-muted mb-1">Alamat</label>
              <p class="fw-semibold mb-0">{{ $registration->alamat ?? '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Kode Pos</label>
              <p class="fw-semibold mb-0">{{ $registration->kode_pos ?? '-' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Pendidikan Terakhir Card -->
      <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-light border-bottom d-flex align-items-center gap-2 py-3">
          <i class="ti ti-school"></i>
          <h6 class="fw-bold mb-0">Pendidikan Terakhir</h6>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="small text-muted mb-1">Nama Sekolah</label>
              <p class="fw-semibold mb-0">{{ $registration->nama_sekolah ?? '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Jurusan</label>
              <p class="fw-semibold mb-0">{{ $registration->jurusan ?? '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Tahun Lulus</label>
              <p class="fw-semibold mb-0">{{ $registration->tahun_lulus ?? '-' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Pilihan Program Studi Card -->
      <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-light border-bottom d-flex align-items-center gap-2 py-3">
          <i class="ti ti-book"></i>
          <h6 class="fw-bold mb-0">Pilihan Program Studi</h6>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="small text-muted mb-1">Pilihan 1</label>
              <p class="fw-semibold mb-0">{{ $registration->programStudi1?->nama ?? '-' }}</p>
            </div>
            <div class="col-md-6">
              <label class="small text-muted mb-1">Pilihan 2</label>
              <p class="fw-semibold mb-0">{{ $registration->programStudi2?->nama ?? '-' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Documents Card — Dynamically rendered from path-specific TemplateBerkas -->
      <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-light border-bottom d-flex align-items-center gap-2 py-3">
          <i class="ti ti-file"></i>
          <h6 class="fw-bold mb-0">Dokumen Persyaratan</h6>
        </div>
        <div class="card-body">
          @if($requiredDocuments->isEmpty())
            <p class="text-muted small mb-0">Jalur ini tidak memerlukan dokumen tambahan.</p>
          @else
            <div class="row g-3">
              @foreach($requiredDocuments as $requirement)
                @php
                  // Generate the type slug matching how RegistrationDocument stores it
                  $typeSlug = \Illuminate\Support\Str::slug($requirement->nama_dokumen, '_');
                  $uploadedFile = $uploadedDocuments->get($typeSlug);
                @endphp
                <div class="col-md-6">
                  <div class="d-flex align-items-center gap-3 p-3 border rounded-3">
                    <div class="flex-shrink-0">
                      @if($uploadedFile)
                        <i class="ti ti-file-check text-success fs-2"></i>
                      @else
                        <i class="ti ti-file-off text-muted fs-2"></i>
                      @endif
                    </div>
                    <div>
                      <p class="fw-semibold mb-1 small">{{ $requirement->nama_dokumen }}</p>
                      @if($uploadedFile)
                        <small class="text-success">Sudah diupload</small>
                        <br>
                        <a href="{{ asset('storage/' . $uploadedFile->file_path) }}" target="_blank" class="small text-decoration-none">
                          <i class="ti ti-download me-1"></i> Download
                        </a>
                      @else
                        <small class="text-muted">Belum diupload</small>
                        @if($requirement->status_wajib)
                          <br><small class="text-danger">Wajib</small>
                        @endif
                      @endif
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Right Column: Info Akun -->
    <div class="col-lg-4">
      <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-light border-bottom d-flex align-items-center gap-2 py-3">
          <i class="ti ti-user-circle"></i>
          <h6 class="fw-bold mb-0">Informasi Akun</h6>
        </div>
        <div class="card-body">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="avatar bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
              <span class="fw-bold text-primary">{{ strtoupper(substr($registration->user?->name ?? '?', 0, 1)) }}</span>
            </div>
            <div>
              <p class="fw-semibold mb-0 small">{{ $registration->user?->name ?? 'Tidak diketahui' }}</p>
              <small class="text-muted">{{ $registration->user?->email ?? '-' }}</small>
            </div>
          </div>
          <hr>
          <div class="mb-2">
            <small class="text-muted d-block">Akun dibuat</small>
            <span class="fw-semibold small">{{ $registration->user?->created_at?->format('d/m/Y H:i') ?? '-' }}</span>
          </div>
          <div class="mb-2">
            <small class="text-muted d-block">Pendaftaran dibuat</small>
            <span class="fw-semibold small">{{ $registration->created_at->format('d/m/Y H:i') }}</span>
          </div>
          <div>
            <small class="text-muted d-block">Terakhir diupdate</small>
            <span class="fw-semibold small">{{ $registration->updated_at->format('d/m/Y H:i') }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
