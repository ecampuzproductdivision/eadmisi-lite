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

  <!-- Prodi Table -->
  <div class="card border-0 shadow-sm">
    <div class="sticky-header-filter">
      <div class="card-body bg-white py-4">
        <div class="row mb-6 align-items-center">
          <div class="col-md-6 col-12">
            <h3 class="mb-1 mt-2 fw-bold">Program Studi</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                <li class="breadcrumb-item active" aria-current="page">Program Studi</li>
              </ol>
            </nav>
          </div>
          <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
            <a href="{{ route('prodi.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
              <i class="ti ti-circle-plus fs-4"></i> Tambah
            </a>
          </div>
        </div>

        <!-- Filters & Search -->
        <form action="{{ route('prodi.index') }}" method="GET" class="row g-3">
          <div class="col-md-4 col-12">
            <div class="input-group">
              <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
              <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama prodi atau kode..." value="{{ request('search') }}">
            </div>
          </div>
          <div class="col-md-2 col-12">
            <select name="fakultas" class="form-select">
              <option value="">-- Semua Fakultas --</option>
              @foreach($fakultasList as $fak)
                <option value="{{ $fak->fakKode }}" {{ request('fakultas') == $fak->fakKode ? 'selected' : '' }}>
                  {{ $fak->fakNama }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2 col-12">
            <select name="jenjang" class="form-select">
              <option value="">-- Semua Jenjang --</option>
              @foreach($jenjangList as $jjr)
                <option value="{{ $jjr->jjarKode }}" {{ request('jenjang') == $jjr->jjarKode ? 'selected' : '' }}>
                  {{ $jjr->jjarNama }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2 col-12">
            <select name="status" class="form-select">
              <option value="">-- Semua Status --</option>
              <option value="A" {{ request('status') == 'A' ? 'selected' : '' }}>Aktif</option>
              <option value="N" {{ request('status') == 'N' ? 'selected' : '' }}>Non-Aktif</option>
            </select>
          </div>
          <div class="col-md-2 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1"> <i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('prodi.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
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
            <th scope="col" class="fw-semibold">Kode PT</th>
            <th scope="col" class="fw-semibold">Kode Prodi</th>
            <th scope="col" class="fw-semibold">Nama Prodi</th>
            <th scope="col" class="fw-semibold text-center">Kampus Merdeka</th>
            <th scope="col" class="fw-semibold">Jenjang Studi</th>
          </tr>
        </thead>
        <tbody id="prodi-table-body">
          @if($programStudis->isEmpty())
            <tr class="empty-row">
              <td colspan="7" class="text-center py-5">
                <p class="mt-2 mb-0 text-muted">Tidak ada data program studi ditemukan.</p>
              </td>
            </tr>
          @else
            @include('references.prodi.partials.prodi_rows')
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
        Menampilkan <span id="displayed-count" class="fw-semibold">{{ $programStudis->count() }}</span> data dari total <span id="total-count" class="fw-semibold">{{ $programStudis->total() }}</span> data
      </p>
    </div>

    <!-- Fallback Pagination Container -->
    <div id="pagination-container">
      @if($programStudis->hasPages())
        <div class="card-footer bg-white border-0 py-3">
          {{ $programStudis->links() }}
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
          label.textContent = data.status === 'A' ? 'Aktif' : 'Non-Aktif';
          if (data.status === 'A') {
            label.className = 'form-check-label status-label text-success fw-semibold small ms-1';
          } else {
            label.className = 'form-check-label status-label text-danger fw-semibold small ms-1';
          }
        } else {
          alert(data.message || 'Gagal mengubah status prodi.');
          this.checked = !this.checked;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengubah status prodi.');
        this.checked = !this.checked;
      });
    });
  }

  // Register initial toggles
  document.querySelectorAll('.status-toggle').forEach(registerStatusToggle);

  // 2. Setup Infinite Scroll parameters
  let nextPageUrl = '{{ $programStudis->nextPageUrl() }}';
  let hasMore = {{ $programStudis->hasMorePages() ? 'true' : 'false' }};
  let isLoading = false;

  const spinner = document.getElementById('loading-spinner');
  const paginationContainer = document.getElementById('pagination-container');
  const tableBody = document.getElementById('prodi-table-body');

  if (paginationContainer) {
    paginationContainer.classList.add('d-none');
  }

  function handleScroll() {
    if (isLoading || !hasMore || !nextPageUrl) return;

    if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 100) {
      loadMoreProdis();
    }
  }

  function loadMoreProdis() {
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
        const tempDiv = document.createElement('tbody');
        tempDiv.innerHTML = data.html;
        
        const rows = tempDiv.querySelectorAll('tr');
        rows.forEach(row => {
          tableBody.appendChild(row);
          const toggle = row.querySelector('.status-toggle');
          if (toggle) registerStatusToggle(toggle);
        });

        // Update dynamic count indicator
        const displayedCountEl = document.getElementById('displayed-count');
        if (displayedCountEl) {
          displayedCountEl.textContent = tableBody.querySelectorAll('tr:not(.empty-row)').length;
        }
      }

      nextPageUrl = data.next_page;
      hasMore = data.has_more;
    })
    .catch(error => {
      console.error('Error fetching prodis:', error);
      isLoading = false;
      spinner.classList.add('d-none');
    });
  }

  window.addEventListener('scroll', handleScroll);
});
</script>
@endsection
