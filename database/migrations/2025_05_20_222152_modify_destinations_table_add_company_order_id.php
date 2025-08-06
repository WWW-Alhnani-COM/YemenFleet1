<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            // حذف المفتاح الأجنبي وعمود order_id إذا كان موجود
            if (Schema::hasColumn('destinations', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }

            // إضافة عمود company_order_id وربطه بجدول company_orders
            $table->foreignId('company_order_id')->nullable()->constrained('company_orders')->onDelete('cascade')->after('task_id');
        });
    }

    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            // حذف عمود company_order_id وعلاقته
            if (Schema::hasColumn('destinations', 'company_order_id')) {
                $table->dropForeign(['company_order_id']);
                $table->dropColumn('company_order_id');
            }

            // إعادة إضافة order_id مع المفتاح الأجنبي
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade')->after('task_id');
        });
    }
};
