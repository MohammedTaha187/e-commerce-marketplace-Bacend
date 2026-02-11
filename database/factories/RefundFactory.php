<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Refund>
 */
class RefundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_item_id' => \App\Models\OrderItem::factory(),
            'user_id' => \App\Models\User::factory(),
            'amount' => fake()->randomFloat(2, 10, 500),
            'reason' => fake()->sentence(),
            'status' => 'pending',
            'images' => null,
        ];
    }
}
