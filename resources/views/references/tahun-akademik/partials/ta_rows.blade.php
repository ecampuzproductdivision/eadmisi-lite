@foreach($tahunAkademiks as $ta)
<tr>
  <td class="text-center fw-semibold text-muted">{{ $loop->iteration + ($tahunAkademiks->currentPage() - 1) * $tahunAkademiks->perPage() }}</td>
  <td class="text-center">
    <div class="dropdown">
      <button class="btn-icon btn btn-ghost btn-sm rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
        <i class="ti ti-dots-vertical"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
        <li><a class="dropdown-item" href="{{ route('tahun-akademik.show', $ta->id_tahun_akademik) }}"><i class="ti ti-list-details me-2"></i>Detail</a></li>
        @if($ta->status === 'PERSIAPAN')
          <li><a class="dropdown-item" href="{{ route('tahun-akademik.edit', $ta->id_tahun_akademik) }}"><i class="ti ti-edit me-2"></i>Ubah</a></li>
          <li><a class="dropdown-item" href="{{ route('tahun-akademik.aktivasi-panel', $ta->id_tahun_akademik) }}"><i class="ti ti-toggle-left me-2"></i>Aktivasi</a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form action="{{ route('tahun-akademik.destroy', $ta->id_tahun_akademik) }}" method="POST" class="d-inline" onsubmit="return confirmSubmit(event, 'Apakah Anda yakin ingin menghapus Tahun Akademik ini?')">
              @csrf @method('DELETE')
              <button type="submit" class="dropdown-item text-danger"><i class="ti ti-trash me-2"></i>Hapus</button>
            </form>
          </li>
        @elseif($ta->status === 'AKTIF')
          <li><hr class="dropdown-divider"></li>
          <li>
            <form action="{{ route('tahun-akademik.penutupan', $ta->id_tahun_akademik) }}" method="POST" class="d-inline" onsubmit="return confirmSubmit(event, 'Tutup Tahun Akademik ini? Semua data akan dikunci.')">
              @csrf
              <button type="submit" class="dropdown-item text-warning"><i class="ti ti-lock me-2"></i>Tutup Semester</button>
            </form>
          </li>
        @endif
      </ul>
    </div>
  </td>
  <td>
    <div class="d-flex align-items-center gap-2">
      <span class="fw-semibold">{{ $ta->kode_ta }}</span>
      @if($ta->is_aktif)
        <span class="badge bg-warning text-dark" title="Semester Berjalan">★</span>
      @endif
    </div>
  </td>
  <td>{{ $ta->nama_ta }}</td>
  <td>
    @php
      $semesterLabels = ['GANJIL' => 'Ganjil', 'GENAP' => 'Genap', 'PENDEK' => 'Pendek'];
      $semesterColors = ['GANJIL' => 'primary', 'GENAP' => 'success', 'PENDEK' => 'info'];
    @endphp
    <span class="badge bg-{{ $semesterColors[$ta->jenis_semester] ?? 'secondary' }}">
      {{ $semesterLabels[$ta->jenis_semester] ?? $ta->jenis_semester }}
    </span>
  </td>
  <td>{{ $ta->tanggal_mulai->format('d/m/Y') }}</td>
  <td>{{ $ta->tanggal_selesai->format('d/m/Y') }}</td>
  <td>
    @php
      $statusLabels = ['PERSIAPAN' => 'Persiapan', 'AKTIF' => 'Aktif', 'SELESAI' => 'Selesai', 'DIARSIPKAN' => 'Diarsipkan'];
      $statusColors = ['PERSIAPAN' => 'primary', 'AKTIF' => 'success', 'SELESAI' => 'secondary', 'DIARSIPKAN' => 'dark'];
    @endphp
    <span class="badge bg-{{ $statusColors[$ta->status] ?? 'secondary' }} bg-opacity-10 text-{{ $statusColors[$ta->status] ?? 'secondary' }} border border-{{ $statusColors[$ta->status] ?? 'secondary' }} border-opacity-25 px-2 py-1 fw-semibold">
      {{ $statusLabels[$ta->status] ?? $ta->status }}
    </span>
  </td>
  <td class="text-center">{{ $ta->jumlah_minggu_efektif }}</td>
</tr>
@endforeach
