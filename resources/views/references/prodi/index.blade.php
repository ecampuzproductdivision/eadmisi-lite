@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $prodis])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Master Data', 'url' => '#'],
        ['label' => 'Program Studi', 'active' => true],
    ])
    @slot('title', 'Program Studi')
    @slot('description', 'Kelola data program studi untuk referensi akademik.')
    @slot('actions')
        <a href="{{ route('prodi.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Tambah
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-4 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama prodi atau kode..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2 col-12">
            <select name="fakultas" class="form-select">
                <option value="">-- Fakultas --</option>
                @foreach($fakultasList as $fak)
                    <option value="{{ $fak->fakKode }}" {{ request('fakultas') == $fak->fakKode ? 'selected' : '' }}>{{ $fak->fakNama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-12">
            <select name="jenjang" class="form-select">
                <option value="">-- Jenjang --</option>
                @foreach($jenjangList as $jjr)
                    <option value="{{ $jjr->jjarKode }}" {{ request('jenjang') == $jjr->jjarKode ? 'selected' : '' }}>{{ $jjr->jjarNama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-12">
            <select name="status" class="form-select">
                <option value="">-- Status --</option>
                <option value="A" {{ request('status') == 'A' ? 'selected' : '' }}>Aktif</option>
                <option value="N" {{ request('status') == 'N' ? 'selected' : '' }}>Non-Aktif</option>
            </select>
        </div>
        <div class="col-md-2 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('prodi.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('prodi.index') }}?export=xls'">
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
        <div class="card-footer bg-white border-top py-3">
            <p class="text-muted mb-0 small">
                Menampilkan <span id="displayed-count" class="fw-semibold">{{ $programStudis->count() }}</span> data dari total <span id="total-count" class="fw-semibold">{{ $programStudis->total() }}</span> data
            </p>
        </div>
        <div id="pagination-container">
            @if($programStudis->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $programStudis->links() }}
                </div>
            @endif
        </div>
    @endslot
@endcomponent

<style>
.table-dotted tbody tr { border-bottom: 1px dotted #dee2e6 !important; }
.table-dotted tbody tr:last-child { border-bottom: none !important; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function registerStatusToggle(toggle) {
        if (toggle.hasAttribute('data-registered')) return;
        toggle.setAttribute('data-registered', 'true');
        toggle.addEventListener('change', function() {
            const url = this.getAttribute('data-url');
            const label = this.parentElement.querySelector('.status-label');
            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    label.textContent = data.status === 'A' ? 'Aktif' : 'Non-Aktif';
                    label.className = 'form-check-label status-label text-' + (data.status === 'A' ? 'success' : 'danger') + ' fw-semibold small ms-1';
                } else { alert(data.message || 'Gagal mengubah status.'); this.checked = !this.checked; }
            })
            .catch(error => { console.error('Error:', error); alert('Terjadi kesalahan.'); this.checked = !this.checked; });
        });
    }
    document.querySelectorAll('.status-toggle').forEach(registerStatusToggle);

    let nextPageUrl = '{{ $programStudis->nextPageUrl() }}';
    let hasMore = {{ $programStudis->hasMorePages() ? 'true' : 'false' }};
    let isLoading = false;
    const spinner = document.getElementById('loading-spinner');
    const paginationContainer = document.getElementById('pagination-container');
    const tableBody = document.getElementById('prodi-table-body');
    if (paginationContainer) paginationContainer.classList.add('d-none');

    function handleScroll() {
        if (isLoading || !hasMore || !nextPageUrl) return;
        if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 100) loadMore();
    }

    function loadMore() {
        isLoading = true;
        if (spinner) spinner.classList.remove('d-none');
        fetch(nextPageUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.json())
        .then(data => {
            isLoading = false; if (spinner) spinner.classList.add('d-none');
            if (data.html) {
                const tempDiv = document.createElement('tbody');
                tempDiv.innerHTML = data.html;
                tempDiv.querySelectorAll('tr').forEach(row => {
                    tableBody.appendChild(row);
                    const toggle = row.querySelector('.status-toggle');
                    if (toggle) registerStatusToggle(toggle);
                });
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