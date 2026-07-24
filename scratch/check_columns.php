<?php
require_once 'C:\xampp82\htdocs\eadmisi-lite\vendor\autoload.php';
$app = require_once 'C:\xampp82\htdocs\eadmisi-lite\bootstrap\app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "Columns in 'registrations' table:\n";
print_r(Schema::getColumnListing('registrations'));
