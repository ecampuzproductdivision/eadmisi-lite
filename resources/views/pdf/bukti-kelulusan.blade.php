<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Kelulusan PMB - {{ $registration->no_pendaftaran ?? $registration->nama_lengkap }}</title>
    <!-- Tabler / Font / Icons (Optional fallback icons for screen) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 18mm 15mm 18mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #222222;
            margin: 0;
            padding: 0;
            background-color: #f1f3f5;
        }

        /* Top Action Bar for Screen Preview (Hidden on Print) */
        .preview-toolbar {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            background-color: #1e293b;
            color: #ffffff;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
        }

        .preview-toolbar .title-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 14px;
        }

        .preview-toolbar .title-info span {
            background-color: #0284c7;
            color: #fff;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .preview-toolbar .btn-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-preview {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-preview-primary {
            background-color: #16a34a;
            color: #ffffff;
        }

        .btn-preview-primary:hover {
            background-color: #15803d;
        }

        .btn-preview-outline {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-preview-outline:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .btn-preview-danger {
            background-color: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-preview-danger:hover {
            background-color: #dc2626;
            color: #ffffff;
        }

        /* Printable Paper Container on Screen */
        .paper-container {
            width: 210mm;
            min-height: 297mm;
            margin: 25px auto 40px auto;
            background-color: #ffffff;
            padding: 20mm 18mm;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            position: relative;
        }

        /* Document Styles */
        .header-kop {
            width: 100%;
            border-bottom: 3px double #111;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header-kop td {
            vertical-align: middle;
        }

        .logo-cell {
            width: 80px;
            text-align: center;
        }

        .logo-box {
            width: 70px;
            height: 70px;
            background-color: #0b5ed7;
            color: #ffffff;
            border-radius: 50%;
            font-weight: bold;
            font-size: 24pt;
            line-height: 70px;
            text-align: center;
            margin: 0 auto;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 15pt;
            font-weight: bold;
            color: #0d3c61;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-text h1 {
            margin: 2px 0;
            font-size: 17pt;
            font-weight: bold;
            color: #111;
            text-transform: uppercase;
        }

        .kop-text p {
            margin: 1px 0;
            font-size: 8.5pt;
            color: #555;
        }

        .title-section {
            text-align: center;
            margin-bottom: 22px;
        }

        .title-section h3 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .title-section .nomor-surat {
            font-size: 9.5pt;
            color: #444;
            margin-top: 3px;
        }

        .narasi-intro {
            text-align: justify;
            margin-bottom: 15px;
            font-size: 10.5pt;
        }

        .table-identity {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fcfcfc;
        }

        .table-identity td {
            padding: 7px 10px;
            font-size: 10.5pt;
            vertical-align: top;
            border-bottom: 1px solid #e9ecef;
        }

        .table-identity tr:last-child td {
            border-bottom: none;
        }

        .label-col {
            width: 32%;
            font-weight: bold;
            color: #333;
        }

        .separator-col {
            width: 3%;
            text-align: center;
        }

        .val-col {
            width: 65%;
            color: #111;
        }

        .badge-lulus {
            display: inline-block;
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 10pt;
        }

        .box-catatan {
            border: 1px solid #b6effb;
            background-color: #f8ffff;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }

        .box-catatan h4 {
            margin: 0 0 6px 0;
            font-size: 10.5pt;
            color: #055160;
            font-weight: bold;
        }

        .box-catatan ol {
            margin: 0;
            padding-left: 18px;
            font-size: 9.5pt;
            color: #333;
        }

        .box-catatan li {
            margin-bottom: 3px;
        }

        .legalitas-section {
            width: 100%;
            margin-top: 20px;
        }

        .legalitas-section td {
            vertical-align: top;
        }

        .qr-box {
            border: 1px dashed #ccc;
            padding: 8px;
            width: 90px;
            text-align: center;
            background-color: #fff;
            margin-top: 5px;
        }

        .qr-placeholder {
            width: 70px;
            height: 70px;
            margin: 0 auto;
            border: 2px solid #222;
            display: block;
            position: relative;
            background: 
                linear-gradient(90deg, #111 20%, transparent 20%, transparent 40%, #111 40%, #111 60%, transparent 60%, transparent 80%, #111 80%),
                linear-gradient(0deg, #111 20%, transparent 20%, transparent 40%, #111 40%, #111 60%, transparent 60%, transparent 80%, #111 80%);
            background-size: 14px 14px;
        }

        .qr-subtext {
            font-size: 7.5pt;
            color: #666;
            margin-top: 4px;
        }

        .ttd-box {
            text-align: right;
        }

        .ttd-box p {
            margin: 2px 0;
            font-size: 10pt;
        }

        .ttd-space {
            height: 55px;
        }

        .footer-note {
            position: absolute;
            bottom: 15mm;
            left: 18mm;
            right: 18mm;
            font-size: 8pt;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 5px;
            text-align: justify;
        }

        /* ── Media Print Overrides ── */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background-color: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .paper-container {
                width: 100% !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            .footer-note {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Bar (Print Preview Toolbar) -->
    <div class="preview-toolbar no-print">
        <div class="title-info">
            <i class="ti ti-printer fs-4"></i>
            <span>PRINT PREVIEW</span>
            Dokumen Bukti Kelulusan PMB ({{ $registration->no_pendaftaran ?? '-' }})
        </div>
        <div class="btn-group">
            <button onclick="window.print()" class="btn-preview btn-preview-primary">
                <i class="ti ti-printer"></i> Cetak / Simpan PDF
            </button>
            <a href="{{ request()->fullUrlWithQuery(['download' => 1]) }}" class="btn-preview btn-preview-outline">
                <i class="ti ti-download"></i> Unduh File PDF Direct
            </a>
            <button onclick="window.close()" class="btn-preview btn-preview-danger">
                <i class="ti ti-x"></i> Tutup
            </button>
        </div>
    </div>

    <!-- Paper Canvas Container -->
    <div class="paper-container">

        <!-- KOP SURAT -->
        <table class="header-kop">
            <tr>
                <td class="logo-cell">
                    <div class="logo-box">E</div>
                </td>
                <td class="kop-text">
                    <h2>Panitia Penerimaan Mahasiswa Baru (PMB)</h2>
                    <h1>E-ADMISI LITE UNIVERSITY</h1>
                    <p>Jl. Kampus Utama No. 1, Kota Akademika, Indonesia | Telp: (021) 555-0199</p>
                    <p>Website: https://pmb.eadmisi.ac.id | Email: pmb@eadmisi.ac.id</p>
                </td>
            </tr>
        </table>

        <!-- JUDUL DOKUMEN -->
        <div class="title-section">
            <h3>Surat Keterangan Lulus Seleksi PMB</h3>
            <div class="nomor-surat">Nomor: {{ $noSurat }}</div>
        </div>

        <!-- INTRO -->
        <div class="narasi-intro">
            Berdasarkan hasil rapat pleno Panitia Penerimaan Mahasiswa Baru (PMB) dan hasil evaluasi seleksi calon mahasiswa baru Periode Akademik <strong>{{ $registration->registrationPath?->periode?->name ?? date('Y') }}</strong>, bersama ini Panitia PMB menyatakan bahwa:
        </div>

        <!-- TABEL IDENTITAS -->
        <table class="table-identity">
            <tr>
                <td class="label-col">Nomor Pendaftaran</td>
                <td class="separator-col">:</td>
                <td class="val-col"><strong>{{ $registration->no_pendaftaran ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td class="label-col">Nama Lengkap</td>
                <td class="separator-col">:</td>
                <td class="val-col"><strong>{{ strtoupper($registration->nama_lengkap) }}</strong></td>
            </tr>
            <tr>
                <td class="label-col">NIK / NISN</td>
                <td class="separator-col">:</td>
                <td class="val-col">{{ $registration->nik ?? '-' }} {{ $registration->nisn ? ' / '.$registration->nisn : '' }}</td>
            </tr>
            <tr>
                <td class="label-col">Jalur Pendaftaran</td>
                <td class="separator-col">:</td>
                <td class="val-col">{{ $registration->registrationPath?->name ?? '-' }} ({{ $registration->registrationPath?->kategori?->nama ?? 'PMB' }})</td>
            </tr>
            <tr>
                <td class="label-col">Program Studi Diterima</td>
                <td class="separator-col">:</td>
                <td class="val-col"><strong style="color: #0b5ed7;">{{ $prodiDiterima }} {{ $jenjang ? '('.$jenjang.')' : '' }}</strong></td>
            </tr>
            @if($skorUjian)
            <tr>
                <td class="label-col">Skor Hasil Ujian CBT</td>
                <td class="separator-col">:</td>
                <td class="val-col"><strong>{{ $skorUjian }}</strong> / 100</td>
            </tr>
            @endif
            <tr>
                <td class="label-col">Status Hasil Seleksi</td>
                <td class="separator-col">:</td>
                <td class="val-col">
                    <span class="badge-lulus">DITERIMA / LULUS SELEKSI</span>
                </td>
            </tr>
        </table>

        <!-- CATATAN REGISTRASI ULANG -->
        <div class="box-catatan">
            <h4>Petunjuk & Instruksi Registrasi Ulang:</h4>
            <ol>
                <li>Calon Mahasiswa yang dinyatakan LULUS wajib melakukan <strong>Registrasi Ulang</strong> melalui Portal Calon Mahasiswa.</li>
                <li>Selesaikan pembayaran biaya registrasi ulang sesuai rincian pada menu <strong>Tagihan</strong>.</li>
                <li>Silakan lengkapi formulir bio-data mahasiswa baru untuk proses penerbitan NIM dan pelaporan ke PDDikti.</li>
                <li>Surat Keterangan Lulus ini merupakan bukti SAH kelulusan seleksi dan dapat dipergunakan sebagaimana mestinya.</li>
            </ol>
        </div>

        <!-- LEGALITAS & TTD -->
        <table class="legalitas-section">
            <tr>
                <td style="width: 50%;">
                    <div style="font-size: 9pt; color: #555;">Dokumen Digital Terverifikasi</div>
                    <div class="qr-box">
                        <div class="qr-placeholder"></div>
                        <div class="qr-subtext">Scan untuk Verifikasi</div>
                    </div>
                </td>
                <td style="width: 50%;">
                    <div class="ttd-box">
                        <p>Ditetapkan di : Kota Akademika</p>
                        <p>Pada Tanggal : <strong>{{ $tanggalCetak }}</strong></p>
                        <p style="margin-top: 10px; font-weight: bold;">Ketua Panitia PMB,</p>
                        <div class="ttd-space"></div>
                        <p style="font-weight: bold; text-decoration: underline;">Dr. Ir. H. Ahmad Fauzi, M.T.</p>
                        <p style="font-size: 8.5pt; color: #555;">NIP. 19780512 200501 1 002</p>
                    </div>
                </td>
            </tr>
        </table>

        <!-- FOOTER NOTE -->
        <div class="footer-note">
            * Dokumen ini diterbitkan secara elektronik oleh Sistem Informasi E-ADMISI LITE dan dianggap sah tanpa memerlukan tanda tangan basah.
        </div>

    </div>

    <!-- Script to Auto Trigger Chrome Print Preview Dialog on Load -->
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            // Auto open Chrome native print preview dialog after 400ms
            setTimeout(function() {
                window.print();
            }, 400);
        });
    </script>
</body>
</html>
