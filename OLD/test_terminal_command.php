<?php
require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\Crypt;

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Terminal Command Execution\n\n";

$server = \App\Models\MikroTikServer::find(2); // hex server

if (!$server) {
    echo "Server not found!\n";
    exit;
}

echo "Server: {$server->name}\n";
echo "IP: {$server->ip}\n";
echo "Username: {$server->username}\n\n";

try {
    // Decrypt password
    $password = Crypt::decryptString($server->password_encrypted);
    echo "✓ Password decrypted successfully\n\n";
    
    // Connect
    $config = new \RouterOS\Config([
        'host' => $server->ip,
        'user' => $server->username,
        'pass' => $password,
        'port' => (int) $server->api_port,
    ]);
    
    $client = new \RouterOS\Client($config);
    echo "✓ Connected to server\n\n";
    
    // Execute command
    $query = new \RouterOS\Query('/ip/address/print');
    $response = $client->query($query)->read();
    
    echo "✓ Command executed\n\n";
    echo "Response:\n";
    print_r($response);
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
