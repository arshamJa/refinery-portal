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
        Schema::create('resident_phones', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->index();
            $table->string('work_phone')->index();
            $table->string('house_phone')->index();
            $table->string('phone')->index();
            $table->string('n_code')->index();
            $table->string('p_code')->index();
            $table->string('position')->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_phones');
    }
};
