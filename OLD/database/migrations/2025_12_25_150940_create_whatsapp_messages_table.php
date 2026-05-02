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
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('direction')->default('outbound'); // outbound, inbound
            $table->string('type')->default('text'); // text, image, file
            $table->text('body')->nullable();
            $table->string('media_url')->nullable();
            $table->string('status')->default('sent'); // sent, delivered, read, failed
            $table->string('message_sid')->nullable(); // Provider ID
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
