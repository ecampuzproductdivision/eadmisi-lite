@extends('layouts.app')

@section('content')
<main class="p-2">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Settings</a></li>
            <li class="breadcrumb-item active">Komponen Biaya</li>
        </ol>
    </nav>
    <hr>

    <div class="d-flex align-items-center justify-content-between my-5">
        <div>
            <h1 class="mb-1 fw-bold">Komponen Biaya</h1>
            <p class="text-muted mb-0">Kelola master komponen biaya untuk Registrasi Ulang (ePembayaran).</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="ti ti-plus me-1"></i> Tambah Komponen Biaya
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check-circle fs-4 me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle fs-4 me-2"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-1 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Kode</th>
                            <th>Nama Komponen</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="komponen-rows">
                        @forelse($komponens as $komponen)
                            <tr>
                                <td class="ps-4"><code>{{ $komponen->kode_komponen }}</code></td>
                                <td>{{ $komponen->nama_komponen }}</td>
                                <td>{{ $komponen->deskripsi ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $komponen->is_active ? 'success' : 'secondary' }}">
                                        {{ $komponen->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $komponen->id }}">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <form action="{{ route('komponen-biaya.toggle-status', $komponen) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $komponen->is_active ? 'warning' : 'success' }} me-1" title="{{ $komponen->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="ti ti-{{ $komponen->is_active ? 'player-pause' : 'player-play' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('komponen-biaya.destroy', $komponen) }}" method="POST" class="d-inline" onsubmit="return confirmSubmit(event, 'Hapus komponen biaya ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada data komponen biaya.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $komponens->links() }}
    </div>
</main>

{{-- Create Modal --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('komponen-biaya.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Komponen Biaya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modals --}}
@foreach($komponens as $komponen)
    <div class="modal fade" id="editModal{{ $komponen->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('komponen-biaya.update', $komponen) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Komponen Biaya</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection