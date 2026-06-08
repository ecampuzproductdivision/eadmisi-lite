@extends('layouts.app')

@section('content')
<main class="p-2">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-start border-success border-4" role="alert">
      <i class="ti ti-circle-check fs-4 me-2 text-success"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-start border-danger border-4" role="alert">
      <i class="ti ti-alert-triangle fs-4 me-2 text-danger"></i>
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="sticky-header-filter">
    <div class="row mb-2 align-items-center">
      <div class="col-md-6 col-12">
        <h3 class="mb-1 fw-bold text-dark">Negara</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
            <li class="breadcrumb-item active" aria-current="page">Negara</li>
          </ol>
        </nav>
      </div>
      <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
        <a href="{{ route('country.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
          <i class="ti ti-circle-plus fs-4"></i> Tambah
        </a>
      </div>
    </div>

    <!-- Filters & Search -->
    <div class="card mb-2 border-0 shadow-sm">
      <div class="card-body py-4">
        <form action="{{ route('country.index') }}" method="GET" class="row g-3">
          <div class="col-md-6 col-12">
            <div class="input-group">
              <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
              <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama negara atau kode..." value="{{ request('search') }}">
            </div>
          </div>
          <div class="col-md-4 col-12">
            <select name="status" class="form-select">
              <option value="">-- Semua Status --</option>
              <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
              <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
          </div>
          <div class="col-md-2 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary w-100"> <i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('country.index') }}" class="btn btn-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Countries Table -->
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle text-nowrap mb-0 table-hover table-dotted">
        <thead class="table-light">
          <tr>
            <th scope="col" class="py-3 fs-6">Nama Negara</th>
            <th scope="col" class="py-3 fs-6">Kode Negara (ISO 2)</th>
            <th scope="col" class="py-3 fs-6">Kode Negara (ISO 3)</th>
            <th scope="col" class="py-3 fs-6">Kode Telepon</th>
            <th scope="col" class="py-3 fs-6">Status</th>
            <th scope="col" class="py-3 text-end fs-6">Aksi</th>
          </tr>
        </thead>
        <tbody id="country-table-body">
          @if($countries->isEmpty())
            <tr>
              <td colspan="6" class="text-center py-5">
                <i class="ti ti-world-off text-muted" style="font-size: 3rem;"></i>
                <p class="mt-2 mb-0 text-muted">Tidak ada data negara ditemukan.</p>
              </td>
            </tr>
          @else
            @include('references.country.partials.country_rows')
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
      @if($countries->hasPages())
        <div class="card-footer bg-white border-0 py-3">
          {{ $countries->links() }}
        </div>
      @endif
    </div>
  </div>
</main>

<style>
/* Table dotted styles consistent with premium mock design */
.table-dotted tbody tr {
  border-bottom: 1px dotted #dee2e6 !important;
}
.table-dotted tbody tr:last-child {
  border-bottom: none !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // 1. Setup toggle listener registration helper
  function registerStatusToggle(toggle) {
    if (toggle.hasAttribute('data-registered')) return;
    toggle.setAttribute('data-registered', 'true');

    toggle.addEventListener('change', function() {
      const url = this.getAttribute('data-url');
      const label = this.parentElement.querySelector('.status-label');
      
      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          label.textContent = data.status === 'active' ? 'Aktif' : 'Nonaktif';
          if (data.status === 'active') {
            label.className = 'form-check-label status-label text-success fw-semibold small ms-1';
          } else {
            label.className = 'form-check-label status-label text-danger fw-semibold small ms-1';
          }
        } else {
          alert(data.message || 'Gagal mengubah status negara.');
          this.checked = !this.checked;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengubah status negara.');
        this.checked = !this.checked;
      });
    });
  }

  // Register initial toggles
  document.querySelectorAll('.status-toggle').forEach(registerStatusToggle);

  // 2. Setup Infinite Scroll parameters
  let nextPageUrl = '{{ $countries->nextPageUrl() }}';
  let hasMore = {{ $countries->hasMorePages() ? 'true' : 'false' }};
  let isLoading = false;

  const spinner = document.getElementById('loading-spinner');
  const paginationContainer = document.getElementById('pagination-container');
  const tableBody = document.getElementById('country-table-body');

  // Hide traditional pagination links if JavaScript runs for seamless UX
  if (paginationContainer) {
    paginationContainer.classList.add('d-none');
  }

  // Scroll listener function
  function handleScroll() {
    if (isLoading || !hasMore || !nextPageUrl) return;

    // Trigger when user scrolls within 100px of the page bottom
    if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 100) {
      loadMoreCountries();
    }
  }

  // Fetch next set of countries via AJAX
  function loadMoreCountries() {
    isLoading = true;
    spinner.classList.remove('d-none');

    fetch(nextPageUrl, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      isLoading = false;
      spinner.classList.add('d-none');

      if (data.html) {
        // Append newly fetched rows
        const tempDiv = document.createElement('tbody');
        tempDiv.innerHTML = data.html;
        
        const rows = tempDiv.querySelectorAll('tr');
        rows.forEach(row => {
          tableBody.appendChild(row);
          // Register dynamic toggle listener on newly loaded element
          const toggle = row.querySelector('.status-toggle');
          if (toggle) registerStatusToggle(toggle);
        });
      }

      // Update next page pointers and release loading lock
      nextPageUrl = data.next_page;
      hasMore = data.has_more;
    })
    .catch(error => {
      console.error('Error fetching countries:', error);
      isLoading = false;
      spinner.classList.add('d-none');
    });
  }

  // Register scroll listener on window
  window.addEventListener('scroll', handleScroll);
});
</script>
@endsection
