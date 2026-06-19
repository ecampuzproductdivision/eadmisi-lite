<?php

namespace Database\Seeders;

use App\Helpers\PeriodeHelper;
use App\Models\Periode;
use Illuminate\Database\Seeder;

/**
 * PeriodeSeeder - Seeds academic periods from 2024/2025 up to 2028/2029.
 *
 * Sets "2026/2027 - Ganjil" as the only active period (status_aktif = true).
 */
class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = 2026;
        $semesters = ['Ganjil', 'Genap'];

        // Define the years range: 2024/2025 to 2028/2029
        $startYear = $currentYear - 2; // 2024
        $endYear   = $currentYear + 2; // 2028

        // Build array of period data
        $periodes = [];

        for ($year = $startYear; $year <= $endYear; $year++) {
            foreach ($semesters as $semester) {
                $periodes[] = [
                    'tahun_akademik' => $year . '/' . ($year + 1),
                    'semester'       => $semester,
                    'status_aktif'   => false,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }
        }

        // Insert all periods
        foreach ($periodes as $data) {
            // Check if combo already exists to avoid duplicates
            $exists = Periode::where('tahun_akademik', $data['tahun_akademik'])
                ->where('semester', $data['semester'])
                ->exists();

            if (!$exists) {
                Periode::create($data);
            }
        }

        // Set "2026/2027 - Ganjil" as the one active period
        $activePeriode = Periode::where('tahun_akademik', '2026/2027')
            ->where('semester', 'Ganjil')
            ->first();

        if ($activePeriode) {
            // Deactivate all others first
            Periode::where('status_aktif', true)->update(['status_aktif' => false]);

            // Activate the target period
            $activePeriode->update(['status_aktif' => true]);

            // Clear the cache so the helper picks up the change
            PeriodeHelper::clearCache();

            $this->command->info('✓ Active period set to: 2026/2027 - Ganjil');
        }

        $this->command->info('✓ PeriodeSeeder completed successfully.');
        $this->command->info('  Periods created: ' . count($periodes));
    }
}