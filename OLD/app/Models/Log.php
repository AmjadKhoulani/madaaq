<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Log extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'action',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
