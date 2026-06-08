@foreach($users as $user)
  <tr>
    <td>
      <div class="d-flex align-items-center gap-3">
        <div class="avatar avatar-md bg-danger-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
          {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <div>
          <h5 class="mb-0 fw-semibold">{{ $user->name }}</h5>
        </div>
      </div>
    </td>
    <td>{{ $user->email }}</td>
    <td>
      <div class="d-flex flex-wrap gap-1">
        @forelse($user->roles as $role)
          <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle py-1 px-2 rounded">{{ $role->role_name }}</span>
        @empty
          <span class="text-muted small">No Role Assigned</span>
        @endforelse
      </div>
    </td>
    <td>
      <div class="form-check form-switch mb-0">
        <input class="form-check-input status-toggle" type="checkbox" role="switch" 
          data-url="{{ route('users.toggle-status', $user->id) }}" 
          {{ $user->status == 'active' ? 'checked' : '' }}
          {{ auth()->id() == $user->id ? 'disabled' : '' }}>
        <label class="form-check-label status-label text-capitalize {{ $user->status == 'active' ? 'text-success' : 'text-danger' }} fw-semibold small ms-1">
          {{ $user->status }}
        </label>
      </div>
    </td>
    <td>{{ $user->created_at->format('M d, Y') }}</td>
    <td class="text-end">
      <div class="d-inline-flex gap-2">
        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-light border" title="Edit">
          <i class="ti ti-edit fs-5"></i>
        </a>
        @if(auth()->id() != $user->id)
          <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete">
              <i class="ti ti-trash fs-5"></i>
            </button>
          </form>
        @endif
      </div>
    </td>
  </tr>
@endforeach
