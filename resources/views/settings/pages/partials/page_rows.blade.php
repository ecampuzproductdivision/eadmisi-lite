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
