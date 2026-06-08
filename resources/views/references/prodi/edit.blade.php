@extends('layouts.app')

@section('content')
<main class="p-2">
  <!-- Header Card -->
  <div class="card border-0 mb-6">
    <div class="card-body p-4">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="mb-1 fw-bold"><i class="ti ti-edit me-2"></i>Ubah Program Studi</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Master Data</a></li>
              <li class="breadcrumb-item"><a href="{{ route('prodi.index') }}">Program Studi</a></li>
              <li class="breadcrumb-item active" aria-current="page">Ubah</li>
            </ol>
          </nav>
        </div>
        <div class="col-auto">
          <a href="{{ route('prodi.index') }}" class="btn btn-light border fw-semibold px-4">
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

  <form action="{{ route('prodi.update', $prodi->prodiKode) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- SECTION 1: Identitas Program Studi -->
    <div class="card card-lg mb-6">
      <div class="card-header bg-white border-bottom py-3 rounded-top-4">
        <h5 class="fw-bold mb-0 d-flex align-items-center">
          <i class="ti ti-id-badge me-2 fs-3"></i> Identitas Program Studi
        </h5>
      </div>
      <div class="card-body p-4">
        <div class="row g-4">
          <div class="col-md-3">
            <label class="form-label fw-semibold">Kode Program Studi</label>
            <input type="text" class="form-control bg-light" value="{{ $prodi->prodiKode }}" readonly>
            <div class="form-text text-muted">Primary key tidak dapat diubah.</div>
          </div>
          
          <div class="col-md-3">
            <label class="form-label fw-semibold">Kode Singkat / Alias <span class="text-danger">*</span></label>
            <input type="text" name="prodiKodeUm" class="form-control @error('prodiKodeUm') is-invalid @enderror" value="{{ old('prodiKodeUm', $prodi->prodiKodeUm) }}" required>
            @error('prodiKodeUm') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Nama Resmi (Indonesian) <span class="text-danger">*</span></label>
            <input type="text" name="prodiNamaResmi" class="form-control @error('prodiNamaResmi') is-invalid @enderror" value="{{ old('prodiNamaResmi', $prodi->prodiNamaResmi) }}" required>
            @error('prodiNamaResmi') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Nama Resmi (English)</label>
            <input type="text" name="prodiNamaAsing" class="form-control" value="{{ old('prodiNamaAsing', $prodi->prodiNamaAsing) }}">
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold">Jenjang Pendidikan <span class="text-danger">*</span></label>
            <select name="prodiJjarKode" class="form-select @error('prodiJjarKode') is-invalid @enderror" required>
              <option value="">-- Pilih Jenjang --</option>
              @foreach($jenjangList as $jenjang)
                <option value="{{ $jenjang->jjarKode }}" {{ old('prodiJjarKode', $prodi->prodiJjarKode) == $jenjang->jjarKode ? 'selected' : '' }}>
                  {{ $jenjang->jjarNama }}
                </option>
              @endforeach
            </select>
            @error('prodiJjarKode') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold">Gelar Kelulusan Singkat <span class="text-danger">*</span></label>
            <input type="text" name="prodiGelarKelulusan" class="form-control @error('prodiGelarKelulusan') is-invalid @enderror" value="{{ old('prodiGelarKelulusan', $prodi->prodiGelarKelulusan) }}" required>
            @error('prodiGelarKelulusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Gelar Kelulusan Lengkap (Indonesian)</label>
            <input type="text" name="prodiGelarKelulusanFull" class="form-control" value="{{ old('prodiGelarKelulusanFull', $prodi->prodiGelarKelulusanFull) }}">
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Gelar Kelulusan Lengkap (English)</label>
            <input type="text" name="prodiGelarKelulusanFullAsing" class="form-control" value="{{ old('prodiGelarKelulusanFullAsing', $prodi->prodiGelarKelulusanFullAsing) }}">
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold">Bahasa Pengantar <span class="text-danger">*</span></label>
            <select name="prodiBahasaPengantar" class="form-select" required>
              <option value="Indonesia" {{ old('prodiBahasaPengantar', $prodi->prodiBahasaPengantar) == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
              <option value="Inggris" {{ old('prodiBahasaPengantar', $prodi->prodiBahasaPengantar) == 'Inggris' ? 'selected' : '' }}>Inggris</option>
              <option value="Bilingual" {{ old('prodiBahasaPengantar', $prodi->prodiBahasaPengantar) == 'Bilingual' ? 'selected' : '' }}>Bilingual</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold">Sistem Kredit <span class="text-danger">*</span></label>
            <select name="prodiSistemKredit" class="form-select" required>
              <option value="SKS" {{ old('prodiSistemKredit', $prodi->prodiSistemKredit) == 'SKS' ? 'selected' : '' }}>SKS</option>
              <option value="ECTS" {{ old('prodiSistemKredit', $prodi->prodiSistemKredit) == 'ECTS' ? 'selected' : '' }}>ECTS</option>
              <option value="Paket" {{ old('prodiSistemKredit', $prodi->prodiSistemKredit) == 'Paket' ? 'selected' : '' }}>Paket</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold">SKS Lulus Minimum <span class="text-danger">*</span></label>
            <input type="number" name="prodiSksLulus" class="form-control @error('prodiSksLulus') is-invalid @enderror" value="{{ old('prodiSksLulus', $prodi->prodiSksLulus) }}" required>
            @error('prodiSksLulus') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold">Maksimal Masa Studi (Sem.) <span class="text-danger">*</span></label>
            <input type="number" name="prodiMaksMasaStudi" class="form-control @error('prodiMaksMasaStudi') is-invalid @enderror" value="{{ old('prodiMaksMasaStudi', $prodi->prodiMaksMasaStudi) }}" required>
            @error('prodiMaksMasaStudi') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Email Resmi Prodi</label>
            <input type="email" name="prodiEmail" class="form-control @error('prodiEmail') is-invalid @enderror" value="{{ old('prodiEmail', $prodi->prodiEmail) }}">
            @error('prodiEmail') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Website Resmi</label>
            <input type="url" name="prodiWebsite" class="form-control @error('prodiWebsite') is-invalid @enderror" value="{{ old('prodiWebsite', $prodi->prodiWebsite) }}">
            @error('prodiWebsite') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-2">
            <label class="form-label fw-semibold">Bidang Ilmu</label>
            <select name="prodiIsEksakta" class="form-select">
              <option value="1" {{ old('prodiIsEksakta', $prodi->prodiIsEksakta) == '1' ? 'selected' : '' }}>Eksakta</option>
              <option value="0" {{ old('prodiIsEksakta', $prodi->prodiIsEksakta) == '0' ? 'selected' : '' }}>Non-Eksakta</option>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label fw-semibold">Kampus Merdeka <span class="text-danger">*</span></label>
            <select name="prodiIsKampusMerdeka" class="form-select" required>
              <option value="Ya" {{ old('prodiIsKampusMerdeka', $prodi->prodiIsKampusMerdeka) == 'Ya' ? 'selected' : '' }}>Ya</option>
              <option value="Tidak" {{ old('prodiIsKampusMerdeka', $prodi->prodiIsKampusMerdeka) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- SECTION 2: Kelembagaan & Legalitas -->
    <div class="card card-lg mb-6">
      <div class="card-header bg-white border-bottom py-3 rounded-top-4">
        <h5 class="fw-bold mb-0 d-flex align-items-center">
          <i class="ti ti-building me-2 fs-3"></i> Kelembagaan & Legalitas
        </h5>
      </div>
      <div class="card-body p-4">
        <div class="row g-4">
          <div class="col-md-4">
            <label class="form-label fw-semibold">Fakultas Induk <span class="text-danger">*</span></label>
            <select name="prodiFakKode" class="form-select @error('prodiFakKode') is-invalid @enderror" required>
              <option value="">-- Pilih Fakultas --</option>
              @foreach($fakultasList as $fakultas)
                <option value="{{ $fakultas->fakKode }}" {{ old('prodiFakKode', $prodi->prodiFakKode) == $fakultas->fakKode ? 'selected' : '' }}>
                  {{ $fakultas->fakNama }}
                </option>
              @endforeach
            </select>
            @error('prodiFakKode') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Departemen / Jurusan</label>
            <select name="prodiJurKode" class="form-select">
              <option value="">-- Pilih Jurusan --</option>
              @foreach($jurusanList as $jurusan)
                <option value="{{ $jurusan->jurKode }}" {{ old('prodiJurKode', $prodi->prodiJurKode) == $jurusan->jurKode ? 'selected' : '' }}>
                  {{ $jurusan->jurNama }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Program Studi DIKTI <span class="text-danger">*</span></label>
            <select name="prodiProdidiktiKode" class="form-select @error('prodiProdidiktiKode') is-invalid @enderror" required>
              <option value="">-- Pilih Prodi DIKTI --</option>
              @foreach($diktiList as $dikti)
                <option value="{{ $dikti->prodidiktiKode }}" {{ old('prodiProdidiktiKode', $prodi->prodiProdidiktiKode) == $dikti->prodidiktiKode ? 'selected' : '' }}>
                  {{ $dikti->prodidiktiKode }} - {{ $dikti->prodidiktiNama }}
                </option>
              @endforeach
            </select>
            @error('prodiProdidiktiKode') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Nomor SK Pendirian / Ijin Operasional <span class="text-danger">*</span></label>
            @if($prodi->prodiNomorSkDikti)
              <input type="text" name="prodiNomorSkDikti" class="form-control bg-light" value="{{ old('prodiNomorSkDikti', $prodi->prodiNomorSkDikti) }}" readonly>
              <div class="form-text text-muted"><i class="ti ti-lock me-1"></i>Nomor SK Pendirian bersifat immutable (tidak dapat diubah).</div>
            @else
              <input type="text" name="prodiNomorSkDikti" class="form-control @error('prodiNomorSkDikti') is-invalid @enderror" value="{{ old('prodiNomorSkDikti') }}" required>
              @error('prodiNomorSkDikti') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @endif
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Tanggal SK Pendirian / Ijin <span class="text-danger">*</span></label>
            <input type="date" name="prodiTanggalSkDikti" class="form-control @error('prodiTanggalSkDikti') is-invalid @enderror" value="{{ old('prodiTanggalSkDikti', $prodi->prodiTanggalSkDikti) }}" required>
            @error('prodiTanggalSkDikti') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Tanggal Berakhir SK Pendirian</label>
            <input type="date" name="prodiTanggalBerakhirSkDikti" class="form-control" value="{{ old('prodiTanggalBerakhirSkDikti', $prodi->prodiTanggalBerakhirSkDikti) }}">
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Tanggal Berdiri Pertama / Beroperasi <span class="text-danger">*</span></label>
            <input type="date" name="prodiTanggalBerdiri" class="form-control @error('prodiTanggalBerdiri') is-invalid @enderror" value="{{ old('prodiTanggalBerdiri', $prodi->prodiTanggalBerdiri) }}" required>
            @error('prodiTanggalBerdiri') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Status Program Studi <span class="text-danger">*</span></label>
            <select name="prodiSahrKode" class="form-select @error('prodiSahrKode') is-invalid @enderror" required>
              <option value="">-- Pilih Status --</option>
              @foreach($statusList as $status)
                <option value="{{ $status->sahrKode }}" {{ old('prodiSahrKode', $prodi->prodiSahrKode) == $status->sahrKode ? 'selected' : '' }}>
                  {{ $status->sahrNama }}
                </option>
              @endforeach
            </select>
            @error('prodiSahrKode') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Model Perkuliahan</label>
            <select name="prodiModelrId" class="form-select">
              <option value="">-- Pilih Model --</option>
              @foreach($modelList as $model)
                <option value="{{ $model->modelrId }}" {{ old('prodiModelrId', $prodi->prodiModelrId) == $model->modelrId ? 'selected' : '' }}>
                  {{ $model->modelrNama }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Frekuensi Peninjauan Kurikulum</label>
            <select name="prodiFpkrKode" class="form-select">
              <option value="">-- Pilih Frekuensi --</option>
              @foreach($frekuensiList as $frekuensi)
                <option value="{{ $frekuensi->fpkrKode }}" {{ old('prodiFpkrKode', $prodi->prodiFpkrKode) == $frekuensi->fpkrKode ? 'selected' : '' }}>
                  {{ $frekuensi->fpkrNama }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Pelaksanaan Peninjauan Kurikulum</label>
            <select name="prodiPpkrKode" class="form-select">
              <option value="">-- Pilih Pelaksanaan --</option>
              @foreach($pelaksanaanList as $pelaksanaan)
                <option value="{{ $pelaksanaan->ppkrKode }}" {{ old('prodiPpkrKode', $prodi->prodiPpkrKode) == $pelaksanaan->ppkrKode ? 'selected' : '' }}>
                  {{ $pelaksanaan->ppkrNama }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Semester Mulai Berlaku</label>
            <select name="prodiSemIdStatusDihapus" class="form-select">
              <option value="">-- Pilih Semester --</option>
              <option value="20191" {{ old('prodiSemIdStatusDihapus', $prodi->prodiSemIdStatusDihapus) == '20191' ? 'selected' : '' }}>2019 - 1</option>
              <option value="20192" {{ old('prodiSemIdStatusDihapus', $prodi->prodiSemIdStatusDihapus) == '20192' ? 'selected' : '' }}>2019 - 2</option>
              <option value="20201" {{ old('prodiSemIdStatusDihapus', $prodi->prodiSemIdStatusDihapus) == '20201' ? 'selected' : '' }}>2020 - 1</option>
              <option value="20202" {{ old('prodiSemIdStatusDihapus', $prodi->prodiSemIdStatusDihapus) == '20202' ? 'selected' : '' }}>2020 - 2</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- SECTION 3: Penanggung Jawab & Kontak -->
    <div class="card card-lg mb-6">
      <div class="card-header bg-white border-bottom py-3 rounded-top-4">
        <h5 class="fw-bold mb-0 d-flex align-items-center">
          <i class="ti ti-users me-2 fs-3"></i> Penanggung Jawab & Kontak
        </h5>
      </div>
      <div class="card-body p-4">
        <div class="row g-4">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Ketua Program Studi (Kaprodi) <span class="text-danger">*</span></label>
            <select name="id_kaprodi" class="form-select @error('id_kaprodi') is-invalid @enderror" required>
              <option value="">-- Pilih Kaprodi --</option>
              @foreach($dosenList as $dosen)
                <option value="{{ $dosen->id_dosen }}" {{ old('id_kaprodi', $prodi->id_kaprodi) == $dosen->id_dosen ? 'selected' : '' }}>
                  {{ $dosen->nama_lengkap }} (NIDN: {{ $dosen->nidn ?? '-' }})
                </option>
              @endforeach
            </select>
            <div class="form-text text-muted">Berdasarkan aturan, harus merupakan dosen aktif di program studi yang sama.</div>
            @error('id_kaprodi') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Sekretaris Program Studi</label>
            <select name="id_sekretaris_prodi" class="form-select @error('id_sekretaris_prodi') is-invalid @enderror">
              <option value="">-- Pilih Sekretaris Prodi --</option>
              @foreach($dosenList as $dosen)
                <option value="{{ $dosen->id_dosen }}" {{ old('id_sekretaris_prodi', $prodi->id_sekretaris_prodi) == $dosen->id_dosen ? 'selected' : '' }}>
                  {{ $dosen->nama_lengkap }} (NIDN: {{ $dosen->nidn ?? '-' }})
                </option>
              @endforeach
            </select>
            @error('id_sekretaris_prodi') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Nama Staff Operator</label>
            <input type="text" name="prodiOperatorNama" class="form-control" value="{{ old('prodiOperatorNama', $prodi->prodiOperatorNama) }}" placeholder="Nama Operator">
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">No Telp Operator</label>
            <input type="text" name="prodiOperatorNoHp" class="form-control" value="{{ old('prodiOperatorNoHp', $prodi->prodiOperatorNoHp) }}" placeholder="No HP Operator">
          </div>

          <div class="col-md-12">
            <label class="form-label fw-semibold">Alamat Kantor Prodi</label>
            <textarea name="prodiAlamat" class="form-control" rows="2" placeholder="Gedung, lantai, jalan...">{{ old('prodiAlamat', $prodi->prodiAlamat) }}</textarea>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Telepon Kantor</label>
            <input type="text" name="prodiTelp" class="form-control" value="{{ old('prodiTelp', $prodi->prodiTelp) }}" placeholder="Contoh: 021-...">
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Fax</label>
            <input type="text" name="prodiFax" class="form-control" value="{{ old('prodiFax', $prodi->prodiFax) }}" placeholder="Contoh: 021-...">
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Kontak Person / Humas</label>
            <input type="text" name="prodiKontakPerson" class="form-control" value="{{ old('prodiKontakPerson', $prodi->prodiKontakPerson) }}" placeholder="Nama Kontak Humas">
          </div>
        </div>
      </div>
    </div>

    <!-- Form Actions -->
    <div class="d-flex justify-content-end gap-2 mb-6">
      <a href="{{ route('prodi.index') }}" class="btn btn-light border fw-semibold px-4 py-2">Batal</a>
      <button type="submit" class="btn btn-primary fw-semibold px-4 py-2">Simpan</button>
    </div>
  </form>
</main>
@endsection
