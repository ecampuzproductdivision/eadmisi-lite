@extends('layouts.app')

@section('content')
<main class="p-2">
    {{-- Breadcrumbs --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Settings</a></li>
            <li class="breadcrumb-item"><a href="{{ route('beasiswa-potongan.index') }}">Beasiswa & Potongan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Plotting Mahasiswa</li>
        </ol>
    </nav>
    <hr>

    {{-- Header with Back Button --}}
    <div class="d-flex align-items-top gap-3 my-5">
        <a href="{{ route('beasiswa-potongan.index') }}" class="btn btn-light d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 36px; height: 36px;" title="Kembali ke Daftar">
            <i class="ti ti-arrow-left fs-5"></i>
        </a>
        <div>
            <h1 class="mb-1 fw-bold">Plotting Mahasiswa</h1>
            <p class="text-muted mb-0">Petakan diskon potongan registrasi ulang untuk beasiswa: <strong>{{ $master->nama_potongan }}</strong></p>
        </div>
    </div>

    {{-- Details Card --}}
    <div class="card border-1 shadow-sm mb-4">
        <div class="card-body bg-light-subtle d-flex flex-wrap gap-4 align-items-center py-3">
            <div>
                <small class="text-muted d-block">Tipe Potongan</small>
                <span class="badge bg-{{ $master->tipe_potongan === 'persen' ? 'info' : 'success' }}-subtle text-{{ $master->tipe_potongan === 'persen' ? 'info' : 'success' }}-emphasis fw-bold px-3 py-2 mt-1">
                    {{ ucfirst($master->tipe_potongan) }}
                </span>
            </div>
            <div>
                <small class="text-muted d-block">Nilai Potongan Master</small>
                <span class="fw-bold fs-5 text-dark mt-1 d-inline-block">
                    {{ $master->tipe_potongan === 'persen' ? $master->nilai_potongan . '%' : 'Rp ' . number_format($master->nilai_potongan, 0, ',', '.') }}
                </span>
            </div>
            @if($master->keterangan)
                <div class="border-start ps-4">
                    <small class="text-muted d-block">Deskripsi / Keterangan</small>
                    <span class="small text-muted d-block mt-1">{{ $master->keterangan }}</span>
                </div>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check-circle fs-4 me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filter Card --}}
    <div class="card border-1 shadow-sm mb-4">
        <div class="card-body py-4">
            <h5 class="fw-bold mb-3"><i class="ti ti-filter me-1"></i> Filter Pencarian Mahasiswa</h5>
            <form method="GET" action="{{ route('beasiswa-potongan.plotting.show', $master->id) }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Periode Jalur</label>
                    <select name="periode_id" class="form-select">
                        <option value="">-- Semua Periode --</option>
                        @foreach($periodes as $periode)
                            <option value="{{ $periode->id }}" {{ $selectedPeriodeId == $periode->id ? 'selected' : '' }}>
                                {{ $periode->nama_periode }} {{ $periode->status_aktif ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jalur Pendaftaran</label>
                    <select name="registration_path_id" class="form-select">
                        <option value="">-- Semua Jalur --</option>
                        @foreach($paths as $path)
                            <option value="{{ $path->id }}" {{ $selectedPathId == $path->id ? 'selected' : '' }}>
                                {{ $path->name }} ({{ $path->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Pencarian Nama / No. Pendaftaran</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ti ti-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama atau nomor..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        Filter
                    </button>
                    <a href="{{ route('beasiswa-potongan.plotting.show', $master->id) }}" class="btn btn-light border py-2" title="Reset Filter">
                        <i class="ti ti-rotate"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Plotting Form --}}
    <form action="{{ route('beasiswa-potongan.plotting.save', $master->id) }}" method="POST">
        @csrf
        <div class="card border-1 shadow-sm mb-4">
            <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark mb-0"><i class="ti ti-list me-1"></i> Daftar Calon Mahasiswa (PMB Lunas)</span>
                <span class="badge bg-secondary-subtle text-secondary px-3 py-1 fw-semibold">Total Terfilter: {{ $registrations->count() }} orang</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" width="40px">
                                    <input type="checkbox" id="select-all-students" class="form-check-input">
                                </th>
                                <th>No. Pendaftaran</th>
                                <th>Nama Calon Mahasiswa</th>
                                <th>Jalur Masuk</th>
                                <th>Biaya Normal</th>
                                <th width="180px">Nominal Diskon (Rp)</th>
                                <th>Catatan Potongan</th>
                                <th class="text-center">Status Lain</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $reg)
                                @php
                                    $isCurrentPlotted = $reg->plottingPotongan && $reg->plottingPotongan->master_potongan_id == $master->id;
                                    $isOtherPlotted = $reg->plottingPotongan && $reg->plottingPotongan->master_potongan_id != $master->id;
                                    $otherScholarshipName = $isOtherPlotted ? ($reg->plottingPotongan->masterPotongan?->nama_potongan ?? 'Lainnya') : '';
                                @endphp
                                <tr class="{{ $isCurrentPlotted ? 'table-success-subtle' : '' }}">
                                    <td class="ps-4">
                                        <input type="checkbox" name="registration_ids[]" value="{{ $reg->id }}" 
                                            class="form-check-input student-checkbox"
                                            {{ $isCurrentPlotted ? 'checked' : '' }}
                                            {{ $isOtherPlotted ? 'disabled' : '' }}>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $reg->no_pendaftaran }}</span>
                                    </td>
                                    <td>
                                        <span class="d-block fw-semibold text-dark">{{ $reg->user?->name }}</span>
                                    </td>
                                    <td>
                                        <span class="small text-muted">{{ $reg->registrationPath?->name ?? '-' }}</span>
                                    </td>
                                    <td class="fw-semibold">
                                        Rp {{ number_format($reg->total_biaya ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <input type="number" name="nominals[{{ $reg->id }}]" 
                                            value="{{ $reg->calculated_potongan }}" 
                                            class="form-control form-control-sm nominal-input" 
                                            min="0"
                                            {{ $isOtherPlotted ? 'disabled' : '' }}>
                                    </td>
                                    <td>
                                        <input type="text" name="keterangans[{{ $reg->id }}]" 
                                            value="{{ $reg->plottingPotongan?->keterangan }}" 
                                            class="form-control form-control-sm text-muted" 
                                            placeholder="Catatan..."
                                            {{ $isOtherPlotted ? 'disabled' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        @if($isCurrentPlotted)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Plotting Aktif</span>
                                        @elseif($isOtherPlotted)
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1" title="Terplot di: {{ $otherScholarshipName }}">
                                                Terplot di: {{ \Str::limit($otherScholarshipName, 15) }}
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">Tidak ada calon mahasiswa yang ditemukan atau memenuhi kriteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($registrations->isNotEmpty())
            <div class="d-flex justify-content-end gap-3 mb-5">
                <a href="{{ route('beasiswa-potongan.index') }}" class="btn btn-light border px-4 py-2">Batal</a>
                <button type="submit" class="btn btn-success px-5 py-2 fw-semibold">
                    <i class="ti ti-circle-check me-1"></i> Simpan Plotting
                </button>
            </div>
        @endif
    </form>
</main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select All Checkbox Handler
    $('#select-all-students').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.student-checkbox:not(:disabled)').prop('checked', isChecked);
    });

    // Auto check checkbox when admin manually updates the discount override
    $('.nominal-input').on('input', function() {
        const $row = $(this).closest('tr');
        const $checkbox = $row.find('.student-checkbox');
        if (!$checkbox.is(':disabled') && !$checkbox.is(':checked')) {
            $checkbox.prop('checked', true);
        }
    });
});
</script>
@endpush
