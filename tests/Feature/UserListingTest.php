<?php

use App\Models\User;

it ('list all users', function () {
    $users = User::factory()->count(50)->create();

    $response = $this->get(route('users-list'));
    $response->assertStatus(200);
    $response->assertExactJson([
        'status' => 'success',
        'data' => $users->toArray(),
    ]);
});

todo('list only ten users by page');
