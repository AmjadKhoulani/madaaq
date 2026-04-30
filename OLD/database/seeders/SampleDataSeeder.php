<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Router;
use App\Models\BandwidthLog;
use App\Models\Tenant;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        $tenantId = Tenant::first()->id ?? 1;
        $clients = Client::all();
        
        if ($clients->isEmpty()) {
            return;
        }

        $this->command->info('Seed Invoices...');
        foreach ($clients as $client) {
            // Create some paid invoices for the last 3 months
            for ($i = 0; $i < 3; $i++) {
                Invoice::create([
                    'tenant_id' => $tenantId,
                    'client_id' => $client->id,
                    'invoice_number' => 'INV-' . strtoupper(uniqid()),
                    'amount' => 100,
                    'status' => 'paid',
                    'due_date' => now()->subMonths($i),
                    'paid_at' => now()->subMonths($i)->addDays(2),
                    'created_at' => now()->subMonths($i),
                ]);
            }

            // Create one unpaid invoice
            Invoice::create([
                'tenant_id' => $tenantId,
                'client_id' => $client->id,
                'invoice_number' => 'INV-UP-' . strtoupper(uniqid()),
                'amount' => 100,
                'status' => 'unpaid',
                'due_date' => now()->addDays(15),
                'created_at' => now(),
            ]);
        }

        $this->command->info('Seed Bandwidth Logs...');
        $routers = Router::all();
        foreach ($routers as $router) {
            // Create logs for the last 24 hours
            for ($i = 0; $i < 24; $i++) {
                BandwidthLog::create([
                    'device_type' => Router::class,
                    'device_id' => $router->id,
                    'interface_name' => 'ether1',
                    'rx_bytes' => rand(100000000, 500000000), // 100MB - 500MB
                    'tx_bytes' => rand(10000000, 50000000),   // 10MB - 50MB
                    'recorded_at' => now()->subHours($i),
                    'created_at' => now()->subHours($i),
                ]);
            }
        }

        $this->command->info('Sample data seeded successfully!');
    }
}
