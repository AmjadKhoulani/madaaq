<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('domain'); // example.com
            $table->bigInteger('hits')->default(0); // Number of requests
            $table->bigInteger('bytes')->default(0); // Traffic consumed
            $table->date('recorded_date'); // Daily aggregation
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unique(['tenant_id', 'domain', 'recorded_date']);
            $table->index('recorded_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_analytics');
    }
};
