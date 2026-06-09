@foreach($paths as $index => $path)
  <tr>
    <td>{{ ($paths->currentPage() - 1) * $paths->perPage() + $index + 1 }}</td>
    <td class="fw-semibold">{{ $path->name }}</td>
    <td>
      @if($path->kategori)
        <span class="badge bg-{{ $path->color ?? 'secondary' }}-subtle text-{{ $path->color ?? 'secondary' }}">{{ $path->kategori->nama }}</span>
      @else
        <span class="text-muted">—</span>
      @endif
    </td>
    <td>Rp {{ number_format($path->fee, 0, ',', '.') }}</td>
    <td>
      @if($path->registration_start && $path->registration_end)
        <small>{{ $path->registration_start->format('d/m/Y') }} - {{ $path->registration_end->format('d/m/Y') }}</small>
      @else
        <span class="text-muted">—</span>
      @endif
    </td>
    <td>
      @if($path->quota)
        <span class="fw-semibold">{{ $path->quota }}</span>
      @else
        <span class="text-muted">∞</span>
      @endif
    </td>
    <td>
      @if($path->is_active)
        <span class="badge bg-success-subtle text-success px-3 py-2">Aktif</span>
      @else
        <span class="badge bg-danger-subtle text-danger px-3 py-2">Nonaktif</span>
      @endif
    </td>
    <td>
      <div class="d-flex gap-2">
        <a href="{{ route('registration-paths.show', $path) }}" class="btn btn-sm py-2 btn-white d-inline-flex align-items-center gap-1">
          <i class="ti ti-list-details"></i>
        </a>
        <a href="{{ route('registration-paths.edit', $path) }}" class="btn btn-sm py-2 btn-white d-inline-flex align-items-center gap-1">
          <i class="ti ti-pencil"></i>
        </a>
        <form action="{{ route('registration-paths.destroy', $path) }}" method="POST" onsubmit="return confirm('Hapus jalur {{ $path->name }}?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-sm py-2 btn-white d-inline-flex align-items-center gap-1">
            <i class="ti ti-trash"></i>
          </button>
        </form>
      </div>
    </td>
  </tr>
@endforeach
