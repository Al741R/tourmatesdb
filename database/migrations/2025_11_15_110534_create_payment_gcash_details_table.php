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
        Schema::create('payment_gcash_details', function (Blueprint $table) {
            $table->bigIncrements('payment_gcash_id');
            $table->unsignedBigInteger('payment_id');
            $table->string('gcash_number');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('payment_id')->references('payment_id')->on('payment_method')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gcash_details');
    }
};
