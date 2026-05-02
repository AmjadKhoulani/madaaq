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
        Schema::table('packages', function (Blueprint $table) {
            $table->string('type')->default('pppoe')->after('name'); // pppoe, hotspot
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->string('type')->default('pppoe')->after('router_id'); // pppoe, hotspot
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
