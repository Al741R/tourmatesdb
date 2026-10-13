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
        Schema::create('payment_card_details', function (Blueprint $table) {
            $table->bigIncrements('payment_card_id');
            $table->unsignedBigInteger('user_id');
            $table->string('card_name');
            $table->string('card_number'); // Will be encrypted at application level
            $table->string('expiry_date');
            $table->string('postal_code');
            $table->unsignedBigInteger('payment_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('payment_id')->references('payment_id')->on('payment_method')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_card_details');
    }
};
