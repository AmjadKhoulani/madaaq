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
        Schema::table('tower_devices', function (Blueprint $table) {
            $table->string('device_type')->default('wireless')->after('id'); // wireless (ap), switch
            $table->integer('ports_count')->nullable()->after('mac_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tower_devices', function (Blueprint $table) {
            $table->dropColumn(['device_type', 'ports_count']);
        });
    }
};
