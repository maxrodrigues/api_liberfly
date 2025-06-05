<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', \App\Http\Controllers\Auth\LoginController::class)->name('login');
Route::post('auth/register', \App\Http\Controllers\Auth\RegisterController::class)->name('register');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users-list', \App\Http\Controllers\UsersListController::class)->name('users-list');
    Route::post('add-user', \App\Http\Controllers\AddUserController::class)->name('add-user');
    Route::get('user/{user}', \App\Http\Controllers\ListUserController::class)->name('get-user');
});
