<?php

namespace Database\Factories;

use App\Models\User;
use App\Services\DomainService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Domain>
 */
class DomainFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->domainName(),
            'verification_code' => rand(DomainService::MIN_VERIFICATION_CODE, DomainService::MAX_VERIFICATION_CODE),
            'verified_at' => fake()->boolean(70) ? fake()->dateTime() : null,
            'user_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the model's verified_at address should be now.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => now(),
        ]);
    }
}
