@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-12">
      <a href="{{ route('users.index') }}" class="btn btn-sm btn-light border mb-3 d-inline-flex align-items-center gap-1">
        <i class="ti ti-arrow-left"></i> Back to List
      </a>
      <h1 class="mb-1 fw-bold">Edit User</h1>
      <p class="mb-0 text-muted">Update profile information, status, and role assignments for <strong>{{ $user->name }}</strong>.</p>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-xl-8 col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-5">
          <form action="{{ route('users.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
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
              <!-- Name -->
              <div class="col-md-6 col-12">
                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="e.g. John Doe" value="{{ old('name', $user->name) }}" required>
                <div class="invalid-feedback">Please enter the user's name.</div>
              </div>

              <!-- Email -->
              <div class="col-md-6 col-12">
                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control" placeholder="e.g. johndoe@company.com" value="{{ old('email', $user->email) }}" required>
                <div class="invalid-feedback">Please enter a valid email address.</div>
              </div>

              <!-- Password -->
              <div class="col-md-6 col-12">
                <label for="password" class="form-label">Password <small class="text-muted">(Leave empty to keep current password)</small></label>
                <input type="password" name="password" id="password" class="form-control">
              </div>

              <!-- Password Confirmation -->
              <div class="col-md-6 col-12">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
              </div>

              <!-- Status -->
              <div class="col-md-6 col-12">
                <label for="status" class="form-label">Account Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select" required {{ auth()->id() == $user->id ? 'disabled' : '' }}>
                  <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                  <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @if(auth()->id() == $user->id)
                  <input type="hidden" name="status" value="active">
                @endif
                <div class="invalid-feedback">Please select the status.</div>
              </div>

              <!-- Assign Roles -->
              <div class="col-12">
                <label class="form-label d-block fw-semibold mb-2">Assign Roles <span class="text-danger">*</span></label>
                <div class="row g-3">
                  @forelse($roles as $role)
                    <div class="col-md-4 col-sm-6 col-12">
                      <div class="card p-3 border rounded shadow-none h-100">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}" 
                            {{ (is_array(old('roles')) && in_array($role->id, old('roles'))) || (!is_array(old('roles')) && in_array($role->id, $userRoleIds)) ? 'checked' : '' }}>
                          <label class="form-check-label fw-bold text-dark mb-1" for="role_{{ $role->id }}">
                            {{ $role->role_name }}
                          </label>
                          <p class="mb-0 text-muted small">{{ $role->description ?: 'No description provided.' }}</p>
                        </div>
                      </div>
                    </div>
                  @empty
                    <div class="col-12 text-center py-3">
                      <p class="text-muted mb-0">No active roles found. Please <a href="{{ route('roles.create') }}">create a role</a> first.</p>
                    </div>
                  @endforelse
                </div>
              </div>

              <!-- Submit -->
              <div class="col-12 d-flex gap-2 justify-content-end mt-5">
                <a href="{{ route('users.index') }}" class="btn btn-light border">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Update User</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
