@extends('layouts.app')

@section('content')
<main class="p-2">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Settings</a></li>
            <li class="breadcrumb-item active">Beasiswa & Potongan</li>
        </ol>
    </nav>
    <hr>

    <div class="d-flex align-items-center justify-content-between my-5">
        <div>
            <h1 class="mb-1 fw-bold">Beasiswa & Potongan</h1>
            <p class="text-muted mb-0">Kelola master beasiswa/potongan dan lakukan plotting diskon biaya registrasi ulang calon mahasiswa.</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMasterModal">
            <i class="ti ti-plus me-1"></i> Tambah Master Potongan
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
                            <th class="ps-4">Nama Potongan</th>
                            <th>Tipe</th>
                            <th>Nilai Potongan</th>
                            <th>Penerima (Plotting)</th>
                            <th>Keterangan</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($masters as $master)
                            <tr>
                                <td class="ps-4 fw-semibold text-dark">{{ $master->nama_potongan }}</td>
                                <td>
                                    <span class="badge bg-{{ $master->tipe_potongan === 'persen' ? 'info' : 'success' }}-subtle text-{{ $master->tipe_potongan === 'persen' ? 'info' : 'success' }}-emphasis">
                                        {{ ucfirst($master->tipe_potongan) }}
                                    </span>
                                </td>
                                <td class="fw-bold">
                                    {{ $master->tipe_potongan === 'persen' ? $master->nilai_potongan . '%' : 'Rp ' . number_format($master->nilai_potongan, 0, ',', '.') }}
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fw-semibold">
                                        <i class="ti ti-users me-1"></i> {{ $master->plottings_count ?? 0 }} Calon Mahasiswa
                                    </span>
                                </td>
                                <td>{{ $master->keterangan ?? '-' }}</td>
                                <td class="text-end pe-4">
                                    {{-- Plotting Button --}}
                                    <a href="{{ route('beasiswa-potongan.plotting.show', $master->id) }}" class="btn btn-sm btn-outline-info me-1" title="Plotting Mahasiswa">
                                        <i class="ti ti-user-plus fs-5"></i>
                                    </a>
                                    {{-- Edit Button --}}
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editMasterModal{{ $master->id }}" title="Edit Master">
                                        <i class="ti ti-edit fs-5"></i>
                                    </button>
                                    {{-- Delete Button --}}
                                    <form action="{{ route('beasiswa-potongan.master.destroy', $master->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus master potongan ini beserta data plotting mahasiswanya?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="ti ti-trash fs-5"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data master potongan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

{{-- Create Master Modal --}}
<div class="modal fade" id="createMasterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('beasiswa-potongan.master.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Master Potongan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Potongan / Beasiswa <span class="text-danger">*</span></label>
                        <input type="text" name="nama_potongan" class="form-control" placeholder="Contoh: Beasiswa Prestasi Hafidz 5 Juz" required value="{{ old('nama_potongan') }}">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tipe Potongan <span class="text-danger">*</span></label>
                            <select name="tipe_potongan" class="form-select" required>
                                <option value="rupiah" {{ old('tipe_potongan') === 'rupiah' ? 'selected' : '' }}>Rupiah (Rp)</option>
                                <option value="persen" {{ old('tipe_potongan') === 'persen' ? 'selected' : '' }}>Persentase (%)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nilai Potongan <span class="text-danger">*</span></label>
                            <input type="number" name="nilai_potongan" class="form-control" placeholder="Contoh: 1000000 atau 50" required min="1" value="{{ old('nilai_potongan') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan opsional mengenai potongan ini...">{{ old('keterangan') }}</textarea>
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

{{-- Edit Master Modals --}}
@foreach($masters as $master)
    <div class="modal fade" id="editMasterModal{{ $master->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('beasiswa-potongan.master.update', $master->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Edit Master Potongan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Potongan / Beasiswa <span class="text-danger">*</span></label>
                            <input type="text" name="nama_potongan" class="form-control" required value="{{ old('nama_potongan', $master->nama_potongan) }}">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tipe Potongan <span class="text-danger">*</span></label>
                                <select name="tipe_potongan" class="form-select" required>
                                    <option value="rupiah" {{ old('tipe_potongan', $master->tipe_potongan) === 'rupiah' ? 'selected' : '' }}>Rupiah (Rp)</option>
                                    <option value="persen" {{ old('tipe_potongan', $master->tipe_potongan) === 'persen' ? 'selected' : '' }}>Persentase (%)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nilai Potongan <span class="text-danger">*</span></label>
                                <input type="number" name="nilai_potongan" class="form-control" required min="1" value="{{ old('nilai_potongan', $master->nilai_potongan) }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $master->keterangan) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection
