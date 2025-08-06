<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_group_message')->default(false);

            // sender polymorphic
            $table->morphs('sender'); // sender_id, sender_type

            // notifiable polymorphic (receiver)
            $table->morphs('notifiable'); // notifiable_id, notifiable_type

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

