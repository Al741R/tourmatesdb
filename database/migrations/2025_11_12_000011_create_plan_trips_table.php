<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plan_trips', function (Blueprint $table) {
            $table->bigIncrements('plan_trip_id');
            $table->integer('accommodation_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('destination_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('destination_id')->references('destination_id')->on('destinations')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            // accommodation foreign key removed here to avoid referencing a table
            // that is created later in the migration order. If desired, add this
            // constraint in the accommodations migration after both tables exist.
        });
    }

    public function down()
    {
        Schema::dropIfExists('plan_trips');
    }
};
