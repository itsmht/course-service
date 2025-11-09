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
        Schema::create('promo_codes', function (Blueprint $table) {
    $table->bigIncrements('promo_id');
    $table->string('code')->unique(); // e.g. SAVE10, COURSE2025
    $table->enum('discount_type', ['percentage', 'fixed']); // % or flat amount
    $table->decimal('discount_value', 8, 2);
    $table->dateTime('start_date')->nullable();
    $table->dateTime('end_date')->nullable();
    $table->integer('usage_limit')->nullable(); // e.g. 100 uses max
    $table->integer('used_count')->default(0);
    $table->integer('status')->default(1); // 1=active, 0=inactive
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
