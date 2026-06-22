<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\RegistrationPath;
use App\Models\KategoriJalur;
use App\Models\ProgramStudi;
use App\Models\PaketSoal;
use App\Models\TemplateBerkas;
use App\Models\Periode;
use Illuminate\Database\Seeder;

class RegistrationPathSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama dulu, lalu buat 5 data baru
        RegistrationPath::query()->delete();

        $kategoriIds = KategoriJalur::pluck('id', 'nama');
        $periode = Periode::first();
        $periodeId = $periode ? $periode->id : null;

        // Ambil data relasi yang sudah ada (dari seeder sebelumnya)
        $programStudis = ProgramStudi::pluck('id')->toArray();
        $paketSoals = PaketSoal::pluck('id')->toArray();
        $templateBerkas = TemplateBerkas::pluck('id')->toArray();
        $forms = Form::pluck('id')->toArray();

        $paths = [
            // ── 1: SNBP ──
            [
                'code'                => 'SNBP',
                'name'                => 'Seleksi Nasional Berdasarkan Prestasi',
                'description'         => 'Jalur seleksi nasional berdasarkan prestasi akademik dan non-akademik siswa.',
                'kategori_jalur_id'   => $kategoriIds['Seleksi Nasional'] ?? 1,
                'form_pendaftaran_id' => $forms[0] ?? null,
                'periode_id'          => $periodeId,
                'registration_start'  => '2026-01-15',
                'registration_end'    => '2026-02-28',
                'fee'                 => 250000,
                'color'               => 'primary',
                'quota'               => 500,
                'jumlah_pilihan_prodi'=> 3,
                'is_active'           => true,
                'gunakan_berkas'      => true,
                'template_berkas_id'  => $templateBerkas[0] ?? null,
                'metode_pengumuman'   => 'langsung',
            ],
            // ── 2: SNBT ──
            [
                'code'                => 'SNBT',
                'name'                => 'Seleksi Nasional Berdasarkan Tes',
                'description'         => 'Jalur seleksi nasional berdasarkan hasil tes kemampuan akademik dengan ujian online.',
                'kategori_jalur_id'   => $kategoriIds['Seleksi Nasional'] ?? 1,
                'form_pendaftaran_id' => $forms[0] ?? null,
                'periode_id'          => $periodeId,
                'registration_start'  => '2026-03-01',
                'registration_end'    => '2026-04-30',
                'fee'                 => 300000,
                'color'               => 'success',
                'quota'               => 800,
                'jumlah_pilihan_prodi'=> 2,
                'is_active'           => true,
                'gunakan_ujian'       => true,
                'paket_soal_id'       => $paketSoals[0] ?? null,
                'gunakan_berkas'      => true,
                'template_berkas_id'  => $templateBerkas[0] ?? null,
                'metode_pengumuman'   => 'langsung',
            ],
            // ── 3: MANDIRI ──
            [
                'code'                => 'MANDIRI',
                'name'                => 'Jalur Mandiri Reguler',
                'description'         => 'Jalur pendaftaran mandiri reguler dengan tes ujian online dan wawancara.',
                'kategori_jalur_id'   => $kategoriIds['Seleksi Mandiri'] ?? 2,
                'form_pendaftaran_id' => $forms[0] ?? null,
                'periode_id'          => $periodeId,
                'registration_start'  => '2026-05-01',
                'registration_end'    => '2026-07-31',
                'fee'                 => 500000,
                'color'               => 'warning',
                'quota'               => 300,
                'jumlah_pilihan_prodi'=> 2,
                'is_active'           => true,
                'gunakan_ujian'       => true,
                'paket_soal_id'       => $paketSoals[1] ?? null,
                'gunakan_berkas'      => true,
                'template_berkas_id'  => $templateBerkas[0] ?? null,
                'gunakan_wawancara'   => true,
                'metode_pengumuman'   => 'ditahan',
            ],
            // ── 4: PRESTASI ──
            [
                'code'                => 'PRESTASI',
                'name'                => 'Jalur Prestasi Akademik',
                'description'         => 'Jalur pendaftaran bagi calon mahasiswa dengan prestasi akademik unggul tanpa tes.',
                'kategori_jalur_id'   => $kategoriIds['Seleksi Prestasi'] ?? 3,
                'form_pendaftaran_id' => $forms[0] ?? null,
                'periode_id'          => $periodeId,
                'registration_start'  => '2026-01-15',
                'registration_end'    => '2026-06-30',
                'fee'                 => 200000,
                'color'               => 'info',
                'quota'               => 200,
                'jumlah_pilihan_prodi'=> 1,
                'is_active'           => true,
                'gunakan_berkas'      => true,
                'template_berkas_id'  => $templateBerkas[4] ?? null,
                'metode_pengumuman'   => 'langsung',
            ],
            // ── 5: KIP KULIAH ──
            [
                'code'                => 'KIP_KULIAH',
                'name'                => 'Jalur KIP Kuliah',
                'description'         => 'Jalur pendaftaran khusus bagi penerima Kartu Indonesia Pintar (KIP) Kuliah.',
                'kategori_jalur_id'   => $kategoriIds['Seleksi Kerjasama'] ?? 5,
                'form_pendaftaran_id' => $forms[0] ?? null,
                'periode_id'          => $periodeId,
                'registration_start'  => '2026-02-01',
                'registration_end'    => '2026-08-31',
                'fee'                 => 100000,
                'color'               => 'danger',
                'quota'               => 500,
                'jumlah_pilihan_prodi'=> 2,
                'is_active'           => true,
                'gunakan_ujian'       => true,
                'paket_soal_id'       => $paketSoals[3] ?? null,
                'gunakan_berkas'      => true,
                'template_berkas_id'  => $templateBerkas[1] ?? null,
                'metode_pengumuman'   => 'ditahan',
            ],
        ];

        foreach ($paths as $data) {
            $path = RegistrationPath::create($data);

            // Attach 2 program studi ke pivot
            if (!empty($programStudis)) {
                $selected = array_slice($programStudis, 0, min(2, count($programStudis)));
                $path->programStudis()->sync($selected);
            }
        }

        $this->command->info('5 registration paths seeded successfully.');
    }
}