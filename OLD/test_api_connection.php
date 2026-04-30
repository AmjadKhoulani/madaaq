<?php

use App\Models\MikroTikServer;
use App\Services\MikroTikService;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

$server = MikroTikServer::where('name', 'hex')->first();

echo "=== Testing MikroTik API Connection ===\n";
echo "Server: {$server->name}\n";
echo "IP: {$server->ip}\n";
echo "Port: {$server->api_port}\n";
echo "Username: {$server->username}\n\n";

try {
    $service = new MikroTikService($server->ip, $server->username, decrypt($server->password_encrypted));
    
    if ($service->connect()) {
        echo "✅ Connected successfully!\n";
        $identity = $service->getSystemIdentity();
        echo "Router Identity: " . ($identity['name'] ?? 'Unknown') . "\n";
    } else {
        echo "❌ Connection failed!\n";
    }
} catch (\Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}
