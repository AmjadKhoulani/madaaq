<?php

use App\Models\MikroTikServer;
use Illuminate\Support\Facades\Crypt;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

$server = MikroTikServer::first();
if (!$server) die("No server found.");

// Same logic as controller
$wireguardPort = 51820;
$serverPublicKey = trim(env('WIREGUARD_PUBLIC_KEY', 'unknown'));
$ipOctet3 = ($server->id / 255) % 255; 
$ipOctet4 = $server->id % 255;
if ($ipOctet4 == 0) $ipOctet4 = 1; 
if ($ipOctet4 == 255) $ipOctet4 = 254;

$vpnIp = "201.10.{$ipOctet3}.{$ipOctet4}";
$serverVpnIp = "201.10.1.1";
$syncUrl = "https://madaaq.com/api/servers/{$server->id}/sync";
$serverDomain = '173.249.52.218'; // Use the confirmed production host IP

$apiUser = $server->username;
// We need to use the encrypted password we just saved, or re-encrypt the known one.
// Let's assume we can get it from the model if we fixed it earlier.
try {
    $apiPass = decrypt($server->password_encrypted);
} catch (\Exception $e) {
    $apiPass = "T2Lo2sDBvjgP"; // Fallback/Reset if still broken
}

$script = <<<SCRIPT
# -----------------------------------------------
# MadaaQ Auto-Configuration Script (Pull Mode)
# -----------------------------------------------

# 1. Reset/Configure WireGuard for MadaaQ
/interface wireguard remove [find name=mcrmip]
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
/user remove [find comment="mcrm"]
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

echo $script;
