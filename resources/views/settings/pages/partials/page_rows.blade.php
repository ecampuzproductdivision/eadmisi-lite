@forelse($pages as $page)
    <tr>
        <td>
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-md bg-light text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                    <i class="ti ti-file fs-4"></i>
                </div>
                <div>
                    <span class="mb-0 fw-semibold">{{ $page->page_name }}</span>
                </div>
            </div>
        </td>
        <td><code>{{ $page->page_code }}</code></td>
        <td>
            @if($page->menu)
                <span class="badge bg-light border text-dark py-1 px-2 rounded">{{ $page->menu->menu_name }}</span>
            @else
                <span class="text-muted">&mdash;</span>
            @endif
        </td>
        <td><code>{{ $page->route_path ?: '&mdash;' }}</code></td>
        <td><code>{{ $page->component_name ?: '&mdash;' }}</code></td>
        <td>
            <span class="badge {{ $page->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border rounded py-1 px-2">
                {{ $page->is_active ? 'Active' : 'Disabled' }}
            </span>
        </td>
        <td class="text-end">
            <div class="dropdown">
                <button class="btn btn-sm btn-light border dropdown-actions-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Actions">
                    <i class="ti ti-dots-vertical fs-5"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('pages.edit', $page->id) }}">
                            <i class="ti ti-edit me-2"></i> Edit
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('pages.destroy', $page->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page registration?');">
                            @csrf
                            @method('DELETE')
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
    <tr>
        <td colspan="7" class="text-center py-5">
            <i class="ti ti-file-off text-muted" style="font-size: 3rem;"></i>
            <p class="mt-2 mb-0 text-muted">No registered pages found.</p>
        </td>
    </tr>
@endforelse
