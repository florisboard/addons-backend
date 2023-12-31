<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create(['email' => 'admin@email.com', 'is_admin' => true]);
        User::factory(20)->create();
        Category::factory(20)->create();

        $this->call([
            ProjectSeeder::class,
            CollectionSeeder::class,
        ]);
    }
}
