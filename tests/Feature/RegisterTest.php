<?php

use Illuminate\Support\Arr;

function registerUserData(): array
{
    return [
        'name' => fake()->name,
        'email' => fake()->email,
        'password' => '1*Kg=4J3p',
        'password_confirmation' => '1*Kg=4J3p',
    ];
}

it('return success and token when register new user', function () {
    $this->post(route('register'), registerUserData())
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_CREATED);

    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseCount('personal_access_tokens', 1);
});

it('return error when field name is missing', function () {
    $userData = registerUserData();
    Arr::forget($userData, 'name');

    $this->post(route('register'), $userData)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            'status' => 'error',
            'data' => [
                'name' => [
                    'The name field is required.',
                ]
            ]
        ]);
});
