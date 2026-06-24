@extends('layouts.app')

@section('content')
<main class="p-2">
  <!-- Top Action & Header -->
  <div class="card border-1 mb-2">
    <div class="card-body p-4">
      <div class="row mb-3 align-items-center">
        <div class="col">
          <h3 class="mb-1 fw-bold text-dark">Tambah Kurikulum</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Master Data</a></li>
              <li class="breadcrumb-item"><a href="{{ route('curiculum.index') }}">Kurikulum</a></li>
              <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
          </nav>
        </div>
        <div class="col-auto">
          <a href="{{ route('curiculum.index') }}" class="btn btn-light border fw-semibold text-dark">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="card card-lg">
    <div class="card-body p-4">
      
      @if ($errors->any())
        <div class="alert alert-danger rounded-3">
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('curiculum.store') }}" method="POST">
        @csrf
        
        <div class="row g-4">
          <!-- Column 1 -->
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">Program Studi <span class="text-danger">*</span></label>
              <select name="kurProdiKode" class="form-select @error('kurProdiKode') is-invalid @enderror" required>
                <option value="">-- Pilih Program Studi --</option>
                @foreach($prodiList as $prodi)
                  <option value="{{ $prodi->prodiKode }}" {{ old('kurProdiKode') == $prodi->prodiKode ? 'selected' : '' }}>
                    {{ $prodi->prodiNamaResmi }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">Kode Kurikulum <span class="text-danger">*</span></label>
              <input type="text" name="kurKode" class="form-control @error('kurKode') is-invalid @enderror" value="{{ old('kurKode') }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">Nama Kurikulum <span class="text-danger">*</span></label>
              <input type="text" name="kurNama" class="form-control @error('kurNama') is-invalid @enderror" value="{{ old('kurNama') }}" required>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-dark">Tahun Mulai <span class="text-danger">*</span></label>
                <input type="number" name="kurTahunMulai" class="form-control @error('kurTahunMulai') is-invalid @enderror" value="{{ old('kurTahunMulai') }}" min="1900" max="2100" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-dark">Tahun Selesai</label>
                <input type="number" name="kurTahunSelesai" class="form-control" value="{{ old('kurTahunSelesai') }}" min="1900" max="2100">
              </div>
            </div>
            
            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">Status Aktif/Hapus</label>
              <select name="kurIsAktif" class="form-select">
                <option value="1" {{ old('kurIsAktif') == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('kurIsAktif') == '0' ? 'selected' : '' }}>Non-Aktif</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">Jenis Kurikulum</label>
              <select name="kurJenis" class="form-select">
                @foreach(['OBE','KBK','Konvensional'] as $jenis)
                  <option value="{{ $jenis }}" {{ old('kurJenis', 'OBE') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                @endforeach
              </select>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-dark">Nomor SK Penetapan</label>
                <input type="text" name="kurNoSk" class="form-control" value="{{ old('kurNoSk') }}" placeholder="cth. SK/001/PT/2026">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-dark">Tanggal Penetapan</label>
                <input type="date" name="kurTanggalPenetapan" class="form-control" value="{{ old('kurTanggalPenetapan') }}">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">Referensi KKNI</label>
              <input type="text" name="kurReferensiKkni" class="form-control" value="{{ old('kurReferensiKkni') }}" placeholder="cth. Level 6">
            </div>
          </div>

          <!-- Column 2 -->
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">Total SKS Wajib Lulus <span class="text-danger">*</span></label>
              <input type="number" name="kurSksLulus" class="form-control @error('kurSksLulus') is-invalid @enderror" value="{{ old('kurSksLulus') }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">SKS Mata Kuliah Wajib</label>
              <input type="number" name="kurSksMatakuliahWajib" class="form-control" value="{{ old('kurSksMatakuliahWajib') }}">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">SKS Mata Kuliah Pilihan</label>
              <input type="number" name="kurSksMatakuliahPilihan" class="form-control" value="{{ old('kurSksMatakuliahPilihan') }}">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">SKS Praktek</label>
              <input type="number" name="kurSksPraktek" class="form-control" value="{{ old('kurSksPraktek') }}">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-dark">Keterangan</label>
              <textarea name="kurKeterangan" class="form-control" rows="3">{{ old('kurKeterangan') }}</textarea>
            </div>
          </div>
        </div>

        <div class="mt-5 d-flex justify-content-end gap-2">
          <a href="{{ route('curiculum.index') }}" class="btn btn-white border fw-semibold px-4 py-2">Batal</a>
          <button type="submit" class="btn btn-primary fw-semibold px-4 py-2">Simpan</button>
        </div>
      </form>

    </div>
  </div>
</main>
@endsection
