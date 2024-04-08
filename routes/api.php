<?php

use App\Http\Controllers\AuthenticateGeneralController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticateUserController;

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
Route::post('general/check' , [AuthenticateGeneralController::class , 'checkCode']);
Route::group(['prefix' => 'general' , 'middleware' => ['general']] , function(){
    
});
