<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Category;
use App\Models\ChangeProposal;
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
    private array $changeProposalFields = ['title', 'description', 'short_description', 'links'];

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

            $getRandomMaintainerId = fn () => collect([$ownerId, ...$maintainerIds])->flatten()->random();

            ChangeProposal::factory(rand(0, 4))
                ->for($project, 'model')
                ->sequence(fn () => [
                    'user_id' => $getRandomMaintainerId(),
                    'data' => Project::factory()->make()->only($this->changeProposalFields),
                ])
                ->create();

            // The last change proposal must match the current model data
            $project->changeProposals()->create([
                'status' => StatusEnum::Approved,
                'user_id' => $getRandomMaintainerId(),
                'data' => $project->only($this->changeProposalFields),
            ]);

            Release::factory(rand(0, 10))
                ->for($project)
                ->sequence(fn (Sequence $sequence) => [
                    'user_id' => $getRandomMaintainerId(),
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
