<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'tenant_id' => null, // Super Admin has no tenant
            ]
        );

        // Assign Role
        $role = Role::where('name', 'super_admin')->whereNull('tenant_id')->first();
        if ($role) {
            // Check if user already has role to avoid duplication if running multiple times
            if (!$admin->roles()->where('role_id', $role->id)->exists()) {
                $admin->roles()->attach($role->id, ['model_type' => get_class($admin)]);
            }
        }
    }
}
