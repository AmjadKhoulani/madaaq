<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Router;
use App\Models\MikroTikServer;
use App\Models\BandwidthLog;
use App\Services\MikroTikService;

class CollectBandwidth extends Command
{
    protected $signature = 'bandwidth:collect';
    protected $description = 'Collect bandwidth statistics from MikroTik devices';

    public function handle()
    {
        $this->info('📊 Collecting bandwidth statistics...');

        // Collect from Routers
        $this->collectFromRouters();

        // Collect from Servers
        $this->collectFromServers();

        $this->info('✅ Bandwidth collection completed!');
    }

    protected function collectFromRouters()
    {
        $routers = Router::whereNotNull('ip')->get();
        $this->info("Checking {$routers->count()} routers...");

        foreach ($routers as $router) {
            try {
                $service = new MikroTikService($router);
                $interfaces = $service->getInterfaceStats();

                foreach ($interfaces as $interface) {
                    $name = $interface['name'] ?? 'unknown';
                    $currentRx = $interface['rx-byte'] ?? 0;
                    $currentTx = $interface['tx-byte'] ?? 0;

                    // Get last record to calculate delta
                    $lastLog = BandwidthLog::where('device_type', Router::class)
                        ->where('device_id', $router->id)
                        ->where('interface_name', $name)
                        ->latest('recorded_at')
                        ->first();

                    $deltaRx = 0;
                    $deltaTx = 0;

                    if ($lastLog) {
                        // If current is less than last, counter probably reset
                        $deltaRx = ($currentRx >= $lastLog->raw_rx) ? ($currentRx - $lastLog->raw_rx) : 0;
                        $deltaTx = ($currentTx >= $lastLog->raw_tx) ? ($currentTx - $lastLog->raw_tx) : 0;
                    }

                    BandwidthLog::create([
                        'device_type' => Router::class,
                        'device_id' => $router->id,
                        'interface_name' => $name,
                        'rx_bytes' => $deltaRx,
                        'tx_bytes' => $deltaTx,
                        'raw_rx' => $currentRx, // We need to add this column or use a dedicated table
                        'raw_tx' => $currentTx,
                        'recorded_at' => now(),
                    ]);
                }

                $this->line("✓ {$router->name}");
            } catch (\Exception $e) {
                $this->warn("✗ {$router->name}: {$e->getMessage()}");
            }
        }
    }

    protected function collectFromServers()
    {
        $servers = MikroTikServer::whereNotNull('ip')->get();
        $this->info("Checking {$servers->count()} servers...");

        foreach ($servers as $server) {
            try {
                $service = new MikroTikService($server);
                $interfaces = $service->getInterfaceStats();

                foreach ($interfaces as $interface) {
                    $name = $interface['name'] ?? 'unknown';
                    $currentRx = $interface['rx-byte'] ?? 0;
                    $currentTx = $interface['tx-byte'] ?? 0;

                    $lastLog = BandwidthLog::where('device_type', MikroTikServer::class)
                        ->where('device_id', $server->id)
                        ->where('interface_name', $name)
                        ->latest('recorded_at')
                        ->first();

                    $deltaRx = 0;
                    $deltaTx = 0;

                    if ($lastLog) {
                        $deltaRx = ($currentRx >= $lastLog->raw_rx) ? ($currentRx - $lastLog->raw_rx) : 0;
                        $deltaTx = ($currentTx >= $lastLog->raw_tx) ? ($currentTx - $lastLog->raw_tx) : 0;
                    }

                    BandwidthLog::create([
                        'device_type' => MikroTikServer::class,
                        'device_id' => $server->id,
                        'interface_name' => $name,
                        'rx_bytes' => $deltaRx,
                        'tx_bytes' => $deltaTx,
                        'raw_rx' => $currentRx,
                        'raw_tx' => $currentTx,
                        'recorded_at' => now(),
                    ]);
                }

                $this->line("✓ {$server->name}");
            } catch (\Exception $e) {
                $this->warn("✗ {$server->name}: {$e->getMessage()}");
            }
        }
    }
}
