@foreach($kurikulums as $index => $kur)
<tr class="bg-white" style="box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
  <td class="px-4 text-muted fw-medium text-center">{{ $kurikulums->firstItem() + $index }}</td>
  <td class="text-center">
    <div class="dropdown">
      <button class="btn-icon btn btn-ghost btn-sm rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ti ti-dots-vertical"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
        <li><a class="dropdown-item" href="{{ route('curiculum.show', $kur->kurKode) }}"><i class="ti ti-list-details me-2"></i>Detail</a></li>
        <li><a class="dropdown-item" href="{{ route('curiculum.edit', $kur->kurKode) }}"><i class="ti ti-edit me-2"></i>Ubah</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <a class="dropdown-item toggle-status-btn" href="#" data-id="{{ $kur->kurKode }}">
            @if($kur->kurIsAktif)
              <i class="ti ti-x me-2"></i>Non-Aktifkan
            @else
              <i class="ti ti-check me-2"></i>Aktifkan
            @endif
          </a>
        </li>
        <li>
          <form action="{{ route('curiculum.destroy', $kur->kurKode) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="dropdown-item text-danger" onclick="return confirmAction(event, 'Apakah Anda yakin ingin menghapus data ini?')"><i class="ti ti-trash me-2"></i>Hapus</button>
          </form>
        </li>
      </ul>
    </div>
  </td>
  <td class="fw-bold text-default">{{ $kur->kurKode }}</td>
  <td class="text-default">{{ $kur->kurNama }}</td>
  <td class="text-muted">{{ $kur->prodiNamaResmi }} <br> <span class="badge bg-light text-dark border">{{ $kur->jjarNama }}</span></td>
  <td class="text-muted">{{ $kur->kurTahunMulai }}</td>
  <td class="text-muted"><span class="badge bg-secondary">{{ $kur->kurSksLulus ?: '-' }}</span> SKS</td>
  <td>
    <span class="badge status-badge {{ $kur->kurIsAktif ? 'bg-success' : 'bg-danger' }}">
      {{ $kur->kurIsAktif ? 'Aktif' : 'Non-Aktif' }}
    </span>
  </td>
</tr>
@endforeach
