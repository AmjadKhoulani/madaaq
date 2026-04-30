<?php

use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "--- Routers Table Columns ---\n";
$columns = Schema::getColumnListing('routers');
foreach ($columns as $col) {
    echo "- $col\n";
}
