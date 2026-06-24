@extends('layouts.app')

@section('content')
@component('components.data-page-layout')
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Tagihan Pembayaran', 'active' => true],
    ])
    @slot('title', 'Tagihan Pembayaran')
    @slot('description', 'Kelola dan verifikasi pembayaran dari calon mahasiswa.')
    @slot('filters')
        <div class="col-md-5 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Nama / No. Invoice..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Berhasil</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
            </select>
        </div>
        <div class="col-md-2 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('settings.tagihan.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('settings.tagihan.index') }}?export=xls'">
            <i class="ti ti-file-spreadsheet"></i> .xls
        </a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
            <i class="ti ti-printer"></i> Print
        </a>
    @endslot
    @slot('table')
        <table class="table align-middle text-nowrap mb-0 table-hover table-ead">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="py-3">No. Invoice</th>
                    <th scope="col" class="py-3">Nama Pendaftar</th>
                    <th scope="col" class="py-3">Jalur</th>
                    <th scope="col" class="py-3 text-end">Nominal</th>
                    <th scope="col" class="py-3">Status</th>
                    <th scope="col" class="py-3">Batas Bayar</th>
                    <th scope="col" class="py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
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
                            <span class="small {{ $payment->isExpired() && $payment->transaction_status == 'pending' ? 'text-danger' : 'text-muted' }}">
                                {{ $payment->expired_at->format('d/m/Y H:i') }}
                            </span>
                        @else
                            <span class="text-muted small">-</span>
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
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="ti ti-receipt-off text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-2 mb-0 text-muted">Belum ada data tagihan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($payments->hasPages())
            <div class="card-footer bg-white border-0 py-3">{{ $payments->links() }}</div>
        @endif
    @endslot
@endcomponent
@endsection