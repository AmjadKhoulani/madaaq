<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DeviceModel;

$devices = DeviceModel::all();

foreach ($devices as $device) {
    echo "ID: {$device->id}, Model: {$device->model_name}, Manufacturer: {$device->manufacturer}, Image URL: {$device->image_url}\n";
}
