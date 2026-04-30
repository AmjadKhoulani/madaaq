<?php

use Illuminate\Support\Facades\Hash;
use App\Models\User;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "Checking for superadmin@example.com...\n";

$user = User::where('email', 'superadmin@example.com')->first();

if (!$user) {
    echo "User NOT FOUND. Creating...\n";
    $user = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@example.com',
        'password' => Hash::make('password'),
        'tenant_id' => null,
    ]);
    echo "User created with password: password\n";
} else {
    echo "User FOUND. Resetting password...\n";
    $user->password = Hash::make('password');
    $user->save();
    echo "Password reset to: password\n";
}
