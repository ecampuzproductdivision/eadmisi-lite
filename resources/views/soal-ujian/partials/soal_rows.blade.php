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
  <td class="text-center">
    <div class="dropdown">
        <button class="btn btn-sm btn-light border dropdown-actions-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Actions">
            <i class="ti ti-dots-vertical fs-5"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a class="dropdown-item" href="javascript:void(0)" onclick="editSoal({{ $soal->id }})">
                    <i class="ti ti-edit me-2"></i> Edit
                </a>
            </li>
            <li>
                <form action="{{ route('paket-soal.toggle-question-status', [$paketSoal, $soal]) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="ti ti-{{ $soal->status_aktif ? 'eye-off' : 'eye' }} me-2"></i>
                        {{ $soal->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{ route('paket-soal.destroy-question', [$paketSoal, $soal]) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
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