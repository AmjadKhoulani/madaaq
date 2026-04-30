<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('network_alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('device_type'); // 'router', 'tower', 'server'
            $table->unsignedBigInteger('device_id');
            $table->string('alert_type'); // 'down', 'high_latency', 'high_bandwidth'
            $table->text('message');
            $table->string('severity')->default('warning'); // 'critical', 'warning', 'info'
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->index(['device_type', 'device_id']);
            $table->index('is_resolved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('network_alerts');
    }
};
