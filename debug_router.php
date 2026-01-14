<?php

use App\Models\MikroTikServer;
use App\Services\MikroTikService;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

// Assume we are debugging the main router or the one involved in recent logs (ID 1?)
$server = MikroTikServer::first(); 

if (!$server) {
    die("No MikroTik Server found in DB.\n");
}

echo "=== Debugging Router: {$server->name} ({$server->ip}) ===\n";

try {
    $service = new MikroTikService($server);
    // Open a raw client via reflection or just use service methods if available?
    // Using service methods is safer usually, but we want raw dump.
    // MikroTikService has protected client. We'll perform a new connection manually or use reflection.
    
    // Let's use reflection to access the protected client after calling connect(),
    // OR just use the library classes directly since we have credentials.
    
    $password = decrypt($server->password_encrypted);
    
    $config = new Config([
        'host' => $server->ip,
        'user' => $server->username,
        'pass' => $password,
        'port' => (int) $server->api_port,
        'timeout' => 5,
        'attempts' => 1
    ]);
    
    $client = new Client($config);
    echo "✅ Connected.\n\n";
    
    echo "--- /ip firewall filter print ---\n";
    $filter = $client->query(new Query('/ip/firewall/filter/print'))->read();
    foreach ($filter as $rule) {
        // Filter out boring stuff if too many, but for now print all keys
        echo "Rule ID: " . ($rule['.id'] ?? '?') . 
             " Chain: " . ($rule['chain'] ?? '') . 
             " Action: " . ($rule['action'] ?? '') . 
             " DstPort: " . ($rule['dst-port'] ?? '') . 
             " SrcAddr: " . ($rule['src-address'] ?? '') .
             " Comment: " . ($rule['comment'] ?? '') . "\n";
    }
    echo "\n";
    
    echo "--- /ip firewall nat print ---\n";
    $nat = $client->query(new Query('/ip/firewall/nat/print'))->read();
    foreach ($nat as $rule) {
        echo "Rule ID: " . ($rule['.id'] ?? '?') . 
             " Chain: " . ($rule['chain'] ?? '') . 
             " Action: " . ($rule['action'] ?? '') . 
             " SrcAddr: " . ($rule['src-address'] ?? '') . 
             " DstAddr: " . ($rule['dst-address'] ?? '') . 
             " Comment: " . ($rule['comment'] ?? '') . "\n";
    }
    echo "\n";
    
    echo "--- /ip socks print ---\n";
    $socks = $client->query(new Query('/ip/socks/print'))->read();
    print_r($socks);
    echo "\n";
    
    echo "--- /ip hotspot user profile print ---\n";
    $profiles = $client->query(new Query('/ip/hotspot/user/profile/print'))->read();
    foreach ($profiles as $p) {
        echo "Name: " . ($p['name'] ?? '') . " RateLimit: " . ($p['rate-limit'] ?? 'UNLIMITED') . "\n";
    }

} catch (\Exception $e) {
    echo "❌ Usage Error: " . $e->getMessage() . "\n";
}
