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
            $table->enum('connection_type', ['cable', 'fiber', 'wireless'])->nullable()->after('mikrotik_server_id');
            $table->string('connection_port')->nullable()->after('connection_type');
            $table->string('transmitter_ip')->nullable()->after('connection_port');
            $table->string('receiver_ip')->nullable()->after('transmitter_ip');
            $table->foreignId('transmitter_model_id')->nullable()->constrained('device_models')->nullOnDelete()->after('receiver_ip');
            $table->foreignId('receiver_model_id')->nullable()->constrained('device_models')->nullOnDelete()->after('transmitter_model_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('towers', function (Blueprint $table) {
            $table->dropForeign(['transmitter_model_id']);
            $table->dropForeign(['receiver_model_id']);
            $table->dropColumn([
                'connection_type',
                'connection_port',
                'transmitter_ip',
                'receiver_ip',
                'transmitter_model_id',
                'receiver_model_id'
            ]);
        });
    }
};
