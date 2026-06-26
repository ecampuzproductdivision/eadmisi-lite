@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $countries])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Master Data', 'url' => '#'],
        ['label' => 'Negara', 'active' => true],
    ])
    @slot('title', 'Negara')
    @slot('description', 'Kelola data negara untuk referensi alamat dan kode telepon internasional.')
    @slot('actions')
        <a href="{{ route('country.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Tambah
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-3 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama negara atau kode..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2 col-12">
            <select name="status" class="form-select">
                <option value="">-- Status --</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('country.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('country.index') }}?export=xls'">
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
                    <th scope="col" class="py-3">Nama Negara</th>
                    <th scope="col" class="py-3">Kode Negara (ISO 2)</th>
                    <th scope="col" class="py-3">Kode Negara (ISO 3)</th>
                    <th scope="col" class="py-3">Kode Telepon</th>
                    <th scope="col" class="py-3">Status</th>
                    <th scope="col" class="py-3 text-end">Aksi</th>
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
    @endslot
    @slot('pagination')
        @if($countries->hasPages())
            {{ $countries->links() }}
        @endif
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
                    label.className = 'form-check-label status-label text-' + (data.status === 'active' ? 'success' : 'danger') + ' fw-semibold small ms-1';
                } else {
                    alert(data.message || 'Gagal mengubah status.');
                    this.checked = !this.checked;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan.');
                this.checked = !this.checked;
            });
        });
    }

    document.querySelectorAll('.status-toggle').forEach(registerStatusToggle);

    let nextPageUrl = '{{ $countries->nextPageUrl() }}';
    let hasMore = {{ $countries->hasMorePages() ? 'true' : 'false' }};
    let isLoading = false;
    const spinner = document.getElementById('loading-spinner');
    const paginationContainer = document.getElementById('pagination-container');
    const tableBody = document.getElementById('country-table-body');

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
                tempDiv.querySelectorAll('tr').forEach(row => {
                    tableBody.appendChild(row);
                    const toggle = row.querySelector('.status-toggle');
                    if (toggle) registerStatusToggle(toggle);
                });
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
