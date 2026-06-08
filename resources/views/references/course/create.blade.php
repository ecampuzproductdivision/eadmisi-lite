@extends('layouts.app')

@section('content')
<main class="p-2">
  <!-- Top Action & Header -->
  <div class="card border-0 mb-2">
    <div class="card-body p-4">
      <div class="row mb-3 align-items-center">
        <div class="col">
          <h3 class="mb-1 fw-bold text-dark">Tambah Mata Kuliah</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Master Data</a></li>
              <li class="breadcrumb-item"><a href="{{ route('course.index') }}">Mata Kuliah</a></li>
              <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
          </nav>
        </div>
        <div class="col-auto">
          <a href="{{ route('course.index') }}" class="btn btn-light border fw-semibold text-dark">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="card card-lg">
    <div class="card-body p-5">
      
      @if ($errors->any())
        <div class="alert alert-danger rounded-3">
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('course.store') }}" method="POST">
        @csrf
        
        <div class="row g-4">
          <!-- Column 1 -->
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">Program Studi <span class="text-danger">*</span></label>
              <select name="prodi_id" class="form-select @error('prodi_id') is-invalid @enderror" required>
                <option value="">-- Pilih Program Studi --</option>
                @foreach($prodiList as $prodi)
                  <option value="{{ $prodi->prodiKode }}" {{ old('prodi_id') == $prodi->prodiKode ? 'selected' : '' }}>
                    {{ $prodi->prodiNamaResmi }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">Kode Mata Kuliah <span class="text-danger">*</span></label>
              <input type="text" name="mk_kode" class="form-control @error('mk_kode') is-invalid @enderror" value="{{ old('mk_kode') }}" placeholder="Contoh: INF101" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">Nama Mata Kuliah <span class="text-danger">*</span></label>
              <input type="text" name="mk_nama" class="form-control @error('mk_nama') is-invalid @enderror" value="{{ old('mk_nama') }}" placeholder="Contoh: Pemrograman Dasar" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">Nama Mata Kuliah Asing</label>
              <input type="text" name="mk_nama_asing" class="form-control" value="{{ old('mk_nama_asing') }}" placeholder="Contoh: Basic Programming">
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-dark">Singkatan</label>
                <input type="text" name="mk_singkatan" class="form-control" value="{{ old('mk_singkatan') }}" placeholder="Contoh: PBD">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-dark">Semester Penawaran <span class="text-danger">*</span></label>
                <input type="number" name="mk_semester" class="form-control @error('mk_semester') is-invalid @enderror" value="{{ old('mk_semester', 1) }}" min="1" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-dark">Jenis Mata Kuliah <span class="text-danger">*</span></label>
                <select name="mk_jenis" class="form-select" required>
                  @foreach(['Wajib', 'Pilihan', 'Wajib Peminatan', 'Pilihan Peminatan', 'Tugas Akhir/Skripsi/Thesis/Disertasi'] as $jenis)
                    <option value="{{ $jenis }}" {{ old('mk_jenis') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6 mb-3 d-flex align-items-end">
                <div class="form-check mb-2">
                  <input type="checkbox" name="mk_is_aktif" class="form-check-input" id="mk_is_aktif" value="1" {{ old('mk_is_aktif', '1') == '1' ? 'checked' : '' }}>
                  <label class="form-check-label fw-semibold text-dark" for="mk_is_aktif">Mata Kuliah Aktif</label>
                </div>
              </div>
            </div>
          </div>

          <!-- Column 2 -->
          <div class="col-md-6">
            <div class="card bg-light border-0 mb-4">
              <div class="card-body p-4">
                <h5 class="fw-bold mb-3 text-dark">Rincian Bobot SKS</h5>
                
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label text-muted small fw-semibold">SKS Tatap Muka</label>
                    <input type="number" name="mk_sks_tatap_muka" id="sks_tatap_muka" class="form-control SksInput" value="{{ old('mk_sks_tatap_muka', 0) }}" min="0" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label text-muted small fw-semibold">SKS Praktikum</label>
                    <input type="number" name="mk_sks_praktikum" id="sks_praktikum" class="form-control SksInput" value="{{ old('mk_sks_praktikum', 0) }}" min="0" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label text-muted small fw-semibold">SKS Praktek Lapangan</label>
                    <input type="number" name="mk_sks_praktek_lapangan" id="sks_praktek_lapangan" class="form-control SksInput" value="{{ old('mk_sks_praktek_lapangan', 0) }}" min="0" required>
                  </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                  <span class="fw-bold text-dark fs-6">Total Bobot SKS</span>
                  <span class="fs-4 fw-bolder text-danger"><span id="total_sks_label">0</span> SKS</span>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">Keterangan</label>
              <textarea name="mk_keterangan" class="form-control" rows="5" placeholder="Tulis deskripsi atau keterangan mata kuliah...">{{ old('mk_keterangan') }}</textarea>
            </div>
          </div>
        </div>

        <div class="mt-5 d-flex justify-content-end gap-2">
          <a href="{{ route('course.index') }}" class="btn btn-white border fw-semibold px-4 py-2">Batal</a>
          <button type="submit" class="btn btn-primary fw-semibold px-4 py-2">Simpan</button>
        </div>
      </form>

    </div>
  </div>
</main>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.SksInput');
    const totalLabel = document.getElementById('total_sks_label');

    function calculateTotalSks() {
      let total = 0;
      inputs.forEach(input => {
        const val = parseInt(input.value) || 0;
        total += val;
      });
      totalLabel.textContent = total;
    }

    inputs.forEach(input => {
      input.addEventListener('input', calculateTotalSks);
    });

    calculateTotalSks(); // Initial calculation
  });
</script>
@endpush
