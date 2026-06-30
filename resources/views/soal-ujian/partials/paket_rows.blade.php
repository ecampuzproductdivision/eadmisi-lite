@foreach($pakets as $paket)
  <tr>
    <td class="text-center text-muted fw-semibold">{{ $loop->iteration + (request('page', 1) - 1) * 10 }}</td>
    <td>
      <a href="{{ route('paket-soal.kelola-soal', $paket->id) }}" class="text-reset text-decoration-none fw-semibold">
        {{ $paket->nama_paket }}
      </a>
      @if($paket->deskripsi)
        <br><span class="text-muted">{{ Str::limit($paket->deskripsi, 80) }}</span>
      @endif
    </td>
    <td class="text-center">{{ $paket->soal_ujians_count }}</td>
    <td class="text-center">{{ $paket->total_skor ?? $paket->soalUjians->sum('skor') }}</td>
    <td>
      @if($paket->status_aktif)
        <span class="badge bg-success-subtle text-success">Aktif</span>
      @else
        <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
      @endif
    </td>
    <td class="text-end">
      @include('components.actions-dropdown', ['items' => [
        ['url' => route('paket-soal.kelola-soal', $paket->id), 'icon' => 'ti ti-list-details', 'label' => 'Kelola Soal', 'title' => 'Kelola Soal'],
        ['divider' => true],
        ['modal' => '#modalEditPaket' . $paket->id, 'icon' => 'ti ti-edit', 'label' => 'Edit', 'title' => 'Edit Paket Soal'],
        ['url' => route('paket-soal.toggle-status', $paket->id), 'icon' => 'ti ti-' . ($paket->status_aktif ? 'player-pause' : 'player-play'), 'label' => $paket->status_aktif ? 'Nonaktifkan' : 'Aktifkan', 'method' => 'POST', 'confirm' => ($paket->status_aktif ? 'Nonaktifkan' : 'Aktifkan') . ' paket soal ini?'],
        ['divider' => true],
        ['url' => route('paket-soal.destroy', $paket->id), 'icon' => 'ti ti-trash', 'label' => 'Hapus', 'class' => 'text-danger', 'method' => 'DELETE', 'confirm' => 'Hapus paket soal beserta semua soalnya?'],
      ]])

      <!-- Modal Edit Paket -->
      <div class="modal fade" id="modalEditPaket{{ $paket->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
              <h5 class="modal-title fw-bold">Edit Paket Soal</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
              <form action="{{ route('paket-soal.update', $paket->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                  <label class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label>
                  <input type="text" name="nama_paket" class="form-control" value="{{ $paket->nama_paket }}" required maxlength="200">
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Deskripsi</label>
                  <textarea name="deskripsi" rows="3" class="form-control">{{ $paket->deskripsi }}</textarea>
                </div>
                <div class="mb-3">
                  <div class="form-check form-switch">
                    <input type="hidden" name="status_aktif" value="0">
                    <input type="checkbox" name="status_aktif" id="edit_status_aktif_{{ $paket->id }}" class="form-check-input" value="1" {{ $paket->status_aktif ? 'checked' : '' }}>
                    <label for="edit_status_aktif_{{ $paket->id }}" class="form-check-label fw-semibold">Aktif</label>
                  </div>
                  <small class="text-muted">Total skor soal dalam paket harus tepat 100 untuk dapat diaktifkan.</small>
                </div>
                <div class="d-flex gap-2 justify-content-end">
                  <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <i class="ti ti-device-floppy fs-4"></i> Perbarui
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </td>
  </tr>
@endforeach