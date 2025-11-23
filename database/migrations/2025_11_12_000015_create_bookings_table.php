<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('booking_id')->primary();
            $table->unsignedBigInteger('plan_trip_id');
            $table->unsignedBigInteger('accommodation_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('number_of_people'); //
            $table->integer('price_per_night');
            $table->integer('number_of_nights');
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('plan_trip_id')->references('plan_trip_id')->on('plan_trips')->onDelete('cascade');
            $table->foreign('accommodation_id')->references('accommodation_id')->on('accommodations')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
