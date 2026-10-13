<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pack_lists', function (Blueprint $table) {
            $table->bigIncrements('pack_list_id')->primary();
            $table->unsignedBigInteger('plan_trip_id');
            $table->string('list_title');
            $table->string('item');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('plan_trip_id')->references('plan_trip_id')->on('plan_trips')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pack_lists');
    }
};
