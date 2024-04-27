<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bill>
 */
class BillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id' => function(){
                return Booking::factory()->create()->id;
            },
            'total_price' => fake()->randomFloat(2,0,1000),
            'date' => fake()->dateTime(),
        ];
    }
}
