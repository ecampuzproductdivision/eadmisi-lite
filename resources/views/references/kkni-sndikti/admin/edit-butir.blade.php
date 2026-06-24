@extends('layouts.app')

@section('content')
<main class="p-2">
  <div class="card border-1 shadow-sm mb-6">
    <div class="card-body p-4">
      <div class="row mb-4 align-items-center">
        <div class="col-md-6 col-12">
          <h3 class="mb-1 mt-2 fw-bold">Edit Butir SN-Dikti</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Data Master</a></li>
              <li class="breadcrumb-item"><a href="{{ route('kkni-sndikti.index') }}">KKNI SNDikti</a></li>
              <li class="breadcrumb-item"><a href="{{ route('kkni-sndikti.admin.regulasi.edit', $regulasi->id_regulasi) }}">{{ $regulasi->nomor_peraturan }}</a></li>
              <li class="breadcrumb-item active">Edit {{ $butir->kode_butir }}</li>
            </ol>
          </nav>
        </div>
      </div>

      <form action="{{ route('kkni-sndikti.admin.butir.update', [$regulasi->id_regulasi, $butir->id_sndikti]) }}" method="POST" class="row g-4">
        @csrf
        @method('PUT')

        <div class="col-md-4">
          <label class="form-label fw-semibold">Kategori</label>
          <input type="text" class="form-control" value="{{ $butir->kategori }}" disabled>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Jenjang</label>
          <input type="text" class="form-control" value="{{ $butir->jenjang }}" disabled>
        </div>

        <div class="col-md-2">
          <label class="form-label fw-semibold">Kode Butir</label>
          <input type="text" class="form-control" value="{{ $butir->kode_butir }}" disabled>
        </div>

        <div class="col-md-2">
          <label class="form-label fw-semibold">Urutan <span class="text-danger">*</span></label>
          <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror" value="{{ old('urutan', $butir->urutan) }}" required min="1">
          @error('urutan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-12">
          <label class="form-label fw-semibold">Deskripsi Butir <span class="text-danger">*</span></label>
          <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3" required>{{ old('deskripsi', $butir->deskripsi) }}</textarea>
          @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Kata Kunci</label>
          <input type="text" name="kata_kunci" class="form-control @error('kata_kunci') is-invalid @enderror" value="{{ old('kata_kunci', $butir->kata_kunci) }}">
          @error('kata_kunci') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
          <label class="form-label fw-semibold">Status Wajib</label>
          <div class="form-check form-switch mt-2">
            <input type="checkbox" name="is_wajib" class="form-check-input" value="1" {{ old('is_wajib', $butir->is_wajib) ? 'checked' : '' }} id="is_wajib">
            <label class="form-check-label" for="is_wajib">Butir Wajib</label>
          </div>
          <div class="form-check mt-2">
            <input type="checkbox" name="konfirmasi_wajib" class="form-check-input" value="1" id="konfirmasi_wajib">
            <label class="form-check-label small text-danger" for="konfirmasi_wajib">
              <i class="ti ti-alert-triangle me-1"></i> Konfirmasi perubahan status wajib
            </label>
          </div>
        </div>

        <div class="col-md-3">
          <label class="form-label fw-semibold">Catatan Implementasi</label>
          <input type="text" name="catatan_implementasi" class="form-control @error('catatan_implementasi') is-invalid @enderror" value="{{ old('catatan_implementasi', $butir->catatan_implementasi) }}">
          @error('catatan_implementasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-12">
          <label class="form-label fw-semibold">Alasan Perubahan <span class="text-danger">*</span></label>
          <textarea name="alasan_perubahan" class="form-control @error('alasan_perubahan') is-invalid @enderror" rows="2" required minlength="10" placeholder="Jelaskan alasan perubahan butir ini...">{{ old('alasan_perubahan') }}</textarea>
          <small class="text-muted">Alasan ini wajib diisi dan akan tercatat di riwayat perubahan (changelog).</small>
          @error('alasan_perubahan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12 border-top pt-4">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-dark px-5">
              <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
            </button>
            <a href="{{ route('kkni-sndikti.admin.regulasi.edit', $regulasi->id_regulasi) }}" class="btn btn-light border fw-semibold px-4">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</main>
@endsection