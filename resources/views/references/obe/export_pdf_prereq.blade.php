<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Prasyarat Mata Kuliah — {{ $kurikulum->kurNama }}</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Arial', sans-serif; font-size: 11px; color: #1e293b; background: white; }
  @page { size: A4 landscape; margin: 15mm; }

  .doc-header { text-align: center; border-bottom: 3px solid #1d4ed8; padding-bottom: 10px; margin-bottom: 16px; }
  .doc-header h1 { font-size: 16px; color: #1d4ed8; margin-bottom: 4px; }
  .doc-header p { font-size: 11px; color: #64748b; }

  .meta-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 16px; }
  .meta-box { background: #f1f5f9; border-radius: 4px; padding: 6px 10px; }
  .meta-box .label { font-size: 9px; color: #64748b; text-transform: uppercase; font-weight: bold; }
  .meta-box .value { font-size: 12px; font-weight: bold; color: #1e293b; }

  .semester-section { margin-bottom: 20px; page-break-inside: avoid; }
  .smt-title { background: #1d4ed8; color: white; padding: 5px 10px; font-size: 11px; font-weight: bold; border-radius: 4px 4px 0 0; }

  table { width: 100%; border-collapse: collapse; margin-top: 0; }
  th { background: #dbeafe; font-size: 9px; font-weight: bold; text-align: left; padding: 4px 6px; border: 1px solid #bfdbfe; }
  td { border: 1px solid #e2e8f0; padding: 4px 6px; vertical-align: top; }
  tr:nth-child(even) td { background: #f8fafc; }

  .badge-pass { background: #dcfce7; color: #166534; border-radius: 3px; padding: 1px 5px; font-size: 9px; font-weight: bold; }
  .badge-taken { background: #f1f5f9; color: #475569; border-radius: 3px; padding: 1px 5px; font-size: 9px; font-weight: bold; }
  .badge-coreq { background: #ffedd5; color: #9a3412; border-radius: 3px; padding: 1px 5px; font-size: 9px; font-weight: bold; }
  .badge-credits { background: #f3e8ff; color: #6b21a8; border-radius: 3px; padding: 1px 5px; font-size: 9px; font-weight: bold; }

  .no-prereq td { color: #94a3b8; font-style: italic; }
  .logic-and { font-weight: bold; color: #b45309; font-size: 9px; }
  .logic-or { color: #0369a1; font-size: 9px; }

  .footer { margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 8px; text-align: center; color: #94a3b8; font-size: 9px; }
</style>
</head>
<body>

<div class="doc-header">
  <h1>Panduan Prasyarat Mata Kuliah</h1>
  <p>{{ $kurikulum->kurNama }} — {{ optional($kurikulum->programStudi)->prodiNamaResmi ?? '-' }}</p>
</div>

<div class="meta-grid">
  <div class="meta-box">
    <div class="label">Kode Kurikulum</div>
    <div class="value">{{ $kurikulum->kurKode }}</div>
  </div>
  <div class="meta-box">
    <div class="label">Tahun Berlaku</div>
    <div class="value">{{ $kurikulum->kurTahunMulai }} – {{ $kurikulum->kurTahunSelesai }}</div>
  </div>
  <div class="meta-box">
    <div class="label">Status</div>
    <div class="value" style="color: {{ $kurikulum->kurIsAktif ? '#166534' : '#92400e' }};">
      {{ $kurikulum->kurIsAktif ? 'Aktif' : 'Draft' }}
    </div>
  </div>
  <div class="meta-box">
    <div class="label">Total SKS Lulus</div>
    <div class="value">{{ $kurikulum->kurSksLulus }} SKS</div>
  </div>
  <div class="meta-box">
    <div class="label">Total Mata Kuliah</div>
    <div class="value">{{ $allKmk->count() }} MK</div>
  </div>
  <div class="meta-box">
    <div class="label">Dicetak</div>
    <div class="value">{{ now()->format('d/m/Y H:i') }}</div>
  </div>
</div>

@foreach($semesters as $sem)
  @php $mkInSmt = $allKmk->where('semester_anjuran', $sem->nomor_semester); @endphp
  @if($mkInSmt->count() > 0)
  <div class="semester-section">
    <div class="smt-title">Semester {{ $sem->nomor_semester }} — {{ $sem->label_semester }}</div>
    <table>
      <thead>
        <tr>
          <th style="width:5%;">No</th>
          <th style="width:10%;">Kode MK</th>
          <th style="width:22%;">Nama Mata Kuliah</th>
          <th style="width:5%;">SKS</th>
          <th style="width:10%;">Jenis</th>
          <th style="width:22%;">MK/Kondisi Prasyarat</th>
          <th style="width:10%;">Min. Nilai/SKS</th>
          <th style="width:8%;">Grup Logika</th>
          <th style="width:8%;">Tipe Logika</th>
        </tr>
      </thead>
      <tbody>
        @php $no = 1; @endphp
        @foreach($mkInSmt as $kmk)
          @php
            $rules = $kmk->prasyarats;
            // Group by grup_logika to determine AND/OR relationships
            $grpCounts = $rules->groupBy('grup_logika')->map->count();
          @endphp
          @if($rules->count() === 0)
            <tr class="no-prereq">
              <td>{{ $no++ }}</td>
              <td>{{ $kmk->mataKuliah->mk_kode ?? '-' }}</td>
              <td>{{ $kmk->mataKuliah->mk_nama ?? '-' }}</td>
              <td>{{ $kmk->sks_override ?? $kmk->mataKuliah->mk_sks_total ?? 0 }}</td>
              <td colspan="5">Tidak ada prasyarat</td>
            </tr>
          @else
            @foreach($rules as $i => $rule)
              <tr>
                @if($i === 0)
                  <td rowspan="{{ $rules->count() }}">{{ $no++ }}</td>
                  <td rowspan="{{ $rules->count() }}">{{ $kmk->mataKuliah->mk_kode ?? '-' }}</td>
                  <td rowspan="{{ $rules->count() }}">{{ $kmk->mataKuliah->mk_nama ?? '-' }}</td>
                  <td rowspan="{{ $rules->count() }}">{{ $kmk->sks_override ?? $kmk->mataKuliah->mk_sks_total ?? 0 }}</td>
                @endif
                <td>
                  @if($rule->jenis_prasyarat === 'PASS')
                    <span class="badge-pass">PASS</span>
                  @elseif($rule->jenis_prasyarat === 'TAKEN')
                    <span class="badge-taken">TAKEN</span>
                  @elseif($rule->jenis_prasyarat === 'COREQ')
                    <span class="badge-coreq">COREQ</span>
                  @else
                    <span class="badge-credits">CREDITS</span>
                  @endif
                </td>
                <td>
                  @if($rule->jenis_prasyarat === 'CREDITS')
                    SKS Kumulatif {{ $rule->sks_kumulatif_tipe ?? 'LULUS' }}
                  @else
                    {{ optional(optional($rule->prasyaratKurikulumMataKuliah)->mataKuliah)->mk_nama ?? '-' }}
                  @endif
                </td>
                <td>
                  @if($rule->jenis_prasyarat === 'PASS')
                    Nilai ≥ {{ $rule->nilai_min ?? 'C' }}
                  @elseif($rule->jenis_prasyarat === 'CREDITS')
                    ≥ {{ $rule->sks_kumulatif_min ?? 0 }} SKS
                  @else
                    —
                  @endif
                </td>
                <td style="font-family:monospace;font-size:9px;">{{ $rule->grup_logika ?? '-' }}</td>
                <td>
                  @php $grpKey = $rule->grup_logika ?? 'null'; @endphp
                  @if($grpCounts[$grpKey] ?? 0 > 1)
                    <span class="logic-or">OR dalam grup</span>
                  @else
                    <span class="logic-and">AND</span>
                  @endif
                </td>
              </tr>
            @endforeach
          @endif
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
@endforeach

<div class="footer">
  Dokumen ini dicetak secara otomatis dari Sistem Informasi Akademik OBE.<br>
  Berlaku untuk Kurikulum {{ $kurikulum->kurKode }} — {{ $kurikulum->kurNama }}.
</div>
</body>
</html>
