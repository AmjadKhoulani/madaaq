<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DeviceModel;

// Flat structure: /images/devices/... (relative to public_html root)
$map = [
    'MikroTik' => '/images/devices/mikrotik_hex.png',
    'Ubiquiti' => '/images/devices/ubiquiti_litebeam.png',
    'Mimosa' => '/images/devices/mimosa_b5.jpg',
];

$devices = DeviceModel::all();
foreach ($devices as $device) {
    if (isset($map[$device->manufacturer])) {
        $device->image_url = $map[$device->manufacturer];
        $device->save();
        echo "Updated {$device->model_name} to {$device->image_url}\n";
    }
}
