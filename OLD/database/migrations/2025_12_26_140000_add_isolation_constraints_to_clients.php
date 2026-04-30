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
        Schema::table('clients', function (Blueprint $table) {
            // Ensure phone is indexable (varchar length)
            $table->string('phone', 50)->nullable()->change();

            // Add composite unique indices for strict tenant isolation
            // This allows the same username/phone to exist in DIFFERENT tenants,
            // but prevents duplicates within the SAME tenant.
            $table->unique(['tenant_id', 'username'], 'clients_tenant_username_unique');
            $table->unique(['tenant_id', 'phone'], 'clients_tenant_phone_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique('clients_tenant_username_unique');
            $table->dropUnique('clients_tenant_phone_unique');
        });
    }
};
