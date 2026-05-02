<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$controller = new \App\Http\Controllers\MikroTikServerController();
$server = \App\Models\MikroTikServer::find(2);

echo "=== Testing Setup Script Generation ===\n\n";
echo "Server ID: {$server->id}\n";
echo "Server Name: {$server->name}\n\n";

$response = $controller->getSetupScript($server);
$script = $response->getContent();

// Extract just the WireGuard peer creation line
preg_match('/public-key="([^"]+)"/', $script, $matches);

echo "Public Key in Script: " . ($matches[1] ?? 'NOT FOUND') . "\n";
echo "Expected Key: " . env('WIREGUARD_PUBLIC_KEY') . "\n\n";

if (isset($matches[1]) && $matches[1] === env('WIREGUARD_PUBLIC_KEY')) {
    echo "✅ Keys MATCH - Script is correct!\n";
} else {
    echo "❌ Keys DON'T MATCH - There's a problem!\n";
}
