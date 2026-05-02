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
            $table->boolean('wireguard_enabled')->default(false)->after('status');
            $table->text('wireguard_public_key')->nullable()->after('wireguard_enabled');
            $table->text('wireguard_private_key')->nullable()->after('wireguard_public_key');
            $table->string('wireguard_ip')->nullable()->after('wireguard_private_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routers', function (Blueprint $table) {
            $table->dropColumn(['wireguard_enabled', 'wireguard_public_key', 'wireguard_private_key', 'wireguard_ip']);
        });
    }
};
