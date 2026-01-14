<?php

use App\Models\Router;
use App\Models\MikroTikServer;
use App\Models\User;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "--- Creating Router Record ---\n";

// 1. Get the existing MikroTik Server to clone details
$server = MikroTikServer::where('name', 'hex')->first();

if (!$server) {
    die("Error: MikroTik Server 'hex' not found. Cannot clone details.\n");
}

echo "Found Server: {$server->name} (ID: {$server->id})\n";

// 2. Check if Router with same ID exists
$router = Router::find($server->id);

if ($router) {
    echo "Router ID {$server->id} already exists: " . $router->name . "\n";
} else {
    // 3. Create Router with SAME ID
    $router = new Router();
    $router->id = $server->id; 
    $router->tenant_id = $server->tenant_id;
    $router->name = $server->name;
    $router->ip = $server->ip;
    $router->api_port = $server->api_port;
    $router->username = $server->username;
    $router->password_encrypted = $server->password_encrypted; // Copy encrypted password directly
    $router->lat = $server->lat;
    $router->lng = $server->lng;
    $router->save();
    
    echo "SUCCESS: Created Router ID {$server->id} ('{$router->name}')\n";
}
