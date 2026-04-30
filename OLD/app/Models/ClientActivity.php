<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class ClientActivity extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'client_id',
        'action',
        'description',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Log activity for client
     */
    public static function log(Client $client, string $action, string $description, array $metadata = [])
    {
        return static::create([
            'client_id' => $client->id,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }
}
