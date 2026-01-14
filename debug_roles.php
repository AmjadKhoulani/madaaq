<?php

use App\Models\User;
use App\Models\Role;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "--- Debugging User Roles ---\n";

$user = User::where('email', 'superadmin@example.com')->first();

if (!$user) {
    echo "ERROR: User 'superadmin@example.com' not found!\n";
    exit(1);
}

echo "User found: ID " . $user->id . "\n";

$role = Role::where('name', 'super_admin')->first();

if (!$role) {
    echo "WARNING: Role 'super_admin' NOT FOUND in database. Creating it...\n";
    $role = Role::create([
        'name' => 'super_admin',
        'label' => 'Super Administrator',
        'tenant_id' => null
    ]);
    echo "Role 'super_admin' created.\n";
} else {
    echo "Role 'super_admin' found: ID " . $role->id . "\n";
}

if ($user->hasRole('super_admin')) {
    echo "SUCCESS: User ALREADY has 'super_admin' role.\n";
    echo "Roles attached: " . $user->roles->pluck('name')->implode(', ') . "\n";
} else {
    echo "User does NOT have 'super_admin' role. Assigning now...\n";
    // Manually attach to be sure, using the specific pivot table logic from the seeder if needed, 
    // or just the relationship if it works standardly.
    // Seeing User.php: return $this->morphToMany(Role::class, 'model', 'model_has_roles', 'model_id', 'role_id');
    try {
        $user->roles()->syncWithoutDetaching([$role->id => ['model_type' => get_class($user)]]);
        echo "Role attached successfully.\n";
    } catch (\Exception $e) {
         echo "Error attaching role: " . $e->getMessage() . "\n";
    }
}

// Final Verification
$user->refresh();
if ($user->hasRole('super_admin')) {
    echo "\nVERIFICATION PASSED: User has 'super_admin' role.\n";
} else {
    echo "\nVERIFICATION FAILED: User still does not have access.\n";
}
