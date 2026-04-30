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
        Schema::table('tower_monthly_costs', function (Blueprint $table) {
            $table->decimal('monthly_rent', 10, 2)->default(0)->after('maintenance_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tower_monthly_costs', function (Blueprint $table) {
            $table->dropColumn('monthly_rent');
        });
    }
};
