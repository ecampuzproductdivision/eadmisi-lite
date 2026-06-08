<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Rekap_CPMK_{{ $course->mk_kode }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 11px;
      color: #333;
      line-height: 1.4;
      margin: 10px;
    }
    .header-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    .header-table td {
      padding: 4px;
      vertical-align: top;
    }
    .title {
      font-size: 16px;
      font-weight: bold;
      text-transform: uppercase;
      text-align: center;
      margin-bottom: 20px;
      border-bottom: 2px solid #000;
      padding-bottom: 8px;
    }
    .main-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    .main-table th, .main-table td {
      border: 1px solid #666;
      padding: 6px 8px;
      text-align: left;
      vertical-align: top;
    }
    .main-table th {
      background-color: #f2f2f2;
      font-weight: bold;
    }
    .sub-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 5px;
      background-color: #fafafa;
    }
    .sub-table th, .sub-table td {
      border: 1px solid #ddd;
      padding: 4px 6px;
      font-size: 10px;
    }
    .sub-table th {
      background-color: #eaeaea;
    }
    .text-center {
      text-align: center;
    }
    .font-monospace {
      font-family: Courier, monospace;
      font-weight: bold;
    }
    .badge {
      display: inline-block;
      padding: 2px 5px;
      background-color: #e3f2fd;
      color: #0d6efd;
      border-radius: 3px;
      font-size: 9px;
      margin: 1px;
    }
    .badge-secondary {
      background-color: #f1f3f5;
      color: #6c757d;
    }
  </style>
</head>
<body onload="window.print()">
  <div class="title">Capaian Pembelajaran Mata Kuliah (CPMK) & Sub-CPMK</div>
  
  <table class="header-table">
    <tr>
      <td style="width: 20%;"><strong>Nama Mata Kuliah</strong></td>
      <td style="width: 30%;">: {{ $course->mk_nama }}</td>
      <td style="width: 20%;"><strong>Versi Kurikulum</strong></td>
      <td style="width: 30%;">: {{ $kurikulum->kurNama }} ({{ $kurikulum->kurKode }})</td>
    </tr>
    <tr>
      <td><strong>Kode Mata Kuliah</strong></td>
      <td>: {{ $course->mk_kode }}</td>
      <td><strong>Program Studi</strong></td>
      <td>: {{ $kurikulum->programStudi->prodiNamaResmi ?? '-' }}</td>
    </tr>
    <tr>
      <td><strong>Bobot SKS</strong></td>
      <td>: {{ $course->mk_sks_total }} SKS</td>
      <td><strong>Tanggal Cetak</strong></td>
      <td>: {{ date('d F Y H:i') }}</td>
    </tr>
  </table>

  <table class="main-table">
    <thead>
      <tr>
        <th style="width: 8%;">Kode</th>
        <th style="width: 35%;">Deskripsi CPMK</th>
        <th style="width: 15%;">Ranah & Level Bloom</th>
        <th style="width: 10%; text-align: center;">Bobot (%)</th>
        <th style="width: 17%;">CPL Terkait</th>
        <th style="width: 15%;">Asesmen Evaluasi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($cpmks as $cpmk)
        <tr>
          <td class="font-monospace text-center">{{ $cpmk->kode_cpmk }}</td>
          <td>
            <strong>{{ $cpmk->deskripsi_singkat }}</strong>
            <p style="margin: 4px 0 0 0; font-size: 10px; color: #555;">{{ $cpmk->deskripsi }}</p>
            
            <!-- Render Sub-CPMK if available -->
            @if($cpmk->subCpmks->isNotEmpty())
              <div style="margin-top: 10px; font-weight: bold; font-size: 10px;">Sub-CPMK:</div>
              <table class="sub-table">
                <thead>
                  <tr>
                    <th style="width: 20%;">Kode Sub</th>
                    <th style="width: 50%;">Deskripsi Sub-CPMK</th>
                    <th style="width: 20%;">Bloom</th>
                    <th style="width: 10%; text-align: center;">Bobot</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($cpmk->subCpmks as $sub)
                    <tr>
                      <td class="font-monospace" style="color: #0d6efd;">{{ $sub->kode_sub_cpmk }}</td>
                      <td>{{ $sub->deskripsi }} (Pert. {{ $sub->pertemuan_ke }})</td>
                      <td>{{ $sub->level_bloom }} ({{ $sub->kko_bloom }})</td>
                      <td class="text-center">{{ $sub->bobot_dalam_cpmk ? number_format($sub->bobot_dalam_cpmk, 0) . '%' : '-' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @endif
          </td>
          <td>
            {{ $cpmk->ranah_taksonomi }}
            <div style="margin-top: 2px;"><span class="badge badge-secondary">{{ $cpmk->level_bloom }}</span></div>
            <div style="margin-top: 2px; font-style: italic;">"{{ $cpmk->kko_bloom }}"</div>
          </td>
          <td class="text-center" style="font-weight: bold;">{{ number_format($cpmk->bobot_cpmk, 0) }}%</td>
          <td>
            @forelse($cpmk->cpls as $cpl)
              <span class="badge">{{ $cpl->kode_cpl }}</span>
            @empty
              <span style="color: red; font-style: italic;">Belum dipetakan</span>
            @endforelse
          </td>
          <td>
            @forelse($cpmk->komponenPemetaan as $ka)
              <div style="font-size: 10px; margin-bottom: 2px;">
                • {{ $ka->komponen->nama_komponen ?? '-' }}
                @if($ka->bobot_dalam_cpmk)
                  ({{ number_format($ka->bobot_dalam_cpmk, 0) }}%)
                @endif
              </div>
            @empty
              <span style="color: red; font-style: italic;">Belum dipetakan</span>
            @endforelse
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="text-center" style="padding: 20px; font-style: italic; color: #999;">Belum ada data CPMK untuk mata kuliah ini.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div style="margin-top: 30px; text-align: right; font-size: 10px; color: #666;">
    Dokumen kurikulum ini dihasilkan secara otomatis oleh Sistem Akademik OBE Universitas.
  </div>
</body>
</html>
