<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FlushWireGuard extends Command
{
    protected $signature = 'wireguard:flush';
    protected $description = 'Removes all WireGuard peers from the interface to ensure a clean state';

    public function handle()
    {
        $this->info("Flushing WireGuard Interface 'madaaqip'...");
        
        try {
            $interface = 'madaaqip';
            
            // Get list of peers
            $outputLines = [];
            $returnVar = 0;
            exec("sudo -n wg show {$interface} peers", $outputLines, $returnVar);
            
            if ($returnVar !== 0) {
                // Interface might not exist, which is fine
                $this->warn("WireGuard interface not found or error. Skipping flush.");
                return;
            }

            if (empty($outputLines)) {
                $this->info("No peers found to flush.");
                return;
            }

            $count = 0;
            foreach ($outputLines as $key) {
                $key = trim($key);
                if (empty($key)) continue;

                $this->line("Removing peer: $key");
                exec("sudo -n wg set {$interface} peer {$key} remove");
                $count++;
            }
            
            $this->info("Successfully flushed $count peers.");
            Log::info("WireGuard Flush: Removed $count peers.");

        } catch (\Exception $e) {
            $this->error("Flush Failed: " . $e->getMessage());
        }
    }
}
