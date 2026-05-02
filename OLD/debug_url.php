<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Storage URL for 'test.jpg' (Default): " . \Illuminate\Support\Facades\Storage::url('test.jpg') . "\n";
echo "Storage URL for 'test.jpg' (Public Disk): " . \Illuminate\Support\Facades\Storage::disk('public')->url('test.jpg') . "\n";
