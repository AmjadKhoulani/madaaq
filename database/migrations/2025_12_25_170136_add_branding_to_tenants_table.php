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
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'logo')) {
                $table->string('logo')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'primary_color')) {
                $table->string('primary_color')->default('#4F46E5');
            }
            if (!Schema::hasColumn('tenants', 'mobile_app_name')) {
                $table->string('mobile_app_name')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'support_contact')) {
                $table->string('support_contact')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            //
        });
    }
};
