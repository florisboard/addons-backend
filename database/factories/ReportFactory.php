<?php

namespace Database\Factories;

use App\Enums\ReportTypeEnum;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => ReportTypeEnum::randomValue(),
            'status' => DatabaseSeeder::randomStatus(),
            'description' => fake()->realText(),
        ];
    }
}
