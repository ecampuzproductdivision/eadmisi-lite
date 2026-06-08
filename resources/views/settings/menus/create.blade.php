@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-12">
      <a href="{{ route('menus.index') }}" class="btn btn-sm btn-light border mb-3 d-inline-flex align-items-center gap-1">
        <i class="ti ti-arrow-left"></i> Back to List
      </a>
      <h1 class="mb-1 fw-bold">Add Navigation Menu</h1>
      <p class="mb-0 text-muted">Create a new sidebar section or nested link in the navigation menu.</p>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-xl-8 col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-5">
          <form action="{{ route('menus.store') }}" method="POST" class="needs-validation" novalidate>
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
              <!-- Menu Name -->
              <div class="col-md-6 col-12">
                <label for="menu_name" class="form-label">Menu Name <span class="text-danger">*</span></label>
                <input type="text" name="menu_name" id="menu_name" class="form-control" placeholder="e.g. Invoicing" value="{{ old('menu_name') }}" required>
                <div class="invalid-feedback">Please enter the menu name.</div>
              </div>

              <!-- Menu Code -->
              <div class="col-md-6 col-12">
                <label for="menu_code" class="form-label">Menu Code <span class="text-danger">*</span></label>
                <input type="text" name="menu_code" id="menu_code" class="form-control" placeholder="e.g. INVOICING" value="{{ old('menu_code') }}" required>
                <div class="invalid-feedback">Please enter a unique menu code.</div>
              </div>

              <!-- Parent Menu -->
              <div class="col-md-6 col-12">
                <label for="parent_id" class="form-label">Parent Menu <small class="text-muted">(Optional)</small></label>
                <select name="parent_id" id="parent_id" class="form-select">
                  <option value="">None (Root Menu)</option>
                  @foreach($parentMenus as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->menu_name }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Icon -->
              <div class="col-md-6 col-12">
                <label for="icon" class="form-label">Icon Class <small class="text-muted">(Optional)</small></label>
                <div class="input-group">
                  <span class="input-group-text bg-transparent"><i class="ti ti-settings text-muted" id="icon-preview"></i></span>
                  <input type="text" name="icon" id="icon" class="form-control" placeholder="e.g. ti-settings" value="{{ old('icon') }}">
                </div>
                <small class="text-muted">Uses Tabler Icons. Example: <code>ti-user</code>, <code>ti-coin</code>, <code>ti-report</code>.</small>
              </div>

              <!-- URL -->
              <div class="col-md-6 col-12">
                <label for="url" class="form-label">URL Path</label>
                <input type="text" name="url" id="url" class="form-control" placeholder="e.g. /finance/invoicing" value="{{ old('url') }}">
                <small class="text-muted">Leave empty for folder menus that only serve as dropdown toggles.</small>
              </div>

              <!-- Sort Order -->
              <div class="col-md-3 col-12">
                <label for="sort_order" class="form-label">Sort Order <span class="text-danger">*</span></label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" required>
                <div class="invalid-feedback">Please specify the sorting weight.</div>
              </div>

              <!-- Status -->
              <div class="col-md-3 col-12">
                <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                <select name="is_active" id="is_active" class="form-select" required>
                  <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Show in Sidebar</option>
                  <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Hide</option>
                </select>
                <div class="invalid-feedback">Please select the menu status.</div>
              </div>

              <!-- Submit -->
              <div class="col-12 d-flex gap-2 justify-content-end mt-5">
                <a href="{{ route('menus.index') }}" class="btn btn-light border">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Create Menu</button>
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
  const iconInput = document.getElementById('icon');
  const iconPreview = document.getElementById('icon-preview');
  
  iconInput.addEventListener('input', function() {
    const val = this.value.trim();
    iconPreview.className = 'ti ' + (val ? val : 'ti-settings') + ' text-muted';
  });

  document.getElementById('menu_name').addEventListener('input', function() {
    const nameVal = this.value;
    const codeInput = document.getElementById('menu_code');
    codeInput.value = nameVal.toUpperCase().replace(/[^A-Z0-9]/g, '_');
  });
});
</script>
@endsection
