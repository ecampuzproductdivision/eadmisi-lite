@extends('layouts.app')

@section('content')
<main class="p-6">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
      <h4 class="fw-bold mb-1">Riwayat Pendaftaran</h4>
      <p class="text-muted mb-0 small">Daftar pendaftaran yang telah Anda lakukan</p>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if($registrations->isEmpty())
    <!-- Empty State -->
    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-body text-center py-5">
        <i class="ti ti-inbox text-muted" style="font-size: 3rem;"></i>
        <h5 class="mt-3 text-muted">Belum ada pendaftaran</h5>
        <p class="text-muted small mb-3">Anda belum melakukan pendaftaran melalui jalur PMB.</p>
        <a href="{{ route('daftar-pmb') }}" class="btn btn-primary">
          <i class="ti ti-plus me-1"></i> Daftar Sekarang
        </a>
      </div>
    </div>
  @else
    <div class="row g-4">
      @foreach($registrations as $registration)
        @php
          $statusBadge = [
            'submitted' => ['bg-warning', 'text-dark', 'Submitted'],
            'documents_uploaded' => ['bg-info', 'text-dark', 'Dokumen Diupload'],
            'payment_pending' => ['bg-warning', 'text-dark', 'Menunggu Pembayaran'],
            'payment_verified' => ['bg-success', 'text-dark', 'Pembayaran Terverifikasi'],
            'exam_completed' => ['bg-primary', 'text-white', 'Ujian Selesai'],
            'reviewed' => ['bg-secondary', 'text-white', 'Direview'],
            'accepted' => ['bg-success', 'text-white', 'Diterima'],
            'rejected' => ['bg-danger', 'text-white', 'Ditolak'],
          ];
          $badge = $statusBadge[$registration->status] ?? ['bg-secondary', 'text-white', $registration->status];
        @endphp
        <div class="col-md-6 col-lg-4">
          <div class="card shadow-sm border-0 rounded-3 h-100">
            <div class="card-body d-flex flex-column">
              <!-- Header -->
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="avatar avatar-md bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px;">
                  <i class="ti ti-clipboard-list text-primary fs-4"></i>
                </div>
                <div class="min-w-0">
                  <h6 class="fw-bold mb-1 text-truncate">{{ $registration->registrationPath?->name ?? 'Tidak diketahui' }}</h6>
                  <small class="text-muted">{{ $registration->created_at->format('d/m/Y H:i') }}</small>
                </div>
              </div>

              <!-- Status -->
              <div class="mb-3">
                <span class="badge {{ $badge[0] }} {{ $badge[1] }} rounded-pill px-3 py-1 fw-semibold">
                  {{ $badge[2] }}
                </span>
              </div>

              <!-- Informasi Ringkas -->
              <div class="mb-3 small">
                <div class="d-flex justify-content-between mb-1">
                  <span class="text-muted">Pilihan 1</span>
                  <span class="fw-semibold text-end" style="max-width: 60%;">{{ $registration->programStudi1?->nama ?? '-' }}</span>
                </div>
                @if($registration->programStudi2)
                <div class="d-flex justify-content-between mb-1">
                  <span class="text-muted">Pilihan 2</span>
                  <span class="fw-semibold text-end" style="max-width: 60%;">{{ $registration->programStudi2?->nama ?? '-' }}</span>
                </div>
                @endif
                @if($registration->payments->isNotEmpty())
                  @php $latestPayment = $registration->payments->sortByDesc('created_at')->first(); @endphp
                  <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Biaya</span>
                    <span class="fw-semibold">Rp {{ number_format($latestPayment->amount, 0, ',', '.') }}</span>
                  </div>
                @elseif($registration->registrationPath?->fee)
                  <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Biaya</span>
                    <span class="fw-semibold">Rp {{ number_format($registration->registrationPath->fee, 0, ',', '.') }}</span>
                  </div>
                @endif
              </div>

              <!-- Spacer -->
              <div class="mt-auto">
                <hr class="my-3">
                <a href="{{ route('riwayat-pendaftaran.show', $registration->id) }}" class="btn btn-outline-primary btn-sm w-100">
                  <i class="ti ti-eye me-1"></i> Lihat Detail
                </a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</main>
@endsection