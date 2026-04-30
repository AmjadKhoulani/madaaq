<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('towers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name'); // اسم البرج
            $table->string('location')->nullable(); // الموقع النصي
            $table->decimal('lat', 10, 7)->nullable(); // GPS
            $table->decimal('lng', 10, 7)->nullable(); // GPS
            $table->decimal('height', 8, 2)->nullable(); // ارتفاع البرج (متر)
            $table->string('type')->default('tower'); // tower, building, pole
            $table->integer('battery_count')->default(0); // عدد البطاريات
            $table->string('battery_type')->nullable(); // نوع البطاريات
            $table->boolean('has_inverter')->default(false); // يوجد انفرتر
            $table->string('inverter_capacity')->nullable(); // سعة الانفرتر (KVA)
            $table->boolean('has_generator')->default(false); // يوجد مولد
            $table->string('generator_capacity')->nullable(); // سعة المولد
            $table->boolean('has_solar')->default(false); // يوجد طاقة شمسية
            $table->string('solar_capacity')->nullable(); // سعة الطاقة الشمسية
            $table->text('notes')->nullable();
            $table->string('status')->default('active'); // active, maintenance, inactive
            $table->timestamps();
            
            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('towers');
    }
};
