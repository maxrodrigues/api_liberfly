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

it('return error when field email is missing', function () {
    $userData = registerUserData();
    Arr::forget($userData, 'email');

    $this->post(route('register'), $userData)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            'status' => 'error',
            'data' => [
                'email' => [
                    'The email field is required.',
                ]
            ]
        ]);
});

it('return error when email already exists', function () {
    $registeredUser = \App\Models\User::factory()->create();
    $userData = registerUserData();
    $userData['email'] = $registeredUser->email;

    $this->post(route('register'), $userData)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            'status' => 'error',
            'data' => [
                'email' => [
                    'The email has already been taken.',
                ]
            ]
        ]);
});

it('return error when password is too short', function () {
    $userData = registerUserData();
    $userData['password'] = '1*K';
    $userData['password_confirmation'] = '1*K';

    $this->post(route('register'), $userData)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            'status' => 'error',
            'data' => [
                'password' => [
                    'The password field must be at least 8 characters.',
                    'The password field must contain at least one uppercase and one lowercase letter.',
                ]
            ]
        ]);
});

it('return error when password doest have special chars, numbers and capital letters', function () {
    $userData = registerUserData();
    $userData['password'] = 'password';
    $userData['password_confirmation'] = 'password';

    $this->post(route('register'), $userData)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
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

it('return error when password is not equals to password confirmation', function () {
    $userData = registerUserData();
    $userData['password'] = '1*Kg=4J3p';
    $userData['password_confirmation'] = '1*Kg@4J3p';

    $this->post(route('register'), $userData)
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            'status' => 'error',
            'data' => [
                'password' => [
                    'The password field confirmation does not match.',
                ]
            ]
        ]);
});
