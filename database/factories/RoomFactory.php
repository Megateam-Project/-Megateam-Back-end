<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $convenients = ['Wifi', 'Breakfast', 'Pool', 'Gym', 'Parking', 'Spa'];
        $convenientString = implode(', ', fake()->randomElements($convenients, fake()->numberBetween(1, count($convenients))));
        return [
            'name' => fake()->name,
            'type' => fake()->word,
            'price' => fake()->randomFloat(2, 0, 1000),
            'description' => fake()->text,
            'image' => fake()->imageUrl,
            'convenient' => $convenientString,
            'number' => fake()->numberBetween(100, 300),
            'discount' => fake()->randomFloat(2, 0, 1000),
        ];
    }
}
