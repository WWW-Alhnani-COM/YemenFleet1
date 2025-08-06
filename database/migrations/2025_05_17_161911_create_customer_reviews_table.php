<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->text('comment')->nullable();
            $table->integer('rating'); // 1-5 مثلاً
            $table->dateTime('review_date');
            $table->timestamps();

            $table->unique(['customer_id', 'product_id']); // منع التكرار لنفس المنتج
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_reviews');
    }
};

