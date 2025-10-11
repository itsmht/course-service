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
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('course_id');
            $table->string('title');
            $table->longtext('description');
            $table->string('price');
            $table->string('featured_video_url')->nullable();
            $table->string('featured_image_url')->nullable();
            $table->string('tagline')->nullable();
            $table->string('location')->nullable();
            $table->time('time')->nullable();
            $table->string('capacity')->nullable();
            $table->string('type')->nullable();
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
