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
        
        $servers = MikroTikServer::where('wireguard_enabled', true)
            ->whereNotNull('wireguard_public_key')
            ->whereNotNull('wireguard_ip')
            ->get();

        foreach ($servers as $server) {
            $this->info("Syncing: {$server->name} ({$server->wireguard_ip})");
            
            try {
                // Command to add peer. If it exists, wg set updates it (idempotent for keys/endpoints usually, but 'set' might strict fail if peer exists? 
                // 'wg set' adds or updates. It does NOT fail if peer exists.
                // We use sudo -n.
                // We execute via shell_exec/exec. But we are CLI user (root/agent), so it works.
                // If running via cron as web user? No, cron usually madaaq. madaaq has sudo.

                $cmd = "sudo -n wg set madaaqip peer {$server->wireguard_public_key} allowed-ips {$server->wireguard_ip}/32 2>&1";
                
                $outputLines = [];
                $returnVar = 0;
                exec($cmd, $outputLines, $returnVar);
                $output = implode("\n", $outputLines);
                
                if ($returnVar !== 0) {
                    $this->error("Failed to add peer for {$server->name}. Code: $returnVar");
                    Log::error("WireGuard Sync Failed for {$server->name}: $output");
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
