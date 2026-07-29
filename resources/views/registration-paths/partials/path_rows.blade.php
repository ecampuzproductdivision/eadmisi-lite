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
    <td>
      @if($path->jenis_pendaftaran)
        <span class="badge bg-info-subtle text-info px-2 py-1">{{ $path->jenis_pendaftaran }}</span>
      @else
        <span class="text-muted">—</span>
      @endif
    </td>
    <td>
      @if($path->formPendaftaran)
        <span class="badge bg-info-subtle text-info px-2 py-1">{{ $path->formPendaftaran->nama }}</span>
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
    <td class="text-end">
      @include('components.actions-dropdown', ['items' => [
        ['url' => route('registration-paths.show', $path), 'icon' => 'ti ti-list-details', 'label' => 'Detail', 'title' => 'Lihat Detail'],
        ['url' => route('registration-paths.edit', $path), 'icon' => 'ti ti-pencil', 'label' => 'Edit', 'title' => 'Edit Jalur Pendaftaran'],
        ['divider' => true],
        ['url' => route('registration-paths.destroy', $path), 'icon' => 'ti ti-trash', 'label' => 'Hapus', 'class' => 'text-danger', 'method' => 'DELETE', 'confirm' => 'Hapus jalur ' . $path->name . '?'],
      ]])
    </td>
  </tr>
@endforeach