<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ResetUserController;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout' , [AuthController::class , 'logout']);
    Route::post('update' , [MedicineController::class , 'updateOneProduct']);
    Route::post('insert' , [MedicineController::class , 'store']);
    Route::post('co' , [OrderController::class , 'createOrder']);
});

Route::controller(AuthController::class) -> group(function(){
    Route::post('register' ,'register');
    Route::post('login' , 'login');
});

Route::controller(ResetUserController::class) -> group(function(){
    Route::post('forget' , 'forgotPassword');
    Route::post('check' , 'checkCode');
    Route::post('reset' , 'resetPassword');
});

Route::controller(MedicineController::class) -> group(function(){
    Route::post('show' , 'show');
    Route::post('getone' , 'getOneProduct');
    Route::post('search' , 'search');
});

Route::controller(OrderController::class) -> group(function(){
    Route::post('ups' , 'updatPaymentStatus');
    Route::post('uos' , 'updateOrderStatus');
});
