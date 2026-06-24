@extends('layouts.app')

@section('content')
<main class="p-2">
  <!-- Header Card -->
  <div class="card border-1 mb-6">
    <div class="card-body p-4">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="mb-1 fw-bold">Detail Tahun Akademik</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Master Data</a></li>
              <li class="breadcrumb-item"><a href="{{ route('tahun-akademik.index') }}">Tahun Akademik</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ $ta->kode_ta }}</li>
            </ol>
          </nav>
        </div>
        <div class="col-auto d-flex gap-2">
          @if($ta->status === 'PERSIAPAN')
            <a href="{{ route('tahun-akademik.edit', $ta->id_tahun_akademik) }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
              <i class="ti ti-edit"></i> Edit
            </a>
            <a href="{{ route('tahun-akademik.aktivasi-panel', $ta->id_tahun_akademik) }}" class="btn btn-success d-inline-flex align-items-center gap-2">
              <i class="ti ti-toggle-left"></i> Aktifkan
            </a>
          @endif
          @if($ta->status === 'AKTIF')
            <form action="{{ route('tahun-akademik.penutupan', $ta->id_tahun_akademik) }}" method="POST" onsubmit="return confirm('Tutup TA ini? Semua data akan dikunci.')">
              @csrf
              <button type="submit" class="btn btn-warning d-inline-flex align-items-center gap-2">
                <i class="ti ti-lock"></i> Tutup Semester
              </button>
            </form>
          @endif
          <a href="{{ route('tahun-akademik.index') }}" class="btn btn-light border fw-semibold px-4">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabs -->
  <ul class="nav nav-tabs nav-fill mb-6" id="taTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active fw-semibold" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">
        <i class="ti ti-info-circle me-1"></i> Informasi Umum
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-semibold" id="periode-tab" data-bs-toggle="tab" data-bs-target="#periode" type="button" role="tab" aria-controls="periode" aria-selected="false">
        <i class="ti ti-list-details me-1"></i> Periode Kegiatan <span class="badge bg-primary ms-1">{{ $ta->periodes->count() }}</span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-semibold" id="kalender-tab" data-bs-toggle="tab" data-bs-target="#kalender" type="button" role="tab" aria-controls="kalender" aria-selected="false">
        <i class="ti ti-calendar-event me-1"></i> Kalender Akademik <span class="badge bg-primary ms-1">{{ $ta->kalenders->count() }}</span>
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-semibold" id="notifikasi-tab" data-bs-toggle="tab" data-bs-target="#notifikasi" type="button" role="tab" aria-controls="notifikasi" aria-selected="false">
        <i class="ti ti-bell me-1"></i> Konfigurasi Notifikasi <span class="badge bg-primary ms-1">{{ $ta->notifikasiConfigs->count() }}</span>
      </button>
    </li>
  </ul>

  <div class="tab-content" id="taTabsContent">
    <!-- ========== TAB 1: INFORMASI UMUM ========== -->
    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
      <!-- Info Cards Row -->
      <div class="row g-4 mb-6">
        <div class="col-md-3">
          <div class="card border-1 shadow-sm">
            <div class="card-body text-center py-4">
              @php
                $statusLabels = ['PERSIAPAN' => 'Persiapan', 'AKTIF' => 'Aktif', 'SELESAI' => 'Selesai', 'DIARSIPKAN' => 'Diarsipkan'];
                $statusColors = ['PERSIAPAN' => 'primary', 'AKTIF' => 'success', 'SELESAI' => 'secondary', 'DIARSIPKAN' => 'dark'];
              @endphp
              <h6 class="text-muted mb-2">Status</h6>
              <span class="badge bg-{{ $statusColors[$ta->status] ?? 'secondary' }} fs-6 px-3 py-2">
                {{ $statusLabels[$ta->status] ?? $ta->status }}
              </span>
              @if($ta->is_aktif)
                <div class="mt-2"><span class="badge bg-warning text-dark">★ Semester Berjalan</span></div>
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-1 shadow-sm">
            <div class="card-body text-center py-4">
              <h6 class="text-muted mb-2">Jenis Semester</h6>
              @php
                $semesterLabels = ['GANJIL' => 'Ganjil', 'GENAP' => 'Genap', 'PENDEK' => 'Pendek'];
                $semesterColors = ['GANJIL' => 'primary', 'GENAP' => 'success', 'PENDEK' => 'info'];
              @endphp
              <span class="badge bg-{{ $semesterColors[$ta->jenis_semester] ?? 'secondary' }} fs-6 px-3 py-2">
                {{ $semesterLabels[$ta->jenis_semester] ?? $ta->jenis_semester }}
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-1 shadow-sm">
            <div class="card-body text-center py-4">
              <h6 class="text-muted mb-2">Periode Kegiatan</h6>
              <h3 class="fw-bold mb-0">{{ $ta->periodes->count() }}</h3>
              <small class="text-muted">periode dikonfigurasi</small>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card border-1 shadow-sm">
            <div class="card-body text-center py-4">
              <h6 class="text-muted mb-2">Minggu Efektif</h6>
              <h3 class="fw-bold mb-0">{{ $ta->jumlah_minggu_efektif }}</h3>
              <small class="text-muted">minggu perkuliahan</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Detail Card -->
      <div class="card card-lg mb-6">
        <div class="card-header bg-white border-bottom py-3">
          <h5 class="fw-bold mb-0 d-flex align-items-center">
            <i class="ti ti-info-circle me-2 fs-3"></i> Detail Informasi
          </h5>
        </div>
        <div class="card-body p-4">
          <div class="row g-4">
            <div class="col-md-3">
              <label class="text-muted small mb-1">Kode TA</label>
              <p class="fw-semibold mb-0">{{ $ta->kode_ta }}</p>
            </div>
            <div class="col-md-3">
              <label class="text-muted small mb-1">Nama TA</label>
              <p class="fw-semibold mb-0">{{ $ta->nama_ta }}</p>
            </div>
            <div class="col-md-3">
              <label class="text-muted small mb-1">Tahun Mulai</label>
              <p class="fw-semibold mb-0">{{ $ta->tahun_mulai }}</p>
            </div>
            <div class="col-md-3">
              <label class="text-muted small mb-1">Tahun Selesai</label>
              <p class="fw-semibold mb-0">{{ $ta->tahun_selesai }}</p>
            </div>
            <div class="col-md-3">
              <label class="text-muted small mb-1">Tanggal Mulai</label>
              <p class="fw-semibold mb-0">{{ $ta->tanggal_mulai->format('d F Y') }}</p>
            </div>
            <div class="col-md-3">
              <label class="text-muted small mb-1">Tanggal Selesai</label>
              <p class="fw-semibold mb-0">{{ $ta->tanggal_selesai->format('d F Y') }}</p>
            </div>
            <div class="col-md-3">
              <label class="text-muted small mb-1">Minggu Efektif</label>
              <p class="fw-semibold mb-0">{{ $ta->jumlah_minggu_efektif }} minggu</p>
            </div>
            <div class="col-md-3">
              <label class="text-muted small mb-1">Jenis Semester</label>
              <p class="fw-semibold mb-0">{{ $semesterLabels[$ta->jenis_semester] ?? $ta->jenis_semester }}</p>
            </div>
            @if($ta->catatan)
            <div class="col-md-12">
              <label class="text-muted small mb-1">Catatan</label>
              <p class="mb-0 text-wrap" style="white-space: pre-line;">{{ $ta->catatan }}</p>
            </div>
            @endif
          </div>

          <hr class="my-4">

          <div class="row g-4">
            <div class="col-md-3">
              <label class="text-muted small mb-1">Dibuat Oleh</label>
              <p class="fw-semibold mb-0">{{ $ta->createdBy->name ?? '-' }}</p>
            </div>
            <div class="col-md-3">
              <label class="text-muted small mb-1">Dibuat Pada</label>
              <p class="fw-semibold mb-0">{{ $ta->created_at->format('d F Y H:i') }}</p>
            </div>
            @if($ta->tanggal_aktivasi)
            <div class="col-md-3">
              <label class="text-muted small mb-1">Tanggal Aktivasi</label>
              <p class="fw-semibold mb-0">{{ $ta->tanggal_aktivasi->format('d F Y H:i') }}</p>
            </div>
            @endif
            @if($ta->diaktifkanOleh)
            <div class="col-md-3">
              <label class="text-muted small mb-1">Diaktifkan Oleh</label>
              <p class="fw-semibold mb-0">{{ $ta->diaktifkanOleh->name }}</p>
            </div>
            @endif
            @if($ta->tanggal_penutupan)
            <div class="col-md-3">
              <label class="text-muted small mb-1">Tanggal Penutupan</label>
              <p class="fw-semibold mb-0">{{ $ta->tanggal_penutupan->format('d F Y H:i') }}</p>
            </div>
            @endif
            @if($ta->ditutupOleh)
            <div class="col-md-3">
              <label class="text-muted small mb-1">Ditutup Oleh</label>
              <p class="fw-semibold mb-0">{{ $ta->ditutupOleh->name }}</p>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- ========== TAB 2: PERIODE KEGIATAN ========== -->
    <div class="tab-pane fade" id="periode" role="tabpanel" aria-labelledby="periode-tab">
      <div class="card card-lg mb-6">
        <div class="card-header bg-white border-bottom py-3">
          <h5 class="fw-bold mb-0 d-flex align-items-center">
            <i class="ti ti-list-details me-2 fs-3"></i> Daftar Periode Kegiatan
          </h5>
        </div>
        <div class="table-responsive">
          <table class="table align-middle mb-0 table-hover">
            <thead class="table-light">
              <tr>
                <th width="50" class="text-center">No</th>
                <th>Kode</th>
                <th>Nama Periode</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Dapat Diperpanjang</th>
                <th>Diperpanjang</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($ta->periodes as $periode)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td><span class="badge bg-secondary">{{ $periode->kode_periode }}</span></td>
                <td class="fw-semibold">{{ $periode->nama_periode }}</td>
                <td>
                  {{ $periode->tanggal_mulai->format('d/m/Y') }}
                  @if($periode->jam_mulai)
                    <small class="text-muted d-block"><i class="ti ti-clock me-1"></i>{{ date('H:i', strtotime($periode->jam_mulai)) }}</small>
                  @endif
                </td>
                <td>
                  {{ $periode->tanggal_selesai->format('d/m/Y') }}
                  @if($periode->jam_selesai)
                    <small class="text-muted d-block"><i class="ti ti-clock me-1"></i>{{ date('H:i', strtotime($periode->jam_selesai)) }}</small>
                  @endif
                </td>
                <td>
                  @if($periode->is_dapat_diperpanjang)
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                      Ya @if($periode->maks_perpanjangan !== null && $periode->maks_perpanjangan > 0)(Maks: {{ $periode->maks_perpanjangan }}x)@else(Bebas)@endif
                    </span>
                  @else
                    <span class="badge bg-light text-muted border">Tidak</span>
                  @endif
                </td>
                <td>
                  @if($periode->riwayat_perpanjangan > 0)
                    <span class="badge bg-warning text-dark fw-bold">{{ $periode->riwayat_perpanjangan }}x</span>
                    <button class="btn btn-xs btn-light border py-0 px-1 ms-1" data-bs-toggle="modal" data-bs-target="#modalRiwayatPerpanjangan{{ $periode->id_periode }}" title="Lihat Detail Riwayat">
                      <i class="ti ti-history me-1"></i>Detail
                    </button>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>
                  @php
                    $today = now()->format('Y-m-d');
                    $tglMulai = $periode->tanggal_mulai->format('Y-m-d');
                    $tglSelesai = $periode->tanggal_selesai->format('Y-m-d');
                  @endphp
                  @if($today < $tglMulai)
                    <span class="badge bg-info">Belum Mulai</span>
                  @elseif($today >= $tglMulai && $today <= $tglSelesai)
                    <span class="badge bg-success">Sedang Berjalan</span>
                  @else
                    <span class="badge bg-secondary">Selesai</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" class="text-center py-4">
                  <p class="text-muted mb-0">Belum ada periode kegiatan yang dikonfigurasi.</p>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ========== TAB 3: KALENDER AKADEMIK ========== -->
    <div class="tab-pane fade" id="kalender" role="tabpanel" aria-labelledby="kalender-tab">
      <div class="card card-lg mb-6">
        <div class="card-header bg-white border-bottom py-3">
          <h5 class="fw-bold mb-0 d-flex align-items-center">
            <i class="ti ti-calendar-event me-2 fs-3"></i> Event Kalender Akademik
          </h5>
        </div>
        <div class="table-responsive">
          <table class="table align-middle mb-0 table-hover">
            <thead class="table-light">
              <tr>
                <th width="50" class="text-center">No</th>
                <th>Event</th>
                <th>Kategori</th>
                <th>Tanggal</th>
                <th class="text-center">Hari Libur</th>
                <th class="text-center">Tampil Publik</th>
              </tr>
            </thead>
            <tbody>
              @forelse($ta->kalenders as $event)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                  <div class="fw-semibold">{{ $event->judul_event }}</div>
                  @if($event->deskripsi)
                    <div class="text-muted small mt-1 text-wrap" style="max-width: 400px;">{{ $event->deskripsi }}</div>
                  @endif
                </td>
                <td>
                  <span class="badge text-white" style="background-color: {{ $event->warna_ui ?? '#6c757d' }}">
                    {{ $event->kategori }}
                  </span>
                </td>
                <td>
                  {{ $event->tanggal_mulai->format('d/m/Y') }}
                  @if($event->tanggal_selesai && $event->tanggal_selesai->format('Y-m-d') !== $event->tanggal_mulai->format('Y-m-d'))
                    s.d {{ $event->tanggal_selesai->format('d/m/Y') }}
                  @endif
                </td>
                <td class="text-center">
                  @if($event->is_libur)
                    <span class="badge bg-danger">Libur</span>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td class="text-center">
                  @if($event->is_tampil_publik)
                    <span class="badge bg-success">Publik</span>
                  @else
                    <span class="badge bg-secondary">Internal</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center py-4">
                  <p class="text-muted mb-0">Belum ada event kalender akademik.</p>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ========== TAB 4: KONFIGURASI NOTIFIKASI ========== -->
    <div class="tab-pane fade" id="notifikasi" role="tabpanel" aria-labelledby="notifikasi-tab">
      <div class="card card-lg mb-6">
        <div class="card-header bg-white border-bottom py-3">
          <h5 class="fw-bold mb-0 d-flex align-items-center">
            <i class="ti ti-bell me-2 fs-3"></i> Konfigurasi Notifikasi
          </h5>
        </div>
        <div class="table-responsive">
          <table class="table align-middle mb-0 table-hover">
            <thead class="table-light">
              <tr>
                <th width="50" class="text-center">No</th>
                <th>Periode</th>
                <th>Trigger Event</th>
                <th class="text-center">Hari Sebelum (H-)</th>
                <th>Target Peran</th>
                <th>Kanal Kirim</th>
                <th>Template Pesan</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($ta->notifikasiConfigs as $notif)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td><span class="badge bg-secondary">{{ $notif->kode_periode }}</span></td>
                <td>
                  @php
                    $triggerLabels = ['MULAI' => 'Mulai', 'AKAN_BERAKHIR' => 'Akan Berakhir', 'BERAKHIR' => 'Berakhir'];
                    $triggerColors = ['MULAI' => 'success', 'AKAN_BERAKHIR' => 'warning', 'BERAKHIR' => 'danger'];
                  @endphp
                  <span class="badge bg-{{ $triggerColors[$notif->trigger_event] ?? 'secondary' }}">
                    {{ $triggerLabels[$notif->trigger_event] ?? $notif->trigger_event }}
                  </span>
                </td>
                <td class="text-center">
                  @if($notif->hari_sebelum !== null)
                    <strong class="text-dark">{{ $notif->hari_sebelum }} hari</strong>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>
                  @foreach(explode(',', $notif->target_peran) as $role)
                    <span class="badge bg-light text-dark border me-1">{{ trim($role) }}</span>
                  @endforeach
                </td>
                <td>
                  @foreach(explode(',', $notif->kanal) as $ch)
                    <span class="badge bg-light text-dark border me-1">{{ trim($ch) }}</span>
                  @endforeach
                </td>
                <td>
                  <code class="text-wrap d-block text-dark font-monospace" style="max-width: 320px; font-size: 0.85rem; background-color: #f8f9fa; padding: 6px; border-radius: 4px; border: 1px solid #dee2e6;">{{ $notif->template_pesan }}</code>
                </td>
                <td class="text-center">
                  @if($notif->is_aktif)
                    <span class="badge bg-success">Aktif</span>
                  @else
                    <span class="badge bg-secondary">Nonaktif</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" class="text-center py-4">
                  <p class="text-muted mb-0">Belum ada konfigurasi notifikasi yang dibuat.</p>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- ========== MODALS FOR EXTENSION HISTORY ========== -->
@foreach($ta->periodes as $periode)
  @if($periode->riwayat_perpanjangan > 0)
  <div class="modal fade" id="modalRiwayatPerpanjangan{{ $periode->id_periode }}" tabindex="-1" aria-labelledby="modalLabel{{ $periode->id_periode }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="modalLabel{{ $periode->id_periode }}">
            <i class="ti ti-history me-2 text-warning"></i> Riwayat Perpanjangan: {{ $periode->nama_periode }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center">
            <i class="ti ti-info-circle fs-6 me-2 text-info"></i>
            <div>
              Periode ini telah diperpanjang sebanyak <strong>{{ $periode->riwayat_perpanjangan }} kali</strong>.
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th width="50" class="text-center">No</th>
                  <th>Tanggal Selesai Lama</th>
                  <th>Tanggal Selesai Baru</th>
                  <th>Alasan Perpanjangan</th>
                  <th>Diperpanjang Oleh</th>
                  <th>Waktu Perpanjangan</th>
                </tr>
              </thead>
              <tbody>
                @foreach($periode->perpanjangans as $index => $perpanjangan)
                <tr>
                  <td class="text-center">{{ $index + 1 }}</td>
                  <td class="text-danger fw-semibold">{{ $perpanjangan->tanggal_selesai_lama->format('d/m/Y') }}</td>
                  <td class="text-success fw-semibold">{{ $perpanjangan->tanggal_selesai_baru->format('d/m/Y') }}</td>
                  <td class="text-wrap" style="max-width: 250px;">{{ $perpanjangan->alasan }}</td>
                  <td>{{ $perpanjangan->diperpanjangOleh->name ?? '-' }}</td>
                  <td>{{ $perpanjangan->diperpanjang_pada->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light border fw-semibold" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  @endif
@endforeach
@endsection