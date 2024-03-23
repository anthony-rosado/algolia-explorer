<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
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
            'code' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'name' => Str::ucfirst($this->faker->words(2, true)),
            'description' => $this->faker->text(),
            'is_available' => $this->faker->boolean(),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'stock' => $this->faker->numberBetween(0, 100),
            'image_url' => $this->faker->optional()->imageUrl(),
        ];
    }

    public function unavailable(): self
    {
        return $this->state([
            'is_available' => false,
        ]);
    }
}
