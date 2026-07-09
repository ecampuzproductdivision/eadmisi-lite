<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // ── Master Data (prerequisite) ──
            UserSeeder::class,
            RegencySeeder::class,
            KecamatanKelurahanSeeder::class,
            KategoriJalurSeeder::class,
            ProgramStudiSeeder::class,

            // ── Menu & Page (needed early) ──
            MenuRegistrationPathSeeder::class,
            PageRegistrationPathSeeder::class,
            DaftarPmbSeeder::class,
            ExamQuestionSeeder::class,
            PendaftaranSeeder::class,
            MenuCalonMahasiswaSeeder::class,
            MenuTagihanSeeder::class,
            MenuFormPendaftaranSeeder::class,
            MenuSyaratBerkasSeeder::class,
            MenuBankSoalSeeder::class,
            MenuProgramStudiSeeder::class,
            PeriodeMenuSeeder::class,

            // ── Komponen Biaya Menu & Data ──
            KomponenBiayaMenuSeeder::class,
            KomponenBiayaSeeder::class,

            // ── Core Business Data (prerequisites for JalurPendaftaran) ──
            PeriodeSeeder::class,
            WawancaraMenuSeeder::class,
            SyaratBerkasSeeder::class,    // TemplateBerkas + SyaratDokumen
            PaketSoalSeeder::class,       // PaketSoal + SoalUjian
            CrmLeadSeeder::class,
            CrmLeadMenuSeeder::class,
            LandingPageMenuSeeder::class,
            PeriodePermissionSeeder::class,
            TestDataStikesSeeder::class,
            LandingPageSeeder::class,
            LandingPageSubMenuSeeder::class,
            MenuFormPendaftaranSubMenuSeeder::class,
            FormPendaftaranSeeder::class, // Form + FormField

            // ── Jalur Pendaftaran (depends on all above) ──
            JalurPendaftaranSeeder::class,
        ]);
    }
}