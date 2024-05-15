<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\User\{UserMedicineController , UserOrderController};

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

    Route::group(['prefix' => 'medicine'], function () {
        Route::post('addtofav' , [UserMedicineController::class , 'addMedicineToFavourites']);
        Route::get('getfav' , [UserMedicineController::class , 'getFavourites']);
        Route::post('search' , [UserMedicineController::class , 'searchForMedicine']);
        Route::post('getmedcat' , [UserMedicineController::class , 'getMedicinesByCategory']);
        Route::post('getmedcatad' , [UserMedicineController::class , 'getMedicinesByCategoryAdmin']);
    });

    Route::group(['prefix' => 'order'], function () {
        Route::post('createsuborder' , [UserOrderController::class , 'createSubOrder']);
        Route::post('deleteorder' , [UserOrderController::class , 'deleteOrder']);
        Route::post('deletesuborder' , [UserOrderController::class , 'deleteSubOrder']);
        Route::post('submit' , [UserOrderController::class , 'submitOrder']);
        Route::get('get' , [UserOrderController::class , 'getOrders']);
    });
});
