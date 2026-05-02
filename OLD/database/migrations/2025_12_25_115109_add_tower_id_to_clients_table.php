<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedBigInteger('tower_id')->nullable()->after('status');
            $table->text('installation_notes')->nullable()->after('tower_id');
            
            $table->foreign('tower_id')->references('id')->on('towers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['tower_id']);
            $table->dropColumn(['tower_id', 'installation_notes']);
        });
    }
};
