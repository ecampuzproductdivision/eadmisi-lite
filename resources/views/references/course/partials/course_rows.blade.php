@foreach($courses as $index => $course)
<tr class="bg-white" style="box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
  <td class="px-4 text-muted fw-medium text-center">{{ $courses->firstItem() + $index }}</td>
  <td>
    <div class="dropdown">
      <button class="btn-icon btn btn-ghost btn-sm rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ti ti-dots-vertical"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
        <li><a class="dropdown-item" href="{{ route('course.show', $course->id) }}"><i class="ti ti-list-details me-2"></i>Detail</a></li>
        <li><a class="dropdown-item" href="{{ route('course.edit', $course->id) }}"><i class="ti ti-edit me-2"></i>Ubah</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <a class="dropdown-item toggle-status-btn" href="#" data-id="{{ $course->id }}">
            @if($course->mk_is_aktif == 1)
              <i class="ti ti-x me-2"></i>Non-Aktifkan
            @else
              <i class="ti ti-check me-2"></i>Aktifkan
            @endif
          </a>
        </li>
        <li>
          <form action="{{ route('course.destroy', $course->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="dropdown-item text-danger" onclick="return confirmAction(event, 'Apakah Anda yakin ingin menghapus data ini?')"><i class="ti ti-trash me-2"></i>Hapus</button>
          </form>
        </li>
      </ul>
    </div>
  </td>
  <td class="fw-bold">{{ $course->mk_kode }}</td>
  <td class="text-default">
    {{ $course->mk_nama }}
    @if($course->mk_singkatan)
      <span class="text-muted small">({{ $course->mk_singkatan }})</span>
    @endif
  </td>
  <td class="text-muted">{{ $course->prodiNamaResmi }} <br> <span class="badge bg-light text-dark border">{{ $course->jjarNama }}</span></td>
  <td class="text-muted">
    <strong class="text-default">{{ $course->mk_sks_total }}</strong> SKS 
    <span class="small text-muted">({{ $course->mk_sks_tatap_muka }}-{{ $course->mk_sks_praktikum }}-{{ $course->mk_sks_praktek_lapangan }})</span>
  </td>
  <td class="text-muted text-center">{{ $course->mk_semester }}</td>
  <td>
    <span class="badge bg-light text-dark border">{{ $course->mk_jenis }}</span>
  </td>
  <td>
    <span class="badge status-badge {{ $course->mk_is_aktif == 1 ? 'bg-success' : 'bg-danger' }}">
      {{ $course->mk_is_aktif == 1 ? 'Aktif' : 'Non-Aktif' }}
    </span>
  </td>
</tr>
@endforeach
