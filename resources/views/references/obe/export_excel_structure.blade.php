<table>
  <thead>
    <tr>
      <th colspan="6" style="font-weight: bold; font-size: 14px; text-align: center;">STRUKTUR KURIKULUM</th>
    </tr>
    <tr>
      <th colspan="6" style="font-weight: bold; font-size: 12px; text-align: center;">{{ $kurikulum->kurNama }}</th>
    </tr>
    <tr>
      <th colspan="6" style="text-align: center;">Program Studi: {{ $kurikulum->programStudi->prodiNamaResmi ?? '-' }} | Kode: {{ $kurikulum->kurKode }}</th>
    </tr>
    <tr></tr>
    <tr style="background-color: #1a56db; color: #ffffff; font-weight: bold;">
      <th>Semester</th>
      <th>Kode MK</th>
      <th>Nama Mata Kuliah</th>
      <th>Kelompok MK</th>
      <th>SKS</th>
      <th>Prasyarat</th>
    </tr>
  </thead>
  <tbody>
    @foreach($semesters as $semester)
      @php
        $semMks = $mappedKmk->where('semester_anjuran', $semester->nomor_semester)->sortBy('urutan_dalam_semester');
        $semSks = $semMks->sum(fn($kmk) => $kmk->sks_override ?? ($kmk->mataKuliah->mk_sks_total ?? 0));
      @endphp
      <tr style="background-color: #eef2ff; font-weight: bold;">
        <td colspan="4">{{ $semester->label_semester ?: 'Semester '.$semester->nomor_semester }}</td>
        <td style="text-align: center;">{{ $semSks }}</td>
        <td></td>
      </tr>
      @if($semMks->isEmpty())
        <tr>
          <td colspan="6" style="color: #999999; font-style: italic; text-align: center;">Belum ada mata kuliah</td>
        </tr>
      @else
        @foreach($semMks as $kmk)
          @php
            $prereqNames = $kmk->prasyarats->map(fn($p) => optional(optional($p->prasyaratKurikulumMataKuliah)->mataKuliah)->mk_kode ?? 'SKS');
          @endphp
          <tr>
            <td style="text-align: center;">{{ $semester->nomor_semester }}</td>
            <td>{{ $kmk->mataKuliah->mk_kode ?? '-' }}</td>
            <td>{{ $kmk->mataKuliah->mk_nama ?? '-' }}</td>
            <td style="text-align: center;">{{ $kmk->kelompokMk->kode_kelompok ?? '-' }}</td>
            <td style="text-align: center;">{{ $kmk->sks_override ?? ($kmk->mataKuliah->mk_sks_total ?? 0) }}</td>
            <td>{{ $prereqNames->implode(', ') ?: '-' }}</td>
          </tr>
        @endforeach
      @endif
    @endforeach
  </tbody>
</table>
