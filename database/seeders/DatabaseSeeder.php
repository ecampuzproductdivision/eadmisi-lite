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
            UserSeeder::class,
            RegencySeeder::class,
            KategoriJalurSeeder::class,
            ProgramStudiSeeder::class,
            RegistrationPathSeeder::class,
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
            PeriodeSeeder::class,
        ]);
    }
}