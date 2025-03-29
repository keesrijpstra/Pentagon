<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//create a route that will store the password that it will get crom a chrome extension
Route::middleware('auth:sanctum')->post('/store-password', [PasswordController::class, 'store']);
Route::middleware('auth:sanctum')->get('/get-passwords', [App\Http\Controllers\PasswordController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
