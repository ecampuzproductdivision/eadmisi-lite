<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RPS_{{ $rps->kurikulumMataKuliah->mataKuliah->mk_kode }}_{{ Str::slug($rps->kurikulumMataKuliah->mataKuliah->mk_nama) }}</title>
  
  <!-- Tabler Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  
  <style>
    /* Reset & Fonts */
    body {
      font-family: "Times New Roman", Times, serif;
      font-size: 11pt;
      line-height: 1.4;
      color: #000;
      background: #fff;
      margin: 0;
      padding: 20px;
    }

    /* Print Page Setup */
    @page {
      size: A4;
      margin: 15mm 15mm 20mm 15mm;
    }

    /* Kop Surat / Header Dokumen */
    .kop-tabel {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    .kop-tabel td {
      border: 2px solid #000;
      padding: 10px;
      text-align: center;
      vertical-align: middle;
    }
    .kop-title {
      font-size: 14pt;
      font-weight: bold;
      text-transform: uppercase;
    }
    .kop-subtitle {
      font-size: 12pt;
      font-weight: bold;
      text-transform: uppercase;
      margin-top: 5px;
    }

    /* Section Tables & Blocks */
    h3.section-title {
      font-size: 12pt;
      font-weight: bold;
      text-transform: uppercase;
      margin-top: 25px;
      margin-bottom: 10px;
      border-bottom: 2px solid #000;
      padding-bottom: 3px;
      page-break-after: avoid;
    }

    .tabel-data {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }
    .tabel-data th, .tabel-data td {
      border: 1px solid #000;
      padding: 6px 10px;
      text-align: left;
      vertical-align: top;
    }
    .tabel-data th {
      background-color: #f2f2f2;
      font-weight: bold;
      text-align: center;
    }

    /* Identitas MK table specific styles */
    .tabel-identitas {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }
    .tabel-identitas td {
      border: 1px solid #000;
      padding: 6px 10px;
      vertical-align: top;
    }
    .label-col {
      font-weight: bold;
      width: 25%;
      background-color: #f9f9f9;
    }

    /* Signature Section */
    .signature-container {
      width: 100%;
      margin-top: 40px;
      page-break-inside: avoid;
    }
    .signature-table {
      width: 100%;
      border-collapse: collapse;
    }
    .signature-table td {
      width: 50%;
      text-align: center;
      vertical-align: top;
      border: none;
      padding: 10px;
    }
    .signature-space {
      height: 70px;
    }

    /* Print utility helpers */
    .no-print-bar {
      background: #f8f9fa;
      padding: 15px 20px;
      border-bottom: 1px solid #ddd;
      margin: -20px -20px 20px -20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .btn-print {
      background-color: #1e293b;
      color: #fff;
      border: none;
      padding: 8px 16px;
      font-size: 10pt;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .btn-print:hover {
      background-color: #0f172a;
    }

    /* Page breaks and optimizations */
    .keep-together {
      page-break-inside: avoid;
    }

    /* Hide print header in physical print */
    @media print {
      .no-print-bar {
        display: none !important;
      }
      body {
        padding: 0;
      }
    }
  </style>
</head>
<body>

  <!-- Top Action Bar (Hidden when printing) -->
  <div class="no-print-bar">
    <div>
      <strong style="font-family: sans-serif; font-size: 12pt; color: #333;">Dokumen Rencana Pembelajaran Semester (RPS)</strong>
    </div>
    <button class="btn-print" onclick="window.print()">
      <i class="ti ti-printer"></i> Cetak Dokumen (PDF/Kertas)
    </button>
  </div>

  <!-- Header Kop Dokumen (Standard BAN-PT / Institution Format) -->
  <table class="kop-tabel">
    <tr>
      <td width="20%" rowspan="2">
        <div style="font-weight: bold; font-size: 16pt; font-family: sans-serif; color: #333;">
          <i class="ti ti-school" style="font-size: 40pt; display: block; margin-bottom: 5px;"></i>
          UNIVERSITAS
        </div>
      </td>
      <td width="55%">
        <div class="kop-title">Rencana Pembelajaran Semester (RPS)</div>
        <div style="font-size: 10pt; font-style: italic; margin-top: 3px;">Outcome-Based Education (OBE) Curriculum</div>
      </td>
      <td width="25%" style="text-align: left; font-size: 9pt; font-family: Arial, sans-serif; line-height: 1.3;">
        <div><strong>No. Dokumen:</strong> RPS-OBE-{{ $rps->kurikulumMataKuliah->mataKuliah->mk_kode }}</div>
        <div><strong>Edisi / Revisi:</strong> 1 / {{ $rps->versi }}</div>
        <div><strong>Tgl Berlaku:</strong> {{ $rps->tanggal_dipublikasi ? $rps->tanggal_dipublikasi->format('d-m-Y') : now()->format('d-m-Y') }}</div>
        <div><strong>Halaman:</strong> 1 dari n</div>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="font-weight: bold; font-size: 11pt; text-transform: uppercase;">
        Program Studi {{ $rps->kurikulumMataKuliah->kurikulum->programStudi->prodiNamaResmi }} <br>
        Fakultas Teknologi & Desain
      </td>
    </tr>
  </table>

  <!-- SECTION 1: IDENTITAS MATA KULIAH -->
  <h3 class="section-title">I. Identitas Mata Kuliah</h3>
  <table class="tabel-identitas">
    <tr>
      <td class="label-col">Nama Mata Kuliah</td>
      <td>{{ $rps->kurikulumMataKuliah->mataKuliah->mk_nama }}</td>
      <td class="label-col">SKS (Teori/Praktikum)</td>
      <td>{{ $rps->kurikulumMataKuliah->mataKuliah->sks_total }} SKS (T:{{ $rps->kurikulumMataKuliah->mataKuliah->sks_teori ?? 0 }} / P:{{ $rps->kurikulumMataKuliah->mataKuliah->sks_praktikum ?? 0 }})</td>
    </tr>
    <tr>
      <td class="label-col">Kode Mata Kuliah</td>
      <td>{{ $rps->kurikulumMataKuliah->mataKuliah->mk_kode }}</td>
      <td class="label-col">Semester / Tahun Ajaran</td>
      <td>Semester {{ $rps->kurikulumMataKuliah->semester_anjuran }} / {{ $rps->tahunAkademik->nama_ta }}</td>
    </tr>
    <tr>
      <td class="label-col">Kurikulum Acuan</td>
      <td>{{ $rps->kurikulumMataKuliah->kurikulum->kurNama }}</td>
      <td class="label-col">Sifat Mata Kuliah</td>
      <td>{{ $rps->kurikulumMataKuliah->is_wajib ? 'Wajib Program Studi' : 'Pilihan / Peminatan' }}</td>
    </tr>
    <tr>
      <td class="label-col">Dosen Koordinator</td>
      <td>{{ $rps->dosenKoordinator->nama_lengkap }} (NIDN: {{ $rps->dosenKoordinator->nidn ?? '-' }})</td>
      <td class="label-col">Tim Dosen Pengampu</td>
      <td>
        @php
          $tim = $rps->rpsDosens->pluck('dosen.nama_lengkap')->toArray();
        @endphp
        {{ empty($tim) ? 'Hanya Dosen Koordinator' : implode(', ', $tim) }}
      </td>
    </tr>
  </table>

  <!-- SECTION 2: DESKRIPSI & MANFAAT -->
  <h3 class="section-title">II. Deskripsi & Manfaat Mata Kuliah</h3>
  <div style="text-align: justify; margin-bottom: 15px;">
    <strong>Deskripsi Mata Kuliah:</strong> <br>
    {{ $rps->deskripsi_mk ?? 'Belum diisi.' }}
  </div>
  @if($rps->manfaat_mk)
    <div style="text-align: justify; margin-bottom: 20px;">
      <strong>Manfaat Pembelajaran:</strong> <br>
      {{ $rps->manfaat_mk }}
    </div>
  @endif

  <!-- SECTION 3: CAPAIAN PEMBELAJARAN (CPL & CPMK) -->
  <h3 class="section-title">III. Capaian Pembelajaran (Outcome)</h3>
  
  <h4 style="font-size: 11pt; font-weight: bold; margin: 10px 0 5px 0;">1. Capaian Pembelajaran Lulusan (CPL) yang dibebankan pada MK:</h4>
  <table class="tabel-data">
    <thead>
      <tr>
        <th width="15%">Kode CPL</th>
        <th>Deskripsi Capaian Pembelajaran Lulusan (CPL)</th>
        <th width="20%">Aspek/Kategori</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rps->rpsCpls as $rcpl)
        <tr>
          <td style="font-weight: bold; font-family: monospace; text-align: center;">{{ $rcpl->cpl->kode_cpl }}</td>
          <td>{{ $rcpl->cpl->deskripsi }}</td>
          <td style="text-align: center;">{{ $rcpl->cpl->kategori }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="3" style="text-align: center; font-style: italic;">Tidak ada CPL dikaitkan.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <h4 style="font-size: 11pt; font-weight: bold; margin: 15px 0 5px 0;">2. Capaian Pembelajaran Mata Kuliah (CPMK) & Indikator Capaian:</h4>
  <table class="tabel-data">
    <thead>
      <tr>
        <th width="15%">Kode CPMK</th>
        <th width="45%">Deskripsi Capaian Pembelajaran Mata Kuliah (CPMK)</th>
        <th>Indikator Keberhasilan / Capaian</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rps->rpsCpmks as $rcpmk)
        <tr>
          <td style="font-weight: bold; font-family: monospace; text-align: center;">{{ $rcpmk->cpmk->kode_cpmk }}</td>
          <td>{{ $rcpmk->cpmk->deskripsi }}</td>
          <td>{{ $rcpmk->indikator_capaian ?? 'Indikator belum didefinisikan.' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="3" style="text-align: center; font-style: italic;">Tidak ada CPMK dikaitkan.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <!-- SECTION 4: MATRIKS RENCANA PEMBELAJARAN (16 PERTEMUAN) -->
  <h3 class="section-title">IV. Matriks Rencana Pembelajaran (16 Pertemuan)</h3>
  <table class="tabel-data" style="font-size: 9.5pt;">
    <thead>
      <tr>
        <th width="5%">Mng Ke</th>
        <th width="20%">Kemampuan Akhir / Sub-CPMK / CPMK</th>
        <th width="25%">Bahan Kajian & Rincian Materi Pokok</th>
        <th width="15%">Metode Pembelajaran & Media</th>
        <th width="20%">Pengalaman Belajar / Aktivitas Mahasiswa</th>
        <th width="7%">Waktu (Mnt)</th>
        <th width="8%">Bobot (%)</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rps->pertemuan as $p)
        <tr>
          <td style="text-align: center; font-weight: bold;">{{ $p->nomor_pertemuan }}</td>
          <td>
            <strong>{{ $p->topik }}</strong>
            @if($p->cpmk)
              <div style="font-size: 8.5pt; font-family: monospace; color: #333; margin-top: 3px;">
                CPMK: {{ $p->cpmk->kode_cpmk }}
              </div>
            @endif
            @if($p->subCpmk)
              <div style="font-size: 8.5pt; font-family: monospace; color: #555;">
                Sub-CPMK: {{ $p->subCpmk->kode_sub_cpmk }}
              </div>
            @endif
          </td>
          <td>
            {{ $p->bahan_kajian ?? '-' }}
            @if($p->sub_topik)
              <div style="font-size: 8.5pt; font-style: italic; color: #444; margin-top: 3px;">
                Sub materi: {{ $p->sub_topik }}
              </div>
            @endif
          </td>
          <td>
            @php
              // Translate codes to readable short text
              $methodsMap = [
                'CER' => 'Ceramah', 'DIS' => 'Diskusi', 'PBL' => 'PBL',
                'CBL' => 'Case Study', 'PJT' => 'Proyek', 'PRAK' => 'Praktikum',
                'FLIPPED' => 'Flipped', 'SELF' => 'Mandiri'
              ];
              $arr = [];
              foreach($p->metode_array as $m) {
                  $arr[] = $methodsMap[$m] ?? $m;
              }
              $metodeRead = implode(', ', $arr);
            @endphp
            {{ $metodeRead ?: $p->jenis_pertemuan }}
            @if($p->media_pembelajaran)
              <div style="font-size: 8.5pt; color: #555; margin-top: 3px;">
                Media: {{ $p->media_pembelajaran }}
              </div>
            @endif
          </td>
          <td>{{ $p->aktivitas_mahasiswa ?? '-' }}</td>
          <td style="text-align: center;">{{ $p->durasi_menit }}</td>
          <td style="text-align: center;">{{ $p->bobot_pertemuan ? $p->bobot_pertemuan . '%' : '-' }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <!-- SECTION 5: RENCANA EVALUASI & PENILAIAN -->
  <h3 class="section-title">V. Rencana Penilaian & Kriteria Kelulusan</h3>
  <table class="tabel-data">
    <thead>
      <tr>
        <th width="20%">Komponen Penilaian</th>
        <th width="10%">Bobot</th>
        <th width="35%">Deskripsi Tugas / Ujian</th>
        <th>Kriteria Penilaian / Rubrik Asesmen</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rps->penilaian as $pen)
        <tr>
          <td style="font-weight: bold;">{{ $pen->komponenPenilaian->nama_komponen }}</td>
          <td style="text-align: center; font-weight: bold;">{{ $pen->komponenPenilaian->bobot }}%</td>
          <td>
            {{ $pen->deskripsi_tugas ?? 'Deskripsi tugas belum diisi.' }}
            @if($pen->bentuk_soal)
              <div style="font-size: 9pt; font-style: italic; color: #333; margin-top: 3px;">Format: {{ $pen->bentuk_soal }}</div>
            @endif
          </td>
          <td>{{ $pen->kriteria_penilaian ?? 'Rubrik kriteria belum dilengkapi.' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="4" style="text-align: center; font-style: italic;">Komponen penilaian belum diatur.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <!-- SECTION 6: DAFTAR REFERENSI -->
  <h3 class="section-title">VI. Daftar Referensi / Pustaka Rujukan</h3>
  <ol style="margin-left: 20px; line-height: 1.5;">
    @forelse($rps->referensi as $ref)
      <li style="margin-bottom: 5px;">
        <strong>[{{ $ref->jenis }}]</strong> 
        {{ $ref->penulis }}. 
        @if($ref->tahun_terbit) ({{ $ref->tahun_terbit }}). @endif
        <em>{{ $ref->judul }}</em>. 
        @if($ref->penerbit) {{ $ref->penerbit }}. @endif
        @if($ref->edisi) Edisi/Vol: {{ $ref->edisi }}. @endif
        @if($ref->isbn_issn) ISBN: {{ $ref->isbn_issn }}. @endif
        @if($ref->url) <a href="{{ $ref->url }}" target="_blank" style="color: #000; text-decoration: none; font-size: 9.5pt; font-family: monospace;">[Link]</a> @endif
      </li>
    @empty
      <li style="list-style-type: none; font-style: italic; color: red;">Belum ada referensi yang ditambahkan.</li>
    @endforelse
  </ol>

  <!-- SECTION 7: MEDIA & CATATAN KHUSUS -->
  @if($rps->media_pembelajaran || $rps->catatan_khusus)
    <h3 class="section-title">VII. Catatan Khusus & Media Pembelajaran</h3>
    @if($rps->media_pembelajaran)
      <div style="margin-bottom: 10px;">
        <strong>Media & Sarana Pembelajaran:</strong> <br>
        {{ $rps->media_pembelajaran }}
      </div>
    @endif
    @if($rps->catatan_khusus)
      <div>
        <strong>Aturan / Kebijakan Khusus Kelas:</strong> <br>
        {{ $rps->catatan_khusus }}
      </div>
    @endif
  @endif

  <!-- SIGNATURES (OTORISASI DOKUMEN) -->
  <div class="signature-container">
    <table class="signature-table">
      <tr>
        <td>
          <div>Mengetahui/Menyetujui,</div>
          <div style="font-weight: bold; text-transform: uppercase; margin-top: 2px;">Ketua Program Studi</div>
          <div class="signature-space"></div>
          <div style="text-decoration: underline; font-weight: bold;">
            {{ $rps->approver ? $rps->approver->name : '(Nama Kaprodi / TTD)' }}
          </div>
          <div style="font-size: 9pt; color: #333;">NIDN / NIP: .............................</div>
        </td>
        <td>
          <div>Yogyakarta, {{ $rps->tanggal_disetujui ? $rps->tanggal_disetujui->format('d F Y') : now()->format('d F Y') }}</div>
          <div style="font-weight: bold; text-transform: uppercase; margin-top: 2px;">Dosen Koordinator Pengampu</div>
          <div class="signature-space"></div>
          <div style="text-decoration: underline; font-weight: bold;">
            {{ $rps->dosenKoordinator->nama_lengkap }}
          </div>
          <div style="font-size: 9pt; color: #333;">NIDN: {{ $rps->dosenKoordinator->nidn ?? '.............................' }}</div>
        </td>
      </tr>
    </table>
  </div>

</body>
</html>
