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
        Schema::create('internet_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // fiber, microwave, starlink, etc
            $table->string('capacity')->nullable();
            $table->string('status')->default('online'); // online, offline, maintenance
            $table->string('ip_gateway')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internet_sources');
    }
};
