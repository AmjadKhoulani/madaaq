<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DeviceModel;

// Specific mappings for all 16 devices
$map = [
    // MikroTik
    'hEX (RB750Gr3)' => '/images/devices/mikrotik_hex.png',
    'hAP ac2' => '/images/devices/mikrotik_hap_ac2.png',
    'CCR1009-7G-1C-1S+' => '/images/devices/mikrotik_ccr1009.png',
    'SXTsq 5 ac' => '/images/devices/mikrotik_sxtsq_5_ac.png',
    'LHG 5' => '/images/devices/mikrotik_lhg_5.png',
    'OmniTik 5' => '/images/devices/mikrotik_omnitik_5.png',
    'NetMetal 5' => '/images/devices/mikrotik_netmetal_5.png',
    
    // Ubiquiti
    'LiteBeam M5' => '/images/devices/ubiquiti_litebeam.png', // Fallback/Logo
    'NanoStation M5' => '/images/devices/ubiquiti_nanostation_m5.png',
    'PowerBeam M5' => '/images/devices/ubiquiti_powerbeam_m5.png',
    'Rocket M5' => '/images/devices/ubiquiti_rocket_m5.png',
    'UniFi AP AC Lite' => '/images/devices/ubiquiti_unifi_ap_ac_lite.png',
    'AirFiber 5X' => '/images/devices/ubiquiti_airfiber_5x.png',
    
    // Mimosa
    'B5' => '/images/devices/mimosa_b5.jpg',
    'C5x' => '/images/devices/mimosa_c5x.jpg',
    'A5c' => '/images/devices/mimosa_b5.jpg', // Reuse B5 as placeholder if A5c verify fails or specific image not found immediately
];

$devices = DeviceModel::all();
foreach ($devices as $device) {
    if (isset($map[$device->model_name])) {
        $device->image_url = $map[$device->model_name];
        $device->save();
        echo "Updated {$device->model_name} to {$device->image_url}\n";
    }
}
