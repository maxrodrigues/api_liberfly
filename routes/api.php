<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('users-list', \App\Http\Controllers\UsersListController::class)->name('users-list');
Route::post('add-user', \App\Http\Controllers\AddUserController::class)->name('add-user');
