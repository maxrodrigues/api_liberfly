<?php

it('should be return error when email field is empty', function () {
    $res = $this->post(route('login'), [
        'email' => "",
        'password' => '1*Kg=4J3p',
    ])
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            'status' => 'error',
            'data' => [
                'email' => [
                    "The email field is required."
                ]
            ]
        ]);
});

it('login user', function () {
    $user = userData();
    $factoryUser = \App\Models\User::factory()->create($user);

    $this->post(route('login'), [
        'email' => $user['email'],
        'password' => '1*Kg=4J3p',
    ])
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_OK);

    $this->assertDatabaseCount('personal_access_tokens', 1);
    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $factoryUser['id'],
    ]);
});

it('should be return error when user is not found or is invalid credentials.', function () {
    $this->post(route('login'), [
        'email' => "testing@test.com",
        'password' => '1*Kg=4J3p',
    ])
        ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            'status' => 'error',
            'message' => "Invalid credentials"
        ]);

    $user = \App\Models\User::factory()->create([
        'password' => '1*Kg=4J3p',
    ]);

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => '1*Kg=4J3o',
    ])
    ->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            'status' => 'error',
            'message' => "Invalid credentials"
        ]);
});
