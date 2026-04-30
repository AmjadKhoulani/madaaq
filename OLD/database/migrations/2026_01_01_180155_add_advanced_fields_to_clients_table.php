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
            $table->string('ip_address')->nullable()->after('password');
            $table->bigInteger('data_limit')->nullable()->comment('In Bytes')->after('ip_address');
            // Assuming download/upload limits on package usually, but per client override requested?
            // "Show usage limit" -> could imply cap.
            // Let's stick to data_limit as a cap for now.
            // also duration.
            // expires_at exists? Yes. But user wants to SET duration.
            // We can store a preferred duration in days maybe? Or just use it for calculation.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
};
