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

      <!-- Documents Card -->
      <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-light border-bottom d-flex align-items-center gap-2 py-3">
          <i class="ti ti-file"></i>
          <h6 class="fw-bold mb-0">Dokumen</h6>
        </div>
        <div class="card-body">
          @php
            $documentLabels = [
              'foto_formal' => 'Foto Formal',
              'ijazah' => 'Ijazah / SKHUN',
              'kartu_keluarga' => 'Kartu Keluarga',
              'akta_kelahiran' => 'Akta Kelahiran',
            ];
            // Key documents by type for easy lookup
            $docsByType = $registration->documents->keyBy('type');
          @endphp
          <div class="row g-3">
            @foreach($documentLabels as $type => $label)
              @php $doc = $docsByType->get($type); @endphp
              <div class="col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 border rounded-3">
                  <div class="flex-shrink-0">
                    @if($doc)
                      <i class="ti ti-file-check text-success fs-2"></i>
                    @else
                      <i class="ti ti-file-off text-muted fs-2"></i>
                    @endif
                  </div>
                  <div>
                    <p class="fw-semibold mb-1 small">{{ $label }}</p>
                    @if($doc)
                      <small class="text-success">Sudah diupload</small>
                      <br>
                      <a href="{{ $doc->url }}" target="_blank" class="small text-decoration-none">
                        <i class="ti ti-download me-1"></i> Download
                      </a>
                    @else
                      <small class="text-muted">Belum diupload</small>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
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
