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
        Schema::create('course_promo_code', function (Blueprint $table) {
        $table->$table->bigIncrements('course_promo_id');
        $table->unsignedBigInteger('course_id');
        $table->unsignedBigInteger('promo_id');
        $table->timestamps();

        $table->foreign('course_id')
            ->references('course_id')
            ->on('courses')
            ->onDelete('cascade');

        $table->foreign('promo_id')
            ->references('promo_id')
            ->on('promo_codes')
            ->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
