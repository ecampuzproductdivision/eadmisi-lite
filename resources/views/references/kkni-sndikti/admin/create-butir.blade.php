@extends('layouts.app')

@section('content')
<main class="p-2">
  <div class="card border-1 shadow-sm mb-6">
    <div class="card-body p-4">
      <div class="row mb-4 align-items-center">
        <div class="col-md-6 col-12">
          <h3 class="mb-1 mt-2 fw-bold">Tambah Butir SN-Dikti</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Data Master</a></li>
              <li class="breadcrumb-item"><a href="{{ route('kkni-sndikti.index') }}">KKNI SNDikti</a></li>
              <li class="breadcrumb-item"><a href="{{ route('kkni-sndikti.admin.regulasi.edit', $regulasi->id_regulasi) }}">{{ $regulasi->nomor_peraturan }}</a></li>
              <li class="breadcrumb-item active">Tambah Butir</li>
            </ol>
          </nav>
        </div>
      </div>

      <div class="alert alert-info border-0 d-flex align-items-center mb-4">
        <i class="ti ti-file-text fs-4 me-2 text-info"></i>
        <div>Regulasi: <strong>{{ $regulasi->nomor_peraturan }}</strong> ({{ $regulasi->versi }}) — Status: <span class="badge bg-warning text-dark">Draft</span></div>
      </div>

      <form action="{{ route('kkni-sndikti.admin.butir.store', $regulasi->id_regulasi) }}" method="POST" class="row g-4">
        @csrf

        <div class="col-md-4">
          <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
          <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
            <option value="">-- Pilih --</option>
            <option value="Sikap" {{ old('kategori') == 'Sikap' ? 'selected' : '' }}>Sikap</option>
            <option value="Pengetahuan" {{ old('kategori') == 'Pengetahuan' ? 'selected' : '' }}>Pengetahuan</option>
            <option value="Keterampilan Umum" {{ old('kategori') == 'Keterampilan Umum' ? 'selected' : '' }}>Keterampilan Umum</option>
            <option value="Keterampilan Khusus" {{ old('kategori') == 'Keterampilan Khusus' ? 'selected' : '' }}>Keterampilan Khusus</option>
          </select>
          @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Jenjang <span class="text-danger">*</span></label>
          <select name="jenjang" class="form-select @error('jenjang') is-invalid @enderror" required>
            <option value="">-- Pilih --</option>
            <option value="D3" {{ old('jenjang') == 'D3' ? 'selected' : '' }}>D3</option>
            <option value="D4" {{ old('jenjang') == 'D4' ? 'selected' : '' }}>D4</option>
            <option value="S1" {{ old('jenjang') == 'S1' ? 'selected' : '' }}>S1</option>
            <option value="Profesi" {{ old('jenjang') == 'Profesi' ? 'selected' : '' }}>Profesi</option>
            <option value="S2" {{ old('jenjang') == 'S2' ? 'selected' : '' }}>S2</option>
            <option value="S3" {{ old('jenjang') == 'S3' ? 'selected' : '' }}>S3</option>
            <option value="Semua" {{ old('jenjang') == 'Semua' ? 'selected' : '' }}>Semua Jenjang</option>
          </select>
          @error('jenjang') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-2">
          <label class="form-label fw-semibold">Kode Butir <span class="text-danger">*</span></label>
          <input type="text" name="kode_butir" class="form-control @error('kode_butir') is-invalid @enderror" value="{{ old('kode_butir') }}" placeholder="Misal: S1, KU1" required maxlength="10">
          @error('kode_butir') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-2">
          <label class="form-label fw-semibold">Urutan <span class="text-danger">*</span></label>
          <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror" value="{{ old('urutan', 1) }}" required min="1">
          @error('urutan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-12">
          <label class="form-label fw-semibold">Deskripsi Butir <span class="text-danger">*</span></label>
          <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3" required>{{ old('deskripsi') }}</textarea>
          @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Kata Kunci</label>
          <input type="text" name="kata_kunci" class="form-control @error('kata_kunci') is-invalid @enderror" value="{{ old('kata_kunci') }}" placeholder="Kata kunci untuk pencarian">
          @error('kata_kunci') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
          <label class="form-label fw-semibold">Status Wajib</label>
          <div class="form-check form-switch mt-2">
            <input type="checkbox" name="is_wajib" class="form-check-input" value="1" {{ old('is_wajib', true) ? 'checked' : '' }} id="is_wajib">
            <label class="form-check-label" for="is_wajib">Butir Wajib</label>
          </div>
        </div>

        <div class="col-md-3">
          <label class="form-label fw-semibold">Catatan Implementasi</label>
          <input type="text" name="catatan_implementasi" class="form-control @error('catatan_implementasi') is-invalid @enderror" value="{{ old('catatan_implementasi') }}">
          @error('catatan_implementasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-12">
          <label class="form-label fw-semibold">Alasan Perubahan <span class="text-danger">*</span></label>
          <textarea name="alasan_perubahan" class="form-control @error('alasan_perubahan') is-invalid @enderror" rows="2" required minlength="10" placeholder="Jelaskan alasan penambahan butir ini...">{{ old('alasan_perubahan') }}</textarea>
          <small class="text-muted">Alasan ini akan tercatat di riwayat perubahan (changelog).</small>
          @error('alasan_perubahan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12 border-top pt-4">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-dark px-5">
              <i class="ti ti-device-floppy me-1"></i> Simpan Butir
            </button>
            <a href="{{ route('kkni-sndikti.admin.regulasi.edit', $regulasi->id_regulasi) }}" class="btn btn-light border fw-semibold px-4">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</main>
@endsection