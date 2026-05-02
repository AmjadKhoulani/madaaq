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
            $table->enum('transmitter_status', ['online', 'offline'])->default('offline')->after('transmitter_ip');
            $table->enum('receiver_status', ['online', 'offline'])->default('offline')->after('receiver_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('towers', function (Blueprint $table) {
            $table->dropColumn(['transmitter_status', 'receiver_status']);
        });
    }
};
