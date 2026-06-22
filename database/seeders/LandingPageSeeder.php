<?php

namespace Database\Seeders;

use App\Models\LandingFeature;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class LandingPageSeeder extends Seeder
{
    public function run(): void
    {
        // ── Program Studi (update existing or create) ──
        $prodis = [
            ['kode_prodi' => 'TI', 'jenjang_akademik' => 'S1', 'nama_prodi' => 'Teknik Informatika', 'jurusan' => 'Informatika', 'deskripsi_singkat' => 'Mempelajari pengembangan perangkat lunak, kecerdasan buatan, keamanan siber, dan teknologi informasi modern.', 'akreditasi' => 'Unggul', 'kode_icon' => 'ti-device-analytics', 'kelompok' => 'Eksakta', 'program' => 'Reguler'],
            ['kode_prodi' => 'MN', 'jenjang_akademik' => 'S1', 'nama_prodi' => 'Manajemen', 'jurusan' => 'Manajemen', 'deskripsi_singkat' => 'Mempelajari strategi bisnis, kewirausahaan, manajemen keuangan, dan operasional perusahaan secara profesional.', 'akreditasi' => 'A', 'kode_icon' => 'ti-building-bank', 'kelompok' => 'Non Eksakta', 'program' => 'Reguler'],
            ['kode_prodi' => 'AK', 'jenjang_akademik' => 'S1', 'nama_prodi' => 'Akuntansi', 'jurusan' => 'Akuntansi', 'deskripsi_singkat' => 'Mempelajari akuntansi keuangan, auditing, perpajakan, dan sistem informasi akuntansi berbasis digital.', 'akreditasi' => 'A', 'kode_icon' => 'ti-report-money', 'kelompok' => 'Non Eksakta', 'program' => 'Reguler'],
            ['kode_prodi' => 'IK', 'jenjang_akademik' => 'S1', 'nama_prodi' => 'Ilmu Hukum', 'jurusan' => 'Hukum', 'deskripsi_singkat' => 'Mempelajari sistem hukum Indonesia, perundang-undangan, hukum bisnis, dan praktik advokasi profesional.', 'akreditasi' => 'A', 'kode_icon' => 'ti-heart', 'kelompok' => 'Non Eksakta', 'program' => 'Reguler'],
        ];

        foreach ($prodis as $data) {
            ProgramStudi::updateOrCreate(
                ['kode_prodi' => $data['kode_prodi']],
                array_merge($data, [
                    'nama' => $data['nama_prodi'],
                    'label_nim' => $data['kode_prodi'],
                    'jenjang' => $data['jenjang_akademik'],
                    'status_aktif' => true,
                    'status' => true,
                ])
            );
        }

        // ── Landing Features ──
        LandingFeature::query()->delete();
        $features = [
            ['nama_icon' => 'ti-certificate', 'judul_poin' => 'Terakreditasi Unggul', 'deskripsi_poin' => 'Terakreditasi A oleh BAN-PT dengan nilai unggul dalam pelaksanaan pendidikan tinggi.', 'warna_skema' => 'danger', 'sort_order' => 1],
            ['nama_icon' => 'ti-affiliate', 'judul_poin' => 'Jaringan Luas', 'deskripsi_poin' => 'Kerjasama dengan 200+ perusahaan dan institusi pendidikan nasional maupun internasional.', 'warna_skema' => 'success', 'sort_order' => 2],
            ['nama_icon' => 'ti-device-laptop', 'judul_poin' => 'Pembelajaran Modern', 'deskripsi_poin' => 'Sistem informasi akademik terintegrasi, LMS, dan laboratorium virtual yang mudah diakses 24/7.', 'warna_skema' => 'info', 'sort_order' => 3],
            ['nama_icon' => 'ti-users', 'judul_poin' => 'Tenaga Pengajar Ahli', 'deskripsi_poin' => 'Dosen berkualifikasi S3 dan praktisi industri yang berpengalaman di bidangnya masing-masing.', 'warna_skema' => 'warning', 'sort_order' => 4],
        ];

        foreach ($features as $f) {
            LandingFeature::create($f);
        }

        $this->command->info('Landing page data seeded: 4 program studi + 4 features.');
    }
}