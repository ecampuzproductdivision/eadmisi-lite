@foreach($dosenList as $dosen)
  <tr class="align-middle">
    <td class="text-center text-muted">
      {{ ($dosenList->currentPage() - 1) * $dosenList->perPage() + $loop->iteration }}
    </td>
    <td class="text-center">
      <div class="dropdown">
        <button class="btn-icon btn btn-ghost btn-sm rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="ti ti-dots-vertical"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
          <li><a class="dropdown-item" href="{{ route('dosen.show', $dosen->id_dosen) }}"><i class="ti ti-list-details me-2"></i>Detail</a></li>
          <li><a class="dropdown-item" href="{{ route('dosen.edit', $dosen->id_dosen) }}"><i class="ti ti-edit me-2"></i>Ubah</a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form action="{{ route('dosen.destroy', $dosen->id_dosen) }}" method="POST" class="d-inline" onsubmit="return confirmSubmit(event, 'Apakah Anda yakin ingin menghapus dosen ini secara permanen? Pastikan tidak ada data terkait.')">
              @csrf
              @method('DELETE')
              <button type="submit" class="dropdown-item text-danger"><i class="ti ti-trash me-2"></i>Hapus Permanen</button>
            </form>
          </li>
        </ul>
      </div>
    </td>
    <td>
      <div class="d-flex align-items-center gap-3">
        @if($dosen->foto)
          <img src="{{ asset('storage/' . $dosen->foto) }}" alt="Foto Dosen" class="dosen-photo shadow-sm">
        @else
          <div class="avatar-text">{{ substr($dosen->nama_lengkap, 0, 1) }}</div>
        @endif
        <div>
          <a href="{{ route('dosen.show', $dosen->id_dosen) }}" class="text-body fw-semibold text-decoration-none d-block mb-1">
            {{ $dosen->gelar_depan ? $dosen->gelar_depan . ' ' : '' }}{{ $dosen->nama_lengkap }}{{ $dosen->gelar_belakang ? ', ' . $dosen->gelar_belakang : '' }}
          </a>
          <span class="text-muted small">NIDN: {{ $dosen->nidn ?? '-' }} | NIK: {{ $dosen->nik }}</span>
        </div>
      </div>
    </td>
    <td>
      <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">{{ $dosen->programStudi->prodiNamaResmi ?? '-' }}</span>
      <div class="small text-muted mt-1">{{ $dosen->jenis_dosen }}</div>
    </td>
    <td>
      <div class="fw-semibold">{{ $dosen->jabatan_fungsional ?? 'Belum Ada Jabatan' }}</div>
      <div class="small text-muted mt-1">{{ $dosen->jenjang_pendidikan_terakhir }} {{ $dosen->bidang_studi_terakhir }}</div>
    </td>
    <td>
      @if($dosen->is_sertifikasi_dosen)
        <span class="badge bg-success-subtle text-success"><i class="ti ti-check me-1"></i>Tersertifikasi</span>
        <div class="small text-muted mt-1">{{ $dosen->tahun_sertifikasi ?? '' }}</div>
      @else
        <span class="badge bg-warning-subtle text-warning"><i class="ti ti-circle-x me-1"></i>Belum Serdos</span>
      @endif
    </td>
    <td class="text-center">
      <div class="form-check form-switch d-flex justify-content-center align-items-center mb-0">
        <input class="form-check-input status-toggle" type="checkbox" role="switch" 
               {{ $dosen->status_dosen === 'Aktif' ? 'checked' : '' }}
               data-url="{{ route('dosen.toggle-status', $dosen->id_dosen) }}">
        <label class="form-check-label status-label {{ $dosen->status_dosen === 'Aktif' ? 'text-success' : 'text-danger' }} fw-semibold small ms-1" style="min-width: 50px;">
          {{ $dosen->status_dosen }}
        </label>
      </div>
    </td>
  </tr>
@endforeach
