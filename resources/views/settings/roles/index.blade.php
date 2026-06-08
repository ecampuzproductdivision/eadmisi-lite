@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-md-6 col-12">
      <h1 class="mb-1 fw-bold">Role & Privilege Management</h1>
      <p class="mb-0 text-muted">Configure access roles, clone configurations, and assign granular permissions.</p>
    </div>
    <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
      <a href="{{ route('roles.matrix') }}" class="btn btn-light border d-inline-flex align-items-center gap-2">
        <i class="ti ti-table fs-4"></i> View Access Matrix
      </a>
      <a href="{{ route('roles.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <i class="ti ti-plus fs-4"></i> Add New Role
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-triangle fs-4 me-2"></i>
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Roles Grid/List -->
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle text-nowrap mb-0 table-hover">
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
                  <div>
                    <h5 class="mb-0 fw-semibold">{{ $role->role_name }}</h5>
                  </div>
                </div>
              </td>
              <td><code>{{ $role->role_code }}</code></td>
              <td>{{ Str::limit($role->description ?? 'No description provided.', 50) }}</td>
              <td>
                <span class="badge {{ $role->status == 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} py-1 px-2 border rounded">
                  {{ $role->status }}
                </span>
              </td>
              <td class="text-center">
                <span class="badge bg-light text-dark border py-1 px-2 rounded fw-bold">{{ $role->users_count }}</span>
              </td>
              <td class="text-end">
                <div class="d-inline-flex gap-2">
                  @if($role->role_code !== 'SUPER_ADMIN')
                    <a href="{{ route('roles.permissions', $role->id) }}" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1" title="Assign Permissions">
                      <i class="ti ti-lock-open fs-5"></i> Set Permissions
                    </a>
                  @else
                    <button class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1 disabled" title="All Permissions Permanently Granted">
                      <i class="ti ti-lock fs-5"></i> Permanent
                    </button>
                  @endif
                  
                  <a href="{{ route('roles.duplicate', $role->id) }}" class="btn btn-sm btn-light border" title="Duplicate/Clone Role">
                    <i class="ti ti-copy fs-5"></i>
                  </a>
                  
                  <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-light border" title="Edit">
                    <i class="ti ti-edit fs-5"></i>
                  </a>

                  @if($role->role_code !== 'SUPER_ADMIN')
                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete">
                        <i class="ti ti-trash fs-5"></i>
                      </button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-5">
                <i class="ti ti-shield-off text-muted" style="font-size: 3rem;"></i>
                <p class="mt-2 mb-0 text-muted">No roles found.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    @if($roles->hasPages())
      <div class="card-footer bg-white border-0 py-3">
        {{ $roles->links() }}
      </div>
    @endif
  </div>
</main>
@endsection
