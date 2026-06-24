@extends('layouts.app')

@section('content')
<main class="p-2">
  <!-- Top Action & Header -->
  <div class="card border-1 mb-3">
    <div class="card-body p-4">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="mb-1 fw-bold text-dark">Program Studi</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Master Data</a></li>
              <li class="breadcrumb-item"><a href="{{ route('prodi.index') }}">Program Studi</a></li>
              <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
          </nav>
        </div>
        <div class="col-auto">
          <a href="{{ route('prodi.index') }}" class="btn btn-light border fw-semibold text-dark">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
      <i class="ti ti-circle-check fs-4 me-2 text-success"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
      <i class="ti ti-alert-triangle fs-4 me-2 text-danger"></i>
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
      <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Header Info Panel -->
  <div class="card card-lg mb-4">
    <div class="card-body p-4">
      <div class="d-flex align-items-center gap-3">
        <div class="bg-primary-subtle text-primary rounded-3 p-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
          <i class="ti ti-school fs-1"></i>
        </div>
        <div>
          <h4 class="fw-bold mb-1 text-dark">{{ $prodi->prodiNamaResmi }}</h4>
          <p class="text-muted mb-0">
            Kode: <span class="fw-semibold text-dark">{{ $prodi->prodiKodeUm ?: '-' }}</span> &bull; 
            Jenjang: <span class="fw-semibold text-dark">{{ $prodi->jenjang ? $prodi->jenjang->jjarNama : '-' }}</span> &bull;
            Fakultas: <span class="fw-semibold text-dark">{{ $prodi->fakultas ? $prodi->fakultas->fakNama : '-' }}</span>
          </p>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
          @php
            $activeAkr = $akreditasis->firstWhere('prodiakrIsDipakai', true);
          @endphp
          @if($activeAkr)
            <div class="d-flex flex-column text-end">
              <span class="small text-muted">Akreditasi Aktif</span>
              <span class="badge bg-success fs-7 px-3 py-2 mt-1">
                {{ $activeAkr->akreditasiRef ? $activeAkr->akreditasiRef->akrrNama : $activeAkr->prodiakrAkrrKode }} ({{ $activeAkr->prodiakrLembaga }})
              </span>
            </div>
          @else
            <span class="badge bg-secondary fs-7 px-3 py-2">Belum Terakreditasi</span>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Tabs Navigation -->
      <ul class="nav nav-lb-tab border-bottom" id="prodiTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active py-3 fw-bold d-flex align-items-center justify-content-center gap-2" id="identitas-tab" data-bs-toggle="tab" data-bs-target="#identitas" type="button" role="tab" aria-controls="identitas" aria-selected="true">
            <i class="ti ti-id-badge fs-5"></i> Identitas & Legalitas
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link py-3 fw-bold d-flex align-items-center justify-content-center gap-2" id="akreditasi-tab" data-bs-toggle="tab" data-bs-target="#akreditasi" type="button" role="tab" aria-controls="akreditasi" aria-selected="false">
            <i class="ti ti-award fs-5"></i> Riwayat Akreditasi
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link py-3 fw-bold d-flex align-items-center justify-content-center gap-2" id="visi-misi-tab" data-bs-toggle="tab" data-bs-target="#visi-misi" type="button" role="tab" aria-controls="visi-misi" aria-selected="false">
            <i class="ti ti-target fs-5"></i> Visi, Misi & Tujuan
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link py-3 fw-bold d-flex align-items-center justify-content-center gap-2" id="profil-lulusan-tab" data-bs-toggle="tab" data-bs-target="#profil-lulusan" type="button" role="tab" aria-controls="profil-lulusan" aria-selected="false">
            <i class="ti ti-school fs-5"></i> Profil Lulusan
          </button>
        </li>
      </ul>

    <div class="card-body p-4">
      <div class="tab-content" id="prodiTabsContent">
        
        <!-- TAB 1: IDENTITAS & LEGALITAS -->
        <div class="tab-pane fade show active" id="identitas" role="tabpanel" aria-labelledby="identitas-tab">
          <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center border-bottom pb-2">
              <h5 class="fw-bold mb-0 text-dark">Informasi Dasar</h5>
              <a href="{{ route('prodi.edit', $prodi->prodiKode) }}" class="btn btn-dark btn-sm fw-semibold">
                <i class="ti ti-edit me-1"></i> Ubah Identitas
              </a>
            </div>
          </div>

          <div class="row g-4 mb-5">
            <div class="col-md-6">
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Nama Resmi</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiNamaResmi ?: '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Kode Program Studi</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiKodeUm ?: '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Program Studi DIKTI</span>
                <span class="d-block text-dark fw-bold">{{ $dikti ? $dikti->prodidiktiKode . ' - ' . $dikti->prodidiktiNama : '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Fakultas</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->fakultas ? strtoupper($prodi->fakultas->fakNama) : '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Status Aktif</span>
                <span class="d-block text-dark fw-bold">
                  @if($prodi->statusAktifHapus)
                    <span class="badge {{ $prodi->statusAktifHapus->sahrKode === 'A' ? 'bg-success' : 'bg-danger' }}">{{ $prodi->statusAktifHapus->sahrNama }}</span>
                  @else
                    -
                  @endif
                </span>
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Nama Asing</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiNamaAsing ?: '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Jenjang</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->jenjang ? $prodi->jenjang->jjarNama : '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Jumlah SKS Lulus</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiSksLulus ?: '-' }} SKS</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Jurusan</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->jurusan ? strtoupper($prodi->jurusan->jurNama) : '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Kampus Merdeka</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiIsKampusMerdeka ?: '-' }}</span>
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-12 border-bottom pb-2">
              <h5 class="fw-bold mb-0 text-dark">Informasi Akademik & Kurikulum</h5>
            </div>
          </div>

          <div class="row g-4 mb-5">
            <div class="col-md-6">
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Model Perkuliahan</span>
                <span class="d-block text-dark fw-bold">{{ $model ? $model->modelrNama : '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Frekuensi Peninjauan</span>
                <span class="d-block text-dark fw-bold">{{ $frekuensi ? $frekuensi->fpkrNama : '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Pelaksanaan Peninjauan</span>
                <span class="d-block text-dark fw-bold">{{ $pelaksanaan ? $pelaksanaan->ppkrNama : '-' }}</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Tanggal Berdiri</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiTanggalBerdiri ? \Carbon\Carbon::parse($prodi->prodiTanggalBerdiri)->format('d F Y') : '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Semester Mulai</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiSemIdStatusDihapus ?: '-' }}</span>
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-12 border-bottom pb-2">
              <h5 class="fw-bold mb-0 text-dark">S.K. Ijin Operasional DIKTI</h5>
            </div>
          </div>

          <div class="row g-4 mb-5">
            <div class="col-md-4">
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Nomor SK</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiNomorSkDikti ?: '-' }}</span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Tanggal SK Dikeluarkan</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiTanggalSkDikti ? \Carbon\Carbon::parse($prodi->prodiTanggalSkDikti)->format('d F Y') : '-' }}</span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Tanggal Batas Akhir</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiTanggalBerakhirSkDikti ? \Carbon\Carbon::parse($prodi->prodiTanggalBerakhirSkDikti)->format('d F Y') : '-' }}</span>
              </div>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-12 border-bottom pb-2">
              <h5 class="fw-bold mb-0 text-dark">Kontak & Penanggung Jawab</h5>
            </div>
          </div>

          <div class="row g-4">
            <div class="col-md-6">
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Kepala Prodi</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiKetuaProdiNama ?: '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">No. Telp Kepala Prodi</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiKetuaProdiNoHp ?: '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Email Institusi</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiEmail ?: '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Website</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiWebsite ?: '-' }}</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Nama Operator</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiOperatorNama ?: '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">No. Telp Operator</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiOperatorNoHp ?: '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Alamat</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiAlamat ?: '-' }}</span>
              </div>
              <div class="mb-3 border-bottom pb-2">
                <span class="d-block text-muted small fw-semibold">Telepon / Fax</span>
                <span class="d-block text-dark fw-bold">{{ $prodi->prodiTelp ?: '-' }} / {{ $prodi->prodiFax ?: '-' }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- TAB 2: RIWAYAT AKREDITASI -->
        <div class="tab-pane fade" id="akreditasi" role="tabpanel" aria-labelledby="akreditasi-tab">
          <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h5 class="fw-bold mb-0 text-dark">Riwayat Akreditasi Program Studi</h5>
            <button type="button" class="btn btn-dark btn-sm fw-semibold d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addAkreditasiModal">
              <i class="ti ti-plus fs-5"></i> Tambah Akreditasi
            </button>
          </div>

          <div class="table-responsive">
            <table class="table align-middle text-nowrap mb-0 table-hover table-dotted">
              <thead class="table-light">
                <tr>
                  <th scope="col" class="fw-semibold" width="50px">No</th>
                  <th scope="col" class="fw-semibold">Lembaga</th>
                  <th scope="col" class="fw-semibold">Peringkat</th>
                  <th scope="col" class="fw-semibold">Nomor SK</th>
                  <th scope="col" class="fw-semibold">Tanggal Mulai</th>
                  <th scope="col" class="fw-semibold">Tanggal Berakhir</th>
                  <th scope="col" class="fw-semibold text-center">Status</th>
                  <th scope="col" class="fw-semibold text-center">Dokumen</th>
                  <th scope="col" class="fw-semibold text-center" width="100px">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($akreditasis as $idx => $akr)
                  <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td><span class="fw-semibold text-dark">{{ $akr->prodiakrLembaga ?: '-' }}</span></td>
                    <td>
                      <span class="badge bg-danger-subtle text-danger fw-bold">
                        {{ $akr->akreditasiRef ? $akr->akreditasiRef->akrrNama : $akr->prodiakrAkrrKode }}
                      </span>
                    </td>
                    <td>{{ $akr->prodiakrNomorSk }}</td>
                    <td>{{ $akr->prodiakrTanggal ? \Carbon\Carbon::parse($akr->prodiakrTanggal)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $akr->prodiakrTanggalBerakhirSk ? \Carbon\Carbon::parse($akr->prodiakrTanggalBerakhirSk)->format('d-m-Y') : '-' }}</td>
                    <td class="text-center">
                      @if($akr->prodiakrIsDipakai)
                        <span class="badge bg-success px-2 py-1">Aktif</span>
                      @else
                        <span class="badge bg-secondary px-2 py-1">Tidak Aktif</span>
                      @endif
                    </td>
                    <td class="text-center">
                      @if($akr->prodiakrDokumen)
                        <a href="{{ asset('storage/' . $akr->prodiakrDokumen) }}" target="_blank" class="btn btn-sm btn-subtle-primary">
                          <i class="ti ti-download"></i> PDF
                        </a>
                      @else
                        <span class="text-muted small">-</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <div class="d-inline-flex gap-1">
                        @if(!$akr->prodiakrIsDipakai)
                          <form action="{{ route('prodi.akreditasi.set-active', $akr->prodiakrId) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light border" title="Set Aktif">
                              <i class="ti ti-check text-success"></i>
                            </button>
                          </form>
                        @endif
                        <form action="{{ route('prodi.akreditasi.destroy', $akr->prodiakrId) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat akreditasi ini?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus">
                            <i class="ti ti-trash"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="9" class="text-center py-4 text-muted">Belum ada riwayat akreditasi terdaftar.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- TAB 3: VISI, MISI & TUJUAN -->
        <div class="tab-pane fade" id="visi-misi" role="tabpanel" aria-labelledby="visi-misi-tab">
          <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2">
            <div class="d-flex align-items-center">
              <h5 class="fw-bold mb-0 text-dark me-3">Visi, Misi & Tujuan Program Studi</h5>
              <div class="d-flex align-items-center">
                <label class="form-label mb-0 fw-semibold text-muted me-2 text-nowrap">Versi Kurikulum </label>
                <select class="form-select form-select-sm me-2" id="curriculum-select-visi-misi">
                  @forelse($kurikulums as $kur)
                    <option value="{{ $kur->kurKode }}">{{ $kur->kurNama }} ({{ $kur->kurTahunMulai }})</option>
                  @empty
                    <option value="">-- Tidak ada kurikulum --</option>
                  @endforelse
                </select>
              </div>
            </div>
            <button type="button" class="btn btn-sm btn-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#editVisiMisiModal-{{ $kur->kurKode }}">
              <i class="ti ti-edit me-1"></i> Ubah Visi, Misi & Tujuan
            </button>
          </div>

          @forelse($kurikulums as $idx => $kur)
            <div id="visi-misi-section-{{ $kur->kurKode }}" class="visi-misi-panel d-none">
              <div class="row g-4">
                <div class="col-md-4">
                  <div class="card border border-light shadow-sm h-100">
                    <div class="card-body p-4">
                      <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-primary-subtle text-primary rounded p-2"><i class="ti ti-eye fs-4"></i></div>
                        <h5 class="fw-bold mb-0 text-dark">Visi</h5>
                      </div>
                      <p class="text-dark bg-light p-3 rounded" style="white-space: pre-wrap; min-height: 200px;">{{ $kur->kurVisi ?: 'Visi belum diisi.' }}</p>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="card border border-light shadow-sm h-100">
                    <div class="card-body p-4">
                      <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-info-subtle text-info rounded p-2"><i class="ti ti-target fs-4"></i></div>
                        <h5 class="fw-bold mb-0 text-dark">Misi</h5>
                      </div>
                      <p class="text-dark bg-light p-3 rounded" style="white-space: pre-wrap; min-height: 200px;">{{ $kur->kurMisi ?: 'Misi belum diisi.' }}</p>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="card border border-light shadow-sm h-100">
                    <div class="card-body p-4">
                      <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-success-subtle text-success rounded p-2"><i class="ti ti-flag fs-4"></i></div>
                        <h5 class="fw-bold mb-0 text-dark">Tujuan</h5>
                      </div>
                      <p class="text-dark bg-light p-3 rounded" style="white-space: pre-wrap; min-height: 200px;">{{ $kur->kurTujuan ?: 'Tujuan belum diisi.' }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="alert alert-warning text-center py-4">
              <i class="ti ti-alert-triangle fs-3 d-block mb-2"></i>
              Silakan buat kurikulum terlebih dahulu sebelum mengatur Visi, Misi & Tujuan.
            </div>
          @endforelse
        </div>

        <!-- TAB 4: PROFIL LULUSAN -->
        <div class="tab-pane fade" id="profil-lulusan" role="tabpanel" aria-labelledby="profil-lulusan-tab">
          <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2">
            <div class="d-flex align-items-center">
              <h5 class="fw-bold mb-0 text-dark me-3">Profil Lulusan</h5>
              <div class="d-flex align-items-center">
                <label class="form-label mb-0 fw-semibold text-muted small me-2 text-nowrap">Versi Kurikulum:</label>
                <select class="form-select form-select-sm" id="curriculum-select-pl" style="width: 250px;">
                  @forelse($kurikulums as $kur)
                    <option value="{{ $kur->kurKode }}">{{ $kur->kurNama }} ({{ $kur->kurTahunMulai }})</option>
                  @empty
                    <option value="">-- Tidak ada kurikulum --</option>
                  @endforelse
                </select>
              </div>
            </div>
            
            @if($kurikulums->isNotEmpty())
              <button type="button" id="btn-add-pl-trigger" class="btn btn-dark btn-sm fw-semibold d-flex align-items-center gap-1">
                <i class="ti ti-plus fs-5"></i> Tambah Profil Lulusan
              </button>
            @endif
          </div>

          @forelse($kurikulums as $kur)
            <div id="pl-section-{{ $kur->kurKode }}" class="pl-panel d-none">
              <div class="table-responsive">
                <table class="table align-middle text-nowrap mb-0 table-hover table-dotted">
                  <thead class="table-light">
                    <tr>
                      <th scope="col" class="fw-semibold text-center" width="50px">No</th>
                      <th scope="col" class="fw-semibold" width="120px">Kode PL</th>
                      <th scope="col" class="fw-semibold">Deskripsi Peran & Fungsi Lulusan</th>
                      <th scope="col" class="fw-semibold text-center" width="120px">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($kur->profilLulusans as $idx => $pl)
                      <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td><span class="badge bg-secondary-subtle text-secondary fw-bold">{{ $pl->kode_pl }}</span></td>
                        <td class="text-wrap" style="max-width: 500px;">{{ $pl->deskripsi }}</td>
                        <td class="text-center">
                          <div class="d-inline-flex gap-1">
                            <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editPlModal-{{ $pl->id }}" title="Ubah">
                              <i class="ti ti-edit"></i>
                            </button>
                            <form action="{{ route('prodi.profil-lulusan.destroy', $pl->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus profil lulusan ini?')">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus">
                                <i class="ti ti-trash"></i>
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada data profil lulusan terdaftar untuk kurikulum ini.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          @empty
            <div class="alert alert-warning text-center py-4">
              <i class="ti ti-alert-triangle fs-3 d-block mb-2"></i>
              Silakan buat kurikulum terlebih dahulu sebelum mendaftarkan Profil Lulusan.
            </div>
          @endforelse
        </div>

      </div>
    </div>
</main>

<!-- ==================== MODALS ==================== -->

<!-- Modal: Tambah Akreditasi -->
<div class="modal fade" id="addAkreditasiModal" tabindex="-1" aria-labelledby="addAkreditasiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('prodi.akreditasi.store', $prodi->prodiKode) }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-content border-0 shadow">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="addAkreditasiModalLabel">Tambah Riwayat Akreditasi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-semibold">Lembaga Akreditasi <span class="text-danger">*</span></label>
            <select name="prodiakrLembaga" class="form-select" required>
              <option value="BAN-PT">BAN-PT</option>
              <option value="LAM">LAM</option>
              <option value="Asing">Asing</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-semibold">Peringkat <span class="text-danger">*</span></label>
            <select name="prodiakrAkrrKode" class="form-select" required>
              <option value="">-- Pilih Peringkat --</option>
              @foreach($akreditasiRefs as $ref)
                <option value="{{ $ref->akrrKode }}">{{ $ref->akrrNama }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Nomor SK <span class="text-danger">*</span></label>
            <input type="text" name="prodiakrNomorSk" class="form-control" placeholder="Contoh: 123/SK/BAN-PT/2026" required>
          </div>

          <div class="row">
            <div class="col-6 mb-3">
              <label class="form-label fw-semibold">Mulai Berlaku <span class="text-danger">*</span></label>
              <input type="date" name="prodiakrTanggal" class="form-control" required>
            </div>
            <div class="col-6 mb-3">
              <label class="form-label fw-semibold">Batas Akhir <span class="text-danger">*</span></label>
              <input type="date" name="prodiakrTanggalBerakhirSk" class="form-control" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Dokumen SK (PDF)</label>
            <input type="file" name="prodiakrDokumen" class="form-control" accept="application/pdf">
            <span class="form-text text-muted">Format file .pdf, ukuran maksimal 5MB.</span>
          </div>

          <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="prodiakrIsDipakai" value="1" id="switchIsDipakai" checked>
            <label class="form-check-label fw-semibold" for="switchIsDipakai">Jadikan Akreditasi Aktif saat ini</label>
          </div>
          <span class="small text-muted d-block">Catatan: Mengaktifkan SK ini otomatis akan menonaktifkan SK akreditasi aktif sebelumnya.</span>
        </div>
        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modals: Edit Visi, Misi & Tujuan (Per-Kurikulum) -->
@foreach($kurikulums as $kur)
  <div class="modal fade" id="editVisiMisiModal-{{ $kur->kurKode }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form action="{{ route('prodi.visi-misi.update', $kur->kurKode) }}" method="POST">
        @csrf
        <div class="modal-content border-0 shadow">
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Ubah Visi, Misi & Tujuan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label fw-semibold">Visi</label>
              <textarea name="kurVisi" class="form-control" rows="4" placeholder="Visi program studi..." style="resize: vertical;">{{ $kur->kurVisi }}</textarea>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Misi</label>
              <textarea name="kurMisi" class="form-control" rows="6" placeholder="Misi program studi..." style="resize: vertical;">{{ $kur->kurMisi }}</textarea>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Tujuan</label>
              <textarea name="kurTujuan" class="form-control" rows="4" placeholder="Tujuan program studi..." style="resize: vertical;">{{ $kur->kurTujuan }}</textarea>
            </div>
          </div>
          <div class="modal-footer border-top-0">
            <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Perbarui</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endforeach

<!-- Modals: Tambah Profil Lulusan (Per-Kurikulum) -->
@foreach($kurikulums as $kur)
  <div class="modal fade" id="addPlModal-{{ $kur->kurKode }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form action="{{ route('prodi.profil-lulusan.store', $kur->kurKode) }}" method="POST">
        @csrf
        <div class="modal-content border-0 shadow">
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Tambah Profil Lulusan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label fw-semibold">Kode PL <span class="text-danger">*</span></label>
              <input type="text" name="kode_pl" class="form-control" placeholder="Contoh: PL1, PL2" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Deskripsi Peran & Fungsi Lulusan <span class="text-danger">*</span></label>
              <textarea name="deskripsi" class="form-control" rows="5" placeholder="Tuliskan kompetensi, peran atau fungsi pekerjaan yang dapat diemban oleh lulusan..." required></textarea>
            </div>
          </div>
          <div class="modal-footer border-top-0">
            <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endforeach

<!-- Modals: Edit Profil Lulusan (Per-Profil) -->
@foreach($kurikulums as $kur)
  @foreach($kur->profilLulusans as $pl)
    <div class="modal fade" id="editPlModal-{{ $pl->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form action="{{ route('prodi.profil-lulusan.update', $pl->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
              <h5 class="modal-title fw-bold">Ubah Profil Lulusan</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
              <div class="mb-3">
                <label class="form-label fw-semibold">Kode PL <span class="text-danger">*</span></label>
                <input type="text" name="kode_pl" class="form-control" value="{{ $pl->kode_pl }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi Peran & Fungsi Lulusan <span class="text-danger">*</span></label>
                <textarea name="deskripsi" class="form-control" rows="5" required>{{ $pl->deskripsi }}</textarea>
              </div>
            </div>
            <div class="modal-footer border-top-0">
              <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  @endforeach
@endforeach

<!-- ==================== SCRIPTS ==================== -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Visi Misi Select Dynamic Switcher
    const selectVisiMisi = document.getElementById('curriculum-select-visi-misi');
    if (selectVisiMisi) {
        function switchVisiMisi() {
            const val = selectVisiMisi.value;
            // Hide all panels
            document.querySelectorAll('.visi-misi-panel').forEach(el => el.classList.add('d-none'));
            if (val) {
                // Show matching panel
                const activePanel = document.getElementById(`visi-misi-section-${val}`);
                if (activePanel) activePanel.classList.remove('d-none');
            }
        }
        selectVisiMisi.addEventListener('change', switchVisiMisi);
        switchVisiMisi(); // initial call
    }

    // 2. Profil Lulusan Select Dynamic Switcher
    const selectPl = document.getElementById('curriculum-select-pl');
    const btnAddPlTrigger = document.getElementById('btn-add-pl-trigger');

    if (selectPl) {
        function switchPl() {
            const val = selectPl.value;
            // Hide all panels
            document.querySelectorAll('.pl-panel').forEach(el => el.classList.add('d-none'));
            if (val) {
                // Show matching panel
                const activePanel = document.getElementById(`pl-section-${val}`);
                if (activePanel) activePanel.classList.remove('d-none');
            }
        }
        selectPl.addEventListener('change', switchPl);
        switchPl(); // initial call

        // Setup add button trigger to launch correct modal dynamically
        if (btnAddPlTrigger) {
            btnAddPlTrigger.addEventListener('click', function() {
                const val = selectPl.value;
                if (val) {
                    const myModal = new bootstrap.Modal(document.getElementById(`addPlModal-${val}`));
                    myModal.show();
                }
            });
        }
    }
    
    // 3. Keep active tab on page refresh
    const activeTab = localStorage.getItem('activeProdiTab');
    if (activeTab) {
        const tabEl = document.querySelector(`#prodiTabs button[data-bs-target="${activeTab}"]`);
        if (tabEl) {
            const tab = new bootstrap.Tab(tabEl);
            tab.show();
        }
    }

    const tabButtons = document.querySelectorAll('#prodiTabs button');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', (event) => {
            const target = event.target.getAttribute('data-bs-target');
            localStorage.setItem('activeProdiTab', target);
        });
    });
});
</script>
@endsection
