@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row justify-content-center">
    <div class="col-lg-10">

      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
          <h2 class="fw-bold mb-1">Tagihan Pembayaran</h2>
          <p class="text-muted mb-0">Kelola dan verifikasi pembayaran dari calon mahasiswa.</p>
        </div>
      </div>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="ti ti-circle-check fs-4 me-2"></i>
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <!-- Filter & Search -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
          <form action="{{ route('settings.tagihan.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
              <label class="form-label small fw-semibold">Cari</label>
              <input type="text" name="search" class="form-control" placeholder="Nama / No. Invoice" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Status</label>
              <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Berhasil</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
              </select>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-search me-1"></i> Cari
              </button>
            </div>
            <div class="col-md-2">
              <a href="{{ route('settings.tagihan.index') }}" class="btn btn-outline-secondary w-100">
                Reset
              </a>
            </div>
          </form>
        </div>
      </div>

      <!-- Tagihan Table -->
      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
              <thead class="bg-light">
                <tr>
                  <th class="px-4 py-3 fw-semibold">No. Invoice</th>
                  <th class="px-4 py-3 fw-semibold">Nama Pendaftar</th>
                  <th class="px-4 py-3 fw-semibold">Jalur</th>
                  <th class="px-4 py-3 fw-semibold text-end">Nominal</th>
                  <th class="px-4 py-3 fw-semibold">Status</th>
                  <th class="px-4 py-3 fw-semibold">Batas Bayar</th>
                  <th class="px-4 py-3 fw-semibold text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($payments as $payment)
                <tr>
                  <td class="px-4">
                    <span class="fw-semibold">{{ $payment->invoice_number }}</span>
                  </td>
                  <td class="px-4">
                    <div class="d-flex align-items-center gap-2">
                      <div class="avatar bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <span class="fw-bold text-primary small">{{ strtoupper(substr($payment->registration?->nama_lengkap ?? '?', 0, 1)) }}</span>
                      </div>
                      <div>
                        <span class="fw-semibold small">{{ $payment->registration?->nama_lengkap ?? '-' }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="px-4">
                    <span class="small">{{ $payment->registration?->registrationPath?->name ?? '-' }}</span>
                  </td>
                  <td class="px-4 text-end">
                    <span class="fw-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                  </td>
                  <td class="px-4">
                    @php
                      $statusConfig = [
                        'pending' => ['warning', 'bg-warning-subtle', 'Menunggu'],
                        'success' => ['success', 'bg-success-subtle', 'Berhasil'],
                        'failed' => ['danger', 'bg-danger-subtle', 'Gagal'],
                        'expired' => ['secondary', 'bg-secondary-subtle', 'Kadaluarsa'],
                        'refund' => ['info', 'bg-info-subtle', 'Refund'],
                      ];
                      $cfg = $statusConfig[$payment->transaction_status] ?? ['secondary', 'bg-secondary-subtle', $payment->transaction_status];
                    @endphp
                    <span class="badge {{ $cfg[1] }} text-{{ $cfg[0] }} px-3 py-2">{{ $cfg[2] }}</span>
                  </td>
                  <td class="px-4">
                    @if($payment->expired_at)
                      <span class="small {{ $payment->isExpired() && $payment->transaction_status == 'pending' ? 'text-danger' : 'text-muted' }}">
                        {{ $payment->expired_at->format('d/m/Y H:i') }}
                      </span>
                    @else
                      <span class="text-muted small">-</span>
                    @endif
                  </td>
                  <td class="px-4 text-center">
                    @if($payment->transaction_status === 'pending')
                      <form action="{{ route('settings.tagihan.verify', $payment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Verifikasi pembayaran {{ $payment->invoice_number }}?')">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" title="Verifikasi Pembayaran">
                          <i class="ti ti-check"></i> Verifikasi
                        </button>
                      </form>
                    @elseif($payment->transaction_status === 'success')
                      <span class="badge bg-success-subtle text-success px-3 py-2">
                        <i class="ti ti-check me-1"></i> Lunas
                      </span>
                    @else
                      <span class="text-muted small">-</span>
                    @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="px-4 py-5 text-center">
                    <div class="mb-3">
                      <i class="ti ti-receipt-off text-muted" style="font-size: 2.5rem;"></i>
                    </div>
                    <h6 class="fw-bold text-muted mb-1">Belum Ada Data Tagihan</h6>
                    <small class="text-muted">Belum ada calon mahasiswa yang membuat invoice pembayaran.</small>
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        @if($payments->hasPages())
        <div class="card-footer bg-white border-top py-3">
          {{ $payments->links() }}
        </div>
        @endif
      </div>

    </div>
  </div>
</main>
@endsection