<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; font-size: 11px; }
  table { border-collapse: collapse; width: 100%; }
  th, td { border: 1px solid #ccc; padding: 4px 6px; }
  th { background: #dbeafe; font-weight: bold; }
  .smt-header { background: #1e40af; color: white; font-size: 12px; }
  .credits { background: #f0fdf4; }
  .no-prereq { color: #9ca3af; font-style: italic; }
</style>
</head>
<body>
<h2>Prasyarat Mata Kuliah — {{ $kurikulum->kurNama }}</h2>
<p>Program Studi: {{ optional($kurikulum->programStudi)->prodiNamaResmi ?? '-' }} | Kode: {{ $kurikulum->kurKode }} | Tahun: {{ $kurikulum->kurTahunMulai }}–{{ $kurikulum->kurTahunSelesai }}</p>
<p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
<br>

{{-- Sheet 1: Daftar Prasyarat Per Semester --}}
<h3>Daftar Prasyarat Mata Kuliah</h3>
<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Smt</th>
      <th>Kode MK</th>
      <th>Nama Mata Kuliah</th>
      <th>SKS</th>
      <th>Jenis</th>
      <th>Prasyarat</th>
      <th>Nilai Min</th>
      <th>SKS Min</th>
      <th>Grup Logika</th>
      <th>Keterangan</th>
    </tr>
  </thead>
  <tbody>
    @php $no = 1; @endphp
    @foreach($semesters as $sem)
      @php $mkInSmt = $allKmk->where('semester_anjuran', $sem->nomor_semester); @endphp
      @if($mkInSmt->count() > 0)
        <tr>
          <td colspan="11" class="smt-header">Semester {{ $sem->nomor_semester }} — {{ $sem->label_semester }}</td>
        </tr>
        @foreach($mkInSmt as $kmk)
          @php $rules = $kmk->prasyarats; @endphp
          @if($rules->count() === 0)
            <tr>
              <td>{{ $no++ }}</td>
              <td>{{ $kmk->semester_anjuran }}</td>
              <td>{{ $kmk->mataKuliah->mk_kode ?? '-' }}</td>
              <td>{{ $kmk->mataKuliah->mk_nama ?? '-' }}</td>
              <td>{{ $kmk->sks_override ?? $kmk->mataKuliah->mk_sks_total ?? 0 }}</td>
              <td colspan="6" class="no-prereq">Tidak ada prasyarat</td>
            </tr>
          @else
            @foreach($rules as $i => $rule)
              <tr class="{{ $rule->jenis_prasyarat === 'CREDITS' ? 'credits' : '' }}">
                @if($i === 0)
                  <td rowspan="{{ $rules->count() }}">{{ $no++ }}</td>
                  <td rowspan="{{ $rules->count() }}">{{ $kmk->semester_anjuran }}</td>
                  <td rowspan="{{ $rules->count() }}">{{ $kmk->mataKuliah->mk_kode ?? '-' }}</td>
                  <td rowspan="{{ $rules->count() }}">{{ $kmk->mataKuliah->mk_nama ?? '-' }}</td>
                  <td rowspan="{{ $rules->count() }}">{{ $kmk->sks_override ?? $kmk->mataKuliah->mk_sks_total ?? 0 }}</td>
                @endif
                <td>{{ $rule->jenis_prasyarat }}</td>
                <td>{{ optional(optional($rule->prasyaratKurikulumMataKuliah)->mataKuliah)->mk_nama ?? '(SKS Kumulatif)' }}</td>
                <td>{{ $rule->nilai_min ?? '-' }}</td>
                <td>{{ $rule->sks_kumulatif_min ? $rule->sks_kumulatif_min . ' SKS' : '-' }}</td>
                <td>{{ $rule->grup_logika ?? '-' }}</td>
                <td>{{ $rule->keterangan ?? '-' }}</td>
              </tr>
            @endforeach
          @endif
        @endforeach
      @endif
    @endforeach
  </tbody>
</table>

<br><br>
{{-- Sheet 2: Raw data --}}
<h3>Raw Data Aturan Prasyarat</h3>
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>ID KMK (Butuh Prasyarat)</th>
      <th>ID KMK Prasyarat</th>
      <th>Jenis</th>
      <th>Nilai Min</th>
      <th>SKS Min</th>
      <th>SKS Tipe</th>
      <th>Grup Logika</th>
    </tr>
  </thead>
  <tbody>
    @foreach($allKmk as $kmk)
      @foreach($kmk->prasyarats as $rule)
        <tr>
          <td style="font-family:monospace;font-size:9px;">{{ $rule->id }}</td>
          <td>{{ $rule->kmk_id }}</td>
          <td>{{ $rule->prasyarat_kmk_id ?? 'NULL' }}</td>
          <td>{{ $rule->jenis_prasyarat }}</td>
          <td>{{ $rule->nilai_min ?? '-' }}</td>
          <td>{{ $rule->sks_kumulatif_min ?? '-' }}</td>
          <td>{{ $rule->sks_kumulatif_tipe ?? '-' }}</td>
          <td>{{ $rule->grup_logika ?? '-' }}</td>
        </tr>
      @endforeach
    @endforeach
  </tbody>
</table>

<br><br>
{{-- Sheet 3: Statistics --}}
<h3>Statistik Prasyarat per Semester</h3>
<table>
  <thead>
    <tr>
      <th>Semester</th>
      <th>Total MK</th>
      <th>MK dengan Prasyarat</th>
      <th>MK tanpa Prasyarat</th>
      <th>Total Aturan</th>
    </tr>
  </thead>
  <tbody>
    @foreach($semesters as $sem)
      @php
        $mkInSmt  = $allKmk->where('semester_anjuran', $sem->nomor_semester);
        $total    = $mkInSmt->count();
        $dgPreq   = $mkInSmt->filter(fn($k) => $k->prasyarats->count() > 0)->count();
        $tpPreq   = $total - $dgPreq;
        $ttlRules = $mkInSmt->sum(fn($k) => $k->prasyarats->count());
      @endphp
      <tr>
        <td>Semester {{ $sem->nomor_semester }}</td>
        <td>{{ $total }}</td>
        <td>{{ $dgPreq }}</td>
        <td>{{ $tpPreq }}</td>
        <td>{{ $ttlRules }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
</body>
</html>
