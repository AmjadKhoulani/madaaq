<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable(); // nullable for system-wide logs
            $table->string('log_name')->nullable(); // e.g. "default", "auth"
            $table->text('description'); // "User X deleted Invoice Y"
            $table->nullableMorphs('subject'); // The model being changed
            $table->nullableMorphs('causer'); // The user performing action
            $table->json('properties')->nullable(); // Old/New values
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
