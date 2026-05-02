<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class WebsiteAnalytic extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'domain',
        'hits',
        'bytes',
        'recorded_date',
    ];

    protected $casts = [
        'recorded_date' => 'date',
        'hits' => 'integer',
        'bytes' => 'integer',
    ];
}
