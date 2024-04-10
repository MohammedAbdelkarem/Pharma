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


Route::post('user/send' , [AuthenticateUserController::class , 'sendCode']);
Route::post('user/register' , [AuthenticateUserController::class , 'register']);
Route::post('user/login' , [AuthenticateUserController::class , 'login']);
Route::group(['prefix' => 'user' , 'middleware' => ['auth:user_api' , 'scopes:user']] , function(){
    Route::post('logout' , [AuthenticateUserController::class , 'logout']);
});
