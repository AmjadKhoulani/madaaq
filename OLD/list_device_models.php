<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DeviceModel;

$devices = DeviceModel::all();
echo "Total Devices: " . $devices->count() . "\n";
echo str_pad("ID", 5) . str_pad("Manufacturer", 15) . str_pad("Model Name", 30) . "Current Image\n";
echo str_repeat("-", 80) . "\n";

foreach ($devices as $device) {
    echo str_pad($device->id, 5) . 
         str_pad($device->manufacturer, 15) . 
         str_pad(substr($device->model_name, 0, 28), 30) . 
         $device->image_url . "\n";
}
