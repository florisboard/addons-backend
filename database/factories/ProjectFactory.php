<?php

namespace Database\Factories;

use App\Enums\ProjectTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'package_name' => Str::reverse(fake()->unique()->domainName()).'.'.fake()->word(),
            'type' => ProjectTypeEnum::randomValue(),
            'description' => fake()->realText(rand(200, 600)),
            'home_page' => fake()->boolean() ? fake()->url() : null,
            'support_email' => fake()->boolean() ? fake()->email() : null,
            'support_site' => fake()->boolean() ? fake()->url() : null,
            'donate_site' => fake()->boolean() ? fake()->url() : null,
            'is_recommended' => fake()->boolean(30),
        ];
    }
}
