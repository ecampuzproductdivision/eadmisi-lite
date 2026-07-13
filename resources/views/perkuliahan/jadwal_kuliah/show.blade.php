@extends('layouts.app')

@section('content')
<main class="p-4">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert" style="border-radius:12px;">
      <div class="d-flex align-items-center"><i class="ti ti-circle-check fs-4 me-2"></i><span>{{ session('success') }}</span></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert" style="border-radius:12px;">
      <div class="d-flex align-items-center"><i class="ti ti-alert-triangle fs-4 me-2"></i>
        <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- ============================================================ --}}
  {{-- BREADCRUMB                                                    --}}
  {{-- ============================================================ --}}
  <nav class="mb-3" style="font-size:0.85rem;">
    <a href="{{ route('jadwal-kuliah.index') }}" class="text-decoration-none text-muted">
      <i class="ti ti-calendar-time me-1"></i>Jadwal Kuliah
    </a>
    <span class="text-muted mx-2">/</span>
    <span class="fw-semibold" style="color:#1e293b;">{{ $jadwal->kode_kelas }}</span>
  </nav>

  {{-- ============================================================ --}}
  {{-- HEADER CARD                                                  --}}
  {{-- ============================================================ --}}
  <div class="card border-1 shadow-sm mb-4" style="background:linear-gradient(135deg,#0f1f3d 0%,#1a3a6e 50%,#0d3b6e 100%);border-radius:20px;overflow:hidden;position:relative;">
    <div style="position:absolute;top:0;right:0;width:300px;height:200px;background:rgba(56,189,248,0.07);border-radius:50%;transform:translate(30%,-30%);"></div>
    <div class="card-body p-4 p-md-5 text-white position-relative">
      <div class="row align-items-start">
        <div class="col-lg-8">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="d-flex align-items-center justify-content-center rounded-3" style="width:56px;height:56px;background:rgba(56,189,248,0.2);backdrop-filter:blur(10px);">
              <i class="ti ti-calendar-event" style="font-size:1.6rem;color:#38bdf8;"></i>
            </div>
            <div>
              <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge" style="background:rgba(56,189,248,0.2);color:#7dd3fc;border:1px solid rgba(56,189,248,0.3);border-radius:20px;font-size:0.72rem;">Perkuliahan → Jadwal Kuliah</span>
                @php
                  $badgeStyle = match($jadwal->status_jadwal) {
                    'Draft'          => 'background:rgba(100,116,139,0.25);color:#cbd5e1;',
                    'Dikonfirmasi'   => 'background:rgba(59,130,246,0.25);color:#93c5fd;',
                    'Dipublikasikan' => 'background:rgba(16,185,129,0.25);color:#6ee7b7;',
                    'Direvisi'       => 'background:rgba(245,158,11,0.25);color:#fcd34d;',
                    'Selesai'        => 'background:rgba(168,85,247,0.25);color:#d8b4fe;',
                    default          => 'background:rgba(100,116,139,0.25);color:#cbd5e1;',
                  };
                @endphp
                <span class="badge" style="{{ $badgeStyle }}border-radius:20px;font-size:0.72rem;">{{ $jadwal->status_jadwal }}</span>
              </div>
              <h2 class="fw-bold text-white mb-0" style="font-size:1.7rem;">{{ $jadwal->kode_kelas }}</h2>
              @if($jadwal->nama_kelas)
                <p class="mb-0" style="color:rgba(255,255,255,0.65);font-size:0.95rem;">{{ $jadwal->nama_kelas }}</p>
              @endif
            </div>
          </div>

          {{-- MK Info --}}
          @php $mk = $jadwal->kurikulumMataKuliah?->mataKuliah; @endphp
          @if($mk)
            <div class="d-flex flex-wrap gap-3 mb-3">
              <div class="d-flex align-items-center gap-2" style="color:rgba(255,255,255,0.75);">
                <i class="ti ti-book" style="color:#38bdf8;"></i>
                <span>{{ $mk->mk_nama }} ({{ $mk->mk_kode }})</span>
              </div>
              <div class="d-flex align-items-center gap-2" style="color:rgba(255,255,255,0.75);">
                <i class="ti ti-hash" style="color:#38bdf8;"></i>
                <span>{{ $mk->sks ?? '-' }} SKS</span>
              </div>
              <div class="d-flex align-items-center gap-2" style="color:rgba(255,255,255,0.75);">
                <i class="ti ti-building" style="color:#38bdf8;"></i>
                <span>{{ $jadwal->kurikulumMataKuliah?->kurikulum?->programStudi?->prodiNamaResmi ?? '-' }}</span>
              </div>
            </div>
          @endif

          {{-- Schedule Info --}}
          <div class="d-flex flex-wrap gap-3">
            @if($jadwal->hari && $jadwal->jam_mulai)
              <span class="d-flex align-items-center gap-1" style="color:rgba(255,255,255,0.8);background:rgba(255,255,255,0.08);padding:6px 12px;border-radius:8px;font-size:0.85rem;">
                <i class="ti ti-calendar" style="color:#38bdf8;"></i>
                {{ $jadwal->hari }}, {{ substr($jadwal->jam_mulai,0,5) }}–{{ substr($jadwal->jam_selesai,0,5) }}
              </span>
            @endif
            @if($jadwal->jenis_kelas === 'Online')
              <span class="d-flex align-items-center gap-1" style="color:rgba(255,255,255,0.8);background:rgba(255,255,255,0.08);padding:6px 12px;border-radius:8px;font-size:0.85rem;">
                <i class="ti ti-wifi" style="color:#38bdf8;"></i>Online
              </span>
            @elseif($jadwal->ruangan)
              <span class="d-flex align-items-center gap-1" style="color:rgba(255,255,255,0.8);background:rgba(255,255,255,0.08);padding:6px 12px;border-radius:8px;font-size:0.85rem;">
                <i class="ti ti-map-pin" style="color:#38bdf8;"></i>
                {{ $jadwal->ruangan->kode_ruangan }} — {{ $jadwal->ruangan->nama_ruangan }}
              </span>
            @endif
            <span class="d-flex align-items-center gap-1" style="color:rgba(255,255,255,0.8);background:rgba(255,255,255,0.08);padding:6px 12px;border-radius:8px;font-size:0.85rem;">
              <i class="ti ti-users" style="color:#38bdf8;"></i>
              {{ $jadwal->jumlah_terdaftar }}/{{ $jadwal->kapasitas_kelas }} Mahasiswa
            </span>
          </div>
        </div>

        <div class="col-lg-4 mt-3 mt-lg-0 d-flex justify-content-lg-end">
          <div class="d-flex flex-column gap-2">
            @if($jadwal->status_jadwal === 'Draft' && !$jadwal->isSelesai())
              <form action="{{ route('jadwal-kuliah.publikasikan', $jadwal->id_jadwal) }}" method="POST">
                @csrf
                <button type="submit" class="btn" style="background:#38bdf8;color:#0f172a;border-radius:10px;font-weight:600;" onclick="return confirmAction(event, 'Publikasikan jadwal {{ $jadwal->kode_kelas }}?')">
                  <i class="ti ti-send me-1"></i>Publikasikan
                </button>
              </form>
            @endif
            @if(!$jadwal->isSelesai())
              <button class="btn" style="border:1px solid rgba(255,255,255,0.2);color:#fff;border-radius:10px;" data-bs-toggle="modal" data-bs-target="#modalEditJadwal">
                <i class="ti ti-edit me-1"></i>Edit Jadwal
              </button>
            @endif
            <a href="{{ route('jadwal-kuliah.index', ['tahun_akademik_id' => $jadwal->id_tahun_akademik]) }}" class="btn" style="border:1px solid rgba(255,255,255,0.15);color:rgba(255,255,255,0.7);border-radius:10px;">
              <i class="ti ti-arrow-left me-1"></i>Kembali
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ============================================================ --}}
  {{-- KONFLIK ALERT                                                --}}
  {{-- ============================================================ --}}
  @if($konflikDosen->isNotEmpty() || $konflikRuangan->isNotEmpty())
    <div class="alert border-0 mb-4" style="background:rgba(239,68,68,0.08);border-left:4px solid #ef4444 !important;border-radius:12px;">
      <div class="fw-bold text-danger mb-2"><i class="ti ti-alert-circle me-2"></i>Jadwal ini memiliki konflik yang harus diselesaikan sebelum dipublikasikan</div>
      @if($konflikDosen->isNotEmpty())
        <div class="mb-1"><strong>Konflik Dosen:</strong> Dosen yang sama dijadwalkan di kelas lain pada waktu yang sama.</div>
        @foreach($konflikDosen as $k)
          <div class="small text-muted ms-3">— Bentrok dengan kelas <strong>{{ $k->kode_kelas }}</strong> ({{ $k->hari }}, {{ substr($k->jam_mulai,0,5) }}–{{ substr($k->jam_selesai,0,5) }})</div>
        @endforeach
      @endif
      @if($konflikRuangan->isNotEmpty())
        <div class="mt-1 mb-1"><strong>Konflik Ruangan:</strong> Ruangan yang sama terpakai oleh kelas lain pada waktu yang sama.</div>
        @foreach($konflikRuangan as $k)
          <div class="small text-muted ms-3">— Bentrok dengan kelas <strong>{{ $k->kode_kelas }}</strong> ({{ $k->hari }}, {{ substr($k->jam_mulai,0,5) }}–{{ substr($k->jam_selesai,0,5) }})</div>
        @endforeach
      @endif
    </div>
  @else
    <div class="alert border-0 mb-4 d-flex align-items-center gap-2" style="background:rgba(16,185,129,0.07);border-left:4px solid #10b981 !important;border-radius:12px;">
      <i class="ti ti-circle-check text-success fs-4"></i>
      <span class="text-success fw-semibold">Tidak ada konflik dosen atau ruangan terdeteksi.</span>
    </div>
  @endif

  {{-- ============================================================ --}}
  {{-- MAIN CONTENT — Two Column Layout                             --}}
  {{-- ============================================================ --}}
  <div class="row g-4">

    {{-- ---- LEFT COLUMN: Info Jadwal + Tim Dosen ---- --}}
    <div class="col-lg-5">

      {{-- Detail Jadwal --}}
      <div class="card border-1 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-header bg-transparent border-0 pb-0 pt-4 px-4">
          <h6 class="fw-bold mb-0" style="color:#1e293b;"><i class="ti ti-info-circle me-2 text-primary"></i>Detail Jadwal</h6>
        </div>
        <div class="card-body px-4 pb-4 pt-3">
          <div class="d-flex flex-column gap-2">
            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f1f5f9;">
              <span class="small text-muted">Tahun Akademik</span>
              <span class="fw-semibold small" style="color:#1e293b;">{{ $jadwal->tahunAkademik?->nama_ta ?? '-' }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f1f5f9;">
              <span class="small text-muted">Jenis Kelas</span>
              <span class="badge" style="background:rgba(99,102,241,0.1);color:#6366f1;border-radius:8px;">{{ $jadwal->jenis_kelas }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f1f5f9;">
              <span class="small text-muted">Hari</span>
              <span class="fw-semibold small" style="color:#1e293b;">{{ $jadwal->hari ?? 'Belum dijadwalkan' }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f1f5f9;">
              <span class="small text-muted">Waktu</span>
              <span class="fw-semibold small" style="color:#1e293b;">
                @if($jadwal->jam_mulai)
                  {{ substr($jadwal->jam_mulai,0,5) }} – {{ substr($jadwal->jam_selesai,0,5) }}
                  <span class="text-muted">({{ $jadwal->durasi_menit }} mnt)</span>
                @else
                  Belum ditetapkan
                @endif
              </span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f1f5f9;">
              <span class="small text-muted">Ruangan</span>
              <span class="fw-semibold small" style="color:#1e293b;">
                @if($jadwal->jenis_kelas === 'Online')
                  <span class="badge" style="background:rgba(6,182,212,0.1);color:#0891b2;">Online</span>
                @elseif($jadwal->ruangan)
                  {{ $jadwal->ruangan->kode_ruangan }} <span class="text-muted">(Kap. {{ $jadwal->ruangan->kapasitas }})</span>
                @else
                  <span class="text-danger">Belum dipilih</span>
                @endif
              </span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid #f1f5f9;">
              <span class="small text-muted">Kapasitas Kelas</span>
              <span class="fw-semibold small" style="color:#1e293b;">{{ $jadwal->kapasitas_kelas }} mahasiswa</span>
            </div>
            <div class="d-flex justify-content-between align-items-center py-2">
              <span class="small text-muted">Terdaftar (KRS)</span>
              <span class="fw-bold" style="color:{{ $jadwal->jumlah_terdaftar >= $jadwal->kapasitas_kelas ? '#ef4444' : '#1e293b' }};">
                {{ $jadwal->jumlah_terdaftar }} / {{ $jadwal->kapasitas_kelas }}
              </span>
            </div>
          </div>
          @if($jadwal->catatan)
            <div class="mt-3 p-3 rounded-3" style="background:#f8fafc;">
              <div class="small text-muted fw-semibold mb-1">Catatan:</div>
              <div class="small" style="color:#475569;">{{ $jadwal->catatan }}</div>
            </div>
          @endif
        </div>
      </div>

      {{-- Tim Dosen --}}
      <div class="card border-1 shadow-sm" style="border-radius:16px;">
        <div class="card-header bg-transparent border-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
          <h6 class="fw-bold mb-0" style="color:#1e293b;"><i class="ti ti-users me-2 text-primary"></i>Tim Pengampu</h6>
          @if(!$jadwal->isSelesai())
            <button class="btn btn-sm" style="background:rgba(99,102,241,0.1);color:#6366f1;border:none;border-radius:8px;font-size:0.78rem;" data-bs-toggle="modal" data-bs-target="#modalTambahDosen">
              <i class="ti ti-plus me-1"></i>Tambah Dosen
            </button>
          @endif
        </div>
        <div class="card-body px-4 pb-4 pt-3">
          @forelse($jadwal->timDosen as $td)
            <div class="d-flex align-items-center gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color:#f1f5f9;">
              <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px;background:linear-gradient(135deg,#3b82f6,#6366f1);color:#fff;font-weight:600;font-size:0.85rem;">
                {{ strtoupper(substr($td->dosen?->nama_lengkap ?? 'D', 0, 1)) }}
              </div>
              <div class="flex-fill">
                <div class="fw-semibold small" style="color:#1e293b;">{{ $td->dosen?->nama_lengkap ?? 'Dosen tidak ditemukan' }}</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                  <span class="badge" style="font-size:0.68rem;background:{{ match($td->peran) { 'Koordinator' => 'rgba(99,102,241,0.1)', 'Pengampu' => 'rgba(16,185,129,0.1)', default => 'rgba(100,116,139,0.1)' } }};color:{{ match($td->peran) { 'Koordinator' => '#6366f1', 'Pengampu' => '#059669', default => '#64748b' } }};border-radius:6px;">
                    {{ $td->peran }}
                  </span>
                  @if($td->pertemuan_ke)
                    <span class="small text-muted">Pertemuan: {{ $td->pertemuan_ke }}</span>
                  @endif
                </div>
              </div>
              <div class="d-flex align-items-center gap-2">
                @php
                  $konfStyle = match($td->status_konfirmasi) {
                    'Dikonfirmasi' => 'color:#059669;', 'Menolak' => 'color:#ef4444;', default => 'color:#d97706;'
                  };
                  $konfIcon = match($td->status_konfirmasi) {
                    'Dikonfirmasi' => 'ti-check', 'Menolak' => 'ti-x', default => 'ti-clock'
                  };
                @endphp
                <i class="ti {{ $konfIcon }} small" style="{{ $konfStyle }}" title="{{ $td->status_konfirmasi }}"></i>
                @if(!$jadwal->isSelesai())
                  <button class="btn btn-sm btn-link p-0 text-danger hapus-dosen-btn"
                    data-jadwal="{{ $jadwal->id_jadwal }}" data-jd="{{ $td->id_jadwal_dosen }}" title="Hapus dari tim">
                    <i class="ti ti-trash small"></i>
                  </button>
                @endif
              </div>
            </div>
          @empty
            <div class="text-center py-3 text-muted">
              <i class="ti ti-user-off" style="font-size:2rem;opacity:0.4;"></i>
              <div class="small mt-1">Belum ada dosen dalam tim pengampu</div>
            </div>
          @endforelse
        </div>
      </div>
    </div>

    {{-- ---- RIGHT COLUMN: Log Perubahan ---- --}}
    <div class="col-lg-7">

      {{-- Sesi Jadwal (Multi Sesi) --}}
      <div class="card border-1 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-header bg-transparent border-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
          <h6 class="fw-bold mb-0" style="color:#1e293b;"><i class="ti ti-calendar-repeat me-2 text-primary"></i>Sesi Jadwal</h6>
          <span class="badge" style="background:rgba(99,102,241,0.1);color:#6366f1;border-radius:8px;font-size:0.75rem;">{{ $jadwal->sesiJadwal->count() }} sesi</span>
        </div>
        <div class="card-body px-4 pb-4 pt-3">
          @if($jadwal->sesiJadwal->isEmpty())
            <div class="text-center py-3 rounded-3" style="background:#f8fafc;">
              <i class="ti ti-calendar" style="font-size:2rem;color:#cbd5e1;"></i>
              <div class="small text-muted mt-1">Jadwal utama berlaku ({{ $jadwal->hari ?? '—' }}, {{ $jadwal->jam_mulai ? substr($jadwal->jam_mulai,0,5).' – '.substr($jadwal->jam_selesai,0,5) : '—' }})</div>
              <div class="small text-muted">Tambah sesi jika MK dijadwal di hari yang berbeda.</div>
            </div>
          @else
            @foreach($jadwal->sesiJadwal->sortBy('nomor_sesi') as $sesi)
              <div class="d-flex align-items-center gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color:#f1f5f9;">
                <div class="d-flex align-items-center justify-content-center rounded-2 fw-bold text-white" style="width:32px;height:32px;background:linear-gradient(135deg,#6366f1,#8b5cf6);font-size:0.8rem;flex-shrink:0;">
                  {{ $sesi->nomor_sesi }}
                </div>
                <div class="flex-fill">
                  <div class="fw-semibold small" style="color:#1e293b;">{{ $sesi->hari }}, {{ substr($sesi->jam_mulai,0,5) }}–{{ substr($sesi->jam_selesai,0,5) }}</div>
                  <div class="small text-muted">{{ $sesi->sks_sesi }} SKS · {{ $sesi->ruangan?->kode_ruangan ?? 'Sama dengan kelas' }}</div>
                </div>
              </div>
            @endforeach
          @endif
        </div>
      </div>

      {{-- Log Perubahan --}}
      <div class="card border-1 shadow-sm" style="border-radius:16px;">
        <div class="card-header bg-transparent border-0 pb-0 pt-4 px-4">
          <h6 class="fw-bold mb-0" style="color:#1e293b;"><i class="ti ti-history me-2 text-primary"></i>Riwayat Perubahan</h6>
        </div>
        <div class="card-body px-4 pb-4 pt-3">
          @forelse($jadwal->perubahanLog->sortByDesc('changed_at')->take(10) as $log)
            <div class="d-flex gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color:#f1f5f9;">
              @php
                $logColor = match($log->jenis_perubahan) {
                  'Hari', 'Jam' => '#6366f1',
                  'Ruangan'    => '#f59e0b',
                  'Dosen'      => '#10b981',
                  'Pembatalan' => '#ef4444',
                  default      => '#64748b',
                };
                $logIcon = match($log->jenis_perubahan) {
                  'Hari'       => 'ti-calendar',
                  'Jam'        => 'ti-clock',
                  'Ruangan'    => 'ti-map-pin',
                  'Dosen'      => 'ti-user',
                  'Pembatalan' => 'ti-x',
                  default      => 'ti-edit',
                };
              @endphp
              <div class="d-flex align-items-center justify-content-center rounded-2 flex-shrink-0" style="width:32px;height:32px;background:{{ $logColor }}18;color:{{ $logColor }};">
                <i class="ti {{ $logIcon }} small"></i>
              </div>
              <div class="flex-fill">
                <div class="d-flex align-items-center justify-content-between">
                  <span class="fw-semibold small" style="color:#1e293b;">{{ $log->jenis_perubahan }}</span>
                  <span class="small text-muted">{{ $log->changed_at?->diffForHumans() ?? '-' }}</span>
                </div>
                <div class="small text-muted">
                  <span class="text-danger">{{ Str::limit($log->nilai_lama, 30) }}</span>
                  <i class="ti ti-arrow-right mx-1"></i>
                  <span class="text-success">{{ Str::limit($log->nilai_baru, 30) }}</span>
                </div>
                <div class="small text-muted">{{ Str::limit($log->alasan, 60) }}</div>
                <div class="small text-muted">oleh {{ $log->changedBy?->name ?? 'System' }}</div>
              </div>
            </div>
          @empty
            <div class="text-center py-4 text-muted">
              <i class="ti ti-history" style="font-size:2.5rem;opacity:0.35;"></i>
              <div class="small mt-1">Belum ada riwayat perubahan</div>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</main>

{{-- ============================================================ --}}
{{-- MODAL EDIT JADWAL                                            --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalEditJadwal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0" style="background:linear-gradient(135deg,#0f1f3d,#1a3a6e);border-radius:16px 16px 0 0;">
        <h5 class="modal-title text-white fw-bold"><i class="ti ti-edit me-2"></i>Edit Jadwal — {{ $jadwal->kode_kelas }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('jadwal-kuliah.update', $jadwal->id_jadwal) }}" method="POST">
        @csrf @method('PUT')
        <div class="modal-body p-4">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold small text-muted">Kode Kelas <span class="text-danger">*</span></label>
              <input type="text" name="kode_kelas" class="form-control" value="{{ $jadwal->kode_kelas }}" required style="text-transform:uppercase;">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small text-muted">Nama Kelas</label>
              <input type="text" name="nama_kelas" class="form-control" value="{{ $jadwal->nama_kelas }}">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small text-muted">Jenis Kelas <span class="text-danger">*</span></label>
              <select name="jenis_kelas" class="form-select" required>
                @foreach(['Reguler','Karyawan','Internasional','Paralel','Online','Hybrid'] as $jk)
                  <option value="{{ $jk }}" {{ $jadwal->jenis_kelas === $jk ? 'selected' : '' }}>{{ $jk }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold small text-muted">Dosen Koordinator</label>
              <select name="id_dosen_koordinator" class="form-select">
                <option value="">— Pilih Dosen —</option>
                @foreach($dosenList as $dosen)
                  <option value="{{ $dosen->id_dosen }}" {{ $jadwal->id_dosen_koordinator === $dosen->id_dosen ? 'selected' : '' }}>
                    {{ $dosen->nama_lengkap }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold small text-muted">Kapasitas Kelas <span class="text-danger">*</span></label>
              <input type="number" name="kapasitas_kelas" class="form-control" value="{{ $jadwal->kapasitas_kelas }}" min="1" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small text-muted">Hari</label>
              <select name="hari" class="form-select">
                <option value="">— Pilih Hari —</option>
                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)
                  <option value="{{ $hari }}" {{ $jadwal->hari === $hari ? 'selected' : '' }}>{{ $hari }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small text-muted">Jam Mulai</label>
              <input type="time" name="jam_mulai" class="form-control" value="{{ $jadwal->jam_mulai ? substr($jadwal->jam_mulai, 0, 5) : '' }}">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small text-muted">Jam Selesai</label>
              <input type="time" name="jam_selesai" class="form-control" value="{{ $jadwal->jam_selesai ? substr($jadwal->jam_selesai, 0, 5) : '' }}">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold small text-muted">Ruangan</label>
              <select name="id_ruangan" class="form-select">
                <option value="">— Pilih Ruangan —</option>
                @foreach($ruanganList as $ruangan)
                  <option value="{{ $ruangan->id_ruangan }}" {{ $jadwal->id_ruangan === $ruangan->id_ruangan ? 'selected' : '' }}>
                    {{ $ruangan->kode_ruangan }} — {{ $ruangan->nama_ruangan }} (Kap. {{ $ruangan->kapasitas }})
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold small text-muted">Catatan</label>
              <textarea name="catatan" class="form-control" rows="2">{{ $jadwal->catatan }}</textarea>
            </div>
            @if(in_array($jadwal->status_jadwal, ['Dipublikasikan','Direvisi']))
              <div class="col-12">
                <label class="form-label fw-semibold small text-muted">Alasan Perubahan <span class="text-danger">*</span></label>
                <textarea name="alasan_perubahan" class="form-control" rows="2" required placeholder="Jelaskan alasan perubahan jadwal yang sudah dipublikasikan..."></textarea>
                <div class="form-text small text-warning"><i class="ti ti-alert-triangle me-1"></i>Jadwal sudah dipublikasikan. Perubahan akan dicatat dan status berubah menjadi Direvisi.</div>
              </div>
            @endif
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Batal</button>
          <button type="submit" class="btn btn-primary" style="border-radius:10px;"><i class="ti ti-device-floppy me-1"></i>Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL TAMBAH DOSEN                                           --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalTambahDosen" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0" style="background:linear-gradient(135deg,#0f1f3d,#1a3a6e);border-radius:16px 16px 0 0;">
        <h5 class="modal-title text-white fw-bold"><i class="ti ti-user-plus me-2"></i>Tambah Dosen ke Tim Pengampu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-semibold small text-muted">Dosen <span class="text-danger">*</span></label>
            <select id="selectDosenTambah" class="form-select">
              <option value="">— Pilih Dosen —</option>
              @foreach($dosenList as $dosen)
                <option value="{{ $dosen->id_dosen }}">{{ $dosen->nama_lengkap }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold small text-muted">Peran <span class="text-danger">*</span></label>
            <select id="selectPeranDosen" class="form-select">
              <option value="Pengampu">Pengampu</option>
              <option value="Koordinator">Koordinator</option>
              <option value="Asisten">Asisten</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold small text-muted">Pertemuan</label>
            <input type="text" id="inputPertemuanKe" class="form-control" placeholder="Semua / 1-8 / 9-16" value="Semua">
          </div>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Batal</button>
        <button type="button" class="btn btn-primary" id="btnSimpanDosen" style="border-radius:10px;"><i class="ti ti-user-plus me-1"></i>Tambahkan</button>
      </div>
    </div>
  </div>
</div>

<script>
(function() {
  const jadwalId = '{{ $jadwal->id_jadwal }}';

  // Tambah Dosen ke tim
  document.getElementById('btnSimpanDosen')?.addEventListener('click', function() {
    const dosenId = document.getElementById('selectDosenTambah').value;
    const peran = document.getElementById('selectPeranDosen').value;
    const pertemuan = document.getElementById('inputPertemuanKe').value;

    if (!dosenId) { alert('Pilih dosen terlebih dahulu.'); return; }

    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

    fetch(`/references/jadwal-kuliah/${jadwalId}/dosen`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
      body: JSON.stringify({ id_dosen: dosenId, peran: peran, pertemuan_ke: pertemuan })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        bootstrap.Modal.getInstance(document.getElementById('modalTambahDosen'))?.hide();
        window.location.reload();
      } else {
        alert(data.message || 'Gagal menambahkan dosen.');
      }
    })
    .catch(() => alert('Terjadi kesalahan jaringan.'))
    .finally(() => {
      this.disabled = false;
      this.innerHTML = '<i class="ti ti-user-plus me-1"></i>Tambahkan';
    });
  });

  // Hapus Dosen dari tim
  document.querySelectorAll('.hapus-dosen-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      if (!(await confirmAsync('Hapus dosen ini dari tim pengampu?')) return;
      const jdId = this.dataset.jd;
      fetch(`/references/jadwal-kuliah/${jadwalId}/dosen/${jdId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content }
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) window.location.reload();
        else alert(data.message || 'Gagal menghapus dosen.');
      });
    });
  });
})();
</script>
@endsection
