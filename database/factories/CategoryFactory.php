<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        /* @var $name string */
        $name = fake()->unique()->words(3, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'is_active' => app()->runningUnitTests() || fake()->boolean(90),
            'is_top' => fake()->boolean(),
            'circle_bg' => fake()->safeHexColor(),
            'circle_fg' => fake()->safeHexColor(),
        ];
    }
}
