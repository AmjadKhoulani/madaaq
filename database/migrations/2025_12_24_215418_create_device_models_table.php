<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_models', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer'); // Ubiquiti, Mikrotik, TP-Link, etc.
            $table->string('model_name'); // PowerBeam AC 500, Mantbox 15s, etc.
            $table->string('device_type'); // router, access_point, base_station
            $table->integer('default_coverage_radius')->nullable(); // in meters
            $table->string('frequency')->nullable(); // 2.4GHz, 5GHz, 60GHz
            $table->string('max_throughput')->nullable(); // 450Mbps, 1Gbps, etc.
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_models');
    }
};
