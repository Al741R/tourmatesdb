<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PaymentCardDetailsSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('payment_card_details')->count() > 0) {
            return;
        }

        $faker = Faker::create();
        $cardPaymentIds = DB::table('payment_method')
            ->where('payment_options', 'card')
            ->pluck('payment_id', 'booking_id');

        if ($cardPaymentIds->isEmpty()) {
            return;
        }

        $userIds = DB::table('users')->pluck('id')->all();
        $rows = [];
        foreach ($cardPaymentIds as $bookingId => $paymentId) {
            $rows[] = [
                'user_id' => !empty($userIds) ? $faker->randomElement($userIds) : 1,
                'card_name' => $faker->name(),
                'card_number' => $faker->creditCardNumber(),
                'expiry_date' => $faker->creditCardExpirationDateString(),
                'cvv' => (string) $faker->numberBetween(100, 999),
                'postal_code' => (string) $faker->numberBetween(1000, 9999),
                'payment_id' => $paymentId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('payment_card_details')->insert($rows);
    }
}
