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
            if (!Schema::hasColumn('towers', 'has_ampere')) {
                $table->boolean('has_ampere')->default(false)->after('type');
            }
            if (!Schema::hasColumn('towers', 'kwh_price')) {
                $table->decimal('kwh_price', 10, 2)->nullable()->after('has_ampere');
            }
            if (!Schema::hasColumn('towers', 'solar_panels_count')) {
                $table->integer('solar_panels_count')->nullable()->after('kwh_price');
            }
            if (!Schema::hasColumn('towers', 'solar_panel_wattage')) {
                $table->integer('solar_panel_wattage')->nullable()->after('solar_panels_count');
            }
            if (!Schema::hasColumn('towers', 'solar_installation_cost')) {
                $table->decimal('solar_installation_cost', 10, 2)->nullable()->after('solar_panel_wattage');
            }
            if (!Schema::hasColumn('towers', 'monthly_maintenance')) {
                $table->decimal('monthly_maintenance', 10, 2)->nullable()->after('solar_installation_cost');
            }
            if (!Schema::hasColumn('towers', 'monthly_rent')) {
                $table->decimal('monthly_rent', 10, 2)->nullable()->after('monthly_maintenance');
            }
            if (!Schema::hasColumn('towers', 'monthly_other_costs')) {
                $table->decimal('monthly_other_costs', 10, 2)->nullable()->after('monthly_rent');
            }
            if (!Schema::hasColumn('towers', 'monthly_notes')) {
                $table->text('monthly_notes')->nullable()->after('monthly_other_costs');
            }
            if (!Schema::hasColumn('towers', 'equipment_notes')) {
                $table->text('equipment_notes')->nullable()->after('monthly_notes');
            }
            if (!Schema::hasColumn('towers', 'equipment_total_cost')) {
                $table->decimal('equipment_total_cost', 10, 2)->nullable()->after('equipment_notes');
            }
            if (!Schema::hasColumn('towers', 'responsible_user_id')) {
                $table->unsignedBigInteger('responsible_user_id')->nullable()->after('equipment_total_cost');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('towers', function (Blueprint $table) {
            $table->dropColumn([
                'has_ampere',
                'kwh_price',
                'solar_panels_count',
                'solar_panel_wattage',
                'solar_installation_cost',
                'monthly_maintenance',
                'monthly_rent',
                'monthly_other_costs',
                'monthly_notes',
                'equipment_notes',
                'equipment_total_cost',
                'responsible_user_id',
            ]);
        });
    }
};
