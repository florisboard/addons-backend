<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
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
            'version_name' => rand(0, 3).'.'.rand(0, 9).'.'.rand(0, 9),
            'version_code' => rand(0, 1000),
            'downloads_count' => rand(0, 1_000_000),
            'status' => DatabaseSeeder::randomStatus(),
            'user_id' => User::factory(),
            'project_id' => Project::factory(),
        ];
    }
}
