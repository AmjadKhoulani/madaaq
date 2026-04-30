<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Client Notes
        Schema::create('client_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('user_id')->nullable(); // Staff who added note
            $table->text('content');
            $table->string('type')->default('general'); // general, technical, billing
            $table->timestamps();
            
            $table->index(['tenant_id', 'client_id']);
        });

        // Client Activities (timeline)
        Schema::create('client_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('client_id');
            $table->string('action'); // created, package_changed, payment_received, suspended, etc.
            $table->text('description');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['tenant_id', 'client_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_activities');
        Schema::dropIfExists('client_notes');
    }
};
