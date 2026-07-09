@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      <!-- Header -->
      <div class="row mb-4">
        <div class="col-12">
          <h2 class="fw-bold mb-1">Informasi Pembayaran Registrasi Ulang</h2>
          <p class="text-muted mb-0">Rincian biaya registrasi ulang dan status pembayaran Anda.</p>
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

      <!-- Student Profile Card -->
      <div class="card border-1 shadow-sm mb-4">
        <div class="card-body p-4">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
              <i class="ti ti-user text-primary fs-2"></i>
            </div>
            <div>
              <h5 class="fw-bold mb-1">{{ $registration->nama_lengkap }}</h5>
              <p class="text-muted mb-0 small">
                @if($path)
                  <span class="badge bg-primary bg-opacity-10 text-primary me-2">{{ $path->name }}</span>
                @endif
                @if($registration->programStudi1)
                  <span>{{ $registration->programStudi1->nama ?? '-' }}</span>
                @endif
              </p>
            </div>
            <div class="ms-auto">
              <span class="badge {{ $paymentStatus === 'Lunas' ? 'bg-success' : ($paymentStatus === 'Menunggu Pembayaran' ? 'bg-info' : 'bg-warning') }} px-3 py-2 fw-semibold fs-6">
                {{ $paymentStatus }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Itemized Cost Breakdown Table -->
      <div class="card border-1 shadow-sm mb-4">
        <div class="card-header bg-light py-3">
          <h5 class="fw-bold mb-0"><i class="ti ti-receipt text-primary me-2"></i>Rincian Biaya Registrasi Ulang</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-borderless mb-0">
              <thead class="bg-light">
                <tr>
                  <th class="ps-4 py-3 fw-semibold">Komponen Biaya</th>
                  <th class="pe-4 py-3 fw-semibold text-end" style="width: 200px;">Nominal (Rp)</th>
                </tr>
              </thead>
              <tbody>
                @forelse($biayaKomponens as $bk)
                <tr>
                  <td class="ps-4 py-3">
                    <span class="fw-medium">{{ $bk->komponenBiaya?->nama_komponen ?? 'Komponen #' . $bk->komponen_biaya_id }}</span>
                    @if($bk->komponenBiaya?->kode_komponen)
                      <br><small class="text-muted">{{ $bk->komponenBiaya->kode_komponen }}</small>
                    @endif
                  </td>
                  <td class="pe-4 py-3 text-end fw-semibold">
                    Rp {{ number_format($bk->nominal, 0, ',', '.') }}
                  </td>
                </tr>
                @empty
                <tr>
                  <td class="ps-4 py-3 text-muted" colspan="2">Belum ada komponen biaya yang ditetapkan untuk jalur ini.</td>
                </tr>
                @endforelse
              </tbody>
              <tfoot class="table-light border-top">
                <tr>
                  <td class="ps-4 py-3 fw-bold fs-5">Total Tagihan</td>
                  <td class="pe-4 py-3 text-end fw-bold fs-5 text-primary">
                    Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        @if($payment && $payment->invoice_number)
        <div class="card-footer bg-white border-top py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <small class="text-muted">No. Invoice:</small>
              <span class="fw-semibold ms-1">{{ $payment->invoice_number }}</span>
              @if($payment->expired_at)
                <br><small class="text-muted">Batas Pembayaran:</small>
                <span class="fw-semibold ms-1 text-danger">{{ $payment->expired_at->format('d/m/Y H:i') }}</span>
              @endif
            </div>
          </div>
        </div>
        @endif

        <!-- Bayar Button -->
        <div class="card-footer bg-white border-top py-3">
          @if($paymentStatus === 'Lunas')
            <div class="alert alert-success border-0 shadow-sm mb-0 d-flex align-items-center gap-3">
              <i class="ti ti-circle-check fs-3"></i>
              <div>
                <h6 class="fw-bold mb-1">Pembayaran Lunas</h6>
                <p class="small mb-0">Terima kasih, pembayaran registrasi ulang Anda telah lunas.</p>
              </div>
            </div>
          @elseif($paymentStatus === 'Menunggu Pembayaran' && $payment)
            <div class="alert alert-info border-0 shadow-sm mb-0 d-flex align-items-center gap-3">
              <i class="ti ti-clock fs-3"></i>
              <div>
                <h6 class="fw-bold mb-1">Menunggu Pembayaran</h6>
                <p class="small mb-0">Invoice sedang diproses. Silakan lakukan pembayaran.</p>
              </div>
            </div>
            <form action="{{ route('payment.invoice', $registration->id) }}" method="POST" class="mt-3">
              @csrf
              <button type="submit" class="btn btn-lg btn-success w-100">
                <i class="ti ti-credit-card me-2"></i> Bayar Sekarang
              </button>
            </form>
          @else
            <form action="{{ route('payment.invoice', $registration->id) }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-lg btn-success w-100" id="btn-initiate-payment">
                <i class="ti ti-credit-card me-2"></i> Bayar Sekarang
              </button>
            </form>
          @endif
        </div>
      </div>

      <!-- Info -->
      <div class="card border-1 shadow-sm mb-4 bg-info-subtle">
        <div class="card-body p-4">
          <div class="d-flex align-items-start gap-3">
            <i class="ti ti-info-circle fs-3 text-info flex-shrink-0 mt-1"></i>
            <div>
              <h6 class="fw-bold mb-2">Informasi Pembayaran</h6>
              <p class="mb-1" style="font-size: 0.9rem;">Pembayaran dapat dilakukan melalui transfer ke rekening kampus atau melalui kanal pembayaran yang tersedia.</p>
              <p class="mb-0" style="font-size: 0.9rem;">Setelah melakukan pembayaran, status akan terverifikasi secara otomatis.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="d-flex gap-3 mb-4">
        <a href="{{ route('daftar-pmb.steps', $path?->code ?? '') }}" class="btn btn-outline-secondary px-4">
          <i class="ti ti-arrow-left me-1"></i> Kembali ke Alur Pendaftaran
        </a>
      </div>

    </div>
  </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const payBtn = document.getElementById('btn-initiate-payment');
    if (payBtn) {
        payBtn.addEventListener('click', function(e) {
            // Optional: show loading state
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Memproses...';
        });
    }
});
</script>
@endpush