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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();
            $table->morphs('notifiable');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->text('data');
            $table->timestamp('sender_read_at')->nullable();
            $table->timestamp('recipient_read_at')->nullable();
            $table->softDeletes()->index();
            $table->timestamps();

            $table->index('notifiable_type');
            $table->index('notifiable_id');

            // Composite indexes for optimal performance
            $table->index(['recipient_id', 'recipient_read_at'], 'notifications_recipient_read_idx');
            $table->index(['sender_id', 'sender_read_at'], 'notifications_sender_read_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
