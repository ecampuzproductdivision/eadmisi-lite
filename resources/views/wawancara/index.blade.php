@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $registrations])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Kelola Wawancara', 'active' => true],
    ])
    @slot('title', 'Kelola Wawancara')
    @slot('description', 'Atur jadwal wawancara dan input hasil untuk calon mahasiswa pada jalur yang menggunakan tahapan wawancara.')
    @slot('filters')
        <div class="col-md-3 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama pendaftar..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="Belum Wawancara" {{ request('status') == 'Belum Wawancara' ? 'selected' : '' }}>Belum Wawancara</option>
                <option value="Lolos" {{ request('status') == 'Lolos' ? 'selected' : '' }}>Lolos</option>
                <option value="Tidak Lolos" {{ request('status') == 'Tidak Lolos' ? 'selected' : '' }}>Tidak Lolos</option>
            </select>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('wawancara.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('table')
        <table class="table table-hover align-middle mb-0 table-ead" style="min-width: 1100px;">
            <thead class="table-light">
                <tr>
                    <th class="py-3">No</th>
                    <th class="py-3">No. Pendaftaran</th>
                    <th class="py-3">Nama Mahasiswa</th>
                    <th class="py-3">Jalur</th>
                    <th class="py-3">Jadwal Wawancara</th>
                    <th class="py-3">Pewawancara</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Aksi</th>
                </tr>
            </thead>
            <tbody id="wawancara-table-body">
                @include('wawancara.partials.wawancara_rows')
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
    'tableBodyId' => 'wawancara-table-body',
    'spinnerId' => 'loading-spinner',
    'nextPageUrl' => $registrations->nextPageUrl(),
    'hasMore' => $registrations->hasMorePages(),
])

<!-- Modal Atur Jadwal -->
<div class="modal fade" id="jadwalModal" tabindex="-1" aria-labelledby="jadwalModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('wawancara.schedule') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="jadwalModalLabel">Atur Jadwal Wawancara</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="pendaftaran_id" id="jadwal_pendaftaran_id" value="">

          <div class="mb-3">
            <p class="fw-semibold mb-0" id="jadwal_nama_label">Nama Mahasiswa</p>
            <small class="text-muted">Isi jadwal wawancara untuk calon mahasiswa ini.</small>
          </div>

          <div class="mb-3">
            <label for="tanggal_wawancara" class="form-label">Tanggal Wawancara</label>
            <input type="date" name="tanggal_wawancara" id="tanggal_wawancara" class="form-control">
          </div>

          <div class="mb-3">
            <label for="jam_wawancara" class="form-label">Jam Wawancara</label>
            <input type="time" name="jam_wawancara" id="jam_wawancara" class="form-control">
          </div>

          <div class="mb-3">
            <label for="lokasi_wawancara" class="form-label">Lokasi / Virtual Link</label>
            <input type="text" name="lokasi_wawancara" id="lokasi_wawancara" class="form-control" placeholder="Contoh: Gedung A Ruang 101 atau https://meet.google.com/xxx">
            <div class="form-text">Masukkan nama ruangan fisik atau URL link virtual meeting.</div>
          </div>

          <div class="mb-3">
            <label for="nama_pewawancara" class="form-label">Nama Pewawancara</label>
            <input type="text" name="nama_pewawancara" id="nama_pewawancara" class="form-control" placeholder="Masukkan nama dosen / tim pewawancara...">
            <div class="form-text">Ketik nama pewawancara secara manual. Super Admin yang mengelola.</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Input Hasil -->
<div class="modal fade" id="hasilModal" tabindex="-1" aria-labelledby="hasilModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('wawancara.hasil') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="hasilModalLabel">Input Hasil Wawancara</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="pendaftaran_id" id="hasil_pendaftaran_id" value="">

          <div class="mb-3">
            <p class="fw-semibold mb-0" id="hasil_nama_label">Nama Mahasiswa</p>
            <small class="text-muted">Input hasil wawancara untuk calon mahasiswa ini.</small>
          </div>

          <div class="mb-3">
            <label for="status_wawancara" class="form-label">Status Wawancara <span class="text-danger">*</span></label>
            <select name="status_wawancara" id="status_wawancara" class="form-select" required>
              <option value="">Pilih status...</option>
              <option value="Lolos">Lolos</option>
              <option value="Tidak Lolos">Tidak Lolos</option>
            </select>
            <div class="form-text text-danger">Jika memilih "Tidak Lolos", sistem akan otomatis menggagalkan pendaftar terlepas dari nilai ujian online.</div>
          </div>

          <div class="mb-3">
            <label for="catatan_pewawancara" class="form-label">Catatan Pewawancara</label>
            <textarea name="catatan_pewawancara" id="catatan_pewawancara" rows="4" class="form-control" placeholder="Catatan hasil wawancara..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Hasil</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const jadwalModal = document.getElementById('jadwalModal');
  jadwalModal.addEventListener('show.bs.modal', function(event) {
    const btn = event.relatedTarget;
    document.getElementById('jadwal_pendaftaran_id').value = btn.getAttribute('data-pendaftaran-id');
    document.getElementById('jadwal_nama_label').textContent = 'Nama: ' + btn.getAttribute('data-nama');
    document.getElementById('tanggal_wawancara').value = btn.getAttribute('data-tanggal');
    document.getElementById('jam_wawancara').value = btn.getAttribute('data-jam');
    document.getElementById('lokasi_wawancara').value = btn.getAttribute('data-lokasi');
    document.getElementById('nama_pewawancara').value = btn.getAttribute('data-nama-pewawancara');
  });

  const hasilModal = document.getElementById('hasilModal');
  hasilModal.addEventListener('show.bs.modal', function(event) {
    const btn = event.relatedTarget;
    document.getElementById('hasil_pendaftaran_id').value = btn.getAttribute('data-pendaftaran-id');
    document.getElementById('hasil_nama_label').textContent = 'Nama: ' + btn.getAttribute('data-nama');
    document.getElementById('status_wawancara').value = btn.getAttribute('data-status');
    document.getElementById('catatan_pewawancara').value = btn.getAttribute('data-catatan');
  });
});
</script>
@endpush
@endsection
