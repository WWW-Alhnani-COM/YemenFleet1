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
      Schema::table('maintenance', function (Blueprint $table) {
    $table->foreign('truck_id')->references('id')->on('trucks')->onDelete('cascade');
});

Schema::table('invoices', function (Blueprint $table) {
    $table->foreign('maintenance_id')->references('id')->on('maintenance')->onDelete('cascade');
    $table->unique('maintenance_id'); // لضمان العلاقة واحد إلى واحد
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance', function (Blueprint $table) {
            //
        });
    }
};
