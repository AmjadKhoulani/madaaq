<?php

use App\Models\Router;
use App\Models\MikroTikServer;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "--- MikroTik Servers ---\n";
$servers = MikroTikServer::all();
if ($servers->isEmpty()) echo "No servers found.\n";
foreach ($servers as $s) {
    echo "ID: {$s->id} | Name: {$s->name} | IP: {$s->ip}\n";
}

echo "\n--- Routers ---\n";
$routers = Router::all();
if ($routers->isEmpty()) echo "No routers found.\n";
foreach ($routers as $r) {
    echo "ID: {$r->id} | Name: {$r->name}\n";
}
