@extends('layouts.app')

@section('content')
<main class="p-2">
  <!-- Header Card -->
  <div class="card border-0 mb-6">
    <div class="card-body p-4">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="mb-1 fw-bold">Tambah Tahun Akademik</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Master Data</a></li>
              <li class="breadcrumb-item"><a href="{{ route('tahun-akademik.index') }}">Tahun Akademik</a></li>
              <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
          </nav>
        </div>
        <div class="col-auto">
          <a href="{{ route('tahun-akademik.index') }}" class="btn btn-light border fw-semibold px-4">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-6" role="alert">
      <div class="d-flex align-items-center">
        <i class="ti ti-alert-triangle fs-4 me-3 text-danger"></i>
        <div>
          <h6 class="fw-bold mb-1">Periksa input Anda:</h6>
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <form action="{{ route('tahun-akademik.store') }}" method="POST">
    @csrf

    <div class="card card-lg mb-6">
      <div class="card-header bg-white border-bottom py-3 rounded-top-4">
        <h5 class="fw-bold mb-0 d-flex align-items-center">
          <i class="ti ti-calendar-stats me-2 fs-3"></i> Informasi Tahun Akademik
        </h5>
      </div>
      <div class="card-body p-4">
        <div class="row g-4">
          <div class="col-md-4">
            <label class="form-label fw-semibold">Kode TA <span class="text-danger">*</span></label>
            <input type="text" name="kode_ta" class="form-control @error('kode_ta') is-invalid @enderror" value="{{ old('kode_ta') }}" placeholder="Contoh: 2026/1" required>
            <div class="form-text text-muted">Format: [TahunMulai]/[KodeSemester]. Contoh: 2026/1, 2026/2, 2026/3</div>
            @error('kode_ta') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-8">
            <label class="form-label fw-semibold">Nama TA <span class="text-danger">*</span></label>
            <input type="text" name="nama_ta" class="form-control @error('nama_ta') is-invalid @enderror" value="{{ old('nama_ta') }}" placeholder="Contoh: 2026/2027 Ganjil" required>
            @error('nama_ta') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold">Tahun Mulai <span class="text-danger">*</span></label>
            <input type="number" name="tahun_mulai" class="form-control @error('tahun_mulai') is-invalid @enderror" value="{{ old('tahun_mulai', date('Y')) }}" min="1900" max="2099" required>
            @error('tahun_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold">Tahun Selesai <span class="text-danger">*</span></label>
            <input type="number" name="tahun_selesai" class="form-control @error('tahun_selesai') is-invalid @enderror" value="{{ old('tahun_selesai', date('Y') + 1) }}" min="1900" max="2099" required>
            @error('tahun_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold">Jenis Semester <span class="text-danger">*</span></label>
            <select name="jenis_semester" class="form-select @error('jenis_semester') is-invalid @enderror" required>
              <option value="">-- Pilih Semester --</option>
              <option value="GANJIL" {{ old('jenis_semester') == 'GANJIL' ? 'selected' : '' }}>Ganjil</option>
              <option value="GENAP" {{ old('jenis_semester') == 'GENAP' ? 'selected' : '' }}>Genap</option>
              <option value="PENDEK" {{ old('jenis_semester') == 'PENDEK' ? 'selected' : '' }}>Pendek</option>
            </select>
            @error('jenis_semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold">Jumlah Minggu Efektif <span class="text-danger">*</span></label>
            <input type="number" name="jumlah_minggu_efektif" class="form-control @error('jumlah_minggu_efektif') is-invalid @enderror" value="{{ old('jumlah_minggu_efektif', 16) }}" min="1" max="30" required>
            @error('jumlah_minggu_efektif') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Tanggal Mulai Semester <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai') }}" required>
            @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Tanggal Selesai Semester <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai') }}" required>
            @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-12">
            <label class="form-label fw-semibold">Catatan</label>
            <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="2" placeholder="Catatan khusus atau kebijakan semester ini (opsional)">{{ old('catatan') }}</textarea>
            @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>
    </div>

    <!-- Form Actions -->
    <div class="d-flex justify-content-end gap-2 mb-6">
      <a href="{{ route('tahun-akademik.index') }}" class="btn btn-light border fw-semibold px-4 py-2">Batal</a>
      <button type="submit" class="btn btn-primary fw-semibold px-4 py-2">Simpan</button>
    </div>
  </form>
</main>
@endsection