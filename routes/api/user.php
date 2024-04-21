<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticateUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['prefix' => 'user/auth'], function () {
    Route::post('send' , [AuthenticateUserController::class , 'sendCode']);
    Route::post('register' , [AuthenticateUserController::class , 'register']);
    Route::post('login' , [AuthenticateUserController::class , 'login']);
});

Route::group(['prefix' => 'user' , 'middleware' => ['auth:user_api' , 'scopes:user']] , function(){
    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout' , [AuthenticateUserController::class , 'logout']);
        Route::post('edit' , [AuthenticateUserController::class , 'editInformation']);
    });
});
