<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Clients
            ['name' => 'view_clients', 'display_name' => 'عرض العملاء', 'group' => 'clients'],
            ['name' => 'create_clients', 'display_name' => 'إضافة عملاء', 'group' => 'clients'],
            ['name' => 'edit_clients', 'display_name' => 'تعديل عملاء', 'group' => 'clients'],
            ['name' => 'delete_clients', 'display_name' => 'حذف عملاء', 'group' => 'clients'],
            
            // Invoices
            ['name' => 'view_invoices', 'display_name' => 'عرض الفواتير', 'group' => 'invoices'],
            ['name' => 'create_invoices', 'display_name' => 'إنشاء فواتير', 'group' => 'invoices'],
            ['name' => 'edit_invoices', 'display_name' => 'تعديل فواتير', 'group' => 'invoices'],
            ['name' => 'delete_invoices', 'display_name' => 'حذف فواتير', 'group' => 'invoices'],
            
            // Reports
            ['name' => 'view_reports', 'display_name' => 'عرض التقارير المالية', 'group' => 'reports'],
            ['name' => 'export_reports', 'display_name' => 'تصدير التقارير', 'group' => 'reports'],
            
            // Network
            ['name' => 'view_routers', 'display_name' => 'عرض معدات الشبكة', 'group' => 'network'],
            ['name' => 'manage_routers', 'display_name' => 'إدارة معدات الشبكة', 'group' => 'network'],
            
            // Settings
            ['name' => 'view_settings', 'display_name' => 'عرض الإعدادات', 'group' => 'settings'],
            ['name' => 'edit_settings', 'display_name' => 'تعديل الإعدادات', 'group' => 'settings'],
            
            // Staff
            ['name' => 'view_staff', 'display_name' => 'عرض الموظفين', 'group' => 'staff'],
            ['name' => 'manage_staff', 'display_name' => 'إدارة الموظفين', 'group' => 'staff'],
            ['name' => 'manage_roles', 'display_name' => 'إدارة الأدوار والصلاحيات', 'group' => 'staff'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                [
                    'display_name' => $permission['display_name'],
                    'group' => $permission['group']
                ]
            );
        }
    }
}
