<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
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

        Project::factory(50)
            ->sequence(fn () => ['category_id' => $categories->random()->id, 'user_id' => $users->random()->id])
            ->create();
    }
}
