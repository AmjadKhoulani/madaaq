<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Subscription extends Model
{
    use BelongsToTenant;

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected $fillable = [
        'tenant_id',
        'client_id',
        'package_id',
        'expires_at',
        'status',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
