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
            // Check if columns exist
            if (!Schema::hasColumn('clients', 'tower_id')) {
                $table->foreignId('tower_id')->nullable()->constrained('towers')->nullOnDelete();
            }
            if (!Schema::hasColumn('clients', 'ssid_id')) {
                $table->foreignId('ssid_id')->nullable()->constrained('tower_ssids')->nullOnDelete();
            }
            if (!Schema::hasColumn('clients', 'ip')) {
                $table->string('ip')->nullable()->after('username');
            }
        });

        // Make MikroTik Server IP/API nullable or optional
        Schema::table('mikrotik_servers', function (Blueprint $table) {
            // Check if ip column is not nullable, change it
             $table->string('ip')->nullable()->change();
             $table->integer('api_port')->nullable()->change();
             $table->string('username')->nullable()->change();
             $table->text('password_encrypted')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // $table->dropForeign(['tower_id']);
            // $table->dropForeign(['ssid_id']);
            // $table->dropColumn(['tower_id', 'ssid_id', 'ip']);
        });
    }
};
