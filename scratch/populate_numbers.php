<?php
require_once 'C:\xampp82\htdocs\eadmisi-lite\vendor\autoload.php';
$app = require_once 'C:\xampp82\htdocs\eadmisi-lite\bootstrap\app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Registration;

echo "Populating no_pendaftaran for existing registrations...\n";

$registrations = Registration::whereNull('no_pendaftaran')
    ->orWhere('no_pendaftaran', '')
    ->get();

foreach ($registrations as $reg) {
    $num = Registration::generateNoPendaftaran($reg->registration_path_id);
    $reg->no_pendaftaran = $num;
    $reg->save();
    echo "Assigned ID {$reg->id} -> {$num}\n";
}

echo "Done populating registrations!\n";
