<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Maintainer;
use App\Models\Project;
use App\Models\Release;
use App\Models\Review;
use App\Models\User;
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

        for ($i = 0; $i < 50; $i++) {
            $ownerId = $users->random()->id;

            $project = Project::factory()
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
                ->sequence(fn () => ['user_id' => collect([$ownerId, ...$maintainerIds])->flatten()->random()])
                ->create();

            $reviewUsers = $users->random(rand(0, 10))
                ->map(fn (User $user) => ['user_id' => $user->id])
                ->toArray();

            Review::factory(count($reviewUsers))
                ->for($project)
                ->forEachSequence(...$reviewUsers)
                ->create();
        }
    }
}
