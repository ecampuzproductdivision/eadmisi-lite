@forelse($registrations as $index => $reg)
    @php $w = $reg->wawancara; @endphp
    <tr>
        <td class="py-3">{{ ($registrations->currentPage() - 1) * $registrations->perPage() + $index + 1 }}</td>
        <td class="fw-semibold py-3">REG-{{ str_pad($reg->id, 5, '0', STR_PAD_LEFT) }}</td>
        <td class="py-3">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ optional($reg->user)->avatar_url ?? asset('assets/images/avatar/avatar-1.jpg') }}" class="rounded-circle" width="32" height="32" alt="">
                <div>
                    <span class="d-block fw-semibold text-truncate" style="max-width: 180px;">{{ $reg->nama_lengkap }}</span>
                    @if($reg->user)
                        <span class="text-muted">{{ $reg->user->email }}</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </div>
            </div>
        </td>
        <td class="py-3">
            <span class="badge bg-{{ $reg->registrationPath->color ?? 'secondary' }}-subtle text-{{ $reg->registrationPath->color ?? 'secondary' }} px-3 py-2 text-truncate" style="max-width: 150px;">
                {{ $reg->registrationPath->name }}
            </span>
        </td>
        <td class="py-3">
            @if($w && $w->tanggal_wawancara)
                <div class="d-flex align-items-center gap-1">
                    <i class="ti ti-calendar-event text-muted" style="font-size: 0.85rem;"></i>
                    <span>{{ $w->tanggal_wawancara->format('d/m/Y') }}</span>
                    @if($w->jam_wawancara)
                        <span class="text-muted mx-1">|</span>
                        <i class="ti ti-clock text-muted" style="font-size: 0.85rem;"></i>
                        <span>{{ $w->jam_wawancara }}</span>
                    @endif
                </div>
                @if($w->lokasi_wawancara)
                    <div class="mt-1">
                        <span class="text-muted">{{ $w->lokasi_wawancara }}</span>
                    </div>
                @endif
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td class="py-3">
            @if($w && $w->nama_pewawancara)
                <span class="text-truncate" style="max-width: 160px; display: inline-block;">{{ $w->nama_pewawancara }}</span>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td class="py-3">
            @if($w)
                @if($w->status_wawancara === 'Belum Wawancara')
                    <span class="badge bg-warning-subtle text-warning px-3 py-2">Belum Wawancara</span>
                @elseif($w->status_wawancara === 'Lolos')
                    <span class="badge bg-success-subtle text-success px-3 py-2">Lolos</span>
                @else
                    <span class="badge bg-danger-subtle text-danger px-3 py-2">Tidak Lolos</span>
                @endif
            @else
                <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Belum dijadwalkan</span>
            @endif
        </td>
        <td class="text-end">
            @include('components.actions-dropdown', ['items' => [
                ['modal' => '#jadwalModal', 'icon' => 'ti ti-calendar-stats', 'label' => 'Atur Jadwal', 'title' => 'Atur Jadwal Wawancara', 'data' => [
                    'pendaftaran-id' => $reg->id,
                    'nama' => $reg->nama_lengkap,
                    'tanggal' => $w?->tanggal_wawancara?->format('Y-m-d') ?? '',
                    'jam' => $w?->jam_wawancara ?? '',
                    'lokasi' => $w?->lokasi_wawancara ?? '',
                    'nama-pewawancara' => $w?->nama_pewawancara ?? '',
                ]],
                ['modal' => '#hasilModal', 'icon' => 'ti ti-checklist', 'label' => 'Input Hasil', 'title' => 'Input Hasil Wawancara', 'data' => [
                    'pendaftaran-id' => $reg->id,
                    'nama' => $reg->nama_lengkap,
                    'status' => $w?->status_wawancara ?? '',
                    'catatan' => $w?->catatan_pewawancara ?? '',
                ]],
            ]])
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-5">
            <i class="ti ti-users text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 mb-0 text-muted">Belum ada pendaftar pada jalur yang menggunakan wawancara.</p>
            <p class="text-muted small">Aktifkan opsi "Gunakan Tahapan Wawancara" pada pengaturan Jalur Pendaftaran.</p>
        </td>
    </tr>
@endforelse