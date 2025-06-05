<?php

use App\Http\Controllers\{AddUserController, ListUserController, UsersListController};
use App\Http\Controllers\Auth\{LoginController, RegisterController};
use Illuminate\Support\Facades\Route;

Route::post('auth/login', LoginController::class)->name('login');
Route::post('auth/register', RegisterController::class)->name('register');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users-list', UsersListController::class)->name('users-list');
    Route::post('add-user', AddUserController::class)->name('add-user');
    Route::get('user/{user}', ListUserController::class)->name('get-user');
});
