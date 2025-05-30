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
            $table->string('title'); // موضوع جلسه
            $table->string('scriptorium')->index(); // نام دبیر جلسه
            $table->string('scriptorium_department')->index(); // واحد دبیرجلسه
            $table->string('scriptorium_position'); // سمت دبیرجلسه
            $table->string('boss');
            $table->string('location');
            $table->string('date')->index();
            $table->string('time')->index();
            $table->string('end_time')->nullable()->index();   // ساعت خاتمه جلسه
            $table->string('unit_held'); // واحد برگزار کننده
            $table->string('treat'); // پذیرایی
            $table->json('guest')->nullable(); // this could be multiple
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
