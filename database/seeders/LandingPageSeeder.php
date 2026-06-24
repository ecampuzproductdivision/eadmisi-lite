<?php

namespace Database\Seeders;

use App\Models\LandingFacility;
use App\Models\LandingFeature;
use App\Models\LandingProgramStudi;
use App\Models\LandingSetting;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class LandingPageSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Program Studi (update existing or create) ──
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
        $this->command->info('4 Program Studi created/updated.');

        // ── 2. Landing Features (Keunggulan) ──
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
        $this->command->info('4 Landing Features created.');

        // ── 3. Landing Program Studi (menghubungkan prodi ke landing page) ──
        LandingProgramStudi::query()->delete();
        $prodiMapping = [
            ['kode_prodi' => 'TI', 'deskripsi' => 'Program studi unggulan di bidang teknologi informasi dengan fokus pada pengembangan perangkat lunak dan AI.', 'akreditasi' => 'Unggul', 'semester' => 8],
            ['kode_prodi' => 'MN', 'deskripsi' => 'Program studi manajemen terbaik dengan kurikulum berbasis kebutuhan industri dan kewirausahaan.', 'akreditasi' => 'A', 'semester' => 8],
            ['kode_prodi' => 'AK', 'deskripsi' => 'Program studi akuntansi yang menghasilkan lulusan kompeten di bidang akuntansi keuangan dan auditing.', 'akreditasi' => 'A', 'semester' => 8],
            ['kode_prodi' => 'IK', 'deskripsi' => 'Program studi ilmu hukum yang mencetak profesional hukum berintegritas dan berwawasan global.', 'akreditasi' => 'A', 'semester' => 8],
        ];

        $icons = ['ti-device-analytics', 'ti-building-bank', 'ti-report-money', 'ti-heart'];
        foreach ($prodiMapping as $i => $item) {
            $prodi = ProgramStudi::where('kode_prodi', $item['kode_prodi'])->first();
            if ($prodi) {
                LandingProgramStudi::create([
                    'program_studi_id'  => $prodi->id,
                    'deskripsi_singkat' => $item['deskripsi'],
                    'kode_icon'         => $icons[$i],
                    'akreditasi'        => $item['akreditasi'],
                    'jumlah_semester'   => $item['semester'],
                    'is_published'      => true,
                ]);
            }
        }
        $this->command->info('4 Landing Program Studi created.');

        // ── 4. Landing Settings (Pengaturan Kontak & Tentang) ──
        LandingSetting::query()->delete();
        $settings = [
            ['key' => 'contact_email', 'value' => 'info@eadmisi.ac.id'],
            ['key' => 'contact_phone', 'value' => '(021) 1234-5678'],
            ['key' => 'contact_address', 'value' => 'Jl. Pendidikan No. 123, Jakarta Pusat, Indonesia'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/eadmisi'],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/eadmisi'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/@eadmisi'],
            ['key' => 'landing_about_title', 'value' => 'Mengapa Memilih<br>Kampus Kami?'],
            ['key' => 'landing_about_description', 'value' => 'Kami berkomitmen untuk memberikan pendidikan berkualitas dengan kurikulum yang relevan terhadap kebutuhan industri dan perkembangan teknologi terkini. Dengan tenaga pengajar profesional dan fasilitas lengkap, kami siap mencetak generasi penerus bangsa yang unggul dan berdaya saing global.'],
            ['key' => 'landing_facility_title', 'value' => 'Fasilitas Lengkap<br>Untuk Mendukung Belajar'],
            ['key' => 'landing_facility_description', 'value' => 'Kami menyediakan berbagai fasilitas modern untuk menunjang kegiatan belajar mengajar dan pengembangan diri mahasiswa.'],
        ];

        foreach ($settings as $s) {
            LandingSetting::create($s);
        }
        $this->command->info('10 Landing Settings created.');

        // ── 5. Landing Facilities (Fasilitas) ──
        LandingFacility::query()->delete();
        $facilities = [
            ['nama_fasilitas' => 'WiFi 24 Jam', 'deskripsi_fasilitas' => 'Akses internet berkecepatan tinggi yang tersedia di seluruh area kampus selama 24 jam non-stop.', 'kode_icon' => 'ti-wifi', 'urutan' => 1, 'is_active' => true],
            ['nama_fasilitas' => 'Perpustakaan Digital', 'deskripsi_fasilitas' => 'Perpustakaan modern dengan koleksi buku fisik dan digital, jurnal internasional, serta ruang baca yang nyaman.', 'kode_icon' => 'ti-books', 'urutan' => 2, 'is_active' => true],
            ['nama_fasilitas' => 'Laboratorium Modern', 'deskripsi_fasilitas' => 'Laboratorium komputer, sains, dan bahasa yang dilengkapi peralatan terkini untuk praktikum dan penelitian.', 'kode_icon' => 'ti-microscope', 'urutan' => 3, 'is_active' => true],
            ['nama_fasilitas' => 'Gedung Olahraga', 'deskripsi_fasilitas' => 'Fasilitas olahraga lengkap termasuk lapangan basket, futsal, bulu tangkis, dan pusat kebugaran.', 'kode_icon' => 'ti-building-stadium', 'urutan' => 4, 'is_active' => true],
            ['nama_fasilitas' => 'Ruang Kelas Smart', 'deskripsi_fasilitas' => 'Ruang kelas modern dengan smart board, sistem audio visual, dan tata udara yang nyaman untuk belajar.', 'kode_icon' => 'ti-school', 'urutan' => 5, 'is_active' => true],
            ['nama_fasilitas' => 'Ruang Kolaborasi', 'deskripsi_fasilitas' => 'Ruang diskusi dan kolaborasi mahasiswa yang nyaman untuk mengerjakan proyek dan tugas kelompok.', 'kode_icon' => 'ti-users', 'urutan' => 6, 'is_active' => true],
        ];

        foreach ($facilities as $f) {
            LandingFacility::create($f);
        }
        $this->command->info('6 Landing Facilities created.');

        $this->command->info('========================================');
        $this->command->info('LandingPageSeeder completed successfully!');
        $this->command->info('- 4 Program Studi');
        $this->command->info('- 4 Keunggulan (Features)');
        $this->command->info('- 4 Program Studi Landing (with akreditasi & icon)');
        $this->command->info('- 10 Pengaturan (Settings)');
        $this->command->info('- 6 Fasilitas (Facilities)');
        $this->command->info('========================================');
    }
}