<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Release>
 */
class ReleaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => fake()->realText(),
            'version' => rand(0, 3).'.'.rand(0, 9).'.'.rand(0, 9),
            'downloads_count' => fake()->numberBetween(0, 1_000_000),
        ];
    }
}
