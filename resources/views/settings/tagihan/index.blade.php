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

    @slot('cards')
    <div class="card border-1 shadow-sm">
        <div class="card-header bg-transparent px-4 pt-3 pb-0 border-bottom">
            <ul class="nav nav-tabs card-header-tabs mb-0" id="tagihanTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tab == 'pendaftaran' ? 'active' : '' }}" 
                            id="tab-pendaftaran" 
                            data-bs-toggle="tab" 
                            data-bs-target="#panel-pendaftaran" 
                            type="button" 
                            role="tab"
                            onclick="switchTab('pendaftaran')">
                        <i class="ti ti-file-text me-2"></i>Tagihan Pendaftaran
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tab == 'registrasi_ulang' ? 'active' : '' }}" 
                            id="tab-registrasi-ulang" 
                            data-bs-toggle="tab" 
                            data-bs-target="#panel-registrasi-ulang" 
                            type="button" 
                            role="tab"
                            onclick="switchTab('registrasi_ulang')">
                        <i class="ti ti-refresh me-2"></i>Tagihan Registrasi Ulang
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-4">
            <div class="tab-content" id="tagihanTabContent">
                <div class="tab-pane fade show active" id="panel-{{ $tab }}" role="tabpanel">
                    <form method="GET" action="{{ route('settings.tagihan.index') }}" id="filterForm">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <div class="row g-2 mb-3 align-items-end">
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
                            <div class="col-md-4 col-12 d-flex gap-2">
                                <button type="submit" class="btn btn-white border"><i class="ti ti-filter"></i> Terapkan</button>
                                <a href="{{ route('settings.tagihan.index', ['tab' => $tab]) }}" class="btn btn-white border px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
                                <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="alert('Export .xls untuk tab aktif')">
                                    <i class="ti ti-file-spreadsheet"></i> .xls
                                </a>
                                <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
                                    <i class="ti ti-printer"></i> Print
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="card border shadow-sm">
                        <div class="card-header bg-transparent px-4 py-3 d-flex align-items-center">
                            <h5 class="fw-bold mb-0"><i class="ti ti-wallet me-2"></i>Daftar Pembayaran</h5>
                        </div>
                        <div class="card-body data-page-table-container p-0">
                            @include('components.ajax-sort-script', ['tableBodyId' => 'payment-table-body'])
                            <table class="table table-hover align-middle mb-0 table-ead">
                                <thead class="bg-light">
                                    <tr>
                                        <x-sortable-header field="invoice_number" label="No. Invoice" />
                                        <th scope="col" class="py-3">Nama Pendaftar</th>
                                        <th scope="col" class="py-3">Jalur</th>
                                        <th scope="col" class="py-3 text-end">Nominal</th>
                                        <x-sortable-header field="transaction_status" label="Status" width="100px" />
                                        <th scope="col" class="py-3">Batas Bayar</th>
                                        <th scope="col" class="py-3 text-center" style="width:80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="payment-table-body">
                                    @include('settings.tagihan.partials.payment_rows')
                                </tbody>
                            </table>
                            <div id="loading-spinner" class="d-none text-center py-3">
                                <div class="spinner-border text-primary" role="status" style="width: 1.5rem; height: 1.5rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endslot
@endcomponent

@include('components.infinite-scroll-script', [
    'tableBodyId' => 'payment-table-body',
    'spinnerId' => 'loading-spinner',
    'nextPageUrl' => $payments->nextPageUrl(),
    'hasMore' => $payments->hasMorePages(),
])

@push('scripts')
<script>
function switchTab(tab) {
    document.querySelector('input[name="tab"]').value = tab;
    document.getElementById('filterForm').submit();
}
</script>
@endpush
@endsection