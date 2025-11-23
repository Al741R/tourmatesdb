<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('notifications')->count() > 0) {
            return;
        }

        $faker = Faker::create();
        $userIds = DB::table('users')->pluck('id')->all();
        if (empty($userIds)) {
            return;
        }

        $rows = [];
        for ($i = 0; $i < 20; $i++) {
            $rows[] = [
                'user_id' => $faker->randomElement($userIds),
                'message' => $faker->sentence(12),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('notifications')->insert($rows);
    }
}
