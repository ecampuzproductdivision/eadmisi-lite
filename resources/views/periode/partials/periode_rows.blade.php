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
            <div class="d-inline-flex gap-2">
                <a href="{{ route('periode.edit', $periode) }}" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1">
                    <i class="ti ti-edit fs-5"></i>
                </a>
                <form action="{{ route('periode.destroy', $periode) }}" method="POST" onsubmit="return confirm('Hapus periode {{ $periode->label }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-light border text-danger d-inline-flex align-items-center gap-1">
                        <i class="ti ti-trash fs-5"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-5">
            <i class="ti ti-calendar-off text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 mb-0 text-muted">Belum ada periode akademik.</p>
            <a href="{{ route('periode.create') }}" class="btn btn-primary mt-3">
                Tambah Periode Pertama
            </a>
        </td>
    </tr>
@endforelse
