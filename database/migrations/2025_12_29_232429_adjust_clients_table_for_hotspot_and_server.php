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
            $table->unsignedBigInteger('router_id')->nullable()->change();
            $table->unsignedBigInteger('mikrotik_server_id')->nullable()->after('router_id');
            $table->string('name')->nullable()->after('type');
            
            $table->foreign('mikrotik_server_id')->references('id')->on('mikrotik_servers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['mikrotik_server_id']);
            $table->dropColumn(['mikrotik_server_id', 'name']);
            $table->unsignedBigInteger('router_id')->nullable(false)->change();
        });
    }
};
