@php
if (!function_exists('country_flag')) {
    function country_flag($iso2) {
        $iso2 = strtoupper(trim($iso2));
        if (strlen($iso2) !== 2) return '🌐';
        // Convert ISO 2 code to flag emoji
        try {
            $first = ord($iso2[0]) - 65 + 127462;
            $second = ord($iso2[1]) - 65 + 127462;
            return mb_chr($first) . mb_chr($second);
        } catch (\Exception $e) {
            return '🌐';
        }
    }
}
@endphp

@foreach($countries as $country)
  <tr>
    <td>
      <div class="d-flex align-items-center gap-3">
        <span class="fs-2" style="line-height: 1;">
          {{ country_flag($country->iso2) }}
        </span>
        <div>
          <h5 class="mb-0 fw-semibold">{{ $country->name }}</h5>
        </div>
      </div>
    </td>
    <td>
      <span class="badge bg-light border text-dark fw-bold px-2 py-1">{{ $country->iso2 }}</span>
    </td>
    <td>
      <span class="badge bg-light border text-dark fw-bold px-2 py-1">{{ $country->iso3 }}</span>
    </td>
    <td>
      <code class="text-muted fw-semibold">{{ $country->phone_code ?: '—' }}</code>
    </td>
    <td>
      <div class="form-check form-switch mb-0">
        <input class="form-check-input status-toggle" type="checkbox" role="switch" 
          data-url="{{ route('country.toggle-status', $country->id) }}" 
          {{ $country->status == 'active' ? 'checked' : '' }}>
        <label class="form-check-label status-label {{ $country->status == 'active' ? 'text-success' : 'text-danger' }} fw-semibold small ms-1">
          {{ $country->status == 'active' ? 'Aktif' : 'Nonaktif' }}
        </label>
      </div>
    </td>
    <td class="text-end">
      <div class="d-inline-flex gap-2">
        <a href="{{ route('country.edit', $country->id) }}" class="btn btn-sm btn-light border" title="Ubah">
          <i class="ti ti-edit fs-5"></i>
        </a>
        <form action="{{ route('country.destroy', $country->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data negara ini?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus">
            <i class="ti ti-trash fs-5"></i>
          </button>
        </form>
      </div>
    </td>
  </tr>
@endforeach
