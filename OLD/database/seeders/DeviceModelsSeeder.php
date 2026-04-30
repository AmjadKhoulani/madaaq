<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeviceModel;

class DeviceModelsSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            // Ubiquiti Devices
            ['manufacturer' => 'Ubiquiti', 'model_name' => 'NanoStation Loco M5', 'device_type' => 'access_point', 'frequency' => '5GHz', 'max_throughput' => 150, 'coverage_range' => 5000, 'image_url' => 'https://images.svc.ui.com/?u=https%3A%2F%2Fcdn.ecomm.ui.com%2Fproducts%2F97c1e53f-219a-4d3e-a7d9-8e0e6c00acf3%2F854e6c57-b3a8-4f6e-bbf4-cd38a7e7c90d.png&q=75&w=256'],
            ['manufacturer' => 'Ubiquiti', 'model_name' => 'NanoStation M2', 'device_type' => 'access_point', 'frequency' => '2.4GHz', 'max_throughput' => 150, 'coverage_range' => 10000, 'image_url' => 'https://images.svc.ui.com/?u=https%3A%2F%2Fcdn.ecomm.ui.com%2Fproducts%2F97c1e53f-219a-4d3e-a7d9-8e0e6c00acf3%2F854e6c57-b3a8-4f6e-bbf4-cd38a7e7c90d.png&q=75&w=256'],
            ['manufacturer' => 'Ubiquiti', 'model_name' => 'UniFi 6 Long Range', 'device_type' => 'access_point', 'frequency' => '2.4/5GHz', 'max_throughput' => 3000, 'coverage_range' => 183, 'image_url' => 'https://images.svc.ui.com/?u=https%3A%2F%2Fcdn.ecomm.ui.com%2Fproducts%2F9e6ad1fb-6c0a-4a3e-91ac-08c2f1d537ac%2Ffa2dd4ee-ae74-46e9-b63f-2b3d0cd6d02e.png&q=75&w=256'],
            ['manufacturer' => 'Ubiquiti', 'model_name' => 'PowerBeam AC Gen2', 'device_type' => 'base_station', 'frequency' => '5GHz', 'max_throughput' => 450, 'coverage_range' => 20000, 'image_url' => 'https://images.svc.ui.com/?u=https%3A%2F%2Fcdn.ecomm.ui.com%2Fproducts%2Fa0d51c36-bfbd-4f45-864c-43e9dcf33eec%2F4daef7e3-bd06-442d-8f83-9da866df067b.png&q=75&w=256'],
            ['manufacturer' => 'Ubiquiti', 'model_name' => 'LiteBeam AC Gen2', 'device_type' => 'access_point', 'frequency' => '5GHz', 'max_throughput' => 450, 'coverage_range' => 15000, 'image_url' => 'https://images.svc.ui.com/?u=https%3A%2F%2Fcdn.ecomm.ui.com%2Fproducts%2Fc14f1e49-3d7a-47d5-be65-3ed0f65f35bc%2F3db17962-d9bc-49fb-aa29-afe1a0a66de6.png&q=75&w=256'],
            ['manufacturer' => 'Ubiquiti', 'model_name' => 'airFiber 60 LR', 'device_type' => 'base_station', 'frequency' => '60GHz', 'max_throughput' => 2000, 'coverage_range' => 12000, 'image_url' => 'https://images.svc.ui.com/?u=https%3A%2F%2Fcdn.ecomm.ui.com%2Fproducts%2Fbe5fa0d1-90cb-4de7-94ab-78ab1738d0b0%2F86c47e77-2bc6-4b40-9e0b-9e4c05bd5cfb.png&q=75&w=256'],
            ['manufacturer' => 'Ubiquiti', 'model_name' => 'Rocket AC Lite', 'device_type' => 'base_station', 'frequency' => '5GHz', 'max_throughput' => 450, 'coverage_range' => 30000, 'image_url' => 'https://images.svc.ui.com/?u=https%3A%2F%2Fcdn.ecomm.ui.com%2Fproducts%2F96e4e0c7-2b8e-411f-bdc4-4dcb24c19fa9%2F23f35e77-0e5e-49ab-9629-0ba6e5c7fb77.png&q=75&w=256'],
            ['manufacturer' => 'Ubiquiti', 'model_name' => 'airMAX AC', 'device_type' => 'access_point', 'frequency' => '5GHz', 'max_throughput' => 500, 'coverage_range' => 10000, 'image_url' => 'https://images.svc.ui.com/?u=https%3A%2F%2Fcdn.ecomm.ui.com%2Fproducts%2F14beb88b-560d-47a4-afdb-41e23c8bef84%2F3c22df5c-43e7-4e44-89e9-fa8b25de662e.png&q=75&w=256'],

            // MikroTik Devices - استخدام placeholder لأن روابط MikroTik قد تكون محمية
            ['manufacturer' => 'MikroTik', 'model_name' => 'hEX S (RB760iGS)', 'device_type' => 'router', 'frequency' => 'N/A', 'max_throughput' => 1000, 'coverage_range' => 0, 'image_url' => 'https://via.placeholder.com/128/1976D2/FFFFFF?text=hEX+S'],
            ['manufacturer' => 'MikroTik', 'model_name' => 'CCR1036-12G-4S', 'device_type' => 'router', 'frequency' => 'N/A', 'max_throughput' => 10000, 'coverage_range' => 0, 'image_url' => 'https://via.placeholder.com/128/1976D2/FFFFFF?text=CCR1036'],
            ['manufacturer' => 'MikroTik', 'model_name' => 'SXTsq Lite5', 'device_type' => 'access_point', 'frequency' => '5GHz', 'max_throughput' => 100, 'coverage_range' => 5000, 'image_url' => 'https://via.placeholder.com/128/4CAF50/FFFFFF?text=SXTsq'],
            ['manufacturer' => 'MikroTik', 'model_name' => 'LHG 5', 'device_type' => 'access_point', 'frequency' => '5GHz', 'max_throughput' => 200, 'coverage_range' => 7000, 'image_url' => 'https://via.placeholder.com/128/4CAF50/FFFFFF?text=LHG+5'],
            ['manufacturer' => 'MikroTik', 'model_name' => 'wAP AC', 'device_type' => 'access_point', 'frequency' => '2.4/5GHz', 'max_throughput' => 867, 'coverage_range' => 300, 'image_url' => 'https://via.placeholder.com/128/4CAF50/FFFFFF?text=wAP+AC'],
            ['manufacturer' => 'MikroTik', 'model_name' => 'BaseBox 5', 'device_type' => 'base_station', 'frequency' => '5GHz', 'max_throughput' => 300, 'coverage_range' => 10000, 'image_url' => 'https://via.placeholder.com/128/9C27B0/FFFFFF?text=BaseBox'],
            ['manufacturer' => 'MikroTik', 'model_name' => 'RB4011iGS+RM', 'device_type' => 'router', 'frequency' => 'N/A', 'max_throughput' => 10000, 'coverage_range' => 0, 'image_url' => 'https://via.placeholder.com/128/1976D2/FFFFFF?text=RB4011'],
            ['manufacturer' => 'MikroTik', 'model_name' => 'OmniTIK 5 AC', 'device_type' => 'base_station', 'frequency' => '5GHz', 'max_throughput' => 867, 'coverage_range' => 360, 'image_url' => 'https://via.placeholder.com/128/9C27B0/FFFFFF?text=OmniTIK'],
            ['manufacturer' => 'MikroTik', 'model_name' => 'OmniTIK UPA-5HnD', 'device_type' => 'base_station', 'frequency' => '5GHz', 'max_throughput' => 300, 'coverage_range' => 360, 'image_url' => 'https://via.placeholder.com/128/9C27B0/FFFFFF?text=OmniTIK'],
            ['manufacturer' => 'MikroTik', 'model_name' => 'MantBox 2', 'device_type' => 'access_point', 'frequency' => '2.4GHz', 'max_throughput' => 150, 'coverage_range' => 5000, 'image_url' => 'https://via.placeholder.com/128/4CAF50/FFFFFF?text=MantBox'],
            ['manufacturer' => 'MikroTik', 'model_name' => 'MantBox 5s', 'device_type' => 'access_point', 'frequency' => '5GHz', 'max_throughput' => 200, 'coverage_range' => 7000, 'image_url' => 'https://via.placeholder.com/128/4CAF50/FFFFFF?text=MantBox'],
            ['manufacturer' => 'MikroTik', 'model_name' => 'Disc Lite5', 'device_type' => 'access_point', 'frequency' => '5GHz', 'max_throughput' => 150, 'coverage_range' => 4000, 'image_url' => 'https://via.placeholder.com/128/4CAF50/FFFFFF?text=Disc+Lite'],
            ['manufacturer' => 'MikroTik', 'model_name' => 'NetMetal 5', 'device_type' => 'base_station', 'frequency' => '5GHz', 'max_throughput' => 300, 'coverage_range' => 15000, 'image_url' => 'https://via.placeholder.com/128/9C27B0/FFFFFF?text=NetMetal'],

            // Mimosa Devices
            ['manufacturer' => 'Mimosa', 'model_name' => 'B5c', 'device_type' => 'base_station', 'frequency' => '5GHz', 'max_throughput' => 1500, 'coverage_range' => 30000, 'image_url' => 'https://via.placeholder.com/128/FF5722/FFFFFF?text=B5c'],
            ['manufacturer' => 'Mimosa', 'model_name' => 'C5x', 'device_type' => 'access_point', 'frequency' => '5GHz', 'max_throughput' => 867, 'coverage_range' => 10000, 'image_url' => 'https://via.placeholder.com/128/FF5722/FFFFFF?text=C5x'],
            ['manufacturer' => 'Mimosa', 'model_name' => 'A5c', 'device_type' => 'base_station', 'frequency' => '5GHz', 'max_throughput' => 1000, 'coverage_range' => 25000, 'image_url' => 'https://via.placeholder.com/128/FF5722/FFFFFF?text=A5c'],
            ['manufacturer' => 'Mimosa', 'model_name' => 'C5c', 'device_type' => 'access_point', 'frequency' => '5GHz', 'max_throughput' => 500, 'coverage_range' => 8000, 'image_url' => 'https://via.placeholder.com/128/FF5722/FFFFFF?text=C5c'],
        ];

        foreach ($devices as $device) {
            DeviceModel::updateOrCreate(
                ['model_name' => $device['model_name'], 'manufacturer' => $device['manufacturer']],
                $device
            );
        }

        $this->command->info('✅ تم إضافة ' . count($devices) . ' جهاز بنجاح!');
    }
}
