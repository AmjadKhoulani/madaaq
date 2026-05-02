<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mikrotik_servers', function (Blueprint $table) {
            $table->foreignId('internet_source_id')->nullable()->constrained('internet_sources')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mikrotik_servers', function (Blueprint $table) {
            $table->dropForeign(['internet_source_id']);
            $table->dropColumn('internet_source_id');
        });
    }
};
