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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('scriptorium_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('boss_id')->constrained('users')->cascadeOnDelete();
            $table->string('location');
            $table->string('date')->index();
            $table->string('time')->index();
            $table->string('end_time')->nullable()->index();
            $table->string('unit_held');
            $table->boolean('treat');
            $table->json('guest')->nullable();
            $table->smallInteger('status')->default(\App\Enums\MeetingStatus::PENDING->value)->index();
            $table->softDeletes()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
