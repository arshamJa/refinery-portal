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
            $table->string('unit_organization'); // واحد سازمانی
            $table->string('scriptorium'); // نام دبیر جلسه
            $table->string('boss');
            $table->string('location');
            $table->string('date');
            $table->string('time');
            $table->string('unit_held'); // واحد برگزار کننده
            $table->string('treat'); // پذیرایی
            $table->json('guest')->nullable(); // this could be multiple
            $table->string('applicant'); // نام درخواست دهنده جلسه
            $table->string('position_organization');
            $table->smallInteger('status')->default(\App\Enums\MeetingStatus::PENDING->value);
            $table->softDeletes();
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
