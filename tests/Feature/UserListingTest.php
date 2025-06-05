<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;

it('only authenticated users can access', function () {
    $this->get(route('users-list'))
        ->assertStatus(302);
});

it ('list all users', function () {
    Sanctum::actingAs(
        User::factory()->create()
    );
    User::factory()->count(50)->create();

    $this->get(route('users-list'))
        ->assertStatus(Response::HTTP_OK);
});

todo('list only ten users by page');
