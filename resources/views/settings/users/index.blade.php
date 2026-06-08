@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6 align-items-center">
    <div class="col-md-6 col-12">
      <h1 class="mb-1 fw-bold">User Management</h1>
      <p class="mb-0 text-muted">Manage system users, assign multiple roles, and toggle their account active states.</p>
    </div>
    <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
      <a href="{{ route('users.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <i class="ti ti-plus fs-4"></i> Add New User
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

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-triangle fs-4 me-2"></i>
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Filters & Search -->
  <div class="card mb-6 border-0 shadow-sm">
    <div class="card-body">
      <form action="{{ route('users.index') }}" method="GET" class="row g-3">
        <div class="col-md-4 col-12">
          <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
            <input type="text" name="search" class="form-control border-start-0" placeholder="Search name or email..." value="{{ request('search') }}">
          </div>
        </div>
        <div class="col-md-3 col-12">
          <select name="status" class="form-select">
            <option value="">All Statuses</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
        <div class="col-md-3 col-12">
          <select name="role" class="form-select">
            <option value="">All Roles</option>
            @foreach($roles as $role)
              <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ $role->role_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 col-12 d-grid">
          <button type="submit" class="btn btn-light border">Filter</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Users Table -->
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle text-nowrap mb-0 table-hover">
        <thead class="table-light">
          <tr>
            <th scope="col" class="py-3">Name</th>
            <th scope="col" class="py-3">Email</th>
            <th scope="col" class="py-3">Roles</th>
            <th scope="col" class="py-3">Status</th>
            <th scope="col" class="py-3">Joined Date</th>
            <th scope="col" class="py-3 text-end">Actions</th>
          </tr>
        </thead>
        <tbody id="user-table-body">
          @if($users->isEmpty())
            <tr id="no-users-row">
              <td colspan="6" class="text-center py-5">
                <i class="ti ti-user-x text-muted" style="font-size: 3rem;"></i>
                <p class="mt-2 mb-0 text-muted">No users found.</p>
              </td>
            </tr>
          @else
            @include('settings.users.partials.user_rows')
          @endif
        </tbody>
      </table>
    </div>

    <!-- Spinner loading indicator -->
    <div id="loading-spinner" class="text-center py-4 d-none">
      <div class="spinner-border text-danger" role="status" style="width: 2rem; height: 2rem;">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    
    <!-- Fallback Pagination Container -->
    <div id="pagination-container">
      @if($users->hasPages())
        <div class="card-footer bg-white border-0 py-3">
          {{ $users->links() }}
        </div>
      @endif
    </div>
  </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // 1. Setup toggle listener registration helper
  function registerStatusToggle(toggle) {
    // Avoid double registering
    if (toggle.hasAttribute('data-registered')) return;
    toggle.setAttribute('data-registered', 'true');

    toggle.addEventListener('change', function() {
      const url = this.getAttribute('data-url');
      const label = this.parentElement.querySelector('.status-label');
      
      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          label.textContent = data.status;
          if (data.status === 'active') {
            label.className = 'form-check-label status-label text-success fw-semibold small ms-1';
          } else {
            label.className = 'form-check-label status-label text-danger fw-semibold small ms-1';
          }
        } else {
          alert(data.message || 'Failed to update user status.');
          this.checked = !this.checked;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the user status.');
        this.checked = !this.checked;
      });
    });
  }

  // Register initial toggles
  document.querySelectorAll('.status-toggle').forEach(registerStatusToggle);

  // 2. Setup Infinite Scroll parameters
  let nextPageUrl = '{{ $users->nextPageUrl() }}';
  let hasMore = {{ $users->hasMorePages() ? 'true' : 'false' }};
  let isLoading = false;

  const spinner = document.getElementById('loading-spinner');
  const paginationContainer = document.getElementById('pagination-container');
  const tableBody = document.getElementById('user-table-body');

  // Hide traditional pagination links if JavaScript runs for seamless UX
  if (paginationContainer) {
    paginationContainer.classList.add('d-none');
  }

  // Scroll listener function
  function handleScroll() {
    if (isLoading || !hasMore || !nextPageUrl) return;

    // Trigger when user scrolls within 100px of the page bottom
    if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 100) {
      loadMoreUsers();
    }
  }

  // Fetch next set of pages via AJAX
  function loadMoreUsers() {
    isLoading = true;
    spinner.classList.remove('d-none');

    fetch(nextPageUrl, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.html) {
        // Append newly fetched rows using safe element selection
        const tempDiv = document.createElement('tbody');
        tempDiv.innerHTML = data.html;
        
        const rows = tempDiv.querySelectorAll('tr');
        rows.forEach(row => {
          tableBody.appendChild(row);
          // Register dynamic toggle listener on newly loaded element
          const toggle = row.querySelector('.status-toggle');
          if (toggle) registerStatusToggle(toggle);
        });
      }

      // Update next page pointers and release loading lock
      nextPageUrl = data.next_page;
      hasMore = data.has_more;
      isLoading = false;
      spinner.classList.add('d-none');
    })
    .catch(error => {
      console.error('Error fetching users:', error);
      isLoading = false;
      spinner.classList.add('d-none');
    });
  }

  // Register scroll listener on window
  window.addEventListener('scroll', handleScroll);
});
</script>
@endsection
