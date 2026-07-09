@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row justify-content-center">
    <div class="col-lg-10">

      <!-- Header -->
      <div class="row mb-4">
        <div class="col-12">
          <h2 class="fw-bold mb-1">Tagihan Pembayaran</h2>
          <p class="text-muted mb-0">Informasi tagihan dan status pembayaran Anda.</p>
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

      <!-- Bootstrap Tabs -->
      <ul class="nav nav-tabs mb-4" id="tagihanTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link {{ session('active_tab') === 'ulang' ? '' : 'active' }}" id="pendaftaran-tab" data-bs-toggle="tab" data-bs-target="#pendaftaran" type="button" role="tab" aria-controls="pendaftaran" aria-selected="true">
            <i class="ti ti-receipt me-1"></i> Biaya Pendaftaran / Formulir
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link {{ session('active_tab') === 'ulang' ? 'active' : '' }}" id="ulang-tab" data-bs-toggle="tab" data-bs-target="#ulang" type="button" role="tab" aria-controls="ulang" aria-selected="false">
            <i class="ti ti-id-badge me-1"></i> Biaya Registrasi Ulang (Mahasiswa Baru)
          </button>
        </li>
      </ul>

      <div class="tab-content" id="tagihanTabsContent">

        {{-- ============================================================ --}}
        {{-- TAB 1: Biaya Pendaftaran / Formulir --}}
        {{-- ============================================================ --}}
        <div class="tab-pane fade {{ session('active_tab') === 'ulang' ? '' : 'show active' }}" id="pendaftaran" role="tabpanel" aria-labelledby="pendaftaran-tab">
          @if($pendaftaranRegistrations->isEmpty())
            <div class="card border-1 shadow-sm">
              <div class="card-body p-5 text-center">
                <div class="mb-4">
                  <div class="rounded-circle bg-warning-subtle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="ti ti-receipt-off text-warning" style="font-size: 2rem;"></i>
                  </div>
                </div>
                <h4 class="fw-bold mb-2">Belum Ada Tagihan Pendaftaran</h4>
                <p class="text-muted mb-4">Anda belum memiliki tagihan pendaftaran. Silakan daftar PMB terlebih dahulu.</p>
                <a href="{{ route('daftar-pmb') }}" class="btn btn-primary px-4">
                  <i class="ti ti-arrow-right me-1"></i> Daftar PMB Sekarang
                </a>
              </div>
            </div>
          @else
            @foreach($pendaftaranRegistrations as $registration)
            @php
              $payment = $pendaftaranPayments->get($registration->id);
              $paymentStatus = 'Belum Dibayar';
              $badgeClass = 'bg-warning-subtle text-warning';
              if ($payment && $payment->transaction_status === 'success') {
                $paymentStatus = 'Lunas';
                $badgeClass = 'bg-success-subtle text-success';
              } elseif ($payment && $payment->transaction_status === 'pending') {
                $paymentStatus = 'Menunggu Pembayaran';
                $badgeClass = 'bg-info-subtle text-info';
              } elseif (in_array($registration->status, ['payment_verified', 'exam_completed', 'reviewed', 'accepted', 'Lulus'])) {
                $paymentStatus = 'Lunas';
                $badgeClass = 'bg-success-subtle text-success';
              }
              $fee = $registration->registrationPath?->fee ?? 0;
            @endphp
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
                  @if($payment && $payment->transaction_status === 'pending')
                    <span class="badge bg-info-subtle text-info px-3 py-2 align-self-center">
                      <i class="ti ti-clock me-1"></i> Menunggu pembayaran
                    </span>
                  @elseif($paymentStatus === 'Belum Dibayar')
                    <form action="{{ route('payment.invoice', $registration->id) }}" method="POST">
                      @csrf
                      <input type="hidden" name="payment_type" value="pendaftaran">
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
          @endif
        </div>

        {{-- ============================================================ --}}
        {{-- TAB 2: Biaya Registrasi Ulang (Mahasiswa Baru) --}}
        {{-- ============================================================ --}}
        <div class="tab-pane fade {{ session('active_tab') === 'ulang' ? 'show active' : '' }}" id="ulang" role="tabpanel" aria-labelledby="ulang-tab">
          @if($ulangRegistrations->isEmpty())
            <div class="card border-1 shadow-sm">
              <div class="card-body p-5 text-center">
                <div class="mb-4">
                  <div class="rounded-circle bg-info-subtle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="ti ti-id-badge-off text-info" style="font-size: 2rem;"></i>
                  </div>
                </div>
                <h4 class="fw-bold mb-2">Belum Ada Tagihan Registrasi Ulang</h4>
                <p class="text-muted mb-4">Anda belum memiliki tagihan registrasi ulang. Tagihan akan muncul setelah Anda dinyatakan Lulus Seleksi dan melakukan submit data registrasi ulang.</p>
              </div>
            </div>
          @else
            @foreach($ulangRegistrations as $registration)
            @php
              $payment = $ulangPayments->get($registration->id);
              $biayaList = $ulangBiayaKomponens[$registration->id] ?? collect();
              $totalBiaya = $biayaList->sum('nominal');
              
              $paymentStatus = 'Belum Dibayar';
              $badgeClass = 'bg-warning-subtle text-warning';
              if ($payment && $payment->transaction_status === 'success') {
                $paymentStatus = 'Lunas';
                $badgeClass = 'bg-success-subtle text-success';
              } elseif ($payment && $payment->transaction_status === 'pending') {
                $paymentStatus = 'Menunggu Pembayaran';
                $badgeClass = 'bg-info-subtle text-info';
              } elseif ($registration->status === 'registered') {
                $paymentStatus = 'Lunas';
                $badgeClass = 'bg-success-subtle text-success';
              }
            @endphp
            <div class="card border-1 shadow-sm mb-4">
              <div class="card-header bg-light py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="fw-bold mb-0">
                  <i class="ti ti-id-badge text-primary me-2"></i>{{ $registration->registrationPath?->name ?? '-' }} - Registrasi Ulang
                </h5>
                <span class="badge {{ $badgeClass }} px-3 py-2 fw-semibold">{{ $paymentStatus }}</span>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-borderless mb-0">
                    <thead class="table-light">
                      <tr>
                        <th class="ps-4 py-3 fw-semibold">Komponen Biaya</th>
                        <th class="pe-4 py-3 fw-semibold text-end" style="width: 200px;">Nominal (Rp)</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($biayaList as $bk)
                      <tr>
                        <td class="ps-4 py-3">
                          <span class="fw-medium">{{ $bk->komponenBiaya?->nama_komponen ?? 'Komponen #'.$bk->komponen_biaya_id }}</span>
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
                        <td class="ps-4 py-3 text-muted" colspan="2">Komponen biaya belum ditetapkan untuk jalur ini.</td>
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
              <div class="card-footer bg-white border-top py-3">
                @if($paymentStatus === 'Lunas')
                  <div class="alert alert-success border-0 shadow-sm mb-0 d-flex align-items-center gap-3">
                    <i class="ti ti-circle-check fs-3"></i>
                    <div>
                      <h6 class="fw-bold mb-1">Pembayaran Lunas</h6>
                      <p class="small mb-0">Terima kasih, pembayaran registrasi ulang Anda telah lunas.</p>
                    </div>
                  </div>
                @elseif($paymentStatus === 'Menunggu Pembayaran')
                  <div class="alert alert-info border-0 shadow-sm mb-0 d-flex align-items-center gap-3">
                    <i class="ti ti-clock fs-3"></i>
                    <div>
                      <h6 class="fw-bold mb-1">Menunggu Pembayaran</h6>
                      <p class="small mb-0">Invoice telah diterbitkan. Silakan lakukan pembayaran.</p>
                    </div>
                  </div>
                  <form action="{{ route('payment.invoice', $registration->id) }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="payment_type" value="registrasi_ulang">
                    <button type="submit" class="btn btn-lg btn-success w-100">
                      <i class="ti ti-credit-card me-2"></i> Bayar Sekarang
                    </button>
                  </form>
                @else
                  <form action="{{ route('payment.invoice', $registration->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_type" value="registrasi_ulang">
                    <button type="submit" class="btn btn-lg btn-success w-100">
                      <i class="ti ti-credit-card me-2"></i> Bayar Sekarang
                    </button>
                  </form>
                @endif
              </div>
            </div>
            @endforeach
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
              <p class="mb-0" style="font-size: 0.9rem;">Setelah melakukan pembayaran, status akan terverifikasi secara otomatis oleh sistem.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="d-flex gap-3 mb-4">
        <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4">
          <i class="ti ti-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-switch to "Registrasi Ulang" tab if session flag is set
    const activeTab = "{{ session('active_tab', 'pendaftaran') }}";
    if (activeTab === 'ulang') {
        const ulangTab = document.getElementById('ulang-tab');
        if (ulangTab) {
            ulangTab.click();
        }
    }
});
</script>
@endpush