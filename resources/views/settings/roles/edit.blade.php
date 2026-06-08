@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-12">
      <a href="{{ route('roles.index') }}" class="btn btn-sm btn-light border mb-3 d-inline-flex align-items-center gap-1">
        <i class="ti ti-arrow-left"></i> Back to List
      </a>
      <h1 class="mb-1 fw-bold">Edit Role</h1>
      <p class="mb-0 text-muted">Update details for role <strong>{{ $role->role_name }}</strong>.</p>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-xl-8 col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-5">
          <form action="{{ route('roles.update', $role->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            @if($errors->any())
              <div class="alert alert-danger mb-4 py-2 small">
                <ul class="mb-0 ps-3">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="row g-4">
              <!-- Role Name -->
              <div class="col-md-6 col-12">
                <label for="role_name" class="form-label">Role Name <span class="text-danger">*</span></label>
                <input type="text" name="role_name" id="role_name" class="form-control" placeholder="e.g. Finance Officer" value="{{ old('role_name', $role->role_name) }}" required>
                <div class="invalid-feedback">Please enter the role name.</div>
              </div>

              <!-- Role Code -->
              <div class="col-md-6 col-12">
                <label for="role_code" class="form-label">Role Code <span class="text-danger">*</span></label>
                <input type="text" name="role_code" id="role_code" class="form-control" placeholder="e.g. FINANCE_OFFICER" value="{{ old('role_code', $role->role_code) }}" required {{ $role->role_code === 'SUPER_ADMIN' ? 'disabled' : '' }}>
                <small class="text-muted">Must be unique, uppercase, with no spaces.</small>
                <div class="invalid-feedback">Please enter a unique uppercase role code.</div>
              </div>

              <!-- Description -->
              <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Provide a short description of what this role does...">{{ old('description', $role->description) }}</textarea>
              </div>

              <!-- Status -->
              <div class="col-md-6 col-12">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select" required {{ $role->role_code === 'SUPER_ADMIN' ? 'disabled' : '' }}>
                  <option value="active" {{ old('status', $role->status) == 'active' ? 'selected' : '' }}>Active</option>
                  <option value="inactive" {{ old('status', $role->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <div class="invalid-feedback">Please select the status.</div>
              </div>

              <!-- Submit -->
              <div class="col-12 d-flex gap-2 justify-content-end mt-5">
                <a href="{{ route('roles.index') }}" class="btn btn-light border">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Update Role</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
