<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DeviceModel;

// Fallback to Ubiquiti Logo (PNG)
$map = [
    'Ubiquiti' => '/images/devices/ubiquiti_litebeam.png',
];

$devices = DeviceModel::all();
foreach ($devices as $device) {
    if (isset($map[$device->manufacturer])) {
        $device->image_url = $map[$device->manufacturer];
        $device->save();
        echo "Updated {$device->model_name} to {$device->image_url}\n";
    }
}
