@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $programStudis])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Program Studi', 'active' => true],
    ])
    @slot('title', 'Master Program Studi')
    @slot('description', 'Kelola data program studi untuk penerimaan mahasiswa baru.')
    @slot('actions')
        <a href="{{ route('program-studi.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Tambah Program Studi
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-3 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari kode atau nama prodi..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2 col-12">
            <select name="jenjang" class="form-select">
                <option value="">-- Jenjang --</option>
                <option value="D3" {{ request('jenjang') == 'D3' ? 'selected' : '' }}>D3</option>
                <option value="S1" {{ request('jenjang') == 'S1' ? 'selected' : '' }}>S1</option>
                <option value="S2" {{ request('jenjang') == 'S2' ? 'selected' : '' }}>S2</option>
            </select>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-white border"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('program-studi.index') }}" class="btn btn-white border px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('program-studi.index') }}?export=xls'">
            <i class="ti ti-file-spreadsheet"></i> .xls
        </a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
            <i class="ti ti-printer"></i> Print
        </a>
    @endslot
    @slot('table')
        @include('components.ajax-sort-script', ['tableBodyId' => 'prodi-table-body'])
        <table class="table table-hover align-middle table-ead">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="py-3" style="width: 60px;">No</th>
                    <x-sortable-header field="kode_prodi" label="Kode Prodi" />
                    <x-sortable-header field="nama_prodi" label="Nama Prodi" />
                    <x-sortable-header field="jurusan" label="Jurusan" />
                    <x-sortable-header field="jenjang_akademik" label="Jenjang" width="80px" />
                    <x-sortable-header field="passing_grade" label="Passing Grade" width="110px" />
                    <x-sortable-header field="program" label="Program" />
                    <x-sortable-header field="kelompok" label="Kelompok" />
                    <x-sortable-header field="status_aktif" label="Status" width="90px" />
                    <th scope="col" class="py-3 text-end" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="prodi-table-body">
                @if($programStudis->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="ti ti-book-off text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-3 mb-0 text-muted">Belum ada program studi.</p>
                            <a href="{{ route('program-studi.create') }}" class="btn btn-primary mt-3">Tambah Program Studi Pertama</a>
                        </td>
                    </tr>
                @else
                    @include('program-studi.partials.prodi_rows')
                @endif
            </tbody>
        </table>
        <div id="scroll-sentinel" class="text-center py-2"></div>
        <div id="loading-spinner" class="d-none text-center py-3">
            <div class="spinner-border text-primary" role="status" style="width: 1.5rem; height: 1.5rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    @endslot
@endcomponent

@include('components.infinite-scroll-script', [
    'tableBodyId' => 'prodi-table-body',
    'spinnerId' => 'loading-spinner',
    'sentinelId' => 'scroll-sentinel',
    'nextPageUrl' => $programStudis->nextPageUrl(),
    'hasMore' => $programStudis->hasMorePages(),
])
@endsection
