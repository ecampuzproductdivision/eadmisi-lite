@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6">
    <div class="col-12">
      <a href="{{ route('registration-paths.index') }}" class="btn btn-soft-secondary mb-3 d-inline-flex align-items-center gap-2">
        <i class="ti ti-arrow-left fs-4"></i> Kembali
      </a>
      <h1 class="mb-1 fw-bold">Edit Jalur Pendaftaran</h1>
      <p class="mb-0 text-muted">Perbarui informasi jalur pendaftaran: {{ $registrationPath->name }}.</p>
    </div>
  </div>

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

  <div class="card border-0 shadow-sm">
    <div class="card-body p-5">
      <form action="{{ route('registration-paths.update', $registrationPath) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
          <div class="col-md-4">
            <label for="kategori_jalur_id" class="form-label fw-semibold">Kategori Jalur</label>
            <select name="kategori_jalur_id" id="kategori_jalur_id" class="form-select @error('kategori_jalur_id') is-invalid @enderror">
              <option value="">Pilih kategori...</option>
              @foreach($kategoris as $kategori)
                <option value="{{ $kategori->id }}" {{ old('kategori_jalur_id', $registrationPath->kategori_jalur_id) == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
              @endforeach
            </select>
            @error('kategori_jalur_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-4">
            <label for="code" class="form-label fw-semibold">Kode Jalur <span class="text-danger">*</span></label>
            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" placeholder="Contoh: SNBP, SNBT, MANDIRI" value="{{ old('code', $registrationPath->code) }}" required maxlength="50">
            <div class="form-text">Kode unik untuk jalur pendaftaran (huruf kapital, tanpa spasi).</div>
            @error('code')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-6">
            <label for="name" class="form-label fw-semibold">Nama Jalur <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Seleksi Nasional Berdasarkan Prestasi" value="{{ old('name', $registrationPath->name) }}" required maxlength="200">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-12">
            <label for="description" class="form-label fw-semibold">Deskripsi</label>
            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Deskripsi jalur pendaftaran...">{{ old('description', $registrationPath->description) }}</textarea>
            @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-4">
            <label for="registration_start" class="form-label fw-semibold">Tanggal Mulai</label>
            <input type="date" name="registration_start" id="registration_start" class="form-control @error('registration_start') is-invalid @enderror" value="{{ old('registration_start', $registrationPath->registration_start?->format('Y-m-d')) }}">
            @error('registration_start')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-4">
            <label for="registration_end" class="form-label fw-semibold">Tanggal Akhir</label>
            <input type="date" name="registration_end" id="registration_end" class="form-control @error('registration_end') is-invalid @enderror" value="{{ old('registration_end', $registrationPath->registration_end?->format('Y-m-d')) }}">
            @error('registration_end')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-4">
            <label for="quota" class="form-label fw-semibold">Kuota</label>
            <input type="number" name="quota" id="quota" class="form-control @error('quota') is-invalid @enderror" placeholder="Kosongkan jika tidak terbatas" value="{{ old('quota', $registrationPath->quota) }}" min="0">
            @error('quota')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-5">
            <label for="fee" class="form-label fw-semibold">Biaya Pendaftaran (Rp)</label>
            <input type="number" name="fee" id="fee" class="form-control @error('fee') is-invalid @enderror" placeholder="0" value="{{ old('fee', $registrationPath->fee) }}" min="0">
            @error('fee')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-4">
            <label for="color" class="form-label fw-semibold">Warna Badge</label>
            <select name="color" id="color" class="form-select @error('color') is-invalid @enderror">
              <option value="">Pilih warna...</option>
              <option value="primary" {{ old('color', $registrationPath->color) == 'primary' ? 'selected' : '' }}>Biru (Primary)</option>
              <option value="success" {{ old('color', $registrationPath->color) == 'success' ? 'selected' : '' }}>Hijau (Success)</option>
              <option value="warning" {{ old('color', $registrationPath->color) == 'warning' ? 'selected' : '' }}>Kuning (Warning)</option>
              <option value="danger" {{ old('color', $registrationPath->color) == 'danger' ? 'selected' : '' }}>Merah (Danger)</option>
              <option value="info" {{ old('color', $registrationPath->color) == 'info' ? 'selected' : '' }}>Biru Muda (Info)</option>
              <option value="secondary" {{ old('color', $registrationPath->color) == 'secondary' ? 'selected' : '' }}>Abu-abu (Secondary)</option>
              <option value="dark" {{ old('color', $registrationPath->color) == 'dark' ? 'selected' : '' }}>Hitam (Dark)</option>
            </select>
            @error('color')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-3 d-flex align-items-end">
            <div class="form-check form-switch">
              <input type="hidden" name="is_active" value="0">
              <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $registrationPath->is_active) ? 'checked' : '' }}>
              <label for="is_active" class="form-check-label fw-semibold">Aktif</label>
            </div>
          </div>
        </div>

        <div class="mt-5 d-flex gap-3">
          <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2 px-5">
            <i class="ti ti-device-floppy fs-4"></i> Perbarui
          </button>
          <a href="{{ route('registration-paths.index') }}" class="btn btn-soft-secondary d-inline-flex align-items-center gap-2">
            Batal
          </a>
        </div>
      </form>
    </div>
  </div>
</main>
@endsection