<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/login', 'UsersController@login')->name('login');

Route::get('auth/refresh', 'UsersController@refresh');
Route::get('auth/user', 'UsersController@user');

Route::put('book/toggle/{id}', 'BooksController@toggle')->middleware('auth:api');
Route::apiResource('book', 'BooksController')->middleware('auth:api');
Route::apiResource('category', 'CategoriesController')->middleware('auth:api');

