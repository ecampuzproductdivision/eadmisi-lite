@extends('layouts.app')

@section('content')
<div class="p-2">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Settings</a></li>
            <li class="breadcrumb-item active">Tagihan Pembayaran</li>
        </ol>
    </nav>
    <hr>

    <div class="my-5">
        <h1 class="mb-1 fw-bold">Tagihan Pembayaran</h1>
        <p class="text-muted mb-0">Kelola dan verifikasi pembayaran dari calon mahasiswa.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check-circle fs-4 me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Navigation Tabs --}}
    <ul class="nav nav-tabs card-header-tabs mb-3" id="tagihanTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab == 'pendaftaran' ? 'active' : '' }}" 
                    id="tab-pendaftaran" 
                    data-bs-toggle="tab" 
                    data-bs-target="#panel-pendaftaran" 
                    type="button" 
                    role="tab"
                    onclick="switchTab('pendaftaran')">
                <i class="ti ti-file-text me-1"></i> Tagihan Pendaftaran
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
                <i class="ti ti-refresh me-1"></i> Tagihan Registrasi Ulang
            </button>
        </li>
    </ul>

    {{-- Tab Content --}}
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

            <div class="card border-1 shadow-sm">
                <div class="card-body p-0">
                    @include('components.ajax-sort-script', ['tableBodyId' => 'payment-table-body'])
                    <table class="table align-middle text-nowrap mb-0 table-hover table-ead">
                        <thead class="table-light">
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