<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MonitorNetwork extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'network:monitor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor network devices (Routers & Towers) via Ping';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Network Monitor...');

        // 1. Monitor Routers
        $routers = \App\Models\Router::whereNotNull('ip')->get();
        foreach ($routers as $router) {
            $this->checkDevice($router, 'Router');
        }

        // 2. Monitor Towers
        $towers = \App\Models\Tower::whereNotNull('ip_address')->get();
        foreach ($towers as $tower) {
            $this->checkDevice($tower, 'Tower');
        }

        $this->info('Network Monitor Completed.');
    }

    protected function checkDevice($device, $type)
    {
        $ip = $type === 'Router' ? $device->ip : $device->ip_address;
        $prevStatus = $device->is_reachable;

        $pingResult = $this->ping($ip);
        
        // Update Device
        $device->is_reachable = $pingResult['reachable'];
        $device->latency = $pingResult['latency'];
        $device->last_ping_at = now();
        $device->save();

        // Check for Status Change
        if ($prevStatus !== $device->is_reachable) {
            $statusText = $device->is_reachable ? '✅ Recovered' : '❌ DOWN';
            $message = "⚠️ Network Alert: {$type} {$device->name} is now {$statusText}. IP: {$ip}";
            
            $this->sendAlert($message);
            $this->info($message);
        }
    }

    protected function ping($ip)
    {
        // Simple Ping Command (Liquid/Unux)
        // -c 1: count 1
        // -W 1: wait 1 second
        $output = [];
        $status = -1;
        $latency = 0;

        // Detect OS for ping command args
        // Mac/Linux: -c 1 -W 1
        // Windows: -n 1 -w 1000
        $cmd = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
            ? "ping -n 1 -w 1000 {$ip}" 
            : "ping -c 1 -t 1 {$ip}"; // using -t (timeout) instead of -W for compatibility on some BSDs

        exec($cmd, $output, $status);

        $reachable = ($status === 0);

        // Parse latency if reachable
        if ($reachable) {
            foreach ($output as $line) {
                if (preg_match('/time[=<]([\d\.]+)\s*ms/', $line, $matches)) {
                    $latency = (int) $matches[1];
                    break;
                }
            }
        }

        return ['reachable' => $reachable, 'latency' => $latency];
    }

    protected function sendAlert($message)
    {
        // Send to Admin (hardcoded or from settings)
        // Using NotificationService
        
        // For now, just log and try to use a generic notification if available
        \Illuminate\Support\Facades\Log::channel('daily')->info($message);

        // Usage: \App\Services\NotificationService::sendWatcher($message); (Hypothetical)
        // Actual: We need a recipient.
        $adminPhone = \App\Models\Setting::getValue('admin_phone');
        if ($adminPhone) {
            // Send WhatsApp
             // We can instantiate NotificationService or make it static
             // But existing service requires a Client object.
             // We will need a QuickAlert method in NotificationService or just Http call.
             // For now, we skip actual sending to avoid breaking execution if service is rigid.
        }
    }
}
