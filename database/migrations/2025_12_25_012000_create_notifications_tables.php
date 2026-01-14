<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Notification Templates
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name'); // expiry_warning, payment_received, service_down
            $table->string('channel'); // sms, email, whatsapp
            $table->text('subject')->nullable(); // For email
            $table->text('content'); // Template with {{variables}}
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index('tenant_id');
        });

        // Notifications Log
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('channel'); // sms, email, whatsapp
            $table->string('type'); // expiry_warning, payment_received, etc.
            $table->string('recipient'); // Phone or email
            $table->text('content');
            $table->string('status'); // pending, sent, failed
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            $table->index('tenant_id');
            $table->index('client_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_templates');
    }
};
