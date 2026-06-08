@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-md-6 col-12">
      <h1 class="mb-1 fw-bold">Sidebar Menu & Navigation</h1>
      <p class="mb-0 text-muted">Manage main sidebar headings, icons, nested sub-items, and layouts.</p>
    </div>
    <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
      <a href="{{ route('menus.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <i class="ti ti-plus fs-4"></i> Add New Menu
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Nested Menu Structure -->
  <div class="row">
    <div class="col-xl-8 col-12">
      <div class="card border-0 shadow-sm p-4">
        <h5 class="fw-bold mb-4">Sidebar Structure</h5>
        
        <div class="menu-list">
          @forelse($menus as $menu)
            <!-- Root Menu Item -->
            <div class="card border mb-3 shadow-none">
              <div class="card-body py-3 d-flex align-items-center justify-content-between bg-light-subtle">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-primary-subtle text-primary py-2 px-3 rounded">
                    @if(str_contains($menu->icon ?? '', 'ti-'))
                      <i class="ti {{ $menu->icon }} fs-4"></i>
                    @else
                      <i class="ti ti-folder fs-4"></i>
                    @endif
                  </div>
                  <div>
                    <h5 class="mb-0 fw-semibold text-dark">{{ $menu->menu_name }}</h5>
                    <div class="d-flex gap-2 align-items-center">
                      <code class="small text-muted">{{ $menu->menu_code }}</code>
                      <span class="text-muted">•</span>
                      <span class="text-muted small">URL: {{ $menu->url ?: '—' }}</span>
                      <span class="text-muted">•</span>
                      <span class="badge bg-light border text-dark py-1 px-2 rounded small">Order: {{ $menu->sort_order }}</span>
                    </div>
                  </div>
                </div>
                
                <div class="d-flex gap-2 align-items-center">
                  <span class="badge {{ $menu->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border rounded py-1 px-2">
                    {{ $menu->is_active ? 'Active' : 'Hidden' }}
                  </span>
                  
                  <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-xs btn-light border py-1 px-2" title="Edit">
                    <i class="ti ti-edit"></i>
                  </a>
                  
                  <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Deleting parent menu will also delete all its submenus. Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-xs btn-light border text-danger py-1 px-2" title="Delete">
                      <i class="ti ti-trash"></i>
                    </button>
                  </form>
                </div>
              </div>

              <!-- Children Nested Container -->
              @if($menu->children->isNotEmpty())
                <div class="card-footer bg-white border-top-0 ps-5 py-3">
                  <div class="d-flex flex-column gap-2">
                    @foreach($menu->children as $child)
                      <div class="border rounded p-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                          <i class="ti ti-corner-down-right text-muted fs-4 me-1"></i>
                          @if($child->icon)
                            <i class="ti {{ $child->icon }} text-muted me-2"></i>
                          @endif
                          <div>
                            <h6 class="mb-0 fw-semibold text-dark">{{ $child->menu_name }}</h6>
                            <div class="d-flex gap-2 align-items-center">
                              <code class="small text-muted">{{ $child->menu_code }}</code>
                              <span class="text-muted">•</span>
                              <span class="text-muted small">URL: {{ $child->url }}</span>
                              <span class="text-muted">•</span>
                              <span class="badge bg-light border text-dark py-1 px-2 rounded small">Order: {{ $child->sort_order }}</span>
                            </div>
                          </div>
                        </div>

                        <div class="d-flex gap-2 align-items-center">
                          <span class="badge {{ $child->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border rounded py-1 px-2">
                            {{ $child->is_active ? 'Active' : 'Hidden' }}
                          </span>
                          
                          <a href="{{ route('menus.edit', $child->id) }}" class="btn btn-xs btn-light border py-1 px-2" title="Edit">
                            <i class="ti ti-edit"></i>
                          </a>
                          
                          <form action="{{ route('menus.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this submenu?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-light border text-danger py-1 px-2" title="Delete">
                              <i class="ti ti-trash"></i>
                            </button>
                          </form>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif
            </div>
          @empty
            <div class="text-center py-5">
              <i class="ti ti-menu-off text-muted" style="font-size: 3rem;"></i>
              <p class="mt-2 mb-0 text-muted">No sidebar menus found.</p>
            </div>
          @endforelse
        </div>
      </div>
    </div>
    
    <!-- Info Column -->
    <div class="col-xl-4 col-12 mt-4 mt-xl-0">
      <div class="card border-0 shadow-sm bg-danger-subtle text-danger-emphasis border-start border-danger border-4">
        <div class="card-body">
          <h5 class="fw-bold mb-3 d-flex align-items-center gap-2"><i class="ti ti-info-circle fs-4"></i> Sidebar Guidelines</h5>
          <p class="mb-2 small">1. <strong>Parent Menu</strong> acts as a dropdown folder if it has children. If it doesn't, it acts as a direct link.</p>
          <p class="mb-2 small">2. <strong>Icons</strong> are rendered using Tabler Icons. Type any class name like <code>ti-settings</code> or <code>ti-users</code>.</p>
          <p class="mb-0 small">3. <strong>Sort Order</strong> determines the rendering sequence on the sidebar (ascending).</p>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
