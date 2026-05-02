<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Router;
use App\Models\Tower;
use App\Models\MikroTikServer;
use App\Models\DeviceStatusLog;
use App\Models\NetworkAlert;

class MonitorDevices extends Command
{
    protected $signature = 'monitor:devices';
    protected $description = 'Monitor network devices (routers, towers, servers) and create alerts';

    public function handle()
    {
        $this->info('🔍 Starting device monitoring...');

        // Monitor Routers
        $this->monitorRouters();

        // Monitor Towers
        $this->monitorTowers();

        // Monitor MikroTik Servers
        $this->monitorServers();

        $this->info('✅ Device monitoring completed!');
    }

    protected function monitorRouters()
    {
        $routers = Router::all();
        $this->info("Checking {$routers->count()} routers...");

        foreach ($routers as $router) {
            if (!$router->ip) {
                continue;
            }

            // Check if router belongs to a tower with a server
            $server = null;
            if ($router->tower && $router->tower->mikrotikServer) {
                $server = $router->tower->mikrotikServer;
            }

            $status = $this->pingDevice($router->ip, $router, $server);

            // Update Router State
            $router->update([
                'is_reachable' => $status['status'] === 'online',
                'latency' => $status['response_time'],
                'last_ping_at' => now(),
            ]);

            // Log status
            DeviceStatusLog::create([
                'device_type' => Router::class,
                'device_id' => $router->id,
                'status' => $status['status'],
                'response_time' => $status['response_time'],
                'checked_at' => now(),
            ]);

            // Check for alerts
            $this->checkAndCreateAlert($router, Router::class, $status);
        }
    }

    protected function monitorTowers()
    {
        $towers = Tower::all();
        $this->info("Checking {$towers->count()} towers...");

        foreach ($towers as $tower) {
            // 1. Check Wireless Pieces (if applicable)
            if ($tower->connection_type === 'wireless') {
                $this->info("  - Checking wireless pieces for Tower: {$tower->name}");
                if ($tower->transmitter_ip) {
                    $this->info("    * Pinging Transmitter: {$tower->transmitter_ip}");
                    $txStatus = $this->pingDevice($tower->transmitter_ip, $tower);
                    $tower->transmitter_status = $txStatus['status'] === 'online' ? 'online' : 'offline';
                }
                if ($tower->receiver_ip) {
                    $this->info("    * Pinging Receiver: {$tower->receiver_ip}");
                    $rxStatus = $this->pingDevice($tower->receiver_ip, $tower);
                    $tower->receiver_status = $rxStatus['status'] === 'online' ? 'online' : 'offline';
                }
                $tower->save();
            }

            // 2. Check related server (administrative link)
            if ($tower->mikrotik_server_id && $tower->mikrotikServer) {
                $status = $this->pingDevice($tower->mikrotikServer->ip, $tower->mikrotikServer);

                DeviceStatusLog::create([
                    'device_type' => Tower::class,
                    'device_id' => $tower->id,
                    'status' => $status['status'],
                    'response_time' => $status['response_time'],
                    'checked_at' => now(),
                ]);

                $this->checkAndCreateAlert($tower, Tower::class, $status);
            }

            // 3. Remote check transmitter/receiver if tower has an ip_address (representing the tower unit itself)
            if ($tower->ip_address) {
                $server = $tower->mikrotikServer;
                $status = $this->pingDevice($tower->ip_address, $tower, $server);
                
                $tower->update([
                    'is_reachable' => $status['status'] === 'online',
                    'latency' => $status['response_time'],
                    'last_ping_at' => now(),
                ]);
            }
        }
    }

    protected function monitorServers()
    {
        $servers = MikroTikServer::all();
        $this->info("Checking {$servers->count()} MikroTik servers...");

        foreach ($servers as $server) {
            if (!$server->ip) {
                continue;
            }

            $status = $this->pingDevice($server->ip, $server);

            DeviceStatusLog::create([
                'device_type' => MikroTikServer::class,
                'device_id' => $server->id,
                'status' => $status['status'],
                'response_time' => $status['response_time'],
                'checked_at' => now(),
            ]);

            $this->checkAndCreateAlert($server, MikroTikServer::class, $status);
        }
    }

    protected function pingDevice($ip, $device = null, $server = null)
    {
        // Try remote ping via MikroTik server if provided
        if ($server) {
            try {
                $service = new \App\Services\MikroTikService($server);
                $remotePing = $service->pingHost($ip, 3);
                
                if ($remotePing['reachable']) {
                    return [
                        'status' => 'online',
                        'response_time' => $remotePing['latency'],
                    ];
                }

                // Fallback: Check Neighbor Table (MNDP)
                $neighbors = $service->getNeighbors();
                foreach ($neighbors as $neighbor) {
                    $mac = $neighbor['mac-address'] ?? null;
                    $identity = $neighbor['identity'] ?? null;
                    
                    // Match by identity (case insensitive) or mac if we had it
                    if (($identity && $device && stripos($identity, $device->name) !== false) || 
                        ($mac && $device && isset($device->mac_address) && $mac === $device->mac_address)) {
                        $this->info("  - Device {$device->name} found via Neighbor Discovery fallback.");
                        return [
                            'status' => 'online',
                            'response_time' => 1, // Artificial latency for neighbor match
                            'via_neighbor' => true
                        ];
                    }
                }
            } catch (\Exception $e) {
                // If remote fails (e.g. server offline), fallback to local
            }
        }

        // Execute local ping command (3 packets, 1 sec wait per packet, 3 sec total timeout)
        $output = [];
        $result = 0;
        
        // Linux/Mac ping format: ping -c 3 -W 3 127.0.0.1
        exec("ping -c 3 -W 3 {$ip} 2>&1", $output, $result);

        // Analyze output for packet loss and time
        $status = 'offline';
        $avgTime = null;
        $packetLoss = 100;

        if ($result === 0 || count($output) > 0) {
            $outputStr = implode("\n", $output);
            
            // Extract Packet Loss
            if (preg_match('/(\d+)% packet loss/', $outputStr, $matches)) {
                $packetLoss = (int)$matches[1];
            }

            // Extract Average Time (min/avg/max/mdev = 23.1/45.2/89.3/...)
            if (preg_match('/min\/avg\/max\/(?:mdev|stddev)\s*=\s*[\d\.]+\/([\d\.]+)\//', $outputStr, $matches)) {
                $avgTime = (float)$matches[1];
            } else {
                // Fallback for single line matches if summary missing
                 if (preg_match('/(?:time|avg)=([\d\.]+) ms/', $outputStr, $matches)) {
                    $avgTime = (float)$matches[1];
                 }
            }

            // Determine Status
            if ($packetLoss < 100) {
                if ($packetLoss > 20) {
                    $status = 'warning'; // Packet loss detected
                } elseif ($avgTime && $avgTime > 150) {
                    $status = 'warning'; // Slow response (> 150ms)
                } else {
                    $status = 'online';
                }
            }
        }

        return [
            'status' => $status,
            'response_time' => $avgTime ? round($avgTime) : null,
        ];
    }

    protected function checkAndCreateAlert($device, $deviceType, $status)
    {
        // Check if device went from online to offline
        $previousLog = DeviceStatusLog::where('device_type', $deviceType)
            ->where('device_id', $device->id)
            ->latest('checked_at')
            ->skip(1)
            ->first();

        // If device is offline
        if ($status['status'] === 'offline') {
            // Check if alert already exists
            $existingAlert = NetworkAlert::where('device_type', $deviceType)
                ->where('device_id', $device->id)
                ->where('is_resolved', false)
                ->first();

            if (!$existingAlert) {
                // Create new alert
                NetworkAlert::create([
                    'tenant_id' => $device->tenant_id ?? 1,
                    'device_type' => $deviceType,
                    'device_id' => $device->id,
                    'alert_type' => 'down',
                    'message' => "الجهاز {$device->name} غير متصل",
                    'severity' => 'critical',
                ]);

                $this->warn("⚠️  Alert created for {$device->name} (OFFLINE)");
            }
        }
        // If device is back online, resolve alerts
        elseif ($status['status'] === 'online' && $previousLog && $previousLog->status === 'offline') {
            NetworkAlert::where('device_type', $deviceType)
                ->where('device_id', $device->id)
                ->where('is_resolved', false)
                ->update([
                    'is_resolved' => true,
                    'resolved_at' => now(),
                ]);

            $this->info("✅ Alert resolved for {$device->name} (ONLINE)");
        }
    }
}
