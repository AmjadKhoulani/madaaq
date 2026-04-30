<?php

namespace Database\Seeders;

use App\Models\Tower;
use App\Models\TowerDevice;
use App\Models\TowerSSID;
use App\Models\DeviceModel;
use Illuminate\Database\Seeder;

class TowerDevicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenantId = \App\Models\Tenant::first()->id ?? 1;

        // Get or create the tower with a specific name/tenant
        $tower = Tower::updateOrCreate(
            ['name' => 'المنزل', 'tenant_id' => $tenantId],
            [
                'type' => 'tower',
                'city' => 'Rif Dimashq',
                'district' => 'داريا',
                'status' => 'active',
                'lat' => 33.4627650,
                'lng' => 36.2443040,
            ]
        );

        // Get a device model (or use null if none exists)
        $deviceModel = DeviceModel::where('manufacturer', 'MikroTik')->first();

        // Create sample broadcast devices with SSIDs
        $devices = [
            [
                'name' => 'OmniTik',
                'mode' => 'ap',
                'frequency' => '5GHz',
                'ssid' => 'Arkad 1 5G',
                'ip' => '192.168.3.1',
            ],
            [
                'name' => 'Sector North',
                'mode' => 'ap',
                'frequency' => '5GHz',
                'ssid' => 'Madaaq-5G',
                'ip' => '192.168.100.10',
            ],
        ];

        foreach ($devices as $deviceData) {
            $ssid = $deviceData['ssid'];
            $frequency = $deviceData['frequency'];
            
            $device = TowerDevice::updateOrCreate(
                ['tower_id' => $tower->id, 'name' => $deviceData['name']],
                [
                    'device_model_id' => $deviceModel ? $deviceModel->id : null,
                    'mode' => $deviceData['mode'],
                    'frequency' => $deviceData['frequency'],
                    'ip' => $deviceData['ip'],
                    'ssid' => $ssid,
                ]
            );

            // Create corresponding SSID record
            TowerSSID::updateOrCreate(
                ['tower_id' => $tower->id, 'ssid_name' => $ssid],
                [
                    'tower_device_id' => $device->id,
                    'frequency' => (float)str_replace('GHz', '', $frequency),
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('✅ تم إنشاء أجهزة البث والشبكات التجريبية بنجاح!');
    }
}
