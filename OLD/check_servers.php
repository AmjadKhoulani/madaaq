<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking MikroTik Servers...\n\n";
$servers = \App\Models\MikroTikServer::all();

if ($servers->isEmpty()) {
    echo "❌ NO SERVERS FOUND!\n";
    echo "Please add a server at: https://www.madaaq.com/servers\n";
} else {
    echo "✅ Found " . $servers->count() . " server(s):\n\n";
    foreach ($servers as $server) {
        echo "- {$server->name} (IP: {$server->ip})\n";
    }
}
