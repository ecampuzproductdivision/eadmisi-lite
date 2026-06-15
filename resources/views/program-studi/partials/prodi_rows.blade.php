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
    <td>
      <div class="d-flex gap-2">
        <a href="{{ route('program-studi.edit', $prodi) }}" class="btn btn-soft-info btn-sm d-inline-flex align-items-center gap-1" title="Edit">
          <i class="ti ti-edit fs-5"></i>
        </a>
        <form action="{{ route('program-studi.destroy', $prodi) }}" method="POST" class="d-inline delete-form">
          @csrf
          @method('DELETE')
          <button type="button" class="btn btn-soft-danger btn-sm d-inline-flex align-items-center gap-1 btn-delete" title="Hapus" data-name="{{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}">
            <i class="ti ti-trash fs-5"></i>
          </button>
        </form>
      </div>
    </td>
  </tr>
@endforeach

@push('scripts')
<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    const name = this.getAttribute('data-name');
    const form = this.closest('form');

    Swal.fire({
      title: 'Konfirmasi Hapus',
      text: `Apakah Anda yakin ingin menghapus program studi "${name}"?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });
});
</script>
@endpush