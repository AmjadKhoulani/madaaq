<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mikrotik_servers', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->nullable()->after('tenant_id');
            $table->foreign('model_id')->references('id')->on('device_models')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('mikrotik_servers', function (Blueprint $table) {
            $table->dropForeign(['model_id']);
            $table->dropColumn('model_id');
        });
    }
};
