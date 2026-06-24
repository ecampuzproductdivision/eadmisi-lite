@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-12">
      <a href="{{ route('roles.index') }}" class="btn btn-sm btn-light border mb-3 d-inline-flex align-items-center gap-1">
        <i class="ti ti-arrow-left"></i> Back to Roles
      </a>
      <h1 class="mb-1 fw-bold">System Access Privilege Matrix</h1>
      <p class="mb-0 text-muted">A complete cross-reference matrix comparing functional permissions across active roles.</p>
    </div>
  </div>

  <!-- Matrix Card -->
  <div class="card border-1 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle text-nowrap mb-0 table-hover table-bordered">
        <thead class="table-light">
          <tr>
            <th scope="col" class="py-3" style="width: 25%;">Page Name / Category</th>
            <th scope="col" class="py-3" style="width: 15%;">Action</th>
            @foreach($roles as $role)
              <th scope="col" class="text-center py-3">
                <span class="fw-bold">{{ $role->role_name }}</span>
                <br>
                <code class="small text-muted">{{ $role->role_code }}</code>
              </th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @forelse($pages as $page)
            @php
              $menuName = $page->menu ? $page->menu->menu_name : 'No Category';
            @endphp
            @foreach($permissions as $index => $perm)
              <tr>
                @if($index === 0)
                  <td rowspan="{{ count($permissions) }}" class="fw-semibold text-dark align-top bg-light-subtle py-3">
                    <div class="d-flex flex-column">
                      <span>{{ $page->page_name }}</span>
                      <small class="text-muted fs-6">{{ $menuName }}</small>
                    </div>
                  </td>
                @endif
                <td>
                  <span class="text-capitalize small fw-semibold bg-light py-1 px-2 border rounded">{{ $perm->permission_name }}</span>
                </td>
                @foreach($roles as $role)
                  <td class="text-center">
                    @if($role->role_code === 'SUPER_ADMIN')
                      <i class="ti ti-circle-check-filled text-success fs-3" title="All Permissions Granted"></i>
                    @else
                      @php
                        $granted = isset($matrix[$role->id][$page->id][$perm->id]);
                      @endphp
                      @if($granted)
                        <i class="ti ti-check text-success fw-bold fs-4" title="Granted"></i>
                      @else
                        <span class="text-muted-subtle">—</span>
                      @endif
                    @endif
                  </td>
                @endforeach
              </tr>
            @endforeach
          @empty
            <tr>
              <td colspan="{{ count($roles) + 2 }}" class="text-center py-5">
                <i class="ti ti-table-off text-muted" style="font-size: 3rem;"></i>
                <p class="mt-2 mb-0 text-muted">No pages defined in the system.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</main>
@endsection
