@foreach($leads as $index => $lead)
  <tr>
    <td>{{ ($leads->currentPage() - 1) * $leads->perPage() + $index + 1 }}</td>
    <td><small>{{ $lead->created_at->format('d/m/Y H:i') }}</small></td>
    <td class="fw-semibold">{{ $lead->nama }}</td>
    <td>
      <a href="https://wa.me/{{ $lead->whatsapp }}?text=Halo%20{{ urlencode($lead->nama) }},%20saya%20Admin%20PMB..." target="_blank" class="text-success text-decoration-none">
        <i class="ti ti-brand-whatsapp me-1"></i>{{ $lead->whatsapp }}
      </a>
    </td>
    <td>
      <div class="text-truncate" title="{{ $lead->pertanyaan }}">
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
    <td>
      <div class="d-flex gap-1">
        <a href="https://wa.me/{{ $lead->whatsapp }}?text=Halo%20{{ urlencode($lead->nama) }},%20saya%20Admin%20PMB..." target="_blank" class="btn btn-sm btn-outline-success border-0" title="Follow Up WA">
          <i class="ti ti-brand-whatsapp"></i>
        </a>
        <button type="button" class="btn btn-sm btn-outline-info border-0" title="Detail & Catatan" onclick="openDetail({{ $lead->id }})">
          <i class="ti ti-list-details"></i>
        </button>
        <button type="button" class="btn btn-sm btn-outline-danger border-0" title="Hapus" onclick="deleteLead({{ $lead->id }}, '{{ addslashes($lead->nama) }}')">
          <i class="ti ti-trash"></i>
        </button>
      </div>
    </td>
  </tr>
@endforeach