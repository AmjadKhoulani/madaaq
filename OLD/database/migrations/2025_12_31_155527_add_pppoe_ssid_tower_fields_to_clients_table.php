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
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('clients', 'pppoe_username')) {
                $table->string('pppoe_username')->nullable()->after('username');
            }
            
            if (!Schema::hasColumn('clients', 'hotspot_username')) {
                $table->string('hotspot_username')->nullable()->after('pppoe_username');
            }
            
            if (!Schema::hasColumn('clients', 'ssid')) {
                $table->string('ssid')->nullable()->after('hotspot_username');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['pppoe_username', 'hotspot_username', 'ssid']);
        });
    }
};
