<?php

use App\Http\Controllers\Admin\AdminMedicineController;
use App\Http\Controllers\Admin\AdminOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticateAdminController;

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


Route::group(['prefix' => 'admin/auth'], function () {
    Route::post('send' , [AuthenticateAdminController::class , 'sendCode']);
    Route::post('register' , [AuthenticateAdminController::class , 'register']);
    Route::post('login' , [AuthenticateAdminController::class , 'login']);
});
Route::group(['prefix' => 'admin' , 'middleware' => ['auth:admin_api' , 'scopes:admin']] , function(){

    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout' , [AuthenticateAdminController::class , 'logout']);
        Route::post('edit' , [AuthenticateAdminController::class , 'editInformation']);
    });
    
    Route::group(['prefix' => 'medicine'], function () {
        Route::post('add' , [AdminMedicineController::class , 'addMedicine']);
        Route::post('update' , [AdminMedicineController::class , 'updateMedicine']);
        Route::post('delete' , [AdminMedicineController::class , 'deleteMedincine']);
        Route::get('getempty' , [AdminMedicineController::class , 'getEmptyQuantities']);
    });

    Route::group(['prefix' => 'order'], function () {
        Route::get('getorders' , [AdminOrderController::class , 'getCurrentOrders']);
        Route::post('updateorder' , [AdminOrderController::class , 'modifyOrderStatus']);
        Route::post('updatepayment' , [AdminOrderController::class , 'modifyPaymentStatus']);
        Route::post('getarch' , [AdminOrderController::class , 'getArchivedOrders']);
        Route::get('getcustomers' , [AdminOrderController::class , 'getCustomers']);
        Route::post('getcustomerorders' , [AdminOrderController::class , 'getCustomerOrders']);
    });
});
