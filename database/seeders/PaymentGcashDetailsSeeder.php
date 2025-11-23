<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PaymentGcashDetailsSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('payment_gcash_details')->count() > 0) {
            return;
        }

        $faker = Faker::create();
        $gcashPaymentIds = DB::table('payment_method')
            ->where('payment_options', 'gcash')
            ->pluck('payment_id', 'booking_id');

        if ($gcashPaymentIds->isEmpty()) {
            return;
        }

        $rows = [];
        foreach ($gcashPaymentIds as $bookingId => $paymentId) {
            $rows[] = [
                'payment_id' => $paymentId,
                'booking_id' => $bookingId,
                'gcash_number' => (string) $faker->numerify('09#########'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('payment_gcash_details')->insert($rows);
    }
}
