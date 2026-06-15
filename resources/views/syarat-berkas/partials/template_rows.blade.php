@foreach($templates as $t)
<tr>
  <td class="text-center text-muted fw-semibold">{{ $loop->iteration + (request('page', 1)-1)*10 }}</td>
  <td>
    <a href="{{ route('syarat-berkas.kelola-dokumen', $t->id) }}" class="text-reset text-decoration-none fw-semibold">
      {{ $t->nama_template }}
    </a>
    @if($t->deskripsi)<br><small class="text-muted">{{ Str::limit($t->deskripsi,80) }}</small>@endif
  </td>
  <td class="text-center">{{ $t->syarat_dokumens_count }}</td>
  <td>
    @if($t->status_aktif) <span class="badge bg-success-subtle text-success">Aktif</span>
    @else <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span> @endif
  </td>
  <td>
    <div class="d-flex gap-1">
      <a href="{{ route('syarat-berkas.kelola-dokumen', $t->id) }}" class="btn btn-sm btn-soft-info d-inline-flex align-items-center gap-1" title="Kelola Dokumen">
        <i class="ti ti-list-details fs-5"></i> Kelola Dokumen
      </a>
      <button type="button" class="btn btn-sm btn-soft-secondary" data-bs-toggle="modal" data-bs-target="#modalEditTemplate{{ $t->id }}" title="Edit"><i class="ti ti-edit fs-5"></i></button>
      <form action="{{ route('syarat-berkas.toggle-status', $t->id) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-soft-warning" title="{{ $t->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}" onclick="return confirm('{{ $t->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }} template ini?')">
          <i class="ti ti-{{ $t->status_aktif ? 'player-pause' : 'player-play' }} fs-5"></i>
        </button>
      </form>
      <form action="{{ route('syarat-berkas.destroy', $t->id) }}" method="POST" class="d-inline">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-sm btn-soft-danger" title="Hapus" onclick="return confirm('Hapus template beserta semua dokumen di dalamnya?')"><i class="ti ti-trash fs-5"></i></button>
      </form>
    </div>

    <!-- Modal Edit Template -->
    <div class="modal fade" id="modalEditTemplate{{ $t->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header border-0 pb-0"><h5 class="modal-title fw-bold">Edit Template</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body p-4">
            <form action="{{ route('syarat-berkas.update', $t->id) }}" method="POST">
              @csrf @method('PUT')
              <div class="mb-3">
                <label class="form-label fw-semibold">Nama Template <span class="text-danger">*</span></label>
                <input type="text" name="nama_template" class="form-control" value="{{ $t->nama_template }}" required maxlength="200">
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="form-control">{{ $t->deskripsi }}</textarea>
              </div>
              <div class="mb-3">
                <div class="form-check form-switch">
                  <input type="hidden" name="status_aktif" value="0">
                  <input type="checkbox" name="status_aktif" id="st{{ $t->id }}" class="form-check-input" value="1" {{ $t->status_aktif ? 'checked' : '' }}>
                  <label for="st{{ $t->id }}" class="form-check-label fw-semibold">Aktif</label>
                </div>
              </div>
              <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy fs-4"></i> Perbarui</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </td>
</tr>
@endforeach