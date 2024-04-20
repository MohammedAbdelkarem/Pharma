<?php

use App\Http\Controllers\Admin\AdminMedicineController;
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

Route::post('admin/send' , [AuthenticateAdminController::class , 'sendCode']);
Route::post('admin/register' , [AuthenticateAdminController::class , 'register']);
Route::post('admin/login' , [AuthenticateAdminController::class , 'login']);
Route::group(['prefix' => 'admin' , 'middleware' => ['auth:admin_api' , 'scopes:admin']] , function(){
    Route::post('logout' , [AuthenticateAdminController::class , 'logout']);
    Route::post('edit' , [AuthenticateAdminController::class , 'editInformation']);
    Route::post('add' , [AdminMedicineController::class , 'addMedicine']);
    Route::post('update' , [AdminMedicineController::class , 'updateMedicine']);
    Route::post('delete' , [AdminMedicineController::class , 'deleteMedincine']);
    Route::get('getempty' , [AdminMedicineController::class , 'getEmptyQuantities']);
    Route::get('getmed' , [AdminMedicineController::class , 'getAllMedicines']);
});
