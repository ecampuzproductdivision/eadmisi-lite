@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-md-6 col-12">
      <h1 class="mb-1 fw-bold">Master Program Studi</h1>
      <p class="mb-0 text-muted">Kelola data program studi untuk penerimaan mahasiswa baru.</p>
    </div>
    <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
      <a href="{{ route('program-studi.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <i class="ti ti-plus fs-4"></i> Tambah Program Studi
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body p-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="bg-light">
            <tr>
              <th style="width: 60px;">No</th>
              <th>Kode Prodi</th>
              <th>Nama Prodi</th>
              <th>Jurusan</th>
              <th>Jenjang</th>
              <th>Program</th>
              <th>Kelompok</th>
              <th>Status</th>
              <th style="width: 150px;">Aksi</th>
            </tr>
          </thead>
          <tbody id="prodi-table-body">
            @if($programStudis->isEmpty())
              <tr>
                <td colspan="9" class="text-center py-5">
                  <i class="ti ti-book-off text-muted" style="font-size: 3rem;"></i>
                  <p class="mt-3 mb-0 text-muted">Belum ada program studi.</p>
              <a href="{{ route('program-studi.create') }}" class="btn btn-primary mt-3">Tambah Program Studi Pertama</a>
                </td>
              </tr>
            @else
              @include('program-studi.partials.prodi_rows')
            @endif
          </tbody>
        </table>
      </div>

      <!-- Loading spinner -->
      <div id="loading-spinner" class="text-center py-4 d-none">
        <div class="spinner-border text-danger" role="status" style="width: 2rem; height: 2rem;">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

      <!-- Pagination -->
      <div id="pagination-container">
        @if($programStudis->hasPages())
          <div class="mt-3">
            {{ $programStudis->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
</main>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  let nextPageUrl = '{{ $programStudis->nextPageUrl() }}';
  let hasMore = {{ $programStudis->hasMorePages() ? 'true' : 'false' }};
  let isLoading = false;

  const spinner = document.getElementById('loading-spinner');
  const paginationContainer = document.getElementById('pagination-container');
  const tableBody = document.getElementById('prodi-table-body');

  // Hide traditional pagination links if JS runs
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
      if (data.html) {
        const tempDiv = document.createElement('tbody');
        tempDiv.innerHTML = data.html;
        const rows = tempDiv.querySelectorAll('tr');
        rows.forEach(row => tableBody.appendChild(row));
      }
      nextPageUrl = data.next_page;
      hasMore = data.has_more;
      isLoading = false;
      spinner.classList.add('d-none');
    })
    .catch(error => {
      console.error('Error:', error);
      isLoading = false;
      spinner.classList.add('d-none');
    });
  }

  window.addEventListener('scroll', handleScroll);
});
</script>
@endpush
@endsection