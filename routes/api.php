<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
   
    return $request->user();
});
// Route::post('/register', [RegisteredUserController::class, 'store'])
//                 ->middleware('guest')
//                 ->name('register');

 Route::post('register', [SuperAdminController::class, 'register']);
 Route::post('updateUser', [SuperAdminController::class, 'updateUser']);
 Route::post('deleteUser', [SuperAdminController::class, 'deleteUser']);