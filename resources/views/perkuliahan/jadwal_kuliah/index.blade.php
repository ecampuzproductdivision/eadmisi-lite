@extends('layouts.app')

@section('content')
<style>
  .table-premium th {
    vertical-align: middle;
    font-size: 0.78rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    font-weight: 700;
    color: #475569 !important;
    padding: 14px 16px !important;
    background-color: #f8fafc !important;
  }
  .table-premium td {
    vertical-align: middle !important;
    padding: 14px 16px !important;
    border-bottom: 1px solid #f1f5f9 !important;
  }
  .table-premium tbody tr {
    transition: all 0.2s ease;
  }
  .table-premium tbody tr:hover {
    background-color: #f8fafc !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
  }
  .badge-status-premium {
    font-weight: 600;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    border: 1px solid transparent;
  }
  .avatar-dosen-premium {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: #fff;
    font-weight: 600;
    font-size: 0.82rem;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.25);
  }
  .btn-action-premium {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
  }
  .btn-action-premium:hover {
    transform: scale(1.08) translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
  }
</style>

<main class="p-4">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:12px;">
      <div class="d-flex align-items-center"><i class="ti ti-circle-check fs-4 me-2"></i><span>{{ session('success') }}</span></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:12px;">
      <div class="d-flex align-items-center"><i class="ti ti-alert-triangle fs-4 me-2"></i>
        <div>
          @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- ============================================================ --}}
  {{-- HEADER BANNER                                                --}}
  {{-- ============================================================ --}}
  <div class="card border-0 shadow-sm mb-4" style="background:linear-gradient(135deg,#0f1f3d 0%,#1a3a6e 50%,#0d3b6e 100%);border-radius:20px;overflow:hidden;position:relative;">
    <div style="position:absolute;top:0;right:0;width:320px;height:220px;background:rgba(56,189,248,0.07);border-radius:50%;transform:translate(30%,-30%);"></div>
    <div style="position:absolute;bottom:-30px;left:15%;width:200px;height:200px;background:rgba(99,102,241,0.06);border-radius:50%;"></div>
    <div class="card-body p-4 p-md-5 text-white position-relative">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="d-flex align-items-center justify-content-center rounded-3" style="width:60px;height:60px;background:rgba(56,189,248,0.2);backdrop-filter:blur(10px);">
              <i class="ti ti-calendar-time" style="font-size:1.8rem;color:#38bdf8;"></i>
            </div>
            <div>
              <span class="badge mb-1" style="background:rgba(56,189,248,0.2);color:#7dd3fc;border:1px solid rgba(56,189,248,0.3);border-radius:20px;font-size:0.72rem;">Perkuliahan</span>
              <h2 class="fw-bold mb-0" style="font-size:1.7rem;color:#fff;">Jadwal Kuliah</h2>
            </div>
          </div>
          <p class="mb-0" style="color:rgba(255,255,255,0.6);max-width:520px;line-height:1.7;">
            Kelola jadwal perkuliahan: dosen, ruangan, hari & jam. Deteksi konflik otomatis dan publikasikan jadwal ke mahasiswa.
          </p>
        </div>
        <div class="col-lg-5 mt-4 mt-lg-0">
          <div class="row g-3">
            <div class="col-6">
              <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,0.06);backdrop-filter:blur(10px);">
                <div class="fw-bold fs-2 text-white">{{ $totalJadwal }}</div>
                <div class="small" style="color:rgba(255,255,255,0.55);">Total Kelas</div>
              </div>
            </div>
            <div class="col-6">
              <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,0.06);backdrop-filter:blur(10px);">
                <div class="fw-bold fs-2" style="color:#4ade80;">{{ $totalDipublikasi }}</div>
                <div class="small" style="color:rgba(255,255,255,0.55);">Dipublikasikan</div>
              </div>
            </div>
            <div class="col-6">
              <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,0.06);backdrop-filter:blur(10px);">
                <div class="fw-bold fs-2" style="color:#fbbf24;">{{ $totalDraft }}</div>
                <div class="small" style="color:rgba(255,255,255,0.55);">Draft</div>
              </div>
            </div>
            <div class="col-6">
              <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,0.06);backdrop-filter:blur(10px);">
                <div class="fw-bold fs-2 {{ $totalKonflik > 0 ? 'text-danger' : 'text-white' }}">{{ $totalKonflik }}</div>
                <div class="small" style="color:rgba(255,255,255,0.55);">⚠ Konflik</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ============================================================ --}}
  {{-- FILTER & ACTION BAR                                          --}}
  {{-- ============================================================ --}}
  <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
    <div class="card-body p-3 p-md-4">
      <form action="{{ route('jadwal-kuliah.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3 col-12">
          <label class="form-label small text-muted fw-bold">Cari Kelas / MK / Dosen</label>
          <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
            <input type="text" name="search" class="form-control border-start-0" placeholder="Kode kelas, nama MK..." value="{{ request('search') }}">
          </div>
        </div>
        <div class="col-md-3 col-12">
          <label class="form-label small text-muted fw-bold">Tahun Akademik</label>
          <select name="tahun_akademik_id" class="form-select" onchange="this.form.submit()">
            @foreach($taList as $ta)
              <option value="{{ $ta->id_tahun_akademik }}" {{ $selectedTaId == $ta->id_tahun_akademik ? 'selected' : '' }}>
                {{ $ta->nama_ta }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 col-6">
          <label class="form-label small text-muted fw-bold">Hari</label>
          <select name="hari" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Hari</option>
            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)
              <option value="{{ $hari }}" {{ request('hari') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 col-6">
          <label class="form-label small text-muted fw-bold">Status</label>
          <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach(['Draft','Dikonfirmasi','Dipublikasikan','Direvisi','Selesai'] as $status)
              <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 col-12 d-flex gap-2">
          <button type="submit" class="btn btn-primary flex-fill" style="border-radius:10px;">
            <i class="ti ti-search me-1"></i>Filter
          </button>
          @if(request()->hasAny(['search','hari','status','jenis_kelas']))
            <a href="{{ route('jadwal-kuliah.index', ['tahun_akademik_id' => $selectedTaId]) }}" class="btn btn-outline-secondary" style="border-radius:10px;">
              <i class="ti ti-x"></i>
            </a>
          @endif
        </div>
      </form>
    </div>
  </div>

  {{-- ============================================================ --}}
  {{-- ACTION BUTTONS                                               --}}
  {{-- ============================================================ --}}
  <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal" style="border-radius:10px;">
      <i class="ti ti-plus me-1"></i>Tambah Jadwal
    </button>
    <button type="button" class="btn btn-outline-warning shadow-sm" id="btnDeteksiKonflik" style="border-radius:10px;" data-ta="{{ $selectedTaId }}">
      <i class="ti ti-alert-triangle me-1"></i>Deteksi Konflik
    </button>
    <button type="button" class="btn btn-outline-info shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKalender" style="border-radius:10px;">
      <i class="ti ti-calendar-week me-1"></i>Kalender Mingguan
    </button>
    <span class="text-muted small ms-auto">
      {{ $jadwalList->total() }} kelas ditemukan
    </span>
  </div>

  {{-- Konflik Alert Banner --}}
  @if(count($konflikJadwalIds) > 0)
    <div class="alert border-0 mb-3 d-flex align-items-center gap-3" style="background:rgba(239,68,68,0.08);border-left:4px solid #ef4444 !important;border-radius:12px;">
      <i class="ti ti-alert-triangle text-danger fs-3"></i>
      <div>
        <div class="fw-bold text-danger">Terdeteksi {{ count($konflikJadwalIds) / 2 }} konflik jadwal</div>
        <div class="small text-muted">Jadwal yang berkonflik ditandai dengan ikon merah. Selesaikan konflik sebelum mempublikasikan.</div>
      </div>
    </div>
  @endif

  {{-- ============================================================ --}}
  {{-- DATA TABLE                                                   --}}
  {{-- ============================================================ --}}
  <div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden;">
    <div class="card-body p-0">
      <div class="no-sticky-global" style="overflow-x:auto;">
        <table class="table table-hover table-premium no-sticky-global mb-0" style="min-width:1000px;">
          <thead style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);">
            <tr>
              <th class="px-4 py-3 text-muted small fw-bold border-0" style="width:50px;">#</th>
              <th class="px-3 py-3 text-muted small fw-bold border-0">Kode Kelas</th>
              <th class="px-3 py-3 text-muted small fw-bold border-0">Mata Kuliah</th>
              <th class="px-3 py-3 text-muted small fw-bold border-0">Dosen Koordinator</th>
              <th class="px-3 py-3 text-muted small fw-bold border-0">Jadwal</th>
              <th class="px-3 py-3 text-muted small fw-bold border-0">Ruangan</th>
              <th class="px-3 py-3 text-muted small fw-bold border-0 text-center">Kapasitas</th>
              <th class="px-3 py-3 text-muted small fw-bold border-0">Status</th>
              <th class="px-3 py-3 text-muted small fw-bold border-0 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($jadwalList as $index => $jadwal)
              @php
                $hasKonflik = in_array($jadwal->id_jadwal, $konflikJadwalIds);
                $mk = $jadwal->kurikulumMataKuliah?->mataKuliah;
                $prodi = $jadwal->kurikulumMataKuliah?->kurikulum?->programStudi;
              @endphp
              <tr style="{{ $hasKonflik ? 'background:rgba(239,68,68,0.04);' : '' }}">
                <td class="px-4 py-3 border-0">
                  <span class="text-muted small">{{ $jadwalList->firstItem() + $index }}</span>
                </td>
                <td class="px-3 py-3 border-0">
                  <div class="d-flex align-items-center gap-2">
                    @if($hasKonflik)
                      <i class="ti ti-alert-circle text-danger" title="Ada konflik jadwal!"></i>
                    @endif
                    <div>
                      <div class="fw-semibold" style="color:#1e293b;">{{ $jadwal->kode_kelas }}</div>
                      @if($jadwal->nama_kelas)
                        <div class="small text-muted">{{ $jadwal->nama_kelas }}</div>
                      @endif
                      <span class="badge badge-sm mt-1" style="font-size:0.65rem;background:{{ match($jadwal->jenis_kelas) {
                        'Reguler' => 'rgba(99,102,241,0.1)', 'Karyawan' => 'rgba(234,179,8,0.1)',
                        'Online' => 'rgba(6,182,212,0.1)', 'Hybrid' => 'rgba(168,85,247,0.1)',
                        default => 'rgba(100,116,139,0.1)' } }};color:{{ match($jadwal->jenis_kelas) {
                        'Reguler' => '#6366f1', 'Karyawan' => '#ca8a04',
                        'Online' => '#0891b2', 'Hybrid' => '#9333ea',
                        default => '#64748b' } }};">
                        {{ $jadwal->jenis_kelas }}
                      </span>
                    </div>
                  </div>
                </td>
                <td class="px-3 py-3 border-0">
                  @if($mk)
                    <div class="fw-medium" style="color:#0f172a;font-size:0.87rem;">{{ $mk->mk_nama }}</div>
                    <div class="small" style="color:#64748b;">{{ $mk->mk_kode }} · {{ $mk->sks ?? $jadwal->kurikulumMataKuliah?->sks_override ?? '-' }} SKS</div>
                    @if($prodi)
                      <div class="small text-muted">{{ $prodi->prodiNamaResmi ?? $prodi->prodiNama }}</div>
                    @endif
                  @else
                    <span class="text-muted small fst-italic">Mata kuliah tidak ditemukan</span>
                  @endif
                </td>
                <td class="px-3 py-3 border-0">
                  @if($jadwal->dosenKoordinator)
                    <div class="d-flex align-items-center gap-2">
                      <div class="avatar-dosen-premium flex-shrink-0">
                        {{ strtoupper(substr($jadwal->dosenKoordinator->nama_lengkap ?? 'D', 0, 1)) }}
                      </div>
                      <div>
                        <div style="font-size:0.85rem;color:#1e293b;font-weight:600;">{{ Str::limit($jadwal->dosenKoordinator->nama_lengkap, 25) }}</div>
                        @if($jadwal->timDosen->count() > 1)
                          <div class="small text-muted" style="font-size:0.75rem;">+{{ $jadwal->timDosen->count() - 1 }} dosen pengampu</div>
                        @endif
                      </div>
                    </div>
                  @else
                    <span class="badge" style="background:rgba(239,68,68,0.08);color:#ef4444;font-size:0.72rem;border-radius:6px;padding:4px 8px;">
                      <i class="ti ti-user-off me-1"></i>Belum ditentukan
                    </span>
                  @endif
                </td>
                <td class="px-3 py-3 border-0">
                  @if($jadwal->hari && $jadwal->jam_mulai)
                    <div class="d-flex align-items-center gap-1">
                      <i class="ti ti-calendar small text-primary" style="font-size: 0.9rem;"></i>
                      <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">{{ $jadwal->hari }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-1 mt-1">
                      <i class="ti ti-clock small text-muted" style="font-size: 0.85rem;"></i>
                      <span class="small text-muted" style="font-size: 0.78rem;">{{ substr($jadwal->jam_mulai, 0, 5) }} – {{ substr($jadwal->jam_selesai, 0, 5) }}</span>
                    </div>
                    <div class="small text-muted mt-1" style="font-size: 0.72rem; padding-left: 14px;">{{ $jadwal->durasi_menit }} menit</div>
                  @else
                    <span class="text-muted small fst-italic">Belum dijadwalkan</span>
                  @endif
                </td>
                <td class="px-3 py-3 border-0">
                  @if($jadwal->jenis_kelas === 'Online')
                    <span class="badge" style="background:rgba(6,182,212,0.08);color:#0891b2;border-radius:6px;font-size:0.75rem;padding:4px 8px;">
                      <i class="ti ti-wifi me-1"></i>Online
                    </span>
                  @elseif($jadwal->ruangan)
                    <div style="font-size:0.85rem;font-weight:600;color:#1e293b;">{{ $jadwal->ruangan->kode_ruangan }}</div>
                    <div class="small text-muted" style="font-size:0.75rem;">{{ Str::limit($jadwal->ruangan->nama_ruangan, 20) }}</div>
                    <div class="small text-muted" style="font-size:0.72rem;">Kap. {{ $jadwal->ruangan->kapasitas }} mhs</div>
                  @else
                    <span class="badge" style="background:rgba(239,68,68,0.08);color:#ef4444;font-size:0.72rem;border-radius:6px;padding:4px 8px;">
                      <i class="ti ti-building-off me-1"></i>Belum dipilih
                    </span>
                  @endif
                </td>
                <td class="px-3 py-3 border-0 text-center">
                  <div class="fw-bold" style="color:#1e293b; font-size: 0.85rem;">{{ $jadwal->jumlah_terdaftar }}<span class="text-muted fw-normal" style="font-size: 0.8rem;">/{{ $jadwal->kapasitas_kelas }}</span></div>
                  @php $pctFull = $jadwal->kapasitas_kelas > 0 ? ($jadwal->jumlah_terdaftar / $jadwal->kapasitas_kelas) * 100 : 0; @endphp
                  <div class="progress mt-1.5" style="height:5px;border-radius:4px;background:#e2e8f0;">
                    <div class="progress-bar {{ $pctFull > 90 ? 'bg-danger' : ($pctFull > 70 ? 'bg-warning' : 'bg-success') }}"
                         style="width:{{ min(100, $pctFull) }}%"></div>
                  </div>
                </td>
                <td class="px-3 py-3 border-0">
                  @php
                    $badgeStyle = match($jadwal->status_jadwal) {
                      'Draft'          => 'background:rgba(100,116,139,0.08);color:#475569;border-color:rgba(100,116,139,0.15);',
                      'Dikonfirmasi'   => 'background:rgba(59,130,246,0.08);color:#1d4ed8;border-color:rgba(59,130,246,0.15);',
                      'Dipublikasikan' => 'background:rgba(16,185,129,0.08);color:#047857;border-color:rgba(16,185,129,0.15);',
                      'Direvisi'       => 'background:rgba(245,158,11,0.08);color:#b45309;border-color:rgba(245,158,11,0.15);',
                      'Selesai'        => 'background:rgba(168,85,247,0.08);color:#7e22ce;border-color:rgba(168,85,247,0.15);',
                      default          => 'background:rgba(100,116,139,0.08);color:#475569;',
                    };
                    $badgeIcon = match($jadwal->status_jadwal) {
                      'Draft'          => 'ti-pencil',
                      'Dikonfirmasi'   => 'ti-check',
                      'Dipublikasikan' => 'ti-circle-check',
                      'Direvisi'       => 'ti-edit',
                      'Selesai'        => 'ti-archive',
                      default          => 'ti-minus',
                    };
                  @endphp
                  <span class="badge-status-premium" style="{{ $badgeStyle }}">
                    <i class="ti {{ $badgeIcon }} me-0.5"></i>{{ $jadwal->status_jadwal }}
                  </span>
                  @if(!$jadwal->is_aktif)
                    <div class="mt-1"><span class="badge" style="background:rgba(239,68,68,0.08);color:#ef4444;font-size:0.68rem;padding:2px 6px;">Nonaktif</span></div>
                  @endif
                </td>
                <td class="px-3 py-3 border-0 text-center">
                  <div class="d-flex gap-1.5 justify-content-center align-items-center">
                    <a href="{{ route('jadwal-kuliah.show', $jadwal->id_jadwal) }}" class="btn-action-premium" style="background:rgba(99,102,241,0.08);color:#4f46e5;" title="Detail / Workspace">
                      <i class="ti ti-eye" style="font-size: 1.05rem;"></i>
                    </a>
                    @if($jadwal->status_jadwal === 'Draft')
                      <form action="{{ route('jadwal-kuliah.publikasikan', $jadwal->id_jadwal) }}" method="POST" class="d-inline m-0">
                        @csrf
                        <button type="submit" class="btn-action-premium" style="background:rgba(16,185,129,0.08);color:#047857;" title="Publikasikan" onclick="return confirm('Publikasikan jadwal {{ $jadwal->kode_kelas }}?')">
                          <i class="ti ti-send" style="font-size: 1.05rem;"></i>
                        </button>
                      </form>
                    @endif
                    <form action="{{ route('jadwal-kuliah.toggle-aktif', $jadwal->id_jadwal) }}" method="POST" class="d-inline m-0">
                      @csrf
                      <button type="submit" class="btn-action-premium" style="background:{{ $jadwal->is_aktif ? 'rgba(245,158,11,0.08)' : 'rgba(16,185,129,0.08)' }};color:{{ $jadwal->is_aktif ? '#b45309' : '#047857' }};" title="{{ $jadwal->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                        <i class="ti {{ $jadwal->is_aktif ? 'ti-toggle-right' : 'ti-toggle-left' }}" style="font-size: 1.1rem;"></i>
                      </button>
                    </form>
                    @if($jadwal->jumlah_terdaftar === 0 && !$jadwal->isSelesai())
                      <form action="{{ route('jadwal-kuliah.destroy', $jadwal->id_jadwal) }}" method="POST" class="d-inline m-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-action-premium" style="background:rgba(239,68,68,0.08);color:#b91c1c;" title="Hapus" onclick="return confirm('Hapus jadwal {{ $jadwal->kode_kelas }}? Tindakan ini tidak dapat dibatalkan.')">
                          <i class="ti ti-trash" style="font-size: 1.05rem;"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center py-5">
                  <div class="d-flex flex-column align-items-center gap-2">
                    <i class="ti ti-calendar-off" style="font-size:3rem;color:#cbd5e1;"></i>
                    <div class="fw-semibold text-muted">Belum ada jadwal kuliah</div>
                    <div class="small text-muted">Klik "Tambah Jadwal" untuk membuat jadwal baru.</div>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if($jadwalList->hasPages())
      <div class="card-footer bg-transparent border-top-0 py-3 px-4">
        {{ $jadwalList->links() }}
      </div>
    @endif
  </div>
</main>

{{-- ============================================================ --}}
{{-- MODAL TAMBAH JADWAL                                          --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalTambahJadwal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#0f1f3d,#1a3a6e);border-radius:16px 16px 0 0;">
        <div class="d-flex align-items-center gap-2">
          <i class="ti ti-calendar-plus" style="font-size:1.4rem;color:#38bdf8;"></i>
          <h5 class="modal-title text-white fw-bold mb-0">Tambah Jadwal Kuliah</h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('jadwal-kuliah.store') }}" method="POST">
        @csrf
        <input type="hidden" name="id_tahun_akademik" value="{{ $selectedTaId }}">
        <div class="modal-body p-4">
          <div class="row g-3">
            {{-- Mata Kuliah --}}
            <div class="col-12">
              <label class="form-label fw-semibold small text-muted">Mata Kuliah <span class="text-danger">*</span></label>
              <select name="id_kmk" class="form-select" id="selectKmk" required>
                <option value="">— Pilih Mata Kuliah —</option>
                @foreach($kmkList as $kmk)
                  <option value="{{ $kmk->id }}" data-sks="{{ $kmk->sks_override ?? $kmk->mataKuliah?->sks ?? 0 }}" data-mk="{{ $kmk->mataKuliah?->mk_kode ?? '' }}">
                    {{ $kmk->mataKuliah?->mk_kode ?? '' }} — {{ $kmk->mataKuliah?->mk_nama ?? 'MK #'.$kmk->id }}
                    ({{ $kmk->kurikulum?->programStudi?->prodiNama ?? 'N/A' }}, Sem {{ $kmk->semester_anjuran }})
                  </option>
                @endforeach
              </select>
            </div>

            {{-- Kode Kelas & Nama Kelas --}}
            <div class="col-md-4">
              <label class="form-label fw-semibold small text-muted">Kode Kelas <span class="text-danger">*</span></label>
              <input type="text" name="kode_kelas" class="form-control" placeholder="Contoh: IF301-A" required maxlength="20" style="text-transform:uppercase;">
              <div class="form-text small">Harus unik per MK & Tahun Akademik</div>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small text-muted">Nama Kelas</label>
              <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: Kelas Reguler A" maxlength="50">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small text-muted">Jenis Kelas <span class="text-danger">*</span></label>
              <select name="jenis_kelas" class="form-select" id="selectJenisKelas" required>
                @foreach(['Reguler','Karyawan','Internasional','Paralel','Online','Hybrid'] as $jk)
                  <option value="{{ $jk }}">{{ $jk }}</option>
                @endforeach
              </select>
            </div>

            {{-- Dosen Koordinator --}}
            <div class="col-md-6">
              <label class="form-label fw-semibold small text-muted">Dosen Koordinator</label>
              <select name="id_dosen_koordinator" class="form-select">
                <option value="">— Pilih Dosen —</option>
                @foreach($dosenList as $dosen)
                  <option value="{{ $dosen->id_dosen }}">{{ $dosen->nama_lengkap }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold small text-muted">Kapasitas Kelas <span class="text-danger">*</span></label>
              <input type="number" name="kapasitas_kelas" class="form-control" value="40" min="1" max="500" required>
            </div>

            {{-- Hari & Jam --}}
            <div class="col-12"><hr class="my-1"><small class="text-muted fw-semibold">Waktu Perkuliahan</small></div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small text-muted">Hari</label>
              <select name="hari" class="form-select">
                <option value="">— Pilih Hari —</option>
                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)
                  <option value="{{ $hari }}">{{ $hari }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small text-muted">Jam Mulai</label>
              <input type="time" name="jam_mulai" class="form-control" id="inputJamMulai">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small text-muted">Jam Selesai</label>
              <input type="time" name="jam_selesai" class="form-control" id="inputJamSelesai">
              <div class="form-text small" id="durasiHint"></div>
            </div>

            {{-- Ruangan --}}
            <div class="col-12" id="ruanganSection">
              <label class="form-label fw-semibold small text-muted">Ruangan</label>
              <select name="id_ruangan" class="form-select">
                <option value="">— Pilih Ruangan —</option>
                @foreach($ruanganList as $ruangan)
                  <option value="{{ $ruangan->id_ruangan }}">
                    {{ $ruangan->kode_ruangan }} — {{ $ruangan->nama_ruangan }} (Kap. {{ $ruangan->kapasitas }})
                  </option>
                @endforeach
              </select>
              <div class="form-text small">Untuk kelas Online, ruangan tidak diperlukan.</div>
            </div>

            {{-- Catatan --}}
            <div class="col-12">
              <label class="form-label fw-semibold small text-muted">Catatan</label>
              <textarea name="catatan" class="form-control" rows="2" placeholder="Keterangan khusus kelas ini..."></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Batal</button>
          <button type="submit" class="btn btn-primary" style="border-radius:10px;"><i class="ti ti-plus me-1"></i>Buat Jadwal</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL KALENDER MINGGUAN                                       --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalKalender" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0" style="background:linear-gradient(135deg,#0f1f3d,#1a3a6e);border-radius:16px 16px 0 0;">
        <div class="d-flex align-items-center gap-2">
          <i class="ti ti-calendar-week" style="font-size:1.4rem;color:#38bdf8;"></i>
          <h5 class="modal-title text-white fw-bold mb-0">Kalender Mingguan</h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div id="kalenderContainer" style="overflow-x:auto;">
          <div class="text-center py-5 text-muted" id="kalenderLoading">
            <div class="spinner-border spinner-border-sm me-2" role="status"></div>Memuat kalender...
          </div>
          <table class="table table-bordered mb-0" id="kalenderTable" style="display:none;min-width:900px;font-size:0.82rem;">
            <thead style="background:#f8fafc;">
              <tr>
                <th class="text-center" style="width:80px;padding:10px;">Jam</th>
                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                  <th class="text-center" style="padding:10px;">{{ $h }}</th>
                @endforeach
              </tr>
            </thead>
            <tbody id="kalenderBody"></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Tutup</button>
      </div>
    </div>
  </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL HASIL DETEKSI KONFLIK                                  --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalKonflik" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0 shadow" style="border-radius:16px;">
      <div class="modal-header border-0" style="background:linear-gradient(135deg,#7f1d1d,#991b1b);border-radius:16px 16px 0 0;">
        <h5 class="modal-title text-white fw-bold"><i class="ti ti-alert-triangle me-2"></i>Hasil Deteksi Konflik</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4" id="konflikContent">
        <div class="text-center text-muted py-3"><div class="spinner-border spinner-border-sm me-2"></div>Mendeteksi konflik...</div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:10px;">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
(function() {
  const selectedTaId = '{{ $selectedTaId }}';

  // Auto-hide jam selesai section for Online class
  const selectJenisKelas = document.getElementById('selectJenisKelas');
  const ruanganSection = document.getElementById('ruanganSection');
  if (selectJenisKelas) {
    selectJenisKelas.addEventListener('change', function() {
      ruanganSection.style.opacity = this.value === 'Online' ? '0.4' : '1';
    });
  }

  // Auto-calculate durasi hint
  const jamMulai = document.getElementById('inputJamMulai');
  const jamSelesai = document.getElementById('inputJamSelesai');
  const durasiHint = document.getElementById('durasiHint');
  function updateDurasi() {
    if (!jamMulai?.value || !jamSelesai?.value) { durasiHint.textContent = ''; return; }
    const [hm, mm] = jamMulai.value.split(':').map(Number);
    const [hs, ms] = jamSelesai.value.split(':').map(Number);
    const total = (hs * 60 + ms) - (hm * 60 + mm);
    if (total > 0) {
      const sks = Math.round(total / 50);
      durasiHint.textContent = `${total} menit ≈ ${sks} SKS`;
      durasiHint.style.color = total < 40 ? '#ef4444' : '#64748b';
    } else {
      durasiHint.textContent = '⚠ Jam selesai harus setelah jam mulai';
      durasiHint.style.color = '#ef4444';
    }
  }
  jamMulai?.addEventListener('change', updateDurasi);
  jamSelesai?.addEventListener('change', updateDurasi);

  // Deteksi Konflik
  const btnDeteksi = document.getElementById('btnDeteksiKonflik');
  if (btnDeteksi) {
    btnDeteksi.addEventListener('click', function() {
      const modal = new bootstrap.Modal(document.getElementById('modalKonflik'));
      modal.show();
      fetch(`{{ route('jadwal-kuliah.deteksi-konflik') }}?tahun_akademik_id=${selectedTaId}`)
        .then(r => r.json())
        .then(data => {
          let html = '';
          if (data.total_error === 0) {
            html = `<div class="text-center py-4"><i class="ti ti-circle-check text-success" style="font-size:3rem;"></i><div class="fw-bold text-success mt-2">Tidak ada konflik terdeteksi!</div><div class="text-muted small">Semua jadwal bebas dari konflik dosen dan ruangan.</div></div>`;
          } else {
            html = `<div class="alert alert-danger border-0 mb-3"><strong>${data.total_error} konflik ditemukan</strong></div>`;
            if (data.konflik_dosen.length > 0) {
              html += `<h6 class="fw-bold text-danger mb-2"><i class="ti ti-user-x me-1"></i>Konflik Dosen (${data.konflik_dosen.length})</h6>`;
              html += `<div class="table-responsive"><table class="table table-sm mb-3"><thead><tr><th>Kelas 1</th><th>Kelas 2</th><th>Hari</th><th>Jam</th></tr></thead><tbody>`;
              data.konflik_dosen.forEach(k => { html += `<tr><td><span class="badge bg-danger">${k.kelas1}</span></td><td><span class="badge bg-danger">${k.kelas2}</span></td><td>${k.hari}</td><td>${k.jam_mulai}–${k.jam_selesai}</td></tr>`; });
              html += `</tbody></table></div>`;
            }
            if (data.konflik_ruangan.length > 0) {
              html += `<h6 class="fw-bold text-warning mb-2"><i class="ti ti-building-off me-1"></i>Konflik Ruangan (${data.konflik_ruangan.length})</h6>`;
              html += `<div class="table-responsive"><table class="table table-sm mb-3"><thead><tr><th>Kelas 1</th><th>Kelas 2</th><th>Hari</th><th>Jam</th></tr></thead><tbody>`;
              data.konflik_ruangan.forEach(k => { html += `<tr><td><span class="badge bg-warning text-dark">${k.kelas1}</span></td><td><span class="badge bg-warning text-dark">${k.kelas2}</span></td><td>${k.hari}</td><td>${k.jam_mulai}–${k.jam_selesai}</td></tr>`; });
              html += `</tbody></table></div>`;
            }
          }
          document.getElementById('konflikContent').innerHTML = html;
        })
        .catch(() => {
          document.getElementById('konflikContent').innerHTML = `<div class="text-danger text-center py-3">Gagal memuat data konflik.</div>`;
        });
    });
  }

  // Kalender Mingguan
  document.getElementById('modalKalender')?.addEventListener('show.bs.modal', function() {
    fetch(`{{ route('jadwal-kuliah.kalender-data') }}?tahun_akademik_id=${selectedTaId}`)
      .then(r => r.json())
      .then(data => {
        buildKalender(data.events || []);
      })
      .catch(() => {
        document.getElementById('kalenderLoading').textContent = 'Gagal memuat kalender.';
      });
  });

  function buildKalender(events) {
    const days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const slots = ['07:00','08:00','09:00','10:00','11:00','13:00','14:00','15:00','16:00','19:00','20:00','21:00'];
    const body = document.getElementById('kalenderBody');
    body.innerHTML = '';
    const colors = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#84cc16','#f97316'];
    let colorMap = {};
    let ci = 0;
    slots.forEach(slot => {
      const tr = document.createElement('tr');
      tr.innerHTML = `<td class="text-center fw-semibold text-muted" style="font-size:0.75rem;vertical-align:middle;background:#f8fafc;">${slot}</td>`;
      days.forEach(day => {
        const td = document.createElement('td');
        td.style.cssText = 'padding:4px;vertical-align:top;min-height:60px;';
        const matching = events.filter(e => e.hari === day && e.jam_mulai && e.jam_mulai.substring(0,5) === slot);
        matching.forEach(e => {
          if (!colorMap[e.mk_kode]) colorMap[e.mk_kode] = colors[ci++ % colors.length];
          const chip = document.createElement('div');
          chip.style.cssText = `background:${colorMap[e.mk_kode]};color:#fff;border-radius:6px;padding:4px 6px;margin-bottom:2px;font-size:0.72rem;cursor:pointer;`;
          chip.innerHTML = `<div class="fw-bold">${e.kode_kelas}</div><div style="opacity:0.85;">${e.mk_nama?.substring(0,18)}</div><div style="opacity:0.7;">${e.jam_mulai?.substring(0,5)}–${e.jam_selesai?.substring(0,5)}</div>`;
          chip.title = `${e.kode_kelas}: ${e.mk_nama}\n${e.dosen}\n${e.ruangan}\n${e.jam_mulai}–${e.jam_selesai}`;
          td.appendChild(chip);
        });
        tr.appendChild(td);
      });
      body.appendChild(tr);
    });
    document.getElementById('kalenderLoading').style.display = 'none';
    document.getElementById('kalenderTable').style.display = '';
  }
})();
</script>
@endsection
