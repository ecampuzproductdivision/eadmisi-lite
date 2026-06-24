@extends('layouts.app')

@section('content')
@component('components.data-page-layout')
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Roles', 'active' => true],
    ])
    @slot('title', 'Role & Privilege Management')
    @slot('description', 'Configure access roles, clone configurations, and assign granular permissions.')
    @slot('actions')
        <a href="{{ route('roles.matrix') }}" class="btn btn-light border d-inline-flex align-items-center gap-2">
            <i class="ti ti-table fs-4"></i> View Access Matrix
        </a>
        <a href="{{ route('roles.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Add New Role
        </a>
    @endslot
    @slot('filters')
        <div class="col-md-4 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search roles..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Filter</button>
            <a href="{{ route('roles.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('roles.index') }}?export=xls'">
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
                    <th scope="col" class="py-3">Role Name</th>
                    <th scope="col" class="py-3">Role Code</th>
                    <th scope="col" class="py-3">Description</th>
                    <th scope="col" class="py-3">Status</th>
                    <th scope="col" class="py-3 text-center">Active Users</th>
                    <th scope="col" class="py-3 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar avatar-md bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                <i class="ti ti-shield fs-4"></i>
                            </div>
                            <div><span class="mb-0 fw-semibold">{{ $role->role_name }}</span></div>
                        </div>
                    </td>
                    <td><code>{{ $role->role_code }}</code></td>
                    <td>{{ Str::limit($role->description ?? 'No description provided.', 50) }}</td>
                    <td><span class="badge {{ $role->status == 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} py-1 px-2 border rounded">{{ $role->status }}</span></td>
                    <td class="text-center"><span class="badge bg-light text-dark border py-1 px-2 rounded fw-bold">{{ $role->users_count }}</span></td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-2">
                            @if($role->role_code !== 'SUPER_ADMIN')
                                <a href="{{ route('roles.permissions', $role->id) }}" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1" title="Assign Permissions"><i class="ti ti-lock-open fs-5"></i> Set Permissions</a>
                            @else
                                <button class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1 disabled" title="All Permissions Permanently Granted"><i class="ti ti-lock fs-5"></i> Permanent</button>
                            @endif
                            <a href="{{ route('roles.duplicate', $role->id) }}" class="btn btn-sm btn-light border" title="Duplicate"><i class="ti ti-copy fs-5"></i></a>
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-light border" title="Edit"><i class="ti ti-edit fs-5"></i></a>
                            @if($role->role_code !== 'SUPER_ADMIN')
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete"><i class="ti ti-trash fs-5"></i></button></form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5"><i class="ti ti-shield-off text-muted" style="font-size: 3rem;"></i><p class="mt-2 mb-0 text-muted">No roles found.</p></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($roles->hasPages())
            <div class="card-footer bg-white border-0 py-3">{{ $roles->links() }}</div>
        @endif
    @endslot
@endcomponent
@endsection