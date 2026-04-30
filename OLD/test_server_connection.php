<?php
require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\Crypt;

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get all MikroTik servers
$servers = \App\Models\MikroTikServer::all();

echo "=== Testing MikroTik Server Connections ===\n\n";

if ($servers->isEmpty()) {
    echo "❌ No servers found in database!\n";
    echo "Please add servers at: https://www.madaaq.com/servers\n";
    exit;
}

foreach ($servers as $server) {
    echo "Server: {$server->name} (ID: {$server->id})\n";
    echo "IP: {$server->ip}\n";
    echo "Username: {$server->username}\n";
    echo "API Port: {$server->api_port}\n";
    
    // Try to decrypt password
    try {
        if ($server->password_encrypted) {
            $decryptedPassword = Crypt::decryptString($server->password_encrypted);
            echo "Password encrypted: ✓ (length: " . strlen($decryptedPassword) . ")\n";
            
            // Test connection
            echo "Testing connection...\n";
            $config = new \RouterOS\Config([
                'host' => $server->ip,
                'user' => $server->username,
                'pass' => $decryptedPassword,
                'port' => (int) $server->api_port,
            ]);
            
            $client = new \RouterOS\Client($config);
            $query = new \RouterOS\Query('/system/identity/print');
            $response = $client->query($query)->read();
            
            echo "✅ Connection successful!\n";
            if (!empty($response)) {
                echo "Identity: " . ($response[0]['name'] ?? 'Unknown') . "\n";
            }
            
        } else {
            echo "❌ No password_encrypted found!\n";
        }
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n" . str_repeat("-", 50) . "\n\n";
}
