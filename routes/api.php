<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//create a route that will store the password that it will get crom a chrome extension
Route::post('/store-password', [App\Http\Controllers\PasswordController::class, 'store']);
Route::get('/get-passwords', [App\Http\Controllers\PasswordController::class, 'index']);
