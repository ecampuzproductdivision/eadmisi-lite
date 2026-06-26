@extends('layouts.app')

@section('content')
<main class="p-2">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Settings</a></li>
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item active">Create New Role</li>
        </ol>
    </nav>
    <hr>

    {{-- Title with Back Button --}}
    <div class="d-flex align-items-top gap-3 my-5">
        <a href="{{ route('roles.index') }}" class="btn btn-light d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 36px; height: 36px;" title="Back to List">
            <i class="ti ti-arrow-left fs-5"></i>
        </a>
        <div>
            <h1 class="mb-1 fw-bold">Create New Role</h1>
            <p class="text-muted mb-0">Create a new access level role. You will configure page permissions in the next step.</p>
        </div>
    </div>

    {{-- Card Form --}}
    <div class="card border-1 shadow-sm px-4 py-4">
        <div class="col-xl-12 col-12">
            <form action="{{ route('roles.store') }}" method="POST" class="needs-validation" novalidate>
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
                    {{-- Row 1: Role Name, Role Code, Status --}}
                    <div class="col-md-3 col-12">
                        <label for="role_name" class="form-label">Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="role_name" id="role_name" class="form-control" placeholder="e.g. Finance Officer" value="{{ old('role_name') }}" required>
                        <div class="invalid-feedback">Please enter the role name.</div>
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="role_code" class="form-label">Role Code <span class="text-danger">*</span></label>
                        <input type="text" name="role_code" id="role_code" class="form-control" placeholder="e.g. FINANCE_OFFICER" value="{{ old('role_code') }}" required>
                        <small class="text-muted">Must be unique, uppercase, with no spaces.</small>
                        <div class="invalid-feedback">Please enter a unique uppercase role code.</div>
                    </div>

                    <div class="col-md-3 col-12">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <div class="invalid-feedback">Please select the status.</div>
                    </div>

                    {{-- Row 2: Description (full width) --}}
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Provide a short description of what this role does...">{{ old('description') }}</textarea>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('roles.index') }}" class="btn btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Create Role</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
document.getElementById('role_name').addEventListener('input', function() {
  const nameVal = this.value;
  const codeInput = document.getElementById('role_code');
  codeInput.value = nameVal.toUpperCase().replace(/[^A-Z0-9]/g, '_');
});
</script>
@endsection
