<?php

use App\Models\MikroTikServer;
use App\Http\Controllers\MikroTikServerController;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Mock request host for CLI
$request->headers->set('HOST', 'madaaq.com'); 

echo "--- Testing Setup Script Generation ---\n";

$server = MikroTikServer::where('name', 'hex')->first();

if (!$server) {
    echo "Server 'hex' not found.\n";
    exit;
}

$controller = new MikroTikServerController();
$response = $controller->getSetupScript($server);

echo "Response:\n";
print_r($response->getData(true)); // Print JSON as array
