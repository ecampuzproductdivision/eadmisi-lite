@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-12">
      <a href="{{ route('roles.index') }}" class="btn btn-sm btn-light border mb-3 d-inline-flex align-items-center gap-1">
        <i class="ti ti-arrow-left"></i> Back to Roles
      </a>
      <h1 class="mb-1 fw-bold">Set Access Permissions</h1>
      <p class="mb-0 text-muted">Grant dynamic menu, page, and action privileges for role: <span class="badge bg-danger-subtle text-danger fs-6 border border-danger-subtle">{{ $role->role_name }}</span></p>
    </div>
  </div>

  <form action="{{ route('roles.permissions.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Quick controls -->
    <div class="card mb-6 border-0 shadow-sm">
      <div class="card-body py-3 d-flex flex-wrap gap-2 justify-content-between align-items-center">
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-sm btn-light border" id="select-all">Select All Checkboxes</button>
          <button type="button" class="btn btn-sm btn-light border" id="deselect-all">Deselect All</button>
        </div>
        <div>
          <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="ti ti-device-floppy fs-4"></i> Save Permissions
          </button>
        </div>
      </div>
    </div>

    <!-- Matrix Grid Card -->
    <div class="card border-0 shadow-sm">
      <div class="table-responsive">
        <table class="table align-middle text-nowrap mb-0 table-hover">
          <thead class="table-light">
            <tr>
              <th scope="col" style="width: 30%;">Page Name / Code</th>
              @foreach($permissions as $perm)
                <th scope="col" class="text-center">
                  <div class="d-flex flex-column align-items-center">
                    <span class="text-capitalize mb-1">{{ $perm->permission_name }}</span>
                    <input class="form-check-input col-select-all" type="checkbox" data-col="{{ $perm->id }}" title="Toggle all {{ $perm->permission_name }} permissions">
                  </div>
                </th>
              @endforeach
              <th scope="col" class="text-center">Row Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pages as $category => $categoryPages)
              <!-- Category Separator Row -->
              <tr class="table-secondary">
                <td colspan="{{ count($permissions) + 2 }}" class="fw-bold py-2 text-dark">
                  <i class="ti ti-folder fs-5 me-1"></i> {{ $category }}
                </td>
              </tr>
              
              @foreach($categoryPages as $page)
                <tr class="page-row">
                  <td>
                    <div class="ps-3">
                      <h5 class="mb-0 fw-semibold text-dark">{{ $page->page_name }}</h5>
                      <code class="text-muted small">{{ $page->page_code }}</code>
                    </div>
                  </td>
                  
                  @foreach($permissions as $perm)
                    @php
                      $hasPermission = isset($activePermissions[$page->id]) && in_array($perm->id, $activePermissions[$page->id]);
                    @endphp
                    <td class="text-center">
                      <input class="form-check-input perm-checkbox col-{{ $perm->id }}" type="checkbox" 
                        name="permissions[{{ $page->id }}][{{ $perm->id }}]" 
                        value="1" 
                        {{ $hasPermission ? 'checked' : '' }}
                        data-page="{{ $page->id }}" 
                        data-perm="{{ $perm->id }}">
                    </td>
                  @endforeach

                  <td class="text-center">
                    <button type="button" class="btn btn-xs btn-light border py-1 px-2 row-select-all" data-row="{{ $page->id }}">Toggle Row</button>
                  </td>
                </tr>
              @endforeach
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="card-footer bg-white border-0 py-4 text-end">
        <a href="{{ route('roles.index') }}" class="btn btn-light border me-2">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">Save Permissions</button>
      </div>
    </div>
  </form>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const selectAllBtn = document.getElementById('select-all');
  const deselectAllBtn = document.getElementById('deselect-all');
  const checkboxes = document.querySelectorAll('.perm-checkbox');
  const colToggles = document.querySelectorAll('.col-select-all');
  const rowToggles = document.querySelectorAll('.row-select-all');

  // Select all global
  selectAllBtn.addEventListener('click', function() {
    checkboxes.forEach(cb => cb.checked = true);
    colToggles.forEach(ct => ct.checked = true);
  });

  // Deselect all global
  deselectAllBtn.addEventListener('click', function() {
    checkboxes.forEach(cb => cb.checked = false);
    colToggles.forEach(ct => ct.checked = false);
  });

  // Column wise toggle
  colToggles.forEach(toggle => {
    toggle.addEventListener('change', function() {
      const colId = this.getAttribute('data-col');
      const checked = this.checked;
      document.querySelectorAll(`.col-${colId}`).forEach(cb => {
        cb.checked = checked;
      });
    });
  });

  // Row wise toggle
  rowToggles.forEach(toggle => {
    toggle.addEventListener('click', function() {
      const rowId = this.getAttribute('data-row');
      const rowCheckboxes = document.querySelectorAll(`input[type="checkbox"][data-page="${rowId}"]`);
      
      // Determine if at least one checkbox in row is checked
      let anyChecked = false;
      rowCheckboxes.forEach(cb => {
        if (cb.checked) anyChecked = true;
      });

      // Toggle based on current state
      rowCheckboxes.forEach(cb => {
        cb.checked = !anyChecked;
      });
    });
  });
});
</script>
@endsection
