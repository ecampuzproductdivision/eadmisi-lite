<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Matriks CPMK–CPL – {{ $kurikulum->kurKode }}</title>
  <style>
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 8px; color: #1a1a2e; margin: 10px; }
    h1 { font-size: 14px; margin-bottom: 2px; text-align: center; }
    h2 { font-size: 11px; color: #555; margin-bottom: 4px; text-align: center; }
    .subtitle { text-align: center; color: #777; margin-bottom: 12px; font-size: 9px; }
    table { width: 100%; border-collapse: collapse; margin-top: 5px; }
    thead tr { background: #f1f5f9; }
    th, td { border: 1px solid #94a3b8; padding: 4px 6px; }
    td.left { text-align: left; }
    td.center, th.center { text-align: center; }
    .c-t { background-color: #c8e6c9 !important; color: #2e7d32; font-weight: bold; }
    .c-s { background-color: #fff9c4 !important; color: #f57f17; font-weight: bold; }
    .c-r { background-color: #ffcdd2 !important; color: #c62828; font-weight: bold; }
    .c-q { background-color: #d1c4e9 !important; color: #4527a0; font-weight: bold; }
    .legend-container { margin-top: 15px; page-break-inside: avoid; }
    .legend-title { font-size: 9px; font-weight: bold; border-bottom: 1px solid #cbd5e1; padding-bottom: 2px; margin-bottom: 5px; }
    .legend-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 5px; }
    .legend-item { font-size: 7.5px; margin-bottom: 2px; }
    @page { margin: 15mm; }
  </style>
</head>
<body>
  <div style="border-bottom: 2px solid #1a56db; padding-bottom: 6px; margin-bottom: 8px;">
    <h1>MATRIKS CPMK–CPL</h1>
    <h2>{{ $kurikulum->kurNama }}</h2>
    <div class="subtitle">
      Program Studi: {{ $kurikulum->programStudi->prodiNamaResmi ?? '-' }} &nbsp;|&nbsp;
      Kode: {{ $kurikulum->kurKode }} &nbsp;|&nbsp;
      Skema: {{ $kurikulum->skema_kontribusi_cpl }} &nbsp;|&nbsp;
      Dicetak: {{ now()->format('d/m/Y H:i') }}
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th rowspan="2" class="left" style="width: 25%;">Mata Kuliah</th>
        <th rowspan="2" class="center" style="width: 4%;">Smt</th>
        <th rowspan="2" class="center" style="width: 4%;">SKS</th>
        @php $categories = $cpls->groupBy('kategori'); @endphp
        @foreach($categories as $cat => $catCpls)
          <th colspan="{{ $catCpls->count() }}" class="center" style="font-size: 7.5px; background: #e2e8f0;">{{ strtoupper($cat) }}</th>
        @endforeach
      </tr>
      <tr>
        @foreach($cpls as $cpl)
          <th class="center" style="width: 2.5%;">{{ $cpl->kode_cpl }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach($courses as $kmk)
        <tr>
          <td class="left"><strong>{{ $kmk->mataKuliah->mk_kode ?? '' }}</strong> - {{ $kmk->mataKuliah->mk_nama ?? '' }}</td>
          <td class="center">{{ $kmk->semester_anjuran }}</td>
          <td class="center">{{ $kmk->sks_override ?? ($kmk->mataKuliah->mk_sks_total ?? 0) }}</td>
          @foreach($cpls as $cpl)
            @php
              $key = $cpl->id_cpl . '_' . $kmk->id;
              $cell = $matrixCells[$key] ?? null;
              $val = '';
              $class = '';
              if ($cell) {
                if ($kurikulum->skema_kontribusi_cpl == 'KUALITATIF') {
                  $val = substr($cell->tingkat_kontribusi, 0, 1);
                  if ($cell->tingkat_kontribusi === 'Tinggi') $class = 'c-t';
                  elseif ($cell->tingkat_kontribusi === 'Sedang') $class = 'c-s';
                  elseif ($cell->tingkat_kontribusi === 'Rendah') $class = 'c-r';
                } else {
                  $val = round($cell->bobot_kontribusi) . '%';
                  $class = 'c-q';
                }
              }
            @endphp
            <td class="center {{ $class }}">{{ $val }}</td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="legend-container">
    <div class="legend-title">Daftar Legenda Capaian Pembelajaran Lulusan (CPL):</div>
    <table style="width: 100%; margin-top: 2px;">
      <thead>
        <tr style="background: #f8fafc;">
          <th class="left" style="width: 10%;">Kode</th>
          <th class="left" style="width: 25%;">Kategori</th>
          <th class="left">Deskripsi Kompetensi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($cpls as $cpl)
          <tr>
            <td class="left"><strong>{{ $cpl->kode_cpl }}</strong></td>
            <td class="left">{{ $cpl->kategori }}</td>
            <td class="left">{{ $cpl->deskripsi }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div style="margin-top:20px; text-align:center; color:#aaa; font-size:7px; border-top:1px solid #eee; padding-top:5px;">
    Dokumen ini dicetak dari Sistem Informasi Akademik – Modul Kurikulum OBE | {{ config('app.name') }}
  </div>
</body>
</html>
