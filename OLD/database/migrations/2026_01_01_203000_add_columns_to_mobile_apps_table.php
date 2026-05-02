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
        Schema::table('mobile_apps', function (Blueprint $table) {
            // Check if columns exist before adding them to avoid duplication errors 
            // if this migration is run on a messy DB state, though standard is `table` should be clean.
            // However, sticking to the plan:
            
            if (!Schema::hasColumn('mobile_apps', 'tenant_id')) {
                $table->unsignedBigInteger('tenant_id')->nullable()->default(1)->after('id');
            }
            if (!Schema::hasColumn('mobile_apps', 'app_name')) {
                $table->string('app_name')->nullable()->after('tenant_id');
            }
            if (!Schema::hasColumn('mobile_apps', 'app_name_en')) {
                $table->string('app_name_en')->nullable()->after('app_name');
            }
            if (!Schema::hasColumn('mobile_apps', 'package_name')) {
                $table->string('package_name')->nullable()->after('app_name_en');
            }
            if (!Schema::hasColumn('mobile_apps', 'description')) {
                $table->text('description')->nullable()->after('package_name');
            }
            if (!Schema::hasColumn('mobile_apps', 'icon_path')) {
                $table->string('icon_path')->nullable()->after('description');
            }
            if (!Schema::hasColumn('mobile_apps', 'primary_color')) {
                $table->string('primary_color')->nullable()->after('icon_path');
            }
            if (!Schema::hasColumn('mobile_apps', 'secondary_color')) {
                $table->string('secondary_color')->nullable()->after('primary_color');
            }
            if (!Schema::hasColumn('mobile_apps', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('secondary_color');
            }
            if (!Schema::hasColumn('mobile_apps', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('contact_email');
            }
            if (!Schema::hasColumn('mobile_apps', 'website')) {
                $table->string('website')->nullable()->after('contact_phone');
            }
            if (!Schema::hasColumn('mobile_apps', 'status')) {
                $table->string('status')->default('pending')->after('website');
            }
            if (!Schema::hasColumn('mobile_apps', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('status');
            }
            if (!Schema::hasColumn('mobile_apps', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('is_paid');
            }
            if (!Schema::hasColumn('mobile_apps', 'aab_file_path')) {
                $table->string('aab_file_path')->nullable()->after('price');
            }
            if (!Schema::hasColumn('mobile_apps', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('aab_file_path');
            }
            if (!Schema::hasColumn('mobile_apps', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('submitted_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mobile_apps', function (Blueprint $table) {
            $table->dropColumn([
                'tenant_id',
                'app_name',
                'app_name_en',
                'package_name',
                'description',
                'icon_path',
                'primary_color',
                'secondary_color',
                'contact_email',
                'contact_phone',
                'website',
                'status',
                'is_paid',
                'price',
                'aab_file_path',
                'submitted_at',
                'completed_at',
            ]);
        });
    }
};
