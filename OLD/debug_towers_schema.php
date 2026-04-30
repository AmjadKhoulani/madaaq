<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$columns = Schema::getColumnListing('towers');
echo "COLUMNS:\n";
print_r($columns);

$tower = DB::table('towers')->first();
echo "\nFIRST TOWER:\n";
print_r($tower);
