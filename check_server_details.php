<?php

use App\Models\MikroTikServer;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "--- MikroTik Server Details ---\n";
$server = MikroTikServer::where('name', 'hex')->first();

if ($server) {
    echo "Name: {$server->name}\n";
    echo "IP: {$server->ip}\n";
    echo "API Port: {$server->api_port}\n";
    echo "Username: {$server->username}\n";
    echo "Connection Status: {$server->connection_status}\n";
} else {
    echo "Server not found\n";
}
