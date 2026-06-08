<?php
// We use a clean HTML table structure that Excel parses automatically.
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    .title {
      font-size: 14px;
      font-weight: bold;
      text-align: center;
    }
    .header-label {
      font-weight: bold;
      background-color: #e0e0e0;
    }
    th {
      background-color: #1f4e78;
      color: #ffffff;
      font-weight: bold;
      border: 1px solid #000000;
    }
    td {
      border: 1px solid #d9d9d9;
      vertical-align: top;
    }
    .sub-header {
      background-color: #f2f2f2;
      font-weight: bold;
    }
    .text-center {
      text-align: center;
    }
    .font-monospace {
      font-family: Courier, monospace;
    }
  </style>
</head>
<body>
  <table>
    <tr>
      <td colspan="8" class="title">REKAPITULASI CPMK & SUB-CPMK MATA KULIAH</td>
    </tr>
    <tr>
      <td colspan="8"></td>
    </tr>
    <tr>
      <td colspan="2" class="header-label">Mata Kuliah:</td>
      <td colspan="2">{{ $course->mk_nama }} ({{ $course->mk_kode }})</td>
      <td colspan="2" class="header-label">Kurikulum:</td>
      <td colspan="2">{{ $kurikulum->kurNama }} ({{ $kurikulum->kurKode }})</td>
    </tr>
    <tr>
      <td colspan="2" class="header-label">SKS:</td>
      <td colspan="2">{{ $course->mk_sks_total }} SKS</td>
      <td colspan="2" class="header-label">Program Studi:</td>
      <td colspan="2">{{ $kurikulum->programStudi->prodiNamaResmi ?? '-' }}</td>
    </tr>
    <tr>
      <td colspan="8"></td>
    </tr>
    <thead>
      <tr>
        <th style="width: 12%;">Kode CPMK / Sub-CPMK</th>
        <th style="width: 30%;">Rumusan Capaian Pembelajaran</th>
        <th style="width: 15%;">Ranah Taksonomi</th>
        <th style="width: 12%;">Level Bloom</th>
        <th style="width: 15%;">KKO Bloom</th>
        <th style="width: 10%; text-align: center;">Bobot CPMK</th>
        <th style="width: 10%; text-align: center;">Bobot Sub dlm CPMK</th>
        <th style="width: 12%; text-align: center;">Pertemuan Ke</th>
      </tr>
    </thead>
    <tbody>
      @forelse($cpmks as $cpmk)
        <!-- CPMK Main Row -->
        <tr style="background-color: #eaf1f5; font-weight: bold;">
          <td class="font-monospace text-center">{{ $cpmk->kode_cpmk }}</td>
          <td>{{ $cpmk->deskripsi }}</td>
          <td>{{ $cpmk->ranah_taksonomi }}</td>
          <td>{{ $cpmk->level_bloom }}</td>
          <td>{{ $cpmk->kko_bloom }}</td>
          <td class="text-center">{{ number_format($cpmk->bobot_cpmk, 0) }}%</td>
          <td class="text-center">-</td>
          <td class="text-center">-</td>
        </tr>

        <!-- Sub-CPMK Rows -->
        @foreach($cpmk->subCpmks as $sub)
          <tr>
            <td class="font-monospace text-center" style="color: #0d6efd; padding-left: 20px;">{{ $sub->kode_sub_cpmk }}</td>
            <td style="padding-left: 20px;">{{ $sub->deskripsi }}</td>
            <td>{{ $sub->ranah_taksonomi }}</td>
            <td>{{ $sub->level_bloom }}</td>
            <td>{{ $sub->kko_bloom }}</td>
            <td class="text-center">-</td>
            <td class="text-center">{{ $sub->bobot_dalam_cpmk ? number_format($sub->bobot_dalam_cpmk, 0) . '%' : '-' }}</td>
            <td class="text-center">Pert. {{ $sub->pertemuan_ke }}</td>
          </tr>
        @endforeach
      @empty
        <tr>
          <td colspan="8" class="text-center" style="color: #999; font-style: italic;">Belum ada data CPMK untuk mata kuliah ini.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
