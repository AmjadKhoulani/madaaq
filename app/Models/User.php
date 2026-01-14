<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, \App\Traits\BelongsToTenant, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'trial_ends_at',
        'subscription_ends_at',
        'subscription_status',
        'tenant_id',
        'plan_name',
    ];

    /**
     * Feature Definitions
     */
    protected $features = [
        'basic_annual' => [
            'dashboard', 
            'clients', 
            'basic_network', 
            'basic_invoices',
            // Now included in Basic:
            'network_topology',
            'live_monitoring',
            'financial_reports',
            'marketing_campaigns',
            'advanced_scripts',
            'unlimited_staff'
        ],
        'pro_annual' => [
            'dashboard', 
            'clients', 
            'basic_network',
            'basic_invoices',
            'network_topology',
            'live_monitoring',
            'financial_reports',
            'marketing_campaigns',
            'advanced_scripts',
            'unlimited_staff',
            // Pro Exclusive:
            'branded_mobile_app', 
        ],
    ];

    public function hasFeature($feature)
    {
        // Admins have all access
        if ($this->hasRole('admin')) {
            return true;
        }

        // Check if subscription is active
        if ($this->subscription_status !== 'active' && now()->gt($this->trial_ends_at)) {
            return false;
        }

        $plan = $this->plan_name ?? 'basic_annual'; // Default or fallback
        $allowed = $this->features[$plan] ?? [];

        return in_array($feature, $allowed);
    }

    public function isPro()
    {
        return $this->plan_name === 'pro_annual' && ($this->subscription_status === 'active' || $this->onTrial());
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'trial_ends_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
        ];
    }

    /**
     * Check if the user is on a generic trial.
     */
    public function onTrial() {
        return ($this->trial_ends_at && $this->trial_ends_at->isFuture()) || $this->subscription_status === 'trial';
    }

    /**
     * Check if the user has an active subscription.
     */
    public function hasActiveSubscription() {
        return $this->subscription_ends_at && $this->subscription_ends_at->isFuture();
    }

    /**
     * Check if user has access (Trial OR Subscription).
     */
    public function hasAccess() {
        return $this->onTrial() || $this->hasActiveSubscription();
    }

    // Relationships
    public function roles()
    {
        return $this->morphToMany(Role::class, 'model', 'model_has_roles', 'model_id', 'role_id');
    }

    public function towers()
    {
        return $this->hasMany(Tower::class, 'responsible_user_id');
    }

    // Permission Methods
    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return $this->roles->contains($role);
    }

    public function hasPermission($permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }

        $this->roles()->syncWithoutDetaching($role);
    }
}
