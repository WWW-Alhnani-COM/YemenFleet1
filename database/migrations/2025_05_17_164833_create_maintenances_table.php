<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('maintenance', function (Blueprint $table) {
    $table->id();
    $table->string('type');
    $table->decimal('cost', 10, 2);
    $table->dateTime('date');
    $table->text('description')->nullable();
    $table->unsignedBigInteger('truck_id');
    // لا تضف المفتاح الأجنبي هنا
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance');
    }
};

