<?php

use App\Models\User;
use App\Models\Role;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::where('email', 'admin@madaaq.com')->first();
if (!$user) {
    die("User not found.\n");
}

$superAdminRole = Role::where('name', 'super_admin')->first();
if (!$superAdminRole) {
    die("Super Admin role not found.\n");
}

$user->roles()->syncWithoutDetaching([$superAdminRole->id]);

echo "User {$user->email} is now a Super Admin. They will be redirected to /admin on login.\n";
