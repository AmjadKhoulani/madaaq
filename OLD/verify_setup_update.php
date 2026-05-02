<?php

use App\Models\MikroTikServer;
use App\Http\Controllers\MikroTikServerController;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "--- Verifying Madaaq Setup Script ---\n";

$server = MikroTikServer::where('name', 'hex')->first();

if (!$server) {
    // Create dummy if needed
    $server = MikroTikServer::create([
        'name' => 'hex',
        'ip' => '127.0.0.1',
        'api_port' => 8728,
        'username' => 'api_test',
        'password_encrypted' => \Illuminate\Support\Facades\Crypt::encryptString('secret'),
        'tenant_id' => 1,
        'setup_script_generated' => true
    ]);
}

$controller = new MikroTikServerController();
$response = $controller->getSetupScript($server);
$data = $response->getData(true);

echo "Script Output:\n";
echo $data['script'] . "\n";
