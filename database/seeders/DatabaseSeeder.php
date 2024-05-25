<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public static function fakeImage($with = 640, $height = 480): string
    {
        $imageId = fake()->randomNumber(1, 1000);

        return "https://picsum.photos/seed/$imageId/$with/$height";
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(20)->create();
        Category::factory(20)->create();

        $this->call([
            ProjectSeeder::class,
            CollectionSeeder::class,
        ]);
    }
}
