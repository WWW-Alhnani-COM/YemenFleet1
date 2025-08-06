<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sensors', function (Blueprint $table) {
            // إضافة عمود truck_id كFK (nullable في حال لم يتم ربط الحساس بعد بشاحنة)
            $table->foreignId('truck_id')->nullable()->constrained('trucks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('sensors', function (Blueprint $table) {
            // حذف المفتاح الخارجي والعمود في حال تم التراجع عن الهجرة
            $table->dropForeign(['truck_id']);
            $table->dropColumn('truck_id');
        });
    }
};
