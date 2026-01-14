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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->text('billing_address')->nullable()->after('status');
            $table->string('payment_method')->nullable()->after('billing_address'); // e.g. 'Bank Transfer', 'PayPal'
            $table->string('tax_number')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['billing_address', 'payment_method', 'tax_number']);
        });
    }
};
