<?php

use App\Models\User;

test('users can logout', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('logout'))
        ->assertNoContent();

    $this->assertGuest();
});
