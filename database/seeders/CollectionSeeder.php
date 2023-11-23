<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all('id');
        $projects = Project::all('id');

        $users->each(function (User $user) use ($projects) {
            Collection::factory(rand(0, 5))
                ->hasAttached($projects->random())
                ->for($user)
                ->create();
        });
    }
}
