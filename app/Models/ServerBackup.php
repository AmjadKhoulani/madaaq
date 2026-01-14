<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerBackup extends Model
{
    protected $fillable = [
        'mikrotik_server_id',
        'filename',
        'path',
        'size',
        'type',
        'status',
    ];

    public function server()
    {
        return $this->belongsTo(MikroTikServer::class, 'mikrotik_server_id');
    }
}
