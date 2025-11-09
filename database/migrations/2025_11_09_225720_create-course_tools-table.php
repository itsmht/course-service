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
        Schema::create('course_techs', function (Blueprint $table) {
            $table->bigIncrements('course_tech_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('technology_id');
            $table->foreign('course_id')->references('course_id')->on('courses');
            $table->foreign('technology_id')->references('technology_id')->on('technologies');
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
