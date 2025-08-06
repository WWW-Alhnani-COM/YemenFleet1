<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id(); // company_id
            $table->string('company_name');
            $table->string('phone_company');
            $table->string('email_company')->unique();
            $table->string('address_company');
            $table->string('owner_name');
            $table->string('phone_owner');
            $table->string('password');
            $table->string('economic_activity');
            $table->string('commercial_reg_number');
            $table->string('fleet_type');
            $table->rememberToken(); // nullable token
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

