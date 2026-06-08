<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Struktur Kurikulum – {{ $kurikulum->kurKode }}</title>
  <style>
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1a1a2e; margin: 20px; }
    h1 { font-size: 16px; margin-bottom: 2px; }
    h2 { font-size: 13px; color: #555; margin-bottom: 4px; }
    .subtitle { color: #777; margin-bottom: 16px; font-size: 10px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    thead tr { background: #1a56db; color: #fff; }
    th, td { border: 1px solid #ccc; padding: 5px 8px; text-align: left; vertical-align: top; }
    td.center { text-align: center; }
    .sem-header { background: #eef2ff; font-weight: bold; font-size: 11px; }
    .kelompok-badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; }
    .total-row { background: #f0f4ff; font-weight: bold; }
    .page-break { page-break-before: always; }
    .summary-table td { border: none; padding: 3px 8px; }
    .ok { color: #166534; } .warn { color: #92400e; } .err { color: #991b1b; }
    @media print {
      button { display: none; }
    }
  </style>
</head>
<body>

  <div class="text-center mb-3" style="text-align:center; border-bottom:2px solid #1a56db; padding-bottom: 10px; margin-bottom:14px;">
    <h1>STRUKTUR KURIKULUM</h1>
    <h2>{{ $kurikulum->kurNama }}</h2>
    <div class="subtitle">
      Program Studi: {{ $kurikulum->prodiNamaResmi ?? '-' }} &nbsp;|&nbsp;
      Kode: {{ $kurikulum->kurKode }} &nbsp;|&nbsp;
      Tahun: {{ $kurikulum->kurTahunMulai }} – {{ $kurikulum->kurTahunSelesai ?: 'Sekarang' }} &nbsp;|&nbsp;
      Status: {{ $kurikulum->kurIsAktif ? 'AKTIF' : 'DRAFT' }} &nbsp;|&nbsp;
      Dicetak: {{ now()->format('d/m/Y H:i') }}
    </div>
  </div>

  <!-- Summary SKS -->
  <h3 style="font-size:12px; border-bottom:1px solid #ddd; padding-bottom:4px; margin-bottom:6px;">Ringkasan SKS</h3>
  <table class="summary-table" style="width:auto;">
    <tr><td><b>Total SKS Wajib Lulus</b></td><td>:</td><td><b>{{ $kurikulum->kurSksLulus ?? 144 }} SKS</b></td></tr>
    <tr><td>Total SKS Terpasang</td><td>:</td><td>{{ $totalSks }} SKS</td></tr>
    <tr><td>Total Mata Kuliah</td><td>:</td><td>{{ $totalMk }} MK</td></tr>
  </table>

  <!-- Per Kelompok Summary -->
  <h3 style="font-size:12px; border-bottom:1px solid #ddd; padding-bottom:4px; margin-bottom:6px; margin-top:14px;">Distribusi per Kelompok MK</h3>
  <table>
    <thead>
      <tr>
        <th>Kode</th>
        <th>Nama Kelompok</th>
        <th class="center">Min SKS</th>
        <th class="center">SKS Terpasang</th>
        <th class="center">Jml MK</th>
        <th class="center">Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($kelompoks as $kel)
        @php
          $kelMks = $mappedKmk->where('kelompok_id', $kel->id);
          $kelSks = $kelMks->sum(fn($kmk) => $kmk->sks_override ?? ($kmk->mataKuliah->mk_sks_total ?? 0));
          $terpenuhi = $kelSks >= $kel->sks_minimum;
        @endphp
        <tr>
          <td><b>{{ $kel->kode_kelompok }}</b></td>
          <td>{{ $kel->nama_kelompok }}</td>
          <td class="center">{{ $kel->sks_minimum }}</td>
          <td class="center"><b>{{ $kelSks }}</b></td>
          <td class="center">{{ $kelMks->count() }}</td>
          <td class="center {{ $terpenuhi ? 'ok' : 'err' }}">{{ $terpenuhi ? '✔ Terpenuhi' : '✘ Kurang '.($kel->sks_minimum - $kelSks) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <!-- Distribusi Semester -->
  <div class="page-break"></div>
  <h3 style="font-size:12px; border-bottom:1px solid #ddd; padding-bottom:4px; margin-bottom:6px;">Distribusi Mata Kuliah per Semester</h3>
  @foreach($semesters as $semester)
    @php
      $semMks = $mappedKmk->where('semester_anjuran', $semester->nomor_semester)->sortBy('urutan_dalam_semester');
      $semSks = $semMks->sum(fn($kmk) => $kmk->sks_override ?? ($kmk->mataKuliah->mk_sks_total ?? 0));
    @endphp
    <h4 style="font-size: 11px; margin: 12px 0 4px 0; color:#1a56db;">
      {{ $semester->label_semester ?: 'Semester '.$semester->nomor_semester }}
      ({{ $semMks->count() }} MK / {{ $semSks }} SKS)
    </h4>
    @if($semMks->isEmpty())
      <p style="color:#aaa; font-size:9px; margin: 0 0 8px 0;">— Belum ada mata kuliah —</p>
    @else
      <table>
        <thead>
          <tr>
            <th style="width:30px;">No</th>
            <th style="width:80px;">Kode MK</th>
            <th>Nama Mata Kuliah</th>
            <th style="width:60px;">Kelompok</th>
            <th class="center" style="width:40px;">SKS</th>
            <th style="width:80px;">Prasyarat</th>
          </tr>
        </thead>
        <tbody>
          @foreach($semMks as $no => $kmk)
            @php
              $prereqNames = $kmk->prasyarats->map(fn($p) => optional(optional($p->prasyaratKurikulumMataKuliah)->mataKuliah)->mk_kode ?? 'SKS');
            @endphp
            <tr>
              <td class="center">{{ $no + 1 }}</td>
              <td class="center"><b>{{ $kmk->mataKuliah->mk_kode ?? '-' }}</b></td>
              <td>{{ $kmk->mataKuliah->mk_nama ?? '-' }}</td>
              <td class="center">{{ $kmk->kelompokMk->kode_kelompok ?? '-' }}</td>
              <td class="center">{{ $kmk->sks_override ?? ($kmk->mataKuliah->mk_sks_total ?? 0) }}</td>
              <td class="center">{{ $prereqNames->implode(', ') ?: '-' }}</td>
            </tr>
          @endforeach
          <tr class="total-row">
            <td colspan="4" class="center"><b>TOTAL</b></td>
            <td class="center"><b>{{ $semSks }}</b></td>
            <td></td>
          </tr>
        </tbody>
      </table>
    @endif
  @endforeach

  <div style="margin-top:30px; text-align:center; color:#aaa; font-size:9px; border-top:1px solid #eee; padding-top:8px;">
    Dokumen ini dicetak dari Sistem Informasi Akademik – Modul Kurikulum OBE | {{ config('app.name') }}
  </div>

  <script>window.onload = function() { window.print(); }</script>
</body>
</html>
