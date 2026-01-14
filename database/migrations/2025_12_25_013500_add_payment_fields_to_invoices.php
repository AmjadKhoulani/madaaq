<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status'); // stripe, paypal, cash, bank_transfer
            $table->string('transaction_id')->nullable()->after('payment_method');
            $table->text('gateway_response')->nullable()->after('transaction_id');
            $table->string('payment_link')->nullable()->after('gateway_response');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'transaction_id', 'gateway_response', 'payment_link']);
        });
    }
};
