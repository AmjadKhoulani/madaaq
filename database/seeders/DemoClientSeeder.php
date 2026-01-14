<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenantId = \App\Models\Tenant::first()->id ?? 1;
        
        // Ensure at least one Router exists
        $router = \App\Models\Router::first();
        if (!$router) {
            $router = \App\Models\Router::create([
                'tenant_id' => $tenantId,
                'name' => 'Main Router',
                'ip' => '192.168.88.1',
                'username' => 'admin',
                'password' => 'admin',
                'api_port' => 8728,
                'status' => 'active'
            ]);
        }

        // Ensure at least one Package exists
        $package = \App\Models\Package::first();
        if (!$package) {
            $package = \App\Models\Package::create([
                'tenant_id' => $tenantId,
                'name' => 'Basic Plan',
                'price' => 100,
                'speed_down' => 10,
                'speed_up' => 2,
                'type' => 'pppoe'
            ]);
        }

        if (!\App\Models\Client::where('username', 'demo')->exists()) {
            \App\Models\Client::create([
                'tenant_id' => $tenantId,
                'router_id' => $router->id,
                'package_id' => $package->id,
                'name' => 'Demo User',
                'username' => 'demo',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'phone' => '966500000000',
                'status' => 'active',
                'expires_at' => now()->addDays(30),
                'balance' => 0,
                'type' => 'pppoe', // Assuming type is required based on package
            ]);
            $this->command->info('Demo Client Created: demo / password');
        } else {
            $this->command->info('Demo Client Already Exists');
        }
    }
}
