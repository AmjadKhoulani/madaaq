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
            $table->unsignedBigInteger('transmitter_router_id')->nullable()->after('transmitter_model_id');
            $table->unsignedBigInteger('receiver_router_id')->nullable()->after('receiver_model_id');
            
            $table->foreign('transmitter_router_id')->references('id')->on('routers')->onDelete('set null');
            $table->foreign('receiver_router_id')->references('id')->on('routers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('towers', function (Blueprint $table) {
            $table->dropForeign(['transmitter_router_id']);
            $table->dropForeign(['receiver_router_id']);
            $table->dropColumn(['transmitter_router_id', 'receiver_router_id']);
        });
    }
};
