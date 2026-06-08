@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-md-6 col-12">
      <h1 class="mb-1 fw-bold">Activity Logs</h1>
      <p class="mb-0 text-muted">Monitor all user activities and system events.</p>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Filter -->
  <div class="card border-0 shadow-sm mb-6">
    <div class="card-body">
      <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
          <label class="form-label small">Module</label>
          <select name="module" class="form-select">
            <option value="">All Modules</option>
            @foreach($modules as $m)
              <option value="{{ $m }}" {{ request('module') == $m ? 'selected' : '' }}>{{ ucfirst($m) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label small">Action</label>
          <select name="action" class="form-select">
            <option value="">All Actions</option>
            @foreach($actions as $a)
              <option value="{{ $a }}" {{ request('action') == $a ? 'selected' : '' }}>{{ ucfirst($a) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label small">Date From</label>
          <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
          <label class="form-label small">Date To</label>
          <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-3 d-grid">
          <button type="submit" class="btn btn-light border">Filter</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Logs Table -->
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle text-nowrap mb-0 table-hover">
        <thead class="table-light">
          <tr>
            <th>Time</th>
            <th>User</th>
            <th>Action</th>
            <th>Module</th>
            <th>Description</th>
            <th>IP Address</th>
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
              <td colspan="6" class="text-center py-6 text-muted">No activity logs found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer bg-white border-0">
      {{ $logs->links() }}
    </div>
  </div>
</main>
@endsection