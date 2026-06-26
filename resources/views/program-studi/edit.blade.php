@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6">
    <div class="col-12">
      <a href="{{ route('program-studi.index') }}" class="btn btn-soft-secondary mb-3 d-inline-flex align-items-center gap-2">
        <i class="ti ti-arrow-left fs-4"></i> Kembali
      </a>
      <h1 class="mb-1 fw-bold">Edit Program Studi</h1>
      <p class="mb-0 text-muted">Perbarui informasi program studi: {{ $programStudi->nama_prodi }}.</p>
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

  <div class="card border-1 shadow-sm">
    <div class="card-body p-5">
      <form action="{{ route('program-studi.update', $programStudi) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
          <!-- Kode Program Studi -->
          <div class="col-md-4">
            <label for="kode_prodi" class="form-label fw-semibold">Kode Program Studi <span class="text-danger">*</span></label>
            <input type="text" name="kode_prodi" id="kode_prodi" class="form-control @error('kode_prodi') is-invalid @enderror" placeholder="Contoh: TI, AK, MN" value="{{ old('kode_prodi', $programStudi->kode_prodi) }}" required maxlength="20">
            <div class="form-text">Kode unik untuk program studi (huruf kapital, tanpa spasi).</div>
            @error('kode_prodi')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Label NIM -->
          <div class="col-md-4">
            <label for="label_nim" class="form-label fw-semibold">Label NIM <span class="text-danger">*</span></label>
            <input type="text" name="label_nim" id="label_nim" class="form-control @error('label_nim') is-invalid @enderror" placeholder="Contoh: TI, AKT" value="{{ old('label_nim', $programStudi->label_nim) }}" required maxlength="50">
            <div class="form-text">Label untuk generating Nomor Induk Mahasiswa.</div>
            @error('label_nim')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Label Prodi No Pendaftaran -->
          <div class="col-md-4">
            <label for="label_prodi_no_pendaftaran" class="form-label fw-semibold">Label Prodi No Pendaftaran</label>
            <input type="text" name="label_prodi_no_pendaftaran" id="label_prodi_no_pendaftaran" class="form-control @error('label_prodi_no_pendaftaran') is-invalid @enderror" placeholder="Opsional" value="{{ old('label_prodi_no_pendaftaran', $programStudi->label_prodi_no_pendaftaran) }}" maxlength="50">
            <div class="form-text">Label untuk nomor pendaftaran (opsional).</div>
            @error('label_prodi_no_pendaftaran')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Nama Program Studi -->
          <div class="col-md-6">
            <label for="nama_prodi" class="form-label fw-semibold">Nama Program Studi <span class="text-danger">*</span></label>
            <input type="text" name="nama_prodi" id="nama_prodi" class="form-control @error('nama_prodi') is-invalid @enderror" placeholder="Contoh: Teknik Informatika" value="{{ old('nama_prodi', $programStudi->nama_prodi) }}" required maxlength="200">
            @error('nama_prodi')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Jurusan -->
          <div class="col-md-6">
            <label for="jurusan" class="form-label fw-semibold">Jurusan Program Studi <span class="text-danger">*</span></label>
            <input type="text" name="jurusan" id="jurusan" class="form-control @error('jurusan') is-invalid @enderror" placeholder="Contoh: Teknik Informatika, Akuntansi" value="{{ old('jurusan', $programStudi->jurusan) }}" required maxlength="200">
            @error('jurusan')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Jenjang Akademik -->
          <div class="col-md-4">
            <label for="jenjang_akademik" class="form-label fw-semibold">Jenjang Akademik <span class="text-danger">*</span></label>
            <select name="jenjang_akademik" id="jenjang_akademik" class="form-select @error('jenjang_akademik') is-invalid @enderror" required>
              <option value="">Pilih jenjang...</option>
              <option value="D3" {{ old('jenjang_akademik', $programStudi->jenjang_akademik) == 'D3' ? 'selected' : '' }}>D3</option>
              <option value="D4" {{ old('jenjang_akademik', $programStudi->jenjang_akademik) == 'D4' ? 'selected' : '' }}>D4</option>
              <option value="S1" {{ old('jenjang_akademik', $programStudi->jenjang_akademik) == 'S1' ? 'selected' : '' }}>S1</option>
              <option value="S2" {{ old('jenjang_akademik', $programStudi->jenjang_akademik) == 'S2' ? 'selected' : '' }}>S2</option>
              <option value="S3" {{ old('jenjang_akademik', $programStudi->jenjang_akademik) == 'S3' ? 'selected' : '' }}>S3</option>
            </select>
            @error('jenjang_akademik')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Program -->
          <div class="col-md-4">
            <label for="program" class="form-label fw-semibold">Program <span class="text-danger">*</span></label>
            <select name="program" id="program" class="form-select @error('program') is-invalid @enderror" required>
              <option value="">Pilih program...</option>
              <option value="Reguler" {{ old('program', $programStudi->program) == 'Reguler' ? 'selected' : '' }}>Reguler</option>
              <option value="Karyawan" {{ old('program', $programStudi->program) == 'Karyawan' ? 'selected' : '' }}>Karyawan</option>
            </select>
            @error('program')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Kelompok (Radio Button) -->
          <div class="col-md-4">
            <label class="form-label fw-semibold d-block">Kelompok <span class="text-danger">*</span></label>
            <div class="d-flex gap-4 mt-2">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="kelompok" id="kelompok_eksakta" value="Eksakta" {{ old('kelompok', $programStudi->kelompok ?? 'Eksakta') == 'Eksakta' ? 'checked' : '' }}>
                <label class="form-check-label" for="kelompok_eksakta">Eksakta</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="kelompok" id="kelompok_non_eksakta" value="Non Eksakta" {{ old('kelompok', $programStudi->kelompok) == 'Non Eksakta' ? 'checked' : '' }}>
                <label class="form-check-label" for="kelompok_non_eksakta">Non Eksakta</label>
              </div>
            </div>
            @error('kelompok')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <!-- Status Aktif (Radio Button) -->
          <div class="col-12">
            <label class="form-label fw-semibold d-block">Status Aktif <span class="text-danger">*</span></label>
            <div class="d-flex gap-4 mt-2">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="status_aktif" id="status_aktif_aktif" value="1" {{ old('status_aktif', $programStudi->status_aktif ? '1' : '0') == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="status_aktif_aktif">Aktif</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="status_aktif" id="status_aktif_non" value="0" {{ old('status_aktif', $programStudi->status_aktif ? '0' : '1') == '0' ? 'checked' : '' }}>
                <label class="form-check-label" for="status_aktif_non">Non Aktif</label>
              </div>
            </div>
            @error('status_aktif')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mt-5 d-flex gap-3">
          <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2 px-5">
            <i class="ti ti-device-floppy fs-4"></i> Perbarui
          </button>
          <a href="{{ route('program-studi.index') }}" class="btn btn-soft-secondary d-inline-flex align-items-center gap-2">
            Batal
          </a>
        </div>
      </form>
    </div>
  </div>
</main>
@endsection
