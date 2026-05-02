<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Testing Storage::disk('public')->url('test.jpg')...\n";
    $url = \Illuminate\Support\Facades\Storage::disk('public')->url('test.jpg');
    echo "URL: " . $url . "\n";
} catch (\Exception $e) {
    echo "Caught Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
