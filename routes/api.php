<?php

use App\Http\Controllers\VehiculeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {

    return $request->user();
});

//vehicules00
Route::post('AddCar',[VehiculeController::class, 'StoreCar']);
Route::get('ViewCar',[VehiculeController::class, 'viewVehicule']);
Route::delete('DeleteCar/{id}',[VehiculeController::class, 'deleteCar']);
Route::post('updateCare/{id}',[VehiculeController::class, 'updateCar']);
//insertion des galerie des vehicules


 Route::post('register', [SuperAdminController::class, 'register']);
 Route::post('updateUser', [SuperAdminController::class, 'updateUser']);
 Route::post('deleteUser', [SuperAdminController::class, 'deleteUser']);
 Route::get('CountUser', [SuperAdminController::class, 'CountUser']);
 Route::post('Reservation/{id}', [SuperAdminController::class, 'reservation']);

 Route::get('/users', [SuperAdminController::class, 'users']);
 Route::post('/updateUser/{id}', [SuperAdminController::class, 'updateUser']);
 Route::delete('/deleteUser/{id}', [SuperAdminController::class, 'deleteUser']);
 Route::get('/user/{id}', [SuperAdminController::class, 'show']);
