@extends('layouts.app')

@section('content')
@component('components.data-page-layout')
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Form Pendaftaran', 'active' => true],
    ])
    @slot('title', 'Form Pendaftaran')
    @slot('description', 'Kelola formulir pendaftaran dan field-field yang terkait.')
    @slot('actions')
        <a href="{{ route('settings.form-pendaftaran.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Tambah Form
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-4 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari form..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="col-md-2 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('settings.form-pendaftaran.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('settings.form-pendaftaran.index') }}?export=xls'">
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
                    <th style="width: 60px;" class="py-3">No</th>
                    <th class="py-3">Nama Form</th>
                    <th class="py-3">Deskripsi</th>
                    <th class="py-3 text-center">Field</th>
                    <th class="py-3 text-center">Status</th>
                    <th class="py-3 text-center">Dibuat</th>
                    <th class="py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($forms as $i => $form)
                <tr>
                    <td class="text-muted small">{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="d-inline-flex rounded-circle bg-primary bg-opacity-10 align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="ti ti-file-text text-primary"></i>
                            </span>
                            <div>
                                <span class="fw-semibold">{{ $form->nama }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <small class="text-muted">{{ Str::limit($form->deskripsi, 60) ?: '-' }}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-info-subtle text-info px-3 py-2">{{ $form->fields_count }} field</span>
                    </td>
                    <td class="text-center">
                        <form action="{{ route('settings.form-pendaftaran.toggle-status', $form->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $form->is_active ? 'btn-success' : 'btn-secondary' }} border-0">
                                <i class="ti ti-{{ $form->is_active ? 'check' : 'x' }} me-1"></i>
                                {{ $form->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td class="text-center">
                        <small class="text-muted">{{ $form->created_at->format('d/m/Y') }}</small>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('settings.form-pendaftaran.builder', $form->id) }}" class="btn btn-primary btn-sm" title="Atur Field">
                                <i class="ti ti-layout-board"></i>
                            </a>
                            <form action="{{ route('settings.form-pendaftaran.destroy', $form->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus form {{ $form->nama }}? Semua field akan ikut terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="ti ti-file-off text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-2 mb-0 text-muted">Belum Ada Form</p>
                        <small class="text-muted">Klik "Tambah Form" untuk membuat formulir pendaftaran baru.</small>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    @endslot
    @slot('pagination')
        @if($forms->hasPages())
            {{ $forms->links() }}
        @endif
    @endslot
@endcomponent
@endsection