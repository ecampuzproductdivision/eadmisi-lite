@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $periodes])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Periode Akademik', 'active' => true],
    ])
    @slot('title', 'Periode Akademik')
    @slot('description', 'Kelola tahun akademik dan semester aktif untuk pendaftaran mahasiswa baru.')
    @slot('actions')
        <a href="{{ route('periode.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Tambah Periode Baru
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-3 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari tahun akademik..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="semester" class="form-select">
                <option value="">-- Semester --</option>
                <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                <option value="Pendek" {{ request('semester') == 'Pendek' ? 'selected' : '' }}>Pendek</option>
            </select>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('periode.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('periode.index') }}?export=xls'">
            <i class="ti ti-file-spreadsheet"></i> .xls
        </a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
            <i class="ti ti-printer"></i> Print
        </a>
    @endslot
    @slot('table')
        @include('components.ajax-sort-script', ['tableBodyId' => 'periode-table-body'])
        <table class="table align-middle table-hover table-ead">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="py-3" style="width: 60px;">No</th>
                    <x-sortable-header field="tahun_akademik" label="Tahun Akademik" />
                    <x-sortable-header field="semester" label="Periode Semester" />
                    <x-sortable-header field="status_aktif" label="Status Aktif" width="120px" />
                    <th scope="col" class="py-3 text-end" style="width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="periode-table-body">
                @forelse($periodes as $index => $periode)
                    <tr>
                        <td class="py-3">{{ ($periodes->currentPage() - 1) * $periodes->perPage() + $index + 1 }}</td>
                        <td class="py-3 fw-semibold">{{ $periode->tahun_akademik }}</td>
                        <td class="py-3">
                            @if($periode->semester === 'Ganjil')
                                <span class="badge bg-primary-subtle text-primary px-3 py-2">Ganjil</span>
                            @elseif($periode->semester === 'Genap')
                                <span class="badge bg-info-subtle text-info px-3 py-2">Genap</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning px-3 py-2">Pendek</span>
                            @endif
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2">
                                <form action="{{ route('periode.toggle-active', $periode) }}" method="POST" class="m-0">
                                    @csrf
                                    <div class="form-check form-switch mb-0">
                                        <input type="checkbox" class="form-check-input" role="switch"
                                               {{ $periode->status_aktif ? 'checked' : '' }}
                                               onchange="this.form.submit()">
                                    </div>
                                </form>
                                @if($periode->status_aktif)
                                    <span class="badge bg-success-subtle text-success px-3 py-2">Aktif</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Nonaktif</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('periode.edit', $periode) }}" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1">
                                    <i class="ti ti-edit fs-5"></i>
                                </a>
                                <form action="{{ route('periode.destroy', $periode) }}" method="POST" onsubmit="return confirm('Hapus periode {{ $periode->label }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger d-inline-flex align-items-center gap-1">
                                        <i class="ti ti-trash fs-5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="ti ti-calendar-off text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-3 mb-0 text-muted">Belum ada periode akademik.</p>
                                <a href="{{ route('periode.create') }}" class="btn btn-primary mt-3">
                                    Tambah Periode Pertama
                                </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($periodes->hasPages())
            <div class="mt-3">
                {{ $periodes->links() }}
            </div>
        @endif
    @endslot
@endcomponent
@endsection
