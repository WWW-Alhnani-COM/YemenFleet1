<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('maintenance', function (Blueprint $table) {
        $table->unsignedBigInteger('invoice_id')->nullable()->after('id'); 
        // nullable إذا لم يكن الفاتورة دائماً موجودة
    });
}

public function down()
{
    Schema::table('maintenance', function (Blueprint $table) {
        $table->dropColumn('invoice_id');
    });
}

};
