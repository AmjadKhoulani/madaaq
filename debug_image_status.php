<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DeviceModel;

$devices = DeviceModel::all();

echo "Checking Device Images:\n";
foreach ($devices as $device) {
    if (in_array($device->manufacturer, ['MikroTik', 'Ubiquiti', 'Mimosa'])) {
        echo "Device: {$device->model_name} ({$device->manufacturer})\n";
        echo "  DB URL: {$device->image_url}\n";
        
        // Check if file exists relative to public path
        $publicPath = public_path();
        // Remove leading slash if present for file check
        $relativePath = ltrim($device->image_url, '/');
        $fullPath = $publicPath . '/' . $relativePath;
        
        if (file_exists($fullPath)) {
            echo "  File: DOES EXIST ({$fullPath})\n";
            echo "  Size: " . filesize($fullPath) . " bytes\n";
            $type = mime_content_type($fullPath);
            echo "  Type: {$type}\n";
        } else {
            echo "  File: DOES NOT EXIST ({$fullPath})\n";
        }
        echo "--------------------------------\n";
    }
}
