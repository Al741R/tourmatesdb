<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PlanTripSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('plan_trips')->count() > 0) {
            return;
        }

        $faker = Faker::create();
        $destinationIds = DB::table('destinations')->pluck('destination_id')->all();
        if (empty($destinationIds)) {
            return;
        }

        $userIds = DB::table('users')->pluck('id')->all();
        $rows = [];
        for ($i = 0; $i < 15; $i++) {
            $start = $faker->dateTimeBetween('+1 day', '+2 months');
            $end = (clone $start)->modify('+' . $faker->numberBetween(2, 10) . ' days');
            $rows[] = [
                'accommodation_id' => null,
                'title' => ucfirst($faker->words(3, true)),
                'description' => $faker->paragraph(),
                'start_date' => $start->format('Y-m-d'),
                'end_date' => $end->format('Y-m-d'),
                'destination_id' => $faker->randomElement($destinationIds),
                'user_id' => !empty($userIds) ? $faker->randomElement($userIds) : null,
                'price' => $faker->randomFloat(2, 100, 5000),
                'category' => $faker->randomElement(['budget', 'standard', 'luxury']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('plan_trips')->insert($rows);
    }
}
