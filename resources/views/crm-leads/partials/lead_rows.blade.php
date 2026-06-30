@foreach($leads as $index => $lead)
  <tr>
    <td>{{ ($leads->currentPage() - 1) * $leads->perPage() + $index + 1 }}</td>
    <td>{{ $lead->created_at->format('d/m/Y H:i') }}</td>
    <td class="fw-semibold">{{ $lead->nama }}</td>
    <td>
      <a href="https://wa.me/{{ $lead->whatsapp }}?text=Halo%20{{ urlencode($lead->nama) }},%20saya%20Admin%20PMB..." target="_blank" class="text-success text-decoration-none">
        <i class="ti ti-brand-whatsapp me-1"></i>{{ $lead->whatsapp }}
      </a>
    </td>
    <td>
      <div>
        {{ $lead->pertanyaan }}
      </div>
    </td>
    <td>
      <div class="dropdown">
        <button class="btn btn-sm dropdown-toggle badge bg-{{ \App\Models\CrmLead::statusBadgeClass($lead->status) }}-subtle text-{{ \App\Models\CrmLead::statusBadgeClass($lead->status) }} border-0 px-3 py-2" type="button" data-bs-toggle="dropdown">
          {{ $lead->status }}
        </button>
        <ul class="dropdown-menu">
          @foreach(\App\Models\CrmLead::STATUSES as $st)
            <li><a class="dropdown-item small {{ $lead->status === $st ? 'active' : '' }}" href="javascript:void(0)" onclick="updateStatus({{ $lead->id }}, '{{ $st }}')">{{ $st }}</a></li>
          @endforeach
        </ul>
      </div>
    </td>
    <td class="text-end">
      @include('components.actions-dropdown', ['items' => [
        ['url' => 'https://wa.me/' . $lead->whatsapp . '?text=Halo%20' . urlencode($lead->nama) . ',%20saya%20Admin%20PMB...', 'icon' => 'ti ti-brand-whatsapp', 'label' => 'Follow Up WA', 'class' => 'text-success', 'title' => 'Follow Up via WhatsApp'],
        ['onclick' => 'openDetail(' . $lead->id . ')', 'icon' => 'ti ti-list-details', 'label' => 'Detail & Catatan', 'title' => 'Lihat Detail & Catatan'],
        ['divider' => true],
        ['onclick' => 'deleteLead(' . $lead->id . ', \'' . addslashes($lead->nama) . '\')', 'icon' => 'ti ti-trash', 'label' => 'Hapus', 'class' => 'text-danger', 'title' => 'Hapus Lead'],
      ]])
    </td>
  </tr>
@endforeach