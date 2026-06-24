@extends('layouts.app')

@section('content')
@component('components.data-page-layout')
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
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Filter</button>
            <a href="{{ route('logs.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('table')
        <table class="table align-middle text-nowrap mb-0 table-hover table-ead">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="py-3">Time</th>
                    <th scope="col" class="py-3">User</th>
                    <th scope="col" class="py-3">Action</th>
                    <th scope="col" class="py-3">Module</th>
                    <th scope="col" class="py-3">Description</th>
                    <th scope="col" class="py-3">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>
                            <div class="fw-semibold small">{{ $log->created_at->format('d M Y') }}</div>
                            <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                        </td>
                        <td>
                            @if($log->user)
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $log->user->avatar_url }}" class="avatar avatar-xs rounded-circle" alt="">
                                    <div>
                                        <div class="fw-semibold small">{{ $log->user->name }}</div>
                                        <small class="text-muted">{{ $log->user->email }}</small>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted small">System</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeClass = match($log->action) {
                                    'login', 'logout' => 'info',
                                    'create' => 'success',
                                    'update' => 'warning',
                                    'delete' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeClass }}-subtle text-{{ $badgeClass }} border border-{{ $badgeClass }}-subtle text-capitalize">{{ $log->action }}</span>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ ucfirst($log->module) }}</span></td>
                        <td>
                            <div class="small">{{ $log->description }}</div>
                        </td>
                        <td><code class="small">{{ $log->ip_address }}</code></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="ti ti-activity-heartbeat text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-2 mb-0 text-muted">No activity logs found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($logs->hasPages())
            {{ $logs->links() }}
        @endif
    @endslot
@endcomponent
@endsection