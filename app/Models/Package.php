<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Package extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'speed_down',
        'speed_up',
        'price',
        'duration_days',
        'data_limit_mb',
    ];
    public function routers()
    {
        return $this->morphedByMany(Router::class, 'device', 'package_target_devices');
    }

    public function mikrotikServers()
    {
        return $this->morphedByMany(MikroTikServer::class, 'device', 'package_target_devices');
    }
}
