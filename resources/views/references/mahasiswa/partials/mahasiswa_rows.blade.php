@foreach($mahasiswas as $mhs)
<tr>
  <td class="text-center text-muted">{{ $loop->iteration + ($mahasiswas->currentPage() - 1) * $mahasiswas->perPage() }}</td>
  <td class="text-center">
    <div class="dropdown">
      <button class="btn-icon btn btn-ghost btn-sm rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ti ti-dots-vertical"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
        <li><a class="dropdown-item" href="{{ route('mahasiswa.show', $mhs->id_mahasiswa) }}"><i class="ti ti-list-details me-2"></i>Detail</a></li>
        <li><a class="dropdown-item" href="{{ route('mahasiswa.edit', $mhs->id_mahasiswa) }}"><i class="ti ti-edit me-2"></i>Ubah</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <form action="{{ route('mahasiswa.destroy', $mhs->id_mahasiswa) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menonaktifkan mahasiswa ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="dropdown-item text-danger"><i class="ti ti-user-off me-2"></i>Nonaktifkan</button>
          </form>
        </li>
      </ul>
    </div>
  </td>
  <td>
    <div class="d-flex align-items-center gap-3">
      <div class="avatar-text rounded-circle {{ $mhs->jenis_kelamin === 'Laki-laki' ? 'bg-info-subtle text-info' : 'bg-danger-subtle text-danger' }}">
        {{ substr($mhs->nama_lengkap, 0, 1) }}
      </div>
      <div>
        <a href="{{ route('mahasiswa.show', $mhs->id_mahasiswa) }}" class="fw-semibold text-body text-decoration-none">{{ $mhs->nama_lengkap }}</a>
        <div class="small text-muted">{{ $mhs->nim }} &middot; {{ $mhs->email_institusi }}</div>
      </div>
    </div>
  </td>
  <td>
    <span class="fw-medium">{{ $mhs->prodi->prodiNamaResmi ?? '-' }}</span>
    <div class="small text-muted">{{ $mhs->kurikulum->kurNama ?? '-' }}</div>
  </td>
  <td>
    <span class="fw-medium">{{ $mhs->tahun_masuk }}</span>
    <div class="small text-muted">Sem {{ $mhs->semester_berjalan }} &middot; {{ $mhs->jalur_masuk }}</div>
  </td>
  <td>
    <span class="fw-bold {{ $mhs->ipk >= 3.5 ? 'text-success' : ($mhs->ipk >= 3.0 ? 'text-primary' : ($mhs->ipk >= 2.0 ? 'text-warning' : 'text-danger')) }}">
      {{ number_format($mhs->ipk, 2) }}
    </span>
    <div class="small text-muted">{{ $mhs->total_sks_lulus }} SKS</div>
  </td>
  <td class="text-center">
    @php
      $statusColors = [
        'Aktif' => 'bg-success-subtle text-success',
        'Cuti' => 'bg-warning-subtle text-warning',
        'Tugas Belajar' => 'bg-info-subtle text-info',
        'Non-aktif' => 'bg-secondary-subtle text-secondary',
        'DO' => 'bg-danger-subtle text-danger',
        'Lulus' => 'bg-primary-subtle text-primary',
        'Mengundurkan Diri' => 'bg-dark-subtle text-dark',
      ];
      $color = $statusColors[$mhs->status_mahasiswa] ?? 'bg-secondary-subtle text-secondary';
    @endphp
    <span class="badge {{ $color }}">{{ $mhs->status_mahasiswa }}</span>
  </td>
</tr>
@endforeach
