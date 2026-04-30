<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bandwidth_logs', function (Blueprint $table) {
            $table->id();
            $table->string('device_type'); // Router::class, MikroTikServer::class
            $table->unsignedBigInteger('device_id');
            $table->string('interface_name');
            $table->bigInteger('rx_bytes')->default(0); // Download
            $table->bigInteger('tx_bytes')->default(0); // Upload
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index(['device_type', 'device_id']);
            $table->index('recorded_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bandwidth_logs');
    }
};
