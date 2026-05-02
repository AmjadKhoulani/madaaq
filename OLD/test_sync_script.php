<?php

use App\Models\Router;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "--- Testing Sync Script Generation ---\n\n";

$router = Router::where('name', 'hex')->first();
if (!$router) {
    die("Router 'hex' not found.\n");
}

$controller = new \App\Http\Controllers\RouterManagementController();
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('generateSyncScript');
$method->setAccessible(true);

$script = $method->invoke($controller, $router);

echo "Generated Script:\n";
echo "==================\n";
echo $script;
echo "==================\n";
echo "\nScript length: " . strlen($script) . " bytes\n";
