<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\MikroTikServer;
use App\Models\Tower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MikroTikServerController extends Controller
{
    public function index()
    {
        $servers = MikroTikServer::where('tenant_id', auth()->user()->tenant_id)->latest()->get();
        return response()->json(['data' => $servers]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'location' => 'nullable|string',
                'uplink_type' => 'nullable|string',
                'uplink_interface' => 'nullable|string',
                'uplink_sending_device_id' => 'nullable|exists:routers,id',
                'uplink_receiving_device_id' => 'nullable|exists:routers,id',
                'uplink_notes' => 'nullable|string',
                'lat' => 'nullable|numeric',
                'lng' => 'nullable|numeric',
                'ip' => 'nullable|ip',
                'api_port' => 'nullable|integer',
                'username' => 'nullable|string',
                'password' => 'nullable|string',
            ]);

            // Default values
            $validated['ip'] = $validated['ip'] ?? '127.0.0.1';
            $validated['api_port'] = $validated['api_port'] ?? 8728;
            
            // Password handling
            $generatedPassword = $validated['password'] ?? \Illuminate\Support\Str::random(16);
            $validated['username'] = $validated['username'] ?? 'madaaq';
            
            $validated['password_encrypted'] = Crypt::encryptString($generatedPassword);
            unset($validated['password']);

            $validated['tenant_id'] = auth()->user()->tenant_id;
            $validated['setup_script_generated'] = true;

            // Generate WireGuard Keys using Sodium
            try {
                $keyPair = sodium_crypto_box_keypair();
                $privateKey = sodium_crypto_box_secretkey($keyPair);
                $publicKey = sodium_crypto_box_publickey($keyPair);
                
                $validated['wireguard_private_key'] = base64_encode($privateKey);
                $validated['wireguard_public_key'] = base64_encode($publicKey);
                $validated['wireguard_enabled'] = true;
            } catch (\Throwable $e) {
                \Log::warning('API WireGuard Key Gen Failed: ' . $e->getMessage());
            }

            $server = MikroTikServer::create($validated);

            // Assign WireGuard IP
             try {
                $ipOctet3 = ($server->id / 255) % 255; 
                $ipOctet4 = $server->id % 255;
                if ($ipOctet4 == 0) $ipOctet4 = 1; 
                if ($ipOctet4 == 255) $ipOctet4 = 254;
                
                $server->wireguard_ip = "201.10.{$ipOctet3}.{$ipOctet4}";
                $server->save();

                 // NOTE: Peer addition handled by Artisan Command
            } catch (\Exception $e) {
                \Log::error('WireGuard IP Assignment Failed: ' . $e->getMessage());
            }

            return response()->json($server, 201);

        } catch (\Exception $e) {
            \Log::error('Server creation failed: ' . $e->getMessage());
            return response()->json(['message' => 'Server creation failed: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $server = MikroTikServer::where('tenant_id', auth()->user()->tenant_id)->findOrFail($id);
        return response()->json($server);
    }

    public function update(Request $request, $id)
    {
        $server = MikroTikServer::where('tenant_id', auth()->user()->tenant_id)->findOrFail($id);
        
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'location' => 'nullable|string',
                'uplink_type' => 'nullable|string',
                'uplink_interface' => 'nullable|string',
                'uplink_sending_device_id' => 'nullable|exists:routers,id',
                'uplink_receiving_device_id' => 'nullable|exists:routers,id',
                'uplink_notes' => 'nullable|string',
                'lat' => 'nullable|numeric',
                'lng' => 'nullable|numeric',
                'ip' => 'nullable|ip',
                'api_port' => 'nullable|integer',
                'username' => 'nullable|string',
                'password' => 'nullable|string',
            ]);

            if (!empty($validated['password'])) {
                $validated['password_encrypted'] = Crypt::encryptString($validated['password']);
            }
            unset($validated['password']);

            $server->update($validated);

            return response()->json($server);

        } catch (\Exception $e) {
             return response()->json(['message' => 'Update failed: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $server = MikroTikServer::where('tenant_id', auth()->user()->tenant_id)->findOrFail($id);
        
        // Remove WireGuard Peer
        if ($server->wireguard_public_key) {
             $cmd = "sudo wg set madaaqip peer {$server->wireguard_public_key} remove";
             shell_exec($cmd);
        }

        $server->delete();
        return response()->json(['message' => 'Server deleted successfully']);
    }

    public function getSetupScript($id)
    {
        $server = MikroTikServer::where('tenant_id', auth()->user()->tenant_id)->findOrFail($id);
        
        $password = '';
        try {
            $password = Crypt::decryptString($server->password_encrypted);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not decrypt password'], 500);
        }

        $wireguardPort = env('WIREGUARD_PORT', 51820);
        $serverPublicKey = trim(env('WIREGUARD_PUBLIC_KEY'), '"');
        
        if (empty($serverPublicKey)) {
            return response()->json(['error' => 'Server config error (PublicKey)'], 500);
        }
        
        $ipOctet3 = ($server->id / 255) % 255; 
        $ipOctet4 = $server->id % 255;
        if ($ipOctet4 == 0) $ipOctet4 = 1; 
        if ($ipOctet4 == 255) $ipOctet4 = 254;

        $vpnIp = "201.10.{$ipOctet3}.{$ipOctet4}";
        $serverVpnIp = "201.10.1.1";
        
        $serverDomain = '173.249.52.218'; // Use the confirmed production host IP
        $syncUrl = "https://madaaq.com/api/servers/{$server->id}/sync";
        
        $apiUser = $server->username;
        $apiPass = $password;

        if ($server->ip === '127.0.0.1' || empty($server->ip)) {
            $server->ip = $vpnIp;
            $server->save();
        }

        $script = <<<SCRIPT
# -----------------------------------------------
# MadaaQ Auto-Configuration Script (Pull Mode)
# -----------------------------------------------

# 1. Reset/Configure WireGuard for MadaaQ
/interface wireguard remove [find name=madaaqip]

/interface wireguard
add listen-port={$wireguardPort} mtu=1420 name=madaaqip private-key="{$server->wireguard_private_key}"

/interface wireguard peers
add allowed-address={$serverVpnIp}/32 endpoint-address={$serverDomain} endpoint-port={$wireguardPort} interface=madaaqip name=madaaq-server persistent-keepalive=25s public-key="{$serverPublicKey}"

# 2. IP Address
/ip address remove [find interface=madaaqip]
/ip address
add address={$vpnIp}/16 interface=madaaqip network=201.10.0.0

# 3. Enable API
/ip service
set api disabled=no port={$server->api_port}

# 4. Create User
/user group add name=madaaq policy=ftp,read,write,test,api
/user remove [find comment="madaaq"]
/user add name={$apiUser} password={$apiPass} address={$serverVpnIp}/32 comment=madaaq group=madaaq

# 5. Cleanup Old/Conflicting Schedulers & Scripts
/system scheduler remove [find name=command]
/system scheduler remove [find name=command-hotspot+]
/system scheduler remove [find name=comand-hotspot+]
/system scheduler remove [find name=madaaq-sync-scheduler]
/system script remove [find name=command]
/system script remove [find name=madaaq-sync-script]

# 6. Create Sync Script (The "Pull" Mechanism)
/system script
add name=madaaq-sync-script source="
/tool fetch http-method=post keep-result=yes url={$syncUrl} dst-path=madaaq_sync.rsc
:delay 2s
/import madaaq_sync.rsc
/file remove madaaq_sync.rsc
"

# 7. Create Scheduler to run the script every 15 seconds
/system scheduler
add interval=15s name=madaaq-sync-scheduler on-event="/system script run madaaq-sync-script" start-time=startup

:log info "✅ Madaaq Setup completed! Polling enabled."
SCRIPT;

        return response()->json([
            'script' => $script,
            'filename' => 'madaaq-setup-' . $server->id . '.rsc'
        ]);
    }
    public function syncServer(MikroTikServer $server)
    {
        // Generate sync script with current users/packages
        $syncScript = $this->generateSyncScript($server);
        
        return response($syncScript)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="server-sync.rsc"');
    }

    private function generateSyncScript($server)
    {
        $script = "; Madaaq Auto-Sync Script\n";
        $script .= "; Generated: " . now()->format('Y-m-d H:i:s') . "\n";
        $script .= "; Server: {$server->name}\n\n";

        // Sync PPPoE Users
        // NOTE: Clients are linked via router_id OR mikrotik_server_id. 
        // Logic: Try matching mikrotik_server_id first, fallback to router_id if logic dictates.
        // Based on findings, we should look for mikrotik_server_id
        
        $pppoeClients = \App\Models\Client::where('type', 'pppoe')
            ->where('status', 'active')
            ->where('mikrotik_server_id', $server->id)
            ->get();

        if ($pppoeClients->count() > 0) {
            $script .= "# PPPoE Users Sync\n";
            foreach ($pppoeClients as $client) {
                // Ensure profile exists or default
                $profile = $client->package ? $client->package->name : 'default';
                
                // Add/Update logic
                $script .= ":if ([:len [/ppp secret find name=\"{$client->username}\"]] = 0) do={ ";
                $script .= "/ppp secret add name=\"{$client->username}\" password=\"{$client->password}\" profile=\"{$profile}\" comment=\"Auto-synced\" ";
                $script .= "} else={ ";
                $script .= "/ppp secret set [find name=\"{$client->username}\"] password=\"{$client->password}\" profile=\"{$profile}\" ";
                $script .= "}\n";
            }
            $script .= "\n";
        } else {
            $script .= "# No PPPoE users assigned to this server\n\n";
        }

        // Sync Hotspot Users
        $hotspotClients = \App\Models\Client::where('type', 'hotspot')
            ->where('status', 'active')
            ->where('mikrotik_server_id', $server->id)
            ->get();

        if ($hotspotClients->count() > 0) {
            $script .= "# Hotspot Users Sync\n";
            foreach ($hotspotClients as $client) {
                $profile = $client->package ? $client->package->name : 'default';
                $script .= ":if ([:len [/ip hotspot user find name=\"{$client->username}\"]] = 0) do={ ";
                $script .= "/ip hotspot user add name=\"{$client->username}\" password=\"{$client->password}\" profile=\"{$profile}\" comment=\"Auto-synced\" ";
                $script .= "} else={ ";
                $script .= "/ip hotspot user set [find name=\"{$client->username}\"] password=\"{$client->password}\" profile=\"{$profile}\" ";
                $script .= "}\n";
            }
            $script .= "\n";
        } else {
            $script .= "# No Hotspot users assigned to this server\n\n";
        }

        // Log
        $script .= ":log info \"Madaaq Sync: {$pppoeClients->count()} PPPoE + {$hotspotClients->count()} Hotspot users synced\"\n";

        return $script;
    }
    public function testConnection(MikroTikServer $server)
    {
        try {
            $ip = $server->ip;
            $port = $server->api_port;

            if (!$ip || $ip === '127.0.0.1' || $ip === 'localhost') {
                 throw new \Exception("Invalid IP or Default Configuration");
            }

            $fp = @fsockopen($ip, $port, $errno, $errstr, 5); 
            
            if (!$fp) {
                throw new \Exception("Connection refused ($errno: $errstr)");
            }
            
            fclose($fp);
            
            $server->update([
                'is_connected' => true,
                'connection_status' => 'connected',
                'last_sync_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => '✅ Connection Successful!'
            ]);

        } catch (\Exception $e) {
            $server->update([
                'is_connected' => false,
                'connection_status' => 'error',
            ]);

            return response()->json([
                'success' => false,
                'message' => '❌ Connection Failed: ' . $e->getMessage()
            ], 200);
        }
    }

    public function importPPPoE(MikroTikServer $server)
    {
        try {
            $service = new \App\Services\MikroTikService($server);
            $users = $service->getPPPoEUsers();
            
            $imported = 0;
            $skipped = 0;
            
            foreach ($users as $user) {
                $exists = \App\Models\Client::where('username', $user['name'] ?? '')->exists();
                
                if (!$exists && isset($user['name'])) {
                    $profileName = $user['profile'] ?? 'default';
                    $package = \App\Models\Package::where('name', $profileName)->where('type', 'pppoe')->first();
                    
                    \App\Models\Client::create([
                        'username' => $user['name'],
                        'password' => $user['password'] ?? '',
                        'type' => 'pppoe',
                        'status' => isset($user['disabled']) && $user['disabled'] === 'true' ? 'disabled' : 'active',
                        'mikrotik_server_id' => $server->id,
                        'package_id' => $package ? $package->id : null,
                        'tenant_id' => auth()->user()->tenant_id ?? 1,
                    ]);
                    $imported++;
                } else {
                    $skipped++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "✅ Imported $imported PPPoE users ($skipped skipped)"
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function importHotspot(MikroTikServer $server)
    {
        try {
            $service = new \App\Services\MikroTikService($server);
            $users = $service->getHotspotUsers();
            
            $imported = 0;
            $skipped = 0;
            
            foreach ($users as $user) {
                $exists = \App\Models\Client::where('username', $user['name'] ?? '')->exists();
                
                if (!$exists && isset($user['name'])) {
                    $profileName = $user['profile'] ?? 'default';
                    $package = \App\Models\Package::where('name', $profileName)->where('type', 'hotspot')->first();

                    \App\Models\Client::create([
                        'username' => $user['name'],
                        'password' => $user['password'] ?? '',
                        'type' => 'hotspot',
                        'status' => isset($user['disabled']) && $user['disabled'] === 'true' ? 'disabled' : 'active',
                        'mikrotik_server_id' => $server->id,
                        'package_id' => $package ? $package->id : null,
                        'tenant_id' => auth()->user()->tenant_id ?? 1,
                    ]);
                    $imported++;
                } else {
                    $skipped++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "✅ Imported $imported Hotspot users ($skipped skipped)"
            ]);
        } catch (\Exception $e) {
             return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getWebfigUrl(MikroTikServer $server)
    {
        // Generate a temporary signed URL or a token
        $token = \Illuminate\Support\Str::random(64);
        
        // Store token in cache for 5 minutes
        \Illuminate\Support\Facades\Cache::put('webfig_token_' . $token, [
            'server_id' => $server->id,
            'user_id' => auth()->id(),
            'tenant_id' => auth()->user()->tenant_id
        ], now()->addMinutes(5));

        // Note: 'webfig.mobile' route needs to be created in web.php
        $url = route('webfig.mobile', ['token' => $token]);
        
        return response()->json(['url' => $url]);
    }
}
