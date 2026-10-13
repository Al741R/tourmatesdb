<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('bookings')->count() > 0) {
            return;
        }

        $faker = Faker::create();
        $planTrips = DB::table('plan_trips')->select('plan_trip_id', 'start_date', 'end_date')->get();
        $accommodationIdsByPlanTrip = DB::table('accommodations')
            ->select('accommodation_id', 'plan_trip_id')
            ->get()
            ->groupBy('plan_trip_id');
        $userIds = DB::table('users')->pluck('id')->all();

        if ($planTrips->isEmpty()) {
            return;
        }

        $rows = [];
        foreach ($planTrips as $pt) {
            $accomCollection = $accommodationIdsByPlanTrip->get($pt->plan_trip_id) ?? collect();
            $bookingCount = rand(1, 2);
            for ($i = 0; $i < $bookingCount; $i++) {
                $checkIn = $pt->start_date ?: $faker->dateTimeBetween('+1 day', '+2 months')->format('Y-m-d');
                $nights = rand(1, 7);
                $checkOut = date('Y-m-d', strtotime("$checkIn +$nights days"));

                $pricePerNight = $faker->numberBetween(50, 300);
                $total = $pricePerNight * $nights;

                $rows[] = [
                    'plan_trip_id' => $pt->plan_trip_id,
                    'accommodation_id' => $accomCollection->isNotEmpty() ? $accomCollection->random()->accommodation_id : null,
                    'user_id' => !empty($userIds) ? $faker->randomElement($userIds) : null,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'number_of_people' => rand(1, 5),
                    'price_per_night' => $pricePerNight,
                    'number_of_nights' => $nights,
                    'total_amount' => $total,
                    'status' => $faker->randomElement(['pending', 'confirmed', 'cancelled']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('bookings')->insert($rows);
    }
}
