<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tower_monthly_costs', function (Blueprint $table) {
            $table->decimal('ampere_cost', 10, 2)->nullable()->after('ampere_bill');
        });
    }

    public function down()
    {
        Schema::table('tower_monthly_costs', function (Blueprint $table) {
            $table->dropColumn('ampere_cost');
        });
    }
};
