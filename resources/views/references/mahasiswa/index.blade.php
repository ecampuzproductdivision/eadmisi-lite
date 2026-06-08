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
  <!-- Mahasiswa Table -->
  <div class="card border-0 shadow-sm">
    <div class="sticky-header-filter">
      <div class="card-header border-bottom-0 py-4">
        <div class="row mb-2 align-items-center mb-6">
          <div class="col-md-6 col-12">
            <h3 class="mb-1 fw-bold mt-2">Mahasiswa</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                <li class="breadcrumb-item active" aria-current="page">Mahasiswa</li>
              </ol>
            </nav>
          </div>
          <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end gap-2">
            <a href="{{ route('mahasiswa.rekap') }}" class="btn btn-outline-dark d-inline-flex align-items-center gap-2">
              <i class="ti ti-chart-pie fs-4"></i> Rekap
            </a>
            <a href="{{ route('mahasiswa.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
              <i class="ti ti-circle-plus fs-4"></i> Tambah
            </a>
          </div>
        </div>
          <!-- Filters & Search -->
          <form action="{{ route('mahasiswa.index') }}" method="GET" class="row g-3">
            <div class="col-md-4 col-12">
              <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari NIM, nama, atau email..." value="{{ request('search') }}">
              </div>
            </div>
            <div class="col-md-3 col-12">
              <select name="id_prodi" class="form-select">
                <option value="">-- Semua Prodi --</option>
                @foreach($prodiList as $p)
                  <option value="{{ $p->prodiKode }}" {{ request('id_prodi') == $p->prodiKode ? 'selected' : '' }}>
                    {{ $p->prodiNamaResmi }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 col-12">
              <select name="status_mahasiswa" class="form-select">
                <option value="">-- Semua Status --</option>
                @foreach(['Aktif', 'Cuti', 'Tugas Belajar', 'Non-aktif', 'DO', 'Lulus', 'Mengundurkan Diri'] as $s)
                  <option value="{{ $s }}" {{ request('status_mahasiswa') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2 col-12 d-flex gap-2">
              <button type="submit" class="btn btn-primary flex-grow-1"> <i class="ti ti-filter"></i> Terapkan</button>
              <a href="{{ route('mahasiswa.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
            </div>
          </form>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table align-middle text-nowrap mb-0 table-hover table-dotted">
        <thead class="table-light">
          <tr>
            <th scope="col" class="fw-semibold text-center" width="50px">No</th>
            <th scope="col" class="fw-semibold text-center" width="50px">Aksi</th>
            <th scope="col" class="fw-semibold">NIM / Nama</th>
            <th scope="col" class="fw-semibold">Program Studi</th>
            <th scope="col" class="fw-semibold">Angkatan</th>
            <th scope="col" class="fw-semibold">IPK</th>
            <th scope="col" class="fw-semibold text-center">Status</th>
          </tr>
        </thead>
        <tbody id="mahasiswa-table-body">
          @if($mahasiswas->isEmpty())
            <tr class="empty-row">
              <td colspan="7" class="text-center py-5">
                <p class="mt-2 mb-0 text-muted">Tidak ada data mahasiswa ditemukan.</p>
              </td>
            </tr>
          @else
            @include('references.mahasiswa.partials.mahasiswa_rows')
          @endif
        </tbody>
      </table>
    </div>

    <!-- Spinner loading indicator -->
    <div id="loading-spinner" class="text-center py-4 d-none">
      <div class="spinner-border text-danger" role="status" style="width: 2rem; height: 2rem;">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    
    <!-- Info Displayed Data Count -->
    <div class="card-footer bg-white border-top py-3">
      <p class="text-muted mb-0 small">
        Menampilkan <span id="displayed-count" class="fw-semibold">{{ $mahasiswas->count() }}</span> data dari total <span id="total-count" class="fw-semibold">{{ $mahasiswas->total() }}</span> data
      </p>
    </div>

    <!-- Fallback Pagination Container -->
    <div id="pagination-container">
      @if($mahasiswas->hasPages())
        <div class="card-footer bg-white border-0 py-3">
          {{ $mahasiswas->links() }}
        </div>
      @endif
    </div>
  </div>
</main>

<style>
.table-dotted tbody tr {
  border-bottom: 1px dotted #dee2e6 !important;
}
.table-dotted tbody tr:last-child {
  border-bottom: none !important;
}
.avatar-text {
  width: 40px;
  height: 40px;
  background-color: #f1f5f9;
  color: #64748b;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  font-weight: 600;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  let nextPageUrl = '{{ $mahasiswas->nextPageUrl() }}';
  let hasMore = {{ $mahasiswas->hasMorePages() ? 'true' : 'false' }};
  let isLoading = false;

  const spinner = document.getElementById('loading-spinner');
  const paginationContainer = document.getElementById('pagination-container');
  const tableBody = document.getElementById('mahasiswa-table-body');

  if (paginationContainer) {
    paginationContainer.classList.add('d-none');
  }

  function handleScroll() {
    if (isLoading || !hasMore || !nextPageUrl) return;
    if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 100) {
      loadMore();
    }
  }

  function loadMore() {
    isLoading = true;
    spinner.classList.remove('d-none');

    fetch(nextPageUrl, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
      isLoading = false;
      spinner.classList.add('d-none');

      if (data.html) {
        const tempDiv = document.createElement('tbody');
        tempDiv.innerHTML = data.html;
        const rows = tempDiv.querySelectorAll('tr');
        rows.forEach(row => tableBody.appendChild(row));

        const displayedCountEl = document.getElementById('displayed-count');
        if (displayedCountEl) {
          displayedCountEl.textContent = tableBody.querySelectorAll('tr:not(.empty-row)').length;
        }
      }

      nextPageUrl = data.next_page;
      hasMore = data.has_more;
    })
    .catch(error => {
      console.error('Error fetching data:', error);
      isLoading = false;
      spinner.classList.add('d-none');
    });
  }

  window.addEventListener('scroll', handleScroll);
});
</script>
@endsection
