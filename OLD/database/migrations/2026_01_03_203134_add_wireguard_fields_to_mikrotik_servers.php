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
        Schema::table('mikrotik_servers', function (Blueprint $table) {
            $table->string('wireguard_public_key')->nullable()->after('password_encrypted');
            $table->string('wireguard_private_key')->nullable()->after('wireguard_public_key');
            $table->string('wireguard_ip')->nullable()->after('wireguard_private_key');
            $table->boolean('wireguard_enabled')->default(false)->after('wireguard_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mikrotik_servers', function (Blueprint $table) {
            $table->dropColumn(['wireguard_public_key', 'wireguard_private_key', 'wireguard_ip', 'wireguard_enabled']);
        });
    }
};
