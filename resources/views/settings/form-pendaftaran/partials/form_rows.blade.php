@forelse($forms as $i => $form)
<tr>
    <td class="text-muted">{{ $loop->iteration }}</td>
    <td>
        <div class="d-flex align-items-center gap-2">
            <span class="d-inline-flex rounded-circle bg-primary bg-opacity-10 align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="ti ti-file-text text-primary"></i>
            </span>
            <div>
                <span class="fw-semibold">{{ $form->nama }}</span>
            </div>
        </div>
    </td>
    <td>
        <span class="text-muted">{{ Str::limit($form->deskripsi, 60) ?: '-' }}</span>
    </td>
    <td class="text-center">
        <span class="badge bg-info-subtle text-info px-3 py-2">{{ $form->fields_count }} field</span>
    </td>
    <td class="text-center">
        <form action="{{ route('settings.form-pendaftaran.toggle-status', $form->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm {{ $form->is_active ? 'btn-success' : 'btn-secondary' }} border-0">
                <i class="ti ti-{{ $form->is_active ? 'check' : 'x' }} me-1"></i>
                {{ $form->is_active ? 'Aktif' : 'Nonaktif' }}
            </button>
        </form>
    </td>
    <td class="text-center">
        <span class="text-muted">{{ $form->created_at->format('d/m/Y') }}</span>
    </td>
    <td class="text-center">
        <div class="dropdown">
            <button class="btn btn-sm btn-light border dropdown-actions-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Actions">
                <i class="ti ti-dots-vertical fs-5"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('settings.form-pendaftaran.builder', $form->id) }}">
                        <i class="ti ti-layout-board me-2"></i> Atur Field
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('settings.form-pendaftaran.destroy', $form->id) }}" method="POST" onsubmit="return confirm('Hapus form {{ $form->nama }}? Semua field akan ikut terhapus.')">
                        @csrf @method('DELETE')
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
    <td colspan="7" class="text-center py-5">
        <i class="ti ti-file-off text-muted" style="font-size: 3rem;"></i>
        <p class="mt-2 mb-0 text-muted">Belum Ada Form</p>
        <span class="text-muted">Klik "Tambah Form" untuk membuat formulir pendaftaran baru.</span>
    </td>
</tr>
@endforelse
