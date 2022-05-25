<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'v1',
],function (){
    Route::apiResource('category', \App\Http\Controllers\V1\CategoryController::class);
    Route::apiResource('article', \App\Http\Controllers\V1\ArticleController::class);
    Route::apiResource('comment', \App\Http\Controllers\V1\CommentController::class)->only(['store','index','destroy']);
    Route::get('comment/by-article/{article}', [\App\Http\Controllers\V1\CommentController::class, 'byArticle']);
    Route::get('article/by-category/{category}', [\App\Http\Controllers\V1\ArticleController::class, 'byCategory']);
    Route::apiResource('user', \App\Http\Controllers\V1\Auth\UserController::class);
});

Route::group([
    'prefix' => 'auth',
], function (){
    Route::post('login', [\App\Http\Controllers\V1\Auth\AuthController::class, 'index']);
    Route::post('logout', [\App\Http\Controllers\V1\Auth\AuthController::class, 'destroy']);
});
