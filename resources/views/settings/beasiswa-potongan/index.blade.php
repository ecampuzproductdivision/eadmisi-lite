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

    {{-- Tabs Navigation --}}
    @php
        $activeTab = session('active_tab', 'master');
    @endphp
    <ul class="nav nav-tabs mb-4" id="scholarshipTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $activeTab === 'master' ? 'active' : '' }} fw-semibold px-4" id="master-tab" data-bs-toggle="tab" data-bs-target="#master-pane" type="button" role="tab">
                <i class="ti ti-settings me-1"></i> Master Setup
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $activeTab === 'plotting' ? 'active' : '' }} fw-semibold px-4" id="plotting-tab" data-bs-toggle="tab" data-bs-target="#plotting-pane" type="button" role="tab">
                <i class="ti ti-users me-1"></i> Plotting Setup
            </button>
        </li>
    </ul>

    {{-- Tabs Content --}}
    <div class="tab-content" id="scholarshipTabsContent">
        {{-- Pane 1: Master Setup --}}
        <div class="tab-pane fade {{ $activeTab === 'master' ? 'show active' : '' }}" id="master-pane" role="tabpanel">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="fw-bold mb-0">Master Potongan</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMasterModal">
                    <i class="ti ti-plus me-1"></i> Tambah Master Potongan
                </button>
            </div>

            <div class="card border-1 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Nama Potongan</th>
                                    <th>Tipe</th>
                                    <th>Nilai Potongan</th>
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
                                        <td>{{ $master->keterangan ?? '-' }}</td>
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editMasterModal{{ $master->id }}" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <form action="{{ route('beasiswa-potongan.master.destroy', $master->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus master potongan ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data master potongan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pane 2: Plotting Setup --}}
        <div class="tab-pane fade {{ $activeTab === 'plotting' ? 'show active' : '' }}" id="plotting-pane" role="tabpanel">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="fw-bold mb-0">Plotting Potongan Mahasiswa</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPlottingModal">
                    <i class="ti ti-plus me-1"></i> Plotting Beasiswa Baru
                </button>
            </div>

            <div class="card border-1 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No. Pendaftaran / Nama Camaba</th>
                                    <th>Jalur Pendaftaran</th>
                                    <th>Nama Potongan (Master)</th>
                                    <th>Nominal Potongan Akhir</th>
                                    <th>Catatan</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plottings as $plotting)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="d-block fw-bold text-dark">{{ $plotting->registration?->user?->name ?? '-' }}</span>
                                            <small class="text-muted">No: <strong>{{ $plotting->registration?->no_pendaftaran ?? '-' }}</strong></small>
                                        </td>
                                        <td>{{ $plotting->registration?->registrationPath?->name ?? '-' }}</td>
                                        <td>
                                            <span class="fw-semibold">{{ $plotting->masterPotongan?->nama_potongan ?? '-' }}</span>
                                            <br>
                                            <small class="text-muted">Nilai Master: {{ $plotting->masterPotongan?->tipe_potongan === 'persen' ? $plotting->masterPotongan?->nilai_potongan . '%' : 'Rp ' . number_format($plotting->masterPotongan?->nilai_potongan ?? 0, 0, ',', '.') }}</small>
                                        </td>
                                        <td class="fw-bold text-success">
                                            Rp {{ number_format($plotting->nominal_potongan, 0, ',', '.') }}
                                        </td>
                                        <td>{{ $plotting->keterangan ?? '-' }}</td>
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editPlottingModal{{ $plotting->id }}" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <form action="{{ route('beasiswa-potongan.plotting.destroy', $plotting->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus plotting potongan untuk mahasiswa ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data plotting potongan mahasiswa.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- MODALS FOR MASTER --}}

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

{{-- MODALS FOR PLOTTING --}}

{{-- Create Plotting Modal --}}
<div class="modal fade" id="createPlottingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('beasiswa-potongan.plotting.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Plotting Beasiswa Pendaftar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Calon Mahasiswa (Status PMB Lunas) <span class="text-danger">*</span></label>
                        <select name="registration_id" id="create_registration_id" class="form-select select2-eligible" required>
                            <option value="">-- Cari Camaba Lunas --</option>
                            @foreach($eligibleRegistrations as $reg)
                                @php
                                    // Check if student already has a plotted discount
                                    $hasPlotting = $plottings->firstWhere('registration_id', $reg->id) !== null;
                                @endphp
                                @if(!$hasPlotting)
                                    <option value="{{ $reg->id }}" data-total-biaya="{{ $reg->total_biaya ?? 0 }}">
                                        {{ $reg->no_pendaftaran }} - {{ $reg->user?->name }} ({{ $reg->registrationPath?->name ?? '-' }} | Biaya Reg: Rp {{ number_format($reg->total_biaya ?? 0, 0, ',', '.') }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Master Potongan <span class="text-danger">*</span></label>
                        <select name="master_potongan_id" id="create_master_potongan_id" class="form-select" required>
                            <option value="">-- Pilih Potongan --</option>
                            @foreach($masters as $master)
                                <option value="{{ $master->id }}" data-tipe="{{ $master->tipe_potongan }}" data-nilai="{{ $master->nilai_potongan }}">
                                    {{ $master->nama_potongan }} ({{ $master->tipe_potongan === 'persen' ? $master->nilai_potongan . '%' : 'Rp ' . number_format($master->nilai_potongan, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nominal Potongan Akhir (Bisa Override) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="nominal_potongan" id="create_nominal_potongan" class="form-control" required min="0" placeholder="0" value="{{ old('nominal_potongan', 0) }}">
                        </div>
                        <div class="form-text">Nilai dikalkulasikan otomatis, tetapi admin dapat mengubahnya jika diperlukan.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan / Catatan Beasiswa</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan plotting beasiswa untuk mahasiswa ini...">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Plotting</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Plotting Modals --}}
@foreach($plottings as $plotting)
    @php
        // Get the eligible student's total re-registration fee
        $totalBiayaReg = \DB::table('jalur_pendaftaran_biayas')
            ->where('registration_path_id', $plotting->registration?->registration_path_id)
            ->sum('nominal');
    @endphp
    <div class="modal fade" id="editPlottingModal{{ $plotting->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('beasiswa-potongan.plotting.update', $plotting->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Edit Plotting Beasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Calon Mahasiswa</label>
                            <input type="text" class="form-control bg-light" readonly value="{{ $plotting->registration?->no_pendaftaran }} - {{ $plotting->registration?->user?->name }} (Rp {{ number_format($totalBiayaReg, 0, ',', '.') }})">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Master Potongan <span class="text-danger">*</span></label>
                            <select name="master_potongan_id" id="edit_master_potongan_id_{{ $plotting->id }}" class="form-select edit-master-select" data-plotting-id="{{ $plotting->id }}" data-total-biaya="{{ $totalBiayaReg }}" required>
                                @foreach($masters as $master)
                                    <option value="{{ $master->id }}" data-tipe="{{ $master->tipe_potongan }}" data-nilai="{{ $master->nilai_potongan }}" {{ $plotting->master_potongan_id == $master->id ? 'selected' : '' }}>
                                        {{ $master->nama_potongan }} ({{ $master->tipe_potongan === 'persen' ? $master->nilai_potongan . '%' : 'Rp ' . number_format($master->nilai_potongan, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nominal Potongan Akhir (Bisa Override) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="nominal_potongan" id="edit_nominal_potongan_{{ $plotting->id }}" class="form-control" required min="0" value="{{ old('nominal_potongan', $plotting->nominal_potongan) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan / Catatan Beasiswa</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $plotting->keterangan) }}</textarea>
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

@push('scripts')
<script>
$(document).ready(function() {
    // Select2 setup for Student Plotting
    if ($.fn.select2) {
        $('.select2-eligible').select2({
            dropdownParent: $('#createPlottingModal'),
            width: '100%'
        });
    }

    // Auto-calculate for Create Plotting Modal
    const $studentSelect = $('#create_registration_id');
    const $masterSelect = $('#create_master_potongan_id');
    const $nominalInput = $('#create_nominal_potongan');

    function calculateCreateDiscount() {
        const selectedStudent = $studentSelect.find('option:selected');
        const selectedMaster = $masterSelect.find('option:selected');
        
        if (!selectedStudent.val() || !selectedMaster.val()) {
            return;
        }

        const totalBiaya = parseInt(selectedStudent.data('total-biaya')) || 0;
        const tipe = selectedMaster.data('tipe');
        const nilai = parseInt(selectedMaster.data('nilai')) || 0;

        let nominal = 0;
        if (tipe === 'rupiah') {
            nominal = nilai;
        } else if (tipe === 'persen') {
            nominal = Math.round((totalBiaya * nilai) / 100);
        }
        $nominalInput.val(nominal);
    }

    $studentSelect.on('change', calculateCreateDiscount);
    $masterSelect.on('change', calculateCreateDiscount);

    // Auto-calculate for Edit Plotting Modals
    $('.edit-master-select').on('change', function() {
        const plottingId = $(this).data('plotting-id');
        const totalBiaya = parseInt($(this).data('total-biaya')) || 0;
        const selectedMaster = $(this).find('option:selected');
        const tipe = selectedMaster.data('tipe');
        const nilai = parseInt(selectedMaster.data('nilai')) || 0;

        let nominal = 0;
        if (tipe === 'rupiah') {
            nominal = nilai;
        } else if (tipe === 'persen') {
            nominal = Math.round((totalBiaya * nilai) / 100);
        }
        $(`#edit_nominal_potongan_${plottingId}`).val(nominal);
    });
});
</script>
@endpush
