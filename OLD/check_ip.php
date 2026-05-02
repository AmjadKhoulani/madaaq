<?php

use App\Models\MikroTikServer;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$server = MikroTikServer::where('name', 'hex')->first();
if ($server) {
    echo "Server Name: " . $server->name . "\n";
    echo "Server IP: " . $server->ip . "\n";
} else {
    echo "Server 'hex' not found.\n";
}
