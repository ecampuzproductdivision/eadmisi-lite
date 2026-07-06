@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $roles])
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
        <div class="col-md-3 col-12">
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
            <button type="submit" class="btn btn-white border"><i class="ti ti-filter"></i> Terapkan</button>
            <a href="{{ route('roles.index') }}" class="btn btn-white border px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
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
        @include('components.ajax-sort-script', ['tableBodyId' => 'role-table-body'])
        <table class="table align-middle text-nowrap mb-0 table-hover table-ead">
            <thead class="table-light">
                <tr>
                    <x-sortable-header field="role_name" label="Role Name" />
                    <x-sortable-header field="role_code" label="Role Code" />
                    <th scope="col" class="py-3">Description</th>
                    <x-sortable-header field="status" label="Status" width="90px" />
                    <th scope="col" class="py-3 text-center">Active Users</th>
                    <th scope="col" class="py-3 text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="role-table-body">
                @include('settings.roles.partials.role_rows')
            </tbody>
        </table>
        <div id="loading-spinner" class="d-none text-center py-3">
            <div class="spinner-border text-primary" role="status" style="width: 1.5rem; height: 1.5rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    @endslot
@endcomponent

@include('components.infinite-scroll-script', [
    'tableBodyId' => 'role-table-body',
    'spinnerId' => 'loading-spinner',
    'nextPageUrl' => $roles->nextPageUrl(),
    'hasMore' => $roles->hasMorePages(),
])
@endsection
