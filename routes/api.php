<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{GeneralController , Auth\AuthenticateGeneralController};

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
    Route::get('getcat' , [GeneralController::class , 'getCategories']);
    Route::get('getadmins' , [GeneralController::class , 'getAdmins']);
    Route::post('getmedad' , [GeneralController::class , 'getMedicianesByAdmin']);
    Route::post('getmeddetails' , [GeneralController::class , 'getMedicineDetails']);
    Route::post('getorddetails' , [GeneralController::class , 'getOrderDetails']);
});
