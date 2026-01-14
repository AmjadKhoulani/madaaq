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
            $table->foreignId('location_tower_id')->nullable()->constrained('towers')->nullOnDelete();
            $table->string('uplink_type')->nullable()->comment('wireless, fiber, ethernet, microwave, etc.');
            $table->string('uplink_interface')->nullable(); // e.g. ether1, sfp-sfpplus1
            
            // If wireless, define the devices
            $table->foreignId('uplink_sending_device_id')->nullable()->constrained('routers')->nullOnDelete(); // The remote end (Source)
            $table->foreignId('uplink_receiving_device_id')->nullable()->constrained('routers')->nullOnDelete(); // The local end (Destination)
            
            // For general notes about the link
            $table->text('uplink_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mikrotik_servers', function (Blueprint $table) {
            $table->dropForeign(['location_tower_id']);
            $table->dropForeign(['uplink_sending_device_id']);
            $table->dropForeign(['uplink_receiving_device_id']);
            $table->dropColumn([
                'location_tower_id', 
                'uplink_type', 
                'uplink_interface', 
                'uplink_sending_device_id', 
                'uplink_receiving_device_id',
                'uplink_notes'
            ]);
        });
    }
};
