<table>
  <thead>
    <tr>
      <th colspan="{{ $cpls->count() + 3 }}" style="font-weight: bold; font-size: 14px; text-align: center;">MATRIKS CPL - MATA KULIAH</th>
    </tr>
    <tr>
      <th colspan="{{ $cpls->count() + 3 }}" style="font-weight: bold; font-size: 12px; text-align: center;">{{ $kurikulum->kurNama }}</th>
    </tr>
    <tr>
      <th colspan="{{ $cpls->count() + 3 }}" style="text-align: center;">Program Studi: {{ $kurikulum->programStudi->prodiNamaResmi ?? '-' }} | Kode: {{ $kurikulum->kurKode }} | Skema: {{ $kurikulum->skema_kontribusi_cpl }}</th>
    </tr>
    <tr></tr>
    <tr style="background-color: #f8fafc; font-weight: bold;">
      <th rowspan="2" style="border: 1px solid #dee2e6; text-align: left; vertical-align: middle;">Mata Kuliah</th>
      <th rowspan="2" style="border: 1px solid #dee2e6; text-align: center; vertical-align: middle;">Semester</th>
      <th rowspan="2" style="border: 1px solid #dee2e6; text-align: center; vertical-align: middle;">SKS</th>
      @php
        $categories = $cpls->groupBy('kategori');
      @endphp
      @foreach($categories as $cat => $catCpls)
        <th colspan="{{ $catCpls->count() }}" style="border: 1px solid #dee2e6; text-align: center; background-color: #eef2ff;">{{ strtoupper($cat) }}</th>
      @endforeach
    </tr>
    <tr style="background-color: #f8fafc; font-weight: bold;">
      @foreach($cpls as $cpl)
        <th style="border: 1px solid #dee2e6; text-align: center;" title="{{ $cpl->deskripsi }}">{{ $cpl->kode_cpl }}</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach($courses as $kmk)
      <tr>
        <td style="border: 1px solid #dee2e6; text-align: left;">{{ $kmk->mataKuliah->mk_kode ?? '' }} - {{ $kmk->mataKuliah->mk_nama ?? '' }}</td>
        <td style="border: 1px solid #dee2e6; text-align: center;">{{ $kmk->semester_anjuran }}</td>
        <td style="border: 1px solid #dee2e6; text-align: center;">{{ $kmk->sks_override ?? ($kmk->mataKuliah->mk_sks_total ?? 0) }}</td>
        
        @foreach($cpls as $cpl)
          @php
            $key = $cpl->id_cpl . '_' . $kmk->id;
            $cell = $matrixCells[$key] ?? null;
            $val = '';
            $bgColor = '#ffffff';
            
            if ($cell) {
              if ($kurikulum->skema_kontribusi_cpl == 'KUALITATIF') {
                $val = substr($cell->tingkat_kontribusi, 0, 1);
                if ($cell->tingkat_kontribusi === 'Tinggi') $bgColor = '#c8e6c9';
                elseif ($cell->tingkat_kontribusi === 'Sedang') $bgColor = '#fff9c4';
                elseif ($cell->tingkat_kontribusi === 'Rendah') $bgColor = '#ffcdd2';
              } else {
                $val = round($cell->bobot_kontribusi) . '%';
                $bgColor = '#d1c4e9';
              }
            }
          @endphp
          <td style="border: 1px solid #dee2e6; text-align: center; background-color: {{ $bgColor }}; font-weight: bold;">{{ $val }}</td>
        @endforeach
      </tr>
    @endforeach
  </tbody>
</table>
