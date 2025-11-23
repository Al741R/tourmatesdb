<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->bigIncrements('destination_id')->primary();
            $table->string('name');
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->text('details')->nullable();
            $table->string('category')->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->text('reviews')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('destinations');
    }
};
