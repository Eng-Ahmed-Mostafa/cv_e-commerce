<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->optional()->randomElement(User::pluck('id')->toArray()), 
            'total' => $this->faker->randomFloat(2, 0, 2000), 
            'status' => $this->faker->randomElement(['active', 'completed', 'abandoned']),
            'session_id' => $this->faker->optional()->uuid(),
        ];
    }
}
