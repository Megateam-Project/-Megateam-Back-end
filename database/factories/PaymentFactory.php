<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
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
            'payment_method' => fake()-> randomElement(['Tiền mặt', 'Momo']),
            'date' => fake() -> dateTime(),
        ];
    }
}
