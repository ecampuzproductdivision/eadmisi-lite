@extends('layouts.app')

@section('content')
<main class="p-2">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('settings.form-pendaftaran.index') }}">Settings</a></li>
            <li class="breadcrumb-item"><a href="{{ route('settings.form-pendaftaran.index') }}">Form Pendaftaran</a></li>
            <li class="breadcrumb-item active">Tambah Form</li>
        </ol>
    </nav>
    <hr>

    {{-- Title with Back Button --}}
    <div class="d-flex align-items-top gap-3 my-5">
        <a href="{{ route('settings.form-pendaftaran.index') }}" class="btn btn-light d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 36px; height: 36px;" title="Back to List">
            <i class="ti ti-arrow-left fs-5"></i>
        </a>
        <div>
            <h1 class="mb-1 fw-bold">Tambah Form</h1>
            <p class="text-muted mb-0">Buat formulir pendaftaran baru, kemudian atur field-formnya.</p>
        </div>
    </div>

    {{-- Card Form --}}
    <div class="card border-1 shadow-sm px-4 py-4">
        <div class="col-xl-12 col-12">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-triangle fs-4 me-2"></i>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('settings.form-pendaftaran.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    {{-- Section: Informasi Form --}}
                    <div class="col-12">
                        <h6 class="text-secondary fw-bold pb-2 mb-0" style="border-bottom: 1px dashed #dee2e6;">Informasi Form</h6>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label fw-semibold">Nama Form <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Contoh: Form Pendaftaran Gelombang 1" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12"></div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi (opsional)</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3" placeholder="Penjelasan singkat tentang form ini">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('settings.form-pendaftaran.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="ti ti-device-floppy me-1"></i> Simpan & Lanjutkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
