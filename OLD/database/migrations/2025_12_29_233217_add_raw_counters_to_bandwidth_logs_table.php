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
        Schema::table('bandwidth_logs', function (Blueprint $table) {
            $table->bigInteger('raw_rx')->default(0)->after('rx_bytes');
            $table->bigInteger('raw_tx')->default(0)->after('tx_bytes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bandwidth_logs', function (Blueprint $table) {
            $table->dropColumn(['raw_rx', 'raw_tx']);
        });
    }
};
