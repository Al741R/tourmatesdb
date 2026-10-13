<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AccommodationSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('accommodations')->count() > 0) {
            return;
        }

        $faker = Faker::create();
        $planTrips = DB::table('plan_trips')->select('plan_trip_id', 'destination_id')->get();
        if ($planTrips->isEmpty()) {
            return;
        }

        $rows = [];
        foreach ($planTrips as $pt) {
            $count = rand(1, 2);
            for ($i = 0; $i < $count; $i++) {
                $rows[] = [
                    'plan_trip_id' => $pt->plan_trip_id,
                    'destination_id' => $pt->destination_id,
                    'name' => $faker->company() . ' Hotel',
                    'address' => $faker->address(),
                    'category' => $faker->randomElement(['hotel', 'hostel', 'bnb', 'resort']),
                    'price' => $faker->randomFloat(2, 30, 800),
                    'room_type' => $faker->randomElement(['single', 'double', 'suite', 'family']),
                    'contact_number' => $faker->numberBetween(1000000, 999999999),
                    'review' => $faker->sentence(),
                    'rating' => $faker->randomFloat(2, 3, 5),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('accommodations')->insert($rows);
    }
}
