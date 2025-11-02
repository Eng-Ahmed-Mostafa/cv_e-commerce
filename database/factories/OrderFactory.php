<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'subtotal' => $this->faker->randomFloat(2, 20, 500),
            'tax' => $this->faker->randomFloat(2, 1, 50),
            'total' => $this->faker->randomFloat(2, 30, 550),
            'status' => $this->faker->randomElement(['pending', 'ordered']),
            'total_amount' => $this->faker->randomFloat(2, 30, 550),
            'ordered_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'delivered_date' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
            'user_id' => \App\Models\User::factory(),
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}
