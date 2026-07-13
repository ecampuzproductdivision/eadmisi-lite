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
        <div class="dropdown">
            <button class="btn btn-sm btn-light border dropdown-actions-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Actions">
                <i class="ti ti-dots-vertical fs-5"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('permissions.edit', $perm->id) }}">
                        <i class="ti ti-edit me-2"></i> Edit
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('permissions.destroy', $perm->id) }}" method="POST" onsubmit="return confirmSubmit(event, 'Are you sure?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="ti ti-trash me-2"></i> Delete
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </td>
</tr>
@empty
<tr><td colspan="3" class="text-center py-5">
    @include('components.empty-state', [
        'icon' => 'ti-lock-off',
        'title' => 'No Action Permissions Defined',
        'subtitle' => 'Permissions are auto-registered when you assign them to role-menu mappings.',
    ])
</td></tr>
@endforelse
