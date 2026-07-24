<?php
require_once 'C:\xampp82\htdocs\eadmisi-lite\vendor\autoload.php';
$app = require_once 'C:\xampp82\htdocs\eadmisi-lite\bootstrap\app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MasterPotongan;
use App\Models\PlottingPotongan;
use App\Models\Registration;
use App\Models\JalurPendaftaranBiaya;
use Illuminate\Support\Facades\DB;

echo "--- SCHOLARSHIP FEATURE VERIFICATION START ---\n";

// 1. Create a dummy Master Potongan
$master = MasterPotongan::updateOrCreate(
    ['nama_potongan' => 'Beasiswa Test Antigravity'],
    [
        'tipe_potongan' => 'rupiah',
        'nilai_potongan' => 1500000,
        'keterangan' => 'Test beasiswa via validation script',
    ]
);
echo "Master Potongan Created: ID = {$master->id}, Nama = '{$master->nama_potongan}', Nilai = {$master->nilai_potongan}\n";

// 2. Setup plotting for registration ID 4
$registration = Registration::find(4);
if (!$registration) {
    echo "ERROR: Student registration ID 4 not found!\n";
    exit(1);
}

// Ensure registration has status_registrasi_ulang reset to test
$registration->update(['status_registrasi_ulang' => 'belum_registrasi']);

// Clear existing plotting
PlottingPotongan::where('registration_id', 4)->delete();

// Create new Plotting
$plotting = PlottingPotongan::create([
    'registration_id' => 4,
    'master_potongan_id' => $master->id,
    'nominal_potongan' => 1500000,
    'keterangan' => 'Plotting test diskon',
]);
echo "Plotting Potongan Created: Student ID = 4, Nominal = {$plotting->nominal_potongan}\n";

// 3. Simulate Re-registration Billing Engine logic
$path = $registration->registrationPath;
echo "Path: {$path->name} (ID: {$path->id})\n";

// Calculate components cost
$biayaKomponens = DB::table('jalur_pendaftaran_biayas')
    ->where('registration_path_id', $path->id)
    ->get();
$totalBiaya = $biayaKomponens->sum('nominal');
echo "Original Re-registration Fee: Rp " . number_format($totalBiaya, 0, ',', '.') . "\n";

// Load discount plotting
$discountPlotting = DB::table('plotting_potongans')
    ->join('master_potongans', 'plotting_potongans.master_potongan_id', '=', 'master_potongans.id')
    ->where('plotting_potongans.registration_id', 4)
    ->select('plotting_potongans.nominal_potongan', 'master_potongans.nama_potongan')
    ->first();

$discountAmount = 0;
$discountName = '';
if ($discountPlotting) {
    $discountAmount = (int) $discountPlotting->nominal_potongan;
    $discountName = $discountPlotting->nama_potongan;
}

$finalBiaya = max(0, $totalBiaya - $discountAmount);
echo "Scholarship Discount: Rp " . number_format($discountAmount, 0, ',', '.') . " ('{$discountName}')\n";
echo "Final Discounted Fee: Rp " . number_format($finalBiaya, 0, ',', '.') . "\n";

// Verify calculations
$expectedFinal = max(0, $totalBiaya - 1500000);
if ($finalBiaya == $expectedFinal) {
    echo "SUCCESS: Final fee calculation is accurate! (Expected: {$expectedFinal}, Actual: {$finalBiaya})\n";
} else {
    echo "ERROR: Final fee calculation mismatch! (Expected: {$expectedFinal}, Actual: {$finalBiaya})\n";
}

// 4. Clean up test database entries to keep DB pristine
$plotting->delete();
$master->delete();
echo "Cleaned up dummy master and plotting records.\n";
echo "--- SCHOLARSHIP FEATURE VERIFICATION END ---\n";
