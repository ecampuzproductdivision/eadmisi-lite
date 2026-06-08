<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matriks CPL - MK - {{ $kurikulum->kurNama }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px double #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: normal;
        }
        .meta-info {
            width: 100%;
            margin-bottom: 20px;
        }
        .meta-info td {
            padding: 4px 0;
            vertical-align: top;
        }
        .meta-info td.label {
            font-weight: bold;
            width: 150px;
        }
        .meta-info td.colon {
            width: 15px;
            text-align: center;
        }
        table.matrix-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table.matrix-table th, table.matrix-table td {
            border: 1px solid #666;
            padding: 8px 6px;
            text-align: center;
        }
        table.matrix-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        table.matrix-table td.left {
            text-align: left;
        }
        .legend {
            margin-top: 30px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            font-size: 11px;
        }
        .legend h4 {
            margin: 5px 0;
        }
        .legend ul {
            margin: 5px 0;
            padding-left: 20px;
        }
        .footer-print {
            position: fixed;
            bottom: 10px;
            right: 10px;
            font-size: 9px;
            color: #777;
        }
        @media print {
            body {
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 15px; font-weight: bold; background: #333; color: #fff; border: none; cursor: pointer; border-radius: 4px;">
            Cetak / Simpan PDF
        </button>
    </div>

    <div class="header">
        <h2>Matriks Capaian Pembelajaran Lulusan (CPL) - Mata Kuliah</h2>
        <h3>Kurikulum Pendidikan Tinggi - SIAKAD OBE</h3>
    </div>

    <table class="meta-info">
        <tr>
            <td class="label">Program Studi</td>
            <td class="colon">:</td>
            <td>{{ $kurikulum->programStudi->prodiNamaResmi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Kurikulum</td>
            <td class="colon">:</td>
            <td>{{ $kurikulum->kurNama }} ({{ $kurikulum->kurKode }})</td>
        </tr>
        <tr>
            <td class="label">Tahun Berlaku</td>
            <td class="colon">:</td>
            <td>{{ $kurikulum->kurTahunMulai }} s/d {{ $kurikulum->kurTahunSelesai ?: 'Sekarang' }}</td>
        </tr>
    </table>

    <table class="matrix-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="80">Kode MK</th>
                <th>Nama Mata Kuliah</th>
                <th width="40">SKS</th>
                @foreach($cpls as $cpl)
                    <th width="35" title="{{ $cpl->deskripsi }}">{{ $cpl->kode_cpl }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($kurikulumMataKuliahs as $idx => $kmk)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>{{ $kmk->mataKuliah->mk_kode ?? '-' }}</td>
                    <td class="left">{{ $kmk->mataKuliah->mk_nama ?? '-' }}</td>
                    <td>{{ $kmk->sks_override ?? ($kmk->mataKuliah->mk_sks_total ?? 0) }}</td>
                    @foreach($cpls as $cpl)
                        @php
                            $val = $mkMappings->get($cpl->id_cpl)->get($kmk->id) ?? '';
                            $symbol = '';
                            if ($val === 'Tinggi') $symbol = 'H';
                            elseif ($val === 'Sedang') $symbol = 'M';
                            elseif ($val === 'Rendah') $symbol = 'L';
                        @endphp
                        <td><strong>{{ $symbol }}</strong></td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="legend">
        <h4>Keterangan Simbol Kontribusi CPL - MK:</h4>
        <ul>
            <li><strong>H (High / Tinggi)</strong> : Mata kuliah memberikan kontribusi yang sangat tinggi terhadap pencapaian CPL.</li>
            <li><strong>M (Medium / Sedang)</strong> : Mata kuliah memberikan kontribusi yang sedang terhadap pencapaian CPL.</li>
            <li><strong>L (Low / Rendah)</strong> : Mata kuliah memberikan kontribusi dasar/pendukung terhadap pencapaian CPL.</li>
        </ul>
    </div>

    <div class="footer-print">
        Dicetak otomatis oleh SIAKAD OBE pada: {{ now()->format('d-m-Y H:i') }}
    </div>

</body>
</html>
