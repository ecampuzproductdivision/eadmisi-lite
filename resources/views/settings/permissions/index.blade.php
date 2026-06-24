@extends('layouts.app')

@section('content')
@component('components.data-page-layout')
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Permissions', 'active' => true],
    ])
    @slot('title', 'Atomic Action Permissions')
    @slot('description', 'Manage granular actions (e.g. read, create, update) used globally to secure functional boundaries.')
    @slot('actions')
        <a href="{{ route('permissions.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Add New Action
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-6 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search permissions..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-4 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Filter</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('permissions.index') }}?export=xls'">
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
                    <th scope="col" class="py-3">Action Token</th>
                    <th scope="col" class="py-3">Dynamic PHP Usage Example</th>
                    <th scope="col" class="py-3 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permissions as $perm)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar avatar-md bg-light text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;"><i class="ti ti-lock-open fs-4"></i></div>
                            <div><span class="mb-0 fw-semibold text-dark">{{ $perm->permission_name }}</span></div>
                        </div>
                    </td>
                    <td><code>auth()->user()->hasPermission('PAGE_CODE', '{{ $perm->permission_name }}')</code></td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-2">
                            <a href="{{ route('permissions.edit', $perm->id) }}" class="btn btn-sm btn-light border" title="Edit"><i class="ti ti-edit fs-5"></i></a>
                            <form action="{{ route('permissions.destroy', $perm->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete"><i class="ti ti-trash fs-5"></i></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center py-5"><i class="ti ti-lock-off text-muted" style="font-size: 3rem;"></i><p class="mt-2 mb-0 text-muted">No action permissions defined.</p></td></tr>
                @endforelse
            </tbody>
        </table>
    @endslot
    @slot('pagination')
        @if($permissions->hasPages())
            {{ $permissions->links() }}
        @endif
    @endslot
@endcomponent
@endsection