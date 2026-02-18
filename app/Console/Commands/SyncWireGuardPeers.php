<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MikroTikServer;
use Illuminate\Support\Facades\Log;

class SyncWireGuardPeers extends Command
{
    protected $signature = 'sync:wireguard';
    protected $description = 'Syncs all MikroTik WireGuard peers to the local server configuration';

    public function handle()
    {
        $this->info("Starting WireGuard Peer Sync...");
        
        // 1. Fetch Servers
        $servers = \App\Models\MikroTikServer::where('wireguard_enabled', true)
            ->whereNotNull('wireguard_public_key')
            ->whereNotNull('wireguard_ip')
            ->get();

        // 2. Fetch Routers (New structural support)
        $routers = \App\Models\Router::where('wireguard_enabled', true)
            ->whereNotNull('wireguard_public_key')
            ->whereNotNull('wireguard_ip')
            ->get();

        $allDevices = $servers->concat($routers);

        foreach ($allDevices as $device) {
            $this->info("Syncing: {$device->name} ({$device->wireguard_ip})");
            
            try {
                $cmd = "sudo -n wg set madaaqip peer {$device->wireguard_public_key} allowed-ips {$device->wireguard_ip}/32 2>&1";
                
                $outputLines = [];
                $returnVar = 0;
                exec($cmd, $outputLines, $returnVar);
                $output = implode("\n", $outputLines);
                
                if ($returnVar !== 0) {
                    $this->error("Failed to add peer for {$device->name}. Code: $returnVar");
                    Log::error("WireGuard Sync Failed for {$device->name}: $output");
                } else {
                    $this->info("Success.");
                }

            } catch (\Exception $e) {
                $this->error("Error: " . $e->getMessage());
            }
        }
        
        $this->info("Sync Completed.");
    }
}
