<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_id' => function(){
                return Room::factory()->create()->id;
            },
            'user_id' => function(){
                return User::factory()->create()->id;
            },
            'check_in_date' => fake()->dateTime(),
            'check_out_date' => fake()->dateTime(),
        ];
    }
}
