@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $komponens])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Komponen Biaya', 'active' => true],
    ])
    @slot('title', 'Komponen Biaya')
    @slot('description', 'Kelola master komponen biaya untuk Registrasi Ulang (ePembayaran).')
    @slot('actions')
        <button type="button" class="btn btn-dark d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="ti ti-plus fs-4"></i> Tambah Komponen Biaya
        </button>
    @endslot
    @slot('filters')
        <div class="col-md-3 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari kode, nama komponen..." value="{{ request('search') }}">
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
            <a href="{{ route('komponen-biaya.index') }}" class="btn btn-white border px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('komponen-biaya.index') }}?export=xls'">
            <i class="ti ti-file-spreadsheet"></i> .xls
        </a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
            <i class="ti ti-printer"></i> Print
        </a>
    @endslot
    @slot('table')
        @include('components.ajax-sort-script', ['tableBodyId' => 'komponen-rows'])
        <table class="table align-middle text-nowrap mb-0 table-hover table-ead">
            <thead class="table-light">
                <tr>
                    <th class="ps-4 py-3">Kode</th>
                    <th class="py-3">Nama Komponen</th>
                    <th class="py-3">Deskripsi</th>
                    <th class="py-3" style="width:120px;">Status</th>
                    <th class="py-3 text-end pe-4" style="width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="komponen-rows">
                @include('komponen-biaya.partials.rows')
            </tbody>
        </table>
        <div id="loading-spinner" class="d-none text-center py-3">
            <div class="spinner-border text-primary" role="status" style="width: 1.5rem; height: 1.5rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    @endslot
    @slot('spinner', true)
    @slot('sentinel', true)
@endcomponent

@include('components.infinite-scroll-script', [
    'tableBodyId' => 'komponen-rows',
    'spinnerId' => 'loading-spinner',
    'nextPageUrl' => $komponens->nextPageUrl(),
    'hasMore' => $komponens->hasMorePages(),
])

{{-- Create Modal --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('komponen-biaya.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Tambah Komponen Biaya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Komponen <span class="text-danger">*</span></label>
                        <input type="text" name="kode_komponen" class="form-control" placeholder="Contoh: REG01, ALM01" required maxlength="50" value="{{ old('kode_komponen') }}">
                        <div class="form-text">Kode unik yang sesuai dengan kode ePembayaran.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Komponen <span class="text-danger">*</span></label>
                        <input type="text" name="nama_komponen" class="form-control" placeholder="Contoh: Uang Gedung, Jas Almamater" required maxlength="200" value="{{ old('nama_komponen') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Deskripsi komponen biaya...">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="create_is_active" class="form-check-input" value="1" checked>
                        <label for="create_is_active" class="form-check-label fw-semibold">Aktif</label>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modals --}}
@foreach($komponens as $komponen)
    <div class="modal fade" id="editModal{{ $komponen->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('komponen-biaya.update', $komponen) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Edit Komponen Biaya</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kode Komponen <span class="text-danger">*</span></label>
                            <input type="text" name="kode_komponen" class="form-control" required maxlength="50" value="{{ old('kode_komponen', $komponen->kode_komponen) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Komponen <span class="text-danger">*</span></label>
                            <input type="text" name="nama_komponen" class="form-control" required maxlength="200" value="{{ old('nama_komponen', $komponen->nama_komponen) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi', $komponen->deskripsi) }}</textarea>
                        </div>
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="edit_is_active_{{ $komponen->id }}" class="form-check-input" value="1" {{ $komponen->is_active ? 'checked' : '' }}>
                            <label for="edit_is_active_{{ $komponen->id }}" class="form-check-label fw-semibold">Aktif</label>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection