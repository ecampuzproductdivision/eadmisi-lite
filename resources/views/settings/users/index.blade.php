@extends('layouts.app')

@section('content')
@component('components.data-page-layout')
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Settings', 'url' => '#'],
        ['label' => 'Users', 'active' => true],
    ])
    @slot('title', 'User Management')
    @slot('description', 'Manage system users, assign multiple roles, and toggle their account active states.')
    @slot('actions')
        <a href="{{ route('users.create') }}" class="btn btn-dark d-inline-flex align-items-center gap-2">
            <i class="ti ti-plus fs-4"></i> Add New User
        </a>
    @endslot
    @slot('filters')
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
        <div class="col-md-2 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Filter</button>
            <a href="{{ route('users.index') }}" class="btn btn-subtle-primary px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('users.index') }}?export=xls'">
            <i class="ti ti-file-spreadsheet"></i> .xls
        </a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
            <i class="ti ti-printer"></i> Print
        </a>
    @endslot
    @slot('table')
        <table class="table align-middle text-nowrap mb-0 table-hover table-ead">
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
    @endslot
    @slot('pagination')
        <div id="pagination-container">
            @if($users->hasPages())
                {{ $users->links() }}
            @endif
        </div>
    @endslot
@endcomponent

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function registerStatusToggle(toggle) {
        if (toggle.hasAttribute('data-registered')) return;
        toggle.setAttribute('data-registered', 'true');
        toggle.addEventListener('change', function() {
            const url = this.getAttribute('data-url');
            const label = this.parentElement.querySelector('.status-label');
            fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' } })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    label.textContent = data.status;
                    label.className = 'form-check-label status-label text-' + (data.status === 'active' ? 'success' : 'danger') + ' fw-semibold small ms-1';
                } else { alert(data.message || 'Failed to update user status.'); this.checked = !this.checked; }
            })
            .catch(error => { console.error('Error:', error); alert('An error occurred.'); this.checked = !this.checked; });
        });
    }
    document.querySelectorAll('.status-toggle').forEach(registerStatusToggle);

    let nextPageUrl = '{{ $users->nextPageUrl() }}';
    let hasMore = {{ $users->hasMorePages() ? 'true' : 'false' }};
    let isLoading = false;
    const spinner = document.getElementById('loading-spinner');
    const paginationContainer = document.getElementById('pagination-container');
    const tableBody = document.getElementById('user-table-body');
    if (paginationContainer) paginationContainer.classList.add('d-none');

    function handleScroll() {
        if (isLoading || !hasMore || !nextPageUrl) return;
        if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 100) loadMoreUsers();
    }

    function loadMoreUsers() {
        isLoading = true; if (spinner) spinner.classList.remove('d-none');
        fetch(nextPageUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                const tempDiv = document.createElement('tbody');
                tempDiv.innerHTML = data.html;
                tempDiv.querySelectorAll('tr').forEach(row => { tableBody.appendChild(row); const toggle = row.querySelector('.status-toggle'); if (toggle) registerStatusToggle(toggle); });
            }
            nextPageUrl = data.next_page; hasMore = data.has_more; isLoading = false; if (spinner) spinner.classList.add('d-none');
        })
        .catch(error => { console.error('Error:', error); isLoading = false; if (spinner) spinner.classList.add('d-none'); });
    }
    window.addEventListener('scroll', handleScroll);
});
</script>
@endpush
@endsection