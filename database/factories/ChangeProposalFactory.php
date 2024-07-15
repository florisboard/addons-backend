<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChangeProposal>
 */
class ChangeProposalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => StatusEnum::randomValue(),
            'reviewer_description' => fake()->boolean() ? fake()->paragraphs(rand(1, 3), true) : null,
        ];
    }
}
