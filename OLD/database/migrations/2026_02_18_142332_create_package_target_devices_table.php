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
        Schema::create('package_target_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            // Polymorphic relation to Router or MikroTikServer
            $table->unsignedBigInteger('device_id');
            $table->string('device_type');
            
            // Allow duplicate device_id if type differs, but unique combo with package
            $table->unique(['package_id', 'device_id', 'device_type'], 'pkg_device_unique');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_target_devices');
    }
};
