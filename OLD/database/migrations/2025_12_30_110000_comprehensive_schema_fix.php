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
        // Fix clients table
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'service_password')) {
                $table->string('service_password')->nullable()->after('password');
            }
            if (!Schema::hasColumn('clients', 'custom_duration_days')) {
                $table->integer('custom_duration_days')->nullable()->after('expires_at');
            }
            if (!Schema::hasColumn('clients', 'custom_data_limit_mb')) {
                $table->integer('custom_data_limit_mb')->nullable()->after('custom_duration_days');
            }
            if (!Schema::hasColumn('clients', 'custom_price')) {
                $table->decimal('custom_price', 10, 2)->nullable()->after('custom_data_limit_mb');
            }
            if (!Schema::hasColumn('clients', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
            if (!Schema::hasColumn('clients', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('clients', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('clients', 'notes')) {
                $table->text('notes')->nullable()->after('city');
            }
        });

        // Fix packages table
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'duration_days')) {
                $table->integer('duration_days')->default(30)->after('price');
            }
            if (!Schema::hasColumn('packages', 'data_limit_mb')) {
                $table->bigInteger('data_limit_mb')->nullable()->after('duration_days');
            }
        });

        // Fix routers table
        Schema::table('routers', function (Blueprint $table) {
            if (!Schema::hasColumn('routers', 'device_type')) {
                $table->string('device_type')->default('mikrotik')->after('name');
            }
            if (!Schema::hasColumn('routers', 'password')) {
                $table->string('password')->nullable()->after('username');
            }
            if (!Schema::hasColumn('routers', 'coverage_radius')) {
                $table->decimal('coverage_radius', 10, 2)->nullable()->after('lng');
            }
            if (!Schema::hasColumn('routers', 'azimuth')) {
                $table->integer('azimuth')->nullable()->after('coverage_radius');
            }
            if (!Schema::hasColumn('routers', 'beam_width')) {
                $table->integer('beam_width')->nullable()->after('azimuth');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'service_password',
                'custom_duration_days',
                'custom_data_limit_mb',
                'custom_price',
                'email',
                'address',
                'city',
                'notes',
            ]);
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['duration_days', 'data_limit_mb']);
        });

        Schema::table('routers', function (Blueprint $table) {
            $table->dropColumn([
                'device_type',
                'password',
                'coverage_radius',
                'azimuth',
                'beam_width',
            ]);
        });
    }
};
