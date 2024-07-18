<?php

use App\Http\Controllers\VehiculeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

//vehicules00
Route::post('AddCar',[VehiculeController::class, 'StoreCar']);
Route::get('ViewCar',[VehiculeController::class, 'viewVehicule']);
Route::delete('DeleteCar/{id}',[VehiculeController::class, 'deleteCar']);
Route::post('updateCare/{id}',[VehiculeController::class, 'updateCar']);
