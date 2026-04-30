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
        Schema::table('mikrotik_servers', function (Blueprint $table) {
            if (!Schema::hasColumn('mikrotik_servers', 'setup_script_generated')) {
                $table->boolean('setup_script_generated')->default(false)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mikrotik_servers', function (Blueprint $table) {
            $table->dropColumn('setup_script_generated');
        });
    }
};
