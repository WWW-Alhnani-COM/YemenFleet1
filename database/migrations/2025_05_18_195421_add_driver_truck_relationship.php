<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // إضافة حقل driver_id لجدول الشاحنات
        Schema::table('trucks', function (Blueprint $table) {
    $table->foreignId('driver_id')->nullable()->constrained()->onDelete('SET NULL');
});

        // أو إنشاء جدول وسيط إذا كنت تفضل many-to-many
        /*
        Schema::create('driver_truck', function (Blueprint $table) {
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->foreignId('truck_id')->constrained()->onDelete('cascade');
            $table->primary(['driver_id', 'truck_id']);
            $table->timestamps();
        });
        */
    }

    public function down()
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn('driver_id');
        });

        // إذا كنت تستخدم جدول وسيط
        // Schema::dropIfExists('driver_truck');
    }
};