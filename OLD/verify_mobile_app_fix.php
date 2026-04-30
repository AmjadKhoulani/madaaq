<?php

use App\Models\MobileApp;
use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Checking MobileApp columns...\n";
    $columns = Schema::getColumnListing('mobile_apps');
    // echo "Columns found: " . implode(', ', $columns) . "\n";

    echo "Attempting to create a MobileApp record...\n";
    
    // Clean up previous test record if exists
    MobileApp::where('package_name', 'com.test.app')->delete();

    $mobileApp = MobileApp::create([
        'tenant_id' => 1,
        'app_name' => 'Test App',
        'app_name_en' => 'Test App EN',
        'package_name' => 'com.test.app',
        'description' => 'This is a test app description.',
        'icon_path' => 'path/to/icon.png',
        'primary_color' => '#ffffff',
        'secondary_color' => '#000000',
        'contact_email' => 'test@example.com',
        'contact_phone' => '1234567890',
        'website' => 'https://example.com',
        'status' => 'pending',
        'is_paid' => false,
        'aab_file_path' => 'path/to/app.aab',
        'submitted_at' => now(),
    ]);

    echo "Successfully created MobileApp with ID: " . $mobileApp->id . "\n";
    echo "Status Badge: " . $mobileApp->status_badge . "\n";
    
    // Clean up
    $mobileApp->delete();
    echo "Test record deleted.\n";
    echo "VERIFICATION SUCCESSFUL\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
    exit(1);
}
