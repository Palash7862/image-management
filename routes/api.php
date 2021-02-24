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

Route::group(['middleware' => 'auth:sanctum'], function () { 
    Route::get('/user', 'Api\Auth\AuthController@currentUser');

    Route::get('/image', 'Api\DownloadImageController@getAllImages');
    Route::post('/image/store', 'Api\DownloadImageController@store'); 
});

// Auth Routes
Route::post('/register', 'Api\Auth\AuthController@register');
Route::post('/login', 'Api\Auth\AuthController@login');
Route::post('/logout', 'Api\Auth\AuthController@logout')->middleware('auth:sanctum');
