@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row justify-content-center">
    <div class="col-lg-6">

      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
          <h2 class="fw-bold mb-1">Tambah Form</h2>
          <p class="text-muted mb-0">Buat formulir pendaftaran baru, kemudian atur field-formnya.</p>
        </div>
        <div>
          <a href="{{ route('settings.form-pendaftaran.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>

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

      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <form action="{{ route('settings.form-pendaftaran.store') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-semibold">Nama Form <span class="text-danger">*</span></label>
              <input type="text" name="nama" class="form-control form-control-lg @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Contoh: Form Pendaftaran Gelombang 1" required>
              @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-4">
              <label class="form-label fw-semibold">Deskripsi (opsional)</label>
              <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3" placeholder="Penjelasan singkat tentang form ini">{{ old('deskripsi') }}</textarea>
              @error('deskripsi')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <button type="submit" class="btn btn-primary fw-semibold px-4">
              <i class="ti ti-device-floppy me-1"></i> Simpan & Lanjutkan
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</main>
@endsection