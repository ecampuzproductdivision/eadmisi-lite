@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-md-6 col-12">
      <h1 class="mb-1 fw-bold">Daftar Paket Soal</h1>
      <p class="mb-0 text-muted">Kelola paket soal ujian untuk seluruh jalur pendaftaran.</p>
    </div>
    <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
      <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahPaket">
        <i class="ti ti-plus fs-4"></i> Tambah Paket Soal
      </button>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-circle fs-4 me-2"></i>
      <strong>Terjadi kesalahan:</strong>
      <ul class="mb-0 mt-1">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body p-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="bg-light">
            <tr>
              <th style="width: 50px;">No</th>
              <th>Nama Paket</th>
              <th style="width: 80px;" class="text-center">Total Soal</th>
              <th style="width: 80px;" class="text-center">Total Skor</th>
              <th style="width: 100px;">Status</th>
              <th style="width: 320px;">Aksi</th>
            </tr>
          </thead>
          <tbody id="paket-table-body">
            @if($pakets->isEmpty())
              <tr>
                <td colspan="6" class="text-center py-5">
                  <i class="ti ti-zoom-question text-muted" style="font-size: 3rem;"></i>
                  <p class="mt-3 mb-0 text-muted">Belum ada paket soal.</p>
                  <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modalTambahPaket">Tambah Paket Soal Pertama</button>
                </td>
              </tr>
            @else
              @include('soal-ujian.partials.paket_rows')
            @endif
          </tbody>
        </table>
      </div>

      <div id="loading-spinner" class="text-center py-4 d-none">
        <div class="spinner-border text-danger" role="status" style="width: 2rem; height: 2rem;">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

      <div id="pagination-container">
        @if($pakets->hasPages())
          <div class="mt-3">
            {{ $pakets->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
</main>

<!-- Modal Tambah Paket -->
<div class="modal fade" id="modalTambahPaket" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Tambah Paket Soal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form action="{{ route('paket-soal.store') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label>
            <input type="text" name="nama_paket" class="form-control" placeholder="Contoh: Paket Ujian SNBT 2026" required maxlength="200">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="form-control" placeholder="Deskripsi paket soal..."></textarea>
          </div>

          <div class="mt-4 d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
              <i class="ti ti-device-floppy fs-4"></i> Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Package (rendered per row) is inside paket_rows.blade.php -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  let nextPageUrl = '{{ $pakets->nextPageUrl() }}';
  let hasMore = {{ $pakets->hasMorePages() ? 'true' : 'false' }};
  let isLoading = false;

  const spinner = document.getElementById('loading-spinner');
  const paginationContainer = document.getElementById('pagination-container');
  const tableBody = document.getElementById('paket-table-body');

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