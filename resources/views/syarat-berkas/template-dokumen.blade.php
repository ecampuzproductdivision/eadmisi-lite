@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $dokumens])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Syarat Berkas', 'url' => route('syarat-berkas.index')],
        ['label' => $templateBerkas->nama_template, 'active' => true],
    ])
    @slot('title', $templateBerkas->nama_template)
    @slot('description')
        Total Dokumen: {{ $templateBerkas->total_dokumen }} |
        Status: @if($templateBerkas->status_aktif) <span class="badge bg-success-subtle text-success">Aktif</span>
        @else <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span> @endif
        @if($templateBerkas->deskripsi)<br><span class="text-muted">{{ $templateBerkas->deskripsi }}</span>@endif
    @endslot
    @slot('backUrl', route('syarat-berkas.index'))
    @slot('actions')
        <button type="button" class="btn btn-dark d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahDokumen">
            <i class="ti ti-plus fs-4"></i> Tambah Dokumen
        </button>
    @endslot
    @slot('exports')
    @endslot
    @slot('table')
        <table class="table table-hover align-middle mb-0 table-ead">
            <thead class="table-light">
                <tr>
                    <th class="py-3" style="width:50px;">No</th>
                    <th class="py-3">Nama Dokumen</th>
                    <th class="py-3">Format Ekstensi</th>
                    <th class="py-3">Maks. Ukuran</th>
                    <th class="py-3">Sifat</th>
                    <th class="py-3 text-center" style="width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($dokumens->isEmpty())
                    <tr><td colspan="6" class="text-center py-5">
                        <i class="ti ti-file-off text-muted" style="font-size:3rem;"></i>
                        <p class="mt-3 mb-0 text-muted">Belum ada dokumen dalam template ini.</p>
                        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modalTambahDokumen">Tambah Dokumen Pertama</button>
                    </td></tr>
                @else
                    @include('syarat-berkas.partials.dokumen_rows')
                @endif
            </tbody>
        </table>
    @endslot
@endcomponent

<!-- Modal Tambah Dokumen -->
<div class="modal fade" id="modalTambahDokumen" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Tambah Dokumen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form action="{{ route('syarat-berkas.store-dokumen', $templateBerkas->id) }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Dokumen <span class="text-danger">*</span></label>
            <input type="text" name="nama_dokumen" class="form-control" placeholder="Contoh: KTP, Ijazah" required maxlength="200">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Format Ekstensi <span class="text-danger">*</span></label>
            <div class="row g-2">
              @foreach(['PDF','PNG','JPG','JPEG'] as $ext)
              <div class="col-3">
                <div class="form-check">
                  <input type="checkbox" name="ekstensi_list[]" value="{{ $ext }}" class="form-check-input ekstensi-checkbox" id="ext_{{ strtolower($ext) }}" checked>
                  <label for="ext_{{ strtolower($ext) }}" class="form-check-label">{{ $ext }}</label>
                </div>
              </div>
              @endforeach
            </div>
            <input type="hidden" name="ekstensi_diizinkan" id="ekstensi_diizinkan" value="PDF,PNG,JPG,JPEG">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Ukuran Maksimal File <span class="text-danger">*</span></label>
            <select name="max_size" class="form-select" required>
              @foreach([1,2,5,10] as $mb)
                <option value="{{ $mb*1024 }}" {{ $mb==2 ? 'selected' : '' }}>{{ $mb }} MB</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Sifat Dokumen</label>
            <div class="form-check form-switch">
              <input type="hidden" name="status_wajib" value="0">
              <input type="checkbox" name="status_wajib" id="status_wajib" class="form-check-input" value="1" checked>
              <label for="status_wajib" class="form-check-label fw-semibold">Wajib Diunggah</label>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Urutan</label>
            <input type="number" name="urutan" class="form-control" placeholder="Otomatis" min="0">
          </div>
          <div class="d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy fs-4"></i> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Dokumen -->
<div class="modal fade" id="modalEditDokumen" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Edit Dokumen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="formEditDokumen" method="POST">
          @csrf @method('PUT')
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Dokumen <span class="text-danger">*</span></label>
            <input type="text" name="nama_dokumen" id="edit_nama" class="form-control" required maxlength="200">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Format Ekstensi</label>
            <div class="row g-2">
              @foreach(['PDF','PNG','JPG','JPEG'] as $ext)
              <div class="col-3">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input edit-ekstensi-cb" value="{{ $ext }}" id="edit_ext_{{ strtolower($ext) }}">
                  <label for="edit_ext_{{ strtolower($ext) }}" class="form-check-label">{{ $ext }}</label>
                </div>
              </div>
              @endforeach
            </div>
            <input type="hidden" name="ekstensi_diizinkan" id="edit_ekstensi" value="">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Ukuran Maksimal</label>
            <select name="max_size" id="edit_max_size" class="form-select" required>
              @foreach([1,2,5,10] as $mb)
                <option value="{{ $mb*1024 }}">{{ $mb }} MB</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <div class="form-check form-switch">
              <input type="hidden" name="status_wajib" value="0">
              <input type="checkbox" name="status_wajib" id="edit_wajib" class="form-check-input" value="1">
              <label for="edit_wajib" class="form-check-label fw-semibold">Wajib Diunggah</label>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Urutan</label>
            <input type="number" name="urutan" id="edit_urutan" class="form-control" min="0">
          </div>
          <div class="d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy fs-4"></i> Perbarui</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@include('components.confirm-modal')

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Sync hidden ekstensi_diizinkan when checkboxes change
  document.querySelectorAll('.ekstensi-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
      var checked = [];
      document.querySelectorAll('.ekstensi-checkbox:checked').forEach(c => checked.push(c.value));
      document.getElementById('ekstensi_diizinkan').value = checked.join(',');
    });
  });

  // Edit modal handler (using event delegation for dynamically reloaded rows)
  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.edit-dokumen-btn');
    if (!btn) return;

    var id = btn.dataset.id;
    var nama = btn.dataset.nama || '';
    var ekstensi = btn.dataset.ekstensi || '';
    var maxsize = btn.dataset.maxsize;
    var wajib = btn.dataset.wajib === 'true';
    var urutan = btn.dataset.urutan || '';

    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_max_size').value = maxsize;
    document.getElementById('edit_wajib').checked = wajib;
    document.getElementById('edit_urutan').value = urutan;

    // Set ekstensi checkboxes
    var exts = ekstensi ? ekstensi.split(',') : [];
    document.querySelectorAll('.edit-ekstensi-cb').forEach(cb => {
      cb.checked = exts.includes(cb.value);
    });
    document.getElementById('edit_ekstensi').value = ekstensi;

    // Sync hidden field on change
    document.querySelectorAll('.edit-ekstensi-cb').forEach(cb => {
      cb.onchange = function() {
        var checked = [];
        document.querySelectorAll('.edit-ekstensi-cb:checked').forEach(c => checked.push(c.value));
        document.getElementById('edit_ekstensi').value = checked.join(',');
      };
    });

    document.getElementById('formEditDokumen').action = '/settings/syarat-berkas/' + {{ $templateBerkas->id }} + '/update-dokumen/' + id;
  });
});
</script>
@endpush
