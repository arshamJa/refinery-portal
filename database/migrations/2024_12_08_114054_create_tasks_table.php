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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('title');
            $table->longText('body');
            $table->string('sent_date');
            $table->string('time_out'); // مهلت ارسال
            $table->boolean('is_completed')->default(false);
            $table->longText('request_task')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
