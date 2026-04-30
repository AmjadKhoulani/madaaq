<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mikrotik_servers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->default(1);
            $table->string('name');
            $table->string('ip');
            $table->integer('api_port')->default(8728);
            $table->string('username');
            $table->text('password_encrypted');
            $table->unsignedBigInteger('tower_id')->nullable();
            $table->string('location')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            
            // WireGuard Config - REMOVED (Added in separate migration 2026_01_03)
            // $table->boolean('wireguard_enabled')->default(false);
            // $table->text('wireguard_public_key')->nullable();
            // $table->text('wireguard_private_key')->nullable();
            // $table->string('wireguard_ip')->nullable();
            
            // Connection Status
            $table->boolean('is_connected')->default(false);
            $table->timestamp('last_sync_at')->nullable();
            $table->enum('connection_status', ['disconnected', 'connecting', 'connected', 'error'])->default('disconnected');
            
            // Setup
            $table->boolean('setup_script_generated')->default(false);
            $table->boolean('setup_completed')->default(false);
            
            $table->timestamps();
            
            // Unique constraint: one server per tenant
            $table->unique('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mikrotik_servers');
    }
};
