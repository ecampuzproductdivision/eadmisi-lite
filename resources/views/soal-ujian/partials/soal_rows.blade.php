@foreach($soals as $i => $soal)
<tr>
  <td>{{ $soals->firstItem() + $i }}</td>
  <td>
    <div class="text-wrap" style="max-width: 400px;">
      <span class="fw-semibold">{{ $soal->pertanyaan }}</span>
    </div>
  </td>
  <td><span class="badge bg-dark fw-bold">{{ $soal->kunci_jawaban }}</span></td>
  <td><span class="fw-semibold">{{ $soal->skor }}</span></td>
  <td>
    @if($soal->status_aktif)
      <span class="badge bg-success-subtle text-success px-3 py-2">Aktif</span>
    @else
      <span class="badge bg-danger-subtle text-danger px-3 py-2">Nonaktif</span>
    @endif
  </td>
  <td>
    <div class="d-flex gap-1">
      <button type="button" class="btn btn-sm btn-soft-warning" onclick="editSoal({{ $soal->id }})" title="Edit">
        <i class="ti ti-edit"></i>
      </button>
      <form action="{{ route('paket-soal.toggle-question-status', [$paketSoal, $soal]) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-soft-info" title="{{ $soal->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
          <i class="ti ti-{{ $soal->status_aktif ? 'eye-off' : 'eye' }}"></i>
        </button>
      </form>
      <form action="{{ route('paket-soal.destroy-question', [$paketSoal, $soal]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus soal ini?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-soft-danger" title="Hapus">
          <i class="ti ti-trash"></i>
        </button>
      </form>
    </div>
  </td>
</tr>
@endforeach