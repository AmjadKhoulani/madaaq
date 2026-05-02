<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_status_logs', function (Blueprint $table) {
            $table->id();
            $table->string('device_type'); // 'router', 'tower', 'server'
            $table->unsignedBigInteger('device_id');
            $table->string('status'); // 'online', 'offline', 'warning'
            $table->integer('response_time')->nullable(); // milliseconds
            $table->timestamp('checked_at');
            $table->timestamps();

            $table->index(['device_type', 'device_id']);
            $table->index('checked_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_status_logs');
    }
};
