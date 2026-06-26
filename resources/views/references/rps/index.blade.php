@extends('layouts.app')

@section('content')
<main class="p-4">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px; background: rgba(25, 135, 84, 0.1); color: #198754;">
      <div class="d-flex align-items-center">
        <i class="ti ti-circle-check fs-4 me-2"></i>
        <span>{{ session('success') }}</span>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px; background: rgba(220, 53, 69, 0.1); color: #dc3545;">
      <div class="d-flex align-items-center">
        <i class="ti ti-alert-triangle fs-4 me-2"></i>
        <span>{{ session('error') }}</span>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Header Banner -->
  <div class="card border-1 shadow-sm mb-4" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 16px;">
    <div class="card-body p-4 text-white">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h2 class="fw-bold mb-1 d-flex align-items-center gap-2" style="color: #ffffff !important;">
            <i class="ti ti-books text-warning fs-1"></i> 
            Penyusunan RPS
          </h2>
          <p class="text-white-50 mb-0">Rencana Pembelajaran Semester (RPS) berbasis Outcome-Based Education (OBE) untuk seluruh mata kuliah aktif.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
          <div class="badge bg-warning text-dark px-3 py-2 fs-7" style="border-radius: 30px;">
            <i class="ti ti-calendar-event me-1"></i>
            TA Aktif: {{ $selectedTa ? $selectedTa->nama_ta : '-' }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  @php
    $allRps = \App\Models\Rps::where('id_tahun_akademik', $selectedTaId ?? '')->get();
    $statTotal   = $courses->total();
    $statDraft   = $allRps->where('status','DRAFT')->count();
    $statReview  = $allRps->where('status','MENUNGGU_REVIEW')->count();
    $statPub     = $allRps->whereIn('status',['DISETUJUI','DIPUBLIKASIKAN','SELESAI'])->count();
    $statNone    = $statTotal - $allRps->count();
  @endphp
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card border-1 shadow-sm h-100" style="border-radius:14px; border-left: 4px solid #6366f1 !important;">
        <div class="card-body p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-3" style="width:44px;height:44px;background:rgba(99,102,241,0.1);">
              <i class="ti ti-books fs-3 text-primary"></i>
            </div>
            <div>
              <div class="fw-bold fs-4 text-dark">{{ $statTotal }}</div>
              <div class="text-muted small">Total Mata Kuliah</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-1 shadow-sm h-100" style="border-radius:14px; border-left: 4px solid #f59e0b !important;">
        <div class="card-body p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-3" style="width:44px;height:44px;background:rgba(245,158,11,0.1);">
              <i class="ti ti-edit-circle fs-3 text-warning"></i>
            </div>
            <div>
              <div class="fw-bold fs-4 text-dark">{{ $statDraft }}</div>
              <div class="text-muted small">Masih Draft</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-1 shadow-sm h-100" style="border-radius:14px; border-left: 4px solid #f97316 !important;">
        <div class="card-body p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-3" style="width:44px;height:44px;background:rgba(249,115,22,0.1);">
              <i class="ti ti-clock fs-3" style="color:#f97316;"></i>
            </div>
            <div>
              <div class="fw-bold fs-4 text-dark">{{ $statReview }}</div>
              <div class="text-muted small">Menunggu Review</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-1 shadow-sm h-100" style="border-radius:14px; border-left: 4px solid #22c55e !important;">
        <div class="card-body p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-3" style="width:44px;height:44px;background:rgba(34,197,94,0.1);">
              <i class="ti ti-circle-check fs-3 text-success"></i>
            </div>
            <div>
              <div class="fw-bold fs-4 text-dark">{{ $statPub }}</div>
              <div class="text-muted small">Disetujui / Publik</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter & Table Card -->
  <div class="card border-1 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
      <form action="{{ route('rps.index') }}" method="GET" class="row g-3 mb-4">
        <!-- Search -->
        <div class="col-md-3 col-12">
          <label class="form-label small text-muted fw-bold">Cari Mata Kuliah</label>
          <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
            <input type="text" name="search" class="form-control border-start-0" placeholder="Kode atau nama mata kuliah..." value="{{ request('search') }}">
          </div>
        </div>

        <!-- Filter Tahun Akademik -->
        <div class="col-md-3 col-12">
          <label class="form-label small text-muted fw-bold">Tahun Akademik</label>
          <select name="tahun_akademik_id" class="form-select" onchange="this.form.submit()">
            @foreach($taList as $ta)
              <option value="{{ $ta->id_tahun_akademik }}" {{ $selectedTaId == $ta->id_tahun_akademik ? 'selected' : '' }}>
                {{ $ta->nama_ta }} ({{ $ta->status }})
              </option>
            @endforeach
          </select>
        </div>

        <!-- Filter Prodi -->
        <div class="col-md-3 col-12">
          <label class="form-label small text-muted fw-bold">Program Studi</label>
          <select name="prodi" class="form-select" onchange="this.form.submit()">
            <option value="">-- Semua Program Studi --</option>
            @foreach($prodiList as $prodi)
              <option value="{{ $prodi->prodiKode }}" {{ request('prodi') == $prodi->prodiKode ? 'selected' : '' }}>
                {{ $prodi->prodiNamaResmi }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Submit & Reset -->
        <div class="col-md-2 col-12 d-flex align-items-end gap-2">
          <button type="submit" class="btn btn-dark py-2"><i class="ti ti-filter me-1"></i> Filter</button>
          <a href="{{ route('rps.index') }}" class="btn btn-light py-2 px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
      </form>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="border-collapse: separate; border-spacing: 0 10px; margin-top: -10px;">
          <thead>
            <tr class="text-secondary small fw-bold text-uppercase">
              <th class="border-0 px-4" width="60px">No</th>
              <th class="border-0">Mata Kuliah</th>
              <th class="border-0">Program Studi</th>
              <th class="border-0 text-center" width="80px">SKS</th>
              <th class="border-0 text-center" width="100px">Semester</th>
              <th class="border-0 text-center" width="180px">Status RPS</th>
              <th class="border-0 text-center" width="180px">Kelengkapan</th>
              <th class="border-0 text-end px-4" width="160px">Aksi</th>
            </tr>
          </thead>
          <tbody id="rps-table-body">
            @include('references.rps.partials.rps_rows')
          </tbody>
        </table>
      </div>

      <!-- Infinite Scroll Loading Indicator -->
      <div id="loading-indicator" class="text-center py-4 d-none">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

      @if($courses->isEmpty())
        <div class="text-center py-5">
          <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='120' height='120' viewBox='0 0 24 24' fill='none' stroke='%23cbd5e1' stroke-width='1.5'><path d='M19 11H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2z'/><path d='M12 2v4'/><path d='m5.2 5.2 2.8 2.8'/><path d='M18.8 5.2 16 8'/></svg>" class="mb-3" alt="Empty">
          <h5 class="fw-bold text-slate-700 mb-1">Mata Kuliah Tidak Ditemukan</h5>
          <p class="text-secondary mb-0">Belum ada mata kuliah yang terjadwal/aktif untuk filter terpilih.</p>
        </div>
      @endif

      <!-- Footer displayed count -->
      <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
        <p class="text-muted mb-0 small">
          Menampilkan <span id="displayed-count" class="fw-semibold text-dark">{{ $courses->count() }}</span> data dari total <span id="total-count" class="fw-semibold text-dark">{{ $courses->total() }}</span> data
        </p>
        <div>
          {{ $courses->links() }}
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script>
  let nextPageUrl = '{{ $courses->nextPageUrl() }}';
  let isLoading = false;
  let hasMore = {{ $courses->hasMorePages() ? 'true' : 'false' }};

  function loadMoreCourses() {
    if (isLoading || !hasMore || !nextPageUrl) return;

    isLoading = true;
    document.getElementById('loading-indicator').classList.remove('d-none');

    fetch(nextPageUrl, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      const tableBody = document.getElementById('rps-table-body');
      tableBody.insertAdjacentHTML('beforeend', data.html);
      nextPageUrl = data.next_page;
      hasMore = data.has_more;
      isLoading = false;
      document.getElementById('loading-indicator').classList.add('d-none');

      // Update count
      const displayedEl = document.getElementById('displayed-count');
      if (displayedEl) {
        displayedEl.textContent = tableBody.querySelectorAll('tr').length;
      }
    })
    .catch(error => {
      console.error('Error loading more courses:', error);
      isLoading = false;
      document.getElementById('loading-indicator').classList.add('d-none');
    });
  }

  // Infinite Scroll Trigger
  window.addEventListener('scroll', () => {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
      loadMoreCourses();
    }
  });
</script>
@endpush
