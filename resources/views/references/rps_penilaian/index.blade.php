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
  <div class="card border-1 shadow-sm mb-4" style="background: linear-gradient(135deg, #090d16 0%, #1e1b4b 50%, #1e3a8a 100%); border-radius: 20px; overflow: hidden; position: relative;">
    <div style="position:absolute;top:0;right:0;width:300px;height:200px;background:rgba(99,102,241,0.06);border-radius:50%;transform:translate(30%,-30%);"></div>
    <div style="position:absolute;bottom:0;left:20%;width:200px;height:150px;background:rgba(59,130,246,0.05);border-radius:50%;"></div>
    <div class="card-body p-4 p-md-5 text-white position-relative">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="d-flex align-items-center justify-content-center rounded-3" style="width:56px;height:56px;background:rgba(255,255,255,0.1);backdrop-filter:blur(10px);">
              <i class="ti ti-report-analytics fs-1 text-primary"></i>
            </div>
            <div>
              <span class="badge mb-1" style="background:rgba(255,255,255,0.1);color:#c7d2fe;border:1px solid rgba(255,255,255,0.2);border-radius:20px;font-size:0.72rem;">Rencana Pembelajaran (RPS)</span>
              <h2 class="fw-bold text-white mb-0" style="font-size:1.6rem;">Metode &amp; Bobot Penilaian</h2>
            </div>
          </div>
          <p class="text-white-50 mb-0" style="max-width:520px;line-height:1.6;">
            Konfigurasikan instrumen penilaian, seimbangkan bobot kriteria asesmen, petakan komponen ke CPMK, serta atur skala kelulusan secara objektif.
          </p>
        </div>
        <div class="col-lg-5 mt-4 mt-lg-0">
          <div class="row g-3">
            <div class="col-6">
              <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);">
                <div class="fw-bold fs-2 text-white">{{ $rpsList->total() }}</div>
                <div class="small text-white-50">RPS Terdaftar</div>
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
      <form action="{{ route('rps-penilaian.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-4 col-12">
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
        <div class="col-md-2 col-12 d-flex gap-2">
          <button type="submit" class="btn btn-dark py-2"><i class="ti ti-filter me-1"></i> Filter</button>
          <a href="{{ route('rps-penilaian.index') }}" class="btn btn-light py-2 px-3" title="Reset"><i class="ti ti-refresh"></i></a>
        </div>
      </form>
    </div>
  </div>

  {{-- ============================================================ --}}
  {{-- COURSE TABLE                                                 --}}
  {{-- ============================================================ --}}
  <div class="card border-1 shadow-sm" style="border-radius: 16px; overflow: hidden;">
    <div class="table-responsive no-sticky-global">
      <table class="table no-sticky-global align-middle mb-0" style="min-width: 800px;">
        <thead class="table-light">
          <tr>
            <th class="ps-4 py-3 text-muted fw-bold text-uppercase fs-7" style="width: 250px;">Mata Kuliah &amp; Prodi</th>
            <th class="py-3 text-muted fw-bold text-uppercase fs-7">Koordinator</th>
            <th class="py-3 text-muted fw-bold text-uppercase fs-7 text-center">Komponen</th>
            <th class="py-3 text-muted fw-bold text-uppercase fs-7 text-center">Kelayakan Bobot</th>
            <th class="py-3 text-muted fw-bold text-uppercase fs-7">Status RPS</th>
            <th class="pe-4 py-3 text-muted fw-bold text-uppercase fs-7 text-end" style="width: 150px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @if($rpsList->isEmpty())
            <tr>
              <td colspan="6" class="text-center py-5">
                <i class="ti ti-report-analytics fs-1 text-muted d-block mb-3" style="font-size: 3.5rem !important;"></i>
                <h6 class="fw-bold text-dark">Tidak Ada Data RPS</h6>
                <p class="text-muted small mb-0">Belum ada penyusunan RPS pada semester/filter terpilih.</p>
              </td>
            </tr>
          @else
            @foreach($rpsList as $rps)
              @php
                $statusColors = [
                  'DRAFT' => ['bg' => '#fef3c7', 'text' => '#92400e', 'border' => '#fbbf24'],
                  'MENUNGGU_REVIEW' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'border' => '#60a5fa'],
                  'DISETUJUI' => ['bg' => '#d1fae5', 'text' => '#065f46', 'border' => '#34d399'],
                  'DIPUBLIKASIKAN' => ['bg' => '#d1fae5', 'text' => '#065f46', 'border' => '#10b981'],
                ];
                $sc = $statusColors[$rps->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151', 'border' => '#9ca3af'];
                $isWeightOk = abs($rps->total_bobot - 100.0) < 0.01;
              @endphp
              <tr>
                <td class="ps-4 py-3">
                  <div class="d-flex flex-column">
                    <span class="badge bg-light text-slate-800 align-self-start font-monospace mb-1" style="font-size: 0.72rem;">{{ $rps->kurikulumMataKuliah->mataKuliah->mk_kode }}</span>
                    <strong class="text-dark" style="font-size: 0.9rem;">{{ $rps->kurikulumMataKuliah->mataKuliah->mk_nama }}</strong>
                    <span class="text-muted small mt-1" style="font-size: 0.75rem;">{{ $rps->kurikulumMataKuliah->kurikulum->programStudi->prodiNamaResmi }}</span>
                  </div>
                </td>
                <td class="py-3">
                  <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                      {{ substr($rps->dosenKoordinator->nama_lengkap ?? 'D', 0, 1) }}
                    </div>
                    <div>
                      <div class="small fw-bold text-dark">{{ $rps->dosenKoordinator->nama_lengkap ?? '-' }}</div>
                      <div class="text-muted" style="font-size: 0.7rem;">NIDN: {{ $rps->dosenKoordinator->nidn ?? '-' }}</div>
                    </div>
                  </div>
                </td>
                <td class="py-3 text-center">
                  <span class="badge bg-dark rounded-circle px-2 py-1" style="font-size: 0.75rem;">{{ $rps->total_komponen }}</span>
                </td>
                <td class="py-3 text-center">
                  @if($isWeightOk)
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold" style="font-size: 0.72rem;">
                      <i class="ti ti-check me-1"></i> Seimbang (100%)
                    </span>
                  @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-bold" style="font-size: 0.72rem;">
                      <i class="ti ti-alert-triangle me-1"></i> Deviasi ({{ $rps->total_bobot }}%)
                    </span>
                  @endif
                </td>
                <td class="py-3">
                  <span class="badge px-3 py-2 border rounded-pill" style="background-color: {{ $sc['bg'] }}; color: {{ $sc['text'] }}; border-color: {{ $sc['border'] }} !important; font-size: 0.72rem;">
                    {{ str_replace('_', ' ', $rps->status) }}
                  </span>
                </td>
                <td class="pe-4 py-3 text-end">
                  <a href="{{ route('rps-penilaian.workspace', $rps->id_rps) }}" class="btn btn-dark btn-sm fw-bold px-3 py-2" style="border-radius: 8px;">
                    <i class="ti ti-layout-grid me-1"></i> Buka Workspace
                  </a>
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
    @if($rpsList->isNotEmpty())
      <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
        <p class="text-muted small mb-0">
          Menampilkan <strong>{{ $rpsList->firstItem() }}–{{ $rpsList->lastItem() }}</strong> dari <strong>{{ $rpsList->total() }}</strong> RPS
        </p>
        {{ $rpsList->links() }}
      </div>
    @endif
  </div>
</main>
@endsection
