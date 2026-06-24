@extends('layouts.app')

@section('content')
@component('components.data-page-layout')
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Master Data', 'url' => '#'],
        ['label' => 'Dosen', 'active' => true],
    ])
    @slot('title', 'Dosen')
    @slot('description', 'Kelola data dosen, homebase, jabatan, dan status.')
    @slot('actions')
        <a href="{{ route('dosen.rekap') }}" class="btn btn-outline-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-chart-pie fs-4"></i> Rekap
        </a>
        <a href="{{ route('dosen.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Tambah
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-4 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama, NIDN, atau keahlian..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2 col-12">
            <select name="prodi" class="form-select">
                <option value="">-- Prodi --</option>
                @foreach($prodiList as $p)
                    <option value="{{ $p->prodiKode }}" {{ request('prodi') == $p->prodiKode ? 'selected' : '' }}>{{ $p->prodiNamaResmi }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-12">
            <select name="jenis" class="form-select">
                <option value="">-- Jenis --</option>
                <option value="Tetap" {{ request('jenis') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                <option value="Tidak Tetap" {{ request('jenis') == 'Tidak Tetap' ? 'selected' : '' }}>Tidak Tetap</option>
                <option value="Luar Biasa" {{ request('jenis') == 'Luar Biasa' ? 'selected' : '' }}>Luar Biasa</option>
                <option value="Tamu" {{ request('jenis') == 'Tamu' ? 'selected' : '' }}>Tamu</option>
            </select>
        </div>
        <div class="col-md-2 col-12">
            <select name="status" class="form-select">
                <option value="">-- Status --</option>
                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Cuti" {{ request('status') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="col-md-2 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('dosen.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('dosen.index') }}?export=xls'">
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
                    <th scope="col" class="fw-semibold">Nama / Identitas</th>
                    <th scope="col" class="fw-semibold">Homebase</th>
                    <th scope="col" class="fw-semibold">Jabatan & Pendidikan</th>
                    <th scope="col" class="fw-semibold">Serdos</th>
                    <th scope="col" class="fw-semibold text-center">Status</th>
                </tr>
            </thead>
            <tbody id="dosen-table-body">
                @if($dosenList->isEmpty())
                    <tr class="empty-row">
                        <td colspan="7" class="text-center py-5"><p class="mt-2 mb-0 text-muted">Tidak ada data dosen ditemukan.</p></td>
                    </tr>
                @else
                    @include('references.dosen.partials.dosen_rows')
                @endif
            </tbody>
        </table>
        <div class="card-footer bg-white border-top py-3">
            <p class="text-muted mb-0 small">
                Menampilkan <span id="displayed-count" class="fw-semibold">{{ $dosenList->count() }}</span> data dari total <span id="total-count" class="fw-semibold">{{ $dosenList->total() }}</span> data
            </p>
        </div>
        <div id="pagination-container">
            @if($dosenList->hasPages())
                <div class="card-footer bg-white border-0 py-3">{{ $dosenList->links() }}</div>
            @endif
        </div>
    @endslot
@endcomponent

<style>
.table-dotted tbody tr { border-bottom: 1px dotted #dee2e6 !important; }
.table-dotted tbody tr:last-child { border-bottom: none !important; }
.avatar-text { width: 40px; height: 40px; background-color: #f1f5f9; color: #64748b; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 600; }
.dosen-photo { width: 40px; height: 40px; object-fit: cover; border-radius: 50%; }
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
            fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' } })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    label.textContent = data.status;
                    label.className = 'form-check-label status-label text-' + (data.status === 'Aktif' ? 'success' : 'danger') + ' fw-semibold small ms-1';
                } else { alert(data.message || 'Gagal mengubah status.'); this.checked = !this.checked; }
            })
            .catch(error => { console.error('Error:', error); alert('Terjadi kesalahan.'); this.checked = !this.checked; });
        });
    }
    document.querySelectorAll('.status-toggle').forEach(registerStatusToggle);

    let nextPageUrl = '{{ $dosenList->nextPageUrl() }}';
    let hasMore = {{ $dosenList->hasMorePages() ? 'true' : 'false' }};
    let isLoading = false;
    const spinner = document.getElementById('loading-spinner');
    const paginationContainer = document.getElementById('pagination-container');
    const tableBody = document.getElementById('dosen-table-body');
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