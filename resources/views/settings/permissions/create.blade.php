@extends('layouts.app')

@section('content')
<main class="p-2">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Settings</a></li>
            <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permissions</a></li>
            <li class="breadcrumb-item active">Register Action Permission</li>
        </ol>
    </nav>
    <hr>

    {{-- Title with Back Button --}}
    <div class="d-flex align-items-top gap-3 my-5">
        <a href="{{ route('permissions.index') }}" class="btn btn-light d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 36px; height: 36px;" title="Back to List">
            <i class="ti ti-arrow-left fs-5"></i>
        </a>
        <div>
            <h1 class="mb-1 fw-bold">Register Action Permission</h1>
            <p class="text-muted mb-0">Register a new global action capability token (e.g. approve, print, download).</p>
        </div>
    </div>

    {{-- Card Form --}}
    <div class="card border-1 shadow-sm px-4 py-4">
        <div class="col-xl-12 col-12">
            <form action="{{ route('permissions.store') }}" method="POST" class="needs-validation" novalidate>
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
                    {{-- Section: Permission Information --}}
                    <div class="col-12">
                        <h6 class="text-secondary fw-bold pb-2 mb-0" style="border-bottom: 1px dashed #dee2e6;">Permission Information</h6>
                    </div>
                    <div class="col-md-6 col-12">
                        <label for="permission_name" class="form-label">Permission / Action Name <span class="text-danger">*</span></label>
                        <input type="text" name="permission_name" id="permission_name" class="form-control" placeholder="e.g. print, download, export" value="{{ old('permission_name') }}" required>
                        <small class="text-muted">Use lowercase alphanumeric words, no spaces. Must be unique.</small>
                        <div class="invalid-feedback">Please enter a valid lowercase action name.</div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('permissions.index') }}" class="btn btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Register Action</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('permission_name');
  input.addEventListener('input', function() {
    this.value = this.value.toLowerCase().replace(/[^a-z0-9_]/g, '');
  });
});
</script>
@endsection
