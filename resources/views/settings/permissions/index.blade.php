@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-md-6 col-12">
      <h1 class="mb-1 fw-bold">Atomic Action Permissions</h1>
      <p class="mb-0 text-muted">Manage granular actions (e.g. read, create, update) used globally to secure functional boundaries.</p>
    </div>
    <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
      <a href="{{ route('permissions.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <i class="ti ti-plus fs-4"></i> Add New Action
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

  <div class="row">
    <!-- Permissions Table -->
    <div class="col-xl-8 col-12">
      <div class="card border-0 shadow-sm">
        <div class="table-responsive">
          <table class="table align-middle text-nowrap mb-0 table-hover">
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
                      <div class="avatar avatar-md bg-light text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                        <i class="ti ti-lock-open fs-4"></i>
                      </div>
                      <div>
                        <h5 class="mb-0 fw-semibold text-dark">{{ $perm->permission_name }}</h5>
                      </div>
                    </div>
                  </td>
                  <td>
                    <code>auth()->user()->hasPermission('PAGE_CODE', '{{ $perm->permission_name }}')</code>
                  </td>
                  <td class="text-end">
                    <div class="d-inline-flex gap-2">
                      <a href="{{ route('permissions.edit', $perm->id) }}" class="btn btn-sm btn-light border" title="Edit">
                        <i class="ti ti-edit fs-5"></i>
                      </a>
                      
                      <form action="{{ route('permissions.destroy', $perm->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this action permission?');">
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
                  <td colspan="3" class="text-center py-5">
                    <i class="ti ti-lock-off text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-2 mb-0 text-muted">No action permissions defined.</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        @if($permissions->hasPages())
          <div class="card-footer bg-white border-0 py-3">
            {{ $permissions->links() }}
          </div>
        @endif
      </div>
    </div>

    <!-- Instructions Panel -->
    <div class="col-xl-4 col-12 mt-4 mt-xl-0">
      <div class="card border-0 shadow-sm bg-danger-subtle text-danger-emphasis border-start border-danger border-4">
        <div class="card-body">
          <h5 class="fw-bold mb-3 d-flex align-items-center gap-2"><i class="ti ti-info-circle fs-4"></i> Developer Guide</h5>
          <p class="mb-2 small">1. **Action Name**: Enter a lowercase word or action code (e.g. `export`, `approve`).</p>
          <p class="mb-2 small">2. **Usage**: These tokens map to checkboxes under the **Roles -> Set Permissions** grid, where admins decide which role can perform this specific action on which page.</p>
          <p class="mb-0 small">3. **Verification**: In Blade templates, use: `auth()->user()->hasPermission('PAGE_CODE', 'action')`.</p>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
