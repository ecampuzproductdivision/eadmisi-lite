@foreach($programStudis as $index => $prodi)
  <tr>
    <td>{{ $programStudis->firstItem() + $index }}</td>
    <td><span class="fw-semibold">{{ $prodi->kode_prodi }}</span></td>
    <td>{{ $prodi->nama_prodi }}</td>
    <td>{{ $prodi->jurusan }}</td>
    <td>
      @php
        $badgeClass = match($prodi->jenjang_akademik) {
          'S1' => 'bg-primary-subtle text-primary',
          'D3' => 'bg-info-subtle text-info',
          'D4' => 'bg-success-subtle text-success',
          'S2' => 'bg-warning-subtle text-warning',
          'S3' => 'bg-danger-subtle text-danger',
          default => 'bg-secondary-subtle text-secondary',
        };
      @endphp
      <span class="badge {{ $badgeClass }}">{{ $prodi->jenjang_akademik }}</span>
    </td>
    <td>{{ $prodi->program }}</td>
    <td>
      @if($prodi->kelompok === 'Eksakta')
        <span class="badge bg-primary-subtle text-primary">Eksakta</span>
      @else
        <span class="badge bg-warning-subtle text-warning">Non Eksakta</span>
      @endif
    </td>
    <td>
      @if($prodi->status_aktif)
        <span class="badge bg-success-subtle text-success"><i class="ti ti-circle-check-filled me-1"></i> Aktif</span>
      @else
        <span class="badge bg-danger-subtle text-danger"><i class="ti ti-circle-x-filled me-1"></i> Non Aktif</span>
      @endif
    </td>
    <td class="text-end">
      @include('components.actions-dropdown', ['items' => [
        ['url' => route('program-studi.edit', $prodi), 'icon' => 'ti ti-edit', 'label' => 'Edit', 'title' => 'Edit Program Studi'],
        ['divider' => true],
        ['url' => route('program-studi.destroy', $prodi), 'icon' => 'ti ti-trash', 'label' => 'Hapus', 'class' => 'text-danger', 'method' => 'DELETE', 'confirm' => 'Apakah Anda yakin ingin menghapus program studi "' . $prodi->kode_prodi . ' - ' . $prodi->nama_prodi . '"?'],
      ]])
    </td>
  </tr>
@endforeach