<?php

namespace Database\Factories;

use App\Enums\ProjectTypeEnum;
use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Exceptions\UnreachableUrl;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *
     * @throws \JsonException
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->words(rand(3, 6), true),
            'package_name' => Str::reverse(fake()->unique()->domainName()).'.'.fake()->word(),
            'type' => ProjectTypeEnum::randomValue(),
            'description' => fake()->realText(rand(200, 600)),
            'short_description' => fake()->realText(rand(50, 200)),
            'links' => [
                'home_page' => fake()->boolean() ? fake()->url() : null,
                'support_email' => fake()->boolean() ? fake()->email() : null,
                'support_site' => fake()->boolean() ? fake()->url() : null,
                'donate_site' => fake()->boolean() ? fake()->url() : null,
            ],
            'is_recommended' => fake()->boolean(30),
            'is_active' => app()->runningUnitTests() || fake()->boolean(90),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
        ];
    }

    public function configure(): ProjectFactory
    {
        return $this->afterCreating(function (Project $project) {
            if (app()->runningUnitTests() || app()->isLocal()) {
                return;
            }
            try {
                $project
                    ->addMediaFromUrl(DatabaseSeeder::fakeImage(300, 200))
                    ->toMediaCollection('image');

                for ($i = 0; $i < rand(2, 4); $i++) {
                    $project
                        ->addMediaFromUrl(DatabaseSeeder::fakeImage(500, 250))
                        ->toMediaCollection('screenshots');
                }
            } catch (UnreachableUrl $exception) {
                return;
            }
        });
    }
}
