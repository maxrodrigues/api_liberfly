<?php

use App\Models\User;

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

it('return error when field email is missing', function () {
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

it('return error when field password is missing', function () {
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
