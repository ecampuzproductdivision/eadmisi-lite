@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $permissions])
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
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-white border"><i class="ti ti-filter"></i> Filter</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-white border px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
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
        @include('components.ajax-sort-script', ['tableBodyId' => 'permission-table-body'])
        <table class="table align-middle text-nowrap mb-0 table-hover table-ead">
            <thead class="table-light">
                <tr>
                    <x-sortable-header field="permission_name" label="Action Token" />
                    <th scope="col" class="py-3">Dynamic PHP Usage Example</th>
                    <th scope="col" class="py-3 text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="permission-table-body">
                @include('settings.permissions.partials.permission_rows')
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
    'tableBodyId' => 'permission-table-body',
    'spinnerId' => 'loading-spinner',
    'nextPageUrl' => $permissions->nextPageUrl(),
    'hasMore' => $permissions->hasMorePages(),
])
@endsection
