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
  <td class="text-end">
    @include('components.actions-dropdown', ['items' => [
      [
        'modal' => '#modalEditDokumen',
        'icon' => 'ti ti-edit',
        'label' => 'Edit',
        'title' => 'Edit Dokumen',
        'class' => 'edit-dokumen-btn',
        'data' => [
          'id' => $d->id,
          'nama' => $d->nama_dokumen,
          'ekstensi' => $d->ekstensi_diizinkan,
          'maxsize' => $d->max_size,
          'wajib' => $d->status_wajib ? 'true' : 'false',
          'urutan' => $d->urutan
        ]
      ],
      ['divider' => true],
      [
        'url' => route('syarat-berkas.destroy-dokumen', [$d->template_berkas_id, $d->id]),
        'icon' => 'ti ti-trash',
        'label' => 'Hapus',
        'class' => 'text-danger',
        'method' => 'DELETE',
        'confirm' => 'Apakah Anda yakin ingin menghapus item dokumen ini?',
        'confirm_text' => 'Ya, Hapus!',
        'confirm_button_class' => 'btn-danger'
      ]
    ]])
  </td>
</tr>
@endforeach