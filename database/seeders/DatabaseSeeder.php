<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Feedback;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RoomSeeder::class,
            PaymentSeeder::class,
            BookingSeeder::class,
            BillSeeder::class,
            FavoriteSeeder::class,
            FeedbackSeeder::class,
        ]);
    }
}
