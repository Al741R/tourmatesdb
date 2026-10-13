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
        // 1. Remove CVV column from payment_card_details (security fix - never store CVV)
        if (Schema::hasColumn('payment_card_details', 'cvv')) {
            Schema::table('payment_card_details', function (Blueprint $table) {
                $table->dropColumn('cvv');
            });
        }

        // 2. Remove redundant booking_id from payment_gcash_details
        // Booking is accessible through payment_method relationship
        if (Schema::hasColumn('payment_gcash_details', 'booking_id')) {
            Schema::table('payment_gcash_details', function (Blueprint $table) {
                $table->dropForeign(['booking_id']);
                $table->dropColumn('booking_id');
            });
        }

        // 3. Make details table columns nullable to match validation logic
        Schema::table('details', function (Blueprint $table) {
            $table->unsignedBigInteger('accommodation_id')->nullable()->change();
            $table->unsignedBigInteger('itineraries_id')->nullable()->change();
            $table->unsignedBigInteger('list_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore CVV column (not recommended for security)
        if (!Schema::hasColumn('payment_card_details', 'cvv')) {
            Schema::table('payment_card_details', function (Blueprint $table) {
                $table->string('cvv')->after('expiry_date');
            });
        }

        // Restore booking_id in payment_gcash_details
        if (!Schema::hasColumn('payment_gcash_details', 'booking_id')) {
            Schema::table('payment_gcash_details', function (Blueprint $table) {
                $table->unsignedBigInteger('booking_id')->after('payment_id');
                $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
            });
        }

        // Make details columns non-nullable again
        Schema::table('details', function (Blueprint $table) {
            $table->unsignedBigInteger('accommodation_id')->nullable(false)->change();
            $table->unsignedBigInteger('itineraries_id')->nullable(false)->change();
            $table->unsignedBigInteger('list_id')->nullable(false)->change();
        });
    }
};
