@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $curiculums])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Master Data', 'url' => '#'],
        ['label' => 'Kurikulum', 'active' => true],
    ])
    @slot('title', 'Kurikulum')
    @slot('description', 'Kelola data kurikulum untuk program studi.')
    @slot('actions')
        <a href="{{ route('curiculum.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Tambah
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-4 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari kurikulum..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="prodi" class="form-select">
                <option value="">-- Prodi --</option>
                @foreach($prodiList as $prodi)
                    <option value="{{ $prodi->prodiKode }}" {{ request('prodi') == $prodi->prodiKode ? 'selected' : '' }}>{{ $prodi->prodiNamaResmi }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-12">
            <select name="status" class="form-select">
                <option value="">-- Status --</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Non-Aktif</option>
            </select>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('curiculum.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('curiculum.index') }}?export=xls'">
            <i class="ti ti-file-spreadsheet"></i> .xls
        </a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
            <i class="ti ti-printer"></i> Print
        </a>
    @endslot
    @slot('table')
        <table class="table table-hover align-middle mb-0 table-ead" style="border-collapse: separate; border-spacing: 0 8px; margin-top: -8px;">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 px-4 fw-semibold" width="50px">No</th>
                    <th class="border-0 fw-semibold" width="50px">Aksi</th>
                    <th class="border-0 fw-semibold">Kode Kurikulum</th>
                    <th class="border-0 fw-semibold">Nama Kurikulum</th>
                    <th class="border-0 fw-semibold">Program Studi</th>
                    <th class="border-0 fw-semibold">Thn Mulai</th>
                    <th class="border-0 fw-semibold">SKS Lulus</th>
                    <th class="border-0 fw-semibold">Status</th>
                </tr>
            </thead>
            <tbody id="curiculum-table-body">
                @include('references.curiculum.partials.kurikulum_rows')
            </tbody>
        </table>
        @if($kurikulums->isEmpty())
        <div class="text-center py-5">
            <p class="fw-semibold mb-0">Tidak Ada Data Kurikulum</p>
            <p class="text-secondary">Data kurikulum belum tersedia atau tidak ditemukan.</p>
        </div>
        @endif
        <div class="card-footer bg-white border-top py-3">
            <p class="text-muted mb-0 small">
                Menampilkan <span id="displayed-count" class="fw-semibold">{{ $kurikulums->count() }}</span> data dari total <span id="total-count" class="fw-semibold">{{ $kurikulums->total() }}</span> data
            </p>
        </div>
    @endslot
@endcomponent

@push('scripts')
<script>
    let nextPageUrl = '{{ $kurikulums->nextPageUrl() }}';
    let isLoading = false;
    let hasMore = {{ $kurikulums->hasMorePages() ? 'true' : 'false' }};

    function loadMoreCuriculums() {
        if (isLoading || !hasMore || !nextPageUrl) return;
        isLoading = true;
        document.getElementById('loading-indicator').classList.remove('d-none');
        fetch(nextPageUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.json())
        .then(data => {
            document.getElementById('curiculum-table-body').insertAdjacentHTML('beforeend', data.html);
            nextPageUrl = data.next_page; hasMore = data.has_more; isLoading = false;
            document.getElementById('loading-indicator').classList.add('d-none');
            const el = document.getElementById('displayed-count');
            if (el) el.textContent = document.getElementById('curiculum-table-body').querySelectorAll('tr').length;
        })
        .catch(error => { console.error('Error:', error); isLoading = false; document.getElementById('loading-indicator').classList.add('d-none'); });
    }

    window.addEventListener('scroll', () => {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) loadMoreCuriculums();
    });

    document.addEventListener('click', function(e) {
        if(e.target.closest('.toggle-status-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.toggle-status-btn');
            const curKode = btn.dataset.id;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const row = btn.closest('tr');
            const badge = row.querySelector('.status-badge') || row.querySelector('.badge');
            fetch(`/references/curiculum/${encodeURIComponent(curKode)}/toggle-status`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    if(data.status) { badge.classList.remove('bg-danger'); badge.classList.add('bg-success'); badge.textContent = 'Aktif'; btn.innerHTML = '<i class="ti ti-x me-2"></i>Non-Aktifkan'; }
                    else { badge.classList.remove('bg-success'); badge.classList.add('bg-danger'); badge.textContent = 'Non-Aktif'; btn.innerHTML = '<i class="ti ti-check me-2"></i>Aktifkan'; }
                }
            })
            .catch(error => { console.error('Error:', error); });
        }
    });
</script>
@endpush
@endsection