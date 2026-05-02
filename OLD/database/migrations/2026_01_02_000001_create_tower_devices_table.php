<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tower_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tower_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_model_id')->nullable()->constrained('device_models')->nullOnDelete();
            $table->string('name'); // e.g., Sector 1, Omni, Dish to School
            $table->string('ip')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('ssid')->nullable(); // If valid for this device
            $table->string('frequency')->nullable(); // 5GHz, 2.4GHz
            $table->string('mode')->default('ap'); // ap, bridge, station
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tower_devices');
    }
};
