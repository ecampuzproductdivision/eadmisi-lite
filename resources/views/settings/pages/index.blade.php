@extends('layouts.app')

@section('content')
@component('components.data-page-layout')
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Pages', 'active' => true],
    ])
    @slot('title', 'Page & Feature Configuration')
    @slot('description', 'Register system routes/components and map them to their parent menus for permission tracking.')
    @slot('actions')
        <a href="{{ route('pages.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Add New Page
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-4 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari page..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }}>Disabled</option>
            </select>
        </div>
        <div class="col-md-2 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('pages.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('pages.index') }}?export=xls'">
            <i class="ti ti-file-spreadsheet"></i> .xls
        </a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
            <i class="ti ti-printer"></i> Print
        </a>
    @endslot
    @slot('table')
        <table class="table align-middle text-nowrap mb-0 table-hover table-ead">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="py-3">Page Name</th>
                    <th scope="col" class="py-3">Page Code</th>
                    <th scope="col" class="py-3">Parent Menu</th>
                    <th scope="col" class="py-3">Route Path</th>
                    <th scope="col" class="py-3">Component / View</th>
                    <th scope="col" class="py-3">Status</th>
                    <th scope="col" class="py-3 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar avatar-md bg-light text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                    <i class="ti ti-file fs-4"></i>
                                </div>
                                <div>
                                    <span class="mb-0 fw-semibold">{{ $page->page_name }}</span>
                                </div>
                            </div>
                        </td>
                        <td><code>{{ $page->page_code }}</code></td>
                        <td>
                            @if($page->menu)
                                <span class="badge bg-light border text-dark py-1 px-2 rounded">{{ $page->menu->menu_name }}</span>
                            @else
                                <span class="text-muted small">&mdash;</span>
                            @endif
                        </td>
                        <td><code>{{ $page->route_path ?: '&mdash;' }}</code></td>
                        <td><code>{{ $page->component_name ?: '&mdash;' }}</code></td>
                        <td>
                            <span class="badge {{ $page->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border rounded py-1 px-2">
                                {{ $page->is_active ? 'Active' : 'Disabled' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-sm btn-light border" title="Edit">
                                    <i class="ti ti-edit fs-5"></i>
                                </a>
                                
                                <form action="{{ route('pages.destroy', $page->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page registration?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete">
                                        <i class="ti ti-trash fs-5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="ti ti-file-off text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-2 mb-0 text-muted">No registered pages found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endslot
    @slot('pagination')
        @if($pages->hasPages())
            {{ $pages->links() }}
        @endif
    @endslot
@endcomponent
@endsection