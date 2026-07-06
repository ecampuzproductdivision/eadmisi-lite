@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $soals])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Paket Soal', 'url' => route('paket-soal.index')],
        ['label' => $paketSoal->nama_paket, 'active' => true],
    ])
    @slot('title', $paketSoal->nama_paket)
    @slot('description')
        Total Soal: {{ $paketSoal->total_soal }} | Total Skor: {{ $paketSoal->total_skor }} |
        Status:
        @if($paketSoal->status_aktif)
            <span class="badge bg-success-subtle text-success">Aktif</span>
        @else
            <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
        @endif
        @if($paketSoal->deskripsi)<br><span class="text-muted">{{ $paketSoal->deskripsi }}</span>@endif
    @endslot
    @slot('backUrl', route('paket-soal.index'))
    @slot('actions')
        <button type="button" class="btn btn-dark d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahSoal">
            <i class="ti ti-plus fs-4"></i> Tambah Soal
        </button>
    @endslot
    @slot('exports')
    @endslot
    @slot('table')
        <table class="table table-hover align-middle mb-0 table-ead">
            <thead class="table-light">
                <tr>
                    <th class="py-3" style="width:50px;">No</th>
                    <th class="py-3">Pertanyaan</th>
                    <th class="py-3">Kunci</th>
                    <th class="py-3">Skor</th>
                    <th class="py-3">Status</th>
                    <th class="py-3 text-center" style="width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="soal-table-body">
                @if($soals->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            @include('components.empty-state')
                            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modalTambahSoal">Tambah Soal Pertama</button>
                        </td>
                    </tr>
                @else
                    @include('soal-ujian.partials.soal_rows')
                @endif
            </tbody>
        </table>
        <div id="loading-spinner" class="d-none text-center py-3">
            <div class="spinner-border text-primary" role="status" style="width: 1.5rem; height: 1.5rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    @endslot
    @slot('pagination')
        @include('components.infinite-scroll-script', [
            'tableBodyId' => 'soal-table-body',
            'spinnerId' => 'loading-spinner',
            'nextPageUrl' => $soals->nextPageUrl(),
            'hasMore' => $soals->hasMorePages(),
        ])
    @endslot
@endcomponent

<!-- Modal Tambah Soal -->
<div class="modal fade" id="modalTambahSoal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Tambah Soal ke: {{ $paketSoal->nama_paket }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form action="{{ route('paket-soal.store-question', $paketSoal->id) }}" method="POST">
          @csrf

          <div class="mb-3">
            <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
            <textarea name="pertanyaan" rows="3" class="form-control" placeholder="Tulis pertanyaan..." required>{{ old('pertanyaan') }}</textarea>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Opsi A <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text fw-bold">A</span>
                <input type="text" name="opsi_a" class="form-control" placeholder="Opsi A" required value="{{ old('opsi_a') }}">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Opsi B <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text fw-bold">B</span>
                <input type="text" name="opsi_b" class="form-control" placeholder="Opsi B" required value="{{ old('opsi_b') }}">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Opsi C <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text fw-bold">C</span>
                <input type="text" name="opsi_c" class="form-control" placeholder="Opsi C" required value="{{ old('opsi_c') }}">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Opsi D <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text fw-bold">D</span>
                <input type="text" name="opsi_d" class="form-control" placeholder="Opsi D" required value="{{ old('opsi_d') }}">
              </div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Kunci Jawaban <span class="text-danger">*</span></label>
              <select name="kunci_jawaban" class="form-select" required>
                <option value="">Pilih...</option>
                <option value="A" {{ old('kunci_jawaban') == 'A' ? 'selected' : '' }}>A</option>
                <option value="B" {{ old('kunci_jawaban') == 'B' ? 'selected' : '' }}>B</option>
                <option value="C" {{ old('kunci_jawaban') == 'C' ? 'selected' : '' }}>C</option>
                <option value="D" {{ old('kunci_jawaban') == 'D' ? 'selected' : '' }}>D</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Skor <span class="text-danger">*</span></label>
              <input type="number" name="skor" class="form-control" placeholder="0" value="{{ old('skor', 10) }}" min="0" max="100" required>
              <small class="text-muted">Total skor semua soal dalam paket harus tepat 100.</small>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Urutan</label>
              <input type="number" name="urutan" class="form-control" placeholder="Otomatis" value="{{ old('urutan') }}" min="0">
            </div>
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

<!-- Modal Edit Soal -->
<div class="modal fade" id="modalEditSoal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Edit Soal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="formEditSoal" method="POST">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
            <textarea name="pertanyaan" id="edit_pertanyaan" rows="3" class="form-control" required></textarea>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Opsi A <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text fw-bold">A</span>
                <input type="text" name="opsi_a" id="edit_opsi_a" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Opsi B <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text fw-bold">B</span>
                <input type="text" name="opsi_b" id="edit_opsi_b" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Opsi C <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text fw-bold">C</span>
                <input type="text" name="opsi_c" id="edit_opsi_c" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Opsi D <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text fw-bold">D</span>
                <input type="text" name="opsi_d" id="edit_opsi_d" class="form-control" required>
              </div>
            </div>
          </div>
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Kunci Jawaban <span class="text-danger">*</span></label>
              <select name="kunci_jawaban" id="edit_kunci_jawaban" class="form-select" required>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Skor <span class="text-danger">*</span></label>
              <input type="number" name="skor" id="edit_skor" class="form-control" min="0" max="100" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Urutan</label>
              <input type="number" name="urutan" id="edit_urutan" class="form-control" min="0">
            </div>
          </div>
          <div class="mt-4 d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
              <i class="ti ti-device-floppy fs-4"></i> Perbarui
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Edit modal handler (scoped to package context)
function editSoal(id) {
  var paketId = {{ $paketSoal->id }};
  fetch('/settings/paket-soal/' + id + '/edit-question')
    .then(r => r.json())
    .then(data => {
      document.getElementById('edit_pertanyaan').value = data.pertanyaan;
      document.getElementById('edit_opsi_a').value = data.opsi_a;
      document.getElementById('edit_opsi_b').value = data.opsi_b;
      document.getElementById('edit_opsi_c').value = data.opsi_c;
      document.getElementById('edit_opsi_d').value = data.opsi_d;
      document.getElementById('edit_kunci_jawaban').value = data.kunci_jawaban.toUpperCase();
      document.getElementById('edit_skor').value = data.skor;
      document.getElementById('edit_urutan').value = data.urutan;
      document.getElementById('formEditSoal').action = '/settings/paket-soal/' + paketId + '/update-question/' + id;
      new bootstrap.Modal(document.getElementById('modalEditSoal')).show();
    });
}
</script>
@endpush