@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $templates])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Master Syarat Berkas', 'active' => true],
    ])
    @slot('title', 'Master Syarat Berkas')
    @slot('description', 'Kelola template persyaratan dokumen untuk jalur pendaftaran.')
    @slot('actions')
        <button type="button" class="btn btn-dark d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahTemplate">
            <i class="ti ti-plus fs-4"></i> Tambah Template
        </button>
    @endslot
    @slot('filters')
        <div class="col-md-3 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama template..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('syarat-berkas.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('table')
        @include('components.ajax-sort-script', ['tableBodyId' => 'template-table-body'])
        <table class="table align-middle text-nowrap mb-0 table-hover table-ead">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="py-3" style="width:50px;">No</th>
                    <x-sortable-header field="nama_template" label="Nama Template" />
                    <th scope="col" class="py-3 text-center" style="width:100px;">Jumlah Dokumen</th>
                    <x-sortable-header field="status_aktif" label="Status" width="100px" />
                    <th scope="col" class="py-3 text-end" style="width:320px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="template-table-body">
                @if($templates->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="ti ti-file-text text-muted" style="font-size:3rem;"></i>
                            <p class="mt-3 mb-0 text-muted">Belum ada template syarat berkas.</p>
                            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modalTambahTemplate">Tambah Template Pertama</button>
                        </td>
                    </tr>
                @else
                    @include('syarat-berkas.partials.template_rows')
                @endif
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
    'tableBodyId' => 'template-table-body',
    'spinnerId' => 'loading-spinner',
    'nextPageUrl' => $templates->nextPageUrl(),
    'hasMore' => $templates->hasMorePages(),
])

<!-- Modal Tambah Template -->
<div class="modal fade" id="modalTambahTemplate" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Tambah Template Syarat Berkas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form action="{{ route('syarat-berkas.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Template <span class="text-danger">*</span></label>
            <input type="text" name="nama_template" class="form-control" placeholder="Contoh: Dokumen Pendaftaran SNBT" required maxlength="200">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="form-control" placeholder="Deskripsi template..."></textarea>
          </div>
          <div class="mt-4 d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy fs-4 me-1"></i>Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
