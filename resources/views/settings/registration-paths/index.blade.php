@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $paths])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Program Studi', 'active' => true],
    ])
    @slot('title', 'Program Studi')
    @slot('description', 'Konfigurasi program studi yang terkait dengan form registrasi.')
    @slot('actions')
        <a href="{{ route('registration-paths.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Tambah Program Studi
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-3 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari program studi..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="kategori" class="form-select">
                <option value="">-- Kategori --</option>
                @foreach($kategoris ?? [] as $kategori)
                    <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-white border"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('registration-paths.index') }}" class="btn btn-white border px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('registration-paths.index') }}?export=xls'">
            <i class="ti ti-file-spreadsheet"></i> .xls
        </a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
            <i class="ti ti-printer"></i> Print
        </a>
    @endslot
    @slot('table')
        <table class="table table-hover align-middle table-ead">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="py-3" style="width: 60px;">No</th>
                    <th scope="col" class="py-3">Kode</th>
                    <th scope="col" class="py-3">Nama</th>
                    <th scope="col" class="py-3">Kategori</th>
                    <th scope="col" class="py-3">Biaya</th>
                    <th scope="col" class="py-3">Periode</th>
                    <th scope="col" class="py-3">Kuota</th>
                    <th scope="col" class="py-3">Status</th>
                    <th scope="col" class="py-3 text-end" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="path-table-body">
                @include('registration-paths.partials.path_rows')
            </tbody>
        </table>
        {{-- Sentinel element for infinite scroll --}}
        <div id="scroll-sentinel" class="text-center py-2"></div>
        <div id="loading-spinner" class="d-none text-center py-3">
            <div class="spinner-border text-primary" role="status" style="width: 1.5rem; height: 1.5rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    @endslot
@endcomponent

@include('components.infinite-scroll-script', [
    'tableBodyId' => 'path-table-body',
    'spinnerId' => 'loading-spinner',
    'sentinelId' => 'scroll-sentinel',
    'nextPageUrl' => $paths->nextPageUrl(),
    'hasMore' => $paths->hasMorePages(),
])
@endsection
