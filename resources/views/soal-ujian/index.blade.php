@extends('layouts.app')

@section('content')
@component('components.data-page-layout')
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Bank Soal (Paket Soal)', 'active' => true],
    ])
    @slot('title', 'Daftar Paket Soal')
    @slot('description', 'Kelola paket soal ujian untuk seluruh jalur pendaftaran.')
    @slot('actions')
        <button type="button" class="btn btn-dark d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahPaket">
            <i class="ti ti-plus fs-4"></i> Tambah Paket Soal
        </button>
    @endslot
    @slot('filters')
        <div class="col-md-4 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama paket..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="col-md-2 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('paket-soal.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('table')
        <table class="table align-middle text-nowrap mb-0 table-hover table-ead">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="py-3" style="width: 50px;">No</th>
                    <th scope="col" class="py-3">Nama Paket</th>
                    <th scope="col" class="py-3 text-center" style="width: 80px;">Total Soal</th>
                    <th scope="col" class="py-3 text-center" style="width: 80px;">Total Skor</th>
                    <th scope="col" class="py-3" style="width: 100px;">Status</th>
                    <th scope="col" class="py-3 text-end" style="width: 320px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="paket-table-body">
                @if($pakets->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="ti ti-zoom-question text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-3 mb-0 text-muted">Belum ada paket soal.</p>
                            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modalTambahPaket">Tambah Paket Soal Pertama</button>
                        </td>
                    </tr>
                @else
                    @include('soal-ujian.partials.paket_rows')
                @endif
            </tbody>
        </table>

        <div id="loading-spinner" class="text-center py-3 d-none">
            <div class="spinner-border text-primary" role="status" style="width: 1.5rem; height: 1.5rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    @endslot
@endcomponent

@include('components.infinite-scroll-script', [
    'tableBodyId' => 'paket-table-body',
    'spinnerId' => 'loading-spinner',
    'nextPageUrl' => $pakets->nextPageUrl(),
    'hasMore' => $pakets->hasMorePages(),
])

<!-- Modal Tambah Paket -->
<div class="modal fade" id="modalTambahPaket" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Tambah Paket Soal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form action="{{ route('paket-soal.store') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label>
            <input type="text" name="nama_paket" class="form-control" placeholder="Contoh: Paket Ujian SNBT 2026" required maxlength="200">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="form-control" placeholder="Deskripsi paket soal..."></textarea>
          </div>

          <div class="mt-4 d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
              <i class="ti ti-device-floppy fs-4"></i> Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Package (rendered per row) is inside paket_rows.blade.php -->
@endsection
