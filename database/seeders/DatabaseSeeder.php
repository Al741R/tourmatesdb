<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Idempotent seed for a known test user
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'location' => '123 Main St, Anytown, USA',
                'first_name' => 'Test',
                'last_name' => 'User',
            ]
        );

        // Idempotent seed for an admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'location' => 'HQ',
                'first_name' => 'System',
                'last_name' => 'Administrator',
            ]
        );

        User::factory(10)->create();
        // Additional random users
        User::factory(5)->create();

        // Seed remaining domain tables in dependency-safe order
        $this->call([
            DestinationSeeder::class,
            PlanTripSeeder::class,
            AccommodationSeeder::class,
            ItinerarySeeder::class,
            PackListSeeder::class,
            BookingSeeder::class,
            PaymentMethodSeeder::class,
            PaymentCardDetailsSeeder::class,
            PaymentGcashDetailsSeeder::class,
            DetailSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
