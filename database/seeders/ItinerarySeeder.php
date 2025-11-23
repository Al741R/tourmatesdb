<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ItinerarySeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('itineraries')->count() > 0) {
            return;
        }

        $faker = Faker::create();
        $planTripIds = DB::table('plan_trips')->pluck('plan_trip_id')->all();
        if (empty($planTripIds)) {
            return;
        }

        $rows = [];
        foreach ($planTripIds as $ptId) {
            $days = rand(3, 5);
            for ($d = 1; $d <= $days; $d++) {
                $rows[] = [
                    'plan_trip_id' => $ptId,
                    'day' => $d,
                    'title' => 'Day ' . $d . ' - ' . ucfirst($faker->words(2, true)),
                    'activity' => $faker->paragraph(),
                    'time' => $faker->time('H:i'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('itineraries')->insert($rows);
    }
}
