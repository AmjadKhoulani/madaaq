<?php

namespace App\Http\Controllers;

use App\Models\MikroTikServer;
use App\Models\Tower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MikroTikServerController extends Controller
{
    public function index()
    {
        $servers = MikroTikServer::where('tenant_id', auth()->user()->tenant_id ?? 1)->latest()->get();
        return view('servers.index', compact('servers'));
    }

    public function create()
    {
        $internetSources = \App\Models\InternetSource::all();
        $towers = Tower::where('tenant_id', auth()->user()->tenant_id ?? 1)->get();
        $products = \App\Models\DeviceModel::where('manufacturer', 'MikroTik')->get();
        return view('servers.create', compact('internetSources', 'towers', 'products'));
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
                'model_id' => 'nullable|exists:device_models,id',
                // Inputs below are optional now
                'ip' => 'nullable|ip',
                'api_port' => 'nullable|integer',
                'username' => 'nullable|string',
                'password' => 'nullable|string',
            ]);

            // Default values if not provided
            $validated['ip'] = $validated['ip'] ?? '127.0.0.1'; // Dummy
            $validated['api_port'] = $validated['api_port'] ?? 8728;
            
            // Generate credentials if not provided
            $generatedPassword = $validated['password'] ?? \Illuminate\Support\Str::random(16);
            $validated['username'] = $validated['username'] ?? 'madaaq';
            
            // Encrypt password
            $validated['password_encrypted'] = Crypt::encryptString($generatedPassword);
            unset($validated['password']);

            $validated['tenant_id'] = auth()->user()->tenant_id ?? 1;
            $validated['setup_script_generated'] = true;

            // Generate WireGuard Keys using Sodium (No shell_exec needed)
            try {
                $keyPair = sodium_crypto_box_keypair();
                $privateKey = sodium_crypto_box_secretkey($keyPair);
                $publicKey = sodium_crypto_box_publickey($keyPair);
                
                $validated['wireguard_private_key'] = base64_encode($privateKey);
                $validated['wireguard_public_key'] = base64_encode($publicKey);
                $validated['wireguard_enabled'] = true;
            } catch (\Throwable $e) {
                \Log::warning('WireGuard Key Gen Failed: ' . $e->getMessage());
            }

            $server = MikroTikServer::create($validated);

            // Assign WireGuard IP based on ID (Pseudo-deterministic for demo)
            try {
                $ipOctet3 = ($server->id / 255) % 255; 
                $ipOctet4 = $server->id % 255;
                if ($ipOctet4 == 0) $ipOctet4 = 1; 
                if ($ipOctet4 == 255) $ipOctet4 = 254;
                
                $server->wireguard_ip = "201.10.{$ipOctet3}.{$ipOctet4}";
                $server->save();

                // NOTE: Peer addition removed from Controller due to disabled exec()
                // It will be handled by the Artisan Command: sync:wireguard
                \Log::info("Server created. Pending WireGuard Sync for: {$server->name}");

            } catch (\Exception $e) {
                \Log::error('WireGuard IP Assignment Failed: ' . $e->getMessage());
                 session()->flash('warning', 'حدث خطأ في إعدادات الربط: ' . $e->getMessage());
            }



            return redirect()->route('servers.show', $server)
                ->with('success', "✅ تم إضافة السيرفر '{$server->name}' بنجاح!");

        } catch (\Exception $e) {
            \Log::error('Server creation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ حدث خطأ أثناء إضافة السيرفر: ' . $e->getMessage());
        }
    }

    public function edit(MikroTikServer $server)
    {
        $routers = \App\Models\Router::where('tenant_id', auth()->user()->tenant_id ?? 1)->get();
        $internetSources = \App\Models\InternetSource::all();
        $towers = Tower::where('tenant_id', auth()->user()->tenant_id ?? 1)->get();
        return view('servers.edit', compact('server', 'routers', 'internetSources', 'towers'));
    }

    public function update(Request $request, MikroTikServer $server)
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
                'model_id' => 'nullable|exists:device_models,id',
                'ip' => 'nullable|ip',
                'api_port' => 'nullable|integer',
                'username' => 'nullable|string',
                'password' => 'nullable|string',
            ]);

            // Handle password update if provided
            if (!empty($validated['password'])) {
                $validated['password_encrypted'] = Crypt::encryptString($validated['password']);
            }
            unset($validated['password']);

            // If IP changed significantly or we want to reset setup? 
            // For now just update fields
            
            $server->update($validated);

            return redirect()->route('servers.show', $server)
                ->with('success', "✅ تم تحديث بيانات السيرفر بنجاح!");

        } catch (\Exception $e) {
            \Log::error('Server update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ حدث خطأ أثناء تحديث السيرفر: ' . $e->getMessage());
        }
    }

    public function show(MikroTikServer $server)
    {
        return view('servers.show', compact('server'));
    }

    public function getSetupScript(MikroTikServer $server)
    {
        $password = '';
        try {
            $password = Crypt::decryptString($server->password_encrypted);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not decrypt password'], 500);
        }

        // Configuration
        $serverDomain = '167.86.118.97'; // Use the confirmed production IP
        $wireguardPort = config('services.wireguard.port', 51820);
        $serverPublicKey = trim(config('services.wireguard.public_key', ''), '"'); // Strip quotes
        
        // Warning for missing configuration
        $configWarning = empty($serverPublicKey) ? "# WARNING: WIREGUARD_PUBLIC_KEY not configured in .env\n" : "";
        
        // VPN IP for this router (In real app, this should be stored/managed)
        // Using a hash of ID to generate a pseudo-unique IP in 201.10.x.x range for demo
        if ($server->wireguard_ip) {
            $vpnIp = $server->wireguard_ip;
        } else {
            // Fallback for old servers
            $ipOctet3 = ($server->id / 255) % 255; 
            $ipOctet4 = $server->id % 255;
            if ($ipOctet4 == 0) $ipOctet4 = 1; 
            if ($ipOctet4 == 255) $ipOctet4 = 254;
            $vpnIp = "201.10.{$ipOctet3}.{$ipOctet4}";
        }
        $serverVpnIp = "201.10.1.1"; // The central server IP inside VPN
        
        // Explicitly set HTTPS URL to avoid APP_URL or Proxy issues
        $syncUrl = "https://madaaq.com/api/servers/{$server->id}/sync";

        $apiUser = $server->username;
        $apiPass = $password;

        // Auto-update server IP if currently dummy, to ensure connection tests pass after setup
        if ($server->ip === '127.0.0.1' || empty($server->ip)) {
            $server->ip = $vpnIp;
            $server->save();
        }

        $script = <<<SCRIPT
:log info "Starting Madaaq Setup..."

# 1. WireGuard Interface
:if ([:len [/interface wireguard find name=madaaqip]] = 0) do={
    /interface wireguard add listen-port={$wireguardPort} mtu=1420 name=madaaqip private-key="{$server->wireguard_private_key}"
} else={
    /interface wireguard set [find name=madaaqip] listen-port={$wireguardPort} mtu=1420 private-key="{$server->wireguard_private_key}"
}

# 2. WireGuard Peer
:if ([:len [/interface wireguard peers find interface=madaaqip public-key="{$serverPublicKey}"]] = 0) do={
    /interface wireguard peers add allowed-address={$serverVpnIp}/32 endpoint-address={$serverDomain} endpoint-port={$wireguardPort} interface=madaaqip name=madaaq-server persistent-keepalive=25s public-key="{$serverPublicKey}"
}

# 3. IP Address
:if ([:len [/ip address find interface=madaaqip]] = 0) do={
    /ip address add address={$vpnIp}/16 interface=madaaqip network=201.10.0.0
}

# 4. API Service
/ip service set api disabled=no port={$server->api_port} address=""

# 5. User Management
:if ([:len [/user group find name=madaaq]] = 0) do={
    /user group add name=madaaq policy=ftp,read,write,test,api
}
/user remove [find comment="madaaq"]
/user add name={$apiUser} password={$apiPass} comment=madaaq group=madaaq

# 6. Firewall (Optional but recommended for VPN)
/ip firewall filter add chain=input action=accept protocol=tcp dst-port={$server->api_port} src-address=201.10.0.0/16 comment="Allow Madaaq API over VPN" place-before=0

# 6. Cleanup & Sync Agent
/system scheduler remove [find name~"madaaq-sync"]
/system script remove [find name~"madaaq-sync"]

/system script
add name=madaaq-sync-script source="/tool fetch http-method=post keep-result=yes url={$syncUrl} check-certificate=no dst-path=madaaq_sync.rsc; :delay 2s; :if ([:len [/file find name=madaaq_sync.rsc]] > 0) do={ /import madaaq_sync.rsc; /file remove madaaq_sync.rsc }"

/system scheduler
add interval=2m name=madaaq-sync-scheduler on-event="/system script run madaaq-sync-script" start-time=startup

:log info "✅ Madaaq Persistent Agent Enabled"
SCRIPT;

        return response()->json([
            'script' => $script,
            'filename' => 'madaaq-setup-' . $server->id . '.rsc'
        ]);
    }

    public function testConnection(MikroTikServer $server)
    {
        try {
            $ip = $server->ip;
            $port = $server->api_port;

            if (!$ip || $ip === '127.0.0.1' || $ip === 'localhost') {
                 // For local dummy, we can try but usually we want it to fail if it's the "default" setup
                 // unless the user actually is running a mikrotik on localhost (unlikely for this context)
                 throw new \Exception("Invalid IP or Default Configuration");
            }

            $fp = @fsockopen($ip, $port, $errno, $errstr, 10); // Increased timeout for VPN
            
            if (!$fp) {
                throw new \Exception("Connection refused or timed out ($errno: $errstr)");
            }
            
            fclose($fp);

            // If TCP connects, we assume okay for now (or improve to full API login later)
            // But user specifically wants it to FAIL when they add a dummy server.
            
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
            ], 200); // Return 200 so frontend handles it gracefully as a status update
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
                // Check if user already exists in database
                $exists = \App\Models\Client::where('username', $user['name'] ?? '')->exists();
                
                if (!$exists && isset($user['name'])) {
                    // Try to find matching package/profile
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
                'message' => "✅ تم استيراد $imported مستخدم PPPoE ($skipped موجود مسبقاً)"
            ]);
        } catch (\Exception $e) {
            \Log::error('PPPoE Import Failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => '❌ فشل الاستيراد: ' . $e->getMessage()
            ], 500);
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
                // Check if user already exists in database
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
                'message' => "✅ تم استيراد $imported مستخدم Hotspot ($skipped موجود مسبقاً)"
            ]);
        } catch (\Exception $e) {
            \Log::error('Hotspot Import Failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => '❌ فشل الاستيراد: ' . $e->getMessage()
            ], 500);
        }
    }

    public function importPPPoEProfiles(MikroTikServer $server)
    {
        try {
            $service = new \App\Services\MikroTikService($server);
            $profiles = $service->getPPPoEProfiles();
            
            $imported = 0;
            
            foreach ($profiles as $profile) {
                $name = $profile['name'] ?? '';
                if ($name && $name !== 'default' && $name !== 'default-encryption') {
                    // Extract speed from rate-limit if available
                    $rateLimit = $profile['rate-limit'] ?? '';
                    $speeds = $this->parseSpeed($rateLimit);
                    
                    \App\Models\Package::updateOrCreate(
                        ['name' => $name, 'type' => 'pppoe'],
                        [
                            'speed_up' => $speeds['up'],
                            'speed_down' => $speeds['down'],
                            'price' => 0, // Default price for imported profiles
                            'description' => 'Imported from MikroTik',
                            'tenant_id' => auth()->user()->tenant_id ?? 1,
                        ]
                    );
                    $imported++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "✅ تم استيراد $imported بروفايل PPPoE"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ فشل الاستيراد: ' . $e->getMessage()
            ], 500);
        }
    }

    public function importHotspotProfiles(MikroTikServer $server)
    {
        try {
            $service = new \App\Services\MikroTikService($server);
            $profiles = $service->getHotspotProfiles();
            
            $imported = 0;
            
            foreach ($profiles as $profile) {
                $name = $profile['name'] ?? '';
                if ($name && $name !== 'default') {
                    $rateLimit = $profile['rate-limit'] ?? '';
                    $speeds = $this->parseSpeed($rateLimit);

                    \App\Models\Package::updateOrCreate(
                        ['name' => $name, 'type' => 'hotspot'],
                        [
                            'speed_up' => $speeds['up'],
                            'speed_down' => $speeds['down'],
                            'price' => 0, // Default price for imported profiles
                            'description' => 'Imported from MikroTik',
                            'tenant_id' => auth()->user()->tenant_id ?? 1,
                        ]
                    );
                    $imported++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "✅ تم استيراد $imported بروفايل Hotspot"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ فشل الاستيراد: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getServerStatus(MikroTikServer $server)
    {
        try {
            $service = new \App\Services\MikroTikService($server);
            
            $resources = $service->getSystemResources();
            $activePPPoE = $service->getActivePPPoEConnections();
            $activeHotspot = $service->getActiveHotspotConnections();
            
            $resource = $resources[0] ?? [];
            
            $status = [
                'uptime' => $resource['uptime'] ?? 'N/A',
                'version' => $resource['version'] ?? 'N/A',
                'cpu_load' => $resource['cpu-load'] ?? 0,
                'free_memory' => $resource['free-memory'] ?? 0,
                'total_memory' => $resource['total-memory'] ?? 0,
            ];
            $status['active_pppoe'] = count($activePPPoE);
            $status['active_hotspot'] = count($activeHotspot);
            $status['pppoe_users'] = array_slice($activePPPoE, 0, 10);
            $status['hotspot_users'] = array_slice($activeHotspot, 0, 10);

            // Get DNS cache for Top Sites
            try {
                $dnsCache = $service->getDnsCache();
                $domains = [];
                foreach ($dnsCache as $entry) {
                    $name = $entry['name'] ?? '';
                    if ($name && !str_contains($name, '.arpa') && str_contains($name, '.') && !str_contains($name, 'localhost')) {
                        $parts = explode('.', $name);
                        $count = count($parts);
                        if ($count >= 2) {
                            $domain = $parts[$count-2] . '.' . $parts[$count-1];
                            $domains[$domain] = ($domains[$domain] ?? 0) + 1;
                        }
                    }
                }
                arsort($domains);
                $status['top_sites'] = [];
                $i = 0;
                foreach ($domains as $domain => $hits) {
                    $status['top_sites'][] = ['domain' => $domain, 'hits' => $hits];
                    if (++$i >= 10) break;
                }
            } catch (\Exception $e) {
                $status['top_sites'] = [];
            }

            return response()->json([
                'success' => true,
                'data' => $status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ فشل جلب الحالة: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(MikroTikServer $server)
    {
        try {
            // Remove WireGuard Peer
            if ($server->wireguard_public_key) {
                 $cmd = "sudo wg set madaaqip peer {$server->wireguard_public_key} remove";
                 \Log::info("Removing WireGuard Peer: " . $cmd);
                 shell_exec($cmd);
            }

            $server->delete();
            return redirect()->route('servers.index')
                ->with('success', "🗑️ تم حذف السيرفر '{$server->name}' بنجاح");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }

    /**
     * Parse MikroTik rate-limit string to integer Mbps
     */
    private function parseSpeed($rateLimit)
    {
        if (empty($rateLimit)) {
            return ['up' => 0, 'down' => 0];
        }
        
        $parts = explode('/', $rateLimit);
        $rx = $parts[0] ?? '0';
        $tx = $parts[1] ?? $rx; 
        
        return [
            'up' => $this->convertToMbps($rx),
            'down' => $this->convertToMbps($tx),
        ];
    }

    private function convertToMbps($value)
    {
        $numeric = floatval(preg_replace('/[^0-9.]/', '', $value));
        $unit = strtoupper(preg_replace('/[0-9.]/', '', $value));
        
        if (str_contains($unit, 'G')) return (int)($numeric * 1024);
        if (str_contains($unit, 'M')) return (int)$numeric;
        if (str_contains($unit, 'K')) return (int)ceil($numeric / 1024);
        return (int)ceil($numeric / (1024 * 1024)); 
    }
    public function getInterfaces(MikroTikServer $server)
    {
        try {
            $service = new \App\Services\MikroTikService($server);
            $interfaces = $service->getInterfaceList();
            
            $result = array_map(function($iface) {
                return [
                    'name' => $iface['name'] ?? '',
                    'type' => $iface['type'] ?? 'unknown',
                    'disabled' => isset($iface['disabled']) && $iface['disabled'] === 'true'
                ];
            }, $interfaces);
            
            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch interfaces: ' . $e->getMessage()
            ], 500);
        }
    }
}
