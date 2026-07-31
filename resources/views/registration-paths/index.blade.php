@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $paths])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Jalur Pendaftaran', 'active' => true],
    ])
    @slot('title', 'Jalur Pendaftaran')
    @slot('description', 'Kelola jalur penerimaan mahasiswa baru, biaya, dan jadwal pendaftaran.')
    @slot('actions')
        <a href="{{ route('registration-paths.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Tambah Jalur Baru
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-3 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari jalur pendaftaran..." value="{{ request('search') }}">
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
        <div class="col-md-3 col-12">
            <select name="periode_id" class="form-select">
                <option value="">-- Periode Akademik --</option>
                @foreach($periodes ?? [] as $periode)
                    <option value="{{ $periode->id }}" {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                        {{ $periode->tahun_akademik }} - {{ $periode->semester }} {{ $periode->status_aktif ? '(Aktif)' : '' }}
                    </option>
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
        @include('components.ajax-sort-script', ['tableBodyId' => 'paths-table-body'])
        <table class="table table-hover align-middle table-ead">
            <thead class="bg-light">
                <tr>
                    <th style="width: 60px;">No</th>
                    <x-sortable-header field="code" label="Nama Jalur" />
                    <th>Kategori</th>
                    <th>Jenis Pendaftaran</th>
                    <th>Form Template</th>
                    <th>Biaya</th>
                    <th>PERIODE AKADEMIK</th>
                    <th>Jadwal Pendaftaran</th>
                    <th>Kuota</th>
                    <x-sortable-header field="is_active" label="Status" width="90px" />
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="paths-table-body">
                @if($paths->isEmpty())
                    <tr>
                        <td colspan="11" class="text-center py-5">
                            <i class="ti ti-road-off text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-3 mb-0 text-muted">Belum ada jalur pendaftaran.</p>
                            <a href="{{ route('registration-paths.create') }}" class="btn btn-primary mt-3">Tambah Jalur Pertama</a>
                        </td>
                    </tr>
                @else
                    @include('registration-paths.partials.path_rows')
                @endif
            </tbody>
        </table>
    @endslot
    @slot('pagination')
        <div class="d-flex justify-content-end align-items-center">
            <div>
                {{ $paths->links() }}
            </div>
        </div>
    @endslot
@endcomponent
@endsection