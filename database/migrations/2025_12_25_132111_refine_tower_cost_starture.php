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
            if (!Schema::hasColumn('towers', 'structure_cost')) {
                $table->decimal('structure_cost', 10, 2)->nullable()->after('notes')->comment('Cost of the tower structure itself');
            }
            if (Schema::hasColumn('towers', 'ampere_subscription_monthly')) {
                $table->dropColumn('ampere_subscription_monthly');
            }
        });

        Schema::table('routers', function (Blueprint $table) {
            if (!Schema::hasColumn('routers', 'model_id')) {
                $table->foreignId('model_id')->nullable()->after('name')->constrained('device_models')->nullOnDelete();
            }
            if (!Schema::hasColumn('routers', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('model_id')->comment('Price of this specific device');
            }
        });

        Schema::table('tower_monthly_costs', function (Blueprint $table) {
            if (!Schema::hasColumn('tower_monthly_costs', 'diesel_cost')) {
                $table->decimal('diesel_cost', 10, 2)->default(0)->after('ampere_bill')->comment('Cost of diesel for generator');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('towers', function (Blueprint $table) {
            $table->dropColumn('structure_cost');
            $table->decimal('ampere_subscription_monthly', 10, 2)->nullable();
        });

        Schema::table('routers', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::table('tower_monthly_costs', function (Blueprint $table) {
            $table->dropColumn('diesel_cost');
        });
    }
};
