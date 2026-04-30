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
        Schema::table('towers', function (Blueprint $table) {
            if (!Schema::hasColumn('towers', 'sending_server_id')) {
                $table->foreignId('sending_server_id')->nullable()->constrained('mikrotik_servers')->nullOnDelete()->after('mikrotik_server_id');
            }
        });

        Schema::table('internet_sources', function (Blueprint $table) {
            if (!Schema::hasColumn('internet_sources', 'connection_type')) {
                $table->enum('connection_type', ['cable', 'fiber', 'wireless'])->nullable()->after('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('towers', function (Blueprint $table) {
            $table->dropForeign(['sending_server_id']);
            $table->dropColumn('sending_server_id');
        });

        Schema::table('internet_sources', function (Blueprint $table) {
            $table->dropColumn('connection_type');
        });
    }
};
