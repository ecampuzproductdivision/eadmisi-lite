@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-md-6 col-12">
      <h1 class="mb-1 fw-bold">Jalur Pendaftaran</h1>
      <p class="mb-0 text-muted">Kelola jalur penerimaan mahasiswa baru, biaya, dan jadwal pendaftaran.</p>
    </div>
    <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
      <a href="{{ route('registration-paths.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <i class="ti ti-plus fs-4"></i> Tambah Jalur Baru
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

  <div class="card border-0 shadow-sm">
    <div class="card-body p-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="bg-light">
            <tr>
              <th style="width: 60px;">No</th>
              <th>Kode</th>
              <th>Nama Jalur</th>
              <th>Kategori</th>
              <th>Biaya</th>
              <th>Periode</th>
              <th>Kuota</th>
              <th>Status</th>
              <th style="width: 150px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($paths as $path)
              <tr>
                <td>{{ $loop->iteration + ($paths->currentPage() - 1) * $paths->perPage() }}</td>
                <td>
                  <span class="badge bg-{{ $path->color ?? 'secondary' }}-subtle text-{{ $path->color ?? 'secondary' }} px-3 py-2">
                    {{ $path->code }}
                  </span>
                </td>
                <td class="fw-semibold">{{ $path->name }}</td>
                <td>
                  @if($path->kategori)
                    <span class="badge bg-dark-subtle text-dark px-3 py-2">{{ $path->kategori->nama }}</span>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td>Rp {{ number_format($path->fee, 0, ',', '.') }}</td>
                <td>
                  @if($path->registration_start && $path->registration_end)
                    <small>{{ \Carbon\Carbon::parse($path->registration_start)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($path->registration_end)->format('d/m/Y') }}</small>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td>
                  @if($path->quota)
                    <span class="fw-semibold">{{ $path->quota }}</span>
                  @else
                    <span class="text-muted">∞</span>
                  @endif
                </td>
                <td>
                  @if($path->is_active)
                    <span class="badge bg-success-subtle text-success px-3 py-2">Aktif</span>
                  @else
                    <span class="badge bg-danger-subtle text-danger px-3 py-2">Nonaktif</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('registration-paths.show', $path) }}" class="btn btn-sm btn-info d-inline-flex align-items-center gap-1">
                      <i class="ti ti-eye fs-5"></i>
                    </a>
                    <a href="{{ route('registration-paths.edit', $path) }}" class="btn btn-sm btn-warning d-inline-flex align-items-center gap-1">
                      <i class="ti ti-edit fs-5"></i>
                    </a>
                    <form action="{{ route('registration-paths.destroy', $path) }}" method="POST" onsubmit="return confirm('Hapus jalur {{ $path->name }}?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1">
                        <i class="ti ti-trash fs-5"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center py-5">
                  <i class="ti ti-road-off text-muted" style="font-size: 3rem;"></i>
                  <p class="mt-3 mb-0 text-muted">Belum ada jalur pendaftaran.</p>
                  <a href="{{ route('registration-paths.create') }}" class="btn btn-primary mt-3">Tambah Jalur Pertama</a>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="mt-3">
        {{ $paths->links() }}
      </div>
    </div>
  </div>
</main>
@endsection