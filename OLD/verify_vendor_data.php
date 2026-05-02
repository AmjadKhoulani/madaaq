<?php

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "Starting Vendor Data Verification...\n";

// 1. Test Tenant Creation with new fields
$domain = 'verifymode' . rand(100, 999);
echo "Creating Tenant with domain: $domain\n";

try {
    $tenant = Tenant::create([
        'name' => 'Verification Company',
        'domain' => $domain,
        'support_contact' => 'verify@support.com',
        'billing_address' => '123 Verification St, Test City',
        'tax_number' => 'TAX-999000',
        'payment_method' => 'bank_transfer',
        'status' => 'active'
    ]);

    echo "[SUCCESS] Tenant created. ID: " . $tenant->id . "\n";
    echo "  - Billing Address: " . ($tenant->billing_address === '123 Verification St, Test City' ? 'MATCH' : 'MISMATCH') . "\n";
    echo "  - Tax Number: " . ($tenant->tax_number === 'TAX-999000' ? 'MATCH' : 'MISMATCH') . "\n";

} catch (Exception $e) {
    echo "[FAILURE] Tenant creation failed: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Test User Creation with phone
$email = 'owner_' . rand(100, 999) . '@verify.com';
echo "Creating User with email: $email\n";

try {
    $user = User::create([
        'name' => 'Verify Owner',
        'email' => $email,
        'phone' => '+1234567890',
        'password' => Hash::make('password'),
        'tenant_id' => $tenant->id,
        'role' => 'owner'
    ]);

    echo "[SUCCESS] User created. ID: " . $user->id . "\n";
    echo "  - Phone: " . ($user->phone === '+1234567890' ? 'MATCH' : 'MISMATCH') . "\n";

} catch (Exception $e) {
    echo "[FAILURE] User creation failed: " . $e->getMessage() . "\n";
}
