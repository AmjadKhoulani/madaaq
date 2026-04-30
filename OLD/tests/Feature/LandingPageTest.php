<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_landing_page()
    {
        $response = $this->get('/');
        if ($response->status() === 302) {
            dump($response->headers->get('Location'));
        }
        $response->assertStatus(200);
        $response->assertViewIs('welcome');
        $response->assertSee('مدى كيو');
        $response->assertSee('تسجيل الدخول');
    }

    public function test_authenticated_user_can_view_landing_page()
    {
        $user = User::factory()->create([
            'subscription_ends_at' => now()->addMonth(),
            'subscription_status' => 'active',
            'plan_name' => 'basic_annual'
        ]);
        
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }
}
