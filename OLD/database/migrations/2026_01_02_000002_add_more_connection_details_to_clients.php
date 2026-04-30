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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('cpe_ip')->nullable()->after('device_model_id');
            $table->string('cpe_mac')->nullable()->after('cpe_ip');
            $table->unsignedBigInteger('tower_device_id')->nullable()->after('tower_id');
            
            $table->foreign('tower_device_id')->references('id')->on('tower_devices')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['tower_device_id']);
            $table->dropColumn(['cpe_ip', 'cpe_mac', 'tower_device_id']);
        });
    }
};
