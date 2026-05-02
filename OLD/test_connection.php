<?php

use App\Models\MikroTikServer;
use App\Http\Controllers\MikroTikServerController;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "--- Testing Connection to 'hex' ---\n";

$server = MikroTikServer::where('name', 'hex')->first();

if (!$server) {
    echo "Server 'hex' not found.\n";
    exit;
}

$controller = new MikroTikServerController();
$result = $controller->testConnection($server);

echo "Status Code: " . $result->status() . "\n";
echo "Response: " . $result->getContent() . "\n";
