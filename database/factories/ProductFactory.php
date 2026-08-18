<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => $this->faker->word(),
            'slug' => $this->faker->unique()->slug(),
            "price" => $this->faker->numberBetween(1000, 9000),
            "image" => $this->faker->imageUrl(),
            "images" =>  [
                $this->faker->imageUrl(),
                $this->faker->imageUrl(),
            ],
            "short_description" => $this->faker->sentence(),
            "description" => $this->faker->paragraph(),
            "sale_price" => $this->faker->numberBetween(1000, 9000),
            "SKU" => $this->faker->bothify('SKU-#####'),
            "feature" => $this->faker->boolean(),
            "quantity" => $this->faker->numberBetween(1, 100),
            "stock" => $this->faker->randomElement(['instock', 'outstock']),
            "category_id" => \App\Models\Category::factory(),
            "brand_id" => \App\Models\Brand::factory(),
        ];
    }
}
