<?php

use App\Models\AdminSetting;
use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Ensure the table exists
if (!Schema::hasTable('admin_settings')) {
    echo "Creating admin_settings table...\n";
    Schema::create('admin_settings', function ($table) {
        $table->id();
        $table->string('key')->unique();
        $table->text('value')->nullable();
        $table->timestamps();
    });
}

$settings = [
    'stripe_active' => '0', // Disable stripe for now as we don't have keys
    'paypal_active' => '0', 
    'sham_cash_active' => '1',
    'syriatel_active' => '1',
    'turkish_active' => '1',
    
    'sham_cash_instructions' => "يرجى تحويل المبلغ إلى حساب 'شام كاش' على الرقم: 0912345678\nثم إرفاق صورة الإشعار.",
    'syriatel_cash_instructions' => "يرجى تحويل المبلغ إلى حساب 'سيريتيل كاش' على الرقم: 0987654321\nثم إرفاق صورة الإشعار.",
    'turkish_bank_details' => "Ziraat Bank\nIBAN: TR12 3456 7890 1234 5678 90\nName: Madaaq Global",
];

foreach ($settings as $key => $value) {
    AdminSetting::updateOrCreate(
        ['key' => $key],
        ['value' => $value]
    );
    echo "Updated $key\n";
}

echo "Payment methods enabled successfully.\n";
