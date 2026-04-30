<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('towers', function (Blueprint $table) {
            $table->boolean('has_government_electricity')->default(false)->after('has_ampere');
            $table->string('government_electricity_notes')->nullable()->after('has_government_electricity');
            // Assuming electricity_hours might not exist in structure yet if it was just in model, 
            // but if it does we can drop it. Safest is to check or just ignore. 
            // The previous conversation log suggests electricity_hours was in "Dashboard Improvements" which might have updated schema.
            // I'll check for it and drop it if exists.
             if (Schema::hasColumn('towers', 'electricity_hours')) {
                $table->dropColumn('electricity_hours');
             }
        });
    }

    public function down(): void
    {
        Schema::table('towers', function (Blueprint $table) {
            $table->dropColumn(['has_government_electricity', 'government_electricity_notes']);
            $table->decimal('electricity_hours', 8, 1)->nullable();
        });
    }
};
