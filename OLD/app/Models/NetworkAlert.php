<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class NetworkAlert extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'device_type',
        'device_id',
        'alert_type',
        'message',
        'severity',
        'is_resolved',
        'resolved_at',
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    // Get the device (polymorphic)
    public function device()
    {
        return $this->morphTo('device', 'device_type', 'device_id');
    }

    // Mark alert as resolved
    public function resolve()
    {
        $this->update([
            'is_resolved' => true,
            'resolved_at' => now(),
        ]);
    }
}
