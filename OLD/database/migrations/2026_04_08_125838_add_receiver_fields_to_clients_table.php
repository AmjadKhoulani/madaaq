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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('receiver_ip')->nullable()->after('cpe_ip');
            $table->string('receiver_username')->nullable()->after('receiver_ip');
            $table->string('receiver_password')->nullable()->after('receiver_username');
            $table->string('receiver_model')->nullable()->after('receiver_password');
            $table->integer('cpe_port')->nullable()->default(80)->after('cpe_password');
            $table->integer('receiver_port')->nullable()->default(80)->after('receiver_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'receiver_ip',
                'receiver_username',
                'receiver_password',
                'receiver_model',
                'cpe_port',
                'receiver_port'
            ]);
        });
    }
};
