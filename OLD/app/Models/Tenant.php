<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'logo',
        'primary_color',
        'mobile_app_name',
        'support_contact',
        'billing_address',
        'payment_method',
        'tax_number',
        'plan_id',
        'status',
        'is_subdomain_enabled',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_subdomain_enabled' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
