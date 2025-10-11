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
        Schema::create('module_banners', function (Blueprint $table) {
            $table->bigIncrements('module_banner_id');
            $table->unsignedBigInteger('module_id');
            $table->foreign('module_id')->references('module_id')->on('modules');
            $table->string('type');
            $table->string('image_url');
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
