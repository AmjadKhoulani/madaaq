<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DeviceModel;

// Update to use .jpg for Mimosa and potentially Ubiquiti
$map = [
    'Mimosa' => '/images/devices/mimosa_b5.jpg',
];

if (file_exists('/home/madaaq/public_html/public/images/devices/ubiquiti_litebeam.jpg') && filesize('/home/madaaq/public_html/public/images/devices/ubiquiti_litebeam.jpg') > 0) {
    $map['Ubiquiti'] = '/images/devices/ubiquiti_litebeam.jpg';
}

$devices = DeviceModel::all();
foreach ($devices as $device) {
    if (isset($map[$device->manufacturer])) {
        $device->image_url = $map[$device->manufacturer];
        $device->save();
        echo "Updated {$device->model_name} to {$device->image_url}\n";
    }
}
