<?php

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

Route::get('/users', [App\Http\Controllers\UsersController::class, 'index']);
Route::get('/orders', [App\Http\Controllers\OrdersController::class, 'index']);
Route::post('/store', [App\Http\Controllers\OrdersController::class, 'store']);
Route::get('/orders_detail/{id}', [App\Http\Controllers\OrdersController::class, 'show']);
Route::post('/orders/{id}/payment', [App\Http\Controllers\OrdersController::class, 'update']);