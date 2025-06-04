<?php

use App\Models\User;

it ('list all users', function () {
    $users = User::factory()->count(50)->create();

    $this->get(route('users-list'))
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_OK)
        ->assertJson([
            'status' => 'success',
            'data' => $users->toArray(),
        ]);
});

todo('list only ten users by page');
