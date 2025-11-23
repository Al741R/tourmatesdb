<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('accommodations', function (Blueprint $table) {
            $table->bigIncrements('accommodation_id')->primary();
            $table->unsignedBigInteger('plan_trip_id');
            $table->unsignedBigInteger('destination_id');
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('category')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('room_type')->nullable();
            $table->integer('contact_number')->nullable();
            $table->string('review')->nullable();
            $table->decimal('rating', 7, 2)-> nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('plan_trip_id')->references('plan_trip_id')->on('plan_trips')->onDelete('cascade');
            $table->foreign('destination_id')->references('destination_id')->on('destinations')->onDelete('cascade');
            //$table->foreign('accommodation_id')->references('accommodation_id')->on('accommodations')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('accommodations');
    }
};
