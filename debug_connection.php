<?php

use App\Models\MikroTikServer;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$server = MikroTikServer::where('name', 'hex')->first();
if ($server) {
    echo "Target Server: " . $server->name . "\n";
    echo "Target IP: " . $server->ip . "\n";
    echo "Target Port: " . $server->api_port . "\n";
    
    // Check if we can ping it (simple check)
    // Note: exec might be disabled but worth a try or just explain logic
    echo "\nPinging...\n";
    system("ping -c 2 -W 1 " . $server->ip);
} else {
    echo "Server 'hex' not found.\n";
}
