<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AdminSetting;
use Illuminate\Support\Facades\Storage;

$qrPath = AdminSetting::where('key', 'sham_cash_qr')->value('value');

echo "Stored Path in DB: " . ($qrPath ?? 'NULL') . "\n";

if ($qrPath) {
    echo "Full URL: " . Storage::disk('public')->url($qrPath) . "\n";
    
    $exists = Storage::disk('public')->exists($qrPath);
    echo "Exists on public disk? " . ($exists ? 'Yes' : 'No') . "\n";
    
    $realPath = storage_path('app/public/' . $qrPath);
    echo "Physical Path: " . $realPath . "\n";
    echo "File Exists Physically? " . (file_exists($realPath) ? 'Yes' : 'No') . "\n";
}

echo "\nChecking Storage Link:\n";
$publicStoragePath = public_path('storage');
if (is_link($publicStoragePath)) {
    echo "public/storage link exists and points to: " . readlink($publicStoragePath) . "\n";
} else {
    echo "public/storage link DOES NOT exist.\n";
}
