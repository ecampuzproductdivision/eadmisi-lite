@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $periodes])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Periode Akademik', 'active' => true],
    ])
    @slot('title', 'Periode Akademik')
    @slot('description', 'Kelola tahun akademik dan semester aktif untuk pendaftaran mahasiswa baru.')
    @slot('actions')
        <a href="{{ route('periode.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Tambah Periode Baru
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-3 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari tahun akademik..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="semester" class="form-select">
                <option value="">-- Semester --</option>
                <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                <option value="Pendek" {{ request('semester') == 'Pendek' ? 'selected' : '' }}>Pendek</option>
            </select>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('periode.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('periode.index') }}?export=xls'">
            <i class="ti ti-file-spreadsheet"></i> .xls
        </a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
            <i class="ti ti-printer"></i> Print
        </a>
    @endslot
    @slot('spinner', true)
    @slot('spinnerId', 'periode-loading-spinner')
    @slot('sentinel', true)
    @slot('sentinelId', 'periode-scroll-sentinel')
    @slot('table')
        @include('components.ajax-sort-script', ['tableBodyId' => 'periode-table-body'])
        <table class="table align-middle table-hover table-ead">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="py-3" style="width: 60px;">No</th>
                    <x-sortable-header field="tahun_akademik" label="Tahun Akademik" />
                    <x-sortable-header field="semester" label="Periode Semester" />
                    <x-sortable-header field="status_aktif" label="Status Aktif" width="120px" />
                    <th scope="col" class="py-3 text-end" style="width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="periode-table-body">
                @include('periode.partials.periode_rows')
            </tbody>
        </table>
    @endslot
    @push('scripts')
        @include('components.infinite-scroll-script', [
            'tableBodyId' => 'periode-table-body',
            'spinnerId' => 'periode-loading-spinner',
            'sentinelId' => 'periode-scroll-sentinel',
            'nextPageUrl' => $nextPageUrl ?? '',
            'hasMore' => $hasMore ?? false,
        ])
    @endpush
@endcomponent
@endsection
