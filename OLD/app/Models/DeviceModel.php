<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceModel extends Model
{
    protected $fillable = [
        'manufacturer',
        'model_name',
        'device_type',
        'default_coverage_radius',
        'frequency',
        'max_throughput',
        'description',
        'image_url',
    ];

    public function routers()
    {
        return $this->hasMany(Router::class, 'model_id');
    }
}
