<?php

use App\Models\MikroTikServer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "--- Debugging Server View (ID: 1) ---\n";

try {
    // 1. Simulate Auth
    $user = User::where('email', 'superadmin@example.com')->first();
    if (!$user) {
        die("Super Admin not found");
    }
    Auth::login($user);

    // 2. Fetch Server
    $server = MikroTikServer::find(1);
    if (!$server) {
        die("Server 1 not found");
    }
    echo "Server found: " . $server->name . "\n";

    // 3. Render View
    $html = View::make('servers.show', compact('server'))->render();
    
    echo "SUCCESS: View rendered successfully.\n";
    // echo substr($html, 0, 500) . "...\n"; 

} catch (\Exception $e) {
    echo "ERROR Rendering View:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    // echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
