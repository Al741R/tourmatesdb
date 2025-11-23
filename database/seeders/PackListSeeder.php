<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PackListSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('pack_lists')->count() > 0) {
            return;
        }

        $faker = Faker::create();
        $planTripIds = DB::table('plan_trips')->pluck('plan_trip_id')->all();
        if (empty($planTripIds)) {
            return;
        }

        $rows = [];
        foreach ($planTripIds as $ptId) {
            $count = rand(3, 6);
            for ($i = 0; $i < $count; $i++) {
                $rows[] = [
                    'plan_trip_id' => $ptId,
                    'list_title' => $faker->randomElement(['Essentials', 'Clothing', 'Gadgets', 'Toiletries', 'Misc']),
                    'item' => $faker->randomElement(['Passport', 'Sunscreen', 'Camera', 'Charger', 'Jacket', 'Hat', 'Sneakers', 'Water Bottle']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('pack_lists')->insert($rows);
    }
}
