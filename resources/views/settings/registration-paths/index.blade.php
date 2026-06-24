@extends('layouts.app')

@section('content')
@component('components.data-page-layout')
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Program Studi', 'active' => true],
    ])
    @slot('title', 'Program Studi')
    @slot('description', 'Konfigurasi program studi yang terkait dengan form registrasi.')
    @slot('actions')
        <a href="{{ route('registration-paths.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Tambah Program Studi
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-4 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari program studi..." value="{{ request('search') }}">
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
        <div class="col-md-3 col-12 d-flex gap-2">
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
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Biaya</th>
                    <th>Periode</th>
                    <th>Kuota</th>
                    <th>Status</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paths as $path)
                <tr>
                    <td>{{ $loop->iteration + ($paths->currentPage() - 1) * $paths->perPage() }}</td>
                    <td><span class="badge bg-{{ $path->color ?? 'secondary' }}-subtle text-{{ $path->color ?? 'secondary' }} px-3 py-2">{{ $path->code }}</span></td>
                    <td class="fw-semibold">{{ $path->name }}</td>
                    <td>@if($path->kategori)<span class="badge bg-dark-subtle text-dark px-3 py-2">{{ $path->kategori->nama }}</span>@else<span class="text-muted">—</span>@endif</td>
                    <td>Rp {{ number_format($path->fee, 0, ',', '.') }}</td>
                    <td>@if($path->registration_start && $path->registration_end){{ \Carbon\Carbon::parse($path->registration_start)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($path->registration_end)->format('d/m/Y') }}@else<span class="text-muted">—</span>@endif</td>
                    <td>@if($path->quota)<span class="fw-semibold">{{ $path->quota }}</span>@else<span class="text-muted">∞</span>@endif</td>
                    <td>@if($path->is_active)<span class="badge bg-success-subtle text-success px-3 py-2">Aktif</span>@else<span class="badge bg-danger-subtle text-danger px-3 py-2">Nonaktif</span>@endif</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('registration-paths.show', $path) }}" class="btn btn-sm btn-info d-inline-flex align-items-center gap-1"><i class="ti ti-eye fs-5"></i></a>
                            <a href="{{ route('registration-paths.edit', $path) }}" class="btn btn-sm btn-warning d-inline-flex align-items-center gap-1"><i class="ti ti-edit fs-5"></i></a>
                            <form action="{{ route('registration-paths.destroy', $path) }}" method="POST" onsubmit="return confirm('Hapus {{ $path->name }}?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1"><i class="ti ti-trash fs-5"></i></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center py-5"><i class="ti ti-road-off text-muted" style="font-size: 3rem;"></i><p class="mt-3 mb-0 text-muted">Belum ada data.</p></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($paths->hasPages())
            {{ $paths->links() }}
        @endif
    @endslot
@endcomponent
@endsection