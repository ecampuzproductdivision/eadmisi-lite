@extends('layouts.app')

@section('content')
<main class="p-2">
  <!-- Header Card -->
  <div class="card border-0 mb-6">
    <div class="card-body p-4">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="mb-1 fw-bold">Edit Tahun Akademik</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Master Data</a></li>
              <li class="breadcrumb-item"><a href="{{ route('tahun-akademik.index') }}">Tahun Akademik</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit: {{ $ta->kode_ta }}</li>
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

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-6" role="alert">
      <i class="ti ti-circle-check fs-4 me-2 text-success"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-6" role="alert">
      <i class="ti ti-alert-triangle fs-4 me-2 text-danger"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

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

  <!-- Tabs -->
  <ul class="nav nav-tabs nav-fill mb-6" id="taTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active fw-semibold" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
        <i class="ti ti-info-circle me-1"></i> Informasi Umum
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-semibold" id="periode-tab" data-bs-toggle="tab" data-bs-target="#periode" type="button" role="tab">
        <i class="ti ti-list-details me-1"></i> Periode <span class="badge bg-primary ms-1">{{ $ta->periodes->count() }}</span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-semibold" id="kalender-tab" data-bs-toggle="tab" data-bs-target="#kalender" type="button" role="tab">
        <i class="ti ti-calendar-event me-1"></i> Kalender <span class="badge bg-primary ms-1">{{ $ta->kalenders->count() }}</span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-semibold" id="notifikasi-tab" data-bs-toggle="tab" data-bs-target="#notifikasi" type="button" role="tab">
        <i class="ti ti-bell me-1"></i> Notifikasi <span class="badge bg-primary ms-1">{{ $ta->notifikasiConfigs->count() }}</span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-semibold" id="copy-tab" data-bs-toggle="tab" data-bs-target="#copy" type="button" role="tab">
        <i class="ti ti-copy me-1"></i> Salin Template
      </button>
    </li>
  </ul>

  <div class="tab-content" id="taTabsContent">
    <!-- ========== TAB 1: INFORMASI UMUM ========== -->
    <div class="tab-pane fade show active" id="info" role="tabpanel">
      <form action="{{ route('tahun-akademik.update', $ta->id_tahun_akademik) }}" method="POST">
        @csrf @method('PUT')
        <div class="card card-lg mb-6">
          <div class="card-body p-4">
            <div class="row g-4">
              <div class="col-md-4">
                <label class="form-label fw-semibold">Kode TA <span class="text-danger">*</span></label>
                <input type="text" name="kode_ta" class="form-control @error('kode_ta') is-invalid @enderror" value="{{ old('kode_ta', $ta->kode_ta) }}" required>
                @error('kode_ta') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-8">
                <label class="form-label fw-semibold">Nama TA <span class="text-danger">*</span></label>
                <input type="text" name="nama_ta" class="form-control @error('nama_ta') is-invalid @enderror" value="{{ old('nama_ta', $ta->nama_ta) }}" required>
                @error('nama_ta') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-3">
                <label class="form-label fw-semibold">Tahun Mulai <span class="text-danger">*</span></label>
                <input type="number" name="tahun_mulai" class="form-control @error('tahun_mulai') is-invalid @enderror" value="{{ old('tahun_mulai', $ta->tahun_mulai) }}" required>
                @error('tahun_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-3">
                <label class="form-label fw-semibold">Tahun Selesai <span class="text-danger">*</span></label>
                <input type="number" name="tahun_selesai" class="form-control @error('tahun_selesai') is-invalid @enderror" value="{{ old('tahun_selesai', $ta->tahun_selesai) }}" required>
                @error('tahun_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-3">
                <label class="form-label fw-semibold">Jenis Semester <span class="text-danger">*</span></label>
                <select name="jenis_semester" class="form-select @error('jenis_semester') is-invalid @enderror" required>
                  <option value="GANJIL" {{ old('jenis_semester', $ta->jenis_semester) == 'GANJIL' ? 'selected' : '' }}>Ganjil</option>
                  <option value="GENAP" {{ old('jenis_semester', $ta->jenis_semester) == 'GENAP' ? 'selected' : '' }}>Genap</option>
                  <option value="PENDEK" {{ old('jenis_semester', $ta->jenis_semester) == 'PENDEK' ? 'selected' : '' }}>Pendek</option>
                </select>
                @error('jenis_semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-3">
                <label class="form-label fw-semibold">Jml Minggu Efektif <span class="text-danger">*</span></label>
                <input type="number" name="jumlah_minggu_efektif" class="form-control @error('jumlah_minggu_efektif') is-invalid @enderror" value="{{ old('jumlah_minggu_efektif', $ta->jumlah_minggu_efektif) }}" required>
                @error('jumlah_minggu_efektif') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai', $ta->tanggal_mulai->format('Y-m-d')) }}" required>
                @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai', $ta->tanggal_selesai->format('Y-m-d')) }}" required>
                @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-12">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="2">{{ old('catatan', $ta->catatan) }}</textarea>
                @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>
          </div>
          <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-end gap-2">
              <a href="{{ route('tahun-akademik.aktivasi-panel', $ta->id_tahun_akademik) }}" class="btn btn-success fw-semibold px-4">
                <i class="ti ti-toggle-left me-1"></i> Lanjut ke Aktivasi
              </a>
              <button type="submit" class="btn btn-primary fw-semibold px-4">Simpan Perubahan</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- ========== TAB 2: PERIODE KEGIATAN ========== -->
    <div class="tab-pane fade" id="periode" role="tabpanel">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Daftar Periode Kegiatan</h5>
        <button type="button" class="btn btn-dark d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahPeriode">
          <i class="ti ti-plus"></i> Tambah Periode
        </button>
      </div>
      <div class="card card-lg mb-6">
        <div class="table-responsive">
          <table class="table align-middle text-nowrap mb-0 table-hover">
            <thead class="table-light">
              <tr>
                <th class="fw-semibold" width="50px">No</th>
                <th class="fw-semibold">Kode</th>
                <th class="fw-semibold">Nama Periode</th>
                <th class="fw-semibold">Tgl Mulai</th>
                <th class="fw-semibold">Tgl Selesai</th>
                <th class="fw-semibold text-center">Diperpanjang</th>
                <th class="fw-semibold text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($ta->periodes as $periode)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td><span class="badge bg-secondary">{{ $periode->kode_periode }}</span></td>
                <td class="fw-semibold">{{ $periode->nama_periode }}</td>
                <td>{{ $periode->tanggal_mulai->format('d/m/Y') }}</td>
                <td>{{ $periode->tanggal_selesai->format('d/m/Y') }}</td>
                <td class="text-center">
                  @if($periode->riwayat_perpanjangan > 0)
                    <span class="badge bg-warning text-dark">{{ $periode->riwayat_perpanjangan }}x</span>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td class="text-center">
                  <div class="dropdown">
                    <button class="btn btn-light btn-sm border-0" type="button" data-bs-toggle="dropdown">
                      <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalEditPeriode{{ $periode->id_periode }}"><i class="ti ti-edit me-2"></i>Edit</a></li>
                      @if($periode->is_dapat_diperpanjang)
                      <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalPerpanjang{{ $periode->id_periode }}"><i class="ti ti-arrow-up-circle me-2"></i>Perpanjang</a></li>
                      @endif
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <form action="{{ route('tahun-akademik.periode.destroy', [$ta->id_tahun_akademik, $periode->id_periode]) }}" method="POST" onsubmit="return confirm('Hapus periode ini?')">
                          @csrf @method('DELETE')
                          <button type="submit" class="dropdown-item text-danger"><i class="ti ti-trash me-2"></i>Hapus</button>
                        </form>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
              @empty
              <tr><td colspan="7" class="text-center py-4"><p class="text-muted mb-0">Belum ada periode. Klik "Tambah Periode".</p></td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Modals Edit & Perpanjang Periode (outside table) --}}
    @foreach($ta->periodes as $periode)
    <!-- Modal Edit Periode -->
    <div class="modal fade" id="modalEditPeriode{{ $periode->id_periode }}" tabindex="-1">
      <div class="modal-dialog">
        <form action="{{ route('tahun-akademik.periode.update', [$ta->id_tahun_akademik, $periode->id_periode]) }}" method="POST" class="modal-content">
          @csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Edit: {{ $periode->kode_periode }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
              <input type="text" name="nama_periode" class="form-control" value="{{ $periode->nama_periode }}" required>
            </div>
            <div class="row mb-3">
              <div class="col-6"><label class="form-label fw-semibold">Tgl Mulai <span class="text-danger">*</span></label><input type="date" name="tanggal_mulai" class="form-control" value="{{ $periode->tanggal_mulai->format('Y-m-d') }}" required></div>
              <div class="col-6"><label class="form-label fw-semibold">Tgl Selesai <span class="text-danger">*</span></label><input type="date" name="tanggal_selesai" class="form-control" value="{{ $periode->tanggal_selesai->format('Y-m-d') }}" required></div>
            </div>
            <div class="row mb-3">
              <div class="col-6"><label class="form-label fw-semibold">Jam Mulai</label><input type="time" name="jam_mulai" class="form-control" value="{{ $periode->jam_mulai }}"></div>
              <div class="col-6"><label class="form-label fw-semibold">Jam Selesai</label><input type="time" name="jam_selesai" class="form-control" value="{{ $periode->jam_selesai }}"></div>
            </div>
            <div class="row mb-3">
              <div class="col-6">
                <div class="form-check form-switch mt-2">
                  <input type="checkbox" name="is_dapat_diperpanjang" class="form-check-input" value="1" id="edit_diperpanjang_{{ $periode->id_periode }}" {{ $periode->is_dapat_diperpanjang ? 'checked' : '' }}>
                  <label class="form-check-label fw-semibold" for="edit_diperpanjang_{{ $periode->id_periode }}">Dapat Diperpanjang</label>
                </div>
              </div>
              <div class="col-6"><label class="form-label fw-semibold">Maks Perpanjangan</label><input type="number" name="maks_perpanjangan" class="form-control" value="{{ $periode->maks_perpanjangan }}" min="0" placeholder="0 = tak terbatas"></div>
            </div>
            <div class="mb-3"><label class="form-label fw-semibold">Keterangan</label><textarea name="keterangan" class="form-control" rows="2">{{ $periode->keterangan }}</textarea></div>
          </div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
      </div>
    </div>

    @if($periode->is_dapat_diperpanjang)
    <!-- Modal Perpanjang Periode -->
    <div class="modal fade" id="modalPerpanjang{{ $periode->id_periode }}" tabindex="-1">
      <div class="modal-dialog">
        <form action="{{ route('tahun-akademik.periode.perpanjang', [$ta->id_tahun_akademik, $periode->id_periode]) }}" method="POST" class="modal-content">
          @csrf
          <div class="modal-header"><h5 class="modal-title">Perpanjang: {{ $periode->nama_periode }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="alert alert-info mb-3">
              <i class="ti ti-info-circle me-2"></i> Selesai saat ini: <strong>{{ $periode->tanggal_selesai->format('d/m/Y') }}</strong>
              @if($periode->riwayat_perpanjangan > 0)
                <br>Diperpanjang {{ $periode->riwayat_perpanjangan }}x
              @endif
              @if($periode->maks_perpanjangan !== null)
                <br>Maks: {{ $periode->maks_perpanjangan }}x
              @endif
            </div>
            <input type="hidden" name="tanggal_selesai_lama" value="{{ $periode->tanggal_selesai->format('Y-m-d') }}">
            <div class="mb-3"><label class="form-label fw-semibold">Tgl Selesai Baru <span class="text-danger">*</span></label><input type="date" name="tanggal_selesai_baru" class="form-control" value="{{ $periode->tanggal_selesai->format('Y-m-d') }}" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Alasan <span class="text-danger">*</span></label><textarea name="alasan" class="form-control" rows="3" placeholder="Min. 10 karakter" required></textarea></div>
          </div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-warning">Perpanjang</button></div>
        </form>
      </div>
    </div>
    @endif
    @endforeach

    <!-- ========== TAB 3: KALENDER AKADEMIK ========== -->
    <div class="tab-pane fade" id="kalender" role="tabpanel">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Event Kalender Akademik</h5>
        <button type="button" class="btn btn-dark d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahKalender">
          <i class="ti ti-plus"></i> Tambah Event
        </button>
      </div>
      <div class="card card-lg mb-6">
        <div class="table-responsive">
          <table class="table align-middle mb-0 table-hover">
            <thead class="table-light">
              <tr>
                <th class="fw-semibold">Judul Event</th>
                <th class="fw-semibold">Kategori</th>
                <th class="fw-semibold">Tanggal Mulai</th>
                <th class="fw-semibold">Tanggal Selesai</th>
                <th class="fw-semibold text-center">Libur</th>
                <th class="fw-semibold text-center">Publik</th>
                <th class="fw-semibold text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($ta->kalenders as $event)
              <tr>
                <td class="fw-semibold">{{ $event->judul_event }}</td>
                <td><span class="badge" style="background-color: {{ $event->warna_ui ?? '#6c757d' }}">{{ $event->kategori }}</span></td>
                <td>{{ $event->tanggal_mulai->format('d/m/Y') }}</td>
                <td>{{ $event->tanggal_selesai ? $event->tanggal_selesai->format('d/m/Y') : '-' }}</td>
                <td class="text-center">{!! $event->is_libur ? '<span class="badge bg-danger">Libur</span>' : '<span class="badge bg-secondary">-</span>' !!}</td>
                <td class="text-center">{!! $event->is_tampil_publik ? '<span class="badge bg-success">Publik</span>' : '<span class="badge bg-secondary">Internal</span>' !!}</td>
                <td class="text-center">
                  <div class="dropdown">
                    <button class="btn btn-light btn-sm border-0" type="button" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalEditKalender{{ $event->id_kalender }}"><i class="ti ti-edit me-2"></i>Edit</a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <form action="{{ route('tahun-akademik.kalender.destroy', [$ta->id_tahun_akademik, $event->id_kalender]) }}" method="POST" onsubmit="return confirm('Hapus event ini?')">
                          @csrf @method('DELETE')
                          <button type="submit" class="dropdown-item text-danger"><i class="ti ti-trash me-2"></i>Hapus</button>
                        </form>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
              @empty
              <tr><td colspan="7" class="text-center py-4"><p class="text-muted mb-0">Belum ada event kalender.</p></td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Modals Edit Kalender (outside table) --}}
    @foreach($ta->kalenders as $event)
    <div class="modal fade" id="modalEditKalender{{ $event->id_kalender }}" tabindex="-1">
      <div class="modal-dialog">
        <form action="{{ route('tahun-akademik.kalender.update', [$ta->id_tahun_akademik, $event->id_kalender]) }}" method="POST" class="modal-content">
          @csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Edit Event</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="mb-3"><label class="form-label fw-semibold">Judul Event <span class="text-danger">*</span></label><input type="text" name="judul_event" class="form-control" value="{{ $event->judul_event }}" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="2">{{ $event->deskripsi }}</textarea></div>
            <div class="row mb-3">
              <div class="col-6">
                <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                <select name="kategori" class="form-select" required>
                  @foreach($kategoriEventList as $kat)
                    <option value="{{ $kat }}" {{ $event->kategori == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-6"><label class="form-label fw-semibold">Warna UI</label><input type="color" name="warna_ui" class="form-control form-control-color" value="{{ $event->warna_ui ?? '#0d6efd' }}"></div>
            </div>
            <div class="row mb-3">
              <div class="col-6"><label class="form-label fw-semibold">Tgl Mulai <span class="text-danger">*</span></label><input type="date" name="tanggal_mulai" class="form-control" value="{{ $event->tanggal_mulai->format('Y-m-d') }}" required></div>
              <div class="col-6"><label class="form-label fw-semibold">Tgl Selesai</label><input type="date" name="tanggal_selesai" class="form-control" value="{{ $event->tanggal_selesai ? $event->tanggal_selesai->format('Y-m-d') : '' }}"></div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-check form-switch mt-2">
                  <input type="checkbox" name="is_libur" class="form-check-input" value="1" id="edit_libur_{{ $event->id_kalender }}" {{ $event->is_libur ? 'checked' : '' }}>
                  <label class="form-check-label fw-semibold" for="edit_libur_{{ $event->id_kalender }}">Hari Libur</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check form-switch mt-2">
                  <input type="checkbox" name="is_tampil_publik" class="form-check-input" value="1" id="edit_publik_{{ $event->id_kalender }}" {{ $event->is_tampil_publik ? 'checked' : '' }}>
                  <label class="form-check-label fw-semibold" for="edit_publik_{{ $event->id_kalender }}">Tampil Publik</label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
      </div>
    </div>
    @endforeach

    <!-- ========== TAB 4: KONFIGURASI NOTIFIKASI ========== -->
    <div class="tab-pane fade" id="notifikasi" role="tabpanel">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Konfigurasi Notifikasi</h5>
        <button type="button" class="btn btn-dark d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahNotif">
          <i class="ti ti-plus"></i> Tambah Konfigurasi
        </button>
      </div>
      <div class="card card-lg mb-6">
        <div class="table-responsive">
          <table class="table align-middle mb-0 table-hover">
            <thead class="table-light">
              <tr>
                <th class="fw-semibold">Periode</th>
                <th class="fw-semibold">Trigger</th>
                <th class="fw-semibold text-center">H-</th>
                <th class="fw-semibold">Target Peran</th>
                <th class="fw-semibold">Kanal</th>
                <th class="fw-semibold text-center">Aktif</th>
                <th class="fw-semibold text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($ta->notifikasiConfigs as $notif)
              <tr>
                <td><span class="badge bg-secondary">{{ $notif->kode_periode }}</span></td>
                <td>
                  @php
                    $triggerLabels = ['MULAI' => 'Mulai', 'AKAN_BERAKHIR' => 'Akan Berakhir', 'BERAKHIR' => 'Berakhir'];
                    $triggerColors = ['MULAI' => 'success', 'AKAN_BERAKHIR' => 'warning', 'BERAKHIR' => 'danger'];
                  @endphp
                  <span class="badge bg-{{ $triggerColors[$notif->trigger_event] ?? 'secondary' }}">{{ $triggerLabels[$notif->trigger_event] ?? $notif->trigger_event }}</span>
                </td>
                <td class="text-center">{{ $notif->hari_sebelum ?? '-' }}</td>
                <td>{{ $notif->target_peran }}</td>
                <td>{{ $notif->kanal }}</td>
                <td class="text-center">{!! $notif->is_aktif ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' !!}</td>
                <td class="text-center">
                  <div class="dropdown">
                    <button class="btn btn-light btn-sm border-0" type="button" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalEditNotif{{ $notif->id_notif_config }}"><i class="ti ti-edit me-2"></i>Edit</a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <form action="{{ route('tahun-akademik.notifikasi.destroy', [$ta->id_tahun_akademik, $notif->id_notif_config]) }}" method="POST" onsubmit="return confirm('Hapus konfigurasi notifikasi ini?')">
                          @csrf @method('DELETE')
                          <button type="submit" class="dropdown-item text-danger"><i class="ti ti-trash me-2"></i>Hapus</button>
                        </form>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
              @empty
              <tr><td colspan="7" class="text-center py-4"><p class="text-muted mb-0">Belum ada konfigurasi notifikasi.</p></td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Modals Edit Notifikasi (outside table) --}}
    @foreach($ta->notifikasiConfigs as $notif)
    <div class="modal fade" id="modalEditNotif{{ $notif->id_notif_config }}" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <form action="{{ route('tahun-akademik.notifikasi.update', [$ta->id_tahun_akademik, $notif->id_notif_config]) }}" method="POST" class="modal-content">
          @csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Edit Konfigurasi Notifikasi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-4">
                <label class="form-label fw-semibold">Kode Periode <span class="text-danger">*</span></label>
                <select name="kode_periode" class="form-select" required>
                  @foreach(['HEREG','KRS','KRS_UBAH','KULIAH','UTS','KULIAH_2','UAS','INPUT_NILAI','VERIF_NILAI','EVALUASI_CPL','YUDISIUM','PENDAFTARAN_WISUDA'] as $kp)
                    <option value="{{ $kp }}" {{ $notif->kode_periode == $kp ? 'selected' : '' }}>{{ $kp }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-4">
                <label class="form-label fw-semibold">Trigger <span class="text-danger">*</span></label>
                <select name="trigger_event" class="form-select" required>
                  @foreach($triggerList as $tr)
                    <option value="{{ $tr }}" {{ $notif->trigger_event == $tr ? 'selected' : '' }}>{{ $tr == 'MULAI' ? 'Mulai' : ($tr == 'AKAN_BERAKHIR' ? 'Akan Berakhir' : 'Berakhir') }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-4">
                <label class="form-label fw-semibold">Hari Sebelum</label>
                <input type="number" name="hari_sebelum" class="form-control" value="{{ $notif->hari_sebelum }}" min="0" max="90" placeholder="Hanya untuk Akan Berakhir">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-6">
                <label class="form-label fw-semibold">Target Peran <span class="text-danger">*</span></label>
                <input type="text" name="target_peran" class="form-control" value="{{ $notif->target_peran }}" placeholder="MAHASISWA,DOSEN,KAPRODI">
                <div class="form-text">Pisahkan dengan koma. Contoh: MAHASISWA,DOSEN</div>
              </div>
              <div class="col-6">
                <label class="form-label fw-semibold">Kanal <span class="text-danger">*</span></label>
                <input type="text" name="kanal" class="form-control" value="{{ $notif->kanal }}" placeholder="Email,Push Notification">
                <div class="form-text">Pisahkan dengan koma. Contoh: Email,In-App</div>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Template Pesan <span class="text-danger">*</span></label>
              <textarea name="template_pesan" class="form-control" rows="3" required>{{ $notif->template_pesan }}</textarea>
              <div class="form-text">Variabel: @{{nama_periode}}, @{{tanggal}}, @{{kode_ta}}</div>
            </div>
            <div class="form-check form-switch">
              <input type="checkbox" name="is_aktif" class="form-check-input" value="1" id="edit_notif_aktif_{{ $notif->id_notif_config }}" {{ $notif->is_aktif ? 'checked' : '' }}>
              <label class="form-check-label fw-semibold" for="edit_notif_aktif_{{ $notif->id_notif_config }}">Aktif</label>
            </div>
          </div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
      </div>
    </div>
    @endforeach

    <!-- ========== TAB 5: SALIN TEMPLATE ========== -->
    <div class="tab-pane fade" id="copy" role="tabpanel">
      <div class="card card-lg mb-6">
        <div class="card-header bg-white border-bottom py-3">
          <h5 class="fw-bold mb-0 d-flex align-items-center">
            <i class="ti ti-copy me-2 fs-3"></i> Salin Template dari TA Sebelumnya
          </h5>
        </div>
        <div class="card-body p-4">
          <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i> Fitur ini akan menyalin <strong>periode kegiatan, event kalender, dan konfigurasi notifikasi</strong> dari TA yang sudah ada sebelumnya. Tanggal akan disesuaikan secara otomatis dengan rentang TA baru.
          </div>
          <form action="{{ route('tahun-akademik.copy-template', $ta->id_tahun_akademik) }}" method="POST" onsubmit="return confirm('Salin template dari TA yang dipilih? Periode yang sudah ada tidak akan ditimpa.')">
            @csrf
            <div class="row g-3 align-items-end">
              <div class="col-md-8">
                <label class="form-label fw-semibold">Pilih TA Sumber <span class="text-danger">*</span></label>
                <select name="id_ta_sumber" class="form-select" required>
                  <option value="">-- Pilih Tahun Akademik --</option>
                  @foreach($taList as $taItem)
                    <option value="{{ $taItem->id_tahun_akademik }}">
                      {{ $taItem->nama_ta }} ({{ $taItem->kode_ta }}) - {{ $taItem->tanggal_mulai->format('d/m/Y') }} s.d {{ $taItem->tanggal_selesai->format('d/m/Y') }}
                      @if($taItem->periodes_count > 0) - {{ $taItem->periodes_count }} periode @endif
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                  <i class="ti ti-copy me-1"></i> Salin Template
                </button>
              </div>
            </div>
          </form>
          @if($taList->isEmpty())
            <div class="text-center py-4">
              <p class="text-muted mb-0">Belum ada TA lain yang bisa dijadikan sumber template.</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</main>

<!-- ========== MODAL TAMBAH PERIODE ========== -->
<div class="modal fade" id="modalTambahPeriode" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('tahun-akademik.periode.store', $ta->id_tahun_akademik) }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header"><h5 class="modal-title">Tambah Periode Kegiatan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-semibold">Kode Periode <span class="text-danger">*</span></label>
          <select name="kode_periode" class="form-select" required>
            <option value="">-- Pilih --</option>
            <option value="HEREG">HEREG - Heregistrasi</option>
            <option value="KRS">KRS - Pengisian KRS</option>
            <option value="KRS_UBAH">KRS_UBAH - Perubahan KRS</option>
            <option value="KULIAH">KULIAH - Perkuliahan</option>
            <option value="UTS">UTS - Ujian Tengah Semester</option>
            <option value="KULIAH_2">KULIAH_2 - Perkuliahan Lanjutan</option>
            <option value="UAS">UAS - Ujian Akhir Semester</option>
            <option value="INPUT_NILAI">INPUT_NILAI - Input Nilai</option>
            <option value="VERIF_NILAI">VERIF_NILAI - Verifikasi Nilai</option>
            <option value="EVALUASI_CPL">EVALUASI_CPL - Evaluasi CPL</option>
            <option value="YUDISIUM">YUDISIUM - Yudisium</option>
            <option value="PENDAFTARAN_WISUDA">PENDAFTARAN_WISUDA - Pendaftaran Wisuda</option>
          </select>
        </div>
        <div class="mb-3"><label class="form-label fw-semibold">Nama Periode <span class="text-danger">*</span></label><input type="text" name="nama_periode" class="form-control" placeholder="Contoh: Pengisian KRS Mahasiswa" required></div>
        <div class="row mb-3">
          <div class="col-6"><label class="form-label fw-semibold">Tgl Mulai <span class="text-danger">*</span></label><input type="date" name="tanggal_mulai" class="form-control" required></div>
          <div class="col-6"><label class="form-label fw-semibold">Tgl Selesai <span class="text-danger">*</span></label><input type="date" name="tanggal_selesai" class="form-control" required></div>
        </div>
        <div class="row mb-3">
          <div class="col-6"><label class="form-label fw-semibold">Jam Mulai</label><input type="time" name="jam_mulai" class="form-control"></div>
          <div class="col-6"><label class="form-label fw-semibold">Jam Selesai</label><input type="time" name="jam_selesai" class="form-control"></div>
        </div>
        <div class="row mb-3">
          <div class="col-6">
            <div class="form-check form-switch mt-2">
              <input type="checkbox" name="is_dapat_diperpanjang" class="form-check-input" value="1" id="tambah_diperpanjang" checked>
              <label class="form-check-label fw-semibold" for="tambah_diperpanjang">Dapat Diperpanjang</label>
            </div>
          </div>
          <div class="col-6"><label class="form-label fw-semibold">Maks Perpanjangan</label><input type="number" name="maks_perpanjangan" class="form-control" placeholder="Kosongkan = tak terbatas" min="0"></div>
        </div>
        <div class="mb-3"><label class="form-label fw-semibold">Keterangan</label><textarea name="keterangan" class="form-control" rows="2"></textarea></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
  </div>
</div>

<!-- ========== MODAL TAMBAH KALENDER ========== -->
<div class="modal fade" id="modalTambahKalender" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('tahun-akademik.kalender.store', $ta->id_tahun_akademik) }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header"><h5 class="modal-title">Tambah Event Kalender</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-semibold">Judul Event <span class="text-danger">*</span></label><input type="text" name="judul_event" class="form-control" placeholder="Contoh: Libur Hari Raya" required></div>
        <div class="mb-3"><label class="form-label fw-semibold">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="2"></textarea></div>
        <div class="row mb-3">
          <div class="col-6">
            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
            <select name="kategori" class="form-select" required>
              @foreach($kategoriEventList as $kat)
                <option value="{{ $kat }}">{{ $kat }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6"><label class="form-label fw-semibold">Warna UI</label><input type="color" name="warna_ui" class="form-control form-control-color" value="#0d6efd"></div>
        </div>
        <div class="row mb-3">
          <div class="col-6"><label class="form-label fw-semibold">Tgl Mulai <span class="text-danger">*</span></label><input type="date" name="tanggal_mulai" class="form-control" required></div>
          <div class="col-6"><label class="form-label fw-semibold">Tgl Selesai</label><input type="date" name="tanggal_selesai" class="form-control"></div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-check form-switch mt-2">
              <input type="checkbox" name="is_libur" class="form-check-input" value="1" id="tambah_libur">
              <label class="form-check-label fw-semibold" for="tambah_libur">Hari Libur</label>
            </div>
          </div>
          <div class="col-6">
            <div class="form-check form-switch mt-2">
              <input type="checkbox" name="is_tampil_publik" class="form-check-input" value="1" id="tambah_publik" checked>
              <label class="form-check-label fw-semibold" for="tambah_publik">Tampil Publik</label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
  </div>
</div>

<!-- ========== MODAL TAMBAH NOTIFIKASI ========== -->
<div class="modal fade" id="modalTambahNotif" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('tahun-akademik.notifikasi.store', $ta->id_tahun_akademik) }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header"><h5 class="modal-title">Tambah Konfigurasi Notifikasi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-4">
            <label class="form-label fw-semibold">Kode Periode <span class="text-danger">*</span></label>
            <select name="kode_periode" class="form-select" required>
              <option value="">-- Pilih --</option>
              @foreach(['HEREG','KRS','KRS_UBAH','KULIAH','UTS','KULIAH_2','UAS','INPUT_NILAI','VERIF_NILAI','EVALUASI_CPL','YUDISIUM','PENDAFTARAN_WISUDA'] as $kp)
                <option value="{{ $kp }}">{{ $kp }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-4">
            <label class="form-label fw-semibold">Trigger <span class="text-danger">*</span></label>
            <select name="trigger_event" class="form-select" required>
              <option value="">-- Pilih --</option>
              @foreach($triggerList as $tr)
                <option value="{{ $tr }}">{{ $tr == 'MULAI' ? 'Mulai' : ($tr == 'AKAN_BERAKHIR' ? 'Akan Berakhir' : 'Berakhir') }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-4">
            <label class="form-label fw-semibold">Hari Sebelum</label>
            <input type="number" name="hari_sebelum" class="form-control" min="0" max="90" placeholder="Hanya untuk Akan Berakhir">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-6">
            <label class="form-label fw-semibold">Target Peran <span class="text-danger">*</span></label>
            <input type="text" name="target_peran" class="form-control" placeholder="MAHASISWA,DOSEN,KAPRODI" required>
            <div class="form-text">Pisahkan dengan koma. Contoh: MAHASISWA,DOSEN</div>
          </div>
          <div class="col-6">
            <label class="form-label fw-semibold">Kanal <span class="text-danger">*</span></label>
            <input type="text" name="kanal" class="form-control" placeholder="Email,Push Notification" required>
            <div class="form-text">Pisahkan dengan koma. Contoh: Email,In-App</div>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Template Pesan <span class="text-danger">*</span></label>
          <textarea name="template_pesan" class="form-control" rows="3" required placeholder="Notifikasi: @{{nama_periode}} akan berakhir pada @{{tanggal}}"></textarea>
          <div class="form-text">Variabel: @{{nama_periode}}, @{{tanggal}}, @{{kode_ta}}</div>
        </div>
        <div class="form-check form-switch">
          <input type="checkbox" name="is_aktif" class="form-check-input" value="1" id="tambah_notif_aktif" checked>
          <label class="form-check-label fw-semibold" for="tambah_notif_aktif">Aktif</label>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
  </div>
</div>
@endsection