<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\AdminSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_settings_page()
    {
        $role = \App\Models\Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole($role);
        
        $response = $this->actingAs($user)->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertSee('Payment Settings');
    }

    public function test_admin_can_update_settings()
    {
        $role = \App\Models\Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($user)->post(route('admin.settings.update'), [
            'sham_cash_instructions' => 'Send money here',
            'stripe_key' => 'pk_test_123'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('Send money here', AdminSetting::get('sham_cash_instructions'));
        $this->assertEquals('pk_test_123', AdminSetting::get('stripe_key'));
    }
}
