<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tower_ssids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tower_id');
            $table->unsignedBigInteger('router_id')->nullable(); // Network equipment
            $table->string('ssid_name');
            $table->enum('frequency', ['2.4GHz', '5GHz', 'Both'])->default('Both');
            $table->string('security_type', 50)->nullable();
            $table->string('password')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('tower_id')->references('id')->on('towers')->onDelete('cascade');
            $table->foreign('router_id')->references('id')->on('routers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tower_ssids');
    }
};
