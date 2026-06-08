@extends('layouts.app')

@section('content')
<main class="p-2">
  <div class="card border-0 mb-6">
    <div class="card-body p-4">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="mb-1 fw-bold">Edit Data Mahasiswa</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('mahasiswa.index') }}">Mahasiswa</a></li>
              <li class="breadcrumb-item active">{{ $mahasiswa->nama_lengkap }}</li>
            </ol>
          </nav>
        </div>
        <div class="col-auto">
          <a href="{{ route('mahasiswa.show', $mahasiswa->id_mahasiswa) }}" class="btn btn-light border fw-semibold text-dark"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
        </div>
      </div>
    </div>
  </div>

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-triangle fs-4 me-2 text-danger"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <form action="{{ route('mahasiswa.update', $mahasiswa->id_mahasiswa) }}" method="POST">
    @csrf @method('PUT')

    <!-- Identitas Pribadi -->
    <div class="card card-lg mb-6">
      <div class="card-header bg-white border-bottom py-3 rounded-top-4">
        <h5 class="fw-bold mb-0"><i class="ti ti-user me-2"></i>Identitas Pribadi</h5>
      </div>
      <div class="card-body p-4">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">NIM <span class="text-danger">*</span></label>
            <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim', $mahasiswa->nim) }}" required>
            @error('nim') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">NIK <span class="text-danger">*</span></label>
            <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', $mahasiswa->nik) }}" maxlength="16" required>
            @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap) }}" required>
            @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-3">
            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
            <select name="jenis_kelamin" class="form-select" required>
              <option value="Laki-laki" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
              <option value="Perempuan" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
            <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $mahasiswa->tempat_lahir) }}" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $mahasiswa->tanggal_lahir?->format('Y-m-d')) }}" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Agama</label>
            <select name="agama" class="form-select">
              <option value="">-- Pilih --</option>
              @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'] as $a)
                <option value="{{ $a }}" {{ old('agama', $mahasiswa->agama) == $a ? 'selected' : '' }}>{{ $a }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Kewarganegaraan</label>
            <input type="text" name="kewarganegaraan" class="form-control" value="{{ old('kewarganegaraan', $mahasiswa->kewarganegaraan) }}">
          </div>
          <div class="col-md-3">
            <label class="form-label">No. Passport</label>
            <input type="text" name="no_passport" class="form-control @error('no_passport') is-invalid @enderror" value="{{ old('no_passport', $mahasiswa->no_passport) }}">
            @error('no_passport') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>
    </div>

    <!-- Akademik -->
    <div class="card card-lg mb-6">
      <div class="card-header bg-white border-bottom py-3 rounded-top-4">
        <h5 class="fw-bold mb-0"><i class="ti ti-school me-2"></i>Data Akademik</h5>
      </div>
      <div class="card-body p-4">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Program Studi <span class="text-danger">*</span></label>
            <select name="id_prodi" class="form-select" required>
              @foreach($prodiList as $p)
                <option value="{{ $p->prodiKode }}" {{ old('id_prodi', $mahasiswa->id_prodi) == $p->prodiKode ? 'selected' : '' }}>{{ $p->prodiNamaResmi }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Kurikulum <span class="text-danger">*</span></label>
            <select name="id_kurikulum" class="form-select" required>
              @foreach($kurikulumList as $k)
                <option value="{{ $k->kurKode }}" {{ old('id_kurikulum', $mahasiswa->id_kurikulum) == $k->kurKode ? 'selected' : '' }}>{{ $k->kurNama }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Dosen PA</label>
            <select name="id_dosen_pa" class="form-select">
              <option value="">-- Pilih Dosen PA --</option>
              @foreach($dosenList as $d)
                <option value="{{ $d->id_dosen }}" {{ old('id_dosen_pa', $mahasiswa->id_dosen_pa) == $d->id_dosen ? 'selected' : '' }}>{{ $d->nama_lengkap }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Tahun Masuk</label>
            <input type="number" name="tahun_masuk" class="form-control" value="{{ old('tahun_masuk', $mahasiswa->tahun_masuk) }}" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Semester Masuk</label>
            <select name="semester_masuk" class="form-select" required>
              <option value="Ganjil" {{ old('semester_masuk', $mahasiswa->semester_masuk) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
              <option value="Genap" {{ old('semester_masuk', $mahasiswa->semester_masuk) == 'Genap' ? 'selected' : '' }}>Genap</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk', $mahasiswa->tanggal_masuk?->format('Y-m-d')) }}" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Jalur Masuk</label>
            <select name="jalur_masuk" class="form-select" required>
              @foreach(['SNBP', 'SNBT', 'Mandiri', 'Pindahan', 'Alih Jenjang', 'Beasiswa'] as $j)
                <option value="{{ $j }}" {{ old('jalur_masuk', $mahasiswa->jalur_masuk) == $j ? 'selected' : '' }}>{{ $j }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Jenis Mahasiswa</label>
            <select name="jenis_mahasiswa" class="form-select" required>
              @foreach(['Reguler', 'Karyawan', 'Internasional', 'Pindahan', 'Transfer'] as $jm)
                <option value="{{ $jm }}" {{ old('jenis_mahasiswa', $mahasiswa->jenis_mahasiswa) == $jm ? 'selected' : '' }}>{{ $jm }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Status Mahasiswa</label>
            <select name="status_mahasiswa" class="form-select" required>
              @foreach(['Aktif', 'Cuti', 'Tugas Belajar', 'Non-aktif', 'DO', 'Lulus', 'Mengundurkan Diri'] as $sm)
                <option value="{{ $sm }}" {{ old('status_mahasiswa', $mahasiswa->status_mahasiswa) == $sm ? 'selected' : '' }}>{{ $sm }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Kontak & Domisili -->
    <div class="card card-lg mb-6">
      <div class="card-header bg-white border-bottom py-3 rounded-top-4">
        <h5 class="fw-bold mb-0"><i class="ti ti-map-pin me-2"></i>Kontak & Domisili</h5>
      </div>
      <div class="card-body p-4">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Email Institusi <span class="text-danger">*</span></label>
            <input type="email" name="email_institusi" class="form-control @error('email_institusi') is-invalid @enderror" value="{{ old('email_institusi', $mahasiswa->email_institusi) }}" required>
            @error('email_institusi') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">Email Pribadi</label>
            <input type="email" name="email_pribadi" class="form-control" value="{{ old('email_pribadi', $mahasiswa->email_pribadi) }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">No. HP <span class="text-danger">*</span></label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $mahasiswa->no_hp) }}" required>
          </div>
          <div class="col-md-12">
            <label class="form-label">Alamat Asal <span class="text-danger">*</span></label>
            <textarea name="alamat_asal" class="form-control" rows="2" required>{{ old('alamat_asal', $mahasiswa->alamat_asal) }}</textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label">Kota Asal <span class="text-danger">*</span></label>
            <input type="text" name="kota_asal" class="form-control" value="{{ old('kota_asal', $mahasiswa->kota_asal) }}" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Provinsi Asal <span class="text-danger">*</span></label>
            <input type="text" name="provinsi_asal" class="form-control" value="{{ old('provinsi_asal', $mahasiswa->provinsi_asal) }}" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Kode Pos</label>
            <input type="text" name="kode_pos_asal" class="form-control" value="{{ old('kode_pos_asal', $mahasiswa->kode_pos_asal) }}">
          </div>
          <div class="col-md-8">
            <label class="form-label">Alamat Domisili</label>
            <textarea name="alamat_domisili" class="form-control" rows="2">{{ old('alamat_domisili', $mahasiswa->alamat_domisili) }}</textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label">Kota Domisili</label>
            <input type="text" name="kota_domisili" class="form-control" value="{{ old('kota_domisili', $mahasiswa->kota_domisili) }}">
          </div>
        </div>
      </div>
    </div>

    <!-- Data Keluarga -->
    <div class="card card-lg mb-6">
      <div class="card-header bg-white border-bottom py-3 rounded-top-4">
        <h5 class="fw-bold mb-0"><i class="ti ti-users me-2"></i>Data Keluarga</h5>
      </div>
      <div class="card-body p-4">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Nama Ayah <span class="text-danger">*</span></label>
            <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $mahasiswa->nama_ayah) }}" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Nama Ibu <span class="text-danger">*</span></label>
            <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $mahasiswa->nama_ibu) }}" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">No. HP Orang Tua <span class="text-danger">*</span></label>
            <input type="text" name="no_hp_ortu" class="form-control" value="{{ old('no_hp_ortu', $mahasiswa->no_hp_ortu) }}" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Pekerjaan Ayah</label>
            <input type="text" name="pekerjaan_ayah" class="form-control" value="{{ old('pekerjaan_ayah', $mahasiswa->pekerjaan_ayah) }}">
          </div>
          <div class="col-md-3">
            <label class="form-label">Pekerjaan Ibu</label>
            <input type="text" name="pekerjaan_ibu" class="form-control" value="{{ old('pekerjaan_ibu', $mahasiswa->pekerjaan_ibu) }}">
          </div>
          <div class="col-md-3">
            <label class="form-label">Penghasilan Orang Tua</label>
            <select name="penghasilan_ortu" class="form-select">
              <option value="">-- Pilih --</option>
              @foreach(['< 1 juta', '1-3 juta', '3-6 juta', '6-10 juta', '> 10 juta'] as $ph)
                <option value="{{ $ph }}" {{ old('penghasilan_ortu', $mahasiswa->penghasilan_ortu) == $ph ? 'selected' : '' }}>{{ $ph }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Nama Wali</label>
            <input type="text" name="nama_wali" class="form-control" value="{{ old('nama_wali', $mahasiswa->nama_wali) }}">
          </div>
          <div class="col-md-3">
            <label class="form-label">Hubungan Wali</label>
            <input type="text" name="hubungan_wali" class="form-control" value="{{ old('hubungan_wali', $mahasiswa->hubungan_wali) }}">
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mb-4">
      <a href="{{ route('mahasiswa.show', $mahasiswa->id_mahasiswa) }}" class="btn btn-light border px-4">Batal</a>
      <button type="submit" class="btn btn-primary px-5">Simpan</button>
    </div>
  </form>
</main>
@endsection
