@extends('layouts.app')

@push('styles')
<style>
/* ===============================
   WORKSPACE LAYOUT
================================ */
.workspace-header {
  background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #1a2e52 100%);
  border-radius: 20px;
  position: relative;
  overflow: hidden;
}
.workspace-header::before {
  content: '';
  position: absolute;
  top: -60px; right: -60px;
  width: 280px; height: 280px;
  background: rgba(99,102,241,0.1);
  border-radius: 50%;
}
.workspace-header::after {
  content: '';
  position: absolute;
  bottom: -40px; left: 15%;
  width: 200px; height: 200px;
  background: rgba(34,197,94,0.06);
  border-radius: 50%;
}

/* ===============================
   STAT CARDS
================================ */
.stat-card {
  border-radius: 14px;
  border: none;
  transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }

/* ===============================
   PERTEMUAN TIMELINE GRID
================================ */
.meeting-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 16px;
}
.meeting-card {
  border-radius: 14px;
  border: none;
  border-left: 4px solid #e2e8f0;
  transition: transform 0.2s, box-shadow 0.2s;
  position: relative;
  overflow: hidden;
}
.meeting-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.09);
}
.meeting-card.is-uts  { border-left-color: #f97316; }
.meeting-card.is-uas  { border-left-color: #ef4444; }
.meeting-card.is-done { border-left-color: #22c55e; }
.meeting-card.is-plan { border-left-color: #6366f1; }

/* ===============================
   STATUS BADGE
================================ */
.badge-status-done    { background: rgba(34,197,94,0.12);  color:#166534;  border:1px solid rgba(34,197,94,0.3); }
.badge-status-plan    { background: rgba(99,102,241,0.1);  color:#4338ca;  border:1px solid rgba(99,102,241,0.25); }
.badge-status-uts     { background: rgba(249,115,22,0.1);  color:#9a3412;  border:1px solid rgba(249,115,22,0.25); }
.badge-status-uas     { background: rgba(239,68,68,0.1);   color:#7f1d1d;  border:1px solid rgba(239,68,68,0.25); }

/* ===============================
   HEATMAP
================================ */
.heatmap-cell {
  width: 28px; height: 28px;
  border-radius: 6px;
  font-size: 0.65rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: default;
  transition: transform 0.15s;
}
.heatmap-cell:hover { transform: scale(1.2); z-index: 10; position: relative; }
.heatmap-hit  { background: #6366f1; color: #fff; }
.heatmap-miss { background: #f1f5f9; color: #94a3b8; }

/* ===============================
   MODAL TABS
================================ */
.ws-tab { cursor:pointer; padding:8px 16px; border-radius:8px; font-size:0.85rem; color:#64748b; font-weight:500; transition:all 0.2s; }
.ws-tab.active { background:#6366f1; color:#fff; }
.ws-tab:not(.active):hover { background:#f1f5f9; color:#334155; }

/* ===============================
   COMPLIANCE METER
================================ */
.compliance-ring { transform: rotate(-90deg); }
.compliance-ring-track { fill:none; stroke:#f1f5f9; stroke-width:12; }
.compliance-ring-fill  { fill:none; stroke-width:12; stroke-linecap:round; transition:stroke-dashoffset 1s ease; }

/* ===============================
   ACCORDION: pertemuan detail
================================ */
.pt-accordion { cursor:pointer; }
.pt-accordion-icon { transition: transform 0.3s; }
.pt-accordion-icon.open { transform: rotate(180deg); }
</style>
@endpush

@section('content')
<main class="p-3 p-md-4">

  {{-- ============================================================ --}}
  {{-- WORKSPACE HEADER                                             --}}
  {{-- ============================================================ --}}
  <div class="workspace-header shadow-sm mb-4">
    <div class="card-body p-4 p-md-5 position-relative text-white" style="z-index:1;">
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size:0.8rem; opacity:0.7;">
          <li class="breadcrumb-item"><a href="{{ route('rps-pertemuan.index') }}" class="text-white-50 text-decoration-none">Pertemuan &amp; Materi</a></li>
          <li class="breadcrumb-item active text-white">Workspace</li>
        </ol>
      </nav>

      <div class="row align-items-center">
        <div class="col-lg-7">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="d-flex align-items-center justify-content-center rounded-3" style="width:54px;height:54px;background:rgba(99,102,241,0.2);backdrop-filter:blur(10px);">
              <i class="ti ti-layout-grid fs-1 text-primary"></i>
            </div>
            <div>
              <span class="badge fw-semibold" style="background:rgba(99,102,241,0.2);color:#a5b4fc;border:1px solid rgba(99,102,241,0.3);border-radius:20px;font-size:0.7rem;">
                {{ $rps->kurikulumMataKuliah->mataKuliah->mk_kode ?? '-' }}
              </span>
              <h2 class="fw-bold text-white mb-0" style="font-size:1.45rem;line-height:1.3;">
                {{ $rps->kurikulumMataKuliah->mataKuliah->mk_nama ?? '-' }}
              </h2>
              <div class="text-white-50 small mt-1">
                <i class="ti ti-school me-1"></i>{{ $rps->kurikulumMataKuliah->kurikulum->programStudi->prodiNamaResmi ?? '-' }}
                &nbsp;·&nbsp;
                <i class="ti ti-calendar me-1"></i>{{ $rps->tahunAkademik->nama_ta ?? '-' }}
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5 mt-3 mt-lg-0">
          <div class="row g-2">
            {{-- Compliance Rate --}}
            <div class="col-6">
              <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,0.06);backdrop-filter:blur(10px);">
                <div id="compliance-display" class="fw-bold fs-2 text-white">
                  @if($complianceRate !== null){{ $complianceRate }}%@else<span class="text-white-50 fs-5">N/A</span>@endif
                </div>
                <div class="small text-white-50">Compliance Rate</div>
              </div>
            </div>
            {{-- Progress --}}
            <div class="col-6">
              <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,0.06);backdrop-filter:blur(10px);">
                <div class="fw-bold fs-2" style="color:#34d399;" id="progress-display">{{ $pctProgress }}%</div>
                <div class="small text-white-50">
                  <span id="terlaksana-count">{{ $totalTerlaksanaAll }}</span>/{{ $totalPt }} Pertemuan
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Overall Progress Bar --}}
      <div class="mt-3">
        <div class="progress" style="height:6px;background:rgba(255,255,255,0.1);border-radius:10px;">
          <div class="progress-bar" id="overall-progress-bar"
               style="width:{{ $pctProgress }}%;background:linear-gradient(90deg,#6366f1,#34d399);border-radius:10px;transition:width 1s ease;"></div>
        </div>
      </div>
    </div>
  </div>

  {{-- ============================================================ --}}
  {{-- QUICK STATS                                                  --}}
  {{-- ============================================================ --}}
  <div class="row g-3 mb-4">
    @php
      $statCards = [
        ['icon'=>'ti-calendar-event','color'=>'#6366f1','bg'=>'rgba(99,102,241,0.1)','val'=>$totalPt,'label'=>'Total Pertemuan'],
        ['icon'=>'ti-circle-check','color'=>'#22c55e','bg'=>'rgba(34,197,94,0.1)','val'=>$totalTerlaksanaAll,'label'=>'Terlaksana'],
        ['icon'=>'ti-file-description','color'=>'#f59e0b','bg'=>'rgba(245,158,11,0.1)','val'=>$rps->pertemuan->sum(fn($p) => $p->materis->count()),'label'=>'Total Materi'],
        ['icon'=>'ti-target','color'=>'#0ea5e9','bg'=>'rgba(14,165,233,0.1)','val'=>$cpmkList->count(),'label'=>'CPMK Terkait'],
      ];
    @endphp
    @foreach($statCards as $sc)
    <div class="col-6 col-md-3">
      <div class="card stat-card shadow-sm">
        <div class="card-body p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                 style="width:44px;height:44px;background:{{ $sc['bg'] }}">
              <i class="ti {{ $sc['icon'] }} fs-3" style="color:{{ $sc['color'] }}"></i>
            </div>
            <div>
              <div class="fw-bold fs-3 text-dark lh-1">{{ $sc['val'] }}</div>
              <div class="text-muted small">{{ $sc['label'] }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  {{-- ============================================================ --}}
  {{-- MAIN CONTENT: 2-COLUMN LAYOUT                               --}}
  {{-- ============================================================ --}}
  <div class="row g-4">

    {{-- ========================= LEFT: PERTEMUAN TIMELINE ========================= --}}
    <div class="col-xl-8 col-12">
      <div class="card border-1 shadow-sm" style="border-radius:16px;">
        <div class="card-header border-0 bg-transparent p-4 pb-2 d-flex align-items-center justify-content-between">
          <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
            <i class="ti ti-list-numbers text-primary"></i> Timeline 16 Pertemuan
          </h6>
          <div class="d-flex gap-2 small text-muted flex-wrap">
            <span><span class="badge badge-status-plan">&nbsp;</span> Direncanakan</span>
            <span><span class="badge badge-status-done">&nbsp;</span> Terlaksana</span>
            <span><span class="badge badge-status-uts">&nbsp;</span> UTS/UAS</span>
          </div>
        </div>
        <div class="card-body p-3 p-md-4">
          <div class="meeting-grid">
            @forelse($rps->pertemuan as $pertemuan)
              @php
                $isUts   = $pertemuan->jenis_pertemuan === 'UTS';
                $isUas   = $pertemuan->jenis_pertemuan === 'UAS';
                $isDone  = $pertemuan->realisasi !== null;
                $cardClass = $isUts ? 'is-uts' : ($isUas ? 'is-uas' : ($isDone ? 'is-done' : 'is-plan'));
                $statusBadgeClass = $isUts ? 'badge-status-uts' : ($isUas ? 'badge-status-uas' : ($isDone ? 'badge-status-done' : 'badge-status-plan'));
                $statusLabel = $isUts ? 'UTS' : ($isUas ? 'UAS' : ($isDone ? 'Terlaksana' : 'Direncanakan'));
                $cpmkKode = $pertemuan->cpmk->kode_cpmk ?? null;
                $subCpmkKode = $pertemuan->subCpmk->kode_sub_cpmk ?? null;
                $materiCount = $pertemuan->materis->count();
              @endphp
              <div class="meeting-card shadow-sm {{ $cardClass }}" id="meeting-card-{{ $pertemuan->id_pertemuan }}">
                <div class="card-body p-3">
                  {{-- Pertemuan Number & Status --}}
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                      <div class="d-flex align-items-center justify-content-center rounded-2 fw-bold text-white"
                           style="width:32px;height:32px;font-size:0.75rem;flex-shrink:0;
                                  background:{{ $isUts ? '#f97316' : ($isUas ? '#ef4444' : ($isDone ? '#22c55e' : '#6366f1')) }}">
                        P{{ $pertemuan->nomor_pertemuan }}
                      </div>
                      <span class="badge rounded-pill {{ $statusBadgeClass }}" style="font-size:0.65rem;">{{ $statusLabel }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                      @if($materiCount > 0)
                        <span class="badge text-secondary" style="background:#f1f5f9;font-size:0.65rem;">
                          <i class="ti ti-paperclip"></i> {{ $materiCount }}
                        </span>
                      @endif
                    </div>
                  </div>

                  {{-- Topik --}}
                  <p class="fw-semibold text-dark mb-1" style="font-size:0.83rem;line-height:1.4;">
                    {{ Str::limit($pertemuan->topik ?? 'Topik belum diisi', 60) }}
                  </p>

                  {{-- Sub-CPMK Tag --}}
                  @if($cpmkKode || $subCpmkKode)
                  <div class="mb-2 d-flex flex-wrap gap-1">
                    @if($cpmkKode)
                      <span class="badge" style="background:rgba(99,102,241,0.1);color:#4f46e5;font-size:0.65rem;border:1px solid rgba(99,102,241,0.2);">
                        <i class="ti ti-target me-1"></i>{{ $cpmkKode }}
                      </span>
                    @endif
                    @if($subCpmkKode)
                      <span class="badge" style="background:rgba(14,165,233,0.1);color:#0369a1;font-size:0.65rem;border:1px solid rgba(14,165,233,0.2);">
                        {{ $subCpmkKode }}
                      </span>
                    @endif
                  </div>
                  @endif

                  {{-- Metode --}}
                  @if($pertemuan->metode_pembelajaran)
                  <div class="small text-muted mb-2">
                    <i class="ti ti-device-desktop me-1"></i>{{ Str::limit($pertemuan->metode_pembelajaran, 40) }}
                  </div>
                  @endif

                  {{-- Realisasi brief --}}
                  @if($isDone)
                  <div class="p-2 rounded-2 mb-2" style="background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.15);">
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="small fw-semibold" style="color:#166534;">
                        <i class="ti ti-calendar-check me-1"></i>{{ \Carbon\Carbon::parse($pertemuan->realisasi->tanggal_pelaksanaan)->format('d M Y') }}
                      </div>
                      <span class="badge" style="background:rgba(34,197,94,0.15);color:#166534;font-size:0.6rem;">
                        {{ $pertemuan->realisasi->status_realisasi }}
                      </span>
                    </div>
                    @if($pertemuan->realisasi->catatan_pelaksanaan)
                      <div class="text-muted mt-1" style="font-size:0.7rem;">{{ Str::limit($pertemuan->realisasi->catatan_pelaksanaan, 50) }}</div>
                    @endif
                  </div>
                  @endif

                  {{-- Action Buttons --}}
                  @if(!$isUts && !$isUas)
                  <div class="d-flex gap-2 mt-2">
                    <button type="button" class="btn btn-sm flex-fill fw-semibold btn-open-realisasi"
                            style="background:{{ $isDone ? 'rgba(34,197,94,0.1)' : 'rgba(99,102,241,0.1)' }};
                                   color:{{ $isDone ? '#166534' : '#4f46e5' }};
                                   border:1px solid {{ $isDone ? 'rgba(34,197,94,0.2)' : 'rgba(99,102,241,0.2)' }};
                                   border-radius:8px;font-size:0.75rem;"
                            data-id-pertemuan="{{ $pertemuan->id_pertemuan }}"
                            data-nomor="{{ $pertemuan->nomor_pertemuan }}"
                            data-topik="{{ $pertemuan->topik }}"
                            data-has-realisasi="{{ $isDone ? '1' : '0' }}"
                            data-realisasi="{{ $isDone ? json_encode($pertemuan->realisasi) : '{}' }}">
                      <i class="ti ti-{{ $isDone ? 'edit' : 'plus' }} me-1"></i>{{ $isDone ? 'Edit' : 'Realisasi' }}
                    </button>
                    <button type="button" class="btn btn-sm btn-open-materi"
                            style="background:rgba(245,158,11,0.08);color:#92400e;border:1px solid rgba(245,158,11,0.2);border-radius:8px;font-size:0.75rem;padding:4px 10px;"
                            data-id-pertemuan="{{ $pertemuan->id_pertemuan }}"
                            data-nomor="{{ $pertemuan->nomor_pertemuan }}"
                            title="Materi">
                      <i class="ti ti-paperclip"></i>
                      @if($materiCount > 0)<span class="ms-1">{{ $materiCount }}</span>@endif
                    </button>
                  </div>
                  @endif
                </div>
              </div>
            @empty
              <div class="col-12 text-center py-4 text-muted">
                <i class="ti ti-calendar-off fs-1 d-block mb-2" style="color:#cbd5e1;"></i>
                Belum ada data pertemuan untuk RPS ini.
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    {{-- ========================= RIGHT: ANALYTICS PANEL ========================= --}}
    <div class="col-xl-4 col-12">

      {{-- CPMK Heatmap --}}
      <div class="card border-1 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-header border-0 bg-transparent p-4 pb-2">
          <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
            <i class="ti ti-grid-dots text-primary"></i> Heatmap CPMK × Pertemuan
          </h6>
          <p class="text-muted small mb-0 mt-1">Sebaran topik per CPMK pada 16 pertemuan</p>
        </div>
        <div class="card-body p-3 p-md-4">
          @if(count($heatmap) === 0)
            <div class="text-center text-muted small py-3">CPMK belum ditetapkan.</div>
          @else
            @foreach($heatmap as $cpmkId => $hdata)
            <div class="mb-3">
              <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="fw-semibold text-dark small">{{ $hdata['kode'] }}</span>
                <span class="badge rounded-pill" style="background:rgba(99,102,241,0.1);color:#4f46e5;font-size:0.65rem;">
                  {{ $hdata['count'] }} pertemuan
                </span>
              </div>
              <div class="d-flex flex-wrap gap-1">
                @for($p = 1; $p <= $totalPt; $p++)
                  <div class="heatmap-cell {{ in_array($p, $hdata['meetings']) ? 'heatmap-hit' : 'heatmap-miss' }}"
                       title="Pertemuan {{ $p }}">{{ $p }}</div>
                @endfor
              </div>
            </div>
            @endforeach
          @endif
        </div>
      </div>

      {{-- Compliance Rate Card --}}
      <div class="card border-1 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-header border-0 bg-transparent p-4 pb-2">
          <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
            <i class="ti ti-chart-pie text-success"></i> Analitik Realisasi
          </h6>
        </div>
        <div class="card-body p-4">
          @php
            $cr = $complianceRate ?? 0;
            $crColor = $cr >= 80 ? '#22c55e' : ($cr >= 60 ? '#f59e0b' : '#ef4444');
            $circumference = 2 * M_PI * 52; // r=52
            $dashoffset = $circumference - ($cr / 100) * $circumference;
          @endphp
          <div class="text-center mb-3">
            <svg width="130" height="130" viewBox="0 0 130 130">
              <circle class="compliance-ring-track" cx="65" cy="65" r="52"/>
              <circle class="compliance-ring-fill compliance-ring" cx="65" cy="65" r="52"
                      stroke="{{ $crColor }}"
                      stroke-dasharray="{{ $circumference }}"
                      stroke-dashoffset="{{ $dashoffset }}"
                      id="compliance-ring-fill"/>
              <text x="65" y="60" text-anchor="middle" font-size="22" font-weight="700" fill="#1e293b" font-family="Inter,sans-serif">
                {{ $complianceRate !== null ? $complianceRate . '%' : 'N/A' }}
              </text>
              <text x="65" y="78" text-anchor="middle" font-size="9" fill="#94a3b8" font-family="Inter,sans-serif">COMPLIANCE</text>
            </svg>
          </div>
          <div class="row g-2 text-center">
            <div class="col-6">
              <div class="p-2 rounded-2" style="background:#f0fdf4;">
                <div class="fw-bold text-success fs-5">{{ $totalTerlaksanaAll }}</div>
                <div class="text-muted small">Terlaksana</div>
              </div>
            </div>
            <div class="col-6">
              <div class="p-2 rounded-2" style="background:#fef2f2;">
                <div class="fw-bold text-danger fs-5">{{ $totalPt - $totalTerlaksanaAll }}</div>
                <div class="text-muted small">Belum</div>
              </div>
            </div>
          </div>
          @if($complianceRate !== null)
          <div class="mt-3 p-3 rounded-2 text-center" style="background:{{ $cr >= 80 ? 'rgba(34,197,94,0.08)' : ($cr >= 60 ? 'rgba(245,158,11,0.08)' : 'rgba(239,68,68,0.08)') }};">
            <div class="small fw-semibold" style="color:{{ $crColor }}">
              @if($cr >= 80) ✓ Compliance Sangat Baik
              @elseif($cr >= 60) ⚠ Compliance Cukup — Perlu Perhatian
              @else ✗ Compliance Rendah — Perlu Evaluasi
              @endif
            </div>
          </div>
          @endif
        </div>
      </div>

      {{-- DOSEN LIST --}}
      <div class="card border-1 shadow-sm" style="border-radius:16px;">
        <div class="card-header border-0 bg-transparent p-4 pb-2">
          <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
            <i class="ti ti-users text-warning"></i> Tim Dosen Pengampu
          </h6>
        </div>
        <div class="card-body p-3 p-md-4">
          @forelse($rps->rpsDosens as $rd)
          <div class="d-flex align-items-center gap-3 {{ !$loop->last ? 'mb-3' : '' }}">
            <div class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold flex-shrink-0"
                 style="width:38px;height:38px;background:{{ ['#6366f1','#f59e0b','#0ea5e9','#22c55e'][$loop->index % 4] }};font-size:0.8rem;">
              {{ substr($rd->dosen->nama_lengkap ?? 'D', 0, 1) }}
            </div>
            <div>
              <div class="fw-semibold text-dark small">{{ $rd->dosen->nama_lengkap ?? '-' }}</div>
              <div class="text-muted" style="font-size:0.7rem;">
                {{ $rd->nidn ?? $rd->dosen->nidn ?? '-' }}
                @if($rps->id_dosen_koordinator == ($rd->id_dosen ?? null))
                  <span class="badge ms-1" style="background:rgba(99,102,241,0.1);color:#4f46e5;font-size:0.6rem;">Koordinator</span>
                @endif
              </div>
            </div>
          </div>
          @empty
          <div class="text-muted small text-center py-2">Belum ada tim dosen.</div>
          @endforelse
        </div>
      </div>

    </div>
  </div>
</main>

{{-- ============================================================ --}}
{{-- MODAL: REALISASI PERTEMUAN                                   --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalRealisasi" tabindex="-1" aria-labelledby="modalRealisasiLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
      <div class="modal-header border-0 p-4 pb-0">
        <div>
          <h5 class="fw-bold text-dark mb-0" id="modalRealisasiLabel">
            <i class="ti ti-calendar-check me-2 text-success"></i>Catat Realisasi Pertemuan
          </h5>
          <p class="text-muted small mb-0 mt-1" id="modal-pertemuan-info">—</p>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="form-realisasi">
          @csrf
          <input type="hidden" id="realisasi-id-pertemuan" name="id_pertemuan">

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold small">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
              <input type="date" name="tanggal_pelaksanaan" id="r-tanggal" class="form-control" max="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold small">Jam Mulai</label>
              <input type="time" name="jam_mulai" id="r-jam-mulai" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold small">Jam Selesai</label>
              <input type="time" name="jam_selesai" id="r-jam-selesai" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold small">Durasi Aktual (menit) <span class="text-danger">*</span></label>
              <input type="number" name="durasi_aktual_menit" id="r-durasi" class="form-control" min="1" placeholder="e.g. 150" required>
            </div>
            <div class="col-md-8">
              <label class="form-label fw-semibold small">Status Realisasi <span class="text-danger">*</span></label>
              <select name="status_realisasi" id="r-status" class="form-select" required>
                <option value="">-- Pilih Status --</option>
                <option value="Terlaksana">✅ Terlaksana Sesuai Rencana</option>
                <option value="Pengganti">🔄 Perkuliahan Pengganti</option>
                <option value="Digabung">🔀 Digabung dengan Pertemuan Lain</option>
                <option value="Tidak_Terlaksana">❌ Tidak Terlaksana</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold small">CPMK yang Dicapai</label>
              <select name="id_cpmk_aktual" id="r-cpmk" class="form-select">
                <option value="">-- Sesuai Rencana / Tidak Ada --</option>
                @foreach($cpmkList as $c)
                  <option value="{{ $c->id }}">{{ $c->kode_cpmk }} — {{ Str::limit($c->deskripsi, 40) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold small">Sub-CPMK yang Dicapai</label>
              <select name="id_sub_cpmk_aktual" id="r-subcpmk" class="form-select">
                <option value="">-- Sesuai Rencana / Tidak Ada --</option>
                @foreach($subCpmkList as $sc)
                  <option value="{{ $sc->id_sub_cpmk }}">{{ $sc->kode_sub_cpmk }} — {{ Str::limit($sc->deskripsi, 35) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold small">Topik Aktual</label>
              <input type="text" name="topik_aktual" id="r-topik" class="form-control" placeholder="Topik yang benar-benar dibahas...">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold small">Metode Pembelajaran Aktual</label>
              <div class="row g-2">
                @foreach(['Ceramah','Diskusi','Praktikum','Presentasi','Studi Kasus','Problem Based Learning','Project Based Learning','Demonstrasi','E-Learning'] as $met)
                  <div class="col-6 col-md-4">
                    <div class="form-check form-check-sm">
                      <input class="form-check-input" type="checkbox" name="metode_aktual[]" value="{{ $met }}" id="metode-{{ Str::slug($met) }}">
                      <label class="form-check-label small text-muted" for="metode-{{ Str::slug($met) }}">{{ $met }}</label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
            <div class="col-12" id="catatan-wrapper">
              <label class="form-label fw-semibold small">Catatan Pelaksanaan</label>
              <textarea name="catatan_pelaksanaan" id="r-catatan" class="form-control" rows="3"
                        placeholder="Deskripsi singkat pelaksanaan, kendala, atau penyesuaian yang dilakukan..."></textarea>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold small">Deviasi dari Rencana</label>
              <textarea name="deviasi_dari_rencana" id="r-deviasi" class="form-control" rows="2"
                        placeholder="Jelaskan jika ada perbedaan dari rencana semula..."></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer border-0 p-4 pt-0 d-flex gap-2">
        <button type="button" class="btn btn-light flex-shrink-0 px-4" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn flex-fill fw-semibold" id="btn-save-realisasi"
                style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;border:none;border-radius:10px;">
          <i class="ti ti-device-floppy me-1"></i> Simpan Realisasi
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: MATERI PERTEMUAN                                      --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalMateri" tabindex="-1" aria-labelledby="modalMateriLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
      <div class="modal-header border-0 p-4 pb-0">
        <div>
          <h5 class="fw-bold text-dark mb-0" id="modalMateriLabel">
            <i class="ti ti-paperclip me-2 text-warning"></i>Kelola Materi Pertemuan
          </h5>
          <p class="text-muted small mb-0 mt-1" id="modal-materi-info">—</p>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">

        {{-- Materi List --}}
        <div id="materi-list" class="mb-4">
          <div class="text-center text-muted small py-3">
            <div class="spinner-border spinner-border-sm me-2"></div> Memuat materi...
          </div>
        </div>

        {{-- Add Materi Form --}}
        <div class="border-top pt-3">
          <h6 class="fw-bold text-dark mb-3"><i class="ti ti-plus me-1 text-success"></i>Tambah Materi Baru</h6>
          <form id="form-materi" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="materi-id-pertemuan" name="id_pertemuan">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label fw-semibold small">Nama Materi <span class="text-danger">*</span></label>
                <input type="text" name="nama_materi" id="m-nama" class="form-control" placeholder="e.g. Slide Pertemuan 1 — Pengantar OBE" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small">Tipe Materi <span class="text-danger">*</span></label>
                <select name="tipe_materi" id="m-tipe" class="form-select" required>
                  <option value="">-- Pilih Tipe --</option>
                  <option value="File">📄 File Upload</option>
                  <option value="URL">🔗 URL / Link</option>
                  <option value="YouTube">▶ YouTube</option>
                  <option value="LMS">🎓 LMS / e-Learning</option>
                  <option value="Repository">💻 Repository (GitHub/GitLab)</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small">Jenis Konten <span class="text-danger">*</span></label>
                <select name="jenis_konten" id="m-jenis" class="form-select" required>
                  <option value="">-- Pilih Jenis --</option>
                  @foreach(['Slide','Video','Modul','Artikel','Soal','Rubrik','Dataset','Kode','Lainnya'] as $jk)
                    <option value="{{ $jk }}">{{ $jk }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12" id="input-file-wrapper">
                <label class="form-label fw-semibold small">File <span class="text-danger">*</span></label>
                <input type="file" name="file_materi" id="m-file" class="form-control">
                <div class="form-text">Maks. 50MB. PDF, DOCX, PPTX, MP4, ZIP, dll.</div>
              </div>
              <div class="col-12 d-none" id="input-url-wrapper">
                <label class="form-label fw-semibold small">URL / Link <span class="text-danger">*</span></label>
                <input type="url" name="url_materi" id="m-url" class="form-control" placeholder="https://...">
              </div>
              <div class="col-12 d-flex align-items-center gap-2">
                <div class="form-check form-switch mb-0">
                  <input class="form-check-input" type="checkbox" id="m-publik" name="is_publik" value="1" checked>
                  <label class="form-check-label fw-semibold small text-muted" for="m-publik">Terlihat oleh Mahasiswa</label>
                </div>
              </div>
            </div>
            <div class="mt-3">
              <button type="button" id="btn-save-materi"
                      class="btn fw-semibold px-4"
                      style="background:linear-gradient(135deg,#f59e0b,#d97706);color:white;border:none;border-radius:10px;">
                <i class="ti ti-upload me-1"></i> Upload Materi
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// ===================================================
// CSRF Token helper
// ===================================================
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

function showToast(message, type = 'success') {
  const id = 'toast-' + Date.now();
  const bg = type === 'success' ? '#22c55e' : '#ef4444';
  const html = `<div id="${id}" class="toast align-items-center border-0 text-white show" role="alert" style="background:${bg};border-radius:12px;">
    <div class="d-flex"><div class="toast-body fw-semibold">${message}</div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>`;
  let container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
    container.style.zIndex = 9999;
    document.body.appendChild(container);
  }
  container.insertAdjacentHTML('beforeend', html);
  setTimeout(() => { const el = document.getElementById(id); if(el) el.remove(); }, 4000);
}

// ===================================================
// REALISASI MODAL
// ===================================================
let currentPertemuanId = null;

document.querySelectorAll('.btn-open-realisasi').forEach(btn => {
  btn.addEventListener('click', () => {
    currentPertemuanId = btn.dataset.idPertemuan;
    document.getElementById('realisasi-id-pertemuan').value = currentPertemuanId;
    document.getElementById('modal-pertemuan-info').textContent =
      `Pertemuan ke-${btn.dataset.nomor}: ${btn.dataset.topik || '-'}`;

    // Reset form
    document.getElementById('form-realisasi').reset();

    // Prefill if realisasi exists
    const r = JSON.parse(btn.dataset.realisasi || '{}');
    if (r && r.tanggal_pelaksanaan) {
      document.getElementById('r-tanggal').value = r.tanggal_pelaksanaan?.substring(0,10) ?? '';
      document.getElementById('r-jam-mulai').value = r.jam_mulai ?? '';
      document.getElementById('r-jam-selesai').value = r.jam_selesai ?? '';
      document.getElementById('r-durasi').value = r.durasi_aktual_menit ?? '';
      document.getElementById('r-status').value = r.status_realisasi ?? '';
      document.getElementById('r-cpmk').value = r.id_cpmk_aktual ?? '';
      document.getElementById('r-subcpmk').value = r.id_sub_cpmk_aktual ?? '';
      document.getElementById('r-topik').value = r.topik_aktual ?? '';
      document.getElementById('r-catatan').value = r.catatan_pelaksanaan ?? '';
      document.getElementById('r-deviasi').value = r.deviasi_dari_rencana ?? '';
      // Metode checkboxes
      if (r.metode_aktual) {
        r.metode_aktual.split(',').forEach(m => {
          const cb = document.querySelector(`input[name="metode_aktual[]"][value="${m.trim()}"]`);
          if (cb) cb.checked = true;
        });
      }
    }

    new bootstrap.Modal(document.getElementById('modalRealisasi')).show();
  });
});

document.getElementById('btn-save-realisasi')?.addEventListener('click', async () => {
  const form = document.getElementById('form-realisasi');
  if (!form.checkValidity()) { form.reportValidity(); return; }

  const btn = document.getElementById('btn-save-realisasi');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

  const fd = new FormData(form);
  fd.append('_token', csrfToken);

  try {
    const res = await fetch(`/rps-pertemuan/pertemuan/${currentPertemuanId}/realisasi`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
      body: fd,
    });
    const data = await res.json();
    if (data.success) {
      showToast(data.message || 'Realisasi berhasil disimpan!');
      bootstrap.Modal.getInstance(document.getElementById('modalRealisasi'))?.hide();

      // Update compliance & progress display
      if (data.compliance_rate !== null) {
        document.getElementById('compliance-display').textContent = data.compliance_rate + '%';
      }
      document.getElementById('progress-display').textContent = data.pct_progress + '%';
      document.getElementById('terlaksana-count').textContent = data.total_terlaksana;
      document.getElementById('overall-progress-bar').style.width = data.pct_progress + '%';

      // Reload page to update card state after short delay
      setTimeout(() => location.reload(), 1200);
    } else {
      showToast(data.message || 'Gagal menyimpan realisasi.', 'error');
    }
  } catch(e) {
    showToast('Terjadi kesalahan. Coba lagi.', 'error');
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="ti ti-device-floppy me-1"></i> Simpan Realisasi';
  }
});

// ===================================================
// MATERI MODAL
// ===================================================
let currentMateriPertemuanId = null;

async function loadMateriList(idPertemuan) {
  document.getElementById('materi-list').innerHTML = `
    <div class="text-center text-muted small py-3">
      <div class="spinner-border spinner-border-sm me-2"></div> Memuat materi...
    </div>`;
  try {
    const res = await fetch(`/rps-pertemuan/pertemuan/${idPertemuan}/materi`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }
    });
    const data = await res.json();
    if (data.success && data.materis.length > 0) {
      const iconMap = { File:'ti-file', URL:'ti-link', YouTube:'ti-brand-youtube', LMS:'ti-school', Repository:'ti-brand-github' };
      const colorMap = { File:'#6366f1', URL:'#0ea5e9', YouTube:'#ef4444', LMS:'#22c55e', Repository:'#1e293b' };
      document.getElementById('materi-list').innerHTML = data.materis.map(m => `
        <div class="d-flex align-items-center gap-3 p-3 mb-2 rounded-3" style="background:#f8fafc;border:1px solid #f1f5f9;" id="materi-row-${m.id_materi}">
          <div class="d-flex align-items-center justify-content-center rounded-2 flex-shrink-0"
               style="width:38px;height:38px;background:rgba(99,102,241,0.1);">
            <i class="ti ${iconMap[m.tipe_materi] || 'ti-file'} fs-5" style="color:${colorMap[m.tipe_materi] || '#6366f1'}"></i>
          </div>
          <div class="flex-grow-1 overflow-hidden">
            <div class="fw-semibold text-dark small text-truncate">${m.nama_materi}</div>
            <div class="text-muted" style="font-size:0.7rem;">${m.tipe_materi} · ${m.jenis_konten}${m.ukuran_file_kb ? ' · ' + (m.ukuran_file_kb > 1024 ? (m.ukuran_file_kb/1024).toFixed(1) + ' MB' : m.ukuran_file_kb + ' KB') : ''}</div>
          </div>
          <div class="d-flex gap-1">
            ${m.url_atau_path ? `<a href="${m.url_atau_path}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2" title="Buka"><i class="ti ti-external-link"></i></a>` : ''}
            <button class="btn btn-sm py-1 px-2 btn-toggle-publik" data-id="${m.id_materi}" data-publik="${m.is_publik}"
                    style="background:${m.is_publik ? 'rgba(34,197,94,0.1)' : 'rgba(239,68,68,0.1)'};color:${m.is_publik ? '#166534' : '#7f1d1d'};border:none;"
                    title="${m.is_publik ? 'Publik' : 'Private'}">
              <i class="ti ${m.is_publik ? 'ti-eye' : 'ti-eye-off'}"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger py-1 px-2 btn-delete-materi" data-id="${m.id_materi}" title="Hapus">
              <i class="ti ti-trash"></i>
            </button>
          </div>
        </div>`).join('');
      attachMateriActions();
    } else {
      document.getElementById('materi-list').innerHTML =
        `<div class="text-center text-muted small py-3"><i class="ti ti-file-off d-block fs-3 mb-1" style="color:#cbd5e1;"></i>Belum ada materi untuk pertemuan ini.</div>`;
    }
  } catch(e) {
    document.getElementById('materi-list').innerHTML = `<div class="text-danger small text-center py-3">Gagal memuat materi.</div>`;
  }
}

function attachMateriActions() {
  document.querySelectorAll('.btn-toggle-publik').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id = btn.dataset.id;
      const res = await fetch(`/rps-pertemuan/materi/${id}/toggle-publik`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
      });
      const data = await res.json();
      if (data.success) { showToast(data.message); loadMateriList(currentMateriPertemuanId); }
    });
  });

  document.querySelectorAll('.btn-delete-materi').forEach(btn => {
    btn.addEventListener('click', async () => {
      if (!confirm('Hapus materi ini? Tindakan tidak dapat dibatalkan.')) return;
      const id = btn.dataset.id;
      const res = await fetch(`/rps-pertemuan/materi/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
      });
      const data = await res.json();
      if (data.success) { showToast(data.message); loadMateriList(currentMateriPertemuanId); }
      else showToast(data.message || 'Gagal menghapus.', 'error');
    });
  });
}

document.querySelectorAll('.btn-open-materi').forEach(btn => {
  btn.addEventListener('click', () => {
    currentMateriPertemuanId = btn.dataset.idPertemuan;
    document.getElementById('materi-id-pertemuan').value = currentMateriPertemuanId;
    document.getElementById('modal-materi-info').textContent = `Pertemuan ke-${btn.dataset.nomor}`;
    document.getElementById('form-materi').reset();
    toggleMateriInput('File');
    new bootstrap.Modal(document.getElementById('modalMateri')).show();
    loadMateriList(currentMateriPertemuanId);
  });
});

function toggleMateriInput(tipe) {
  const isFile = tipe === 'File';
  document.getElementById('input-file-wrapper').classList.toggle('d-none', !isFile);
  document.getElementById('input-url-wrapper').classList.toggle('d-none', isFile);
  if (!isFile) {
    document.getElementById('m-file').value = '';
    document.getElementById('m-file').removeAttribute('required');
    document.getElementById('m-url').setAttribute('required','');
  } else {
    document.getElementById('m-url').value = '';
    document.getElementById('m-url').removeAttribute('required');
    document.getElementById('m-file').setAttribute('required','');
  }
}

document.getElementById('m-tipe')?.addEventListener('change', function() { toggleMateriInput(this.value); });

document.getElementById('btn-save-materi')?.addEventListener('click', async () => {
  const form = document.getElementById('form-materi');
  if (!form.checkValidity()) { form.reportValidity(); return; }

  const btn = document.getElementById('btn-save-materi');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengupload...';

  const fd = new FormData(form);
  fd.append('_token', csrfToken);
  // Set is_publik boolean
  fd.set('is_publik', document.getElementById('m-publik').checked ? '1' : '0');

  try {
    const res = await fetch(`/rps-pertemuan/pertemuan/${currentMateriPertemuanId}/materi`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
      body: fd,
    });
    const data = await res.json();
    if (data.success) {
      showToast(data.message || 'Materi berhasil ditambahkan!');
      form.reset();
      toggleMateriInput('File');
      loadMateriList(currentMateriPertemuanId);
    } else {
      showToast(data.message || 'Gagal menyimpan materi.', 'error');
    }
  } catch(e) {
    showToast('Terjadi kesalahan.', 'error');
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="ti ti-upload me-1"></i> Upload Materi';
  }
});
</script>
@endpush
