<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_id')->constrained()->onDelete('cascade');
            $table->dateTime('timestamp');
            $table->json('value'); // بيانات عامة (raw JSON)
            $table->string('location')->nullable();
            $table->string('weather_type')->nullable();
            $table->string('obd_code')->nullable();
            $table->integer('heart_rate')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->boolean('is_alerted')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};

