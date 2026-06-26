@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
          <h2 class="fw-bold mb-1">Edit Jalur Pendaftaran</h2>
          <p class="text-muted mb-0">Perbarui informasi jalur pendaftaran mahasiswa baru.</p>
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
          <strong>Terjadi kesalahan:</strong>
          <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <!-- Form Card -->
      <div class="card border-1 shadow-sm">
        <div class="card-body p-4">
          <form action="{{ route('settings.form-pendaftaran.update', $registrationPath->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
              <!-- Kode Jalur -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">Kode Jalur <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $registrationPath->code) }}" placeholder="Contoh: SNBP, SNBT, MANDIRI" required maxlength="50">
                <small class="text-muted">Kode unik untuk jalur pendaftaran (huruf besar tanpa spasi).</small>
                @error('code')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Nama Jalur -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Jalur <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $registrationPath->name) }}" placeholder="Nama lengkap jalur pendaftaran" required maxlength="255">
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Kategori Jalur -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">Kategori Jalur</label>
                <select name="kategori_jalur_id" class="form-select @error('kategori_jalur_id') is-invalid @enderror">
                  <option value="">Pilih Kategori (Opsional)</option>
                  @foreach($kategoriJalurs as $kategori)
                    <option value="{{ $kategori->id }}" {{ old('kategori_jalur_id', $registrationPath->kategori_jalur_id) == $kategori->id ? 'selected' : '' }}>
                      {{ $kategori->nama }}
                    </option>
                  @endforeach
                </select>
                @error('kategori_jalur_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Warna -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">Warna Label</label>
                <div class="input-group">
                  <input type="color" name="color" class="form-control form-control-color @error('color') is-invalid @enderror" value="{{ old('color', $registrationPath->color ?? '#f63a4c') }}" style="max-width: 60px;">
                  <input type="text" class="form-control" value="{{ old('color', $registrationPath->color ?? '#f63a4c') }}" maxlength="7" readonly style="background: transparent;">
                </div>
                <small class="text-muted">Warna untuk menandai jalur di halaman pendaftaran.</small>
                @error('color')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Biaya -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">Biaya Pendaftaran</label>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input type="number" name="fee" class="form-control @error('fee') is-invalid @enderror" value="{{ old('fee', $registrationPath->fee) }}" placeholder="0" min="0">
                </div>
                <small class="text-muted">Kosongi jika gratis.</small>
                @error('fee')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Kuota -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">Kuota Pendaftar</label>
                <input type="number" name="quota" class="form-control @error('quota') is-invalid @enderror" value="{{ old('quota', $registrationPath->quota) }}" placeholder="Kosongi jika tidak terbatas" min="0">
                <small class="text-muted">Maksimal jumlah pendaftar. Kosongi jika tidak terbatas.</small>
                @error('quota')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Tanggal Mulai -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">Tanggal Mulai Pendaftaran</label>
                <input type="date" name="registration_start" class="form-control @error('registration_start') is-invalid @enderror" value="{{ old('registration_start', $registrationPath->registration_start?->format('Y-m-d')) }}">
                @error('registration_start')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Tanggal Selesai -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">Tanggal Selesai Pendaftaran</label>
                <input type="date" name="registration_end" class="form-control @error('registration_end') is-invalid @enderror" value="{{ old('registration_end', $registrationPath->registration_end?->format('Y-m-d')) }}">
                @error('registration_end')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Deskripsi -->
              <div class="col-12">
                <label class="form-label fw-semibold">Deskripsi Jalur</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Penjelasan mengenai jalur pendaftaran ini...">{{ old('description', $registrationPath->description) }}</textarea>
                @error('description')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Status Aktif -->
              <div class="col-12">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $registrationPath->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                  <label class="form-check-label fw-semibold" for="is_active">Aktif</label>
                  <small class="text-muted d-block">Nonaktifkan untuk menyembunyikan jalur ini dari halaman pendaftaran.</small>
                </div>
              </div>
            </div>

            <hr class="my-4">

            <div class="d-flex gap-3">
              <button type="submit" class="btn btn-primary fw-semibold px-4">
                <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
              </button>
              <a href="{{ route('settings.form-pendaftaran.index') }}" class="btn btn-outline-secondary px-4">
                Batal
              </a>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</main>

<style>
.form-control-color {
  cursor: pointer;
}
</style>
@endsection
