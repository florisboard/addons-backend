<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->words(3, true),
            'is_active' => app()->runningUnitTests() || fake()->boolean(90),
            'is_top' => fake()->boolean(),
            'circle_bg' => fake()->safeHexColor(),
            'circle_fg' => fake()->safeHexColor(),
        ];
    }
}
