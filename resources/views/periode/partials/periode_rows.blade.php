@forelse($periodes as $index => $periode)
    <tr>
        <td class="py-3">{{ ($periodes->currentPage() - 1) * $periodes->perPage() + $index + 1 }}</td>
        <td class="py-3 fw-semibold">{{ $periode->tahun_akademik }}</td>
        <td class="py-3">
            @if($periode->semester === 'Ganjil')
                <span class="badge bg-primary-subtle text-primary px-3 py-2">Ganjil</span>
            @elseif($periode->semester === 'Genap')
                <span class="badge bg-info-subtle text-info px-3 py-2">Genap</span>
            @else
                <span class="badge bg-warning-subtle text-warning px-3 py-2">Pendek</span>
            @endif
        </td>
        <td class="py-3">
            <div class="d-flex align-items-center gap-2">
                <form action="{{ route('periode.toggle-active', $periode) }}" method="POST" class="m-0">
                    @csrf
                    <div class="form-check form-switch mb-0">
                        <input type="checkbox" class="form-check-input" role="switch"
                               {{ $periode->status_aktif ? 'checked' : '' }}
                               onchange="this.form.submit()">
                    </div>
                </form>
                @if($periode->status_aktif)
                    <span class="badge bg-success-subtle text-success px-3 py-2">Aktif</span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Nonaktif</span>
                @endif
            </div>
        </td>
        <td class="py-3 text-end">
            <div class="dropdown">
                <button class="btn btn-sm btn-light border dropdown-actions-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Actions">
                    <i class="ti ti-dots-vertical fs-5"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('periode.edit', $periode) }}">
                            <i class="ti ti-edit me-2"></i> Edit
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('periode.destroy', $periode) }}" method="POST" onsubmit="return confirm('Hapus periode {{ $periode->label }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="ti ti-trash me-2"></i> Hapus
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-5">
            @include('components.empty-state', [
                'icon' => 'ti-calendar-off',
                'title' => 'Belum Ada Periode Akademik',
                'subtitle' => 'Klik "Tambah Periode" untuk membuat periode akademik baru.',
                'action' => '<a href="' . route('periode.create') . '" class="btn btn-primary mt-3"><i class="ti ti-plus me-1"></i> Tambah Periode Pertama</a>',
            ])
        </td>
    </tr>
@endforelse
