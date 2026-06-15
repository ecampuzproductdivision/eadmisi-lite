@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row justify-content-center">
    <div class="col-lg-10">

      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
          <h2 class="fw-bold mb-1">Form Pendaftaran</h2>
          <p class="text-muted mb-0">Kelola formulir pendaftaran mahasiswa baru.</p>
        </div>
        <div>
          <a href="{{ route('settings.form-pendaftaran.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Tambah Form
          </a>
        </div>
      </div>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="ti ti-circle-check fs-4 me-2"></i>
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <!-- Table -->
      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
              <thead class="bg-light">
                <tr>
                  <th class="px-4 py-3 fw-semibold" width="50">No</th>
                  <th class="px-4 py-3 fw-semibold">Nama Form</th>
                  <th class="px-4 py-3 fw-semibold">Deskripsi</th>
                  <th class="px-4 py-3 fw-semibold text-center">Field</th>
                  <th class="px-4 py-3 fw-semibold text-center">Status</th>
                  <th class="px-4 py-3 fw-semibold text-center">Dibuat</th>
                  <th class="px-4 py-3 fw-semibold text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($forms as $i => $form)
                <tr>
                  <td class="px-4 text-muted small">{{ $loop->iteration }}</td>
                  <td class="px-4">
                    <div class="d-flex align-items-center gap-2">
                      <span class="d-inline-flex rounded-circle bg-primary bg-opacity-10 align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="ti ti-file-text text-primary"></i>
                      </span>
                      <div>
                        <span class="fw-semibold">{{ $form->nama }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="px-4">
                    <small class="text-muted">{{ Str::limit($form->deskripsi, 60) ?: '-' }}</small>
                  </td>
                  <td class="px-4 text-center">
                    <span class="badge bg-info-subtle text-info px-3 py-2">{{ $form->fields_count }} field</span>
                  </td>
                  <td class="px-4 text-center">
                    <form action="{{ route('settings.form-pendaftaran.toggle-status', $form->id) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm {{ $form->is_active ? 'btn-success' : 'btn-secondary' }} border-0">
                        <i class="ti ti-{{ $form->is_active ? 'check' : 'x' }} me-1"></i>
                        {{ $form->is_active ? 'Aktif' : 'Nonaktif' }}
                      </button>
                    </form>
                  </td>
                  <td class="px-4 text-center">
                    <small class="text-muted">{{ $form->created_at->format('d/m/Y') }}</small>
                  </td>
                  <td class="px-4 text-center">
                    <div class="d-flex gap-1 justify-content-center">
                      <a href="{{ route('settings.form-pendaftaran.builder', $form->id) }}" class="btn btn-primary btn-sm" title="Atur Field">
                        <i class="ti ti-layout-board"></i>
                      </a>
                      <form action="{{ route('settings.form-pendaftaran.destroy', $form->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus form {{ $form->nama }}? Semua field akan ikut terhapus.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                          <i class="ti ti-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="px-4 py-5 text-center">
                    <div class="mb-3"><i class="ti ti-file-off text-muted" style="font-size: 2.5rem;"></i></div>
                    <h6 class="fw-bold text-muted mb-1">Belum Ada Form</h6>
                    <small class="text-muted">Klik "Tambah Form" untuk membuat formulir pendaftaran baru.</small>
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
        @if($forms->hasPages())
        <div class="card-footer bg-white border-top py-3">{{ $forms->links() }}</div>
        @endif
      </div>

    </div>
  </div>
</main>
@endsection