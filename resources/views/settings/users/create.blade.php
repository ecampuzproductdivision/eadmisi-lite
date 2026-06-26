@extends('layouts.app')

@section('content')
<main class="p-2">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Settings</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
            <li class="breadcrumb-item active">Create New User</li>
        </ol>
    </nav>
    <hr>

    {{-- Title with Back Button --}}
    <div class="d-flex align-items-top gap-3 my-5">
        <a href="{{ route('users.index') }}" class="btn btn-light d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 36px; height: 36px;" title="Back to List">
            <i class="ti ti-arrow-left fs-5"></i>
        </a>
        <div>
            <h1 class="mb-1 fw-bold">Create New User</h1>
            <p class="text-muted mb-0">Create a new platform user account and assign roles.</p>
        </div>
    </div>

    {{-- Card Form --}}
    <div class="card border-1 shadow-sm px-4 py-4">
        <div class="col-xl-12 col-12">
            <form action="{{ route('users.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

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
                    {{-- Row 1: Name, Email, Status --}}
                    <div class="col-md-3 col-12">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="e.g. John Doe" value="{{ old('name') }}" required>
                        <div class="invalid-feedback">Please enter the user's name.</div>
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="e.g. johndoe@company.com" value="{{ old('email') }}" required>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="status" class="form-label">Account Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <div class="invalid-feedback">Please select the status.</div>
                    </div>

                    {{-- Row 2: Password, Confirm Password --}}
                    <div class="col-md-3 col-12">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        <div class="invalid-feedback">Please enter a password.</div>
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        <div class="invalid-feedback">Please confirm the password.</div>
                    </div>

                    {{-- Row 3: Assign Roles (full width) --}}
                    <div class="col-12">
                        <label class="form-label d-block fw-semibold mb-2">Assign Roles <span class="text-danger">*</span></label>
                        <div class="row g-3">
                            @forelse($roles as $role)
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="card p-3 border rounded shadow-none h-100">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}" 
                                                {{ is_array(old('roles')) && in_array($role->id, old('roles')) ? 'checked' : '' }}>
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

                    {{-- Submit Buttons --}}
                    <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Create User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
