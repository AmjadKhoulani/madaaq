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
            $table->enum('connection_mode', ['wireless', 'cable'])->default('wireless')->after('type'); // Defaulting to wireless as it's common
            $table->unsignedBigInteger('device_model_id')->nullable()->after('connection_mode');
            $table->integer('switch_port')->nullable()->after('device_model_id');
            
            // Foreign key for device_model_id
            $table->foreign('device_model_id')->references('id')->on('device_models')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['device_model_id']);
            $table->dropColumn(['connection_mode', 'device_model_id', 'switch_port']);
        });
    }
};
