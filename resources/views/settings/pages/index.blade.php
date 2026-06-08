@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-md-6 col-12">
      <h1 class="mb-1 fw-bold">Page & Feature Configuration</h1>
      <p class="mb-0 text-muted">Register system routes/components and map them to their parent menus for permission tracking.</p>
    </div>
    <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
      <a href="{{ route('pages.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <i class="ti ti-plus fs-4"></i> Add New Page
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

  <!-- Pages Table Card -->
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle text-nowrap mb-0 table-hover">
        <thead class="table-light">
          <tr>
            <th scope="col" class="py-3">Page Name</th>
            <th scope="col" class="py-3">Page Code</th>
            <th scope="col" class="py-3">Parent Menu</th>
            <th scope="col" class="py-3">Route Path</th>
            <th scope="col" class="py-3">Component / View</th>
            <th scope="col" class="py-3">Status</th>
            <th scope="col" class="py-3 text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pages as $page)
            <tr>
              <td>
                <div class="d-flex align-items-center gap-3">
                  <div class="avatar avatar-md bg-light text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                    <i class="ti ti-file fs-4"></i>
                  </div>
                  <div>
                    <h5 class="mb-0 fw-semibold">{{ $page->page_name }}</h5>
                  </div>
                </div>
              </td>
              <td><code>{{ $page->page_code }}</code></td>
              <td>
                @if($page->menu)
                  <span class="badge bg-light border text-dark py-1 px-2 rounded">{{ $page->menu->menu_name }}</span>
                @else
                  <span class="text-muted small">—</span>
                @endif
              </td>
              <td><code>{{ $page->route_path ?: '—' }}</code></td>
              <td><code>{{ $page->component_name ?: '—' }}</code></td>
              <td>
                <span class="badge {{ $page->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border rounded py-1 px-2">
                  {{ $page->is_active ? 'Active' : 'Disabled' }}
                </span>
              </td>
              <td class="text-end">
                <div class="d-inline-flex gap-2">
                  <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-sm btn-light border" title="Edit">
                    <i class="ti ti-edit fs-5"></i>
                  </a>
                  
                  <form action="{{ route('pages.destroy', $page->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page registration?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete">
                      <i class="ti ti-trash fs-5"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-5">
                <i class="ti ti-file-off text-muted" style="font-size: 3rem;"></i>
                <p class="mt-2 mb-0 text-muted">No registered pages found.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    @if($pages->hasPages())
      <div class="card-footer bg-white border-0 py-3">
        {{ $pages->links() }}
      </div>
    @endif
  </div>
</main>
@endsection
