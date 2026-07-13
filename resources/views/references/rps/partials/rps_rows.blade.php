@foreach($courses as $index => $kmk)
  @php
    $rowNumber = ($courses->currentPage() - 1) * $courses->perPage() + $index + 1;
    $hasRps = !empty($kmk->activeRps);
    $rps = $kmk->activeRps;
  @endphp
  <tr class="bg-white border-0 shadow-sm" style="border-radius: 12px; margin-bottom: 10px;">
    <!-- No -->
    <td class="px-4 py-3 fw-bold text-slate-500" style="border-top-left-radius: 12px; border-bottom-left-radius: 12px; border: none;">
      {{ $rowNumber }}
    </td>

    <!-- Mata Kuliah Info -->
    <td class="py-3" style="border: none;">
      <div class="d-flex flex-column">
        <span class="fw-bold text-dark fs-6">{{ $kmk->mataKuliah->mk_nama }}</span>
        <div class="d-flex align-items-center gap-2 mt-1">
          <span class="badge font-monospace py-1 px-2 border" style="font-size: 0.75rem; color: #334155 !important; background-color: #f1f5f9 !important; border-color: #cbd5e1 !important;">
            {{ $kmk->mataKuliah->mk_kode }}
          </span>
          @if($kmk->is_wajib)
            <span class="badge bg-light-primary text-primary py-0 px-2" style="font-size: 0.7rem; border: 1px solid rgba(13, 110, 253, 0.2);">Wajib</span>
          @else
            <span class="badge bg-light-secondary text-secondary py-0 px-2" style="font-size: 0.7rem; border: 1px solid rgba(108, 117, 125, 0.2);">Pilihan</span>
          @endif
        </div>
      </div>
    </td>

    <!-- Program Studi -->
    <td class="py-3" style="border: none;">
      <span class="text-slate-600 fs-7 fw-medium">{{ $kmk->kurikulum->programStudi->prodiNamaResmi }}</span>
    </td>

    <!-- SKS -->
    <td class="py-3 text-center" style="border: none;">
      <span class="fw-bold text-slate-800">{{ $kmk->mataKuliah->sks_total }} SKS</span>
    </td>

    <!-- Semester Anjuran -->
    <td class="py-3 text-center" style="border: none;">
      <span class="badge rounded-pill py-1 px-3 fs-7 border" style="color: #334155 !important; background-color: #f1f5f9 !important; border-color: #cbd5e1 !important;">
        Sem. {{ $kmk->semester_anjuran }}
      </span>
    </td>

    <!-- Status RPS -->
    <td class="py-3 text-center" style="border: none;">
      @if(!$hasRps)
        <span class="badge bg-light-danger text-danger py-1 px-3" style="border-radius: 20px; font-size: 0.8rem; border: 1px solid rgba(220, 53, 69, 0.2);">
          <i class="ti ti-ban me-1"></i> Belum Dibuat
        </span>
      @else
        @switch($rps->status)
          @case('DRAFT')
            <span class="badge py-1 px-3" style="border-radius: 20px; font-size: 0.8rem; color: #475569 !important; background-color: #f1f5f9 !important; border: 1px solid #cbd5e1 !important;">
              <i class="ti ti-edit-circle me-1"></i> Draft
            </span>
            @break
          @case('MENUNGGU_REVIEW')
            <span class="badge bg-light-warning text-warning py-1 px-3" style="border-radius: 20px; font-size: 0.8rem; border: 1px solid rgba(255, 193, 7, 0.2);">
              <i class="ti ti-clock me-1"></i> Menunggu Review
            </span>
            @break
          @case('DISETUJUI')
            <span class="badge bg-light-info text-info py-1 px-3" style="border-radius: 20px; font-size: 0.8rem; border: 1px solid rgba(13, 202, 240, 0.2);">
              <i class="ti ti-discount-check me-1"></i> Disetujui
            </span>
            @break
          @case('DIPUBLIKASIKAN')
            <span class="badge bg-light-success text-success py-1 px-3" style="border-radius: 20px; font-size: 0.8rem; border: 1px solid rgba(25, 135, 84, 0.2);">
              <i class="ti ti-eye me-1"></i> Dipublikasikan
            </span>
            @break
          @case('SELESAI')
            <span class="badge bg-light-dark text-slate-800 py-1 px-3" style="border-radius: 20px; font-size: 0.8rem; border: 1px solid rgba(33, 37, 41, 0.2);">
              <i class="ti ti-archive me-1"></i> Diarsipkan
            </span>
            @break
        @endswitch
      @endif
    </td>

    <!-- Kelengkapan Progress -->
    <td class="py-3" style="border: none;">
      @if($hasRps)
        <div class="d-flex flex-column align-items-center">
          <div class="d-flex justify-content-between w-100 mb-1" style="max-width: 140px;">
            <span class="text-muted" style="font-size: 0.75rem;">Progress</span>
            <span class="fw-bold text-dark" style="font-size: 0.75rem;">{{ $rps->progress_percentage }}%</span>
          </div>
          <div class="progress w-100" style="height: 6px; max-width: 140px; border-radius: 3px;">
            @php
              $progressColor = 'bg-danger';
              if ($rps->progress_percentage >= 100) {
                  $progressColor = 'bg-success';
              } elseif ($rps->progress_percentage >= 50) {
                  $progressColor = 'bg-warning';
              }
            @endphp
            <div class="progress-bar {{ $progressColor }}" role="progressbar" style="width: {{ $rps->progress_percentage }}%;" aria-valuenow="{{ $rps->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      @else
        <div class="text-center text-muted" style="font-size: 0.8rem;">
          -
        </div>
      @endif
    </td>

    <!-- Aksi -->
    <td class="px-4 py-3 text-end" style="border-top-right-radius: 12px; border-bottom-right-radius: 12px; border: none;">
      <div class="d-flex justify-content-end gap-1">
        @if(!$hasRps)
          <a href="{{ route('rps.create', $kmk->id) }}" class="btn btn-primary btn-sm px-3 d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
            <i class="ti ti-circle-plus"></i> Buat RPS
          </a>
        @else
          <a href="{{ route('rps.edit', $rps->id_rps) }}" class="btn btn-light border btn-sm px-3 d-inline-flex align-items-center gap-1" style="border-radius: 8px;" title="Lihat/Edit RPS">
            <i class="ti ti-edit"></i> {{ $rps->status === 'DRAFT' ? 'Susun' : 'Lihat' }}
          </a>

          @if($rps->status === 'DIPUBLIKASIKAN' || $rps->status === 'DISETUJUI')
            <a href="{{ route('rps.print', $rps->id_rps) }}" target="_blank" class="btn btn-subtle-primary btn-sm px-2" style="border-radius: 8px;" title="Cetak RPS">
              <i class="ti ti-printer fs-4"></i>
            </a>
          @endif

          @if($rps->status === 'DRAFT')
            <form action="{{ route('rps.destroy', $rps->id_rps) }}" method="POST" class="d-inline" onsubmit="return confirmSubmit(event, 'Apakah Anda yakin ingin menghapus RPS ini? Seluruh rancangan pertemuan dan tim pengampu akan ikut terhapus.')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-subtle-danger btn-sm px-2" style="border-radius: 8px;" title="Hapus Draft">
                <i class="ti ti-trash fs-4"></i>
              </button>
            </form>
          @endif
        @endif
      </div>
    </td>
  </tr>
@endforeach
