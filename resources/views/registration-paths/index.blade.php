@extends('layouts.app')

@section('content')
@component('components.data-page-layout')
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Jalur Pendaftaran', 'active' => true],
    ])
    @slot('title', 'Jalur Pendaftaran')
    @slot('description', 'Kelola jalur penerimaan mahasiswa baru, biaya, dan jadwal pendaftaran.')
    @slot('actions')
        <a href="{{ route('registration-paths.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Tambah Jalur Baru
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-4 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari jalur pendaftaran..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="kategori" class="form-select">
                <option value="">-- Kategori --</option>
                @foreach($kategoris ?? [] as $kategori)
                    <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('registration-paths.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('registration-paths.index') }}?export=xls'">
            <i class="ti ti-file-spreadsheet"></i> .xls
        </a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
            <i class="ti ti-printer"></i> Print
        </a>
    @endslot
    @slot('table')
        <table class="table table-hover align-middle table-ead">
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
        <div id="pagination-container">
            @if($paths->hasPages())
                <div class="card-footer bg-white border-0 py-3">{{ $paths->links() }}</div>
            @endif
        </div>
    @endslot
@endcomponent

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let nextPageUrl = '{{ $paths->nextPageUrl() }}';
    let hasMore = {{ $paths->hasMorePages() ? 'true' : 'false' }};
    let isLoading = false;
    const spinner = document.getElementById('loading-spinner');
    const paginationContainer = document.getElementById('pagination-container');
    const tableBody = document.getElementById('paths-table-body');
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
            if (data.html) {
                const tempDiv = document.createElement('tbody');
                tempDiv.innerHTML = data.html;
                tempDiv.querySelectorAll('tr').forEach(row => tableBody.appendChild(row));
            }
            nextPageUrl = data.next_page; hasMore = data.has_more; isLoading = false; if (spinner) spinner.classList.add('d-none');
        })
        .catch(error => { console.error('Error:', error); isLoading = false; if (spinner) spinner.classList.add('d-none'); });
    }
    window.addEventListener('scroll', handleScroll);
});
</script>
@endpush
@endsection