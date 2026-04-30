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
        Schema::table('tower_ssids', function (Blueprint $table) {
            $table->foreignId('tower_device_id')->nullable()->constrained('tower_devices')->nullOnDelete()->after('tower_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tower_ssids', function (Blueprint $table) {
            $table->dropForeign(['tower_device_id']);
            $table->dropColumn('tower_device_id');
        });
    }
};
