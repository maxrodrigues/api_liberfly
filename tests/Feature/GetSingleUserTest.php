<?php

it('get user', function () {
    $user = \App\Models\User::factory()->create();
    $this->get(route('get-user', ['user' => 1]))
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_OK)
        ->assertJson([
            'status' => 'success',
            'data' => [
                'name' => $user['name'],
                'email' => $user['email'],
            ]
        ]);
});

it('should be return error when user is not authenticated', function () {
    \App\Models\User::factory()->count(4)->create();
    $this->get(route('get-user', ['user' => 1]))
        ->assertStatus(403);
})->skip();

it('should be return error when user is not found', function () {
    \App\Models\User::factory()->create();
    $this->get(route('get-user', ['user' => 99]))
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND)
        ->assertJson([
            'status' => 'error',
            'message' => 'User not found'
        ]);
});
