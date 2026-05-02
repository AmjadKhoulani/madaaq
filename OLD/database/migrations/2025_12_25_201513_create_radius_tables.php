<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('radius_accounting', function (Blueprint $table) {
            $table->id();
            $table->string('username')->index();
            $table->string('nas_ip_address');
            $table->string('session_id')->unique();
            $table->timestamp('start_time');
            $table->timestamp('stop_time')->nullable();
            $table->bigInteger('session_time')->default(0); // seconds
            $table->bigInteger('input_octets')->default(0); // bytes uploaded
            $table->bigInteger('output_octets')->default(0); // bytes downloaded
            $table->string('terminate_cause')->nullable();
            $table->ipAddress('framed_ip_address')->nullable();
            $table->timestamps();
        });

        Schema::create('radius_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('username')->index();
            $table->string('session_id')->unique();
            $table->string('nas_ip')->index();
            $table->ipAddress('framed_ip');
            $table->timestamp('start_time');
            $table->bigInteger('input_octets_current')->default(0);
            $table->bigInteger('output_octets_current')->default(0);
            $table->string('status')->default('active'); // active, stopped
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('radius_sessions');
        Schema::dropIfExists('radius_accounting');
    }
};
