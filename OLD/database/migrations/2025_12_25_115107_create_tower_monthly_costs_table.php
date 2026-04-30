<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tower_monthly_costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tower_id');
            $table->string('month', 7); // Format: 2025-01
            
            // Ampere Bills
            $table->decimal('ampere_bill', 10, 2)->default(0);
            $table->integer('ampere_kwh_consumed')->default(0);
            
            // Maintenance
            $table->decimal('maintenance_cost', 10, 2)->default(0);
            
            // Other
            $table->decimal('other_costs', 10, 2)->default(0);
            $table->text('notes')->nullable();
            
            // Calculated
            $table->decimal('total_cost', 10, 2)->default(0);
            
            $table->timestamps();
            
            // Unique constraint: one record per tower per month
            $table->unique(['tower_id', 'month']);
            
            $table->foreign('tower_id')->references('id')->on('towers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tower_monthly_costs');
    }
};
