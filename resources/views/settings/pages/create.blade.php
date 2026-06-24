@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-12">
      <a href="{{ route('pages.index') }}" class="btn btn-sm btn-light border mb-3 d-inline-flex align-items-center gap-1">
        <i class="ti ti-arrow-left"></i> Back to List
      </a>
      <h1 class="mb-1 fw-bold">Register New Page</h1>
      <p class="mb-0 text-muted">Register a new system view or functional screen to assign dynamic permissions.</p>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-xl-8 col-12">
      <div class="card border-1 shadow-sm">
        <div class="card-body p-5">
          <form action="{{ route('pages.store') }}" method="POST" class="needs-validation" novalidate>
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
              <!-- Page Name -->
              <div class="col-md-6 col-12">
                <label for="page_name" class="form-label">Page Name <span class="text-danger">*</span></label>
                <input type="text" name="page_name" id="page_name" class="form-control" placeholder="e.g. Sales Report" value="{{ old('page_name') }}" required>
                <div class="invalid-feedback">Please enter the page name.</div>
              </div>

              <!-- Page Code -->
              <div class="col-md-6 col-12">
                <label for="page_code" class="form-label">Page Code <span class="text-danger">*</span></label>
                <input type="text" name="page_code" id="page_code" class="form-control" placeholder="e.g. PAGE_SALES_REPORT" value="{{ old('page_code') }}" required>
                <small class="text-muted">Must be unique, uppercase, with no spaces.</small>
                <div class="invalid-feedback">Please enter a unique uppercase page code.</div>
              </div>

              <!-- Parent Menu Mapping -->
              <div class="col-md-6 col-12">
                <label for="menu_id" class="form-label">Associated Sidebar Menu <small class="text-muted">(Optional)</small></label>
                <select name="menu_id" id="menu_id" class="form-select">
                  <option value="">None / Standalone Page</option>
                  @foreach($menus as $menu)
                    <option value="{{ $menu->id }}" {{ old('menu_id') == $menu->id ? 'selected' : '' }}>{{ $menu->menu_name }}</option>
                  @endforeach
                </select>
                <small class="text-muted">Used to render this page in a specific navigation category.</small>
              </div>

              <!-- Route Path -->
              <div class="col-md-6 col-12">
                <label for="route_path" class="form-label">Route Path / URI <small class="text-muted">(Optional)</small></label>
                <input type="text" name="route_path" id="route_path" class="form-control" placeholder="e.g. /sales/report" value="{{ old('route_path') }}">
                <small class="text-muted">Required if you want the permission middleware to dynamically authorize this path.</small>
              </div>

              <!-- Component Name -->
              <div class="col-md-6 col-12">
                <label for="component_name" class="form-label">Component / Controller Class <small class="text-muted">(Optional)</small></label>
                <input type="text" name="component_name" id="component_name" class="form-control" placeholder="e.g. SalesReportController" value="{{ old('component_name') }}">
              </div>

              <!-- Status -->
              <div class="col-md-6 col-12">
                <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                <select name="is_active" id="is_active" class="form-select" required>
                  <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active (Enforce permissions)</option>
                  <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Disabled (Bypass checks)</option>
                </select>
                <div class="invalid-feedback">Please select the page status.</div>
              </div>

              <!-- Submit -->
              <div class="col-12 d-flex gap-2 justify-content-end mt-5">
                <a href="{{ route('pages.index') }}" class="btn btn-light border">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Register Page</button>
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
  document.getElementById('page_name').addEventListener('input', function() {
    const nameVal = this.value;
    const codeInput = document.getElementById('page_code');
    codeInput.value = 'PAGE_' + nameVal.toUpperCase().replace(/[^A-Z0-9]/g, '_');
  });
});
</script>
@endsection
