@extends('layouts.app')

@section('content')
<main class="p-2">
  <div class="card border-1 mb-4">
    <div class="card-body py-4">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="mb-1 fw-bold">Ubah Data Dosen</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('dosen.index') }}">Dosen</a></li>
              <li class="breadcrumb-item active" aria-current="page">Ubah</li>
            </ol>
          </nav>
        </div>
        <div class="col-auto">
          <a href="{{ route('dosen.show', $dosen->id_dosen) }}" class="btn btn-light border fw-semibold text-dark"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
        </div>
      </div>
    </div>
  </div>

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-triangle fs-4 me-2"></i>
      Ada beberapa kesalahan dalam isian form Anda:
      <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <form action="{{ route('dosen.update', $dosen->id_dosen) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    @method('PUT')

    <div class="row g-4">
      <!-- Identitas Pribadi -->
      <div class="col-12 col-xl-6">
        <div class="card h-100 card-lg mb-6">
          <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <h5 class="mb-0 fw-bold"><i class="ti ti-id text-primary me-2"></i>Identitas Pribadi</h5>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">NIK <span class="text-danger">*</span></label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik', $dosen->nik) }}" maxlength="16" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">NIDN</label>
                <input type="text" name="nidn" class="form-control" value="{{ old('nidn', $dosen->nidn) }}" maxlength="10">
              </div>
              <div class="col-md-6">
                <label class="form-label">NIDK</label>
                <input type="text" name="nidk" class="form-control" value="{{ old('nidk', $dosen->nidk) }}" maxlength="10">
              </div>
              <div class="col-md-6">
                <label class="form-label">NUPN</label>
                <input type="text" name="nupn" class="form-control" value="{{ old('nupn', $dosen->nupn) }}" maxlength="10">
              </div>

              <div class="col-12">
                <label class="form-label">Nama Lengkap (Tanpa Gelar) <span class="text-danger">*</span></label>
                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $dosen->nama_lengkap) }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Gelar Depan</label>
                <input type="text" name="gelar_depan" class="form-control" value="{{ old('gelar_depan', $dosen->gelar_depan) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Gelar Belakang</label>
                <input type="text" name="gelar_belakang" class="form-control" value="{{ old('gelar_belakang', $dosen->gelar_belakang) }}">
              </div>
              
              <div class="col-md-6">
                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="jenis_kelamin" class="form-select" required>
                  <option value="">-- Pilih --</option>
                  <option value="Laki-laki" {{ old('jenis_kelamin', $dosen->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                  <option value="Perempuan" {{ old('jenis_kelamin', $dosen->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $dosen->tempat_lahir) }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $dosen->tanggal_lahir) }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Agama</label>
                <select name="agama" class="form-select">
                  <option value="">-- Pilih --</option>
                  @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'] as $agm)
                    <option value="{{ $agm }}" {{ old('agama', $dosen->agama) == $agm ? 'selected' : '' }}>{{ $agm }}</option>
                  @endforeach
                </select>
              </div>
              
              <div class="col-md-12">
                <label class="form-label">Foto Profil</label>
                @if($dosen->foto)
                  <div class="mb-2">
                    <img src="{{ asset('storage/' . $dosen->foto) }}" alt="Foto" width="60" class="rounded">
                  </div>
                @endif
                <input type="file" name="foto" class="form-control" accept="image/*">
                <div class="form-text">Maksimal 5MB, format JPG/PNG. Biarkan kosong jika tidak ingin mengubah foto.</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Kepegawaian & Akademik -->
      <div class="col-12 col-xl-6">
        <div class="card h-100 card-lg mb-6">
          <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <h5 class="mb-0 fw-bold"><i class="ti ti-briefcase text-primary me-2"></i>Kepegawaian & Akademik</h5>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Program Studi Homebase <span class="text-danger">*</span></label>
                <select name="id_prodi" class="form-select" required>
                  <option value="">-- Pilih Program Studi --</option>
                  @foreach($prodiList as $p)
                    <option value="{{ $p->prodiKode }}" {{ old('id_prodi', $dosen->id_prodi) == $p->prodiKode ? 'selected' : '' }}>
                      {{ $p->prodiKode }} - {{ $p->prodiNamaResmi }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Jenis Dosen <span class="text-danger">*</span></label>
                <select name="jenis_dosen" class="form-select" required>
                  @foreach(['Tetap', 'Tidak Tetap', 'Luar Biasa', 'Tamu'] as $jd)
                    <option value="{{ $jd }}" {{ old('jenis_dosen', $dosen->jenis_dosen) == $jd ? 'selected' : '' }}>{{ $jd }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Status Kepegawaian <span class="text-danger">*</span></label>
                <select name="status_kepegawaian" class="form-select" required>
                  @foreach(['PNS', 'PPPK', 'Non-PNS', 'Kontrak'] as $sk)
                    <option value="{{ $sk }}" {{ old('status_kepegawaian', $dosen->status_kepegawaian) == $sk ? 'selected' : '' }}>{{ $sk }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Status Dosen <span class="text-danger">*</span></label>
                <select name="status_dosen" class="form-select" required>
                  @foreach(['Aktif', 'Cuti', 'Tugas Belajar', 'Pensiun', 'Wafat', 'Nonaktif'] as $sd)
                    <option value="{{ $sd }}" {{ old('status_dosen', $dosen->status_dosen) == $sd ? 'selected' : '' }}>{{ $sd }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Mulai Bertugas <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_mulai_bertugas" class="form-control" value="{{ old('tanggal_mulai_bertugas', $dosen->tanggal_mulai_bertugas) }}" required>
              </div>

              <div class="col-md-4">
                <label class="form-label">Jenjang Terakhir <span class="text-danger">*</span></label>
                <select name="jenjang_pendidikan_terakhir" class="form-select" required>
                  <option value="">-- Pilih --</option>
                  @foreach(['S1', 'S2', 'S3'] as $jenjang)
                    <option value="{{ $jenjang }}" {{ old('jenjang_pendidikan_terakhir', $dosen->jenjang_pendidikan_terakhir) == $jenjang ? 'selected' : '' }}>{{ $jenjang }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-8">
                <label class="form-label">Bidang Studi Terakhir <span class="text-danger">*</span></label>
                <input type="text" name="bidang_studi_terakhir" class="form-control" value="{{ old('bidang_studi_terakhir', $dosen->bidang_studi_terakhir) }}" required>
              </div>
              <div class="col-12">
                <label class="form-label">Perguruan Tinggi Asal <span class="text-danger">*</span></label>
                <input type="text" name="perguruan_tinggi_asal" class="form-control" value="{{ old('perguruan_tinggi_asal', $dosen->perguruan_tinggi_asal) }}" required>
              </div>

              <div class="col-12 border-top my-2 pt-2"></div>

              <div class="col-md-6">
                <label class="form-label">Jabatan Fungsional</label>
                <select name="jabatan_fungsional" class="form-select">
                  <option value="">-- Belum Ada --</option>
                  @foreach(['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'] as $jf)
                    <option value="{{ $jf }}" {{ old('jabatan_fungsional', $dosen->jabatan_fungsional) == $jf ? 'selected' : '' }}>{{ $jf }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">TMT Jabatan</label>
                <input type="date" name="tmt_jabatan_fungsional" class="form-control" value="{{ old('tmt_jabatan_fungsional', $dosen->tmt_jabatan_fungsional) }}">
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Kontak & Sertifikasi -->
      <div class="col-12">
        <div class="card card-lg mb-6">
          <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <h5 class="mb-0 fw-bold"><i class="ti ti-certificate text-primary me-2"></i>Kontak & Sertifikasi</h5>
          </div>
          <div class="card-body">
            <div class="row g-4">
              <!-- Akun & Kontak -->
              <div class="col-md-6">
                <div class="row g-3">
                  <div class="col-12">
                    <label class="form-label">Email Institusi <span class="text-danger">*</span></label>
                    <input type="email" name="email_institusi" class="form-control" value="{{ old('email_institusi', $dosen->email_institusi) }}" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Email Pribadi</label>
                    <input type="email" name="email_pribadi" class="form-control" value="{{ old('email_pribadi', $dosen->email_pribadi) }}">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">No. Handphone <span class="text-danger">*</span></label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $dosen->no_hp) }}" required>
                  </div>
                  <div class="col-12">
                    <label class="form-label">Alamat Domisili</label>
                    <textarea name="alamat_domisili" class="form-control" rows="2">{{ old('alamat_domisili', $dosen->alamat_domisili) }}</textarea>
                  </div>
                </div>
              </div>

              <!-- Sertifikasi -->
              <div class="col-md-6">
                <div class="row g-3 ps-md-3">
                  <div class="col-12">
                    <div class="form-check form-switch mt-2">
                      <input class="form-check-input" type="checkbox" role="switch" id="is_sertifikasi" name="is_sertifikasi_dosen" value="1" {{ old('is_sertifikasi_dosen', $dosen->is_sertifikasi_dosen) ? 'checked' : '' }}>
                      <label class="form-check-label fw-bold" for="is_sertifikasi">Tersertifikasi Pendidik (Serdos)</label>
                    </div>
                  </div>
                  <div class="col-12 serdos-field" style="display: none;">
                    <label class="form-label">No. Sertifikasi <span class="text-danger">*</span></label>
                    <input type="text" name="no_sertifikasi_dosen" class="form-control" value="{{ old('no_sertifikasi_dosen', $dosen->no_sertifikasi_dosen) }}" id="no_serdos">
                  </div>
                  <div class="col-md-6 serdos-field" style="display: none;">
                    <label class="form-label">Tahun Sertifikasi <span class="text-danger">*</span></label>
                    <input type="number" name="tahun_sertifikasi" class="form-control" value="{{ old('tahun_sertifikasi', $dosen->tahun_sertifikasi) }}" min="1900" id="th_serdos">
                  </div>
                  <div class="col-md-6 serdos-field" style="display: none;">
                    <label class="form-label">Bidang Sertifikasi <span class="text-danger">*</span></label>
                    <input type="text" name="bidang_sertifikasi" class="form-control" value="{{ old('bidang_sertifikasi', $dosen->bidang_sertifikasi) }}" id="bid_serdos">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Actions -->
    <div class="d-flex justify-content-end gap-2 mt-4 mb-5">
      <a href="{{ route('dosen.show', $dosen->id_dosen) }}" class="btn btn-light border px-4">Batal</a>
      <button type="submit" class="btn btn-primary px-5"><i class="ti ti-device-floppy me-2"></i>Simpan</button>
    </div>
  </form>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const serdosCheck = document.getElementById('is_sertifikasi');
  const serdosFields = document.querySelectorAll('.serdos-field');
  const noSerdos = document.getElementById('no_serdos');
  const thSerdos = document.getElementById('th_serdos');
  const bidSerdos = document.getElementById('bid_serdos');

  function toggleSerdos() {
    const isChecked = serdosCheck.checked;
    serdosFields.forEach(el => {
      el.style.display = isChecked ? 'block' : 'none';
    });
    
    if (isChecked) {
      noSerdos.setAttribute('required', 'required');
      thSerdos.setAttribute('required', 'required');
      bidSerdos.setAttribute('required', 'required');
    } else {
      noSerdos.removeAttribute('required');
      thSerdos.removeAttribute('required');
      bidSerdos.removeAttribute('required');
    }
  }

  serdosCheck.addEventListener('change', toggleSerdos);
  toggleSerdos(); // initial run
});
</script>
@endsection
