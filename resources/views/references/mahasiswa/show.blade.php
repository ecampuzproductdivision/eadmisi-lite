@extends('layouts.app')

@section('content')
<main class="p-2">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2 text-success"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-triangle fs-4 me-2"></i>{{ session('warning') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Header Card -->
  <div class="row mb-6">
    <div class="col-12">
      <div class="row align-items-center">
        <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
          @if($mahasiswa->foto)
            <img src="{{ asset('storage/' . $mahasiswa->foto) }}" alt="Foto" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #fff;">
          @else
            <div class="avatar-text-lg rounded-circle mx-auto mx-md-0 {{ $mahasiswa->jenis_kelamin === 'Laki-laki' ? 'bg-info-subtle text-info' : 'bg-danger-subtle text-danger' }} d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: bold; border: 3px solid #fff;">
              {{ substr($mahasiswa->nama_lengkap, 0, 1) }}
            </div>
          @endif
        </div>
        <div class="col-md text-center text-md-start">
          <h2 class="fw-bold mb-1">{{ $mahasiswa->nama_lengkap }}</h2>
          <p class="text-muted mb-2 fs-5">
            {{ $mahasiswa->jenis_mahasiswa }} &middot; {{ $mahasiswa->prodi->prodiNamaResmi ?? 'Program Studi' }}
          </p>
          <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
            <span class="badge bg-light text-dark border"><i class="ti ti-id me-1"></i> NIM: {{ $mahasiswa->nim }}</span>
            <span class="badge bg-light text-dark border"><i class="ti ti-mail me-1"></i> {{ $mahasiswa->email_institusi }}</span>
            <span class="badge bg-light text-dark border"><i class="ti ti-calendar me-1"></i> Angkatan {{ $mahasiswa->tahun_masuk }}</span>
            @php
              $statusColors = [
                'Aktif' => 'bg-success-subtle text-success border-success-subtle',
                'Cuti' => 'bg-warning-subtle text-warning border-warning-subtle',
                'Tugas Belajar' => 'bg-info-subtle text-info border-info-subtle',
                'Non-aktif' => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                'DO' => 'bg-danger-subtle text-danger border-danger-subtle',
                'Lulus' => 'bg-primary-subtle text-primary border-primary-subtle',
                'Mengundurkan Diri' => 'bg-dark-subtle text-dark border-dark-subtle',
              ];
              $color = $statusColors[$mahasiswa->status_mahasiswa] ?? 'bg-secondary-subtle text-secondary';
            @endphp
            <span class="badge {{ $color }} border">{{ $mahasiswa->status_mahasiswa }}</span>
          </div>
        </div>
        <div class="col-md-auto mt-3 mt-md-0 text-center text-md-end">
          <a href="{{ route('mahasiswa.edit', $mahasiswa->id_mahasiswa) }}" class="btn btn-dark"><i class="ti ti-edit me-2"></i>Ubah</a>
          <a href="{{ route('mahasiswa.index') }}" class="btn btn-light border fw-semibold text-dark ms-2"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Stats -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card card-lg h-100">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="rounded-3 bg-secondary-subtle p-3"><i class="ti ti-chart-bar fs-3 text-secondary"></i></div>
          <div>
            <div class="small text-muted">IPK</div>
            <div class="fs-4 fw-bold text-secondary">{{ number_format($mahasiswa->ipk, 2) }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-lg h-100">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="rounded-3 bg-success-subtle p-3"><i class="ti ti-books fs-3 text-success"></i></div>
          <div>
            <div class="small text-muted">Total SKS</div>
            <div class="fs-4 fw-bold text-success">{{ $mahasiswa->total_sks_lulus }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-lg h-100">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="rounded-3 bg-warning-subtle p-3"><i class="ti ti-school fs-3 text-warning"></i></div>
          <div>
            <div class="small text-muted">Semester</div>
            <div class="fs-4 fw-bold text-warning">{{ $mahasiswa->semester_berjalan }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-lg h-100">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="rounded-3 bg-info-subtle p-3"><i class="ti ti-trending-up fs-3 text-info"></i></div>
          <div>
            <div class="small text-muted">IP Terakhir</div>
            <div class="fs-4 fw-bold text-info">{{ number_format($mahasiswa->ip_terakhir, 2) }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabbed Content -->
  <div class="card border-1">
    <div class="card-header bg-white border-bottom-0 pt-3 pb-0 px-4">
      <ul class="nav nav-lb-tab border-bottom" id="mhsTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active fw-semibold" id="profil-tab" data-bs-toggle="tab" data-bs-target="#profil" type="button" role="tab"><i class="ti ti-user me-2"></i>Profil</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-semibold" id="pendidikan-tab" data-bs-toggle="tab" data-bs-target="#pendidikan" type="button" role="tab"><i class="ti ti-book me-2"></i>Pendidikan Asal</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-semibold" id="status-tab" data-bs-toggle="tab" data-bs-target="#status" type="button" role="tab"><i class="ti ti-history me-2"></i>Riwayat Status</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-semibold" id="kurikulum-tab" data-bs-toggle="tab" data-bs-target="#kurikulum" type="button" role="tab"><i class="ti ti-clipboard me-2"></i>Kurikulum</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-semibold" id="cuti-tab" data-bs-toggle="tab" data-bs-target="#cuti" type="button" role="tab"><i class="ti ti-calendar-off me-2"></i>Cuti</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-semibold" id="prestasi-tab" data-bs-toggle="tab" data-bs-target="#prestasi" type="button" role="tab"><i class="ti ti-trophy me-2"></i>Prestasi</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-semibold" id="beasiswa-tab" data-bs-toggle="tab" data-bs-target="#beasiswa" type="button" role="tab"><i class="ti ti-coin me-2"></i>Beasiswa</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-semibold" id="bimbingan-tab" data-bs-toggle="tab" data-bs-target="#bimbingan" type="button" role="tab"><i class="ti ti-messages me-2"></i>Bimbingan PA</button>
        </li>
      </ul>
    </div>
    
    <div class="card-body p-4 bg-light bg-opacity-50">
      <div class="tab-content" id="mhsTabsContent">

        {{-- ===== Tab: Profil ===== --}}
        <div class="tab-pane fade show active" id="profil" role="tabpanel">
          <div class="row g-4">
            <div class="col-md-6">
              <div class="card h-100 card-lg">
                <div class="card-body p-4">
                  <h5 class="fw-bold mb-4"><i class="ti ti-address-book me-2"></i>Informasi Pribadi</h5>
                  <table class="table table-borderless table-sm mb-0">
                    <tr><td width="40%" class="text-muted">NIK</td><td class="fw-medium">{{ $mahasiswa->nik }}</td></tr>
                    <tr><td class="text-muted">Tempat, Tgl Lahir</td><td class="fw-medium">{{ $mahasiswa->tempat_lahir }}, {{ $mahasiswa->tanggal_lahir?->format('d M Y') }}</td></tr>
                    <tr><td class="text-muted">Jenis Kelamin</td><td class="fw-medium">{{ $mahasiswa->jenis_kelamin }}</td></tr>
                    <tr><td class="text-muted">Agama</td><td class="fw-medium">{{ $mahasiswa->agama ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Kewarganegaraan</td><td class="fw-medium">{{ $mahasiswa->kewarganegaraan }}</td></tr>
                    <tr><td class="text-muted">No. Passport</td><td class="fw-medium">{{ $mahasiswa->no_passport ?? '-' }}</td></tr>
                    <tr><td class="text-muted">No. HP</td><td class="fw-medium">{{ $mahasiswa->no_hp }}</td></tr>
                    <tr><td class="text-muted">Email Pribadi</td><td class="fw-medium">{{ $mahasiswa->email_pribadi ?? '-' }}</td></tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card h-100 card-lg">
                <div class="card-body p-4">
                  <h5 class="fw-bold mb-4"><i class="ti ti-school me-2"></i>Data Akademik</h5>
                  <table class="table table-borderless table-sm mb-0">
                    <tr><td width="40%" class="text-muted">Program Studi</td><td class="fw-medium">{{ $mahasiswa->prodi->prodiNamaResmi ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Kurikulum</td><td class="fw-medium">{{ $mahasiswa->kurikulum->kurNama ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Dosen PA</td><td class="fw-medium">{{ $mahasiswa->dosenPa->nama_lengkap ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Jalur Masuk</td><td class="fw-medium">{{ $mahasiswa->jalur_masuk }}</td></tr>
                    <tr><td class="text-muted">Jenis Mahasiswa</td><td class="fw-medium">{{ $mahasiswa->jenis_mahasiswa }}</td></tr>
                    <tr><td class="text-muted">Tanggal Masuk</td><td class="fw-medium">{{ $mahasiswa->tanggal_masuk?->format('d M Y') }}</td></tr>
                    <tr><td class="text-muted">Tanggal Lulus</td><td class="fw-medium">{{ $mahasiswa->tanggal_lulus?->format('d M Y') ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Predikat</td><td class="fw-medium">{{ $mahasiswa->predikat_kelulusan ?? '-' }}</td></tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card h-100 card-lg">
                <div class="card-body p-4">
                  <h5 class="fw-bold mb-4 text-primary"><i class="ti ti-map-pin me-2"></i>Alamat</h5>
                  <table class="table table-borderless table-sm mb-0">
                    <tr><td width="40%" class="text-muted">Alamat Asal</td><td class="fw-medium">{{ $mahasiswa->alamat_asal }}</td></tr>
                    <tr><td class="text-muted">Kota, Provinsi</td><td class="fw-medium">{{ $mahasiswa->kota_asal }}, {{ $mahasiswa->provinsi_asal }}</td></tr>
                    <tr><td class="text-muted">Kode Pos</td><td class="fw-medium">{{ $mahasiswa->kode_pos_asal ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Alamat Domisili</td><td class="fw-medium">{{ $mahasiswa->alamat_domisili ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Kota Domisili</td><td class="fw-medium">{{ $mahasiswa->kota_domisili ?? '-' }}</td></tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card h-100 card-lg">
                <div class="card-body p-4">
                  <h5 class="fw-bold mb-4 text-primary"><i class="ti ti-users me-2"></i>Data Keluarga</h5>
                  <table class="table table-borderless table-sm mb-0">
                    <tr><td width="40%" class="text-muted">Nama Ayah</td><td class="fw-medium">{{ $mahasiswa->nama_ayah }}</td></tr>
                    <tr><td class="text-muted">Pekerjaan Ayah</td><td class="fw-medium">{{ $mahasiswa->pekerjaan_ayah ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Nama Ibu</td><td class="fw-medium">{{ $mahasiswa->nama_ibu }}</td></tr>
                    <tr><td class="text-muted">Pekerjaan Ibu</td><td class="fw-medium">{{ $mahasiswa->pekerjaan_ibu ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Penghasilan Ortu</td><td class="fw-medium">{{ $mahasiswa->penghasilan_ortu ?? '-' }}</td></tr>
                    <tr><td class="text-muted">No. HP Ortu</td><td class="fw-medium">{{ $mahasiswa->no_hp_ortu }}</td></tr>
                    <tr><td class="text-muted">Nama Wali</td><td class="fw-medium">{{ $mahasiswa->nama_wali ?? '-' }}</td></tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- ===== Tab: Pendidikan Asal ===== --}}
        <div class="tab-pane fade" id="pendidikan" role="tabpanel">
          <div class="card card-lg">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
              <h5 class="fw-bold mb-0">Riwayat Pendidikan Sebelumnya</h5>
              <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#modalAddPendidikanAsal"><i class="ti ti-plus me-1"></i>Tambah</button>
            </div>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr><th>Jenjang</th><th>Nama Sekolah / PT</th><th>Jurusan</th><th>Tahun</th><th>Nilai</th><th width="80">Aksi</th></tr>
                </thead>
                <tbody id="tbody-pendidikan-asal">
                  @forelse($mahasiswa->pendidikanAsal as $pa)
                    <tr id="row-pa-{{ $pa->id_pendidikan_asal }}">
                      <td class="fw-semibold">{{ $pa->jenjang }}</td>
                      <td>{{ $pa->nama_sekolah_pt }}<div class="small text-muted">{{ $pa->kota_sekolah_pt }}</div></td>
                      <td>{{ $pa->jurusan_prodi ?? '-' }}</td>
                      <td>{{ $pa->tahun_masuk }} - {{ $pa->tahun_lulus }}</td>
                      <td>{{ $pa->nilai_rata_rata ? number_format($pa->nilai_rata_rata, 2) : '-' }}</td>
                      <td>
                        <button type="button" class="btn btn-sm btn-light text-danger btn-delete-sub" data-url="{{ route('mahasiswa.pendidikan-asal.store', $mahasiswa->id_mahasiswa) }}" data-id="{{ $pa->id_pendidikan_asal }}" data-target="#row-pa-{{ $pa->id_pendidikan_asal }}"><i class="ti ti-trash"></i></button>
                      </td>
                    </tr>
                  @empty
                    <tr id="empty-pa"><td colspan="6" class="text-center text-muted py-4">Belum ada data pendidikan asal</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        {{-- ===== Tab: Riwayat Status ===== --}}
        <div class="tab-pane fade" id="status" role="tabpanel">
          <div class="card card-lg">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
              <h5 class="fw-bold mb-0">Riwayat Perubahan Status</h5>
              <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#modalAddStatus"><i class="ti ti-plus me-1"></i>Ubah Status</button>
            </div>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr><th>Tanggal</th><th>Semester</th><th>Status Lama</th><th>Status Baru</th><th>Alasan</th><th>No SK</th></tr>
                </thead>
                <tbody>
                  @forelse($mahasiswa->riwayatStatus as $rs)
                    <tr>
                      <td>{{ $rs->tanggal_berlaku?->format('d M Y') }}</td>
                      <td class="text-center">{{ $rs->semester_ke }}</td>
                      <td><span class="badge bg-secondary-subtle text-secondary">{{ $rs->status_lama ?? '-' }}</span></td>
                      <td><span class="badge bg-primary-subtle text-primary">{{ $rs->status_baru }}</span></td>
                      <td>{{ $rs->alasan ?? '-' }}</td>
                      <td class="small">{{ $rs->no_sk_perubahan ?? '-' }}</td>
                    </tr>
                  @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada riwayat status</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        {{-- ===== Tab: Kurikulum ===== --}}
        <div class="tab-pane fade" id="kurikulum" role="tabpanel">
          <div class="card card-lg">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
              <h5 class="fw-bold mb-0">Riwayat Kurikulum</h5>
              <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#modalAddKurikulum"><i class="ti ti-plus me-1"></i>Migrasi</button>
            </div>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr><th>Kurikulum</th><th>Tanggal Mulai</th><th>Tanggal Berakhir</th><th>Jenis</th><th>Status</th></tr>
                </thead>
                <tbody>
                  @forelse($mahasiswa->riwayatKurikulum as $rk)
                    <tr>
                      <td class="fw-semibold">{{ $rk->kurikulum->kurNama ?? $rk->id_kurikulum }}</td>
                      <td>{{ $rk->tanggal_mulai?->format('d M Y') }}</td>
                      <td>{{ $rk->tanggal_berakhir?->format('d M Y') ?? '-' }}</td>
                      <td><span class="badge bg-light text-dark border">{{ $rk->jenis_keterikatan }}</span></td>
                      <td>
                        @if($rk->is_aktif)
                          <span class="badge bg-success-subtle text-success">Aktif</span>
                        @else
                          <span class="badge bg-secondary-subtle text-secondary">Berakhir</span>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat kurikulum</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        {{-- ===== Tab: Cuti ===== --}}
        <div class="tab-pane fade" id="cuti" role="tabpanel">
          <div class="card card-lg">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
              <h5 class="fw-bold mb-0">Riwayat Cuti Akademik</h5>
              <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#modalAddCuti"><i class="ti ti-plus me-1"></i>Tambah</button>
            </div>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr><th>Semester</th><th>Tahun Akademik</th><th>Jenis</th><th>Alasan</th><th>Tanggal Pengajuan</th><th>Status</th></tr>
                </thead>
                <tbody>
                  @forelse($mahasiswa->cuti as $c)
                    <tr>
                      <td class="text-center">{{ $c->semester_cuti_ke }}</td>
                      <td>{{ $c->tahun_akademik_cuti }}</td>
                      <td><span class="badge bg-light text-dark border">{{ $c->jenis_cuti }}</span></td>
                      <td>{{ Str::limit($c->alasan_cuti, 50) }}</td>
                      <td>{{ $c->tanggal_pengajuan?->format('d M Y') }}</td>
                      <td>
                        @php
                          $cutiColors = ['Diajukan' => 'bg-warning-subtle text-warning', 'Disetujui' => 'bg-success-subtle text-success', 'Ditolak' => 'bg-danger-subtle text-danger', 'Dibatalkan' => 'bg-secondary-subtle text-secondary'];
                        @endphp
                        <span class="badge {{ $cutiColors[$c->status_pengajuan] ?? 'bg-secondary-subtle text-secondary' }}">{{ $c->status_pengajuan }}</span>
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada riwayat cuti</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        {{-- ===== Tab: Prestasi ===== --}}
        <div class="tab-pane fade" id="prestasi" role="tabpanel">
          <div class="card card-lg">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
              <h5 class="fw-bold mb-0">Daftar Prestasi</h5>
              <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#modalAddPrestasi"><i class="ti ti-plus me-1"></i>Tambah</button>
            </div>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr><th>Prestasi</th><th>Jenis</th><th>Tingkat</th><th>Peringkat</th><th>Penyelenggara</th><th>Tanggal</th></tr>
                </thead>
                <tbody>
                  @forelse($mahasiswa->prestasi as $p)
                    <tr>
                      <td class="fw-semibold">{{ $p->nama_prestasi }}</td>
                      <td><span class="badge bg-light text-dark border">{{ $p->jenis_prestasi }}</span></td>
                      <td><span class="badge bg-primary-subtle text-primary">{{ $p->tingkat }}</span></td>
                      <td>{{ $p->peringkat ?? '-' }}</td>
                      <td>{{ $p->penyelenggara }}</td>
                      <td>{{ $p->tanggal_perolehan?->format('d M Y') }}</td>
                    </tr>
                  @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada prestasi tercatat</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        {{-- ===== Tab: Beasiswa ===== --}}
        <div class="tab-pane fade" id="beasiswa" role="tabpanel">
          <div class="card card-lg">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
              <h5 class="fw-bold mb-0">Riwayat Beasiswa</h5>
              <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#modalAddBeasiswa"><i class="ti ti-plus me-1"></i>Tambah</button>
            </div>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr><th>Beasiswa</th><th>Penyelenggara</th><th>Jenis</th><th>Periode</th><th>Nominal/Bln</th><th>Status</th></tr>
                </thead>
                <tbody>
                  @forelse($mahasiswa->beasiswa as $b)
                    <tr>
                      <td class="fw-semibold">{{ $b->nama_beasiswa }}</td>
                      <td>{{ $b->penyelenggara_beasiswa }}</td>
                      <td><span class="badge bg-light text-dark border">{{ $b->jenis_beasiswa }}</span></td>
                      <td>{{ $b->tahun_mulai }} - {{ $b->tahun_berakhir ?? 'Sekarang' }}</td>
                      <td>{{ $b->nominal_per_bulan ? 'Rp ' . number_format($b->nominal_per_bulan, 0, ',', '.') : '-' }}</td>
                      <td>
                        @php $bsColors = ['Aktif' => 'bg-success-subtle text-success', 'Selesai' => 'bg-primary-subtle text-primary', 'Dicabut' => 'bg-danger-subtle text-danger']; @endphp
                        <span class="badge {{ $bsColors[$b->status_beasiswa] ?? 'bg-secondary-subtle text-secondary' }}">{{ $b->status_beasiswa }}</span>
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada riwayat beasiswa</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        {{-- ===== Tab: Bimbingan PA ===== --}}
        <div class="tab-pane fade" id="bimbingan" role="tabpanel">
          <div class="card card-lg">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
              <h5 class="fw-bold mb-0">Catatan Bimbingan PA</h5>
              <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#modalAddBimbingan"><i class="ti ti-plus me-1"></i>Tambah</button>
            </div>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr><th>Tanggal</th><th>Sem</th><th>Dosen PA</th><th>Topik</th><th>Metode</th><th>Tindak Lanjut</th></tr>
                </thead>
                <tbody>
                  @forelse($mahasiswa->bimbinganPa as $bp)
                    <tr>
                      <td>{{ $bp->tanggal_bimbingan?->format('d M Y') }}</td>
                      <td class="text-center">{{ $bp->semester_ke }}</td>
                      <td>{{ $bp->dosenPa->nama_lengkap ?? '-' }}</td>
                      <td>{{ Str::limit($bp->topik_bimbingan, 60) }}</td>
                      <td><span class="badge bg-light text-dark border">{{ $bp->metode }}</span></td>
                      <td class="small">{{ Str::limit($bp->tindak_lanjut, 40) ?? '-' }}</td>
                    </tr>
                  @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada catatan bimbingan</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</main>

{{-- ===== MODALS ===== --}}

<!-- Modal: Pendidikan Asal -->
<div class="modal fade" id="modalAddPendidikanAsal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content form-ajax" action="{{ route('mahasiswa.pendidikan-asal.store', $mahasiswa->id_mahasiswa) }}" method="POST">
      @csrf
      <div class="modal-header border-bottom-0 pb-0"><h5 class="modal-title fw-bold">Tambah Pendidikan Asal</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label">Jenjang <span class="text-danger">*</span></label>
          <select name="jenjang" class="form-select" required>@foreach(['SMA', 'SMK', 'MA', 'D3', 'S1', 'PT Lain'] as $j)<option value="{{ $j }}">{{ $j }}</option>@endforeach</select></div>
        <div class="mb-3"><label class="form-label">Nama Sekolah/PT <span class="text-danger">*</span></label><input type="text" name="nama_sekolah_pt" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Jurusan/Prodi</label><input type="text" name="jurusan_prodi" class="form-control"></div>
        <div class="row mb-3">
          <div class="col-6"><label class="form-label">Tahun Masuk <span class="text-danger">*</span></label><input type="number" name="tahun_masuk" class="form-control" required></div>
          <div class="col-6"><label class="form-label">Tahun Lulus <span class="text-danger">*</span></label><input type="number" name="tahun_lulus" class="form-control" required></div>
        </div>
        <div class="row mb-3">
          <div class="col-6"><label class="form-label">Nilai Rata-rata</label><input type="number" step="0.01" name="nilai_rata_rata" class="form-control"></div>
          <div class="col-6"><label class="form-label">Kota</label><input type="text" name="kota_sekolah_pt" class="form-control"></div>
        </div>
      </div>
      <div class="modal-footer border-top-0 pt-0"><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
  </div>
</div>

<!-- Modal: Ubah Status -->
<div class="modal fade" id="modalAddStatus" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content form-ajax" action="{{ route('mahasiswa.riwayat-status.store', $mahasiswa->id_mahasiswa) }}" method="POST">
      @csrf
      <div class="modal-header border-bottom-0 pb-0"><h5 class="modal-title fw-bold">Ubah Status Mahasiswa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label">Status Baru <span class="text-danger">*</span></label>
          <select name="status_baru" class="form-select" required>@foreach(['Aktif', 'Cuti', 'Tugas Belajar', 'Non-aktif', 'DO', 'Lulus', 'Mengundurkan Diri'] as $s)<option value="{{ $s }}">{{ $s }}</option>@endforeach</select></div>
        <div class="row mb-3">
          <div class="col-6"><label class="form-label">Tanggal Berlaku <span class="text-danger">*</span></label><input type="date" name="tanggal_berlaku" class="form-control" value="{{ date('Y-m-d') }}" required></div>
          <div class="col-6"><label class="form-label">Semester Ke <span class="text-danger">*</span></label><input type="number" name="semester_ke" class="form-control" value="{{ $mahasiswa->semester_berjalan }}" required></div>
        </div>
        <div class="mb-3"><label class="form-label">Alasan</label><textarea name="alasan" class="form-control" rows="2"></textarea></div>
        <div class="mb-3"><label class="form-label">No. SK Perubahan</label><input type="text" name="no_sk_perubahan" class="form-control"></div>
      </div>
      <div class="modal-footer border-top-0 pt-0"><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
  </div>
</div>

<!-- Modal: Kurikulum -->
<div class="modal fade" id="modalAddKurikulum" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content form-ajax" action="{{ route('mahasiswa.kurikulum.store', $mahasiswa->id_mahasiswa) }}" method="POST">
      @csrf
      <div class="modal-header border-bottom-0 pb-0"><h5 class="modal-title fw-bold">Migrasi Kurikulum</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label">Kurikulum Baru <span class="text-danger">*</span></label>
          <select name="id_kurikulum" class="form-select" required>
            @php $kurikulumList = \App\Models\Kurikulum::orderBy('kurNama')->get(); @endphp
            @foreach($kurikulumList as $k)<option value="{{ $k->kurKode }}">{{ $k->kurNama }}</option>@endforeach
          </select></div>
        <div class="mb-3"><label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label><input type="date" name="tanggal_mulai" class="form-control" value="{{ date('Y-m-d') }}" required></div>
        <div class="mb-3"><label class="form-label">Jenis Keterikatan <span class="text-danger">*</span></label>
          <select name="jenis_keterikatan" class="form-select" required>@foreach(['Migrasi', 'Transfer'] as $jk)<option value="{{ $jk }}">{{ $jk }}</option>@endforeach</select></div>
        <div class="mb-3"><label class="form-label">Catatan</label><textarea name="catatan" class="form-control" rows="2"></textarea></div>
      </div>
      <div class="modal-footer border-top-0 pt-0"><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
  </div>
</div>

<!-- Modal: Cuti -->
<div class="modal fade" id="modalAddCuti" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content form-ajax" action="{{ route('mahasiswa.cuti.store', $mahasiswa->id_mahasiswa) }}" method="POST">
      @csrf
      <div class="modal-header border-bottom-0 pb-0"><h5 class="modal-title fw-bold">Tambah Pengajuan Cuti</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-6"><label class="form-label">Semester Cuti Ke <span class="text-danger">*</span></label><input type="number" name="semester_cuti_ke" class="form-control" value="{{ $mahasiswa->semester_berjalan }}" required></div>
          <div class="col-6"><label class="form-label">Tahun Akademik <span class="text-danger">*</span></label><input type="text" name="tahun_akademik_cuti" class="form-control" placeholder="2024/2025 Ganjil" required></div>
        </div>
        <div class="mb-3"><label class="form-label">Jenis Cuti <span class="text-danger">*</span></label>
          <select name="jenis_cuti" class="form-select" required>@foreach(['Sakit', 'Keluarga', 'Ekonomi', 'Lainnya'] as $jc)<option value="{{ $jc }}">{{ $jc }}</option>@endforeach</select></div>
        <div class="mb-3"><label class="form-label">Alasan Cuti <span class="text-danger">*</span></label><textarea name="alasan_cuti" class="form-control" rows="2" required></textarea></div>
        <div class="row mb-3">
          <div class="col-6"><label class="form-label">Tanggal Pengajuan <span class="text-danger">*</span></label><input type="date" name="tanggal_pengajuan" class="form-control" value="{{ date('Y-m-d') }}" required></div>
          <div class="col-6"><label class="form-label">Status <span class="text-danger">*</span></label>
            <select name="status_pengajuan" class="form-select" required>@foreach(['Diajukan', 'Disetujui', 'Ditolak', 'Dibatalkan'] as $sp)<option value="{{ $sp }}">{{ $sp }}</option>@endforeach</select></div>
        </div>
      </div>
      <div class="modal-footer border-top-0 pt-0"><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
  </div>
</div>

<!-- Modal: Prestasi -->
<div class="modal fade" id="modalAddPrestasi" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content form-ajax" action="{{ route('mahasiswa.prestasi.store', $mahasiswa->id_mahasiswa) }}" method="POST">
      @csrf
      <div class="modal-header border-bottom-0 pb-0"><h5 class="modal-title fw-bold">Tambah Prestasi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label">Nama Prestasi <span class="text-danger">*</span></label><input type="text" name="nama_prestasi" class="form-control" required></div>
        <div class="row mb-3">
          <div class="col-6"><label class="form-label">Jenis <span class="text-danger">*</span></label>
            <select name="jenis_prestasi" class="form-select" required>@foreach(['Akademik', 'Olahraga', 'Seni', 'Organisasi', 'Penelitian', 'Lainnya'] as $jp)<option value="{{ $jp }}">{{ $jp }}</option>@endforeach</select></div>
          <div class="col-6"><label class="form-label">Tingkat <span class="text-danger">*</span></label>
            <select name="tingkat" class="form-select" required>@foreach(['Kampus', 'Kota', 'Provinsi', 'Nasional', 'Internasional'] as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach</select></div>
        </div>
        <div class="row mb-3">
          <div class="col-6"><label class="form-label">Peringkat</label><input type="text" name="peringkat" class="form-control"></div>
          <div class="col-6"><label class="form-label">Tanggal <span class="text-danger">*</span></label><input type="date" name="tanggal_perolehan" class="form-control" required></div>
        </div>
        <div class="mb-3"><label class="form-label">Penyelenggara <span class="text-danger">*</span></label><input type="text" name="penyelenggara" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="2"></textarea></div>
      </div>
      <div class="modal-footer border-top-0 pt-0"><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
  </div>
</div>

<!-- Modal: Beasiswa -->
<div class="modal fade" id="modalAddBeasiswa" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content form-ajax" action="{{ route('mahasiswa.beasiswa.store', $mahasiswa->id_mahasiswa) }}" method="POST">
      @csrf
      <div class="modal-header border-bottom-0 pb-0"><h5 class="modal-title fw-bold">Tambah Beasiswa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label">Nama Beasiswa <span class="text-danger">*</span></label><input type="text" name="nama_beasiswa" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Penyelenggara <span class="text-danger">*</span></label><input type="text" name="penyelenggara_beasiswa" class="form-control" required></div>
        <div class="row mb-3">
          <div class="col-6"><label class="form-label">Jenis <span class="text-danger">*</span></label>
            <select name="jenis_beasiswa" class="form-select" required>@foreach(['Pemerintah', 'Swasta', 'Institusi', 'Lainnya'] as $jb)<option value="{{ $jb }}">{{ $jb }}</option>@endforeach</select></div>
          <div class="col-6"><label class="form-label">Kategori <span class="text-danger">*</span></label>
            <select name="kategori_beasiswa" class="form-select" required>@foreach(['Prestasi', 'Kebutuhan', 'Afirmasi', 'Ikatan Dinas'] as $kb)<option value="{{ $kb }}">{{ $kb }}</option>@endforeach</select></div>
        </div>
        <div class="row mb-3">
          <div class="col-4"><label class="form-label">Tahun Mulai <span class="text-danger">*</span></label><input type="number" name="tahun_mulai" class="form-control" required></div>
          <div class="col-4"><label class="form-label">Tahun Berakhir</label><input type="number" name="tahun_berakhir" class="form-control"></div>
          <div class="col-4"><label class="form-label">Nominal/Bulan</label><input type="number" name="nominal_per_bulan" class="form-control"></div>
        </div>
        <div class="mb-3"><label class="form-label">Status <span class="text-danger">*</span></label>
          <select name="status_beasiswa" class="form-select" required>@foreach(['Aktif', 'Selesai', 'Dicabut'] as $sb)<option value="{{ $sb }}">{{ $sb }}</option>@endforeach</select></div>
      </div>
      <div class="modal-footer border-top-0 pt-0"><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
  </div>
</div>

<!-- Modal: Bimbingan PA -->
<div class="modal fade" id="modalAddBimbingan" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content form-ajax" action="{{ route('mahasiswa.bimbingan.store', $mahasiswa->id_mahasiswa) }}" method="POST">
      @csrf
      <div class="modal-header border-bottom-0 pb-0"><h5 class="modal-title fw-bold">Tambah Catatan Bimbingan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label">Dosen PA <span class="text-danger">*</span></label>
          <select name="id_dosen_pa" class="form-select" required>
            @php $dosenList = \App\Models\Dosen::where('status_dosen', 'Aktif')->orderBy('nama_lengkap')->get(); @endphp
            @foreach($dosenList as $d)<option value="{{ $d->id_dosen }}" {{ $mahasiswa->id_dosen_pa == $d->id_dosen ? 'selected' : '' }}>{{ $d->nama_lengkap }}</option>@endforeach
          </select></div>
        <div class="row mb-3">
          <div class="col-6"><label class="form-label">Tanggal <span class="text-danger">*</span></label><input type="date" name="tanggal_bimbingan" class="form-control" value="{{ date('Y-m-d') }}" required></div>
          <div class="col-6"><label class="form-label">Semester Ke <span class="text-danger">*</span></label><input type="number" name="semester_ke" class="form-control" value="{{ $mahasiswa->semester_berjalan }}" required></div>
        </div>
        <div class="mb-3"><label class="form-label">Topik Bimbingan <span class="text-danger">*</span></label><textarea name="topik_bimbingan" class="form-control" rows="2" required></textarea></div>
        <div class="mb-3"><label class="form-label">Catatan PA</label><textarea name="catatan_pa" class="form-control" rows="2"></textarea></div>
        <div class="mb-3"><label class="form-label">Tindak Lanjut</label><textarea name="tindak_lanjut" class="form-control" rows="2"></textarea></div>
        <div class="mb-3"><label class="form-label">Metode <span class="text-danger">*</span></label>
          <select name="metode" class="form-select" required>@foreach(['Tatap Muka', 'Daring', 'Telepon'] as $m)<option value="{{ $m }}">{{ $m }}</option>@endforeach</select></div>
      </div>
      <div class="modal-footer border-top-0 pt-0"><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
  </div>
</div>

<style>
.custom-tabs .nav-link {
  color: #64748b;
  border: none;
  padding: 1rem 1.25rem;
  white-space: nowrap;
}
.custom-tabs .nav-link:hover { color: #3b82f6; }
.custom-tabs .nav-link.active {
  color: #2563eb;
  background: transparent;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Generic AJAX Form Submitter
  document.querySelectorAll('.form-ajax').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const btn = form.querySelector('button[type="submit"]');
      const originalText = btn.innerHTML;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
      btn.disabled = true;

      fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'Accept': 'application/json' }
      })
      .then(r => r.json())
      .then(res => {
        if(res.success) {
          location.reload();
        } else {
          alert(res.message || 'Gagal menyimpan.');
        }
      })
      .catch(err => alert('Terjadi kesalahan.'))
      .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
      });
    });
  });
});
</script>
@endsection
