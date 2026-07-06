@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $forms])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Form Pendaftaran', 'active' => true],
    ])
    @slot('title', 'Form Pendaftaran')
    @slot('description', 'Kelola formulir pendaftaran dan field-field yang terkait.')
    @slot('actions')
        <a href="{{ route('settings.form-pendaftaran.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Tambah Form
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-3 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari form..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-white border"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('settings.form-pendaftaran.index') }}" class="btn btn-white border px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('settings.form-pendaftaran.index') }}?export=xls'">
            <i class="ti ti-file-spreadsheet"></i> .xls
        </a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
            <i class="ti ti-printer"></i> Print
        </a>
    @endslot
    @slot('table')
        @include('components.ajax-sort-script', ['tableBodyId' => 'form-table-body'])
        <table class="table table-hover align-middle table-ead">
            <thead class="bg-light">
                <tr>
                    <th style="width: 60px;" class="py-3">No</th>
                    <x-sortable-header field="nama" label="Nama Form" />
                    <th class="py-3">Deskripsi</th>
                    <th class="py-3 text-center">Field</th>
                    <th class="py-3 text-center">Status</th>
                    <x-sortable-header field="created_at" label="Dibuat" align="center" />
                    <th class="py-3 text-center" style="width:90px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="form-table-body">
                @include('settings.form-pendaftaran.partials.form_rows')
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
    'tableBodyId' => 'form-table-body',
    'spinnerId' => 'loading-spinner',
    'nextPageUrl' => $forms->nextPageUrl(),
    'hasMore' => $forms->hasMorePages(),
])
@endsection
