<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('towers', function (Blueprint $table) {
            $table->decimal('electricity_hours', 4, 1)->nullable()->after('kwh_price');
        });
    }

    public function down()
    {
        Schema::table('towers', function (Blueprint $table) {
            $table->dropColumn('electricity_hours');
        });
    }
};
