<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class VendorInvoice extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'amount',
        'plan_name',
        'invoice_number',
        'status',
        'paid_at',
        'expires_at',
        'receipt_image',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
