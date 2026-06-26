@extends('layouts.app')

@section('content')
<main class="p-2">
  <div class="card border-1 shadow-sm mb-6">
    <div class="card-body p-4">
      <div class="row mb-4 align-items-center">
        <div class="col-md-6 col-12">
          <h3 class="mb-1 mt-2 fw-bold">Tambah Regulasi Baru</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Data Master</a></li>
              <li class="breadcrumb-item"><a href="{{ route('kkni-sndikti.index') }}">KKNI SNDikti</a></li>
              <li class="breadcrumb-item active">Tambah Regulasi</li>
            </ol>
          </nav>
        </div>
        <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
          <a href="{{ route('kkni-sndikti.index') }}" class="btn btn-light border fw-semibold">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>

      <form action="{{ route('kkni-sndikti.admin.regulasi.store') }}" method="POST" class="row g-4" enctype="multipart/form-data">
        @csrf

        <div class="col-md-6">
          <label class="form-label fw-semibold">Jenis Regulasi <span class="text-danger">*</span></label>
          <select name="jenis_regulasi" class="form-select @error('jenis_regulasi') is-invalid @enderror" required>
            <option value="">-- Pilih --</option>
            @foreach($jenisList as $j)
              <option value="{{ $j }}" {{ old('jenis_regulasi') == $j ? 'selected' : '' }}>{{ $j }}</option>
            @endforeach
          </select>
          @error('jenis_regulasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Versi <span class="text-danger">*</span></label>
          <input type="text" name="versi" class="form-control @error('versi') is-invalid @enderror" value="{{ old('versi') }}" placeholder="Misal: v1, v2" required maxlength="10">
          @error('versi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-12">
          <label class="form-label fw-semibold">Nomor Peraturan <span class="text-danger">*</span></label>
          <input type="text" name="nomor_peraturan" class="form-control @error('nomor_peraturan') is-invalid @enderror" value="{{ old('nomor_peraturan') }}" placeholder="Misal: Permendikbud No. 3 Tahun 2020" required maxlength="100">
          @error('nomor_peraturan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-12">
          <label class="form-label fw-semibold">Judul Peraturan <span class="text-danger">*</span></label>
          <textarea name="judul_peraturan" class="form-control @error('judul_peraturan') is-invalid @enderror" rows="2" required>{{ old('judul_peraturan') }}</textarea>
          @error('judul_peraturan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Instansi Penerbit <span class="text-danger">*</span></label>
          <input type="text" name="instansi_penerbit" class="form-control @error('instansi_penerbit') is-invalid @enderror" value="{{ old('instansi_penerbit') }}" placeholder="Misal: Kementerian Pendidikan dan Kebudayaan" required maxlength="200">
          @error('instansi_penerbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
          <label class="form-label fw-semibold">Tanggal Terbit <span class="text-danger">*</span></label>
          <input type="date" name="tanggal_terbit" class="form-control @error('tanggal_terbit') is-invalid @enderror" value="{{ old('tanggal_terbit') }}" required>
          @error('tanggal_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
          <label class="form-label fw-semibold">Tanggal Berlaku <span class="text-danger">*</span></label>
          <input type="date" name="tanggal_berlaku" class="form-control @error('tanggal_berlaku') is-invalid @enderror" value="{{ old('tanggal_berlaku') }}" required>
          @error('tanggal_berlaku') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">URL Dokumen Resmi</label>
          <input type="url" name="url_dokumen_resmi" class="form-control @error('url_dokumen_resmi') is-invalid @enderror" value="{{ old('url_dokumen_resmi') }}" placeholder="https://..." maxlength="500">
          @error('url_dokumen_resmi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Upload Dokumen PDF</label>
          <input type="file" name="dokumen_pdf" class="form-control @error('dokumen_pdf') is-invalid @enderror" accept="application/pdf">
          <small class="text-muted">Format PDF, maksimal 10 MB</small>
          @error('dokumen_pdf') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-12">
          <label class="form-label fw-semibold">Catatan</label>
          <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3">{{ old('catatan') }}</textarea>
          @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12 border-top pt-4">
          <div class="alert alert-info border-0 d-flex align-items-center">
            <i class="ti ti-info-circle fs-4 me-2 text-info"></i>
            <div>Regulasi baru akan disimpan dengan status <strong>Draft</strong>. Anda dapat menambahkan butir SN-Dikti setelahnya sebelum melakukan aktivasi.</div>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-dark px-5">
              <i class="ti ti-device-floppy me-1"></i> Simpan sebagai Draft
            </button>
            <a href="{{ route('kkni-sndikti.index') }}" class="btn btn-light border fw-semibold px-4">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</main>
@endsection
