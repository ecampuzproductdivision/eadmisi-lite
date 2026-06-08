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
  <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%); border-radius: 20px; overflow: hidden; position: relative;">
    <div style="position:absolute;top:0;right:0;width:300px;height:200px;background:rgba(224,231,255,0.06);border-radius:50%;transform:translate(30%,-30%);"></div>
    <div style="position:absolute;bottom:0;left:20%;width:200px;height:150px;background:rgba(99,102,241,0.08);border-radius:50%;"></div>
    <div class="card-body p-4 p-md-5 text-white position-relative">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="d-flex align-items-center justify-content-center rounded-3" style="width:56px;height:56px;background:rgba(255,255,255,0.1);backdrop-filter:blur(10px);">
              <i class="ti ti-checkbox fs-1 text-warning"></i>
            </div>
            <div>
              <span class="badge mb-1" style="background:rgba(255,255,255,0.1);color:#c7d2fe;border:1px solid rgba(255,255,255,0.2);border-radius:20px;font-size:0.72rem;">Penjaminan Mutu Akademik</span>
              <h2 class="fw-bold text-white mb-0" style="font-size:1.6rem;">Review &amp; Persetujuan RPS</h2>
            </div>
          </div>
          <p class="text-white-50 mb-0" style="max-width:520px;line-height:1.6;">
            Lakukan evaluasi kelayakan dokumen Rencana Pembelajaran Semester (RPS), berikan rekomendasi perbaikan, dan tetapkan keputusan mutu.
          </p>
        </div>
        <div class="col-lg-5 mt-4 mt-lg-0">
          <div class="row g-3">
            <div class="col-4">
              <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);">
                <div class="fw-bold fs-2 text-warning">{{ $stats['MENUNGGU_REVIEW'] }}</div>
                <div class="small text-white-50" style="font-size: 0.72rem;">Menunggu</div>
              </div>
            </div>
            <div class="col-4">
              <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);">
                <div class="fw-bold fs-2 text-info">{{ $stats['DALAM_REVIEW'] }}</div>
                <div class="small text-white-50" style="font-size: 0.72rem;">Dalam Review</div>
              </div>
            </div>
            <div class="col-4">
              <div class="text-center p-3 rounded-3" style="background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);">
                <div class="fw-bold fs-2 text-success">{{ $stats['DISETUJUI'] }}</div>
                <div class="small text-white-50" style="font-size: 0.72rem;">Disetujui</div>
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
  <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-3 p-md-4">
      <form action="{{ route('rps-review.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3 col-12">
          <label class="form-label small text-muted fw-bold">Cari Mata Kuliah</label>
          <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
            <input type="text" name="search" class="form-control border-start-0" placeholder="Kode atau nama MK..." value="{{ request('search') }}">
          </div>
        </div>
        <div class="col-md-2 col-12">
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
        <div class="col-md-2 col-12">
          <label class="form-label small text-muted fw-bold">Status</label>
          <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">-- Semua Status --</option>
            <option value="MENUNGGU_REVIEW" {{ request('status') === 'MENUNGGU_REVIEW' ? 'selected' : '' }}>Menunggu Review</option>
            <option value="DALAM_REVIEW" {{ request('status') === 'DALAM_REVIEW' ? 'selected' : '' }}>Dalam Review</option>
            <option value="DISETUJUI" {{ request('status') === 'DISETUJUI' ? 'selected' : '' }}>Disetujui</option>
            <option value="DIKEMBALIKAN" {{ request('status') === 'DIKEMBALIKAN' ? 'selected' : '' }}>Dikembalikan</option>
            <option value="DITOLAK" {{ request('status') === 'DITOLAK' ? 'selected' : '' }}>Ditolak</option>
          </select>
        </div>
        <div class="col-md-2 col-12 d-flex gap-2">
          <button type="submit" class="btn btn-dark w-100 py-2"><i class="ti ti-filter me-1"></i> Filter</button>
          <a href="{{ route('rps-review.index') }}" class="btn btn-light py-2 px-3" title="Reset"><i class="ti ti-refresh"></i></a>
        </div>
      </form>
    </div>
  </div>

  {{-- ============================================================ --}}
  {{-- QUEUE TABLE                                                  --}}
  {{-- ============================================================ --}}
  <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
    <div class="table-responsive no-sticky-global">
      <table class="table no-sticky-global align-middle mb-0" style="min-width: 800px;">
        <thead class="table-light">
          <tr>
            <th class="ps-4 py-3 text-muted fw-bold text-uppercase fs-7" style="width: 250px;">Mata Kuliah &amp; Prodi</th>
            <th class="py-3 text-muted fw-bold text-uppercase fs-7">Koordinator</th>
            <th class="py-3 text-muted fw-bold text-uppercase fs-7 text-center">Iterasi</th>
            <th class="py-3 text-muted fw-bold text-uppercase fs-7">Tanggal Submit</th>
            <th class="py-3 text-muted fw-bold text-uppercase fs-7">Status</th>
            <th class="pe-4 py-3 text-muted fw-bold text-uppercase fs-7 text-end" style="width: 150px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @if($rpsList->isEmpty())
            <tr>
              <td colspan="6" class="text-center py-5">
                <i class="ti ti-checkbox fs-1 text-muted d-block mb-3" style="font-size: 3.5rem !important;"></i>
                <h6 class="fw-bold text-dark">Antrean Review Kosong</h6>
                <p class="text-muted small mb-0">Tidak ada RPS yang memerlukan review saat ini.</p>
              </td>
            </tr>
          @else
            @foreach($rpsList as $rps)
              @php
                $statusColors = [
                  'MENUNGGU_REVIEW' => ['bg' => '#fef3c7', 'text' => '#d97706', 'border' => '#fcd34d'],
                  'DALAM_REVIEW'    => ['bg' => '#e0f2fe', 'text' => '#0284c7', 'border' => '#bae6fd'],
                  'DISETUJUI'       => ['bg' => '#d1fae5', 'text' => '#059669', 'border' => '#a7f3d0'],
                  'DIKEMBALIKAN'    => ['bg' => '#fee2e2', 'text' => '#dc2626', 'border' => '#fecaca'],
                  'DITOLAK'         => ['bg' => '#f3f4f6', 'text' => '#4b5563', 'border' => '#e5e7eb'],
                ];
                $sc = $statusColors[$rps->status] ?? ['bg' => '#f3f4f6', 'text' => '#4b5563', 'border' => '#e5e7eb'];
                $activeReview = $rps->reviews->first();
                $iterasi = $activeReview ? $activeReview->iterasi_ke : 1;
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
                  <span class="badge bg-dark rounded-circle px-2 py-1" style="font-size: 0.75rem;">Ke-{{ $iterasi }}</span>
                </td>
                <td class="py-3 text-muted small">
                  {{ $rps->tanggal_submit_review ? \Carbon\Carbon::parse($rps->tanggal_submit_review)->translatedFormat('d M Y H:i') : '-' }}
                </td>
                <td class="py-3">
                  <span class="badge px-3 py-2 border rounded-pill" style="background-color: {{ $sc['bg'] }}; color: {{ $sc['text'] }}; border-color: {{ $sc['border'] }} !important; font-size: 0.72rem;">
                    {{ str_replace('_', ' ', $rps->status) }}
                  </span>
                </td>
                <td class="pe-4 py-3 text-end">
                  @if($rps->status === 'MENUNGGU_REVIEW')
                    <form action="{{ route('rps-review.start', $rps->id_rps) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-warning btn-sm fw-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="ti ti-player-play me-1"></i> Mulai Review
                      </button>
                    </form>
                  @else
                    <a href="{{ route('rps-review.show', $rps->id_rps) }}" class="btn btn-dark btn-sm fw-bold px-3 py-2" style="border-radius: 8px;">
                      <i class="ti ti-zoom-in me-1"></i> Kelola Review
                    </a>
                  @endif
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
