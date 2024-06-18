<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Maintainer;
use App\Models\Project;
use App\Models\Release;
use App\Models\Report;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all('id');
        $categories = Category::all('id');

        for ($i = 0; $i < 20; $i++) {
            $ownerId = $users->random()->id;

            $project = Project::factory()
                ->has(
                    Report::factory(3)
                        ->sequence(fn () => ['user_id' => User::all()->random()->id])
                )
                ->create([
                    'category_id' => $categories->random()->id,
                    'user_id' => $ownerId,
                ]);

            $maintainerIds = $users
                ->reject(fn (User $user) => $user->id === $ownerId)
                ->random(rand(0, 5))
                ->map(fn (User $user) => ['user_id' => $user->id])
                ->toArray();

            Maintainer::factory(count($maintainerIds))
                ->for($project)
                ->forEachSequence(...$maintainerIds)
                ->create();

            Release::factory(rand(0, 10))
                ->for($project)
                ->sequence(fn (Sequence $sequence) => [
                    'user_id' => collect([$ownerId, ...$maintainerIds])->flatten()->random(),
                    'version_code' => $sequence->index + 1,
                    'version_name' => $sequence->index + 1 .'.0.0',
                ])
                ->create();

            $reviewUsers = $users->random(rand(0, 10))
                ->map(fn (User $user) => ['user_id' => $user->id])
                ->toArray();

            Review::factory(count($reviewUsers))
                ->for($project)
                ->has(
                    Report::factory(3)
                        ->sequence(fn () => ['user_id' => User::all()->random()->id])
                )
                ->forEachSequence(...$reviewUsers)
                ->create();
        }
    }
}
