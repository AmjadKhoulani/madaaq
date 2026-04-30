<?php
require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\Crypt;
use RouterOS\Client;
use RouterOS\Config;

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get all routers
$routers = \App\Models\Router::all();

echo "=== Testing Router Connections ===\n\n";

foreach ($routers as $router) {
    echo "Router: {$router->name} (ID: {$router->id})\n";
    echo "IP: {$router->ip}\n";
    echo "Username: {$router->username}\n";
    echo "API Port: {$router->api_port}\n";
    echo "Has password field: " . ($router->password ? 'Yes' : 'No') . "\n";
    echo "Has password_encrypted field: " . ($router->password_encrypted ? 'Yes' : 'No') . "\n";
    
    // Try to decrypt password
    try {
        if ($router->password_encrypted) {
            $decryptedPassword = Crypt::decryptString($router->password_encrypted);
            echo "Password decrypted: " . str_repeat('*', strlen($decryptedPassword)) . " (length: " . strlen($decryptedPassword) . ")\n";
            
            // Test connection
            echo "Testing connection...\n";
            $config = new Config([
                'host' => $router->ip,
                'user' => $router->username,
                'pass' => $decryptedPassword,
                'port' => (int) $router->api_port,
            ]);
            
            $client = new Client($config);
            echo "✅ Connection successful!\n";
            
        } else if ($router->password) {
            echo "Using plain password field: {$router->password}\n";
            
            // Test connection with plain password
            echo "Testing connection...\n";
            $config = new Config([
                'host' => $router->ip,
                'user' => $router->username,
                'pass' => $router->password,
                'port' => (int) $router->api_port,
            ]);
            
            $client = new Client($config);
            echo "✅ Connection successful!\n";
        } else {
            echo "❌ No password found!\n";
        }
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n" . str_repeat("-", 50) . "\n\n";
}
