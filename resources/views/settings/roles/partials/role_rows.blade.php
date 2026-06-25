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
        <div class="d-inline-flex gap-2">
            @if($role->role_code !== 'SUPER_ADMIN')
                <a href="{{ route('roles.permissions', $role->id) }}" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1" title="Assign Permissions"><i class="ti ti-lock-open fs-5"></i> Set Permissions</a>
            @else
                <button class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1 disabled" title="All Permissions Permanently Granted"><i class="ti ti-lock fs-5"></i> Permanent</button>
            @endif
            <a href="{{ route('roles.duplicate', $role->id) }}" class="btn btn-sm btn-light border" title="Duplicate"><i class="ti ti-copy fs-5"></i></a>
            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-light border" title="Edit"><i class="ti ti-edit fs-5"></i></a>
            @if($role->role_code !== 'SUPER_ADMIN')
                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete"><i class="ti ti-trash fs-5"></i></button></form>
            @endif
        </div>
    </td>
</tr>
@empty
<tr><td colspan="6" class="text-center py-5"><i class="ti ti-shield-off text-muted" style="font-size: 3rem;"></i><p class="mt-2 mb-0 text-muted">No roles found.</p></td></tr>
@endforelse