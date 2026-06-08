@foreach($programStudis as $prodi)
  <tr class="align-middle">
    <td class="text-center text-muted">
      {{ ($programStudis->currentPage() - 1) * $programStudis->perPage() + $loop->iteration }}
    </td>
    <td class="text-center">
      <div class="dropdown">
        <button class="btn-icon btn btn-ghost btn-sm rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="ti ti-dots-vertical"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
          <li><a class="dropdown-item" href="{{ route('prodi.show', $prodi->ID) }}"><i class="ti ti-list-details me-2"></i>Detail</a></li>
          <li><a class="dropdown-item" href="{{ route('prodi.edit', $prodi->ID) }}"><i class="ti ti-edit me-2"></i>Ubah</a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form action="{{ route('prodi.destroy', $prodi->ID) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="dropdown-item text-danger"><i class="ti ti-trash me-2"></i>Hapus</button>
            </form>
          </li>
        </ul>
      </div>
    </td>
    <td>
      <span class="text-default">{{ $prodi->KDPTIMSPST ?? '-' }}</span>
    </td>
    <td>
      <span class="text-default">{{ $prodi->KDPSTMSPST ?? '-' }}</span>
    </td>
    <td>
      <span class="text-default text-uppercase">{{ $prodi->NMPSTMSPST ?? '-' }}</span>
    </td>
    <td class="text-center">
      <span class="text-default">{{ $prodi->MERDEKA === 'Ya' ? 'Ya' : 'Tidak' }}</span>
    </td>
    <td>
      <span class="text-default">{{ $prodi->KDJENMSPST ?? '-' }}</span>
    </td>
  </tr>
@endforeach
