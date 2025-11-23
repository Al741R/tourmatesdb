<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->bigIncrements('details_id')->primary();
            $table->unsignedBigInteger('plan_trip_id');
            $table->unsignedBigInteger('accommodation_id');
            $table->unsignedBigInteger('itineraries_id');
            $table->unsignedBigInteger('list_id');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('plan_trip_id')->references('plan_trip_id')->on('plan_trips')->onDelete('cascade');
            $table->foreign('accommodation_id')->references('accommodation_id')->on('accommodations')->onDelete('cascade');
            $table->foreign('itineraries_id')->references('itineraries_id')->on('itineraries')->onDelete('cascade');
            $table->foreign('list_id')->references('pack_list_id')->on('pack_lists')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('details');
    }
};
