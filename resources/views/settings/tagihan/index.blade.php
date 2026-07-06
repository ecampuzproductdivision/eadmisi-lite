@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $payments])
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
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-white border"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('settings.tagihan.index') }}" class="btn btn-white border px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
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
    @endslot
@endcomponent

@include('components.infinite-scroll-script', [
    'tableBodyId' => 'payment-table-body',
    'spinnerId' => 'loading-spinner',
    'nextPageUrl' => $payments->nextPageUrl(),
    'hasMore' => $payments->hasMorePages(),
])
@endsection
