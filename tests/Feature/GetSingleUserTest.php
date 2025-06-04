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

todo('should be return error when user is not authenticated');
todo('should be return error when user is not found');
