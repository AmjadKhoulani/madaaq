<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\MikroTikServer;
use App\Models\Router;
use App\Models\Tower;
use App\Services\MikroTikService;
use Illuminate\Http\Request;

class DiscoveryController extends Controller
{
    public function index()
    {
        $servers = MikroTikServer::all();
        return view('network.discovery.index', compact('servers'));
    }

    public function scan(MikroTikServer $server)
    {
        try {
            $service = new MikroTikService($server);
            
            $neighbors = $service->getNeighbors();
            $arp = $service->getArpTable();
            $leases = $service->getDhcpLeases();

            $devices = [];
            
            // 1. Process ARP table (Main source for IPs/MACs)
            foreach ($arp as $entry) {
                $mac = $entry['mac-address'] ?? null;
                $ip = $entry['address'] ?? null;
                if (!$mac || !$ip || str_contains($ip, ':')) continue; // Skip IPv6 and invalid entries
                
                $devices[$mac] = [
                    'address' => $ip,
                    'mac-address' => $mac,
                    'source' => 'ARP',
                    'identity' => null,
                    'platform' => null,
                    'interface' => $entry['interface'] ?? null,
                    'age' => null,
                ];
            }

            // 2. Enrich with DHCP Hostnames
            foreach ($leases as $lease) {
                $mac = $lease['mac-address'] ?? null;
                if (!$mac) continue;
                
                if (!isset($devices[$mac])) {
                    $devices[$mac] = [
                        'address' => $lease['address'] ?? null,
                        'mac-address' => $mac,
                        'source' => 'DHCP',
                        'identity' => $lease['host-name'] ?? null,
                        'platform' => null,
                        'interface' => null,
                        'age' => null,
                    ];
                } else {
                    $devices[$mac]['identity'] = $lease['host-name'] ?? $devices[$mac]['identity'];
                    if (!str_contains($devices[$mac]['source'], 'DHCP')) {
                        $devices[$mac]['source'] .= ', DHCP';
                    }
                }
            }

            // 3. Perfect with Neighbors (Models & Protocols)
            foreach ($neighbors as $neighbor) {
                $mac = $neighbor['mac-address'] ?? null;
                if (!$mac) continue;
                
                $ip = $neighbor['address'] ?? ($neighbor['address4'] ?? null);
                
                if (!isset($devices[$mac])) {
                    $devices[$mac] = [
                        'address' => $ip,
                        'mac-address' => $mac,
                        'source' => 'MNDP',
                        'identity' => $neighbor['identity'] ?? null,
                        'platform' => $neighbor['platform'] ?? ($neighbor['board'] ?? null),
                        'interface' => $neighbor['interface'] ?? null,
                        'age' => $neighbor['age'] ?? null,
                    ];
                } else {
                    $devices[$mac]['identity'] = $neighbor['identity'] ?? $devices[$mac]['identity'];
                    $devices[$mac]['platform'] = $neighbor['platform'] ?? ($neighbor['board'] ?? null);
                    $devices[$mac]['age'] = $neighbor['age'] ?? $devices[$mac]['age'];
                    if (!str_contains($devices[$mac]['source'], 'MNDP')) {
                        $devices[$mac]['source'] .= ', MNDP';
                    }
                }
            }

            // Convert to indexed array for response and match with DB
            $finalResults = [];
            foreach ($devices as $mac => $data) {
                $existing = Router::where('mac_address', $mac)
                    ->orWhere('ip', $data['address'])
                    ->first();

                if ($existing) {
                    $data['is_linked'] = true;
                    $data['device_id'] = $existing->id;
                    $data['device_type'] = 'router';
                    $data['db_name'] = $existing->name;
                    $data['db_ip'] = $existing->ip;
                } else {
                    $data['is_linked'] = false;
                }
                
                // Add server info for remote webfig
                $data['server_id'] = $server->id;

                $finalResults[] = $data;
            }

            return response()->json($finalResults);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function linkDevice(Request $request)
    {
        $request->validate([
            'device_id' => 'required',
            'neighbor_ip' => 'required',
            'device_type' => 'required|in:router,tower'
        ]);

        if ($request->device_type === 'router') {
            $router = Router::findOrFail($request->device_id);
            $router->update([
                'ip' => $request->neighbor_ip,
                'is_reachable' => true,
                'last_ping_at' => now()
            ]);
        }

        return response()->json(['success' => true]);
    }
}
