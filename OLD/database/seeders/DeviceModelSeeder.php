<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeviceModel;

class DeviceModelSeeder extends Seeder
{
    public function run()
    {
        $devices = [
            // MikroTik Devices
            [
                'manufacturer' => 'MikroTik',
                'model_name' => 'CCR1072-1G-8S+',
                'device_type' => 'server',
                'frequency' => null,
                'image_url' => 'https://cdn.mikrotik.com/web-assets/rb_images/1055_tm.webp'
            ],
            [
                'manufacturer' => 'MikroTik',
                'model_name' => 'CCR1036-8G-2S+',
                'device_type' => 'server',
                'frequency' => null,
                'image_url' => 'https://cdn.mikrotik.com/web-assets/rb_images/1738_tm.webp'
            ],
            [
                'manufacturer' => 'MikroTik',
                'model_name' => 'CCR1009-7G-1C-1S+',
                'device_type' => 'server',
                'frequency' => null,
                'image_url' => 'https://cdn.mikrotik.com/web-assets/rb_images/1228_tm.webp'
            ],
            [
                'manufacturer' => 'MikroTik',
                'model_name' => 'RB4011iGS+RM',
                'device_type' => 'router',
                'frequency' => '5GHz',
                'image_url' => 'https://cdn.mikrotik.com/web-assets/rb_images/2065_tm.webp'
            ],
            [
                'manufacturer' => 'MikroTik',
                'model_name' => 'RB5009UG+S+IN',
                'device_type' => 'router',
                'frequency' => null,
                'image_url' => 'https://cdn.mikrotik.com/web-assets/rb_images/2065_tm.webp'
            ],
            [
                'manufacturer' => 'MikroTik',
                'model_name' => 'hEX S (RB760iGS)',
                'device_type' => 'router',
                'frequency' => null,
                'image_url' => 'https://cdn.mikrotik.com/web-assets/rb_images/2065_tm.webp'
            ],
             [
                'manufacturer' => 'MikroTik',
                'model_name' => 'LHG 5',
                'device_type' => 'access_point',
                'frequency' => '5GHz',
                'image_url' => 'https://cdn.mikrotik.com/web-assets/rb_images/1086_l.jpg'
            ],
            [
                'manufacturer' => 'MikroTik',
                'model_name' => 'SXTsq 5 ac',
                'device_type' => 'access_point',
                'frequency' => '5GHz',
                'image_url' => 'https://cdn.mikrotik.com/web-assets/rb_images/1547_l.jpg'
            ],

            // Ubiquiti Devices
            [
                'manufacturer' => 'Ubiquiti',
                'model_name' => 'NanoStation M5',
                'device_type' => 'access_point',
                'frequency' => '5GHz',
                'image_url' => 'https://dl.ui.com/ubnt/nsm5.png'
            ],
            [
                'manufacturer' => 'Ubiquiti',
                'model_name' => 'LiteBeam M5',
                'device_type' => 'access_point',
                'frequency' => '5GHz',
                'image_url' => 'https://dl.ui.com/ubnt/lbe-m5-23.png'
            ],
            [
                'manufacturer' => 'Ubiquiti',
                'model_name' => 'PowerBeam 5AC ISO',
                'device_type' => 'base_station',
                'frequency' => '5GHz',
                'image_url' => 'https://dl.ui.com/ubnt/pbe-5ac-iso-gen2.png'
            ],
             [
                'manufacturer' => 'Ubiquiti',
                'model_name' => 'Rocket Prism 5AC',
                'device_type' => 'base_station',
                'frequency' => '5GHz',
                'image_url' => 'https://dl.ui.com/ubnt/rp-5ac-gen2.png'
            ],

            // Mimosa
            [
                'manufacturer' => 'Mimosa',
                'model_name' => 'C5x',
                'device_type' => 'access_point',
                'frequency' => '5GHz',
                'image_url' => 'https://mimosa.co/uploads/C5x.png'
            ],
            [
                'manufacturer' => 'Mimosa',
                'model_name' => 'B5c',
                'device_type' => 'base_station',
                'frequency' => '5GHz',
                'image_url' => 'https://mimosa.co/uploads/B5c.png'
            ],
        ];

        foreach ($devices as $device) {
            DeviceModel::firstOrCreate(
                ['model_name' => $device['model_name']],
                $device
            );
        }
    }
}
