@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      <!-- Header -->
      <div class="row mb-4">
        <div class="col-12">
          <h2 class="fw-bold mb-1">Tagihan Pendaftaran</h2>
          <p class="text-muted mb-0">Informasi tagihan biaya pendaftaran mahasiswa baru.</p>
        </div>
      </div>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="ti ti-circle-check fs-4 me-2"></i>
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if($registrations->isEmpty())
        <div class="card border-1 shadow-sm">
          <div class="card-body p-5 text-center">
            <div class="mb-4">
              <div class="rounded-circle bg-warning-subtle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                <i class="ti ti-receipt-off text-warning" style="font-size: 2rem;"></i>
              </div>
            </div>
            <h4 class="fw-bold mb-2">Belum Ada Tagihan</h4>
            <p class="text-muted mb-4">Anda belum memiliki tagihan pendaftaran. Silakan daftar PMB terlebih dahulu melalui menu "Daftar PMB".</p>
            <a href="{{ route('daftar-pmb') }}" class="btn btn-primary px-4">
              <i class="ti ti-arrow-right me-1"></i> Daftar PMB Sekarang
            </a>
          </div>
        </div>
      @else
        <!-- Header Info -->
        <div class="card border-1 shadow-sm mb-4">
          <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                <i class="ti ti-receipt text-primary fs-2"></i>
              </div>
              <div>
                <h5 class="fw-bold mb-1">Daftar Tagihan</h5>
                <p class="text-muted mb-0 small">Berikut adalah tagihan pendaftaran Anda berdasarkan jalur yang didaftarkan.</p>
              </div>
            </div>
          </div>
        </div>

        @php
          // Load payments for all registrations
          $regIds = $registrations->pluck('id');
          $payments = \App\Models\Payment::whereIn('registration_id', $regIds)
              ->whereIn('transaction_status', ['pending', 'success'])
              ->get()
              ->keyBy('registration_id');
        @endphp

        @foreach($registrations as $registration)
        @php
          $payment = $payments->get($registration->id);
          $paymentStatus = 'Belum Dibayar';
          $badgeClass = 'bg-warning-subtle text-warning';

          if ($registration->status === 'payment_pending') {
            $paymentStatus = 'Menunggu Pembayaran';
            $badgeClass = 'bg-info-subtle text-info';
          } elseif (in_array($registration->status, ['payment_verified', 'exam_completed', 'reviewed', 'accepted'])) {
            $paymentStatus = 'Lunas';
            $badgeClass = 'bg-success-subtle text-success';
          }

          $fee = $registration->registrationPath?->fee ?? 0;
        @endphp
        <!-- Tagihan Card per Jalur -->
        <div class="card border-1 shadow-sm mb-4">
          <div class="card-header bg-light py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="fw-bold mb-0">
              <i class="ti ti-road text-primary me-2"></i>{{ $registration->registrationPath?->name ?? '-' }}
            </h5>
            <span class="badge {{ $badgeClass }} px-3 py-2 fw-semibold">{{ $paymentStatus }}</span>
          </div>
          <div class="card-body p-4">
            <div class="table-responsive">
              <table class="table table-borderless mb-0">
                <tbody>
                  <tr>
                    <td class="ps-0 text-muted" style="width: 200px;">Program Studi Pilihan 1</td>
                    <td class="fw-semibold">{{ $registration->programStudi1?->nama ?? '-' }}</td>
                  </tr>
                  @if($registration->programStudi2)
                  <tr>
                    <td class="ps-0 text-muted">Program Studi Pilihan 2</td>
                    <td class="fw-semibold">{{ $registration->programStudi2?->nama ?? '-' }}</td>
                  </tr>
                  @endif
                  <tr>
                    <td class="ps-0 text-muted">Tanggal Daftar</td>
                    <td class="fw-semibold">{{ $registration->created_at->format('d/m/Y H:i') }}</td>
                  </tr>
                  @if($payment && $payment->invoice_number)
                  <tr>
                    <td class="ps-0 text-muted">No. Invoice</td>
                    <td class="fw-semibold">{{ $payment->invoice_number }}</td>
                  </tr>
                  @endif
                  @if($payment && $payment->expired_at)
                  <tr>
                    <td class="ps-0 text-muted">Batas Pembayaran</td>
                    <td class="fw-semibold text-danger">{{ $payment->expired_at->format('d/m/Y H:i') }}</td>
                  </tr>
                  @endif
                  <tr class="border-top">
                    <td class="ps-0 pt-3"><strong>Biaya Pendaftaran</strong></td>
                    <td class="fw-bold fs-5 pt-3">
                      @if($fee > 0)
                        Rp {{ number_format($fee, 0, ',', '.') }}
                      @else
                        <span class="text-muted">Gratis</span>
                      @endif
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-end gap-2">
              @if($registration->status === 'payment_pending' && $payment)
                <span class="badge bg-info-subtle text-info px-3 py-2 align-self-center">
                  <i class="ti ti-clock me-1"></i> Menunggu pembayaran
                </span>
                <!-- TODO: Integrasi dengan Midtrans/Finnet -->
                <!-- <a href="#" class="btn btn-success px-4" onclick="payWithMidtrans('{{ $payment->invoice_number }}')">
                  <i class="ti ti-credit-card me-1"></i> Lanjutkan Pembayaran
                </a> -->
              @elseif(!in_array($registration->status, ['payment_verified', 'exam_completed', 'reviewed', 'accepted']))
                <form action="{{ route('payment.invoice', $registration->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-success px-4">
                    <i class="ti ti-credit-card me-1"></i> Bayar Sekarang
                  </button>
                </form>
              @else
                <span class="badge bg-success-subtle text-success px-3 py-2 align-self-center">
                  <i class="ti ti-check me-1"></i> Lunas
                </span>
              @endif
            </div>
          </div>
        </div>
        @endforeach

        <!-- Informasi -->
        <div class="card border-1 shadow-sm mb-4 bg-info-subtle">
          <div class="card-body p-4">
            <div class="d-flex align-items-start gap-3">
              <i class="ti ti-info-circle fs-3 text-info flex-shrink-0 mt-1"></i>
              <div>
                <h6 class="fw-bold mb-2">Informasi Pembayaran</h6>
                <p class="mb-1" style="font-size: 0.9rem;">Pembayaran dapat dilakukan melalui transfer ke rekening yang akan diinformasikan lebih lanjut oleh pihak kampus.</p>
                <p class="mb-0" style="font-size: 0.9rem;">Setelah melakukan pembayaran, silakan hubungi admin untuk verifikasi pembayaran.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-3 mb-4">
          <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
        </div>
      @endif
    </div>
  </div>
</main>
@endsection
