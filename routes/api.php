<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Rotas protegidas por autenticação JWT
Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('me', [AuthController::class, 'user']);

    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);


    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('books', BookController::class);

    Route::apiResource('loans', LoanController::class)->only(['index', 'store', 'show']);
    Route::post('/loans/{loan}/return', [LoanController::class, 'return']);
});
