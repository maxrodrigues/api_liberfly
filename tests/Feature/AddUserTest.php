<?php

function userData(): array
{
    return [
        'name' => fake()->name,
        'email' => fake()->safeEmail(),
        'password' => 'password',
    ];
}

it('add new user', function () {
    $user = userData();

    $this->post(route('add-user'), $user)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseHas('users', ['name' => $user['name']]);
});

it ('return error when field name is missing', function () {
    $user = userData();
    \Illuminate\Support\Arr::forget($user, ['name']);

    $response = $this->post(route('add-user'), $user)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertExactJson([
            'status' => 'error',
            'data' => [
                'name' => ['The name field is required.'],
            ]
        ]);
});
todo('return error when field email is missing');
todo('user email should be unique');
