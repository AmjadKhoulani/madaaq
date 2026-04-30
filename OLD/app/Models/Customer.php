<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Customer extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'national_id',
        'address',
        'city',
        'lat',
        'lng',
        'notes',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Client::class, 'customer_id');
    }
}
