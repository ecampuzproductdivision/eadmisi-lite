@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $tahunAkademiks])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Master Data', 'url' => '#'],
        ['label' => 'Tahun Akademik', 'active' => true],
    ])
    @slot('title', 'Tahun Akademik')
    @slot('description', 'Kelola data tahun akademik, semester, dan status aktivasi.')
    @slot('actions')
        <a href="{{ route('tahun-akademik.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Tambah
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-3 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari kode atau nama TA..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2 col-12">
            <select name="status" class="form-select">
                <option value="">-- Status --</option>
                <option value="PERSIAPAN" {{ request('status') == 'PERSIAPAN' ? 'selected' : '' }}>Persiapan</option>
                <option value="AKTIF" {{ request('status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                <option value="SELESAI" {{ request('status') == 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                <option value="DIARSIPKAN" {{ request('status') == 'DIARSIPKAN' ? 'selected' : '' }}>Diarsipkan</option>
            </select>
        </div>
        <div class="col-md-2 col-12">
            <select name="jenis_semester" class="form-select">
                <option value="">-- Semester --</option>
                <option value="GANJIL" {{ request('jenis_semester') == 'GANJIL' ? 'selected' : '' }}>Ganjil</option>
                <option value="GENAP" {{ request('jenis_semester') == 'GENAP' ? 'selected' : '' }}>Genap</option>
                <option value="PENDEK" {{ request('jenis_semester') == 'PENDEK' ? 'selected' : '' }}>Pendek</option>
            </select>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-white border"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('tahun-akademik.index') }}" class="btn btn-white border px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('tahun-akademik.index') }}?export=xls'">
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
                    <th scope="col" class="fw-semibold">Kode TA</th>
                    <th scope="col" class="fw-semibold">Nama TA</th>
                    <th scope="col" class="fw-semibold">Semester</th>
                    <th scope="col" class="fw-semibold">Tgl Mulai</th>
                    <th scope="col" class="fw-semibold">Tgl Selesai</th>
                    <th scope="col" class="fw-semibold">Status</th>
                    <th scope="col" class="fw-semibold text-center">Minggu</th>
                </tr>
            </thead>
            <tbody id="ta-table-body">
                @if($tahunAkademiks->isEmpty())
                    <tr class="empty-row">
                        <td colspan="9" class="text-center py-5">
                            <p class="mt-2 mb-0 text-muted">Tidak ada data Tahun Akademik ditemukan.</p>
                        </td>
                    </tr>
                @else
                    @include('references.tahun-akademik.partials.ta_rows')
                @endif
            </tbody>
        </table>
        <div class="card-footer bg-white border-top py-3">
            <p class="text-muted mb-0 small">
                Menampilkan <span id="displayed-count" class="fw-semibold">{{ $tahunAkademiks->count() }}</span> data dari total <span id="total-count" class="fw-semibold">{{ $tahunAkademiks->total() }}</span> data
            </p>
        </div>
        <div id="pagination-container">
            @if($tahunAkademiks->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $tahunAkademiks->links() }}
                </div>
            @endif
        </div>
    @endslot
@endcomponent

<style>
.table-dotted tbody tr {
    border-bottom: 1px dotted #dee2e6 !important;
}
.table-dotted tbody tr:last-child {
    border-bottom: none !important;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let nextPageUrl = '{{ $tahunAkademiks->nextPageUrl() }}';
    let hasMore = {{ $tahunAkademiks->hasMorePages() ? 'true' : 'false' }};
    let isLoading = false;

    const spinner = document.getElementById('loading-spinner');
    const paginationContainer = document.getElementById('pagination-container');
    const tableBody = document.getElementById('ta-table-body');

    if (paginationContainer) paginationContainer.classList.add('d-none');

    function handleScroll() {
        if (isLoading || !hasMore || !nextPageUrl) return;
        if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 100) {
            loadMore();
        }
    }

    function loadMore() {
        isLoading = true;
        if (spinner) spinner.classList.remove('d-none');
        fetch(nextPageUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.json())
        .then(data => {
            isLoading = false;
            if (spinner) spinner.classList.add('d-none');
            if (data.html) {
                const tempDiv = document.createElement('tbody');
                tempDiv.innerHTML = data.html;
                tempDiv.querySelectorAll('tr').forEach(row => tableBody.appendChild(row));
                const displayedCountEl = document.getElementById('displayed-count');
                if (displayedCountEl) {
                    displayedCountEl.textContent = tableBody.querySelectorAll('tr:not(.empty-row)').length;
                }
            }
            nextPageUrl = data.next_page;
            hasMore = data.has_more;
        })
        .catch(error => {
            console.error('Error:', error);
            isLoading = false;
            if (spinner) spinner.classList.add('d-none');
        });
    }

    window.addEventListener('scroll', handleScroll);
});
</script>
@endpush
@endsection
