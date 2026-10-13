<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('payment_method')->count() > 0) {
            return;
        }

        $faker = Faker::create();
        $bookingIds = DB::table('bookings')->pluck('booking_id')->all();
        if (empty($bookingIds)) {
            return;
        }

        $rows = [];
        foreach ($bookingIds as $bookingId) {
            $rows[] = [
                'booking_id' => $bookingId,
                'payment_options' => $faker->randomElement(['card', 'gcash']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('payment_method')->insert($rows);
    }
}
