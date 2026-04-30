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
        Schema::table('towers', function (Blueprint $table) {
            $table->foreignId('mikrotik_server_id')->nullable()->after('tenant_id')->constrained('mikrotik_servers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('towers', function (Blueprint $table) {
            //
        });
    }
};
