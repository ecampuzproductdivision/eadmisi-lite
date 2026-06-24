@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-12">
      <a href="{{ route('permissions.index') }}" class="btn btn-sm btn-light border mb-3 d-inline-flex align-items-center gap-1">
        <i class="ti ti-arrow-left"></i> Back to List
      </a>
      <h1 class="mb-1 fw-bold">Register Action Permission</h1>
      <p class="mb-0 text-muted">Register a new global action capability token (e.g. approve, print, download).</p>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-xl-8 col-12">
      <div class="card border-1 shadow-sm">
        <div class="card-body p-5">
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

            <div class="row g-4">
              <!-- Permission Name -->
              <div class="col-12">
                <label for="permission_name" class="form-label">Permission / Action Name <span class="text-danger">*</span></label>
                <input type="text" name="permission_name" id="permission_name" class="form-control" placeholder="e.g. print, download, export" value="{{ old('permission_name') }}" required>
                <small class="text-muted">Use lowercase alphanumeric words, no spaces. Must be unique.</small>
                <div class="invalid-feedback">Please enter a valid lowercase action name.</div>
              </div>

              <!-- Submit -->
              <div class="col-12 d-flex gap-2 justify-content-end mt-5">
                <a href="{{ route('permissions.index') }}" class="btn btn-light border">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Register Action</button>
              </div>
            </div>
          </form>
        </div>
      </div>
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
