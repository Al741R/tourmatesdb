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
        // Remove calculated fields from bookings (3NF violation)
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['number_of_nights', 'total_amount']);
        });

        // Remove redundant destination_id from accommodations (3NF violation)
        Schema::table('accommodations', function (Blueprint $table) {
            $table->dropForeign(['destination_id']);
            $table->dropColumn('destination_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore calculated fields to bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('number_of_nights')->after('price_per_night');
            $table->decimal('total_amount', 10, 2)->nullable()->after('number_of_nights');
        });

        // Restore redundant destination_id to accommodations
        Schema::table('accommodations', function (Blueprint $table) {
            $table->unsignedBigInteger('destination_id')->after('plan_trip_id');
            $table->foreign('destination_id')->references('destination_id')->on('destinations')->onDelete('cascade');
        });
    }
};
