<?php

use App\Models\User;

function userData(): array
{
    return [
        'name' => fake()->name,
        'email' => fake()->safeEmail(),
        'password' => '1*Kg=4J3p',
    ];
}

it('add new user', function () {
    $user = userData();

    $this->post(route('add-user'), $user)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseHas('users', ['name' => $user['name']]);
});

it ('should be return error when field name is missing', function () {
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

it('should be return error when field email is missing', function () {
    $user = userData();
    \Illuminate\Support\Arr::forget($user, ['email']);

    $response = $this->post(route('add-user'), $user)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertExactJson([
            'status' => 'error',
            'data' => [
                'email' => ['The email field is required.'],
            ]
        ]);
});

it('should be return error when field password is missing', function () {
    $user = userData();
    \Illuminate\Support\Arr::forget($user, ['password']);

    $response = $this->post(route('add-user'), $user)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertExactJson([
            'status' => 'error',
            'data' => [
                'password' => ['The password field is required.'],
            ]
        ]);
});

it('should be return error when email is invalid', function () {
    $user = userData();
    $user['email'] = 'testing';

    $this->post(route('add-user'), $user)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertExactJson([
            'status' => 'error',
            'data' => [
                'email' => ['The email field must be a valid email address.'],
            ],
        ]);
});

it('should be return error when password is too short', function () {
    $user = userData();
    $user['password'] = '1mM@';
    $this->post(route('add-user'), $user)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertExactJson([
            'status' => 'error',
            'data' => [
                'password' => ['The password field must be at least 6 characters.'],
            ]
        ]);
});

it('should be return error when password doest have special chars, numbers and capital letters', function () {
    $user = userData();
    $user['password'] = 'password';
    $this->post(route('add-user'), $user)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertExactJson([
            'status' => 'error',
            'data' => [
                'password' => [
                    'The password field must contain at least one uppercase and one lowercase letter.',
                    'The password field must contain at least one symbol.',
                    'The password field must contain at least one number.',
                ]
            ]
        ]);
});

it('user email should be unique', function () {
    $userFactory = \App\Models\User::factory()->create();
    $user = userData();
    $user['email'] = $userFactory->email;

    $response = $this->post(route('add-user'), $user)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertExactJson([
            'status' => 'error',
            'data' => [
                'email' => ['The email has already been taken.'],
            ]
        ]);
});

it('password should be hashed', function () {
    $user = userData();
    $this->post(route('add-user'), $user);

    $savedUser = User::where('email', $user['email'])->first();
    $this->assertNotEquals($user['password'], $savedUser->password);
});

it('should be return only name and email user when save data', function () {
    $user = userData();

    $response = $this->post(route('add-user'), $user)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_CREATED);

    $response->assertExactJson([
        'status' => 'success',
        'data' => [
            'name' => $user['name'],
            'email' => $user['email'],
        ],
    ]);
});
