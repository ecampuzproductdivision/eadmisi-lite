@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $mahasiswas])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Master Data', 'url' => '#'],
        ['label' => 'Mahasiswa', 'active' => true],
    ])
    @slot('title', 'Mahasiswa')
    @slot('description', 'Kelola data mahasiswa, NIM, program studi, dan status.')
    @slot('actions')
        <a href="{{ route('mahasiswa.rekap') }}" class="btn btn-outline-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-chart-pie fs-4"></i> Rekap
        </a>
        <a href="{{ route('mahasiswa.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Tambah
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-4 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari NIM, nama, atau email..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="id_prodi" class="form-select">
                <option value="">-- Prodi --</option>
                @foreach($prodiList as $p)
                    <option value="{{ $p->prodiKode }}" {{ request('id_prodi') == $p->prodiKode ? 'selected' : '' }}>{{ $p->prodiNamaResmi }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 col-12">
            <select name="status_mahasiswa" class="form-select">
                <option value="">-- Status --</option>
                @foreach(['Aktif', 'Cuti', 'Tugas Belajar', 'Non-aktif', 'DO', 'Lulus', 'Mengundurkan Diri'] as $s)
                    <option value="{{ $s }}" {{ request('status_mahasiswa') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('mahasiswa.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('mahasiswa.index') }}?export=xls'">
            <i class="ti ti-file-spreadsheet"></i> .xls
        </a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
            <i class="ti ti-printer"></i> Print
        </a>
    @endslot
    @slot('table')
        <table class="table align-middle text-nowrap mb-0 table-hover table-dotted table-ead">
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
                        <td colspan="7" class="text-center py-5"><p class="mt-2 mb-0 text-muted">Tidak ada data mahasiswa ditemukan.</p></td>
                    </tr>
                @else
                    @include('references.mahasiswa.partials.mahasiswa_rows')
                @endif
            </tbody>
        </table>
        <div class="card-footer bg-white border-top py-3">
            <p class="text-muted mb-0 small">
                Menampilkan <span id="displayed-count" class="fw-semibold">{{ $mahasiswas->count() }}</span> data dari total <span id="total-count" class="fw-semibold">{{ $mahasiswas->total() }}</span> data
            </p>
        </div>
        <div id="pagination-container">
            @if($mahasiswas->hasPages())
                <div class="card-footer bg-white border-0 py-3">{{ $mahasiswas->links() }}</div>
            @endif
        </div>
    @endslot
@endcomponent

<style>
.table-dotted tbody tr { border-bottom: 1px dotted #dee2e6 !important; }
.table-dotted tbody tr:last-child { border-bottom: none !important; }
.avatar-text { width: 40px; height: 40px; background-color: #f1f5f9; color: #64748b; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 600; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let nextPageUrl = '{{ $mahasiswas->nextPageUrl() }}';
    let hasMore = {{ $mahasiswas->hasMorePages() ? 'true' : 'false' }};
    let isLoading = false;
    const spinner = document.getElementById('loading-spinner');
    const paginationContainer = document.getElementById('pagination-container');
    const tableBody = document.getElementById('mahasiswa-table-body');
    if (paginationContainer) paginationContainer.classList.add('d-none');

    function handleScroll() {
        if (isLoading || !hasMore || !nextPageUrl) return;
        if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 100) loadMore();
    }

    function loadMore() {
        isLoading = true; if (spinner) spinner.classList.remove('d-none');
        fetch(nextPageUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.json())
        .then(data => {
            isLoading = false; if (spinner) spinner.classList.add('d-none');
            if (data.html) {
                const tempDiv = document.createElement('tbody');
                tempDiv.innerHTML = data.html;
                tempDiv.querySelectorAll('tr').forEach(row => tableBody.appendChild(row));
                const el = document.getElementById('displayed-count');
                if (el) el.textContent = tableBody.querySelectorAll('tr:not(.empty-row)').length;
            }
            nextPageUrl = data.next_page; hasMore = data.has_more;
        })
        .catch(error => { console.error('Error:', error); isLoading = false; if (spinner) spinner.classList.add('d-none'); });
    }
    window.addEventListener('scroll', handleScroll);
});
</script>
@endpush
@endsection