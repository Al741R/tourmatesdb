<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('destinations')->count() > 0) {
            return;
        }

        $faker = Faker::create();
        $rows = [];
        for ($i = 0; $i < 10; $i++) {
            $rows[] = [
                'name' => ucfirst($faker->words(2, true)),
                'city' => $faker->city(),
                'address' => $faker->address(),
                'details' => $faker->paragraph(),
                'category' => $faker->randomElement(['beach', 'mountain', 'city', 'cultural', 'nature']),
                'rating' => $faker->randomFloat(2, 3, 5),
                'reviews' => $faker->sentence(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('destinations')->insert($rows);
    }
}
