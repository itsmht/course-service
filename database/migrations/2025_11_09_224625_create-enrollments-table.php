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
        Schema::create('enrollments', function (Blueprint $table) {
        $table->bigIncrements('enrollment_id');
        $table->unsignedBigInteger('account_id');
        $table->unsignedBigInteger('course_id');
        $table->unsignedBigInteger('promo_id')->nullable();
        $table->decimal('final_price', 10, 2)->nullable(); // price after discount
        $table->timestamps();

        $table->foreign('course_id')
            ->references('course_id')
            ->on('courses')
            ->onDelete('cascade');

        $table->foreign('promo_id')
            ->references('promo_id')
            ->on('promo_codes')
            ->onDelete('set null');
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
