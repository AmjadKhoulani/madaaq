<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Roles Table
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->string('name');
                $table->string('display_name')->nullable();
                $table->string('description')->nullable();
                $table->string('guard_name')->default('web');
                $table->timestamps();

                $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            });
        }

        // Permissions Table
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // e.g., 'view_invoices'
                $table->string('display_name')->nullable();
                $table->string('guard_name')->default('web');
                $table->timestamps();
            });
        }

        // Role <-> Permission Pivot
        if (!Schema::hasTable('role_has_permissions')) {
            Schema::create('role_has_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('permission_id');

                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

                $table->primary(['role_id', 'permission_id']);
            });
        }

        // User <-> Role Pivot (Model Has Roles)
        if (!Schema::hasTable('model_has_roles')) {
            Schema::create('model_has_roles', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->string('model_type'); // App\Models\User
                $table->unsignedBigInteger('model_id');

                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->index(['model_id', 'model_type']);
                
                $table->primary(['role_id', 'model_id', 'model_type']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
