@forelse($permissions as $perm)
<tr>
    <td>
        <div class="d-flex align-items-center gap-3">
            <div class="avatar avatar-md bg-light text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;"><i class="ti ti-lock-open fs-4"></i></div>
            <div><span class="mb-0 fw-semibold text-dark">{{ $perm->permission_name }}</span></div>
        </div>
    </td>
    <td><code>auth()->user()->hasPermission('PAGE_CODE', '{{ $perm->permission_name }}')</code></td>
    <td class="text-end">
        <div class="d-inline-flex gap-2">
            <a href="{{ route('permissions.edit', $perm->id) }}" class="btn btn-sm btn-light border" title="Edit"><i class="ti ti-edit fs-5"></i></a>
            <form action="{{ route('permissions.destroy', $perm->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete"><i class="ti ti-trash fs-5"></i></button></form>
        </div>
    </td>
</tr>
@empty
<tr><td colspan="3" class="text-center py-5"><i class="ti ti-lock-off text-muted" style="font-size: 3rem;"></i><p class="mt-2 mb-0 text-muted">No action permissions defined.</p></td></tr>
@endforelse
