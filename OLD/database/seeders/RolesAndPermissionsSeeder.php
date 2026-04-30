<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // Clients
            ['name' => 'view_clients', 'display_name' => 'عرض العملاء'],
            ['name' => 'create_clients', 'display_name' => 'إضافة عملاء'],
            ['name' => 'edit_clients', 'display_name' => 'تعديل عملاء'],
            ['name' => 'delete_clients', 'display_name' => 'حذف عملاء'],
            
            // Billing
            ['name' => 'view_invoices', 'display_name' => 'عرض الفواتير'],
            ['name' => 'create_invoices', 'display_name' => 'إنشاء فواتير'],
            ['name' => 'edit_invoices', 'display_name' => 'تعديل فواتير'],
            ['name' => 'delete_invoices', 'display_name' => 'حذف فواتير'],
            ['name' => 'view_reports', 'display_name' => 'عرض التقارير'],
            
            // Network
            ['name' => 'view_routers', 'display_name' => 'عرض الأجهزة'],
            ['name' => 'create_routers', 'display_name' => 'إضافة أجهزة'],
            ['name' => 'edit_routers', 'display_name' => 'تعديل أجهزة'],
            ['name' => 'delete_routers', 'display_name' => 'حذف أجهزة'],
            ['name' => 'view_towers', 'display_name' => 'عرض الأبراج'],
            ['name' => 'create_towers', 'display_name' => 'إضافة أبراج'],
            ['name' => 'edit_towers', 'display_name' => 'تعديل أبراج'],
            ['name' => 'delete_towers', 'display_name' => 'حذف أبراج'],
            
            // CRM
            ['name' => 'view_crm', 'display_name' => 'عرض CRM'],
            ['name' => 'send_campaigns', 'display_name' => 'إرسال حملات'],
            
            // Settings
            ['name' => 'manage_settings', 'display_name' => 'إدارة الإعدادات'],
            ['name' => 'manage_staff', 'display_name' => 'إدارة الموظفين'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        // Create Global Roles (System Wide)
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web'],
            [
                'tenant_id' => null, // Global
                'display_name' => 'Super Admin',
                'description' => 'System Administrator',
            ]
        );
        $superAdminRole->permissions()->sync(Permission::all());

        // Default roles for tenant_id = 1 (other tenants will get these via Tenant Observer)
        $this->createDefaultRolesForTenant(1);
    }

    /**
     * Create default roles for a tenant
     */
    public function createDefaultRolesForTenant($tenantId)
    {
        // 1. مدير بصلاحيات كاملة (System Administrator)
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'tenant_id' => $tenantId],
            [
                'display_name' => 'مدير بصلاحيات كاملة',
                'description' => 'صلاحيات كاملة على جميع أقسام النظام',
                'guard_name' => 'web'
            ]
        );
        $adminRole->permissions()->sync(Permission::all());

        // 2. مدير (Manager)
        $managerRole = Role::firstOrCreate(
            ['name' => 'manager', 'tenant_id' => $tenantId],
            [
                'display_name' => 'مدير',
                'description' => 'صلاحيات إدارية على العملاء والشبكة والمالية',
                'guard_name' => 'web'
            ]
        );
        $managerPerms = ['view_clients', 'create_clients', 'edit_clients', 'delete_clients',
                        'view_invoices', 'create_invoices', 'edit_invoices', 'delete_invoices', 'view_reports',
                        'view_routers', 'create_routers', 'edit_routers',  'view_towers', 'edit_towers',
                        'view_crm', 'send_campaigns'];
        $managerRole->permissions()->sync(Permission::whereIn('name', $managerPerms)->get());

        // 3. تقني (Technical)
        $techRole = Role::firstOrCreate(
            ['name' => 'technician', 'tenant_id' => $tenantId],
            [
                'display_name' => 'تقني',
                'description' => 'إدارة الشبكة والأجهزة والتوصيلات الفنية',
                'guard_name' => 'web'
            ]
        );
        $techPerms = ['view_routers', 'create_routers', 'edit_routers',  'delete_routers',
                     'view_towers', 'create_towers', 'edit_towers', 'delete_towers',
                     'view_clients', 'edit_clients'];
        $techRole->permissions()->sync(Permission::whereIn('name', $techPerms)->get());

        // 4. مالي (Financial)
        $financialRole = Role::firstOrCreate(
            ['name' => 'accountant', 'tenant_id' => $tenantId],
            [
                'display_name' => 'مالي',
                'description' => 'إدارة الفواتير والمدفوعات والتقارير المالية',
                'guard_name' => 'web'
            ]
        );
        $financialPerms = ['view_invoices', 'create_invoices', 'edit_invoices', 'delete_invoices', 
                          'view_reports', 'view_clients'];
        $financialRole->permissions()->sync(Permission::whereIn('name', $financialPerms)->get());

        // 5. مسوق (Marketing)
        $marketingRole = Role::firstOrCreate(
            ['name' => 'marketer', 'tenant_id' => $tenantId],
            [
                'display_name' => 'مسوّق',
                'description' => 'إدارة الحملات التسويقية والعملاء المحتملين',
                'guard_name' => 'web'
            ]
        );
        $marketingPerms = ['view_crm', 'send_campaigns', 'view_clients', 'create_clients', 'view_invoices'];
        $marketingRole->permissions()->sync(Permission::whereIn('name', $marketingPerms)->get());

        // 6. موظف متابعة الاشتراكات (Subscriptions Follow-up)
        $subscriptionsRole = Role::firstOrCreate(
            ['name' => 'subscriptions_followup', 'tenant_id' => $tenantId],
            [
                'display_name' => 'موظف متابعة اشتراكات',
                'description' => 'متابعة تجديد الاشتراكات وتفعيل العملاء',
                'guard_name' => 'web'
            ]
        );
        $subsPerms = ['view_clients', 'edit_clients', 'view_invoices', 'create_invoices', 'send_campaigns'];
        $subscriptionsRole->permissions()->sync(Permission::whereIn('name', $subsPerms)->get());
    }
}
