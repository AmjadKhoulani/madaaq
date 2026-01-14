<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add tower_id to routers if not exists
        if (!Schema::hasColumn('routers', 'tower_id')) {
            Schema::table('routers', function (Blueprint $table) {
                $table->unsignedBigInteger('tower_id')->nullable();
            });
        }

        // Add phone to clients if not exists
        if (!Schema::hasColumn('clients', 'phone')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->string('phone', 50)->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('routers', function (Blueprint $table) {
            $table->dropColumn('tower_id');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
