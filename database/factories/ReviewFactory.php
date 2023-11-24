<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(rand(1, 7), true),
            'description' => fake()->realText(),
            'score' => rand(1, 5),
            'is_anonymous' => fake()->boolean(),
            'deleted_at' => fake()->boolean(20) ? fake()->dateTime() : null,
        ];
    }
}
