<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('itineraries', function (Blueprint $table) {
            $table->bigIncrements('itineraries_id')->primary();
            $table->unsignedBigInteger('plan_trip_id');
            $table->integer('day')->nullable();
            $table->string('title');
            $table->text('activity')->nullable();
            $table->string('time')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('plan_trip_id')->references('plan_trip_id')->on('plan_trips')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('itineraries');
    }
};
