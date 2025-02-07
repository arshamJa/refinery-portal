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
            $table->string('location');
            $table->string('date');
            $table->string('time');
            $table->string('unit_held'); // واحد برگزار کننده
            $table->string('treat'); // پذیرایی
            $table->json('guest'); // this could be multiple
            $table->string('applicant'); // نام درخواست دهنده جلسه
            $table->string('position_organization');
            $table->string('signature');
            $table->string('reminder');
            $table->smallInteger('is_cancelled')->default('0');
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
