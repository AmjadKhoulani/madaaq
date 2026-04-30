<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class BlockedWebsite extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'domain',
        'type',
        'reason',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
