<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Rotas protegidas por autenticação JWT
Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
});


Route::apiResource('authors', AuthorController::class);
Route::apiResource('books', BookController::class);


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
