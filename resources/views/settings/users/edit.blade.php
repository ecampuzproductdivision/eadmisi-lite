@extends('layouts.app')

@section('content')
<main class="p-2">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Settings</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
            <li class="breadcrumb-item active">Edit User</li>
        </ol>
    </nav>
    <hr>

    {{-- Title with Back Button --}}
    <div class="d-flex align-items-top gap-3 my-5">
        <a href="{{ route('users.index') }}" class="btn btn-light d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 36px; height: 36px;" title="Back to List">
            <i class="ti ti-arrow-left fs-5"></i>
        </a>
        <div>
            <h1 class="mb-1 fw-bold">Edit User</h1>
            <p class="text-muted mb-0">Update profile information, status, and role assignments for <strong>{{ $user->name }}</strong>.</p>
        </div>
    </div>

    {{-- Card Form --}}
    <div class="card border-1 shadow-sm px-4 py-4">
        <div class="col-xl-12 col-12">
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

                <div class="row g-3">
                    {{-- Section: Account Information --}}
                    <div class="col-12">
                        <h6 class="text-secondary fw-bold pb-2 mb-0" style="border-bottom: 1px dashed #dee2e6;">Account Information</h6>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="e.g. John Doe" value="{{ old('name', $user->name) }}" required>
                        <div class="invalid-feedback">Please enter the user's name.</div>
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="e.g. johndoe@company.com" value="{{ old('email', $user->email) }}" required>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>

                    <div class="col-md-3 col-12">
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

                    <div class="col-md-3 col-12"></div>

                    {{-- Section: Security --}}
                    <div class="col-12">
                        <h6 class="text-secondary fw-bold pb-2 mb-0 mt-2" style="border-bottom: 1px dashed #dee2e6;">Security</h6>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="password" class="form-label">Password <small class="text-muted">(Leave empty to keep current)</small></label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>

                    <div class="col-md-6 col-12"></div>

                    {{-- Section: Role Assignment --}}
                    <div class="col-12">
                        <h6 class="text-secondary fw-bold pb-2 mb-0 mt-2" style="border-bottom: 1px dashed #dee2e6;">Role Assignment</h6>
                    </div>
                    <div class="col-12">
                        <label class="form-label d-block fw-semibold mb-2 mt-2">Assign Roles <span class="text-danger">*</span></label>
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

                    {{-- Submit --}}
                    <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Update User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection