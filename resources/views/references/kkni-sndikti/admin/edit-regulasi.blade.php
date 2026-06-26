@extends('layouts.app')

@section('content')
<main class="p-2">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2 text-success"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-triangle fs-4 me-2 text-danger"></i>
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card border-1 shadow-sm mb-6">
    <div class="card-body p-4">
      <div class="row mb-4 align-items-center">
        <div class="col-md-6 col-12">
          <h3 class="mb-1 mt-2 fw-bold">Panel Regulasi</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Data Master</a></li>
              <li class="breadcrumb-item"><a href="{{ route('kkni-sndikti.index') }}">KKNI SNDikti</a></li>
              <li class="breadcrumb-item active">{{ $regulasi->nomor_peraturan }}</li>
            </ol>
          </nav>
        </div>
        <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
          <a href="{{ route('kkni-sndikti.index') }}" class="btn btn-light border fw-semibold">
            <i class="ti ti-arrow-left me-1"></i> Dashboard
          </a>
          @if($isEditing)
            <a href="{{ route('kkni-sndikti.admin.preview-aktivasi', $regulasi->id_regulasi) }}" class="btn btn-success d-inline-flex align-items-center gap-2">
              <i class="ti ti-toggle-left"></i> Preview & Aktifkan
            </a>
          @endif
        </div>
      </div>

      <!-- Status Badge -->
      <div class="d-flex align-items-center gap-2 mb-4">
        <span class="badge bg-{{ $regulasi->status === 'Aktif' ? 'success' : ($regulasi->status === 'Draft' ? 'warning text-dark' : 'secondary') }} fs-6 px-3 py-2">
          {{ $regulasi->status }}
        </span>
        @if($regulasi->is_aktif)
          <span class="badge bg-info fs-6 px-3 py-2">Sedang Berlaku</span>
        @endif
        <span class="badge bg-dark fs-6 px-3 py-2">{{ $regulasi->jenis_regulasi }}</span>
      </div>

      @if(!$isEditing)
        <div class="alert alert-secondary border-0">
          <i class="ti ti-lock me-2"></i> Regulasi ini sudah <strong>{{ $regulasi->status }}</strong> dan tidak dapat diedit. Data bersifat read-only.
        </div>
      @endif

      <!-- Informasi Regulasi -->
      <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
        <li class="nav-item">
          <button class="nav-link active fw-semibold" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">
            <i class="ti ti-info-circle me-1"></i> Informasi Regulasi
          </button>
        </li>
        @if($regulasi->jenis_regulasi === 'KKNI')
        <li class="nav-item">
          <button class="nav-link fw-semibold" id="kkni-level-tab" data-bs-toggle="tab" data-bs-target="#kkni-level" type="button">
            <i class="ti ti-certificate-2 me-1"></i> Level KKNI
            <span class="badge bg-primary ms-1">{{ $kkniLevels->count() }}</span>
          </button>
        </li>
        @endif
        @if($regulasi->jenis_regulasi === 'SN-Dikti')
        <li class="nav-item">
          <button class="nav-link fw-semibold" id="butir-tab" data-bs-toggle="tab" data-bs-target="#butir" type="button">
            <i class="ti ti-list-details me-1"></i> Butir SN-Dikti
          </button>
        </li>
        @endif
        @if($regulasiLama)
        <li class="nav-item">
          <button class="nav-link fw-semibold" id="dampak-tab" data-bs-toggle="tab" data-bs-target="#dampak" type="button">
            <i class="ti ti-users me-1"></i> Dampak Prodi ({{ $dampakProdi['total_prodi'] }})
          </button>
        </li>
        @endif
      </ul>

      <div class="tab-content">
        <!-- TAB 1: Info Regulasi -->
        <div class="tab-pane fade show active" id="info">
          @if($isEditing)
          <form action="{{ route('kkni-sndikti.admin.regulasi.update', $regulasi->id_regulasi) }}" method="POST" class="row g-4">
            @csrf
            @method('PUT')

            <div class="col-md-6">
              <label class="form-label fw-semibold">Nomor Peraturan</label>
              <input type="text" name="nomor_peraturan" class="form-control" value="{{ old('nomor_peraturan', $regulasi->nomor_peraturan) }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Versi</label>
              <input type="text" name="versi" class="form-control" value="{{ old('versi', $regulasi->versi) }}" required>
            </div>
            <div class="col-md-12">
              <label class="form-label fw-semibold">Judul Peraturan</label>
              <textarea name="judul_peraturan" class="form-control" rows="2" required>{{ old('judul_peraturan', $regulasi->judul_peraturan) }}</textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Instansi Penerbit</label>
              <input type="text" name="instansi_penerbit" class="form-control" value="{{ old('instansi_penerbit', $regulasi->instansi_penerbit) }}" required>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Tanggal Terbit</label>
              <input type="date" name="tanggal_terbit" class="form-control" value="{{ old('tanggal_terbit', $regulasi->tanggal_terbit?->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Tanggal Berlaku</label>
              <input type="date" name="tanggal_berlaku" class="form-control" value="{{ old('tanggal_berlaku', $regulasi->tanggal_berlaku?->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-12">
              <label class="form-label fw-semibold">URL Dokumen Resmi</label>
              <input type="url" name="url_dokumen_resmi" class="form-control" value="{{ old('url_dokumen_resmi', $regulasi->url_dokumen_resmi) }}">
            </div>
            <div class="col-md-12">
              <label class="form-label fw-semibold">Catatan</label>
              <textarea name="catatan" class="form-control" rows="2">{{ old('catatan', $regulasi->catatan) }}</textarea>
            </div>
            <div class="col-md-12">
              <label class="form-label fw-semibold">Alasan Perubahan <span class="text-danger">*</span></label>
              <textarea name="alasan_perubahan" class="form-control @error('alasan_perubahan') is-invalid @enderror" rows="2" required minlength="10" placeholder="Jelaskan alasan perubahan data regulasi...">{{ old('alasan_perubahan') }}</textarea>
              <small class="text-muted">Alasan ini wajib diisi dan akan tercatat di changelog.</small>
              @error('alasan_perubahan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-dark px-5">
                <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
              </button>
            </div>
          </form>
          @else
          <div class="row g-4">
            <div class="col-md-6"><label class="text-muted small">Nomor Peraturan</label><p class="fw-semibold">{{ $regulasi->nomor_peraturan }}</p></div>
            <div class="col-md-3"><label class="text-muted small">Versi</label><p class="fw-semibold">{{ $regulasi->versi }}</p></div>
            <div class="col-md-3"><label class="text-muted small">Jenis</label><p class="fw-semibold">{{ $regulasi->jenis_regulasi }}</p></div>
            <div class="col-md-12"><label class="text-muted small">Judul</label><p>{{ $regulasi->judul_peraturan }}</p></div>
            <div class="col-md-6"><label class="text-muted small">Instansi</label><p>{{ $regulasi->instansi_penerbit }}</p></div>
            <div class="col-md-3"><label class="text-muted small">Tanggal Terbit</label><p>{{ $regulasi->tanggal_terbit?->format('d/m/Y') }}</p></div>
            <div class="col-md-3"><label class="text-muted small">Tanggal Berlaku</label><p>{{ $regulasi->tanggal_berlaku?->format('d/m/Y') }}</p></div>
            @if($regulasi->catatan)<div class="col-md-12"><label class="text-muted small">Catatan</label><p>{{ $regulasi->catatan }}</p></div>@endif
          </div>
          @endif
        </div>

        <!-- TAB 2: Level KKNI -->
        @if($regulasi->jenis_regulasi === 'KKNI')
        <div class="tab-pane fade" id="kkni-level">
          @if($isEditing)
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="fw-bold mb-0">Daftar Level KKNI</h5>
              <a href="{{ route('kkni-sndikti.admin.kkni-level.create', $regulasi->id_regulasi) }}" class="btn btn-dark btn-sm">
                <i class="ti ti-plus me-1"></i> Tambah Level
              </a>
            </div>
          @endif

          @forelse($kkniLevels as $level)
          <div class="card border-1 bg-light mb-3">
            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
              <h6 class="fw-bold mb-0">
                <span class="badge bg-primary me-2 rounded-pill">Level {{ $level->level }}</span>
                {{ $level->nama_level }}
                @if($level->jenjang_pendidikan)
                  <small class="text-muted ms-2">({{ $level->jenjang_pendidikan }})</small>
                @endif
              </h6>
              @if($isEditing)
              <div class="d-flex gap-1">
                <a href="{{ route('kkni-sndikti.admin.kkni-level.edit', [$regulasi->id_regulasi, $level->id_kkni_level]) }}" class="btn btn-sm btn-light border" title="Edit">
                  <i class="ti ti-edit"></i>
                </a>
                <form action="{{ route('kkni-sndikti.admin.kkni-level.destroy', [$regulasi->id_regulasi, $level->id_kkni_level]) }}" method="POST" onsubmit="return confirm('Hapus Level {{ $level->level }}? Semua deskriptor terkait juga akan dihapus.')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus">
                    <i class="ti ti-trash"></i>
                  </button>
                </form>
              </div>
              @endif
            </div>
            <div class="card-body p-3">
              <p class="text-muted small mb-2">{{ $level->deskripsi_umum }}</p>
              @php
                $deskByArea = $level->deskriptors->groupBy('area_kompetensi');
              @endphp
              <div class="row g-2">
                @foreach(['Sikap & Tata Nilai', 'Kemampuan Kerja', 'Pengetahuan', 'Tanggung Jawab & Hak'] as $area)
                  <div class="col-md-6">
                    <div class="p-2 bg-white rounded border-start border-3">
                      <small class="fw-semibold text-muted">{{ $area }}</small>
                      <p class="mb-0 small">
                        {{ isset($deskByArea[$area]) ? $deskByArea[$area]->first()->deskripsi : '<em class="text-muted">Belum tersedia</em>' }}
                      </p>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
          @empty
          <div class="text-center py-5">
            <i class="ti ti-certificate-2 fs-1 text-muted"></i>
            <p class="mt-2 text-muted">Belum ada level KKNI yang ditambahkan.</p>
            @if($isEditing)
            <a href="{{ route('kkni-sndikti.admin.kkni-level.create', $regulasi->id_regulasi) }}" class="btn btn-dark btn-sm mt-2">
              <i class="ti ti-plus me-1"></i> Tambah Level KKNI
            </a>
            @endif
          </div>
          @endforelse
        </div>
        @endif

        <!-- TAB 3: Butir SN-Dikti -->
        @if($regulasi->jenis_regulasi === 'SN-Dikti')
        <div class="tab-pane fade" id="butir">
          @if($isEditing)
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="fw-bold mb-0">Daftar Butir SN-Dikti</h5>
              <a href="{{ route('kkni-sndikti.admin.butir.create', $regulasi->id_regulasi) }}" class="btn btn-dark btn-sm">
                <i class="ti ti-plus me-1"></i> Tambah Butir
              </a>
            </div>
          @endif

          <ul class="nav nav-pills mb-3 gap-2" id="butirJenjangTabs">
            @foreach($jenjangList as $jenjang)
            <li class="nav-item">
              <button class="nav-link {{ $loop->first ? 'active' : '' }} fw-semibold btn-sm" data-bs-toggle="tab" data-bs-target="#butir-{{ $jenjang }}">
                {{ $jenjang }}
              </button>
            </li>
            @endforeach
          </ul>

          <div class="tab-content">
            @foreach($jenjangList as $jenjang)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="butir-{{ $jenjang }}">
              @foreach(['Sikap', 'Keterampilan Umum'] as $kategori)
                @php $items = $butirByJenjang[$jenjang][$kategori] ?? collect(); @endphp
                <div class="card border-1 bg-light mb-3">
                  <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">{{ $kategori }} <span class="badge bg-secondary ms-1">{{ $items->count() }} butir</span></h6>
                  </div>
                  <div class="card-body p-2">
                    @forelse($items as $butir)
                    <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded mb-1 border-start border-3 border-{{ $kategori === 'Sikap' ? 'danger' : 'primary' }}">
                      <div>
                        <span class="badge bg-{{ $kategori === 'Sikap' ? 'danger' : 'primary' }} bg-opacity-10 text-{{ $kategori === 'Sikap' ? 'danger' : 'primary' }} me-1">{{ $butir->kode_butir }}</span>
                        @if($butir->is_wajib)<span class="badge bg-success badge-sm">Wajib</span>@endif
                        <span class="small ms-2">{{ Str::limit($butir->deskripsi, 120) }}</span>
                      </div>
                      @if($isEditing)
                      <div class="d-flex gap-1">
                        <a href="{{ route('kkni-sndikti.admin.butir.edit', [$regulasi->id_regulasi, $butir->id_sndikti]) }}" class="btn btn-sm btn-light border" title="Edit">
                          <i class="ti ti-edit"></i>
                        </a>
                        <form action="{{ route('kkni-sndikti.admin.butir.destroy', [$regulasi->id_regulasi, $butir->id_sndikti]) }}" method="POST" onsubmit="return confirm('Hapus butir {{ $butir->kode_butir }}?')">
                          @csrf @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus">
                            <i class="ti ti-trash"></i>
                          </button>
                        </form>
                      </div>
                      @endif
                    </div>
                    @empty
                    <p class="text-muted small text-center py-2"><em>Belum ada butir {{ $kategori }} untuk jenjang {{ $jenjang }}</em></p>
                    @endforelse
                  </div>
                </div>
              @endforeach
            </div>
            @endforeach
          </div>
        </div>
        @endif

        <!-- TAB 3: Dampak Prodi -->
        @if($regulasiLama)
        <div class="tab-pane fade" id="dampak">
          <div class="alert alert-warning border-0 d-flex align-items-center">
            <i class="ti ti-alert-triangle fs-4 me-2 text-warning"></i>
            <div>
              <strong>{{ $dampakProdi['pesan'] }}</strong><br>
              <small>Regulasi lama <strong>{{ $regulasiLama->nomor_peraturan }}</strong> akan dinonaktifkan.</small>
            </div>
          </div>
          @if(count($dampakProdi['prodi_perlu_update']) > 0)
          <div class="table-responsive">
            <table class="table table-sm table-bordered">
              <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Kode Prodi</th>
                  <th>Nama Prodi</th>
                  <th>Jenjang</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($dampakProdi['prodi_perlu_update'] as $prodi)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $prodi->kode_prodi }}</td>
                  <td>{{ $prodi->nama_prodi }}</td>
                  <td><span class="badge bg-dark">{{ $prodi->jenjang }}</span></td>
                  <td><span class="badge bg-warning text-dark">Perlu Penyesuaian</span></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @endif
        </div>
        @endif
      </div>
    </div>
  </div>
</main>
@endsection
