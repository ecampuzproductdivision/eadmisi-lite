@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-md-6 col-12">
      <h1 class="mb-1 fw-bold">Jalur Pendaftaran</h1>
      <p class="mb-0 text-muted">Kelola jalur penerimaan mahasiswa baru, biaya, dan jadwal pendaftaran.</p>
    </div>
    <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
      <a href="{{ route('registration-paths.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <i class="ti ti-plus fs-4"></i> Tambah Jalur Baru
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
              <th>Nama Jalur</th>
              <th>Kategori</th>
              <th>Form Template</th>
              <th>Biaya</th>
              <th>Periode</th>
              <th>Kuota</th>
              <th>Status</th>
              <th style="width: 150px;">Aksi</th>
            </tr>
          </thead>
          <tbody id="paths-table-body">
            @if($paths->isEmpty())
              <tr>
                <td colspan="10" class="text-center py-5">
                  <i class="ti ti-road-off text-muted" style="font-size: 3rem;"></i>
                  <p class="mt-3 mb-0 text-muted">Belum ada jalur pendaftaran.</p>
                  <a href="{{ route('registration-paths.create') }}" class="btn btn-primary mt-3">Tambah Jalur Pertama</a>
                </td>
              </tr>
            @else
              @include('registration-paths.partials.path_rows')
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

      <!-- Fallback Pagination Container -->
      <div id="pagination-container">
        @if($paths->hasPages())
          <div class="mt-3">
            {{ $paths->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
</main>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  let nextPageUrl = '{{ $paths->nextPageUrl() }}';
  let hasMore = {{ $paths->hasMorePages() ? 'true' : 'false' }};
  let isLoading = false;

  const spinner = document.getElementById('loading-spinner');
  const paginationContainer = document.getElementById('pagination-container');
  const tableBody = document.getElementById('paths-table-body');

  // Hide traditional pagination links if JavaScript runs for seamless UX
  if (paginationContainer) {
    paginationContainer.classList.add('d-none');
  }

  // Scroll listener function
  function handleScroll() {
    if (isLoading || !hasMore || !nextPageUrl) return;

    // Trigger when user scrolls within 100px of the page bottom
    if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 100) {
      loadMorePaths();
    }
  }

  // Fetch next set of pages via AJAX
  function loadMorePaths() {
    isLoading = true;
    spinner.classList.remove('d-none');

    fetch(nextPageUrl, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.html) {
        // Append newly fetched rows using safe element selection
        const tempDiv = document.createElement('tbody');
        tempDiv.innerHTML = data.html;
        
        const rows = tempDiv.querySelectorAll('tr');
        rows.forEach(row => {
          tableBody.appendChild(row);
        });
      }

      // Update next page pointers and release loading lock
      nextPageUrl = data.next_page;
      hasMore = data.has_more;
      isLoading = false;
      spinner.classList.add('d-none');
    })
    .catch(error => {
      console.error('Error fetching paths:', error);
      isLoading = false;
      spinner.classList.add('d-none');
    });
  }

  // Register scroll listener on window
  window.addEventListener('scroll', handleScroll);
});
</script>
@endpush
@endsection