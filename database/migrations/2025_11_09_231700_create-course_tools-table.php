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
        Schema::create('course_tools', function (Blueprint $table) {
            $table->bigIncrements('course_tool_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('tool_id');
            $table->foreign('course_id')->references('course_id')->on('courses');
            $table->foreign('tool_id')->references('tool_id')->on('tools');
            $table->timestamps();
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
