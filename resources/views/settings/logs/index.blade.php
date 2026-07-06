@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $logs])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Activity Logs', 'active' => true],
    ])
    @slot('title', 'Activity Logs')
    @slot('description', 'Monitor all user activities and system events.')
    @slot('filters')
        <div class="col-md-3 col-12">
            <select name="module" class="form-select">
                <option value="">All Modules</option>
                @foreach($modules as $m)
                    <option value="{{ $m }}" {{ request('module') == $m ? 'selected' : '' }}>{{ ucfirst($m) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-12">
            <select name="action" class="form-select">
                <option value="">All Actions</option>
                @foreach($actions as $a)
                    <option value="{{ $a }}" {{ request('action') == $a ? 'selected' : '' }}>{{ ucfirst($a) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-12">
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Date From">
        </div>
        <div class="col-md-2 col-12">
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Date To">
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-white border"><i class="ti ti-filter"></i> Filter</button>
            <a href="{{ route('logs.index') }}" class="btn btn-white border px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('table')
        @include('components.ajax-sort-script', ['tableBodyId' => 'log-table-body'])
        <table class="table align-middle text-nowrap mb-0 table-hover table-ead">
            <thead class="table-light">
                <tr>
                    <x-sortable-header field="created_at" label="Time" />
                    <th scope="col" class="py-3">User</th>
                    <x-sortable-header field="action" label="Action" />
                    <x-sortable-header field="module" label="Module" />
                    <th scope="col" class="py-3">Description</th>
                    <th scope="col" class="py-3">IP Address</th>
                </tr>
            </thead>
            <tbody id="log-table-body">
                @include('settings.logs.partials.log_rows')
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
    'tableBodyId' => 'log-table-body',
    'spinnerId' => 'loading-spinner',
    'nextPageUrl' => $logs->nextPageUrl(),
    'hasMore' => $logs->hasMorePages(),
])
@endsection
