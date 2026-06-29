<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\RegistrationPath;
use App\Models\KategoriJalur;
use App\Models\ProgramStudi;
use App\Models\PaketSoal;
use App\Models\TemplateBerkas;
use App\Models\SyaratDokumen;
use App\Models\Periode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JalurPendaftaranSeeder extends Seeder
{
    /**
     * Seed admission paths (Jalur Pendaftaran) with dynamic relational mappings.
     *
     * Prerequisites (must run BEFORE this seeder):
     *   - KategoriJalurSeeder
     *   - ProgramStudiSeeder
     *   - SyaratBerkasSeeder  (TemplateBerkas + SyaratDokumen)
     *   - PaketSoalSeeder     (PaketSoal + SoalUjian)
     *   - FormPendaftaranSeeder (Form + FormField)
     *   - PeriodeSeeder
     */
    public function run(): void
    {
        // Force delete all (termasuk soft deleted)
        RegistrationPath::withTrashed()->forceDelete();

        // ── Dynamic lookups (avoid hardcoded IDs) ──
        $kategori = KategoriJalur::pluck('id', 'nama');

        $periode = Periode::where('status_aktif', true)->first();
        $periodeId = $periode?->id;

        $programStudiIds = ProgramStudi::pluck('id')->toArray();

        // ── Lookup Forms by 'nama' ──
        $formReguler  = Form::where('nama', 'Form Pendaftaran Reguler')->first();
        $formPrestasi = Form::where('nama', 'Form Pendaftaran Jalur Prestasi')->first();

        // ── Lookup Template Berkas by 'nama_template' ──
        $templateReguler  = TemplateBerkas::where('nama_template', 'Dokumen Registrasi Reguler')->first();
        $templateKip      = TemplateBerkas::where('nama_template', 'Dokumen Beasiswa KIP Kuliah')->first();
        $templatePrestasi = TemplateBerkas::where('nama_template', 'Dokumen Prestasi Olahraga & Seni')->first();

        // ── Lookup Paket Soal by 'nama_paket' ──
        $paketKesehatan = PaketSoal::where('nama_paket', 'like', '%Dasar Kesehatan%')->first();
        $paketTpa       = PaketSoal::where('nama_paket', 'Try Out TPA Saintek')->first();
        $paketWawasan   = PaketSoal::where('nama_paket', 'Try Out Wawasan Kebangsaan')->first();

        // ── Lookup Syarat Dokumen by 'nama_dokumen' ──
        $syaratKtp          = SyaratDokumen::where('nama_dokumen', 'Ijazah / Surat Keterangan Lulus')->orWhere('nama_dokumen', 'Ijazah / SKL')->first();
        $syaratIjazah       = SyaratDokumen::where('nama_dokumen', 'Ijazah / Surat Keterangan Lulus')->orWhere('nama_dokumen', 'Ijazah / SKL')->first();
        $syaratKk           = SyaratDokumen::where('nama_dokumen', 'Kartu Keluarga')->first();
        $syaratAkta         = SyaratDokumen::where('nama_dokumen', 'Akta Kelahiran')->first();
        $syaratKip          = SyaratDokumen::where('nama_dokumen', 'Kartu Indonesia Pintar (KIP)')->first();
        $syaratSktm         = SyaratDokumen::where('nama_dokumen', 'Surat Keterangan Tidak Mampu (SKTM)')->first();
        $syaratSertifikat   = SyaratDokumen::where('nama_dokumen', 'Sertifikat Prestasi (jika ada)')->orWhere('nama_dokumen', 'Sertifikat/Piagam Prestasi Asli')->first();

        // Collect IDs per template for easier lookup
        $syaratIdsReguler = [];
        $syaratIdsKip     = [];
        $syaratIdsPrestasi = [];

        if ($templateReguler) {
            $syaratIdsReguler = SyaratDokumen::where('template_berkas_id', $templateReguler->id)
                ->whereIn('nama_dokumen', [
                    'Ijazah / Surat Keterangan Lulus',
                    'Kartu Keluarga',
                    'Akta Kelahiran',
                    'Foto Formal 3x4',
                ])->pluck('id')->toArray();
        }

        if ($templateKip) {
            $syaratIdsKip = SyaratDokumen::where('template_berkas_id', $templateKip->id)
                ->whereIn('nama_dokumen', [
                    'Ijazah / SKL',
                    'Kartu Keluarga',
                    'Kartu Indonesia Pintar (KIP)',
                    'Surat Keterangan Tidak Mampu (SKTM)',
                ])->pluck('id')->toArray();
        }

        if ($templatePrestasi) {
            $syaratIdsPrestasi = SyaratDokumen::where('template_berkas_id', $templatePrestasi->id)
                ->whereIn('nama_dokumen', [
                    'Ijazah / SKL',
                    'Sertifikat/Piagam Prestasi Asli',
                    'Surat Rekomendasi Pelatih/Pembina',
                ])->pluck('id')->toArray();
        }

        // ─────────────────────────────────────────────
        //  PATH 1: Jalur KIP Kuliah
        // ─────────────────────────────────────────────
        $kipPath = RegistrationPath::create([
            'code'                => 'KIP_KULIAH',
            'name'                => 'Jalur KIP Kuliah',
            'description'         => 'Jalur pendaftaran khusus bagi penerima Kartu Indonesia Pintar (KIP) Kuliah. Biaya ringan dengan kuota besar, tanpa ujian dan tanpa wawancara.',
            'kategori_jalur_id'   => $kategori['Seleksi Kerjasama'] ?? $kategori->first(),
            'form_pendaftaran_id' => $formReguler?->id,
            'periode_id'          => $periodeId,
            'registration_start'  => '2026-02-01',
            'registration_end'    => '2026-08-31',
            'fee'                 => 100000,
            'color'               => 'danger',
            'quota'               => 500,
            'jumlah_pilihan_prodi'=> 2,
            'is_active'           => true,
            'gunakan_berkas'      => true,
            'template_berkas_id'  => $templateKip?->id,
            'gunakan_ujian'       => false,
            'paket_soal_id'       => null,
            'gunakan_wawancara'   => false,
            'metode_pengumuman'   => 'ditahan',
        ]);

        // Attach 2 program studi
        if (!empty($programStudiIds)) {
            $kipPath->programStudis()->sync(array_slice($programStudiIds, 0, 2));
        }

        $this->command->info("Path 1 '{$kipPath->name}' (ID: {$kipPath->id}) — template_berkas_id: " . ($templateKip?->id ?? 'NULL'));
        if (!empty($syaratIdsKip)) {
            $this->command->info("  -> Syarat dokumen attached: " . implode(', ', SyaratDokumen::whereIn('id', $syaratIdsKip)->pluck('nama_dokumen')->toArray()));
        }

        // ─────────────────────────────────────────────
        //  PATH 2: Jalur Mandiri Reguler
        // ─────────────────────────────────────────────
        $mandiriPath = RegistrationPath::create([
            'code'                => 'MANDIRI',
            'name'                => 'Jalur Mandiri Reguler',
            'description'         => 'Jalur pendaftaran mandiri reguler dengan tes ujian online. Cocok bagi calon mahasiswa yang ingin mengikuti seleksi berbasis tes.',
            'kategori_jalur_id'   => $kategori['Seleksi Mandiri'] ?? $kategori->skip(1)->first(),
            'form_pendaftaran_id' => $formReguler?->id,
            'periode_id'          => $periodeId,
            'registration_start'  => '2026-05-01',
            'registration_end'    => '2026-07-31',
            'fee'                 => 500000,
            'color'               => 'warning',
            'quota'               => 300,
            'jumlah_pilihan_prodi'=> 2,
            'is_active'           => true,
            'gunakan_berkas'      => true,
            'template_berkas_id'  => $templateReguler?->id,
            'gunakan_ujian'       => true,
            'paket_soal_id'       => $paketKesehatan?->id ?? $paketTpa?->id,
            'gunakan_wawancara'   => false,
            'metode_pengumuman'   => 'langsung',
        ]);

        if (!empty($programStudiIds)) {
            $mandiriPath->programStudis()->sync(array_slice($programStudiIds, 0, 2));
        }

        $this->command->info("Path 2 '{$mandiriPath->name}' (ID: {$mandiriPath->id}) — paket_soal_id: " . ($paketKesehatan?->id ?? $paketTpa?->id ?? 'NULL') . ", template_berkas_id: " . ($templateReguler?->id ?? 'NULL'));
        if (!empty($syaratIdsReguler)) {
            $this->command->info("  -> Syarat dokumen attached: " . implode(', ', SyaratDokumen::whereIn('id', $syaratIdsReguler)->pluck('nama_dokumen')->toArray()));
        }

        // ─────────────────────────────────────────────
        //  PATH 3: Jalur Prestasi Akademik
        // ─────────────────────────────────────────────
        $prestasiPath = RegistrationPath::create([
            'code'                => 'PRESTASI',
            'name'                => 'Jalur Prestasi Akademik',
            'description'         => 'Jalur pendaftaran bagi calon mahasiswa dengan prestasi akademik unggul, tanpa tes ujian namun melalui proses wawancara.',
            'kategori_jalur_id'   => $kategori['Seleksi Prestasi'] ?? $kategori->skip(2)->first(),
            'form_pendaftaran_id' => $formPrestasi?->id,
            'periode_id'          => $periodeId,
            'registration_start'  => '2026-01-15',
            'registration_end'    => '2026-06-30',
            'fee'                 => 200000,
            'color'               => 'info',
            'quota'               => 200,
            'jumlah_pilihan_prodi'=> 1,
            'is_active'           => true,
            'gunakan_berkas'      => true,
            'template_berkas_id'  => $templatePrestasi?->id,
            'gunakan_ujian'       => false,
            'paket_soal_id'       => null,
            'gunakan_wawancara'   => true,
            'metode_pengumuman'   => 'ditahan',
        ]);

        if (!empty($programStudiIds)) {
            $prestasiPath->programStudis()->sync(array_slice($programStudiIds, 0, 1));
        }

        $this->command->info("Path 3 '{$prestasiPath->name}' (ID: {$prestasiPath->id}) — form_pendaftaran_id: " . ($formPrestasi?->id ?? 'NULL') . ", template_berkas_id: " . ($templatePrestasi?->id ?? 'NULL'));
        if (!empty($syaratIdsPrestasi)) {
            $this->command->info("  -> Syarat dokumen attached: " . implode(', ', SyaratDokumen::whereIn('id', $syaratIdsPrestasi)->pluck('nama_dokumen')->toArray()));
        }

        // ─────────────────────────────────────────────
        //  Summary
        // ─────────────────────────────────────────────
        $this->command->info('========================================');
        $this->command->info('JalurPendaftaranSeeder COMPLETED!');
        $this->command->info("========================================");
        $this->command->info("Jalur created: 3 (KIP Kuliah, Mandiri Reguler, Prestasi Akademik)");
        $this->command->info("Forms linked: " . ($formReguler?->nama ?? 'N/A') . ", " . ($formPrestasi?->nama ?? 'N/A'));
        $this->command->info("Templates linked: " . ($templateKip?->nama_template ?? 'N/A') . ", " . ($templateReguler?->nama_template ?? 'N/A') . ", " . ($templatePrestasi?->nama_template ?? 'N/A'));
        $this->command->info("Paket Soal linked: " . ($paketKesehatan?->nama_paket ?? ($paketTpa?->nama_paket ?? 'N/A')));
        $this->command->info('========================================');
    }
}