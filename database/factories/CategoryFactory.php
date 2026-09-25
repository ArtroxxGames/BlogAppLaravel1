<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->unique()->words(2, true)),
            'description' => fake()->sentence(),
            'is_featured' => false,
            'is_visible' => true,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => ['is_featured' => true]);
    }

    public function hidden(): static
    {
        return $this->state(fn (array $attributes) => ['is_visible' => false]);
    }
}
