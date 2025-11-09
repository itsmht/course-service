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
        Schema::create('course_tool_code', function (Blueprint $table) {
        $table->$table->bigIncrements('course_tool_id');
        $table->unsignedBigInteger('course_id');
        $table->unsignedBigInteger('tool_id');
        $table->timestamps();

        $table->foreign('course_id')
            ->references('course_id')
            ->on('courses')
            ->onDelete('cascade');

        $table->foreign('tool_id')
            ->references('tool_id')
            ->on('tools')
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
