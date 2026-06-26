@extends('layouts.app')

@section('content')
<main class="p-4">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
      <div class="d-flex align-items-center"><i class="ti ti-circle-check fs-4 me-2"></i><span>{{ session('success') }}</span></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
      <div class="d-flex align-items-center"><i class="ti ti-alert-triangle fs-4 me-2"></i><span>{{ session('error') }}</span></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- ============================================================ --}}
  {{-- HEADER BANNER                                                --}}
  {{-- ============================================================ --}}
  <div class="card border-1 shadow-sm mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #1a2e52 100%); border-radius: 20px; overflow: hidden; position: relative;">
    <div style="position:absolute;top:0;right:0;width:300px;height:200px;background:rgba(99,102,241,0.08);border-radius:50%;transform:translate(30%,-30%);"></div>
    <div style="position:absolute;bottom:0;left:20%;width:200px;height:150px;background:rgba(34,197,94,0.05);border-radius:50%;"></div>
    <div class="card-body p-4 p-md-5 text-white position-relative">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="d-flex align-items-center justify-content-center rounded-3" style="width:56px;height:56px;background:rgba(99,102,241,0.2);backdrop-filter:blur(10px);">
              <i class="ti ti-list-check fs-1 text-primary"></i>
            </div>
            <div>
              <span class="badge mb-1" style="background:rgba(99,102,241,0.2);color:#a5b4fc;border:1px solid rgba(99,102,241,0.3);border-radius:20px;font-size:0.72rem;">Rencana Pembelajaran (RPS)</span>
              <h2 class="fw-bold text-white mb-0" style="font-size:1.6rem;">Pertemuan &amp; Materi</h2>
            </div>
          </div>
          <p class="text-white-50 mb-0" style="max-width:520px;line-height:1.6;">
            Kelola rencana pertemuan, lampirkan bahan ajar, dan catat realisasi perkuliahan mingguan sesuai Sub-CPMK yang direncanakan.
          </p>
        </div>
        <div class="col-lg-5 mt-4 mt-lg-0">
          <div class="row g-3">
            <div class="col-6">
              <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);">
                <div class="fw-bold fs-2 text-white">{{ $rpsList->total() }}</div>
                <div class="small text-white-50">RPS Aktif</div>
              </div>
            </div>
            <div class="col-6">
              <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);">
                <div class="fw-bold fs-2 text-warning">{{ $selectedTa ? $selectedTa->nama_ta : '-' }}</div>
                <div class="small text-white-50">Tahun Akademik</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ============================================================ --}}
  {{-- FILTER SECTION                                               --}}
  {{-- ============================================================ --}}
  <div class="card border-1 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-3 p-md-4">
      <form action="{{ route('rps-pertemuan.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3 col-12">
          <label class="form-label small text-muted fw-bold">Cari Mata Kuliah</label>
          <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
            <input type="text" name="search" class="form-control border-start-0" placeholder="Kode atau nama MK..." value="{{ request('search') }}">
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
        <div class="col-md-3 col-12">
          <label class="form-label small text-muted fw-bold">Program Studi</label>
          <select name="prodi" class="form-select" onchange="this.form.submit()">
            <option value="">-- Semua Prodi --</option>
            @foreach($prodiList as $prodi)
              <option value="{{ $prodi->prodiKode }}" {{ request('prodi') == $prodi->prodiKode ? 'selected' : '' }}>
                {{ $prodi->prodiNamaResmi }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
          <button type="submit" class="btn btn-dark py-2"><i class="ti ti-filter me-1"></i> Filter</button>
          <a href="{{ route('rps-pertemuan.index') }}" class="btn btn-light py-2 px-3" title="Reset"><i class="ti ti-refresh"></i></a>
        </div>
      </form>
    </div>
  </div>

  {{-- ============================================================ --}}
  {{-- RPS CARDS GRID                                               --}}
  {{-- ============================================================ --}}
  @if($rpsList->isEmpty())
    <div class="card border-1 shadow-sm" style="border-radius: 16px;">
      <div class="card-body py-5 text-center">
        <i class="ti ti-book-off fs-1 text-muted d-block mb-3" style="font-size: 4rem !important; color: #cbd5e1 !important;"></i>
        <h5 class="fw-bold text-slate-700 mb-1">Tidak Ada RPS Ditemukan</h5>
        <p class="text-muted mb-3">Belum ada RPS pada tahun akademik dan filter yang dipilih.</p>
        <a href="{{ route('rps.index') }}" class="btn btn-primary">
          <i class="ti ti-notebook me-1"></i> Susun RPS Baru
        </a>
      </div>
    </div>
  @else
    <div class="row g-4 mb-4">
      @foreach($rpsList as $rps)
        @php
          $statusColors = [
            'DRAFT' => ['bg' => '#fef3c7', 'text' => '#92400e', 'border' => '#fbbf24'],
            'MENUNGGU_REVIEW' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'border' => '#60a5fa'],
            'DISETUJUI' => ['bg' => '#d1fae5', 'text' => '#065f46', 'border' => '#34d399'],
            'DIPUBLIKASIKAN' => ['bg' => '#d1fae5', 'text' => '#065f46', 'border' => '#10b981'],
            'SELESAI' => ['bg' => '#f3f4f6', 'text' => '#374151', 'border' => '#9ca3af'],
          ];
          $sc = $statusColors[$rps->status] ?? $statusColors['DRAFT'];
          $pctColor = $rps->pct_realisasi >= 80 ? '#22c55e' : ($rps->pct_realisasi >= 50 ? '#f59e0b' : '#ef4444');
        @endphp
        <div class="col-xl-4 col-md-6 col-12">
          <div class="card border-1 shadow-sm h-100" style="border-radius: 16px; transition: transform 0.2s, box-shadow 0.2s; border-top: 4px solid {{ $sc['border'] }} !important;"
               onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,0.12)'"
               onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div class="card-body p-4">
              {{-- Course Header --}}
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="flex-grow-1">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge font-monospace fw-bold" style="background:rgba(99,102,241,0.1);color:#6366f1;border:1px solid rgba(99,102,241,0.2);font-size:0.72rem;">
                      {{ $rps->kurikulumMataKuliah->mataKuliah->mk_kode }}
                    </span>
                    <span class="badge" style="background:{{ $sc['bg'] }};color:{{ $sc['text'] }};border:1px solid {{ $sc['border'] }};font-size:0.65rem;">
                      {{ $rps->status }}
                    </span>
                  </div>
                  <h6 class="fw-bold text-dark mb-1" style="font-size:0.95rem;line-height:1.3;">
                    {{ Str::limit($rps->kurikulumMataKuliah->mataKuliah->mk_nama, 50) }}
                  </h6>
                  <div class="small text-muted">
                    <i class="ti ti-school me-1"></i>{{ $rps->kurikulumMataKuliah->kurikulum->programStudi->prodiNamaResmi ?? '-' }}
                  </div>
                </div>
              </div>

              {{-- Stats Row --}}
              <div class="row g-2 mb-3">
                <div class="col-4 text-center">
                  <div class="p-2 rounded-2" style="background:#f8fafc;">
                    <div class="fw-bold text-dark" style="font-size:1.1rem;">{{ $rps->total_pertemuan }}</div>
                    <div class="text-muted" style="font-size:0.65rem;">Pertemuan</div>
                  </div>
                </div>
                <div class="col-4 text-center">
                  <div class="p-2 rounded-2" style="background:#f0fdf4;">
                    <div class="fw-bold" style="font-size:1.1rem;color:#22c55e;">{{ $rps->total_terlaksana }}</div>
                    <div class="text-muted" style="font-size:0.65rem;">Terlaksana</div>
                  </div>
                </div>
                <div class="col-4 text-center">
                  <div class="p-2 rounded-2" style="background:#fefce8;">
                    <div class="fw-bold" style="font-size:1.1rem;color:#f59e0b;">{{ $rps->total_materi }}</div>
                    <div class="text-muted" style="font-size:0.65rem;">Materi</div>
                  </div>
                </div>
              </div>

              {{-- Progress Realisasi --}}
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="small text-muted">Progress Realisasi</span>
                  <span class="small fw-bold" style="color:{{ $pctColor }}">{{ $rps->pct_realisasi }}%</span>
                </div>
                <div class="progress" style="height: 6px; border-radius: 3px; background: #f1f5f9;">
                  <div class="progress-bar" style="width:{{ $rps->pct_realisasi }}%;background:{{ $pctColor }};border-radius:3px;transition:width 0.8s ease;"></div>
                </div>
              </div>

              {{-- Coordinator --}}
              <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded-2" style="background:#f8fafc;">
                <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold"
                     style="width:32px;height:32px;font-size:0.75rem;flex-shrink:0;">
                  {{ substr($rps->dosenKoordinator->nama_lengkap ?? 'D', 0, 1) }}
                </div>
                <div class="overflow-hidden">
                  <div class="small fw-semibold text-dark text-truncate">{{ $rps->dosenKoordinator->nama_lengkap ?? '-' }}</div>
                  <div class="text-muted" style="font-size:0.68rem;">Koordinator</div>
                </div>
              </div>

              {{-- CTA --}}
              <a href="{{ route('rps-pertemuan.workspace', $rps->id_rps) }}"
                 class="btn fw-semibold d-inline-flex align-items-center justify-content-center gap-2"
                 style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;border:none;border-radius:10px;padding:10px;font-size:0.87rem;transition:opacity 0.2s;"
                 onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                <i class="ti ti-layout-grid"></i>
                Buka Workspace
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between align-items-center">
      <p class="text-muted small mb-0">
        Menampilkan <strong>{{ $rpsList->firstItem() }}–{{ $rpsList->lastItem() }}</strong> dari <strong>{{ $rpsList->total() }}</strong> RPS
      </p>
      {{ $rpsList->links() }}
    </div>
  @endif
</main>
@endsection
