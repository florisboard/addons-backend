<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->uuid(),
            'name' => fake()->word(),
            'file_name' => fake()->word(),
            'mime_type' => fake()->mimeType(),
            'disk' => fake()->word(),
            'conversations_disk' => fake()->word(),
            'size' => int(1_000,10_000),
        ];
    }
}
