<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách các Room
        $rooms = Room::all();

        // Lấy danh sách các User
        $users = User::all();

        Feedback::factory(5)
            ->create([
                'id' => $rooms->random()->id,
                'id' => $users->random()->id,
            ]);
    }
}
