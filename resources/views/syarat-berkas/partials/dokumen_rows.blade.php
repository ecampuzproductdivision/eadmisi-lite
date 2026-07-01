@foreach($dokumens as $d)
<tr>
  <td>{{ $dokumens->firstItem() + $loop->index }}</td>
  <td><span class="fw-semibold">{{ $d->nama_dokumen }}</span></td>
  <td>
    @foreach(explode(',', $d->ekstensi_diizinkan) as $ext)
      <span class="badge bg-dark me-1">{{ trim($ext) }}</span>
    @endforeach
  </td>
  <td>
    @php $mb = $d->max_size / 1024; @endphp
    @if($mb >= 1) {{ number_format($mb,1) }} MB @else {{ $d->max_size }} KB @endif
  </td>
  <td>
    @if($d->status_wajib)
      <span class="badge bg-danger-subtle text-danger px-3 py-2">Wajib</span>
    @else
      <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Opsional</span>
    @endif
  </td>
  <td class="text-center">
    <div class="dropdown">
        <button class="btn btn-sm btn-light border dropdown-actions-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Actions">
            <i class="ti ti-dots-vertical fs-5"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a href="#" class="dropdown-item edit-dokumen-btn"
                   data-id="{{ $d->id }}"
                   data-nama="{{ $d->nama_dokumen }}"
                   data-ekstensi="{{ $d->ekstensi_diizinkan }}"
                   data-maxsize="{{ $d->max_size }}"
                   data-wajib="{{ $d->status_wajib ? 'true' : 'false' }}"
                   data-urutan="{{ $d->urutan }}">
                    <i class="ti ti-edit me-2"></i> Edit
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{ route('syarat-berkas.destroy-dokumen', [request()->route('templateBerkas'), $d->id]) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="ti ti-trash me-2"></i> Hapus
                    </button>
                </form>
            </li>
        </ul>
    </div>
  </td>
</tr>
@endforeach