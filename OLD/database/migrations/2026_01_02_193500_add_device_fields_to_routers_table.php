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
        Schema::table('routers', function (Blueprint $table) {
            if (!Schema::hasColumn('routers', 'device_type')) {
                $table->string('device_type')->default('router')->after('name');
            }
            if (!Schema::hasColumn('routers', 'model_id')) {
                $table->foreignId('model_id')->nullable()->constrained('device_models')->nullOnDelete()->after('device_type');
            }
            if (!Schema::hasColumn('routers', 'mac_address')) {
                $table->string('mac_address')->nullable()->after('ip');
            }
            if (!Schema::hasColumn('routers', 'ssid')) {
                $table->string('ssid')->nullable()->after('mac_address');
            }
            if (!Schema::hasColumn('routers', 'status')) {
                $table->string('status')->default('active')->after('ssid');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routers', function (Blueprint $table) {
            $table->dropForeign(['model_id']);
            $table->dropColumn(['device_type', 'model_id', 'mac_address', 'ssid', 'status']);
        });
    }
};
