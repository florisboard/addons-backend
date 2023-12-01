<?php

use App\Models\Category;

uses()->group('Category');

test('users can get categories', function () {
    Category::factory(2)->create();

    $this->getJson(route('categories.index'))
        ->assertOk();
});

test('users can get an active category', function () {
    $category = Category::factory()->create(['is_active' => true]);

    $this->getJson(route('categories.show', [$category]))
        ->assertOk();
});

test('users cannot get an inactive blog category', function () {
    $category = Category::factory()->create(['is_active' => false]);

    $this->getJson(route('categories.show', [$category]))
        ->assertNotFound();
});
