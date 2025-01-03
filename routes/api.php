<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::prefix('/v1')->group(function () {
    Route::middleware('auth.jwt')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('/user', 'getUser');
            Route::post('/register', 'createUser')->withoutMiddleware('auth.jwt');
            Route::post('/logout', 'logout');
            Route::post('/login', 'login')->withoutMiddleware('auth.jwt');

        });
        Route::controller(NewsController::class)->group(function () {
            Route::prefix('/articles')->group(function () {
                Route::get('/', 'getArticles');
                Route::get('/authors', 'getArticlesAuthor');
                Route::get('/categories', 'getArticlesCategories');
                Route::get('/source', 'getArticlesSource');
                Route::get('/details/{id}', 'getArticlesSource');
            });
        });

        Route::controller(UserPreferenceController::class)->group(function () {
            Route::prefix('/preference')->group(function () {
                Route::get('/', 'getUserPreference');
                Route::put('/', 'updatePreference');
            });
        });
    });

});

