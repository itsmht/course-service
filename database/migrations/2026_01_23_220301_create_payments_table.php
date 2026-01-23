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
        Schema::create('payments', function (Blueprint $table) {
        $table->bigIncrements('payment_id');
        $table->string('tran_id')->unique();
        $table->unsignedBigInteger('enrollment_id');
        $table->decimal('amount', 10, 2);
        $table->string('status')->default('pending'); // pending | paid | failed
        $table->string('val_id')->nullable();
        $table->foreign('enrollment_id')
            ->references('enrollment_id')
            ->on('enrollments')
            ->onDelete('cascade');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
