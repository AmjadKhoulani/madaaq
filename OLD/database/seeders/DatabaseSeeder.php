<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Flush WireGuard peers to prevent IP conflicts after reset
        $this->command->info('Flushing WireGuard peers...');
        \Illuminate\Support\Facades\Artisan::call('wireguard:flush');
        $this->command->info(\Illuminate\Support\Facades\Artisan::output());

        $tenant = \App\Models\Tenant::firstOrCreate(
            ['domain' => 'demo.test'],
            ['name' => 'Demo ISP', 'status' => 'active']
        );

        // Run Permissions System First
        $this->call([
            \Database\Seeders\RolesAndPermissionsSeeder::class,
            \Database\Seeders\NotificationTemplateSeeder::class,
            \Database\Seeders\DeviceModelSeeder::class,
            \Database\Seeders\TowerDevicesSeeder::class,
        ]);

        $user = User::updateOrCreate(
            ['email' => 'admin@madaaq.com'],
            [
                'name' => 'Amjad Khoulani',
                'password' => bcrypt('password'),
                'tenant_id' => $tenant->id,
            ]
        );

        // Assign 'admin' role (created by RolesAndPermissionsSeeder)
        $adminRole = Role::where('name', 'admin')->where('tenant_id', $tenant->id)->first();
        if ($adminRole) {
            $user->assignRole($adminRole);
        }

        // Assign 'super_admin' role for global access (Admin Panel)
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $user->assignRole($superAdminRole);
        }

        // Default Settings
        \App\Models\Setting::updateOrCreate(
            ['key' => 'currency', 'tenant_id' => $tenant->id],
            ['value' => 'ل.س']
        );
        \App\Models\Setting::updateOrCreate(
            ['key' => 'company_name', 'tenant_id' => $tenant->id],
            ['value' => 'Madaaq']
        );

        // Global Admin Settings (Payment Gateways)
        $adminSettings = [
            'stripe_active' => '0',
            'paypal_active' => '0', 
            'sham_cash_active' => '1',
            'syriatel_active' => '1',
            'turkish_active' => '1',
            
            'sham_cash_instructions' => "يرجى تحويل المبلغ إلى حساب 'شام كاش' على الرقم: 0912345678\nثم إرفاق صورة الإشعار.",
            'syriatel_cash_instructions' => "يرجى تحويل المبلغ إلى حساب 'سيريتيل كاش' على الرقم: 0987654321\nثم إرفاق صورة الإشعار.",
            'turkish_bank_details' => "Ziraat Bank\nIBAN: TR12 3456 7890 1234 5678 90\nName: Madaaq Global",
        ];
        
        foreach ($adminSettings as $key => $value) {
            \App\Models\AdminSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
