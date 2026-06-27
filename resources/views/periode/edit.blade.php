@extends('layouts.app')

@section('content')
<main class="p-2">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('periode.index') }}">Settings</a></li>
            <li class="breadcrumb-item"><a href="{{ route('periode.index') }}">Periode Akademik</a></li>
            <li class="breadcrumb-item active">Edit Periode</li>
        </ol>
    </nav>
    <hr>

    {{-- Title with Back Button --}}
    <div class="d-flex align-items-top gap-3 my-5">
        <a href="{{ route('periode.index') }}" class="btn btn-light d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 36px; height: 36px;" title="Back to List">
            <i class="ti ti-arrow-left fs-5"></i>
        </a>
        <div>
            <h1 class="mb-1 fw-bold">Edit Periode</h1>
            <p class="text-muted mb-0">Ubah data tahun akademik dan semester.</p>
        </div>
    </div>

    {{-- Card Form --}}
    <div class="card border-1 shadow-sm px-4 py-4">
        <div class="col-xl-12 col-12">
            <form action="{{ route('periode.update', $periode) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="alert alert-danger mb-4 py-2 small">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3">
                    {{-- Section: Informasi Periode --}}
                    <div class="col-12">
                        <h6 class="text-secondary fw-bold pb-2 mb-0" style="border-bottom: 1px dashed #dee2e6;">Informasi Periode</h6>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="tahun_akademik" class="form-label">Tahun Akademik <span class="text-danger">*</span></label>
                        <select class="form-select @error('tahun_akademik') is-invalid @enderror" id="tahun_akademik" name="tahun_akademik" required>
                            <option value="">-- Pilih Tahun Akademik --</option>
                            @php
                                $currentYear = date('Y');
                                $startYear = $currentYear - 7;
                                $endYear = $currentYear + 7;
                            @endphp
                            @for($year = $startYear; $year <= $endYear; $year++)
                                @php $label = $year . '/' . ($year + 1); @endphp
                                <option value="{{ $label }}" {{ old('tahun_akademik', $periode->tahun_akademik) === $label ? 'selected' : '' }}>{{ $label }}</option>
                            @endfor
                        </select>
                        @error('tahun_akademik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="semester" class="form-label">Periode Semester <span class="text-danger">*</span></label>
                        <select class="form-select @error('semester') is-invalid @enderror" id="semester" name="semester" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="Ganjil" {{ old('semester', $periode->semester) === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ old('semester', $periode->semester) === 'Genap' ? 'selected' : '' }}>Genap</option>
                            <option value="Pendek" {{ old('semester', $periode->semester) === 'Pendek' ? 'selected' : '' }}>Pendek</option>
                        </select>
                        @error('semester')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12"></div>

                    {{-- Section: Status --}}
                    <div class="col-12">
                        <h6 class="text-secondary fw-bold pb-2 mb-0 mt-2" style="border-bottom: 1px dashed #dee2e6;">Status</h6>
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="status_aktif" name="status_aktif" value="1" {{ old('status_aktif', $periode->status_aktif) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="status_aktif">Aktifkan periode ini</label>
                        </div>
                        <small class="text-muted">Hanya satu periode yang dapat aktif dalam satu waktu. Mengaktifkan periode ini akan menonaktifkan periode lainnya.</small>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('periode.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Perbarui</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection