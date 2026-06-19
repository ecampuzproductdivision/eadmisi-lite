@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-md-6 col-12">
      <h1 class="mb-1 fw-bold">Periode Akademik</h1>
      <p class="mb-0 text-muted">Kelola tahun akademik dan semester aktif untuk pendaftaran mahasiswa baru.</p>
    </div>
    <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
      <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#periodeModal">
        <i class="ti ti-plus fs-4"></i> Tambah Periode Baru
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

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-circle fs-4 me-2"></i>
      {{ $errors->first() }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body p-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="bg-light">
            <tr>
              <th style="width: 60px;">No</th>
              <th>Tahun Akademik</th>
              <th>Periode Semester</th>
              <th>Status Aktif</th>
              <th style="width: 160px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($periodes as $index => $periode)
              <tr>
                <td>{{ ($periodes->currentPage() - 1) * $periodes->perPage() + $index + 1 }}</td>
                <td class="fw-semibold">{{ $periode->tahun_akademik }}</td>
                <td>
                  @if($periode->semester === 'Ganjil')
                    <span class="badge bg-primary-subtle text-primary px-3 py-2">Ganjil</span>
                  @elseif($periode->semester === 'Genap')
                    <span class="badge bg-info-subtle text-info px-3 py-2">Genap</span>
                  @else
                    <span class="badge bg-warning-subtle text-warning px-3 py-2">Pendek</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('periode.toggle-active', $periode) }}" method="POST" class="m-0">
                      @csrf
                      <div class="form-check form-switch mb-0">
                        <input type="checkbox" class="form-check-input" role="switch"
                               {{ $periode->status_aktif ? 'checked' : '' }}
                               onchange="this.form.submit()">
                      </div>
                    </form>
                    @if($periode->status_aktif)
                      <span class="badge bg-success-subtle text-success px-3 py-2">Aktif</span>
                    @else
                      <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Nonaktif</span>
                    @endif
                  </div>
                </td>
                <td>
                  <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm py-2 btn-white d-inline-flex align-items-center gap-1"
                            data-bs-toggle="modal" data-bs-target="#periodeModal"
                            data-id="{{ $periode->id }}"
                            data-tahun-akademik="{{ $periode->tahun_akademik }}"
                            data-semester="{{ $periode->semester }}"
                            data-status-aktif="{{ $periode->status_aktif ? 'true' : 'false' }}">
                      <i class="ti ti-pencil"></i>
                    </button>
                    <form action="{{ route('periode.destroy', $periode) }}" method="POST" onsubmit="return confirm('Hapus periode {{ $periode->label }}?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm py-2 btn-white d-inline-flex align-items-center gap-1">
                        <i class="ti ti-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-5">
                  <i class="ti ti-calendar-off text-muted" style="font-size: 3rem;"></i>
                  <p class="mt-3 mb-0 text-muted">Belum ada periode akademik.</p>
                  <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#periodeModal">
                    Tambah Periode Pertama
                  </button>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($periodes->hasPages())
        <div class="mt-3">
          {{ $periodes->links() }}
        </div>
      @endif
    </div>
  </div>
</main>

<!-- Add/Edit Modal -->
<div class="modal fade" id="periodeModal" tabindex="-1" aria-labelledby="periodeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="periodeForm" method="POST" action="{{ route('periode.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="periodeModalLabel">Tambah Periode Akademik</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- ID field for edit mode -->
          <input type="hidden" name="periode_id" id="periode_id" value="">

          <!-- Tahun Akademik (Combobox / Dropdown) -->
          <div class="mb-3">
            <label for="tahun_akademik" class="form-label">Tahun Akademik <span class="text-danger">*</span></label>
            <select class="form-select @error('tahun_akademik') is-invalid @enderror"
                    id="tahun_akademik" name="tahun_akademik" required>
              <option value="">-- Pilih Tahun Akademik --</option>
              @php
                $currentYear = date('Y');
                $startYear = $currentYear - 7;
                $endYear = $currentYear + 7;
              @endphp
              @for($year = $startYear; $year <= $endYear; $year++)
                @php $label = $year . '/' . ($year + 1); @endphp
                <option value="{{ $label }}" {{ old('tahun_akademik') === $label ? 'selected' : '' }}>{{ $label }}</option>
              @endfor
            </select>
            @error('tahun_akademik')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Periode Semester -->
          <div class="mb-3">
            <label for="semester" class="form-label">Periode Semester <span class="text-danger">*</span></label>
            <select class="form-select @error('semester') is-invalid @enderror"
                    id="semester" name="semester" required>
              <option value="">-- Pilih Semester --</option>
              <option value="Ganjil" {{ old('semester') === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
              <option value="Genap" {{ old('semester') === 'Genap' ? 'selected' : '' }}>Genap</option>
              <option value="Pendek" {{ old('semester') === 'Pendek' ? 'selected' : '' }}>Pendek</option>
            </select>
            @error('semester')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Status Aktif (Toggle Switch) -->
          <div class="mb-3">
            <label class="form-label">Status Aktif</label>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" role="switch"
                     id="status_aktif" name="status_aktif" value="1"
                     {{ old('status_aktif') ? 'checked' : '' }}>
              <label class="form-check-label" for="status_aktif">
                Aktifkan periode ini
              </label>
            </div>
            <small class="text-muted">Hanya satu periode yang dapat aktif dalam satu waktu.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const periodeModal = document.getElementById('periodeModal');
  const form = document.getElementById('periodeForm');
  const modalTitle = document.getElementById('periodeModalLabel');
  const btnSave = document.getElementById('btnSave');
  const idField = document.getElementById('periode_id');
  const tahunField = document.getElementById('tahun_akademik');
  const semesterField = document.getElementById('semester');
  const statusField = document.getElementById('status_aktif');

  // Handle edit: populate modal with data
  periodeModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const tahunAkademik = button.getAttribute('data-tahun-akademik');
    const semester = button.getAttribute('data-semester');
    const statusAktif = button.getAttribute('data-status-aktif');

    if (id) {
      // Edit mode
      modalTitle.textContent = 'Edit Periode Akademik';
      btnSave.textContent = 'Perbarui';
      idField.value = id;
      tahunField.value = tahunAkademik;
      semesterField.value = semester;
      statusField.checked = statusAktif === 'true';

      // Update form action for update
      form.action = '{{ route("periode.update", ":id") }}'.replace(':id', id);
      // Add PUT method
      if (!form.querySelector('input[name="_method"]')) {
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
      }
    } else {
      // Create mode
      modalTitle.textContent = 'Tambah Periode Akademik';
      btnSave.textContent = 'Simpan';
      idField.value = '';
      tahunField.value = '';
      semesterField.value = '';
      statusField.checked = false;
      form.action = '{{ route("periode.store") }}';

      // Remove method override if exists
      const methodInput = form.querySelector('input[name="_method"]');
      if (methodInput) {
        methodInput.remove();
      }
    }
  });

  // Reset form when modal is closed (to clear validation state)
  periodeModal.addEventListener('hidden.bs.modal', function() {
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
  });
});
</script>
@endpush
@endsection