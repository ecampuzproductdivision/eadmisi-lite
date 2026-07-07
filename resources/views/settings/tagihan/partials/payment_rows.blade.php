@forelse($payments as $payment)
<tr>
    <td class="py-3">
        <span class="fw-semibold">{{ $payment->invoice_number }}</span>
    </td>
    <td class="py-3">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                <span class="fw-bold text-primary small">{{ strtoupper(substr($payment->registration?->nama_lengkap ?? '?', 0, 1)) }}</span>
            </div>
            <div>
                <span class="fw-semibold small">{{ $payment->registration?->nama_lengkap ?? '-' }}</span>
            </div>
        </div>
    </td>
    <td class="py-3">
        <span class="small">{{ $payment->registration?->registrationPath?->name ?? '-' }}</span>
    </td>
    <td class="py-3 text-end">
        <span class="fw-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
    </td>
    <td class="py-3">
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
    <td class="py-3">
        @if($payment->expired_at)
            <span class="{{ $payment->isExpired() && $payment->transaction_status == 'pending' ? 'text-danger' : 'text-muted' }}">
                {{ $payment->expired_at->format('d/m/Y H:i') }}
            </span>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>
    <td class="py-3 text-center">
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
            <span class="text-muted">-</span>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center py-5">
        @include('components.empty-state', [
            'icon' => 'ti-wallet-off',
            'title' => 'Belum Ada Pembayaran',
            'subtitle' => 'Pembayaran dari pendaftar akan muncul di sini setelah mereka melakukan transaksi.',
        ])
    </td>
</tr>
@endforelse
