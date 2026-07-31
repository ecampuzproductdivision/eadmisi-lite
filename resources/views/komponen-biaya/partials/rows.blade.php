@forelse($komponens as $komponen)
    <tr>
        <td class="ps-4"><code>{{ $komponen->kode_komponen }}</code></td>
        <td>{{ $komponen->nama_komponen }}</td>
        <td>{{ $komponen->deskripsi ?? '-' }}</td>
        <td>
            @if($komponen->is_active)
                <span class="badge text-success-emphasis bg-success-subtle border border-success-subtle px-3 py-2">
                    <i class="ti ti-circle-check me-1"></i> Aktif
                </span>
            @else
                <span class="badge text-danger-emphasis bg-danger-subtle border border-danger-subtle px-3 py-2">
                    <i class="ti ti-circle-x me-1"></i> Nonaktif
                </span>
            @endif
        </td>
        <td class="text-end pe-4">
            @include('components.actions-dropdown', ['items' => [
                [
                    'modal' => '#editModal' . $komponen->id,
                    'icon' => 'ti ti-edit',
                    'label' => 'Edit',
                ],
                ['divider' => true],
                [
                    'url' => route('komponen-biaya.toggle-status', $komponen),
                    'icon' => $komponen->is_active ? 'ti ti-player-pause' : 'ti ti-player-play',
                    'label' => $komponen->is_active ? 'Nonaktifkan' : 'Aktifkan',
                    'method' => 'POST',
                    'confirm' => $komponen->is_active ? 'Nonaktifkan komponen biaya ini?' : 'Aktifkan komponen biaya ini?',
                    'confirm_text' => $komponen->is_active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan',
                    'confirm_button_class' => $komponen->is_active ? 'btn-warning' : 'btn-success',
                    'confirm_icon' => $komponen->is_active ? 'player-pause' : 'player-play',
                    'confirm_icon_color' => $komponen->is_active ? 'text-warning' : 'text-success',
                ],
                [
                    'url' => route('komponen-biaya.destroy', $komponen),
                    'icon' => 'ti ti-trash',
                    'label' => 'Hapus',
                    'class' => 'text-danger',
                    'method' => 'DELETE',
                    'confirm' => 'Hapus komponen biaya ini?',
                ],
            ]])
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-5">
            @include('components.empty-state', [
                'icon' => 'ti-inbox',
                'title' => 'Belum ada komponen biaya',
                'subtitle' => 'Belum ada data komponen biaya yang tersedia saat ini.',
            ])
        </td>
    </tr>
@endforelse