@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-md-6 col-12">
      <h1 class="mb-1 fw-bold">Master Syarat Berkas</h1>
      <p class="mb-0 text-muted">Kelola template persyaratan dokumen untuk jalur pendaftaran.</p>
    </div>
    <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
      <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahTemplate">
        <i class="ti ti-plus fs-4"></i> Tambah Template
      </button>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body p-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="bg-light">
            <tr>
              <th style="width:50px;">No</th>
              <th>Nama Template</th>
              <th style="width:100px;" class="text-center">Jumlah Dokumen</th>
              <th style="width:100px;">Status</th>
              <th style="width:320px;">Aksi</th>
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
      </div>
      <div id="pagination-container">
        @if($templates->hasPages())<div class="mt-3">{{ $templates->links() }}</div>@endif
      </div>
    </div>
  </div>
</main>

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