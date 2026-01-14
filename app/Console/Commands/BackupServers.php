<?php

namespace App\Console\Commands;

use App\Models\MikroTikServer;
use App\Services\MikroTikService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackupServers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:servers {--server= : ID of specific server to backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger backup process for MikroTik servers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serverId = $this->option('server');
        
        $query = MikroTikServer::where('is_connected', true);
        
        if ($serverId) {
            $query->where('id', $serverId);
        }

        $servers = $query->get();

        $this->info("Found {$servers->count()} active servers to backup.");

        foreach ($servers as $server) {
            $this->info("Processing server: {$server->name} ({$server->ip})");
            
            try {
                $service = new MikroTikService($server);
                
                // Target URL: The endpoint we just created
                // Explicitly use the VPN IP of the server (201.10.1.1) to ensure router can reach it via VPN
                // or use config('app.url') if public/accessible.
                // Plan said: 201.10.1.1
                $vpnIp = "201.10.1.1"; 
                $targetUrl = "http://{$vpnIp}/api/servers/{$server->id}/backups/upload";
                
                // Generate Token (Must match controller logic)
                $token = md5($server->id . $server->created_at);

                $service->performBackup($targetUrl, $token);
                
                $this->info("✅ Backup command sent successfully.");

                // Cleanup: Keep only last 10 backups
                $oldBackups = $server->backups()
                    ->orderBy('created_at', 'desc')
                    ->skip(10)
                    ->get();

                foreach ($oldBackups as $backup) {
                    try {
                        if (file_exists(storage_path('app/' . $backup->path))) {
                            unlink(storage_path('app/' . $backup->path));
                        }
                        $backup->delete();
                        $this->info("Checking cleanup for {$server->name}: Deleted old backup {$backup->filename}");
                    } catch (\Exception $e) {
                         Log::warning("Failed to delete old backup {$backup->id}: " . $e->getMessage());
                    }
                }

            } catch (\Exception $e) {
                $this->error("❌ Failed to backup server {$server->name}: " . $e->getMessage());
                Log::error("Backup Command Failed for {$server->name}: " . $e->getMessage());
            }
        }
    }
}
