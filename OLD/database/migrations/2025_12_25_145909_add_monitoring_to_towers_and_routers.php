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
        if (Schema::hasTable('towers') && !Schema::hasColumn('towers', 'ip_address')) {
            Schema::table('towers', function (Blueprint $table) {
                $table->string('ip_address')->nullable();
                $table->boolean('is_reachable')->default(false); // After last known column
                $table->timestamp('last_ping_at')->nullable();
                $table->integer('latency')->nullable()->comment('in ms');
            });
        }

        if (Schema::hasTable('routers') && !Schema::hasColumn('routers', 'is_reachable')) {
            Schema::table('routers', function (Blueprint $table) {
                $table->boolean('is_reachable')->default(false)->after('ip');
                $table->timestamp('last_ping_at')->nullable()->after('is_reachable');
                $table->integer('latency')->nullable()->after('last_ping_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('towers')) {
            Schema::table('towers', function (Blueprint $table) {
                $table->dropColumn(['ip_address', 'is_reachable', 'last_ping_at', 'latency']);
            });
        }

        if (Schema::hasTable('routers')) {
            Schema::table('routers', function (Blueprint $table) {
                $table->dropColumn(['is_reachable', 'last_ping_at', 'latency']);
            });
        }
    }
};
