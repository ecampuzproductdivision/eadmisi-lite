@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $menus])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Menus', 'active' => true],
    ])
    @slot('title', 'Sidebar Menu & Navigation')
    @slot('description', 'Manage main sidebar headings, icons, nested sub-items, and layouts.')
    @slot('actions')
        <a href="{{ route('menus.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Add New Menu
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-6 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search menus..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-white border"><i class="ti ti-filter"></i> Filter</button>
            <a href="{{ route('menus.index') }}" class="btn btn-white border px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('menus.index') }}?export=xls'">
            <i class="ti ti-file-spreadsheet"></i> .xls
        </a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
            <i class="ti ti-printer"></i> Print
        </a>
    @endslot
    @slot('table')
        <div>
            <div class="menu-list">
                @forelse($menus as $menu)
                <div class="card border mb-3 shadow-none">
                    <div class="card-body py-3 d-flex align-items-center justify-content-between bg-light-subtle">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle text-primary py-2 px-3 rounded">
                                @if(str_contains($menu->icon ?? '', 'ti-'))
                                    <i class="ti {{ $menu->icon }} fs-4"></i>
                                @else
                                    <i class="ti ti-folder fs-4"></i>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold text-dark">{{ $menu->menu_name }}</h6>
                                <div class="d-flex gap-2 align-items-center">
                                    <code class="small text-muted">{{ $menu->menu_code }}</code>
                                    <span class="text-muted">•</span>
                                    <span class="text-muted small">URL: {{ $menu->url ?: '—' }}</span>
                                    <span class="text-muted">•</span>
                                    <span class="badge bg-light border text-dark py-1 px-2 rounded small">Order: {{ $menu->sort_order }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge {{ $menu->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border rounded py-1 px-2">{{ $menu->is_active ? 'Active' : 'Hidden' }}</span>
                            <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-xs btn-light border py-1 px-2" title="Edit"><i class="ti ti-edit"></i></a>
                            <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Deleting parent menu will also delete all its submenus. Are you sure?');">@csrf @method('DELETE')<button type="submit" class="btn btn-xs btn-light border text-danger py-1 px-2" title="Delete"><i class="ti ti-trash"></i></button></form>
                        </div>
                    </div>
                    @if($menu->children->isNotEmpty())
                    <div class="card-footer bg-white border-top-0 ps-5 py-3">
                        <div class="d-flex flex-column gap-2">
                            @foreach($menu->children as $child)
                            <div class="border rounded p-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ti ti-corner-down-right text-muted fs-4 me-1"></i>
                                    @if($child->icon)<i class="ti {{ $child->icon }} text-muted me-2"></i>@endif
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-dark">{{ $child->menu_name }}</h6>
                                        <div class="d-flex gap-2 align-items-center">
                                            <code class="small text-muted">{{ $child->menu_code }}</code>
                                            <span class="text-muted">•</span><span class="text-muted small">URL: {{ $child->url }}</span>
                                            <span class="text-muted">•</span><span class="badge bg-light border text-dark py-1 px-2 rounded small">Order: {{ $child->sort_order }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="badge {{ $child->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border rounded py-1 px-2">{{ $child->is_active ? 'Active' : 'Hidden' }}</span>
                                    <a href="{{ route('menus.edit', $child->id) }}" class="btn btn-xs btn-light border py-1 px-2"><i class="ti ti-edit"></i></a>
                                    <form action="{{ route('menus.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">@csrf @method('DELETE')<button type="submit" class="btn btn-xs btn-light border text-danger py-1 px-2"><i class="ti ti-trash"></i></button></form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-5"><i class="ti ti-menu-off text-muted" style="font-size: 3rem;"></i><p class="mt-2 mb-0 text-muted">No sidebar menus found.</p></div>
                @endforelse
            </div>
        </div>
    @endslot
@endcomponent
@endsection
