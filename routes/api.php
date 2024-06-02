<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AuthorController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// AUTHENTICATION
Route::controller(AuthController::class)->group(function() {
    Route::post('auth/register', 'register');
    Route::post('auth/login', 'login');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');



//BOOKS
Route::apiResource('book', BookController::class);
Route::get('booksearch', [BookController::class, 'search']);
Route::post('affect/{id}', [BookController::class, 'affectAuthors']);
Route::get('leaderbord', [BookController::class, 'leaderbord']);



//AUTHORS
Route::controller(AuthorController::class)->group(function(){
    Route::get('authors', 'index');
    Route::get('author/{id}', 'show');
    Route::get('authorsearch', 'search');
    Route::get('authorbooks/{id}', 'authorbooks');
});
Route::controller(AuthorController::class)->middleware('auth:sanctum')->group(function(){
    Route::post('/author', 'store');
    Route::put('/author/{id}', 'update');
    Route::delete('/author/{id}', 'destroy');
 });
