<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DetailSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('details')->count() > 0) {
            return;
        }

        $faker = Faker::create();

        $planTripIds = DB::table('plan_trips')->pluck('plan_trip_id')->all();
        if (empty($planTripIds)) {
            return;
        }

        $accommodationIds = DB::table('accommodations')->pluck('accommodation_id')->all();
        $itineraryIds = DB::table('itineraries')->pluck('itineraries_id')->all();
        $packListIds = DB::table('pack_lists')->pluck('pack_list_id')->all();

        if (empty($accommodationIds) || empty($itineraryIds) || empty($packListIds)) {
            return;
        }

        $rows = [];
        foreach ($planTripIds as $ptId) {
            $count = rand(2, 4);
            for ($i = 0; $i < $count; $i++) {
                $rows[] = [
                    'plan_trip_id' => $ptId,
                    'accommodation_id' => $faker->randomElement($accommodationIds),
                    'itineraries_id' => $faker->randomElement($itineraryIds),
                    'list_id' => $faker->randomElement($packListIds),
                    'key' => $faker->randomElement(['note', 'tip', 'reminder', 'requirement']),
                    'value' => $faker->sentence(12),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('details')->insert($rows);
    }
}
