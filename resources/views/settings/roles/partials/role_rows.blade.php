@forelse($roles as $role)
<tr>
    <td>
        <div class="d-flex align-items-center gap-3">
            <div class="avatar avatar-md bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                <i class="ti ti-shield fs-4"></i>
            </div>
            <div><span class="mb-0 fw-semibold">{{ $role->role_name }}</span></div>
        </div>
    </td>
    <td><code>{{ $role->role_code }}</code></td>
    <td>{{ Str::limit($role->description ?? 'No description provided.', 50) }}</td>
    <td><span class="badge {{ $role->status == 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} py-1 px-2 border rounded">{{ $role->status }}</span></td>
    <td class="text-center"><span class="badge bg-light text-dark border py-1 px-2 rounded fw-bold">{{ $role->users_count }}</span></td>
    <td class="text-end">
        <div class="dropdown">
            <button class="btn btn-sm btn-light border dropdown-actions-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Actions">
                <i class="ti ti-dots-vertical fs-5"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @if($role->role_code !== 'SUPER_ADMIN')
                    <li>
                        <a class="dropdown-item" href="{{ route('roles.permissions', $role->id) }}">
                            <i class="ti ti-lock-open me-2"></i> Set Permissions
                        </a>
                    </li>
                @else
                    <li>
                        <button class="dropdown-item disabled">
                            <i class="ti ti-lock me-2"></i> Permanent (All Permissions)
                        </button>
                    </li>
                @endif
                <li>
                    <a class="dropdown-item" href="{{ route('roles.duplicate', $role->id) }}">
                        <i class="ti ti-copy me-2"></i> Duplicate
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('roles.edit', $role->id) }}">
                        <i class="ti ti-edit me-2"></i> Edit
                    </a>
                </li>
                @if($role->role_code !== 'SUPER_ADMIN')
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="ti ti-trash me-2"></i> Delete
                            </button>
                        </form>
                    </li>
                @endif
            </ul>
        </div>
    </td>
</tr>
@empty
<tr><td colspan="6" class="text-center py-5"><i class="ti ti-shield-off text-muted" style="font-size: 3rem;"></i><p class="mt-2 mb-0 text-muted">No roles found.</p></td></tr>
@endforelse
