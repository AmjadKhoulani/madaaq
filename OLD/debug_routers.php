<?php

use App\Models\Router;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$routers = Router::with('deviceModel')->get();

foreach ($routers as $router) {
    echo "Router ID: " . $router->id . "\n";
    echo "Name: " . $router->name . "\n";
    echo "Model ID: " . $router->model_id . "\n";
    
    if ($router->deviceModel) {
        echo "Manufacturer: " . $router->deviceModel->manufacturer . "\n";
        $isMikroTik = stripos($router->deviceModel->manufacturer, 'MikroTik') !== false;
        echo "Is MikroTik: " . ($isMikroTik ? 'Yes' : 'No') . "\n";
    } else {
        echo "Device Model: NULL\n";
    }
    echo "--------------------------------\n";
}
