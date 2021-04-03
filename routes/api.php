<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{UserController, CategoryController, ArticleController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });

    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('register', [UserController::class, 'register']);

    Route::get('category', [CategoryController::class, 'index']);
    Route::post('category', [CategoryController::class, 'create']);
    Route::put('category/{category:id}', [CategoryController::class, 'update']);
    Route::delete('category/{category:id}', [CategoryController::class, 'delete']);

    Route::get('article', [ArticleController::class, 'index']);
    Route::post('article', [ArticleController::class, 'create']);
    Route::put('article/{article:id}', [ArticleController::class, 'update']);
    Route::delete('article/{article:id}', [ArticleController::class, 'delete']);
});
