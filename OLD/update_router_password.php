<?php

use App\Models\MikroTikServer;
use Illuminate\Support\Facades\Crypt;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

// We will update the first server for now as it seems to be the one in use
$server = MikroTikServer::first();

if (!$server) {
    die("No server found.\n");
}

echo "Updating server: {$server->name}\n";
// CAUTION: We need the REAL password. 
// Since I don't know it, I will ask the user or try a common one if I saw it in logs?
// No, I shouldn't guess. 
// However, I can check if I can 'peek' at the raw value to see if it looks wrong?
// Or I can ask the user. 
// But first, let's see if the value is just NOT encrypted properly (e.g. plain text).

try {
    $decrypted = decrypt($server->password_encrypted);
    echo "Current password decrypts OK: $decrypted\n";
} catch (\Exception $e) {
    echo "Current password fails decryption: " . $e->getMessage() . "\n";
    echo "Raw value start: " . substr($server->password_encrypted, 0, 20) . "...\n";
}

// If the user hasn't provided the password, I can't reset it to the CORRECT value.
// BUT, I can ask the user to input it via a form, or I can provide a script for them to run.
// Or, if this is a dev environment, maybe I can find it in a seeder?

// Wait, the user said "Sync is gone".
// Checking previous history/context...
// I don't have the password.

// I will create a script that TAKES an argument to update it.
if (isset($argv[1])) {
    $newPassword = $argv[1];
    $server->password_encrypted = encrypt($newPassword);
    $server->save();
    echo "✅ Password updated successfully.\n";
} else {
    echo "Usage: php update_router_password.php <new_password>\n";
}
